<?php
/**
 * Plugin Name: Registration Feature
 * Description: Extended plugin
 * Version: 1.0
 * Author: Jack Ananchenko
 *
 * Text Domain: registration-feature
 * Domain Path: /i18n/languages/
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ReyRegFeature' ) ) :

final class ReyRegFeature {

	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	private function init_hooks() {

		register_activation_hook( 	__FILE__, array( 'ReyReg_Install', 'install' ) );
		register_deactivation_hook( __FILE__, array( 'ReyReg_Install', 'un_install' ) );

		add_action( 'init', array( $this, 'init' ), 100 );
	}

	private function define_constants() {
		$this->define( 'REYREG_ABSPATH', dirname( __FILE__ ) . '/' );
		$this->define( 'REYREG_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
	}

	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	public function includes() {
		
		include_once( REYREG_ABSPATH . 'includes/class-reyreg-activation-codes.php' );
		include_once( REYREG_ABSPATH . 'includes/class-reyreg-install.php' );
		include_once( REYREG_ABSPATH . 'includes/class-woocommerce-rewrite.php' );
		include_once( REYREG_ABSPATH . 'includes/class-registration-check.php' );

		include_once( REYREG_ABSPATH . 'includes/class-reyreg-actions-filters.php' );

		if ( $this->is_request( 'admin' ) ) {
			include_once( REYREG_ABSPATH . 'includes/admin/class-reyreg-admin.php' );
		}
	}

	public function init() {

		new RewriteWooCommerce();
		new RegistrationCheck();

		// Classes/actions loaded for the frontend and for ajax requests.
		if ( $this->is_request( 'frontend' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'rey_reg_scripts' ) );
		}

		if ( $this->is_request( 'admin' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'rey_reg_admin_scripts' ) );
		}
	}

	public function rey_reg_scripts() {
		// Css
		wp_enqueue_style( 'rey-reg-style', REYREG_PLUGIN_DIR_URL . 'css/style.css' );
		// Js
		wp_enqueue_script( 'rey-reg-registration', REYREG_PLUGIN_DIR_URL . 'js/registration.js', array('jquery'), '', true );
	}

	public function rey_reg_admin_scripts() {
		// Css
		wp_enqueue_style( 'rey-reg-admin-style', REYREG_PLUGIN_DIR_URL . 'css/admin-style.css' );
	}
}

endif;

ReyRegFeature::instance();
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ReyReg_Install {

	public static function install() {

		if ( ! is_blog_installed() ) {
			return;
		}

		//self::create_tables();
		self::set_options();
	}

	public static function un_install() {

		self::delete_options();
	}

	private static function create_tables() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( self::get_schema() );
	}

	private static function get_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
	}

	private static function set_options() {
		add_option( 'reyreg_activation_codes', array(), '', 'no' );
	}

	private static function delete_options() {
		delete_option( 'reyreg_activation_codes' );
	}
}
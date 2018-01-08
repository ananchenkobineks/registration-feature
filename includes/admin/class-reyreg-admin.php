<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ReyReg_Admin {

	public function __construct() {
		
		add_action( 'init', array( $this, 'includes' ) );
	}

	public function includes() {

		include_once( dirname( __FILE__ ) . '/class-reyreg-admin-menus.php' );
	}

}

return new ReyReg_Admin();
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ReyReg_Admin_Menus {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_register_generate_codes_setting' ) );
	}

	public function admin_menu() {

		add_menu_page( __( 'Invitation Codes', 'rey_reg_feature' ), __( 'Invitation Codes', 'rey_reg_feature' ), 'manage_options', 'invitation_codes', array( $this, 'invitation_list' ), 'dashicons-universal-access-alt', 30 );
		add_submenu_page( 'invitation_codes', __( 'Generate Codes', 'rey_reg_feature' ), __( 'Generate Codes', 'rey_reg_feature' ), 'manage_options', 'generate_codes', array( $this, 'generate_codes_page' ) );
	}	

	public function invitation_list() {
		ReyReg_Activation_Codes::invitation_list_table();
	}

	public function generate_codes_page() {

		ReyReg_Activation_Codes::output_generate_codes_fields();
	}

	public function admin_register_generate_codes_setting() {

		ReyReg_Activation_Codes::generate_codes_register_fields();
	}
}

return new ReyReg_Admin_Menus();
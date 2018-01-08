<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RegistrationCheck {
	
	public function __construct() {
		// Add actiovation code field
		add_action( 'bp_account_details_fields', array( $this, 'activation_code_field' ) ); 
		// Add buying button
		add_action( 'bp_before_registration_submit_buttons', array( $this, 'add_buying_button' ) );
		// Add activation errors
		add_action( 'bp_signup_validate', array( $this, 'validate_singup' ) );

		// Auto activate user after register
		add_filter( 'bp_core_signups_add', array( $this, 'auto_approve_user' ) );

		// Remove code from DB on complete signup
		add_action( 'bp_complete_signup', array( $this, 'complete_signup' ) );

		add_action( 'template_redirect', array( $this, 'login_user_after_registration' ) );
	}

	public function activation_code_field() {
		include_once( REYREG_ABSPATH . 'templates/template-registration-field.php' );
	}

	public function add_buying_button() {
		include_once( REYREG_ABSPATH . 'templates/template-buy-button.php' );	
	}

	public function validate_singup() {
	 	global $bp;

	 	$validate = false;

	 	if( !empty($_POST['activation_code']) ) {
	 		$validate = ReyReg_Activation_Codes::validate_activation_code( $_POST['activation_code'] );
	 	}

	 	if( $validate == false )
			$bp->signup->errors['activation_code'] = __( 'برجاء مراجعة رمز الدخول الخاص بك', 'rey_reg_feature' );	
	}

	public function auto_approve_user( $retval ) {

		if( $retval ) {
			BP_Signup::activate( array($retval) );
		}

		return $retval;
	}

	public function complete_signup() {

		ReyReg_Activation_Codes::remove_activation_code( $_POST['activation_code'] );

		$redirect_url = get_site_url()."/?register=true&user_login={$_POST['signup_username']}&user_pass={$_POST['signup_password']}";
		wp_redirect( $redirect_url );
		exit;
	}

	public function login_user_after_registration() {

		if( !empty($_GET['register']) && !empty($_GET['user_login']) && !empty($_GET['user_pass']) ) {

			$creds = array(
		        'user_login'    => $_GET['user_login'],
		        'user_password' => $_GET['user_pass'],
		        'remember'      => true
		    );

		    wp_signon( $creds, false );

		    $registered_user = get_user_by( 'login', $_GET['user_login'] );
		    wp_redirect( bp_core_get_user_domain( $registered_user->ID ) );
    		exit;
		}
	}

}
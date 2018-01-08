<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ReyReg_Activation_Codes {

	public static function generate_codes_register_fields() {

		register_setting( 'generate_rand_code', 'code_prefix', array( __CLASS__, 'save_rand_code' ) );

		add_settings_section(
			'rand_code_section',
			__( 'Add auto generated codes', 'rey_reg_feature' ),
			'__return_false',
			'generate_rand_code'
		);
		add_settings_field(
			'code_prefix',
			__( 'Prefix', 'rey_reg_feature' ),
			array( __CLASS__, 'code_prefix_func' ),
			'generate_rand_code',
			'rand_code_section'
		);
		add_settings_field(
			'code_length',
			__( 'Length', 'rey_reg_feature' ),
			array( __CLASS__, 'code_length_func' ),
			'generate_rand_code',
			'rand_code_section'
		);
		add_settings_field(
			'code_howmany',
			__( 'How many codes', 'rey_reg_feature' ),
			array( __CLASS__, 'code_howmany_func' ),
			'generate_rand_code',
			'rand_code_section'
		);
	}

	public static function output_generate_codes_fields() {

		include( dirname( __FILE__ ) . '/admin/views/html-admin-generate-code-settings.php' );
	}

	public static function save_rand_code() {

		$prefix 	= preg_replace('/\s+/', '', $_POST['code_prefix']);
		$length 	= isset( $_POST['code_length'] ) ? (int) $_POST['code_length'] : 8;
		$howmany 	= isset( $_POST['code_howmany'] ) ? (int) $_POST['code_howmany'] : 5;

		if( $length < 4 || $length > 16 ) {
			add_settings_error( 'rey_reg_feature', '', __( 'Incorrect length.', 'rey_reg_feature' ) . sprintf( __( ' (Minimum %d)', 'rey_reg_feature' ), 4 ) . sprintf( __( ' (Maximum %d)', 'rey_reg_feature' ), 16 ), 'error' );
		} elseif( $howmany < 1 ) {
			add_settings_error( 'rey_reg_feature', '', __( 'How many codes do you need?', 'rey_reg_feature' ) . sprintf( __( ' (Minimum %d)', 'rey_reg_feature' ), 1 ), 'error' );
		} else {

			self::generate_activation_code( $howmany, $prefix, $length );

			add_settings_error( 'rey_reg_feature', '', sprintf( __( '%d code(s) have been added. <a href="%s">Check the codes list &raquo;</a>', 'rey_reg_feature' ), $howmany, admin_url( 'admin.php?page=invitation_codes' ) ), 'updated' );
		}
		return false;
	}

	public static function validate_activation_code( $code ) {

		$activation_codes = get_option( 'reyreg_activation_codes' );
		if( in_array($code, $activation_codes) ) {
			return true;
		}

		return false;
	}

	public static function remove_activation_code( $code ) {

		$activation_codes = get_option( 'reyreg_activation_codes' );

		foreach( $activation_codes as $key => $code_item ) {

			if( $code == $code_item ) {

				unset( $activation_codes[ $key ] );
				break;
			}
		}

		update_option( 'reyreg_activation_codes', $activation_codes );
	}

	public static function generate_activation_code( $howmany=1, $prefix="", $length=8 ) {

		$activation_codes = get_option( 'reyreg_activation_codes' );
		$temp_codes_array = array();

		$prefix = sanitize_key( $prefix );

		$i = 1;
		while ( $i <= $howmany ) {
			$temp_code = strtoupper( $prefix . wp_generate_password( $length, false ) );

			if ( ! in_array( $temp_code, $activation_codes ) ) {
				$activation_codes[] = $temp_code;
				$temp_codes_array[] = $temp_code;
				++$i;
			}
		}
		update_option( 'reyreg_activation_codes', $activation_codes );
		
		return $temp_codes_array;
	}

	public static function code_prefix_func() {
	?>
		<input type="text" name="code_prefix" size="10" value="invite-" style="text-transform: uppercase;" /> <em><?php _e( 'All generated codes will start with this.', 'rey_reg_feature' ); ?></em>
	<?php
	}

	public function code_length_func() {
	?>
		<input type="number" size="10" min="4" max="16" name="code_length" value="8" /> <em><?php _e( 'Length of generated codes (Min. 4, Max. 16)', 'rey_reg_feature' ); ?></em>
	<?php
	}

	public function code_howmany_func() {
	?>
		<input type="number" size="3" min="1" max="10" name="code_howmany" value="5" /> <em><?php _e( 'How many codes do you need?', 'rey_reg_feature' ); ?></em>
	<?php
	}

	public static function invitation_list_table() {

		$activation_codes = get_option( 'reyreg_activation_codes' );

		$admin_notices = array();
		if ( isset( $_GET['action'], $_GET['_wpnonce'] ) ) { // do this in admin-post next time
			switch ( $_GET['action'] ) {
				case 'delete':
					$code = isset( $_GET['code'] ) ? $_GET['code'] : false;

					if( $code && in_array( $code, $activation_codes ) && wp_verify_nonce( $_GET['_wpnonce'], 'invitation-' . $_GET['action'] . '-' . $code ) ) {

						if( ($key = array_search($code, $activation_codes)) !== false) {
						    unset($activation_codes[$key]);
						}
						
						update_option( 'reyreg_activation_codes', $activation_codes );

						$admin_notices = array(
							'status' => 'updated',
							'message' => sprintf( __( 'The code <b>%s</b> have been successfully deleted.', 'rey_reg_feature' ), esc_html( $code ) )
						);
					} else {
						
						$admin_notices = array(
							'status' => 'error',
							'message' => sprintf( __( 'The code <b>%s</b> have not been deleted.', 'rey_reg_feature' ), esc_html( $code ) )
						);
					}
					break;
					
			}
		}

		include( dirname( __FILE__ ) . '/admin/views/html-admin-invitation-list.php' );
	}

}
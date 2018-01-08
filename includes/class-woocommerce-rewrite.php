<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RewriteWooCommerce {
	
	public function __construct() {
		// Change checkout fields
		add_filter( 'woocommerce_checkout_fields' , array( $this, 'override_checkout_fields' ) );
		// Change order item name
		add_filter( 'woocommerce_order_item_name', array( $this, 'change_order_item_name' ), 10, 2 );
		// After order completed
		add_action( 'woocommerce_payment_complete', array( $this, 'after_order_completed' ) );
	}

	public function override_checkout_fields( $fields ) {
		unset($fields['shipping']);
		//unset($fields['billing']['billing_first_name']);
		unset($fields['billing']['billing_last_name']);
		unset($fields['billing']['billing_company']);
		unset($fields['billing']['billing_country']);
		unset($fields['billing']['billing_address_1']);
		unset($fields['billing']['billing_address_2']);
		unset($fields['billing']['billing_city']);
		unset($fields['billing']['billing_state']);
		unset($fields['billing']['billing_postcode']);
		unset($fields['billing']['billing_phone']);

     	return $fields;
	}

	public function change_order_item_name( $link, $item ) {
		return $item->get_name();
	}

	public function after_order_completed( $order_id ) {
		global $woocommerce;

		$order = new WC_Order($order_id);
        $order->payment_complete();
		$order->add_order_note('Tap payment successful.<br/>Tap ID: '.sanitize_text_field($_REQUEST['ref']).' ('.sanitize_text_field($_REQUEST['trackid']).')<br/>Payment Type: '.sanitize_text_field($_REQUEST['crdtype']).'<br/>Payment Ref: '.sanitize_text_field($_REQUEST['payid']));
		$woocommerce->cart->empty_cart();

		if ( $order->status == 'processing' ) {
		   $order->update_status('completed');
		}

		$order_data = $order->get_data();
		$user_email = $order_data['billing']['email'];

		$code = ReyReg_Activation_Codes::generate_activation_code();
		$activation_code = $code[0];

		$existing_pages = bp_core_get_directory_page_ids();
		$register_page_link = get_permalink( $existing_pages['register'] );
		
		$to = $user_email;
		$subject = __('رمز الدخول', 'rey_reg_feature');

		$body =
		"<p>مرحبا،</p>" .
		"<p>شكرا لاشتراكك على موقعنا." .
		"سوف تحتاج الى الرمز التالي لإستكمال اشتراكك:<br> {$activation_code}.</p>" .
		"<p>لخلق حساب جديد برجاء ادخال رمزك الخاص على صفحة الاشتراك من خلال الدخول على الرابط التالي:<br>" .
		"<a href='$register_page_link'>$register_page_link</a></p>" . 
		"<p>شكرا</p>";
		
		$admin_email = get_option("admin_email");
		$site_title = get_option('blogname');

		$headers = array(
			"From: $site_title <$admin_email>",
			'content-type: text/html'
		);
		 
		wp_mail( $to, $subject, $body, $headers );
		
		$redirect_url	= wp_registration_url();
		wp_redirect( $redirect_url );
        exit;
	}
}
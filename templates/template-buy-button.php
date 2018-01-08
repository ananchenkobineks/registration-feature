<?php $code_id = url_to_postid( '/product/registration-code' ); ?>
<div class="submit code">	
	<a href="<?php echo get_site_url() . '/?add-to-cart=' . $code_id ?>" id="buy-activation-code"><?php _e( 'شراء رمز الدخول', 'rey_reg_feature' ); ?></a>
</div>
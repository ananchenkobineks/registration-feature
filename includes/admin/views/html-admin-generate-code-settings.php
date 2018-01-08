<?php settings_errors(); ?>
<div class="wrap">
	<form action="options.php" method="post">
		<?php settings_fields( 'generate_rand_code' ); ?>
		<?php do_settings_sections( 'generate_rand_code' ); ?>
		<?php submit_button( __( 'Generate codes', 'rey_reg_feature' ) ); ?>
	</form>
</div>
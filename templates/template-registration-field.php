<label for="activation_code"><?php _e( 'رمز الدخول', 'rey_reg_feature' ); ?> <?php _e( '*', 'rey_reg_feature' ); ?></label>
<?php do_action( 'bp_activation_code_errors' ); ?>
<input type="text" name="activation_code" id="activation_code" value="<?php echo $_GET['activation-code'] ?>">
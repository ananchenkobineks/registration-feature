<div id="invitation-codes-list" class="wrap">

	<?php if( !empty( $admin_notices ) ): ?>
		<div class="<?php echo $admin_notices['status'] ?> notice is-dismissible"> 
			<p><?php echo $admin_notices['message'] ?></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
	<?php endif; ?>

	<h1><?php _e( 'Invitation Codes List', 'rey_reg_feature' ); ?>
		<a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=generate_codes' ); ?>"><?php _e( 'Generate codes', 'rey_reg_feature' ) ;?></a>
	</h1>
	
	<form action="<?php echo admin_url( 'admin.php' ); ?>">

		<?php
			if ( ! empty( $_GET['s'] ) ) {
				printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html( strtoupper($_GET['s']) ) );
			}
		?>

		<p class="search-box" style="margin: 7px 0;">
			<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php _e( 'Search by keyword' ); ?>:</label>
			<input type="search" id="search-text" name="s" style="text-transform: uppercase;" value="<?php _admin_search_query(); ?>" />
			<input type="hidden" id="page" name="page" value="invitation_codes" />
			<?php submit_button( __( 'Search codes', 'rey_reg_feature' ), 'button', false, false, array('id' => 'search-submit') ); ?>
		</p>
	</form>
	<table class="wp-list-table widefat fixed striped pages">
		<thead>
			<tr>
				<th><?php _e( 'Code', 'rey_reg_feature' ); ?></th>
				<th><?php _e( 'Action', 'rey_reg_feature' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$empty = true;
			if ( !empty( $activation_codes ) && count( $activation_codes ) > 0 ) {

				foreach( $activation_codes as $code ) {

					if ( ! empty( $_GET['s'] ) && strstr( $code, strtoupper( $_GET['s'] ) ) === false ) {
						continue;
					}
					$empty = false;
				?>

					<tr class="token">
						<td>
							<div class="activation">
								<b><?php echo esc_html( $code ); ?></b>
							</div>
						</td>
						<td>
							<div class="activation">
								<span class="trash">
									<a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=invitation_codes&action=delete&code=' . esc_attr( $code ) ), 'invitation-delete-' . $code ); ?>"><?php _e( 'Delete' ); ?></a>
								</span>
							</div>
						</td>
					</tr>

				<?php
				}
			}
			if ( $empty ) {
				echo '<tr><td colspan="2">' . __( 'No codes yet!', 'rey_reg_feature' ) . '</td></tr>';
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th><?php _e( 'Code', 'rey_reg_feature' ); ?></th>
				<th><?php _e( 'Action', 'rey_reg_feature' ); ?></th>
			</tr>
		</tfoot>
	</table>
</div>
<?php
//General Setting from admin side
class withinweb_wwkc_keycodes_setting {

	//--------------------------------------------------
    public static function init() {    
        add_action('withinweb_wwkc_keycodes_general_setting_save_field', array(__CLASS__, 'withinweb_wwkc_keycodes_general_setting_save_field'));
    	add_action('withinweb_wwkc_keycodes_general_setting_display', array(__CLASS__, 'withinweb_wwkc_keycodes_general_setting_display'));
    }
	//--------------------------------------------------
    //save general setting field value 
    public static function withinweb_wwkc_keycodes_general_setting_save_field() {
		
		if ( !current_user_can( 'manage_options' ) )
		{
			wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}

		//------------------------------------------------
		//If submitted then update the envirmonment option
		if (isset($_POST['general_setting_environment']) && !empty($_POST['general_setting_environment'])) {
			
			// Check the nonce field
			if ( !check_admin_referer( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_environment' ) )
			{
				exit();   
			}		
			
			//live or sandbox
			$withinweb_wwkc_keycodes_paypal_environment = sanitize_text_field( $_POST['environment'] );
			update_option('withinweb_wwkc_keycodes_paypal_environment', $withinweb_wwkc_keycodes_paypal_environment);			
			self::showMessage(1);
		}		
		
		//------------------------------------------------		
		//If submitted then update the debug options
        if (isset($_POST['general_setting_integration']) && !empty($_POST['general_setting_integration'])) {

			// Check the nonce field
			if ( !check_admin_referer( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_environment' ) )
			{
				exit();   
			}
			
			//debug flag			
			if ( !isset($_POST['withinweb_wwkc_keycodes_paypal_debug'] )) {
				$withinweb_wwkc_keycodes_paypal_debug = "0";
			}
			else {
				$withinweb_wwkc_keycodes_paypal_debug = sanitize_text_field( $_POST['withinweb_wwkc_keycodes_paypal_debug'] );				
			}			
            update_option('withinweb_wwkc_keycodes_paypal_debug', $withinweb_wwkc_keycodes_paypal_debug);
			
			//do local test flag
			if ( !isset($_POST['withinweb_wwkc_keycodes_paypal_dolocaltest'] )) {
				$withinweb_wwkc_keycodes_paypal_dolocaltest = "0";
			}
			else {
				$withinweb_wwkc_keycodes_paypal_dolocaltest = sanitize_text_field( $_POST['withinweb_wwkc_keycodes_paypal_dolocaltest'] );
			}
			update_option('withinweb_wwkc_keycodes_paypal_dolocaltest', $withinweb_wwkc_keycodes_paypal_dolocaltest);
			self::showMessage(1);
		}
		
		//------------------------------------------------		
		//If submitted then update the option
		if (isset($_POST['general_settings_paypal']) && !empty($_POST['general_settings_paypal'])) {			
			
			// Check the nonce field
			if ( !check_admin_referer( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_environment' ) )
			{
				exit();   
			}		
			
			$message = "1";
			
			//paypal email address
			$withinweb_wwkc_keycodes_paypal_email = sanitize_email( $_POST['paypal_email'] );      
			//Validaate email
			if ( is_email($withinweb_wwkc_keycodes_paypal_email) )
			{
				update_option('withinweb_wwkc_keycodes_paypal_email', $withinweb_wwkc_keycodes_paypal_email);
			}
			else
			{
				$message = "2";
			}
			
			//cancel url			
			if ( isset( $_POST['cancel_url'] ) ) {
				$withinweb_wwkc_keycodes_cancel_url = sanitize_text_field( $_POST['cancel_url'] );			
        		update_option('withinweb_wwkc_keycodes_cancel_url', $withinweb_wwkc_keycodes_cancel_url);
			}
			
			//return url
  			if ( isset( $_POST['return_url'] ) ) {			
				$withinweb_wwkc_keycodes_return_url = sanitize_text_field( $_POST['return_url'] );			
        		update_option('withinweb_wwkc_keycodes_return_url', $withinweb_wwkc_keycodes_return_url);
  			}
			
			//sandbox email address
			if ( isset( $_POST['sandbox_paypal_email'] ) )
			{			
				$withinweb_wwkc_keycodes_sandbox_paypal_email = sanitize_email( $_POST['sandbox_paypal_email'] );
				if ( $withinweb_wwkc_keycodes_sandbox_paypal_email != "" )
				{
					//Validaate email
					if ( is_email($withinweb_wwkc_keycodes_sandbox_paypal_email) )
					{
						update_option('withinweb_wwkc_keycodes_sandbox_paypal_email', $withinweb_wwkc_keycodes_sandbox_paypal_email);	
					}
					else		
					{
						$message = "2";
					}
				}
			}
			
			self::showMessage($message);

		}
        
    }
	//--------------------------------------------------
    //display general setting
    public static function withinweb_wwkc_keycodes_general_setting_display() {        
        ?>

		<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?>

		<div class="wrap">

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Settings', 'withinweb-wwkc-keycodes'); ?></strong></h2>
			<h3><?php _e('This page provides you with the set up that is required to use the KeyCodes system', 'withinweb-wwkc-keycodes'); ?></h3>		
			<?php
			if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			?>
			<h2><strong><?php _e('IPN Call Back URL', 'withinweb-wwkc-keycodes'); ?>:</strong></h2>			
			
			<form enctype="multipart/form-data" action="" method="post" style="background-color:#CCC;padding-left:10px;">            

   				<?php wp_nonce_field( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_environment' ); ?>
				
				<table class="form-table" id="paypal_ipn_primary_url">
					<tbody>

						<tr class="form-field">
							<th scope="row"><label for="paypal_ipn_primary_url"><?php _e('IPN Call Back URL', 'withinweb-wwkc-keycodes'); ?>:<span class="description"></span></label></th>
							<td class="forminp forminp-text"><?php echo esc_html ( get_option('withinweb_wwkc_keycodes_ipn_url') ); ?></td>
						</tr>

						<tr class="form-field form-required">
							<th scope="row"><label for="paypal_server"><?php _e('PayPal environment', 'withinweb-wwkc-keycodes'); ?><span class="description"></span></label></th>
							<td>                    	

								<select id="environment" name="environment">
									<option value="live" <?php echo esc_html ( get_option('withinweb_wwkc_keycodes_paypal_environment') ) == 'live' ? 'selected="selected"' : ''; ?>>Live - Production</option>
									<option value="sandbox" <?php echo esc_html ( get_option('withinweb_wwkc_keycodes_paypal_environment') ) == 'sandbox' ? 'selected="selected"' : ''; ?>>Sandbox - Testing</option>
								</select>

							</td>
						</tr>

					</tbody>
				</table>
				
				<p class="submit">
				<input type="submit" name="general_setting_environment" class="button-primary" value="<?php esc_attr_e('Save changes', 'withinweb-wwkc-keycodes'); ?>" />
				</p>
				
			</form>
			
					
			<h2><strong><?php _e('Debug options', 'withinweb-wwkc-keycodes'); ?>:</strong></h2>	
			
			<form enctype="multipart/form-data" action="" method="post" style="background-color:#CCC;padding-left:10px;">

   				<?php wp_nonce_field( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_environment' ); ?>				
				
				<table class="form-table" id="debug_option">
					<tbody>

						<tr class="form-field">
							<th scope="row"><label for="withinweb_wwkc_keycodes_paypal_debug"><?php _e('Debug Log', 'withinweb-wwkc-keycodes'); ?></label></th>
							<td>
								<?php if (defined('wwkc_KEYCODES_LOG_DIR')) { ?>
									<?php if (@fopen(wwkc_KEYCODES_LOG_DIR . 'test-log.txt', 'a')) { ?>
										<fieldset>											
											<label for="withinweb_wwkc_keycodes_paypal_debug">
												<input type="checkbox" <?php echo (get_option('withinweb_wwkc_keycodes_paypal_debug') == '1') ? 'checked="checked"' : '' ?> value="1" id="withinweb_wwkc_keycodes_paypal_debug" name="withinweb_wwkc_keycodes_paypal_debug" class=""><?php _e('Enable logging', 'withinweb-wwkc-keycodes'); ?></label><br>
											<p class="description"><?php _e('Log PayPal events, such as IPN requests, at', 'withinweb-wwkc-keycodes'); ?><br/><?php echo wwkc_KEYCODES_LOG_DIR; ?></p>
										</fieldset>
									<?php } else { ?>
										<p><?php printf('<mark class="error">' . __('Log directory (<code>%s</code>) is not writable. To allow logging, make this writable or define a custom <code>wwkc_KEYCODES_LOG_DIR</code>.', 'withinweb-wwkc-keycodes') . '</mark>', wwkc_KEYCODES_LOG_DIR); ?></p>
										<?php
									}
								}
								?>
							</td>
						</tr>

						<tr class="form-field">
							<th scope="row"><label for="withinweb_wwkc_keycodes_paypal_dolocaltest">Do Local Test</label></th>
							<td><input type="checkbox" <?php echo (get_option('withinweb_wwkc_keycodes_paypal_dolocaltest') == '1') ? 'checked="checked"' : '' ?> value="1" id="withinweb_wwkc_keycodes_paypal_dolocaltest" name="withinweb_wwkc_keycodes_paypal_dolocaltest" class=""><?php _e('Enable Local Test', 'withinweb-wwkc-keycodes'); ?><br>
								<p class="description"><?php _e('Tick to enable local test.  Disable this for normal Live and Sandbox operation.', 'withinweb-wwkc-keycodes'); ?></p>
							</td>
						</tr>
						
					</tbody>
				</table>
				
				<p class="submit">
				<input type="submit" name="general_setting_integration" id="general_setting_integration" class="button-primary" value="<?php _e('Save changes', 'withinweb-wwkc-keycodes'); ?>" />
				</p>
				
			</form>
			
			
			<h2><strong>PayPal Settings:</strong></h2>
			<form enctype="multipart/form-data" method="post" action="" style="background-color:#CCC;padding-left:10px;">

				<?php wp_nonce_field( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_environment' ); ?>
				
				<table class="form-table" id="paypal_settings">
					<tbody>

						<tr class="form-field form-required">
							<th scope="row"><label for="paypal_email"><?php _e('PayPal PRIMARY email address', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="email" value="<?php echo esc_html( get_option('withinweb_wwkc_keycodes_paypal_email') ); ?>" id="paypal_email" name="paypal_email" style="width:240px;" required /></td>
						</tr>

						<tr class="form-field form-required">
							<th scope="row"><label for="cancel_url"><?php _e('Cancel URL', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(optional)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="url" value="<?php echo esc_html( get_option('withinweb_wwkc_keycodes_cancel_url') ); ?>" id="cancel_url" name="cancel_url" style="width:360px;" /></td>
						</tr>

						<tr class="form-field form-required">
							<th scope="row"><label for="return_url"><?php _e('Return URL', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(optional)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="url" value="<?php echo esc_html( get_option('withinweb_wwkc_keycodes_return_url') ); ?>" id="return_url" name="return_url" style="width:360px;" /></td>
						</tr>

						<tr class="form-field form-required">
							<th scope="row"><label for="sandbox_paypal_email"><?php _e('Sandbox PayPal email address', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(optional)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="email" value="<?php echo esc_html( get_option('withinweb_wwkc_keycodes_sandbox_paypal_email') ); ?>" id="sandbox_paypal_email" name="sandbox_paypal_email" style="width:240px;" /></td>
						</tr>

					</tbody>
				</table>
				
				<p class="submit">
				<input type="submit" value="<?php _e('Save PayPal Settings', 'withinweb-wwkc-keycodes'); ?>" class="button-primary" name="general_settings_paypal" id="general_settings_paypal" />
				</p>
				
			</form>			
			
		</div>			
        <?php
    }
	//--------------------------------------------------
	//Show message  http://www.wprecipes.com/how-to-show-an-urgent-message-in-the-wordpress-admin-area/	
	private static function showMessage($m) {
		
		switch ($m) {
			case '1':
        		?> <div id='message' class='updated fade'><p><strong><?php _e('You have successfully updated your settings.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;
    		case '2':
        	 	?> <div id='message' class='error'><p><strong><?php _e('Email address not saved.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;    
		} 		
		
	}

}

withinweb_wwkc_keycodes_setting::init();

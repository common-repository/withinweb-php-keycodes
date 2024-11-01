<?php
//Create item 
class withinweb_wwkc_keycodes_premium {
	
	//--------------------------------------------------------------------------------------------	
	public static function init() {
   		add_action('withinweb_wwkc_keycodes_premium_display', array(__CLASS__, 'withinweb_wwkc_keycodes_premium_display'));
    }
	//--------------------------------------------------------------------------------------------
	public static function withinweb_wwkc_keycodes_premium_display() {

		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}
		?>

		<div class="wrap">

			<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?>     

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Upgrade to Premium', 'withinweb-wwkc-keycodes'); ?></strong></h2>
			<?php
			if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			?>
			
			<table width="100%">
				<tr>
					<td width="50%">						
						
						<p><?php _e('PHP-KeyCodes is brought to you from the withinweb.com web site:', 'withinweb-wwkc-keycodes') ?></p>

						<p><a href="https://www.withinweb.com/wordpresskeycodes/" class="button-primary" target="_blank"><strong><?php _e('Go to WithinWeb.com web site', 'withinweb-wwkc-keycodes'); ?></strong></a></p>

						<h3><?php _e('Differences between free and Premium version', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('The premium version has more facilities than the free version and has priority support.  With the premium version 
						you can create more than one product item so that you can distribute multiple key codes.  The premium version also has 
						a sales history display which shows the purchased license codes.', 'withinweb-wwkc-keycodes'); ?></p>

						<h3><?php _e('Purchase WordPress PHP-KeyCodes Premium', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('We use PayPal because it is secure and accepts payment from most credit cards.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('The complete transaction is handled by the PayPal secure server system.  PayPal is  
						responsible for handling the credit card and other payment details.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('If you have not purchased anything through PayPal before, you will be able to register 
						with PayPal, or you may purchase by credit card without registering.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><strong><?php _e('MAKE SURE THAT THE EMAIL ADDRESS YOU ENTER INTO PAYPAL IS VALID AS THIS WILL BE 
						THE EMAIL ADDRESS THAT YOUR DOWNLOAD DETAILS WILL BE SENT TO', 'withinweb-wwkc-keycodes'); ?></strong></p>

						<p><?php _e('Once your payment has been accepted you will receive an email from us with a web link 
						from where you download the zip file.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('The premium version may be purchased for <strong>$20.00 USD</strong> by clicking on the following 
						button:', 'withinweb-wwkc-keycodes') ?></p>

						<p>
							<form action="https://www.withinweb.com/goods/ipn/process.php" method="post" target="_blank">
								<input type="hidden" name="recid" value="7">
								<input type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-large.png"
								border="0" name="submit" alt="Buy now with PayPal">
							</form>		
						</p>

						<h3><?php _e('Installation', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('The installation into WordPress is the same as for any plugin as is the procedure for upgrades.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('If you have the free version of the plugin, Deactivate and Delete it before you install the Premium version.  Having both the 
						Premium and Free version active should not be an issue but may may cause confusion.', 'withinweb-wwkc-keycodes'); ?></p>

						<h3><?php _e('Purchase from the WithinWeb.com site', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('You can also purchase from the withinweb.com web site and see other PHP / MySQL applications:', 'withinweb-wwkc-keycodes'); ?></p>

						<p><a href="https://www.withinweb.com/wordpresskeycodes/" class="button-primary" target="_blank"><strong><?php _e('Go to WithinWeb.com web site', 'withinweb-wwkc-keycodes'); ?></strong></a></p>
					
					</td>
					<td width="50%">&nbsp;</td>		
				</tr>
			</table>
		
		</div>
	<?php
	}
	
}

withinweb_wwkc_keycodes_premium::init();
?>
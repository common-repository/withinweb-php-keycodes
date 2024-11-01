<?php
//Create item 
class withinweb_wwkc_keycodes_about {
	
	//--------------------------------------------------------------------------------------------	
	public static function init() {
   		add_action('withinweb_wwkc_keycodes_about_display', array(__CLASS__, 'withinweb_wwkc_keycodes_about_display'));
    }
	//--------------------------------------------------------------------------------------------
	public static function withinweb_wwkc_keycodes_about_display() {

		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}
		?>

		<div class="wrap">

			<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?>     

			<h2><strong><?php _e('WordPress PHP-KeyCodes - About', 'withinweb-wwkc-keycodes'); ?></strong></h2>
			<?php
			if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			?>

			<table width="100%">
				<tr>
					<td width="50%">
						
						<p><?php _e('PHP-KeyCodes is brought to you from the withinweb.com web site.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><a href="https://www.withinweb.com/wordpresskeycodes/" class="button-primary" target="_blank"><strong><?php _e('Go to WithinWeb.com web site', 'withinweb-wwkc-keycodes'); ?></strong></a></p>

						<h3><?php _e('Installation', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('The installation into WordPress is the same as for any plugin as is the procedure for upgrades.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('If you have the free version of the plugin, Deactivate and Delete it before you install the Premium version.  Having both the 
							Premium and Free version active should not be an issue but may may cause confusion.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('In the admin area of your WordPress site, click on "New Plugin" and then click on "Upload Plugin". Browse for 
							"withinweb-wwkc-keycodes.zip" on your computer and click on "Install now".', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('Activate the plugin once it has been uploaded and un-zipped.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('In the "Settings" of the KeyCodes menu, you must enter in your PRIMARY PayPal email address for payments.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('Create an item and enter in the key codes in the key codes field one line at a time.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('You may test the system using a local test without connecting to PayPal.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('In "Settings" make sure you have selected the PayPal enviromment that you want to use, as either PayPal live or PayPal sandbox.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('To display the PayPal button on your WordPress page, use the short code [keycodesbutton recid="x"] where x is the record id 
							of the product item. Or you can use the HTML code displayed from the "Item List" Page. You can get the record id of the 
							product by going to "Item List".', 'withinweb-wwkc-keycodes'); ?></p>

						<h3><?php _e('PHP-KeyCodes Settings', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('Before you create your product items, first go to the "Settings" page.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('The PayPal environment can be either Live or Sandbox.  If you are going to use the PayPal Sandbox testing environment, you also 
							need to enter the PayPal Sandbox email address which you will have to set up through the PayPal developer environment.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('Note that the PayPal email address you enter into PHP-KeyCodes must be your Primary PayPal email address. You can set up multiple 
							email addresses in PayPal but only the Primary PayPal address will work with the IPN system.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('Also note that if you receive a purchase which has a currency that is not the same as your PayPal default currency, then you have 
							to accept the currency code before the transaction is completed.  To overcome this, you can set your PayPal account to accept a 
							range of different currencies.', 'withinweb-wwkc-keycodes'); ?></p>

						<h3><?php _e('PayPal activation', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('Make sure that you have enabled IPN in your PayPal account. You may also have to enter in the IPN Call Back URL which you can get from 
							the "Settings" menu of the plugin.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('The call back url is acutally sent to PayPal from PHP-KeyCodes as part of the button submission, which means that the url entered 
							in PayPal setup can be different to the url needed for this plugin.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('Hence it is possible to have multiple PayPal IPN systems without any conflicts.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('The IPN system (Instant Payment Notification) is the way in which PayPal sends messages to and from PHP-KeyCodes.  PayPal will 
							send out a verified message only when the purchase is complete so you can be sure that no one can make a purchase without 
							correct payment.', 'withinweb-wwkc-keycodes'); ?></p>	

						<h3><?php _e('Creating your items', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('The create item page should be self-explanatory.  The codes that you are going to sell go in the "keycodes" text box each one 
							entered a line at a time.  The top key code will be removed when sold so that the next key code is avaiable for the next 
							purchase.', 'withinweb-wwkc-keycodes'); ?></p>

						<h3><?php _e('Short codes', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('The PayPal buttons are created using short codes as follows:', 'withinweb-wwkc-keycodes'); ?>
						<br/>    
						<strong>[keycodesbutton recid="x" ]</strong>
						<br/>
						<?php _e('where x is the record id of the product item.', 'withinweb-wwkc-keycodes'); ?>
						<br/>
						<?php _e('Place the short code onto any of your WordPress pages.', 'withinweb-wwkc-keycodes'); ?></p> 

						<p><?php _e('The short code options are:', 'withinweb-wwkc-keycodes'); ?></p>

						<table>
							<tr>
								<td>recid</td>
								<td width="20"></td>
								<td><?php _e('a required entry', 'withinweb-wwkc-keycodes'); ?></td>
							</tr>
							<tr>
								<td valign="top">custom <?php _e('(optional)', 'withinweb-wwkc-keycodes'); ?></td>
								<td width="20"></td>
								<td><?php _e('default of blank which can be used for the IPN custom field which you can use to return any information back.', 'withinweb-wwkc-keycodes'); ?></td>
							</tr>
							<tr>
								<td>quantity <?php _e('(optional)', 'withinweb-wwkc-keycodes'); ?></td>
								<td width="20"></td>
								<td><?php _e('default 1', 'withinweb-wwkc-keycodes'); ?></td>								
							</tr>
							<tr>
								<td>buttontext <?php _e('(optional)', 'withinweb-wwkc-keycodes'); ?></td>
								<td width="20"></td>
								<td><?php _e('default of "Buy with PayPal"', 'withinweb-wwkc-keycodes'); ?></td>								
							</tr>							
							<tr>
								<td>buttonclass <?php _e('(optional)', 'withinweb-wwkc-keycodes'); ?></td>
								<td width="20"></td>
								<td><?php _e('default of "button-primary"', 'withinweb-wwkc-keycodes'); ?></td>								
							</tr>
							<tr>
								<td>tax <?php _e('(optional) percentage tax', 'withinweb-wwkcp-keycodes'); ?></td>
								<td width="20"></td>
								<td><?php _e('default of "0"', 'withinweb-wwkcp-keycodes'); ?></td>								
							</tr>							
						</table>
						
						<p><?php _e('So a full example would be:', 'withinweb-wwkc-keycodes'); ?><br/>
						
						[keycodesbutton recid="3" buttontext="Buy this at quantity 2" quantity="2" custom="Custom string" buttonclass="button-primary" tax="20"]</p>
						
						<p><?php _e('You can get the record id of the product by listing the items in "Item List" page.  This page also has the short code displayed.  If
						you want to use more conventional buttons which can be placed on non WordPress web pages, then use the html code which can also be
						displayed from the "Item List" page.', 'withinweb-wwkc-keycodes'); ?></p>	

						<h3><?php _e('Testing', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('The best way to test the application is to use a second live PayPal account as that tests the complete system.  You have to do this
							if you want to test live because PayPal does not allow you to purchase from your own account.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('To open a second PayPal account you need a second bank account which some people may find difficult but you will find it worth 
							doing in the long run.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('You can also use the PayPal Sandbox for testing which requires you work in the developer enviromment <strong>http://developer.paypal.com</strong>.  Login into 
							this using your normal PayPal account.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('In the developer environment, you can create as many test acoounts as you want, and then set the PHP-KeyCodes to the sandbox environment.  
							You also need to enter the sandbox email address into the "Settings" dsplay of PHP-KeyCodes.', 'withinweb-wwkc-keycodes'); ?></p>

						<h3><?php _e('Local Test', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('PHP-KeyCodes has a local test facility which will test all the set up and email details, but does not go through PayPal.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('Local test has to be enabled before it can be used by going to the "Settings" display and setting the "Do Local Test" check box.  Once
						you have finished your local tests, you should un-tick this box.', 'withinweb-wwkc-keycodes'); ?></p>

						<p><?php _e('The local test is useful if you don\'t want to sepend time going in and out of PayPal.', 'withinweb-wwkc-keycodes'); ?></p>

						<h3><?php _e('Logging', 'withinweb-wwkc-keycodes'); ?></h3>

						<p><?php _e('Enable the debug log in the "Setting" section of PHP-KeyCodes.  This will create a file on your server in your WordPress 
						installation which details the IPN results and other messages to show the path through the application.', 'withinweb-wwkc-keycodes'); ?></p>
		
					</td>
					<td width="50%">&nbsp;</td>		
				</tr>
			</table>

		</div>
	<?php
	}
	
}

withinweb_wwkc_keycodes_about::init();
?>
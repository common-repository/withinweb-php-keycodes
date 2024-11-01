<?php
//Button code
class withinweb_wwkc_keycodes_buttoncode {

	//--------------------------------------------------
    public static function init() {    
        add_action('withinweb_wwkc_keycodes_buttoncode_display', array(__CLASS__, 'withinweb_wwkc_keycodes_buttoncode_display'));
    }
	//--------------------------------------------------
	public static function withinweb_wwkc_keycodes_buttoncode_display() {
		
		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}			
		
		?>

		<div class="wrap">

			<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?> 

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Button code', 'withinweb-wwkc-keycodes'); ?></strong></h2>

			<?php
				if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			?>
			
			<p><strong><?php _e('If you want to add a purchase button onto a page outside of WordPress or even on another web site, then 
			   copy the following code from below.', 'withinweb-wwkc-keycodes'); ?></strong></p>
			
			<p><?php _e('Note that you can change the button image to anything you like once you have copied the code to your 
			web page.', 'withinweb-wwkc-keycodes'); ?></p>

			<?php	
			//Check for verification nonce
			if ( !isset($_GET['recid']) || !wp_verify_nonce($_GET['refid'], 'button_page')) {
				exit();
			}

			$recid = sanitize_text_field($_GET['recid']);

			//Check if nummeric value
			if ( !is_numeric($recid) ) { exit(); }

				//------------------------------------------
				//Get details for this record and display it.
				global $wpdb;	
				$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
				//$row = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE recid = " . $recid );

				$row = $wpdb->get_row( $wpdb->prepare( 
					"
						SELECT * FROM $table_name WHERE recid = %d
					", 
					array (
						$recid
					)
				) );			

				$mc_currency = $row->mc_currency;
				$item_name = $row->item_name;
				$mc_gross = $row->mc_gross;
				$item_number = $row->item_number;
				$custom = "";
				//------------------------------------------

				//------------------------------------------
				$livepaypaladdress 		= sanitize_email( get_option('withinweb_wwkc_keycodes_paypal_email') );
				$sandboxpaypaladdress 	= sanitize_email( get_option('withinweb_wwkc_keycodes_sandbox_paypal_email') );
				$cancel_url 			= sanitize_text_field( get_option('withinweb_wwkc_keycodes_cancel_url') );
				$return_url  			= sanitize_text_field( get_option('withinweb_wwkc_keycodes_return_url') );
				$notify_url 			= sanitize_text_field( get_option('withinweb_wwkc_keycodes_ipn_url') );	
				$withinweb_wwkc_keycodes_paypal_environment = sanitize_text_field ( get_option('withinweb_wwkc_keycodes_paypal_environment') );
				//------------------------------------------
		
				//echo($livepaypaladdress . "<br/>");
				//echo($sandboxpaypaladdress . "<br/>");
				//echo($cancel_url . "<br/>");
				//echo($return_url . "<br/>");
				//echo($notify_url . "<br/>");
				//echo($withinweb_wwkc_keycodes_paypal_environment);
				//exit();
		

				if ($withinweb_wwkc_keycodes_paypal_environment == "live")
				{
					$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
					$emailaddress = $livepaypaladdress;
				}

				if ($withinweb_wwkc_keycodes_paypal_environment == "sandbox")    
				{
					$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
					$emailaddress = $sandboxpaypaladdress;
				}

				$paypalimage = wwkc_PLUGIN_URL . "images/btn_buynowCC_LG.gif";
		
				?>
				<p>&nbsp;</p>	
				<?php	

				$result = "";
				$result .= "<form method='post' name='paypal_form' action='$paypal_url' target='_blank'>" . "\r\n";

					$result .= "<!-- PayPal Configuration -->" . "\r\n";
					$result .= "<input type='hidden' name='cmd' value='_xclick' />" . "\r\n";
					$result .= "<input type='hidden' name='business' value='$emailaddress' />" . "\r\n";
					$result .= "<input type='hidden' name='currency_code' value='$mc_currency' />" . "\r\n";
					$result .= "<input type='hidden' name='no_note' value='1' />" . "\r\n";

					$result .= "<!-- Product Information -->" . "\r\n";
					$result .= "<input type='hidden' name='item_name' value='$item_name' />" . "\r\n";
					$result .= "<input type='hidden' name='amount' value='$mc_gross' />" . "\r\n";
					$result .= "<input type='hidden' name='item_number' value='$item_number' />" . "\r\n";

					$result .= "<!-- //language code, France(FR), Spain (ES), Italy (IT), Germany (DE), China (CN), English (US). -->" . "\r\n";
					$result .= "<input type='hidden' name='lc' value='US' />" . "\r\n";	

					$result .= "<!-- Quantity -->". "\r\n";
					$result .= "<input type='hidden' name='quantity' value='1' />" . "\r\n";		
		
					$result .= "<!-- custom variable -->" . "\r\n";

					if ($custom != "") {
						$result .= "<input type='hidden' name='custom' value='$custom' />" . "\r\n";        									
					}

					//Note that name='no_shipping' defines if you want the shipping address to be prompted for		
					$result .= "<!-- Free Shipping -->" . "\r\n";
					$result .= "<input type='hidden' name='shipping' value='0' />" . "\r\n";

					$result .= "<!-- URLs -->" . "\r\n";
					$result .= "<input type='hidden' name='notify_url' " . "\r\n" . "value='$notify_url' />" . "\r\n";

					if ($cancel_url != "") {			
						$result .= "<input type='hidden' name='cancel_return' " . "\r\n" . "value='$cancel_url' />" . "\r\n";
					}

					if ($return_url != "") {			
						$result .= "<input type='hidden' name='return' " . "\r\n" . "value='$return_url' />" . "\r\n";
					}

		
				$result .= "<input type='image' src='$paypalimage' " . "\r\n" . "border='0' name='submit' alt='PayPal - The safer, easier way to pay online.'>" . "\r\n";

				$result .= "</form>" . "\r\n";	
				
				echo( "<pre>" . esc_html( "" .  $result . "" ) . "</pre>" );

				?>
				<p>&nbsp;</p>
				<p><strong><?php _e('This is what the button will look like.', 'withinweb-wwkc-keycodes'); ?></strong></p>	
				<?php
		
				echo( $result );		
			
			 ?>

		</div>


	<?php
	}	
	
	
}

withinweb_wwkc_keycodes_buttoncode::init();
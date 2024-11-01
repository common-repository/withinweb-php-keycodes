<?php
//Local test
class withinweb_wwkc_keycodes_localtest {
	
	//--------------------------------------------------------------------------------------------	
	public static function init() {
		add_action('withinweb_wwkc_keycodes_localtest_update', array(__CLASS__, 'withinweb_wwkc_keycodes_localtest_update'));				
   		add_action('withinweb_wwkc_keycodes_localtest_display', array(__CLASS__, 'withinweb_wwkc_keycodes_localtest_display'));
    }	
	//--------------------------------------------------------------------------------------------	
	public static function withinweb_wwkc_keycodes_localtest_update() {
	
		if ( !current_user_can( 'manage_options' ) )
		{
			wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}
		
		if (isset($_POST['localtest_update']) && !empty($_POST['localtest_update'])) {
   
			// Check the nonce field
			if ( !check_admin_referer( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_localtest' ) )
			{
			   exit();
			}   

			//Get the item number
			//Get the details for this product and post it to the handler

				//We need to post:
				//$item_name	
				//$mc_gross 
				//$item_number
				//$mc_currency
				//quantity
			
			if ( isset( $_POST['quantity'] ) )					
			{
				$quantity = sanitize_text_field( $_POST['quantity'] );
			}			

			if ( isset( $_POST['item_number'] ) )					
			{
				$item_number = sanitize_text_field( $_POST['item_number'] );
			}

			//----------------------------------------------
			//Get item name given item number
			global $wpdb;
			$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
			$row = $wpdb->get_row( $wpdb->prepare( 
				"
					SELECT item_name FROM $table_name WHERE item_number = %s
				", 
					array(
					$item_number
				) 
			) );	
			$item_name = $row->item_name;
			//----------------------------------------------

			if ( isset( $_POST['receiver_email'] ) )
			{
				$receiver_email = sanitize_email( $_POST['receiver_email'] );
			}   

			if ( isset( $_POST['payment_status'] ) )
			{
				$payment_status = sanitize_text_field( $_POST['payment_status'] );
			}   

			if ( isset( $_POST['mc_gross'] ) )
			{
			  $mc_gross = sanitize_text_field( $_POST['mc_gross'] );

			  if (!self::withinweb_wwkc_keycodes_validateTwoDecimals($mc_gross))
			  {  
				self::showMessage("2");
				return;
			  }

			}   	

			if ( isset( $_POST['mc_currency'] ) )
			{
				$mc_currency = sanitize_text_field( $_POST['mc_currency'] );
			}   

			if ( isset( $_POST['txn_id'] ) )
			{
				$txn_id = sanitize_text_field( $_POST['txn_id'] );
			}   

			if ( isset( $_POST['txn_type'] ) )
			{
				$txn_type = sanitize_text_field( $_POST['txn_type'] );
			}   

			if ( isset( $_POST['payer_email'] ) )
			{
				$payer_email = sanitize_email( $_POST['payer_email'] );
			}	

			//Get confirm url
			$withinweb_wwkc_keycodes_ipn_url 	= sanitize_text_field(get_option("withinweb_wwkc_keycodes_ipn_url"));	//The location of the confirm url	

			//Fields which should be posted to test the item.
			//$receiver_email  	//This is the email address which paypal uses to send confirmation emails, normally it is your email address
			//item_name			//The name of the item which can actually be anything and is not validated by the script
			//item_number		//The reference number of the item which should be the same as entered in the database
			//payment_status	//Completed, Pending, Failed, Denied   Defines the status from Paypal - selecting completed will simulate a completed transaction
			//mc_gross			//The gross value of the goods which must be the same as entered into the database.
			//quantity			//The quantity of items
			//mc_currency		//The currency code which must be the same as entered into the database.
			//txn_id			//The PayPal transaction id. You can enter any random series of characters here, I place the current time and date.
			//payer_email		//This is the email address of the purchaser.  For this test, enter a personal email address so that you can see the resulting emails.
			//txn_type			//web_accept, Cart, Send Money		//This should be web_accept or cart.	

			$response = wp_remote_post( $withinweb_wwkc_keycodes_ipn_url, array(	//send to confirm url
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array( 'item_number' => $item_number, 'item_name' => $item_name, 'receiver_email' => $receiver_email, 
								'payment_status' => $payment_status, 'mc_gross' => $mc_gross, 'mc_currency' => $mc_currency, 
								'txn_id' => $txn_id, 'payment_status' => $payment_status, 'txn_type' => $txn_type, 'quantity' => $quantity,
								'payer_email' => $payer_email, 'testmode' => 'true', 'first_name' => 'Local', 'last_name' => 'Test' ),
				'cookies' => array()
				)	
			);

			self::showMessage("1");

		}		

		
	}
	//--------------------------------------------------------------------------------------------
	public static function withinweb_wwkc_keycodes_localtest_display() {        

		if ( !current_user_can( 'manage_options' ) )
		{
			wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}		
		
		//Check for verification nonce	
		if ( !isset($_GET['recid'] ) || !wp_verify_nonce($_GET['refid'], 'localtest')) {	
			exit();
		}

		$recid = sanitize_text_field($_GET['recid']);

		//Check if nummeric value
		if ( !is_numeric($recid) ) { exit(); }		
		
		?>
		<div class="wrap">

			<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?> 

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Local test', 'withinweb-wwkc-keycodes'); ?></strong></h2>
			<h3><?php _e('Test an item locally, not through PayPal', 'withinweb-wwkc-keycodes'); ?></h3>
			
			<?php
			if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			else
			{
			?>
				<h2 style='color:red'><?php _e('You need to set local test mode to do a local test.', 'withinweb-wwkc-keycodes'); ?></h2>
			<?php
			}
		
			global $wpdb;
			$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";									

			$row = $wpdb->get_row( $wpdb->prepare( 
						"
							SELECT * FROM $table_name WHERE recid = %d
						", 
						array (
							$recid
						)
					) );
		
		
			if ( $row )
			{		
		
				?>
				<form enctype="multipart/form-data" action="" method="post">   

					<input type="hidden" name="action" value="withinweb_wwkc_keycodes_localtest" />				
					<?php wp_nonce_field( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_localtest' ); ?>				

					<table class="form-table">
						<tbody>

							<tr class="form-field form-required">
								<th scope="row"><label for="receiver_email"><?php _e('Receiver email', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"></span><?php _e('This is the email address which PayPal uses to send confirmation emails, normally it is your email address', 'withinweb-wwkc-keycodes'); ?></label></th>
								<td><input type="email" id="receiver_email" name="receiver_email" value="<?php echo esc_html( get_option('withinweb_wwkc_keycodes_paypal_email') ); ?>" style="width:200px;" required /></td>
							</tr>

							<tr class="form-field form-required">
								<th scope="row"><label for="item_number"><?php _e('Item number reference', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('The item number reference of the item.', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td>
									<input type="text" name="item_number" id="item_number" value="<?php echo( esc_html( $row->item_number )); ?>" style="width:200px;" required />
								</td>
							</tr>
							
							<tr class="form-field form-required">
								<th scope="row"><label for="quantity"><?php _e('Quantity', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"></span></label></th>
								<td>
									<input type="number" name="quantity" id="quantity" value="1" min="1" step="1" style="width:100px;" required />
								</td>
							</tr>							

							<tr class="form-field form-required">
								<th scope="row"><label for="payment_status"><?php _e('Payment status', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('Defines the status from Paypal - selecting completed will simulate a completed transaction.', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td>                    	
									<select name="payment_status" required>
										<option value="Completed" selected>Completed</option>
										<option value="Pending">Pending</option>
										<option value="Failed">Failed</option>
										<option value="Denied">Denied</option>
									</select>    
								</td>
							</tr>

							<tr class="form-field form-required">
								<th scope="row"><label for="mc_gross"><?php _e('Payment gross', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('The gross value of the goods which must be the same as entered into the database in the form of x.yz.', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td>                   
									<input type="number" id="mc_gross" name="mc_gross" value="<?php echo( esc_html( $row->mc_gross )); ?>" style="width:100px;" min="0" step="0.01" required />
								</td>
							</tr>

							<tr class="form-field form-required">
								<th scope="row"><label for="mc_currency"><?php _e('Currency Code', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('The currency code which must be the same as entered into the database.', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td>

									<select id="mc_currency" name="mc_currency" required>
										<option value="AUD" <?php if ( $row->mc_currency == "AUD" ) { echo(" selected "); } ?>>AUD</option>
										<option value="CAD" <?php if ( $row->mc_currency == "CAD" ) { echo(" selected "); } ?>>CAD</option>
										<option value="CSK" <?php if ( $row->mc_currency == "CSK" ) { echo(" selected "); } ?>>CSK</option>
										<option value="DKK" <?php if ( $row->mc_currency == "DKK" ) { echo(" selected "); } ?>>DKK</option>
										<option value="EUR" <?php if ( $row->mc_currency == "EUR" ) { echo(" selected "); } ?>>EUR</option>
										<option value="HKD" <?php if ( $row->mc_currency == "HKD" ) { echo(" selected "); } ?>>HKD</option>
										<option value="ILS" <?php if ( $row->mc_currency == "ILS" ) { echo(" selected "); } ?>>ILS</option>
										<option value="JPY" <?php if ( $row->mc_currency == "JPY" ) { echo(" selected "); } ?>>JPY</option>
										<option value="MXN" <?php if ( $row->mc_currency == "MXN" ) { echo(" selected "); } ?>>MXN</option>
										<option value="NOK" <?php if ( $row->mc_currency == "NOK" ) { echo(" selected "); } ?>>NOK</option>
										<option value="NZD" <?php if ( $row->mc_currency == "NZD" ) { echo(" selected "); } ?>>NZD</option>
										<option value="PHP" <?php if ( $row->mc_currency == "PHP" ) { echo(" selected "); } ?>>PHP</option>
										<option value="PLN" <?php if ( $row->mc_currency == "PLN" ) { echo(" selected "); } ?>>PLN</option>
										<option value="GBP" <?php if ( $row->mc_currency == "GBP" ) { echo(" selected "); } ?>>GBP</option>
										<option value="RUB" <?php if ( $row->mc_currency == "RUB" ) { echo(" selected "); } ?>>RUB</option>
										<option value="SGD" <?php if ( $row->mc_currency == "SGD" ) { echo(" selected "); } ?>>SGD</option>
										<option value="SEK" <?php if ( $row->mc_currency == "SEK" ) { echo(" selected "); } ?>>SEK</option>
										<option value="CHF" <?php if ( $row->mc_currency == "CHF" ) { echo(" selected "); } ?>>CHF</option>
										<option value="THB" <?php if ( $row->mc_currency == "THB" ) { echo(" selected "); } ?>>THB</option>
										<option value="USD" <?php if ( $row->mc_currency == "USD" ) { echo(" selected "); } ?>>USD</option>
									</select>
									
								</td>
							</tr>        

							<tr class="form-field form-required">
								<th scope="row"><label for="txn_id"><?php _e('Transaction ID', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required) The PayPal transaction id. You can enter any random series of characters here, I place the current time and date.', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td>                   
									<input type="text" id="txn_id" name="txn_id" style="width:200px;" value="<?php echo (date( "d/m/y G:i:s" )); ?>" required />
								</td>
							</tr>  

							<tr class="form-field form-required">
								<th scope="row"><label for="txn_type"><?php _e('Transaction Type', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"></span></label></th>
								<td>
									<select name="txn_type" required>
										<option value="web_accept" selected>Web accept</option>
										<option value="cart">Cart</option>
										<option value="send_money">Send Money</option>
									</select>                    
								</td>
							</tr>

							<tr class="form-field form-required">
								<th scope="row"><label for="txn_type"><?php _e('Payer email', 'withinweb-wwkc-keycodes'); ?></strong>&nbsp;<span class="description"><?php _e('This is the email address of the purchaser.  For this test, enter a personal email address so that you can see the resulting emails.', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td>
									<input type="email" id="payer_email" name="payer_email" style="width:200px;" required />       
								</td>
							</tr>        

						</tbody>
					</table>

					<?php
					if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
						?>
						<p class="submit">
							<input type="submit" value="<?php _e('Submit The Test', 'withinweb-wwkc-keycodes'); ?>" class="button-primary" name="localtest_update" id="localtest_update" />
						</p>        
						<?php
					}				
					?>

			</form>
			<?php

		}
		
	}	
	//--------------------------------------------------------
	// Validate to two decimal places
	public static function withinweb_wwkc_keycodes_validateTwoDecimals($number)
	{
	   if(preg_match('/^[0-9]+\.[0-9]{2}$/', $number))
		 return true;
	   else
		 return false;
	}
	//--------------------------------------------------------
	//Show message  http://www.wprecipes.com/how-to-show-an-urgent-message-in-the-wordpress-admin-area/	
	private static function showMessage($m) {
		
		switch ($m) {
			case '1':
        		?> <div id='message' class='updated fade'><p><strong><?php _e('A local test has been actioned - check your email.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;
    		case '2':
        	 	?> <div id='message' class='error'><p><strong><?php _e('Error - no test was actioned because the Gross value needs to be two decimal places.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;    
		} 		
		
	}		
	
	
}

withinweb_wwkc_keycodes_localtest::init();
?>
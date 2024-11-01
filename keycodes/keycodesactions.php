<?php
//-------------------------------------------------------
//Class for all PayPal actions
class withinweb_wwkc_keycodes_actions {

	//-------------------------------------------------------
	//init
	public static function init() {
		
		add_action('withinweb_wwkc_keycodes_payment_status_completed', array(__CLASS__, 'withinweb_wwkc_function_completed'));
		add_action('withinweb_wwkc_keycodes_payment_status_pending', array(__CLASS__, 'withinweb_wwkc_function_pending'));
		add_action('withinweb_wwkc_keycodes_payment_status_failed', array(__CLASS__, 'withinweb_wwkc_function_failed'));
		add_action('withinweb_wwkc_keycodes_payment_status_denied', array(__CLASS__, 'withinweb_wwkc_function_denied'));
		add_action('withinweb_wwkc_keycodes_payment_status_refunded', array(__CLASS__, 'withinweb_wwkc_function_refunded'));
		add_action('withinweb_wwkc_keycodes_payment_status_partially_refunded', array(__CLASS__, 'withinweb_wwkc_function_partially_refunded'));
		add_action('withinweb_wwkc_keycodes_payment_status_expired', array(__CLASS__, 'withinweb_wwkc_function_expired'));
		add_action('withinweb_wwkc_keycodes_payment_status_processed', array(__CLASS__, 'withinweb_wwkc_function_processed'));
		add_action('withinweb_wwkc_keycodes_payment_status_canceled_reversal', array(__CLASS__, 'withinweb_wwkc_function_canceled_reversal'));
		add_action('withinweb_wwkc_keycodes_payment_status_in_progress', array(__CLASS__, 'withinweb_wwkc_function_in_progress'));
		add_action('withinweb_wwkc_keycodes_payment_status_reversed', array(__CLASS__, 'withinweb_wwkc_function_reversed'));
		
	}	
	//-------------------------------------------------------
	//complete action	
	public static function withinweb_wwkc_function_completed($posted) {
		//PAYMENT VALIDATED & VERIFIED
		
		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers		
		$headers = self::get_header();			
		
			//check email address - note the trim of the email addresses						
			if ( strcasecmp( trim( $posted["receiver_email"] ), trim( $receiver_email ) ) == 0) {

					switch( strtolower( $posted["txn_type"] ) ) {
						case "web_accept": //"web_accept": The payment was sent by your customer via a Buy Now button.		

							$emailtext = "";
							
							//This is single item PayPal selection	
							//If we know the item_name we now have to find the email details of this.
	 
							//Get email details for this item	
							$itemdetails = self::withinweb_wwkc_keycodes_itemdetails( sanitize_text_field ( $posted['item_number'] ));

								$item_name 			= $itemdetails['item_name'];
								$item_number		= $itemdetails['item_number'];
								$emailsubject 		= $itemdetails['emailsubject'];
								$emailtextkeycodes	= $itemdetails['emailtextkeycodes'];
							
							$adminaddress  = sanitize_email ( get_option( "admin_email" ) );	//Your admin email address
							
							//Transaction OK, so now make further tests and processing
							self::withinweb_wwkc_keycodes_process($posted, $receiver_email, $adminaddress, $emailsubject, $emailtext, $emailtextkeycodes, 1, 'false' );
							
							$req_format = self::withinweb_wwkc_keycodes_format_post($posted);
							wp_mail( $receiver_email, "IPN - VERIFIED and SINGLE ITEM PURCHASE", $req_format, $headers );
							break;

						case "cart":  //"cart": This payment was sent by your customer via the Shopping Cart featureS					

						case "send_money":							

							$reason = "This payment was sent by your customer from the PayPal website using the Send Money tab\r\n";
							wp_mail( $receiver_email, "IPN - VERIFIED and SOMEBODY SENT MONEY", $reason, $headers );
							break;

						//subscr_signup, subscr_cancel,subscr_failed,subscr_eot,subscr_modify - these do not have a payment_status of Completed

						case "subscr_payment":

							$reason = "This IPN is for a subscription payment\r\n";
							wp_mail( $receiver_email, "IPN - VERIFIED and SUBSCRIPTION PAYMENT", $reason, $headers );
							break;		

						default:							

							$reason = "Unknown txn type is received\r\n";
							wp_mail( $receiver_email, "IPN - Unknown txn type has been received", $reason, $headers );
							break;								

						}

			}
			else					
			{
				$reason = "This usually means that you have multiple emails registered with PayPal but you have not used the primary email address in the PHP-Keycodes admin set up";
				wp_mail( $receiver_email, "IPN - VERIFIED but incorrect Receiver_email address, no action taken", $reason, $headers );
			}
		
	}
	//-------------------------------------------------------
	//pending action
    public static function withinweb_wwkc_function_pending($posted) {
		
		//Send email saying it is pending
		switch( strtolower ( $posted["pending_reason"]  )  ) {
			case "address":
				$reason = "The payment is pending because your customer did not include a confirmed shipping address and you, the merchant, have your Payment Receiving Preferences set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile";
				break;								
			case "authorization";
				$reason = "The payment is pending because it has been authorized but not settled. You must capture the funds first.";
				break;								
			case "echeck":
				$reason = "The payment is pending because it was made by an eCheck, which has not yet cleared";
				break;								
			case "intl":
				$reason = "The payment is pending because you, the merchant, hold an international account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview";
				break;								
			case "multi-currency":
				$reason = "You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment";
				break;							
			case "order":
				$reason = "The payment is pending because it is part of an order that has been authorized but not settled.";
				break;
			case "paymentreview":
				$reason = "The payment is pending while it is being reviewed by PayPal for risk.";
				break;
			case "unilateral":
				$reason = "The payment is pending because it was made to an email address that is not yet registered or confirmed.";
				break;								
			case "upgrade":
				$reason = "The payment is pending because it was made via credit card and you, the merchant, must upgrade your account to Business or Premier status in order to receive the funds";
				break;								
			case "verify":
				$reason = "The payment is pending because you, the merchant, are not yet verified. You must verify your account before you can accept this payment";
				break;								
			case "other":
				$reason = "The payment is pending for some other reason. For more information, contact PayPal customer service";
				break;
			default:
				$reason = "Unknown pending reason was received.";
				break;				
		}
		
		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers		
		$headers = self::get_header();
		
		//Send email to admin
		wp_mail( $receiver_email, "IPN - VERIFIED and PENDING", $reason, $headers );
		//Send email to customer
		wp_mail( $posted["payer_email"], "PayPal purchase verified and order is waiting to be processed", $reason, $headers );
		
	}
	//-------------------------------------------------------
	//failed action
	public static function withinweb_wwkc_function_failed($posted) {

		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers		
		$headers = self::get_header();

		$reason = "This only happens if the payment was made from your customer's bank account.\r\n\r\n";
		wp_mail( $receiver_email, "IPN - VERIFIED and FAILED", $reason, $headers );
	}
	//-------------------------------------------------------
	//denied action
	public static function withinweb_wwkc_function_denied($posted) {

		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers		
		$headers = self::get_header();
		
		$reason = "You, the merchant, denied the payment. This will only happen if the payment was previously pending due to one of the pending reasons\r\n\r\n";
		wp_mail( $receiver_email, "IPN - VERIFIED and DENIED", $reason, $headers );		
	}
	//-------------------------------------------------------
	//refunded action
	public static function withinweb_wwkc_function_refunded($posted) {

		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers		
		$headers = self::get_header();
		
		$reason = "You refunded the payment\r\n\r\n";
		wp_mail( $receiver_email, "IPN - VERIFIED and REFUNDED", $reason, $headers );
	}
	//-------------------------------------------------------
	//partially-refunded
	public static function withinweb_wwkc_function_partially_refunded($posted) {

		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers		
		$headers = self::get_header();
		
		$reason = "The transaction has been partially refunded\r\n\r\n";
		wp_mail( $receiver_email, "IPN - VERIFIED and PARTIALLY-REFUNDED", $reason, $headers );
	}
	//-------------------------------------------------------
	//expired
	public static function withinweb_wwkc_function_expired($posted) {

		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers		
		$headers = self::get_header();

		$reason = "This authorization has expired and cannot be captured\r\n\r\n";
		wp_mail( $receiver_email, "IPN - VERIFIED and EXPIRED", $reason, $headers );
	}
	//-------------------------------------------------------
	//processed
	public static function withinweb_wwkc_function_processed($posted) {

		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers		
		$headers = self::get_header();
			
		$reason = "A payment has been accepted\r\n\r\n";
		wp_mail( $receiver_email, "IPN - VERIFIED and PROCESSED", $reason, $headers );
	}
	//-------------------------------------------------------
	//canceled-reversal	
	public static function withinweb_wwkc_function_canceled_reversal($posted) {
		
		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers		
		$headers = self::get_header();		
		
		$reason = "A reversal has been cancelled. For example, you won a dispute with the customer, and the funds for the transaction that was reversed have been returned to you.\r\n\r\n";
		wp_mail( $receiver_email, "IPN - VERIFIED and CANCELLED-REVERSAL", $reason, $headers );		
	}
	//-------------------------------------------------------	
	//in-progress	
	public static function withinweb_wwkc_function_in_progress($posted) {
		
		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers
		$headers = self::get_header();		
		
		$reason = "The transaction is in process of authorization and capture\r\n\r\n";
		wp_mail( $receiver_email, "IPN - VERIFIED and IN-PROGRESS", $reason, $headers );		
	}
	//-------------------------------------------------------
	//reversed
	public static function withinweb_wwkc_function_reversed($posted) {
	
		//Get receiver email address
		$receiver_email = self::get_receiver_email();
		//Get headers
		$headers = self::get_header();	
		
		$reason = "A payment was reversed due to a chargeback or other type of reversal. The funds have been removed from your account balance and returned to the buyer.\r\n\r\n";
		wp_mail( $receiver_email, "IPN - VERIFIED and REVERSED", $reason, $headers );		
	}
	//-------------------------------------------------------
	//Get receiver email address
	public static function get_receiver_email() {
		
		if ( get_option('withinweb_wwkc_keycodes_paypal_environment') == "live") {$receiver_email = sanitize_email( get_option('withinweb_wwkc_keycodes_paypal_email') );}
		if ( get_option('withinweb_wwkc_keycodes_paypal_environment') == "sandbox") {$receiver_email = sanitize_email( get_option('withinweb_wwkc_keycodes_sandbox_paypal_email') );}
		
		return $receiver_email;
	}	
	//-------------------------------------------------------
	//Get email headers
	public static function get_header() {
		
		$withinweb_wwkc_keycodes_admin_email  = sanitize_email ( get_option( "admin_email" ) );	//Your admin email address
		$headers = "From:" . $withinweb_wwkc_keycodes_admin_email . "\r\n";   //header with separater for headers
	
		return $headers;
	}	
	//-------------------------------------------------------
	/**
	* Purpose	:	Format the array into a better list
	* Inputs	:	The data
	* @return string
	*/
	public static function withinweb_wwkc_keycodes_format_post($data) {

		if ( is_array ($data) ) {
			$file_values = "";
			foreach($data as $key=>$val) {
				$file_values .= "$key = $val\r\n";
			}

			return $file_values;

		}
		else
			{ 
			return false; 
			}

	}
	//-------------------------------------------------------
	/**
	* Process the transaction for single purchase item
	* Check if the mc_gross payment value is correct
	* Check if the mc_currency code is correct
	* Check if txn_id has been used before
	* Save the data to the database
	* Send the email
	*
	* For keycodegoods only check that the payment sent is at least the database value but do not exist.
	* Also only send a warning email if payment and currency values don't match
	* For digital goods and key codes, check if database value is less than or equal to paypal value, if not 
	* it will exit and send an email
	*/
	public static function withinweb_wwkc_keycodes_process($paypal, $toaddress, $fromaddress, $emailsubject, 
															$emailtext, $emailtextkeycodes, $copyemail, $testmode ) {

		$txn_id 		=	$paypal["txn_id"];
		$item_number 	=	$paypal["item_number"];	
		$item_name 		=	$paypal["item_name"];
		$payment_status =	$paypal["payment_status"];
		$mc_gross		=	$paypal["mc_gross"];
		$mc_currency	=	$paypal["mc_currency"];
		$receiver_email	=	$paypal["receiver_email"];
		$payer_email	=	$paypal["payer_email"];
		$quantity		=	$paypal["quantity"];		
		
		/*	echo("txn_id : $txn_id<br>");
		echo("item_number : $item_number<br>");
		echo("item_name : $item_name<br>");	
		echo("payment_status : $payment_status<br>");	
		echo("mc_gross : $mc_gross<br>");	
		echo("mc_currency : $mc_currency<br>");	
		echo("receiver_email : $receiver_email<br>");	
		echo("payer_email : $payer_email<br>");	
		echo("email text : $emailtext<br>");
		exit();*/	

		//Get headers for eamail
		$headers = self::get_header();

		//Get tblitems details given the item_number		
		$itemdetails = self::withinweb_wwkc_keycodes_itemdetails($item_number);	//returns an array

		if ( $itemdetails )
		{

			$recid				= $itemdetails['recid'];
			$item_name			= $itemdetails['item_name'];
			$item_number		= $itemdetails['item_number'];
			$emailsubject 		= $itemdetails['emailsubject'];
			$emailtextkeycodes	= $itemdetails['emailtextkeycodes'];
			$dbpaymentgross 	= $itemdetails['mc_gross'];
			//$isphysical			= $itemdetails['physicalgoods'];
			//$isdigital			= $itemdetails['digitalgoods'];
			//$iskeycodes			= $itemdetails['keycodesgoods'];
			$dbmc_currency 		= $itemdetails['mc_currency'];
		
		
			$ipnqty = $quantity;					//quantity received back from IPN
			$ipnvalue = $mc_gross;					//value received back from IPN
			$ipnunitvalue = $ipnvalue/$ipnqty;		//unit value
			
			
			$iskeycodes = 1;			
			if ($iskeycodes == 1) {
				//if ( $dbpaymentgross > $mc_gross ) {	// $mc_gross must always be more than or equal to the entry in the database	* quantity
				if ( $dbpaymentgross > $ipnunitvalue ) {	//Check to see that unit value in database is not larger than value received from IPN
					wp_mail( $toaddress, "IPN - Error : Payment Gross is not correct", "No action taken, no goods sent\r\nDatabase unit value : $dbpaymentgross, Amount paid unit value : $ipnunitvalue", $headers);
					exit();		//exit with key codes
				}	
			}			
			
			//Now check if the currency code is correct	
			if (strcmp($dbmc_currency, $mc_currency) != 0) {
				if ($iskeycodes == 1) {				
					wp_mail( $toaddress, "IPN - Error : Currency Code is not correct", "No action taken, no goods sent\r\nDatabase entry : $dbmc_currency, mc_currency : $mc_currency", $headers );
					exit(); //exit here for key codes
				}		
			}
			
			//Now check if the txn_id has been used before
			if ( self::withinweb_wwkc_keycodes_checktxnid($txn_id) != 0 ) {
				//wp_mail( $toaddress, "IPN - Error : Txn_ID has been duplicated", "No action taken, no goods sent\r\ntxn_id : $txn_id", $headers );
				exit();		
			}
	
			if ($iskeycodes == 1) {		//uses $emailtextkeycodes
				self::withinweb_wwkc_keycodes_savetodbkeycodes($paypal, $recid, $txn_id, $item_number, $item_name, $payment_status, $mc_gross, 
												$mc_currency, $receiver_email, $payer_email, $fromaddress, $emailsubject, 
												$emailtextkeycodes, $copyemail, $toaddress, $testmode);
			}
				
			
		}
		
	}
	//-----------------------------------------------------------------
	/**
	//Purpose : For key codes, save the details to database - KEY CODES SINGLE ITEM PURCHASE
	//Note that key code goods do not store the user id because no entry is created in the tblusers table
	*/
	static public function withinweb_wwkc_keycodes_savetodbkeycodes($paypal, $recid, $txn_id, $item_number, $item_name, $payment_status, 
											 $mc_gross, $mc_currency, $receiver_email, $payer_email, $fromaddress, 
											 $emailsubject, $emailtext, $copyemail, $toaddress, $testmode) {

		//Get headers for eamail
		$headers = self::get_header();		

		//create database entry with current date/time for 
		//tblsaleshistory and convert to yyyy-mmm-dd time i.e. date("Y-m-d H:i:s")
	
		$currentdate = date("Y-m-d H:i:s");
	
		$item_name_s 			= 	$item_name;
		$receiver_email_s 		= 	$receiver_email;
		$payer_email_s 			= 	$payer_email;
		
		if ( isset ( $paypal["quantity"] ) ) 			{ $quantity = substr( $paypal["quantity"] , 0, 254); } else { $quantity = ""; }
		
		if ( isset ( $paypal["custom"] ) ) 				{ $custom_s = substr( $paypal["custom"] , 0, 254); } else { $custom_s = ""; }

		if ( isset ( $paypal["business"] ) ) 			{ $business_s = substr( $paypal["business"] , 0, 254); } else { $business_s = ""; }
		if ( isset ( $paypal["receiver_id"] ) ) 		{ $receiver_id_s = substr( $paypal["receiver_id"] , 0, 254); } else { $receiver_id_s = ""; }
		
	
		if ( isset ( $paypal["invoice"] ) ) 			{ $invoice_s = substr( $paypal["invoice"] , 0, 254); } else { $invoice_s = ""; }	
		if ( isset ( $paypal["memo"] ) ) 				{ $memo_s = $paypal["memo"]; } else { $memo_s = ""; }	
		if ( isset ( $paypal["tax"] ) ) 				{ $tax_s = substr( $paypal["tax"] , 0, 254); } else { $tax_s = ""; }
	
		if ( isset ( $paypal["option_name1"] ) ) 		{ $option_name1_s = substr( $paypal["option_name1"] , 0, 254 ); } else { $option_name1_s = ""; }
		if ( isset ( $paypal["option_selection1"] ) ) 	{ $option_selection1_s = substr( $paypal["option_selection1"] , 0, 254 ); } else { $option_selection1_s = ""; }	
		if ( isset ( $paypal["option_name2"] ) ) 		{ $option_name2_s = substr( $paypal["option_name2"] , 0, 254 ); } else { $option_name2_s = ""; }
		if ( isset ( $paypal["option_selection2"] ) ) 	{ $option_selection2_s = substr( $paypal["option_selection2"] , 0, 254 ); } else { $option_selection2_s = ""; }
		if ( isset ( $paypal["num_cart_items"] ) ) 		{ $num_cart_items_s = substr( $paypal["num_cart_items"] , 0, 254 ); } else { $num_cart_items_s = ""; }
		
		if ( isset ( $paypal["mc_fee"] ) ) 				{ $mc_fee_s = substr( $paypal["mc_fee"] , 0, 254 ); } else { $mc_fee_s = ""; }	
		if ( isset ( $paypal["payment_date"] ) ) 		{ $payment_date_s = substr( $paypal["payment_date"] , 0, 254 ); } else { $payment_date_s = ""; }
		if ( isset ( $paypal["payment_type"] ) ) 		{ $payment_type_s = substr( $paypal["payment_type"] , 0, 254 ); } else { $payment_type_s = ""; }

		if ( isset ( $paypal["first_name"] ) ) 			{ $first_name_s = substr( $paypal["first_name"] , 0, 254); } else { $first_name_s = ""; }	
		if ( isset ( $paypal["last_name"] ) ) 			{ $last_name_s = substr( $paypal["last_name"] , 0, 254); } else { $last_name_s = ""; }	
		if ( isset ( $paypal["payer_business_name"] ) ) { $payer_business_name_s = substr( $paypal["payer_business_name"] , 0, 254); } else { $payer_business_name_s = ""; }
		if ( isset ( $paypal["address_name"] ) ) 		{ $address_name_s = substr( $paypal["address_name"] , 0, 254); } else { $address_name_s = ""; }
		if ( isset ( $paypal["address_street"] ) ) 		{ $address_street_s = substr( $paypal["address_street"] , 0, 254); } else { $address_street_s = ""; }
		if ( isset ( $paypal["address_city"] ) ) 		{ $address_city_s = substr( $paypal["address_city"] , 0, 254); } else { $address_city_s = ""; }
		if ( isset ( $paypal["address_state"] ) ) 		{ $address_state_s = substr( $paypal["address_state"] , 0, 254); } else { $address_state_s = ""; }
		if ( isset ( $paypal["address_zip"] ) ) 		{ $address_zip_s = substr( $paypal["address_zip"] , 0, 254); } else { $address_zip_s = ""; }
		if ( isset ( $paypal["address_country"] ) ) 	{ $address_country_s = substr( $paypal["address_country"] , 0, 254); } else { $address_country_s = ""; }	
		if ( isset ( $paypal["address_status"] ) ) 		{ $address_status_s = substr( $paypal["address_status"] , 0, 254); } else { $address_status_s = ""; }	
		if ( isset ( $paypal["payer_id"] ) ) 			{ $payer_id_s = substr( $paypal["payer_id"] , 0, 254); } else { $payer_id_s = ""; }
		if ( isset ( $paypal["payer_status"] ) ) 		{ $payer_status_s = substr( $paypal["payer_status"] , 0, 254); } else { $payer_status_s = ""; }
		if ( isset ( $paypal["notify_version"] ) ) 		{ $notify_version_s = substr( $paypal["notify_version"] , 0, 254); } else { $notify_version_s = ""; }
		if ( isset ( $paypal["verify_sign"] ) ) 		{ $verify_sign_s = substr( $paypal["verify_sign"] , 0, 254); } else { $verify_sign_s = ""; }	
	
		//Now check if the txn_id has been used before
		//This is to prevent duplicate saving into database and to stop email being sent out to customers twice.
		if (self::withinweb_wwkc_keycodes_checktxnid($txn_id, $toaddress, $fromaddress, $testmode) != 0) {	
			exit();
		}
	
		//--------------------------------------------	
		//Check if there are key codes in the database for this $recid
		//If there are then :
			//Read the top key code from the database.
			//If there are less then the lower limit then send an email
			//Delete the key codes from the database
			//Add it to the email message
			//If not then send an email
			
		//Use add_filter and apply_filter to return values
		$numberofkeycodes = apply_filters("withinweb_wwkc_keycodes_getnoofkeycodes", $paypal, $recid);
		
 		$debug = (get_option('withinweb_wwkc_keycodes_paypal_debug') == '1') ? 'yes' : 'no';
        if ('yes' == $debug) {
            $log = new withinweb_wwkc_keycodes_Logger();
            $log->add('paypal', 'Number of keycodes: ' . $numberofkeycodes);
        }

		if ($numberofkeycodes > 0) {

			$notoretrieve = $quantity;			//The number to retreive
			$deletetopitem = false;				

			$keycodes = apply_filters("withinweb_wwkc_keycodes_getnextkeycodes", $paypal, $recid, $notoretrieve, $deletetopitem);  //get top key code and delete it from the database
			$lowerlimit = apply_filters("withinweb_wwkc_keycodes_getlowerlimit", $paypal, $recid);

			//--------------------------------------
			//Send email to customer
			//Case insensitive replace.				
			$emailtext 		= preg_replace("/key_codes/i", $keycodes, $emailtext);				
			$emailtext 		= preg_replace("/email_address/i", $payer_email, $emailtext);		
			$emailtext 		= preg_replace("/first_name/i", $first_name_s, $emailtext);
			$emailtext 		= preg_replace("/last_name/i", $last_name_s, $emailtext);		
			$emailsubject 	= preg_replace("/item_name/i", $item_name, $emailsubject);				
			//--------------------------------------  		
		
			wp_mail( $payer_email, $emailsubject, $emailtext, $headers );

			$emailsubject = $emailsubject . " (copy)";        
			wp_mail( $toaddress, $emailsubject, $emailtext, $headers );        


			if ($numberofkeycodes <= $lowerlimit) {		
				//send email to administrator saying that lower limit has been reached.
				$emailtext = "You have reached the lower limit for key codes in the product item : " . $item_number;
				$emailsubject = "Key code lower limit reached";
				wp_mail( $toaddress, $emailsubject, $emailtext, $headers );                    
			}         

			$keycodes_s = $keycodes;

		}
		else
		{

			//send email to administrator saying that lower limit has been reached.
			$emailtext = "You have reached the lower limit for key codes in the product item : " . $item_number;
			$emailsubject = "Key code lower limit reached";
			wp_mail( $toaddress, $emailsubject, $emailtext, $headers );

		}


		if ($testmode == "true") {
			$payment_status = "LOCALTEST " . $payment_status;
		}
		else
		{
			$payment_status = "PAYPAL " . $payment_status;		
		}


		if ('yes' == $debug) {			
			//wp_mail( $toaddress, "Test 7", self::withinweb_wwkc_keycodes_format_post($paypal), $headers );
			$log->add('paypal', 'PayPal post: ' . print_r($paypal, true));			
		}
		

		global $wpdb;
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_saleshistory";
		$rows_affected = $wpdb->query( $wpdb->prepare( 
			"
				INSERT INTO $table_name
				(
				licencecodes,
				business,
				receiver_id,
				invoice,
				memo,
				tax,
				option_name1,
				option_selection1,
				option_name2,
				option_selection2,

				num_cart_items,
				mc_fee,
				payment_date,
				payment_type,
				first_name,
				last_name,
				payer_business_name,
				address_name,
				address_street,
				address_city,

				address_state,
				address_zip,
				address_country,
				address_status, 
				payer_id,
				payer_status,
				notify_version,
				verify_sign,
				custom,	

				txn_id,
				item_number,
				item_name,
				payment_status,
				mc_gross,
				mc_currency,
				receiver_email,
				payer_email,
				completed,
				quantity
				)

				VALUES
				(
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 

				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',

				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',	

				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
				)
			", 
				array(

				$keycodes_s, 
				$business_s, 
				$receiver_id_s,
				$invoice_s,
				$memo_s,
				$tax_s,
				$option_name1_s,
				$option_selection1_s,
				$option_name2_s, 
				$option_selection2_s,

				$num_cart_items_s,
				$mc_fee_s,
				$payment_date_s,
				$payment_type_s,
				$first_name_s,
				$last_name_s,
				$payer_business_name_s,
				$address_name_s,
				$address_street_s,
				$address_city_s,

				$address_state_s,
				$address_zip_s,
				$address_country_s,
				$address_status_s, 
				$payer_id_s,
				$payer_status_s,
				$notify_version_s,
				$verify_sign_s,
				$custom_s,	

				$txn_id,
				$item_number,
				$item_name_s,
				$payment_status,
				$mc_gross,
				$mc_currency,
				$receiver_email_s,
				$payer_email_s,
				$currentdate,
				$quantity			

				) 
			) );

		
 		$debug = (get_option('withinweb_wwkc_keycodes_paypal_debug') == '1') ? 'yes' : 'no';
        if ('yes' == $debug) {
            $log = new withinweb_wwkc_keycodes_Logger();
            $log->add('paypal', 'Insert query: ' . print_r($wpdb->last_query, true));
        }		
		
		//exit();				

		//$query = " INSERT INTO " . $dbprefix . "key_tblsaleshistory ";
		//$query .= " ( ";
		//$query .= " licencecodes, business, receiver_id, invoice, memo, tax, option_name1, option_selection1, option_name2, option_selection2,  num_cart_items, mc_fee, payment_date, payment_type, ";
		//$query .= " first_name, last_name, payer_business_name, address_name, address_street, address_city, address_state, address_zip, address_country, address_status, payer_id, payer_status, ";
		//$query .= " notify_version, verify_sign, custom,  item_id, txn_id, item_number, item_name, payment_status, mc_gross, mc_currency, receiver_email, payer_email, ";
		//$query .= " completed, quantity ";
		//$query .= " ) ";
		//$query .= " VALUES ";
		//$query .= " ( ";
		//$query .= "	'$keycodes_s', '$business_s', '$receiver_id_s', '$invoice_s', '$memo_s', '$tax_s', '$option_name1_s', '$option_selection1_s', '$option_name2_s', '$option_selection2_s', '$num_cart_items_s', ";
		//$query .= " '$mc_fee_s', '$payment_date_s', '$payment_type_s', '$first_name_s', '$last_name_s', '$payer_business_name_s', '$address_name_s', '$address_street_s', '$address_city_s', '$address_state_s', ";
		//$query .= " '$address_zip_s', '$address_country_s', '$address_status_s', '$payer_id_s', '$payer_status_s', '$notify_version_s', '$verify_sign_s', '$custom_s', $recid, '$txn_id', '$item_number', ";
		//$query .= " '$item_name_s', '$payment_status', '$mc_gross', '$mc_currency', '$receiver_email_s', '$payer_email_s', '$currentdate', $quantity "; 
		//$query .= " ) ";	


		if ($rows_affected == 0)
		{
			$emailsubject = "SQL error - wordpress-actions.php ref 1";
			$emailtext = "Error saving to saleshistory table";
			wp_mail( $toaddress, $emailsubject, $emailtext, $headers );
			exit();	
		}	


	}	
	//-----------------------------------------------------------------
	/**
	* Purpose : Checks if the txn_id has already been used
	* Inputs : The txn_id to check against
	* Outputs : The number of records found
	* @return integer
	*/
	static public function withinweb_wwkc_keycodes_checktxnid($txn_id) {		

		//Get item details
		global $wpdb;	
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_saleshistory";
		//$row = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE txn_id = '" . $txn_id . "' " );

		$row = $wpdb->get_row( $wpdb->prepare( 
			"
				SELECT * FROM $table_name WHERE txn_id = %s
			", 
				array(
				$txn_id			
			) 
		) );

		//$headers = "From:" . $fromaddress . "\r\n";   //header with separater for headers
		//wp_mail( $toaddress, "Test - process", "SELECT * FROM " . $table_name . " WHERE txn_id = '" . $txn_id . "' ", $headers);
		//exit();

		return count($row);

	}
	//----------------------------------------------------------
	/**
	* Purpose	:	Get tblitems details given the item_number of the record
	* Outputs	:	Certain details from tblitems in an array
	* @return array
	*/
	static public function withinweb_wwkc_keycodes_itemdetails($item_number) {

		//Get item details
		global $wpdb;	
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
		//$row = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE item_number = '" . $item_number . "' " );

		$row = $wpdb->get_row( $wpdb->prepare( 
			"
				SELECT * FROM $table_name WHERE item_number = %s
			", 
				array(
				$item_number			
			) 
		) );

		$array_name = array();
		
		if ( $row )
		{		
			
			$recid 								= $row->recid;			
			$item_name							= $row->item_name;
			$item_number						= $row->item_number;
			$mc_gross							= $row->mc_gross;
			$item_number						= $row->item_number;
			$mc_currency						= $row->mc_currency;
			$emailsubject						= $row->emailsubject;
			$emailtextkeycodes					= $row->emailtextkeycodes;
		
			$array_name['recid'] 				= $recid;
			$array_name['item_name'] 			= $item_name;
			$array_name['item_number'] 			= $item_number;
			$array_name['mc_gross'] 			= $mc_gross;
			$array_name['item_number'] 			= $item_number;
			$array_name['mc_currency'] 			= $mc_currency;
			$array_name['emailsubject'] 		= $emailsubject;
			$array_name['emailtextkeycodes'] 	= $emailtextkeycodes;
			
		}

		return $array_name;
	}	
	//----------------------------------------------------------
	
}
//-------------------------------------------------------

withinweb_wwkc_keycodes_actions::init();
?>
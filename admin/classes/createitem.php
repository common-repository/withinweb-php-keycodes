<?php
//Create item 
class withinweb_wwkc_keycodes_createitem {
	
	//------------------------------------------------------------
	 public static function init() {
		add_action('withinweb_wwkc_keycodes_createitem_save_field', array(__CLASS__, 'withinweb_wwkc_keycodes_createitem_save_field'));		 
        add_action('withinweb_wwkc_keycodes_createitem_display', array(__CLASS__, 'withinweb_wwkc_keycodes_createitem_display'));        
    }
	//------------------------------------------------------------
    //save general setting field value 
    public static function withinweb_wwkc_keycodes_createitem_save_field() {
		
		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}		
		
		$message = "1";
		
		//If submitted then update the option       
		if (isset($_POST['admin_createitem']) && !empty($_POST['admin_createitem'])) {		

			if ( !current_user_can( 'manage_options' ) )
			{
			  wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
			}		

			//Check the nonce field
			if ( !check_admin_referer( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_createitem' ) )
			{
				exit();
			}

			if ( isset( $_POST['item_number'] ) )
			{
			  $item_number = sanitize_text_field( $_POST['item_number'] );
			}  

			if ( isset( $_POST['item_name'] ) )
			{
			  $item_name = sanitize_text_field( $_POST['item_name'] );
			}   

			if ( isset( $_POST['item_title'] ) )
			{
			  $item_title = sanitize_text_field( $_POST['item_title'] );
			}   

			if ( isset( $_POST['item_description'] ) )
			{
			  $item_description = sanitize_text_field( $_POST['item_description'] );
			}   

			if ( isset( $_POST['mc_gross'] ) )
			{
			  $mc_gross = sanitize_text_field( $_POST['mc_gross'] );
			  if ( !self::withinweb_wwkc_keycodes_validateTwoDecimals($mc_gross) ) {				  
				$message = "3";				  
			  }
			}   

			if ( isset( $_POST['mc_currency'] ) )   
			{
			  $mc_currency = sanitize_text_field( $_POST['mc_currency'] );
			}   

			if ( isset( $_POST['emailsubject'] ) )   
			{
			  $emailsubject = sanitize_textarea_field( $_POST['emailsubject'] );
			}   

			if ( isset( $_POST['emailtextkeycodes'] ) )   
			{
			  $emailtextkeycodes = sanitize_textarea_field ( $_POST['emailtextkeycodes'] );
			}

			if ( isset( $_POST['lowerlimit'] ) )
			{
			  $lowerlimit = sanitize_text_field( $_POST['lowerlimit'] );
			}

			if (!is_numeric($lowerlimit))
			{
				$message = "3";	
			}

			if ( isset( $_POST['keycodes'] ) )
			{
			  $keycodes = sanitize_textarea_field($_POST['keycodes']);
			}			
			
			if ($message == "1") {			
			
				//-------------------------------
				//Save to table
				global $wpdb;
				$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";

				//Check item_name and item_numebr to see if they are duplciated values
				//$strSQL = " SELECT item_name FROM $table_name WHERE item_name = $item_name ";
				$row1 = $wpdb->get_row( $wpdb->prepare( 
					"
						SELECT item_name FROM $table_name WHERE item_name = %s
					", 
						array(
						$item_name			
					) 
				) );

				//Check item_name and item_numebr to see if they are duplciated values
				//$strSQL = " SELECT item_name FROM $table_name WHERE item_name = $item_name ";
				$row2 = $wpdb->get_row( $wpdb->prepare( 
					"
						SELECT item_number FROM $table_name WHERE item_number = %s
					", 
						array(
						$item_number			
					) 
				) );


				if ($row1 || $row2)
				{
					//If either row has something in it then it means item_name or item_number exists.
					$message = "4";
				}
				else
				{

					$wpdb->query( $wpdb->prepare( 
						"
							INSERT INTO $table_name
							( item_number, item_name, item_title, item_description, mc_gross, mc_currency, lowerlimit, emailsubject, emailtextkeycodes, keycodes )
							VALUES ( %s, %s, %s, %s, %s, %s, %d, %s, %s, %s  )
						", 
							array(
							$item_number, 
							$item_name, 
							$item_title,
							$item_description,
							$mc_gross,
							$mc_currency,
							$lowerlimit,
							$emailsubject,
							$emailtextkeycodes,
							$keycodes
						) 
					) );

					//exit( var_dump( $wpdb->last_query ) );
					$message = "1";

				}
				//-------------------------------
			
			
			}
			
			self::showMessage($message);			

		}		
		
    }
	//------------------------------------------------------------
	public static function withinweb_wwkc_keycodes_createitem_display() {        
        
		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}
		?>

		<?php if ( ! defined( 'ABSPATH' )  ) exit();

		//Get details for this record and display it.
		global $wpdb;
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";	
		$total = $wpdb->get_var( " SELECT COUNT('recid') FROM $table_name " );
		
		if ( $total == 0 ) {

		?>
		<div class="wrap">

			<h1><strong><?php _e('WordPress PHP-KeyCodes - Create a new item', 'withinweb-wwkc-keycodes'); ?></strong></h1>
			<h3><?php _e('This page creates a new item', 'withinweb-wwkc-keycodes'); ?></h3>

			<?php
			if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			?>			
			
			<form enctype="multipart/form-data" action="" method="post">				

				<?php wp_nonce_field( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_createitem' ); ?>

				<table class="form-table">
					<tbody>
						<tr class="form-field form-required">
							<th scope="row"><label for="item_name"><?php _e('Item name', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="text" id="item_name" name="item_name"  style="width:200px;" required /></td>
						</tr>

						<tr class="form-field form-required">
							<th scope="row"><label for="item_number"><?php _e('Item number', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="text" id="item_number" name="item_number" style="width:200px;" required /></td>
						</tr>

						<tr class="form-field form-required">
							<th scope="row"><label for="item_title"><?php _e('Item title', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="text" id="item_title" name="item_title" style="width:250px;" required /></td>
						</tr>

						<tr class="form-field form-required">
							<th scope="row"><label for="item_description"><?php _e('Item Description', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="text" id="item_description" name="item_description" style="width:250px;" required /></td>
						</tr>    

						<tr class="form-field form-required">
							<th scope="row"><label for="mc_gross"><?php _e('Gross', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required, in the form x.yz)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="number" id="mc_gross" name="mc_gross" value="0.01" style="width:70px;" min="0" step="0.01" required /></td>
						</tr>                    

						<tr class="form-field form-required">
							<th scope="row"><label for="mc_gross"><?php _e('Currency', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td>
								<select id="mc_currency" name="mc_currency" required>
									<option value=""></option>                      
									<option value="AUD">AUD</option>
									<option value="CAD">CAD</option>
									<option value="CSK">CSK</option>
									<option value="DKK">DKK</option>
									<option value="EUR">EUR</option>
									<option value="HKD">HKD</option>
									<option value="ILS">ILS</option>
									<option value="JPY">JPY</option>
									<option value="MXN">MXN</option>
									<option value="NOK">NOK</option>
									<option value="NZD">NZD</option>
									<option value="PHP">PHP</option>
									<option value="PLN">PLN</option>
									<option value="GBP">GBP</option>
									<option value="RUB">RUB</option>
									<option value="SGD">SGD</option>
									<option value="SEK">SEK</option>
									<option value="CHF">CHF</option>
									<option value="THB">THB</option>
									<option value="USD" selected>USD</option>
								</select>                        
							</td>
						</tr>                   

						<tr class="form-field form-required">
							<th scope="row"><label for="emailsubject"><?php _e('Email Subject This is the subject of the email when 
							an item is purchased.  <br/>The item name is substituted for item_name when the email is sent.', 'withinweb-wwkc-keycodes'); ?>&nbsp;
							<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="text" id="emailsubject" name="emailsubject" style="width:400px;" value="Subject line of email sent to purchaser for item_name" required /></td>
						</tr>

						<tr class="form-field form-required">
							<th scope="row"><label for="emailtextkeycodes"><?php _e('Email Text for key codes / pins / software 
							licenses<br/>The actual codes are substituted for key_codes when the email is sent.', 'withinweb-wwkc-keycodes'); ?>&nbsp;
							<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><textarea id="emailtextkeycodes" name="emailtextkeycodes" style="width:350px;" rows="6" >A suitable entry for the body text of the email for key codes / pin codes / license codes similar to the following :

To : email_address first_name last_name

Your key code(s) are listed here :

key_codes</textarea></td>
						</tr>                   

						<tr class="form-field form-required">
							<th scope="row"><label for="lowerlimit"><?php _e('Lower Limit', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><input type="number" id="lowerlimit" name="lowerlimit" value="5" style="width:50px;" required /></td>
						</tr>                   

						<tr class="form-field form-required">
							<th scope="row"><label for="keycodes"><?php _e('Key Codes', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
							<td><textarea id="keycodes" name="keycodes" style="width:350px;" rows="6"></textarea></td>
						</tr>            

					</tbody>
				</table>

				<p class="submit">
				<input type="submit" value="<?php _e('Create Item', 'withinweb-wwkc-keycodes'); ?>" class="button-primary" name="admin_createitem" id="admin_createitem" />
				</p>

			</form>

		</div>
		<?php
			
		}
		else
		{
	
		?>

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Edit the item', 'withinweb-wwkc-keycodes'); ?></strong></h2>
			<p><?php _e('You can only create one product item in this version', 'withinweb-wwkc-keycodes'); ?></p>
			<?php

			//Look up the row id
			global $wpdb;
			$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
			$items = $wpdb->get_row( " SELECT recid FROM $table_name LIMIT 1 " );

			$rowtoedit = $items->recid;

			$complete_url_edit = wp_nonce_url( 'admin.php?page=withinweb_wwkc_keycodes_admin_display_edititem&recid='.$rowtoedit, 'edit_page', 'refid' );
			?>                
			<td class="role column-role">
				<a href="<?php echo $complete_url_edit; ?>" class='button-primary'><?php _e('Edit this record', 'withinweb-wwkc-keycodes'); ?></a>
			</td>
			<?php

		}
		
	}
	//------------------------------------------------------------
	//Show message  http://www.wprecipes.com/how-to-show-an-urgent-message-in-the-wordpress-admin-area/	
	private static function showMessage($m) {
		
		switch ($m) {
			case '1':
        		?> <div id='message' class='updated fade'><p><strong><?php _e('You have successfully created a new item.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;
    		case '2':
        	 	?> <div id='message' class='error'><p><strong><?php _e('Lower Limit must be an integer value - no data has been saved.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;				
			case '3':
				?> <div id='message' class='error'><p><strong><?php _e('Gross value must be two decimal places - no data has been saved.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
			break;				
			case '4':
				?> <div id='message' class='error'><p><strong><?php _e('Either item name or item number are duplicated values- no data has been saved.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
			break;
		} 		
		
	}
	//------------------------------------------------------------
	private static function withinweb_wwkc_keycodes_validateTwoDecimals($number) {		
		if(preg_match('/^[0-9]+\.[0-9]{2}$/', $number))
	 		return true;
   		else
	 		return false;		
	}	
	
	
}

withinweb_wwkc_keycodes_createitem::init();
?>
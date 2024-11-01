<?php
//Edit item
class withinweb_wwkc_keycodes_edititem {
	
	//------------------------------------------------------------
 	public static function init() {		
		add_action('withinweb_wwkc_keycodes_edititem_save_field', array(__CLASS__, 'withinweb_wwkc_keycodes_edititem_save_field'));
		add_action('withinweb_wwkc_keycodes_edititem_display', array(__CLASS__, 'withinweb_wwkc_keycodes_edititem_display'));		
 	}
	
	//------------------------------------------------------------
    //save general setting field value 
    public static function withinweb_wwkc_keycodes_edititem_save_field() {
		
		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}		
		
	   	$message = "1";		
		
		//If submitted then update the option       
		if (isset($_POST['admin_edititem']) && !empty($_POST['admin_edititem'])) {	

		   // Check the nonce field
		   if ( !check_admin_referer( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_edititem' ) )
		   {
			   //echo("refer failed");
			   exit();
		   }

		   if ( isset( $_POST['recid'] ) )
		   {
			  $recid = sanitize_text_field( $_POST['recid'] );
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

			  if (!self::withinweb_wwkc_keycodes_validateTwoDecimals($mc_gross))
			  {			
				$message = "2";
			  }	  	  

		   }   

		   if ( isset( $_POST['mc_currency'] ) )   
		   {
			  $mc_currency = sanitize_text_field( $_POST['mc_currency'] );
		   }   

		   if ( isset( $_POST['emailsubject'] ) )   
		   {
			  $emailsubject = sanitize_textarea_field($_POST['emailsubject']);
		   }   

		   if ( isset( $_POST['emailtextkeycodes'] ) )   
		   {
			  $emailtextkeycodes = sanitize_textarea_field($_POST['emailtextkeycodes']);
		   }   

		   if ( isset( $_POST['lowerlimit'] ) )
		   {
			  $lowerlimit = sanitize_text_field( $_POST['lowerlimit'] );
		   }   

		   if ( isset( $_POST['keycodes'] ) )
		   {
			  $keycodes = $_POST['keycodes'];
		   }   

			//echo("recid " . $recid . "<br/");
			//echo("item number " . $item_number . "<br/>");
			//echo("item_name " . $item_name . "<br/>");
			//echo("item title " . $item_title . "<br/>");
			//echo("item description " . $item_description . "<br/>");
			//echo("mc gross " . $mc_gross . "<br/>");
			//echo("mc currency " . $mc_currency . "<br/>");
			//echo("emailsubject " . $emailsubject . "<br/>");		
			//echo("email textkey codes " . $emailtextkeycodes . "<br/>");	
			//echo("lower limit " . $lowerlimit . "<br/>");
			//echo("keycodes " . $keycodes . "<br/>");
			//exit();			
			
			//-------------------------------
			//Save to table			
			if ($message == "1") {						

				global $wpdb;
				$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";

				//Check item_name and item_numebr to see if they are duplciated values
				//$strSQL = " SELECT item_name FROM $table_name WHERE item_name = $item_name ";
				$row1 = $wpdb->get_row( $wpdb->prepare( 
					"
						SELECT item_name FROM $table_name WHERE item_name = %s AND recid <> %d
					", 
						array(
						$item_name,
						$recid
					) 
				) );

				//Check item_name and item_numebr to see if they are duplciated values
				//$strSQL = " SELECT item_name FROM $table_name WHERE item_name = $item_name ";
				$row2 = $wpdb->get_row( $wpdb->prepare( 
					"
						SELECT item_number FROM $table_name WHERE item_number = %s AND recid <> %d
					", 
						array(
						$item_number,
						$recid
					) 
				) );	


				if ($row1 || $row2)
				{
					//If either row has something in it then it means item_name or item_number exists.
					$message = "3";
				}
				else
				{
					$wpdb->query( $wpdb->prepare( 
						"
							UPDATE $table_name SET
							item_number = %s, 
							item_name = %s, 
							item_title = %s, 
							item_description = %s, 
							mc_gross = %s, 
							mc_currency = %s, 
							emailsubject = %s, 
							emailtextkeycodes = %s, 
							lowerlimit = %d,
							keycodes = %s
							WHERE 
							recid = %d

						", 
							array(
							$item_number, 
							$item_name, 
							$item_title,
							$item_description,
							$mc_gross,
							$mc_currency,
							$emailsubject,
							$emailtextkeycodes,
							$lowerlimit,			
							$keycodes,
							$recid			
						) 
					) );	
					
					$message = "1";
					
				}					
				//-------------------------------			
				
			}
			
			self::showMessage($message);
		
		}
		
	}	
	//------------------------------------------------------------
	public static function withinweb_wwkc_keycodes_edititem_display() {	
		
		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}		
		
		?>

		<div class="wrap">

			<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?>     

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Edit item', 'withinweb-wwkc-keycodes'); ?></strong></h2>
			<h3><?php _e('This page edits the item', 'withinweb-wwkc-keycodes'); ?></h3>

			<?php
			if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			?>

			<?php

			//---------------------
			//http://wordpress.stackexchange.com/questions/135692/creating-a-wordpress-admin-page-without-a-menu-for-a-plugin
			//---------------------

			//---------------------
			//http://codex.wordpress.org/Function_Reference/check_admin_referer
			//---------------------

			//---------------------	
			//http://codex.wordpress.org/Function_Reference/wp_nonce_url
			//---------------------

			//Check for verification nonce	
			if ( !isset($_GET['recid'] ) || !wp_verify_nonce($_GET['refid'], 'edit_page')) {					
				exit();
			}

			$recid = sanitize_text_field($_GET['recid']);

			//Check if nummeric value
			if ( !is_numeric($recid) ) { exit(); }	

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


			if ( $row )
			{
			?>

				<form enctype="multipart/form-data" action="" method="post">

					<input type="hidden" name="action" value="withinweb_wwkc_keycodes_edititem" />

					<?php wp_nonce_field ( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_edititem' ); ?>

					<input type="hidden" name="recid" value="<?php echo($recid) ?>" />     

					<table class="form-table">
						<tbody>

							<tr class="form-field form-required">
								<th scope="row"><label for="item_number"><?php _e('Item number', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td><input type="text" id="item_number" name="item_number" value="<?php echo( esc_html( $row->item_number) ); ?>" style="width:200px;" required /></td>
							</tr>                

							<tr class="form-field form-required">
								<th scope="row"><label for="item_name"><?php _e('Item name', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td><input type="text" id="item_name" name="item_name" value="<?php echo( esc_html( $row->item_name) ); ?>"  style="width:200px;" required /></td>
							</tr>

							<tr class="form-field form-required">
								<th scope="row"><label for="item_title"><?php _e('Item title', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td><input type="text" id="item_title" name="item_title" value="<?php echo( esc_html( $row->item_title) ); ?>" style="width:250px;" /></td>
							</tr>

							<tr class="form-field form-required">
								<th scope="row"><label for="item_description"><?php _e('Item Description', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td><input type="text" id="item_description" name="item_description" value="<?php echo( esc_html( $row->item_description) ); ?>" style="width:250px;" required /></td>
							</tr>    

							<tr class="form-field form-required">
								<th scope="row"><label for="mc_gross"><?php _e('Gross', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required, in the form x.yz)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td><input type="number" id="mc_gross" name="mc_gross" value="<?php echo( esc_html( $row->mc_gross) ); ?>" style="width:70px;" min="0" step="0.01" required /></td>
							</tr>

							<tr class="form-field form-required">
								<th scope="row"><label for="mc_currency"><?php _e('Currency', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
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
								<th scope="row"><label for="emailsubject"><?php _e('Email Subject', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td><input type="text" id="emailsubject" name="emailsubject" value="<?php echo( esc_html( $row->emailsubject ) ); ?>" style="width:400px;" required /></td>
							</tr>                   

							<tr class="form-field form-required">
								<th scope="row"><label for="emailtextkeycodes"><?php _e('Email Text', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td><textarea id="emailtextkeycodes" name="emailtextkeycodes" style="width:350px;" rows="6" ><?php echo( esc_html( $row->emailtextkeycodes) ); ?></textarea></td>
							</tr>                   

							<tr class="form-field form-required">
								<th scope="row"><label for="lowerlimit"><?php _e('Lower Limit', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td><input type="number" id="lowerlimit" name="lowerlimit" value="<?php echo( esc_html ( $row->lowerlimit) ); ?>" style="width:50px;" required /></td>
							</tr>                   

							<tr class="form-field form-required">
								<th scope="row"><label for="keycodes"><?php _e('Key Codes', 'withinweb-wwkc-keycodes'); ?>&nbsp;<span class="description"><?php _e('(required)', 'withinweb-wwkc-keycodes'); ?></span></label></th>
								<td><textarea id="keycodes" name="keycodes" style="width:350px;" rows="6"><?php echo( $row->keycodes ); ?></textarea></td>
							</tr>

						</tbody>
					</table>		

					<p class="submit">
					<input type="submit" value="<?php _e('Update Item', 'withinweb-wwkc-keycodes'); ?>" class="button-primary" name="admin_edititem" id="admin_edititem" />
					</p>				

				</form>

			<?php
			}
			else
			{
			?>
				<h2><?php _e('No items found', 'withinweb-wwkc-keycodes'); ?></h2>
			<?php	
			}

			?>
		</div>
	<?php
	}	
	//------------------------------------------------------------
	//Show message  http://www.wprecipes.com/how-to-show-an-urgent-message-in-the-wordpress-admin-area/	
	private static function showMessage($m) {
		
		switch ($m) {
			case '1':
        		?> <div id='message' class='updated fade'><p><strong><?php _e('You have successfully updated the item.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;
    		case '2':
        	 	?> <div id='message' class='error'><p><strong><?php _e('Gross value must be two decimal places - no data has been saved.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;
    		case '3':
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

withinweb_wwkc_keycodes_edititem::init();
?>
<?php
//Sales details
class withinweb_wwkc_keycodes_salesdetails {

	//--------------------------------------------------------------------------------------------	
	public static function init() {
		add_action('withinweb_wwkc_keycodes_salesdetails_display', array(__CLASS__, 'withinweb_wwkc_keycodes_salesdetails_display'));		
	}	
	//--------------------------------------------------------------------------------------------
	public static function withinweb_wwkc_keycodes_salesdetails_display() {
		
		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}			
		?>

   		<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?>

		<div class="wrap">

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Sales detail', 'withinweb-wwkc-keycodes'); ?></strong></h2>
			<h3><?php _e('Lists the sales details for this item', 'withinweb-wwkc-keycodes'); ?></h3>   

			<?php
			//Check for verification nonce
			if ( !isset($_GET['recid']) || !wp_verify_nonce($_GET['refid'], 'salesdetails_page')) {
				//invalid click through
				exit();
			}

			$recid = sanitize_text_field($_GET['recid']);

			//Check if nummeric value
			if ( !is_numeric($recid) )
			{
				exit();	
			}	

				//Get details for this record and display it.
				global $wpdb;	
				$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_saleshistory";
				//$row = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE recid = " . $recid );

				$row = $wpdb->get_row( $wpdb->prepare(
					"
						SELECT * FROM $table_name WHERE recid = %d 
					", 
						array(
						$recid
						) 
				) );	

				if ( $row )
				{

					?>

						<table>
							<tbody>
								<tr>
									<td><label for="receiver_email"><span style="font-weight:bold;">receiver_email:</span></label></td>
									<td style="width:30">&nbsp;</td>
									<td><?php echo( esc_html( $row->receiver_email) ); ?></td>
								</tr>

								<tr>
									<td><label for="item_name"><span style="font-weight:bold;">item_name:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->item_name )); ?></td>
								</tr>

								<tr>
									<td><label for="item_number"><span style="font-weight:bold;">item_number:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->item_number )); ?></td>
								</tr>                

								<tr>
									<td><label for="payment_status"><span style="font-weight:bold;">payment_status:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->payment_status )); ?></td>
								</tr>              

								<tr>
									<td><label for="mc_gross"><span style="font-weight:bold;">mc_gross:</span></label></td>
									<td style="width:30">&nbsp;</td>
									<td><?php echo( esc_html( $row->mc_gross )); ?></td>
								</tr>

								<tr>
									<td><label for="payer_email"><span style="font-weight:bold;">payer_email:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->payer_email )); ?></td>
								</tr>                    

								<tr>
									<td><label for="txn_type"><span style="font-weight:bold;">txn_type:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->txn_type )); ?></td>
								</tr> 

								<tr>
									<td><label for="txn_id"><span style="font-weight:bold;">txn_id:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->txn_id )); ?></td>
								</tr> 

								<tr>
									<td><label for="mc_currency"><span style="font-weight:bold;">mc_currency:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->mc_currency) ); ?></td>
								</tr> 		

								<tr>
									<td><label for="completed"><span style="font-weight:bold;">completed:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->completed )); ?></td>
								</tr> 	

								<tr>
									<td><label for="quantity"><span style="font-weight:bold;">quantity:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->quantity )); ?></td>
								</tr> 	 

								<tr>
									<td><label for="licencecodes"><span style="font-weight:bold;">licensecodes:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html ( $row->licencecodes )); ?></td>
								</tr> 	                     

								<tr>
									<td><label for="business"><span style="font-weight:bold;">business:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->business )); ?></td>
								</tr>

								<tr>
									<td><label for="receiver_id"><span style="font-weight:bold;">receiver_id:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->receiver_id )); ?></td>
								</tr>

								<tr>
									<td><label for="invoice"><span style="font-weight:bold;">invoice:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->invoice )); ?></td>
								</tr>

								<tr>
									<td><label for="custom"><span style="font-weight:bold;">custom:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->custom )); ?></td>
								</tr>

								<tr>
									<td><label for="memo"><span style="font-weight:bold;">memo:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->memo )); ?></td>
								</tr>

								<tr>
									<td><label for="tax"><span style="font-weight:bold;">tax:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->tax) ); ?></td>
								</tr>

								<tr>
									<td><label for="option_name1"><span style="font-weight:bold;">option_name1:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->option_name1 )); ?></td>
								</tr>

								<tr>
									<td><label for="option_selection1"><span style="font-weight:bold;">option_selection1:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->option_selection1 )); ?></td>
								</tr>

								<tr>
									<td><label for="option_name2"><span style="font-weight:bold;">option_name2:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->option_name2 )); ?></td>
								</tr>                    

								<tr>
									<td><label for="option_selection2"><span style="font-weight:bold;">option_selection2:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->option_selection2 )); ?></td>
								</tr>       

								<tr>
									<td><label for="num_cart_items"><span style="font-weight:bold;">num_cart_items:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->num_cart_items )); ?></td>
								</tr>       

								<tr>
									<td><label for="mc_fee"><span style="font-weight:bold;">mc_fee:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->mc_fee )); ?></td>
								</tr>

								<tr>
									<td><label for="payment_date"><span style="font-weight:bold;">payment_date:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->payment_date )); ?></td>
								</tr>

								<tr>
									<td><label for="payment_type"><span style="font-weight:bold;">payment_type:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->payment_type )); ?></td>
								</tr>

								<tr>
									<td><label for="first_name"><span style="font-weight:bold;">first_name:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html ( $row->first_name )); ?></td>
								</tr>

								<tr>
									<td><label for="last_name"><span style="font-weight:bold;">last_name:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->last_name )); ?></td>
								</tr>

								<tr>
									<td><label for="payer_business_name"><span style="font-weight:bold;">payer_business_name:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->payer_business_name )); ?></td>
								</tr>

								<tr>
									<td><label for="address_name"><span style="font-weight:bold;">address_name:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->address_name )); ?></td>
								</tr>                    

								<tr>
									<td><label for="address_street"><span style="font-weight:bold;">address_street:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->address_street )); ?></td>
								</tr>

								<tr>
									<td><label for="address_city"><span style="font-weight:bold;">address_city:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->address_city )); ?></td>
								</tr>   

								<tr>
									<td><label for="address_state"><span style="font-weight:bold;">address_state:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->address_state )); ?></td>
								</tr>   

								<tr>
									<td><label for="address_zip"><span style="font-weight:bold;">address_zip:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->address_zip )); ?></td>
								</tr>   

								<tr>
									<td><label for="address_country"><span style="font-weight:bold;">address_country:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->address_country )); ?></td>
								</tr> 

								<tr>
									<td><label for="address_status"><span style="font-weight:bold;">address_status:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->address_status )); ?></td>
								</tr> 

								<tr>
									<td><label for="payer_id"><span style="font-weight:bold;">payer_id:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->payer_id )); ?></td>
								</tr>    

								<tr>
									<td><label for="payer_status"><span style="font-weight:bold;">payer_status:</span></label></td>
									<td style="width:30">&nbsp;</td>
									<td><?php echo( esc_html( $row->payer_status )); ?></td>
								</tr>     

								<tr>
									<td><label for="notify_version"><span style="font-weight:bold;">notify_version:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->notify_version )); ?></td>
								</tr>    

								<tr>
									<td><label for="verify_sign"><span style="font-weight:bold;">verify_sign:</span></label></td>
									<td style="width:30">&nbsp;</td>									
									<td><?php echo( esc_html( $row->verify_sign )); ?></td>
								</tr>                    

							</tbody>
						</table>

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

	
}

withinweb_wwkc_keycodes_salesdetails::init();
?>
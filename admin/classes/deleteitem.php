<?php
//delete item
class withinweb_wwkc_keycodes_deleteitem {

	//--------------------------------------------------
    public static function init() {
		add_action('withinweb_wwkc_keycodes_deleteitem_save_field', array(__CLASS__, 'withinweb_wwkc_keycodes_deleteitem_save_field'));
		add_action('withinweb_wwkc_keycodes_deleteitem_display', array(__CLASS__, 'withinweb_wwkc_keycodes_deleteitem_display'));
	}	
	//--------------------------------------------------
	public static function withinweb_wwkc_keycodes_deleteitem_save_field() {		
		
		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}			
		
		//If submitted then update the option       
		if ( isset($_POST['admin_deleteitem']) && !empty($_POST['admin_deleteitem']) ) {

			// Check the nonce field
		   if ( !check_admin_referer( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_deleteitem' ) )
		   {	  
			   exit();
		   }	

		   if ( isset( $_POST['admin_deleteitem'] ) ) 
		   {			
			   
				$answer = sanitize_text_field( $_POST['admin_deleteitem'] );
			
				if ($answer == "Yes" || $answer == "No") 
				{
					//if yes then delete and redirect back to list
					//if no then redirect back to list

					if ($answer == "Yes")
					{
						
						$recid= sanitize_text_field( $_POST['recid'] );

						if ( !is_numeric($recid) ) {
							exit();
						}

						//delete the record from table items
						global $wpdb;
						$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";

						//Delete item
						//$strSQL = " DELETE  FROM $table_name WHERE recid = $recid ";
						$noofrows = $wpdb->query( $wpdb->prepare( 
							"
								DELETE FROM $table_name WHERE recid = %d
							", 
								array(
								$recid
							) 
						) );


						if ($noofrows == 1)	//deleted
						{							
							$message = "1";
							self::showMessage($message);
						}
						else
						{							
							$message = "2";
							self::showMessage($message);
						}

					}
					else
					{
						$message = "2";
						self::showMessage($message);
					}

				}
				else
				{
				   exit();	//this would be an error and would not normally get here.
				}	   

			}
			else
			{
				exit();  //this would be an error and would not normally get here.
			}

		}
		
	}	
	//------------------------------------------------------------
	public static function withinweb_wwkc_keycodes_deleteitem_display() {	

		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}			
		
		?>

		<div class="wrap">

			<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?> 

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Confirm deletion of item', 'withinweb-wwkc-keycodes'); ?></strong></h2>

			<?php
			if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			?>			
			
			<?php

			//Check for verification nonce
			if ( !isset($_GET['recid']) || !wp_verify_nonce($_GET['refid'], 'delete_page')) {
				//invalid click through
				echo("invalid click through");
				exit();
			}

			$recid = sanitize_text_field($_GET['recid']);

			//Check if nummeric value
			if ( !is_numeric($recid) )
			{
				exit();
			}

			if ( !isset($_POST['admin_deleteitem'] ) )
			{		
				?>			

				<form enctype="multipart/form-data" action="" method="post">

					<input type="hidden" name="action" value="withinweb_wwkc_keycodes_deleteitem" />    

					<?php wp_nonce_field( 'withinweb_wwkc_keycodes_op_verify', 'withinweb_wwkc_keycodes_deleteitem' ); ?>

					<input type="hidden" name="recid" value="<?php echo($recid) ?>" /> 

					<p><strong><?php _e('Are you sure you want to delete this record?', 'withinweb-wwkc-keycodes'); ?></strong></p>

					<p class="submit">					
						<input type="submit" value="Yes" class="button-primary" name="admin_deleteitem" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" value="No" class="button-primary" name="admin_deleteitem" />
					</p>            

				</form>	

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
        		?> <div id='message' class='updated fade'><p><strong><?php _e('The item was deleted.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;
    		case '2':
        	 	?> <div id='message' class='error'><p><strong><?php _e('The deletion was cancelled.', 'withinweb-wwkc-keycodes'); ?></strong></p></div> <?php
        	break;				
		} 		
		
	}	
}

withinweb_wwkc_keycodes_deleteitem::init();
?>
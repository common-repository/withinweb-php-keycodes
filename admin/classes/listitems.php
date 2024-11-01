<?php
//List items
class withinweb_wwkc_keycodes_listitems {
	
	//--------------------------------------------------------------------------------------------	
	public static function init() {
   		add_action('withinweb_wwkc_keycodes_listitems_display', array(__CLASS__, 'withinweb_wwkc_keycodes_listitems_display'));
    }
	//--------------------------------------------------------------------------------------------
	public static function withinweb_wwkc_keycodes_listitems_display() { 
		
		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}			
		
        ?>

		<div class="wrap">

			<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?> 

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Item list', 'withinweb-wwkc-keycodes'); ?></strong></h2>
			<h3><?php _e('List of items', 'withinweb-wwkc-keycodes'); ?></h3>  

			<?php
			if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			?>			
			
			<?php	

			global $wpdb;	
			$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
			//$items = $wpdb->get_results( "SELECT * FROM " . $table_name );

			//---------------------------------------------------------
			$page_num = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
			if ( !is_numeric($page_num) ) { exit(); }

			$limit = 10; // Number of rows in page
			$offset = ( $page_num - 1 ) * $limit;

			$total = $wpdb->get_var( " SELECT COUNT('recid') FROM $table_name " );
			$num_of_pages = ceil( $total / $limit );		

			$items = $wpdb->get_results( " SELECT * FROM $table_name ORDER BY item_number ASC LIMIT $offset, $limit " );

			$page_links = paginate_links( array(
						'base' => add_query_arg( 'pagenum', '%#%' ),
						'format' => '',
						'prev_text' => __( '«', 'withinweb-wwkc-keycodes' ),
						'next_text' => __( '»', 'withinweb-wwkc-keycodes' ),
						'total' => $num_of_pages,
						'current' => $page_num
				) );

			if ( $page_links ) {
				echo '<div class="tablenav">
						<div class="tablenav-pages" style="margin: 1em 0;">' .$total . ' items ' . $page_links . '</div>
					</div>';
			}
			//---------------------------------------------------------


			if ( $items )
			{

				?>
				<table cellspacing="0" class="widefat">
				<thead>
				<tr>
					<th class="manage-column"><?php _e('Rec id', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Item Number', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Item Name', 'withinweb-wwkc-keycodes'); ?></td>        
					<th class="manage-column"><?php _e('Item Title', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Gross', 'withinweb-wwkc-keycodes'); ?></td>            
					<th class="manage-column"><?php _e('Currency', 'withinweb-wwkc-keycodes'); ?></td> 
					<th class="manage-column"><?php _e('Lower Limit', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Short Code', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Button Code', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column">&nbsp;</td> 
					<th class="manage-column">&nbsp;</td>
					<th class="manage-column">&nbsp;</td> 
				</tr>
				</thead>

				<tfoot>
				<tr>
					<th class="manage-column"><?php _e('Rec id', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Item Number', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Item Name', 'withinweb-wwkc-keycodes'); ?></td>        
					<th class="manage-column"><?php _e('Item Title', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Gross', 'withinweb-wwkc-keycodes'); ?></td>            
					<th class="manage-column"><?php _e('Currency', 'withinweb-wwkc-keycodes'); ?></td> 
					<th class="manage-column"><?php _e('Lower Limit', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Short Code', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column"><?php _e('Button Code', 'withinweb-wwkc-keycodes'); ?></td>
					<th class="manage-column">&nbsp;</td> 
					<th class="manage-column">&nbsp;</td>
					<th class="manage-column">&nbsp;</td> 
				</tr>
				</tfoot>        
				<?php

				foreach ( $items as $row )
				{		
					?>
					<tbody>
					<tr class="alternate">
						<td class="role column-role"><?php echo( esc_html( $row->recid ) ) ; ?></td>                      
						<td class="role column-role"><?php echo( esc_html( $row->item_number )); ?></td>          
						<td class="role column-role"><?php echo( esc_html( $row->item_name )); ?></td>          
						<td class="role column-role"><?php echo( esc_html( $row->item_title )); ?></td>			
						<td class="role column-role"><?php echo( esc_html( $row->mc_gross )); ?></td>			
						<td class="role column-role"><?php echo( esc_html( $row->mc_currency )); ?></td>
						<td class="role column-role"><?php echo( esc_html( $row->lowerlimit )); ?></td>                         
						<td class="role column-role">[keycodesbutton recid="<?php echo( esc_html($row->recid )); ?>"]</td>
						<?php 				
						//Get plug in base name
						//slug name of show button page is "withinweb_wwkc_keycodes_admin_display_buttoncode" 
						$complete_url_button = wp_nonce_url( 'admin.php?page=withinweb_wwkc_keycodes_admin_display_buttoncode&recid='.$row->recid, 'button_page', 'refid' );
					
						?>     
						<td class="role column-role">                
							<a href="<?php echo $complete_url_button; ?>"><?php _e('View', 'withinweb-wwkc-keycodes'); ?></a>
						</td>
						
						<?php 						
						$complete_url_localtest = wp_nonce_url( 'admin.php?page=withinweb_wwkc_keycodes_admin_display_localtest&recid='.$row->recid, 'localtest', 'refid' );
						?>						
						<th class="role column-role">
							<a href="<?php echo $complete_url_localtest; ?>"><?php _e('Local Test', 'withinweb-wwkc-keycodes'); ?></a>
						</td>						
						<?php 						
						$complete_url_edit = wp_nonce_url( 'admin.php?page=withinweb_wwkc_keycodes_admin_display_edititem&recid='.$row->recid, 'edit_page', 'refid' );
						?>                
						<td class="role column-role">
							<a href="<?php echo $complete_url_edit; ?>"><?php _e('Edit', 'withinweb-wwkc-keycodes'); ?></a>
						</td>

						<?php
						$complete_url_delete = wp_nonce_url( 'admin.php?page=withinweb_wwkc_keycodes_admin_display_deleteitem&recid='.$row->recid, 'delete_page', 'refid' );
						?>
						<td class="role column-role"><a href="<?php echo $complete_url_delete ?>"><?php _e('Delete', 'withinweb-wwkc-keycodes'); ?></a></td>				

					</tr>
					<tbody>
					<?php
				}		
				?>
				</table>     

				<?php
				if ( $page_links ) {
				echo '<div class="tablenav">
						<div class="tablenav-pages" style="margin: 1em 0;">' .$total . ' items ' . $page_links . '</div>
					</div>';
				}       

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

withinweb_wwkc_keycodes_listitems::init();
?>
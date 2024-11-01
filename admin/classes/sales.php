<?php
//Create item 
class withinweb_wwkc_keycodes_sales {
	
	//--------------------------------------------------------------------------------------------	
	public static function init() {
   		add_action('withinweb_wwkc_keycodes_sales_display', array(__CLASS__, 'withinweb_wwkc_keycodes_sales_display'));
    }
	//--------------------------------------------------------------------------------------------
	public static function withinweb_wwkc_keycodes_sales_display() {        

		if ( !current_user_can( 'manage_options' ) )
		{
		wp_die( __('You are not allowed to be on this page.', 'withinweb-wwkc-keycodes') );
		}			
		
		?>
		
		<div class="wrap">

			<?php if ( ! defined( 'ABSPATH' )  ) exit(); ?>     

			<h2><strong><?php _e('WordPress PHP-KeyCodes - Sales list', 'withinweb-wwkc-keycodes'); ?></strong></h2>
			<h3><?php _e('List of sales', 'withinweb-wwkc-keycodes'); ?></h3>        

			<?php
			if ( sanitize_text_field(get_option('withinweb_wwkc_keycodes_paypal_dolocaltest')) == '1' ) {
				?><h2 style='color:red'><?php _e('You are now in local test mode', 'withinweb-wwkc-keycodes'); ?></h2><?php
			}
			?>			
			
			<?php

			global $wpdb;
			$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_saleshistory";	

			//---------------------------------------------------------
			$page_num = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
			if ( !is_numeric($page_num) ) { exit(); }

			$limit = 10; // Number of rows in page
			$offset = ( $page_num - 1 ) * $limit;

			$total = $wpdb->get_var( " SELECT COUNT('recid') FROM $table_name " );
			$num_of_pages = ceil( $total / $limit );	

			$saleshistory = $wpdb->get_results( " SELECT * FROM $table_name ORDER BY completed DESC LIMIT $offset, $limit " );

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


			if ( $saleshistory )
			{

				?>
				<table cellspacing="0" class="widefat">
				<thead>
					<tr>
						<th class="manage-column"><?php _e('Receiver Email', 'withinweb-wwkc-keycodes'); ?></td>          
						<th class="manage-column"><?php _e('Item name', 'withinweb-wwkc-keycodes'); ?></td>          
						<th class="manage-column"><?php _e('Item number', 'withinweb-wwkc-keycodes'); ?></td>          
						<th class="manage-column"><?php _e('Payment Status', 'withinweb-wwkc-keycodes'); ?></td> 
						<th class="manage-column"><?php _e('Completed', 'withinweb-wwkc-keycodes'); ?></td> 
						<th class="manage-column"><?php _e('Gross', 'withinweb-wwkc-keycodes'); ?></td>            
						<th class="manage-column"><?php _e('Payer Email', 'withinweb-wwkc-keycodes'); ?></td> 
						<th class="manage-column"><?php _e('License Codes', 'withinweb-wwkc-keycodes'); ?></td>
						<th class="manage-column"><?php _e('Details', 'withinweb-wwkc-keycodes'); ?></td>                                       
					</tr>
				</thead>

				<tfoot>
					<tr>
						<th class="manage-column"><?php _e('Receiver Email', 'withinweb-wwkc-keycodes'); ?></td>          
						<th class="manage-column"><?php _e('Item name', 'withinweb-wwkc-keycodes'); ?></td>          
						<th class="manage-column"><?php _e('Item number', 'withinweb-wwkc-keycodes'); ?></td>          
						<th class="manage-column"><?php _e('Payment Status', 'withinweb-wwkc-keycodes'); ?></td> 
						<th class="manage-column"><?php _e('Completed', 'withinweb-wwkc-keycodes'); ?></td> 
						<th class="manage-column"><?php _e('Gross', 'withinweb-wwkc-keycodes'); ?></td>            
						<th class="manage-column"><?php _e('Payer Email', 'withinweb-wwkc-keycodes'); ?></td> 
						<th class="manage-column"><?php _e('License Codes', 'withinweb-wwkc-keycodes'); ?></td>
						<th class="manage-column"><?php _e('Details', 'withinweb-wwkc-keycodes'); ?></td>                    
					</tr>
				</tfoot>        
				<?php

				foreach ( $saleshistory as $row )
				{		
					?>
					<tbody>
					<tr class="alternate">
						<td class="role column-role"><?php echo( esc_html( $row->receiver_email) ); ?></td>          
						<td class="role column-role"><?php echo( esc_html( $row->item_name )); ?></td>          
						<td class="role column-role"><?php echo( esc_html( $row->item_number )); ?></td>			
						<td class="role column-role"><?php echo( esc_html( $row->payment_status )); ?></td>				
						<td class="role column-role"><?php echo( esc_html( $row->completed )); ?></td>
						<td class="role column-role"><?php echo( esc_html( $row->mc_gross )); ?></td>			
						<td class="role column-role"><?php echo( esc_html ($row->payer_email )); ?></td>
						<td class="role column-role"><?php echo( esc_html ($row->licencecodes )); ?></td>

						<?php
						$complete_url = wp_nonce_url( 'admin.php?page=withinweb_wwkc_keycodes_admin_display_salesdetails&recid='.$row->recid, 'salesdetails_page', 'refid' );					
						?>
						<td class="role column-role"><a href="<?php echo ($complete_url) ?>"><?php _e('View details', 'withinweb-wwkc-keycodes'); ?></a></td>
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
			   ?>		
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
		
	}}

withinweb_wwkc_keycodes_sales::init();
?>
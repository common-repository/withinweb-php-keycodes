<?php

class withinweb_wwkc_keycodes_public_display {
	
	//--------------------------------------------
	//Public displays
    public static function init() {
        self::paypal_shopping_cart_for_wordPress_add_shortcode();
        add_filter('widget_text', 'do_shortcode');
    }
	//--------------------------------------------
    public static function paypal_shopping_cart_for_wordPress_add_shortcode() {
        add_shortcode('paypal_ipn_list', array(__CLASS__, 'withinweb_wwkc_keycodes_paypal_ipn_list'));
        add_shortcode('paypal_ipn_data', array(__CLASS__, 'withinweb_wwkc_keycodes_paypal_ipn_paypal_ipn_data'));
		add_shortcode('keycodesbutton', array(__CLASS__, 'withinweb_wwkc_keycodes_paypal_ipn_paypal_keycodesbutton'));
    }
	//--------------------------------------------	
	public static function withinweb_wwkc_keycodes_paypal_ipn_paypal_keycodesbutton($atts) {

		extract(shortcode_atts(array(
      		'recid' => 0,     //Default value
			'custom' => '',
			'quantity' => 1,
			'buttontext' => 'Buy with PayPal',
			'buttonclass' => 'button-primary',
			'tax' => '0',
   			), $atts));
		
		$custom = "";	//Need to find a way to get $custom into the array
		
		ob_start();		//output buffer start
		
		if (!is_numeric($recid)) {
			$result = "Short code error";
			return ob_get_clean();			
		}		
		$custom = sanitize_text_field( $custom );
		if ( !is_numeric($quantity) ) { 
			$result = "Short code error";			
			return ob_get_clean();			
		}
		
		$result = "";
		
		//------------------------------------------		
		$livepaypaladdress 		= sanitize_email( get_option('withinweb_wwkc_keycodes_paypal_email') );
		$sandboxpaypaladdress 	= sanitize_email( get_option('withinweb_wwkc_keycodes_sandbox_paypal_email') );
		$cancel_url 			= sanitize_text_field( get_option('withinweb_wwkc_keycodes_cancel_url') );
		$return_url  			= sanitize_text_field( get_option('withinweb_wwkc_keycodes_return_url') );
		$notify_url 			= sanitize_text_field( get_option('withinweb_wwkc_keycodes_ipn_url') );	
		$withinweb_wwkc_keycodes_paypal_environment = sanitize_text_field ( get_option('withinweb_wwkc_keycodes_paypal_environment') );		
		//------------------------------------------	
		
		//Get item details
		global $wpdb;	
		$table_name = $wpdb->prefix . "withinweb_wwkc_keycodes_items";
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

			$item_name 				= $row->item_name;
			$mc_gross 				= $row->mc_gross;			//This is the value of a single product item, not the tolal value of all the items
			$item_number 			= $row->item_number;
			//$physicalgoods 			= $row->physicalgoods;
			$mc_currency 			= $row->mc_currency;

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

				$result .= "<!-- Quantity -->";
				$result .= "<input type='hidden' name='quantity' value='" . esc_attr($quantity) . "' />" . "\r\n";
			
				$result .= "<!-- custom variable -->" . "\r\n";
				//Custom entry
				if ( $custom != "") {				
					$result .= "<input type='hidden' name='custom' value='" . esc_attr($custom) . "' />" . "\r\n";
				}		

				//Tax entry
				if ( $tax > 0) {
					$tax = round( $mc_gross * ($tax / 100), 2);
					$result .= "<input type='hidden' name='tax' value='$tax' />" . "\r\n";
				}			
			
				//Note that name='no_shipping' defines if you want the shipping address to be prompted for
				$result .= "<!-- Free Shipping -->" . "\r\n";
				$result .= "<input type='hidden' name='shipping' value='0' />" . "\r\n";

				$result .= "<!-- URLs -->" . "\r\n";
				$result .= "<input type='hidden' name='notify_url' value='$notify_url' />" . "\r\n";

				if ($cancel_url != "") {
					$result .= "<input type='hidden' name='cancel_return' value='$cancel_url' />" . "\r\n";
				}

				if ($return_url != "") {
					$result .= "<input type='hidden' name='return' value='$return_url' />" . "\r\n";
				}		
			
			$result .= "<input type='submit' name='submit' value='" . esc_attr($buttontext) . "' class='" . esc_attr($buttonclass) . "' />" . "\r\n";
			$result .= "</form>" . "\r\n";    	

		}
		else
		{
			$result = "No row found";	
		}

		return $result;		
		return ob_get_clean();		//output object buffer		
	}	
	//--------------------------------------------
    public static function withinweb_wwkc_keycodes_paypal_ipn_paypal_ipn_data($atts) {

        extract(shortcode_atts(array(
            'txn_id' => 'txn_id',
            'field' => 'first_name',
                        ), $atts));

        ob_start();		//output buffer start

        if (isset($atts['txn_id']) && !empty($atts['txn_id'])) {
            $args = array(
                'post_type' => 'paypal_ipn',
                'post_status' => 'any',
                'meta_query' => array(
                    array(
                        'key' => 'txn_id',
                        'value' => $atts['txn_id'],
                        'compare' => 'LIKE'
                    )
                )
            );

            $posts = get_posts($args);
            if (isset($posts[0]->ID) && !empty($posts[0]->ID)) {
                return get_post_meta($posts[0]->ID, $field, true);
                return ob_get_clean();
            } else {
                $mainhtml = "no records";
                return ob_get_clean();
            }
        } else {
            $mainhtml = "transaction id not found.";
            return ob_get_clean();
        }
    }
	//--------------------------------------------
    public static function withinweb_wwkc_keycodes_paypal_ipn_list($atts) {

        extract(shortcode_atts(array(
            'txn_type' => 'any',
            'payment_status' => '',
            'limit' => 10,
            'field1' => 'txn_id',
            'field2' => 'payment_date',
            'field3' => 'first_name',
            'field4' => 'last_name',
            'field5' => 'mc_gross',
                        ), $atts));

        ob_start();

        if (empty($payment_status)) {
            $paypal_ipn_type = get_terms('paypal_ipn_type');
            $term_ids = wp_list_pluck($paypal_ipn_type, 'slug');
        } else {
            $term_ids = array('0' => $payment_status);
        }

        $args = array(
            'post_type' => 'paypal_ipn',
            'post_status' => $txn_type,
            'posts_per_page' => $limit,
            'tax_query' => array(
                array(
                    'taxonomy' => 'paypal_ipn_type',
                    'terms' => array_map('sanitize_title', $term_ids),
                    'field' => 'slug'
                )
            )
        );

        if (isset($atts) && !empty($atts)) {
            $start_loop = 1;
            $field_key_header = array();
            $field_key = array();
            foreach ($atts as $atts_key => $atts_value) {
                if (array_key_exists('field' . $start_loop, $atts)) {
                    $field_key_header['field' . $start_loop] = ucwords(str_replace('_', ' ', $atts['field' . $start_loop]));
                    $field_key['field' . $start_loop] = $atts['field' . $start_loop];
                }
                $start_loop = $start_loop + 1;
            }
        }

        $posts = get_posts($args);
        if ($posts) {
            $mainhtml = '';
            $output = '';
            $output .= '<table id="example" class="display" cellspacing="0" width="100%"><thead>';

            $thead = "<tr>";

            if(!empty($field_key_header))
            {
                foreach ($field_key_header as $field_key_header_key => $field_key_header_value) {
                    $thead .= "<th>" . $field_key_header_value . "</th>";
                }
            }

            $thead .= "</tr>";

            $thead_end = '</thead>';
            $tfoot_start = "<tfoot>";
            $tfoot_end = "</tfoot>";
            $mainhtml .= $output . $thead . $thead_end . $tfoot_start . $thead . $tfoot_end;
            $tbody_start = "<tbody>";
            $tbody = "";
            foreach ($posts as $post):
                $tbody .= "<tr>";

                if (isset($field_key) && !empty($field_key)) {
                    foreach ($field_key as $field_key_key => $field_key_value) {
                        $tbody .= "<td>" . get_post_meta($post->ID, $field_key_value, true) . "</td>";
                    }
                }

                $tbody .= "</tr>";
            endforeach;

            $tbody_end = "</tbody>";
            $mainhtml .= $tbody_start . $tbody . $tbody_end;
            $mainhtml .= "</table>";
            return $mainhtml;
            return ob_get_clean();
        } else {
            $mainhtml = "no records found";
            return ob_get_clean();
        }
    }
											   

}

withinweb_wwkc_keycodes_public_display::init();

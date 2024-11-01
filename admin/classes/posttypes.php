<?php
//Registers post types and taxonomies 
class withinweb_wwkc_keycodes_post_types {

	//----------------------------------------
    //Hooks
    public static function init() {
        add_action('admin_print_scripts', array(__CLASS__, 'disable_autosave'));
        add_action('init', array(__CLASS__, 'withinweb_wwkc_keycodes_register_taxonomies'), 5);
        add_action('init', array(__CLASS__, 'withinweb_wwkc_keycodes_register_post_types'), 5);
        add_action('init', array(__CLASS__, 'withinweb_wwkc_keycodes_register_post_status'), 10);
        add_action('restrict_manage_posts', array(__CLASS__, 'withinweb_wwkc_keycodes_ipn_filter'), 10);
        add_action('add_meta_boxes', array(__CLASS__, 'withinweb_wwkc_keycodes_remove_meta_boxes'), 10);
        add_action('manage_edit-paypal_ipn_columns', array(__CLASS__, 'withinweb_wwkc_keycodes_add_paypal_ipn_columns'), 10, 2);
        add_action('manage_paypal_ipn_posts_custom_column', array(__CLASS__, 'withinweb_wwkc_keycodes_render_paypal_ipn_columns'), 2);
        add_filter('manage_edit-paypal_ipn_sortable_columns', array(__CLASS__, 'withinweb_wwkc_keycodes_paypal_ipn_sortable_columns'));
        add_action('pre_get_posts', array(__CLASS__, 'withinweb_wwkc_keycodes_ipn_column_orderby'));
        add_filter('views_edit-paypal_ipn', array(__CLASS__, 'withinweb_wwkc_keycodes_ipn_section_name'), 10, 1);
        add_action('add_meta_boxes', array(__CLASS__, 'withinweb_wwkc_keycodes_add_meta_boxes_ipn_data_custome_fields'), 31);
        add_filter('withinweb_wwkc_keycodes_the_meta_key', array(__CLASS__, 'withinweb_wwkc_keycodes_the_meta_key_remove_raw_dump'), 10, 3);
        add_filter('post_class', array(__CLASS__, 'withinweb_wwkc_keycodes_post_class_representation'), 10, 3);
        add_action('parse_query', array(__CLASS__, 'withinweb_wwkc_keycodes_parse_query'), 10, 1);
    }
	//----------------------------------------
    //register taxonomies
    public static function withinweb_wwkc_keycodes_register_taxonomies() {

        if (taxonomy_exists('paypal_ipn_type')) {
            return;
        }

        do_action('withinweb_wwkc_keycodes_register_taxonomy');

        register_taxonomy('paypal_ipn_type', apply_filters('paypal-ipn-for-wordpress_taxonomy_objects_ipn_cat', array('paypal_ipn')), apply_filters('paypal-ipn-for-wordpress_taxonomy_args_ipn_cat', array(
            'hierarchical' => true,
            'label' => __('PayPal IPN Types', 'withinweb-wwkc-keycodes'),
            'labels' => array(
                'name' => __('PayPal IPN Types', 'withinweb-wwkc-keycodes'),
                'singular_name' => __('PayPal IPN Types', 'withinweb-wwkc-keycodes'),
                'menu_name' => _x('PayPal IPN Types', 'Admin menu name', 'withinweb-wwkc-keycodes'),
                'search_items' => __('Search PayPal IPN Types', 'withinweb-wwkc-keycodes'),
                'all_items' => __('All PayPal IPN Types', 'withinweb-wwkc-keycodes'),
                'parent_item' => __('Parent PayPal IPN Types', 'withinweb-wwkc-keycodes'),
                'parent_item_colon' => __('Parent PayPal IPN Types:', 'withinweb-wwkc-keycodes'),
                'edit_item' => __('Edit PayPal IPN Types', 'withinweb-wwkc-keycodes'),
                'update_item' => __('Update PayPal IPN Types', 'withinweb-wwkc-keycodes'),
                'add_new_item' => __('Add New PayPal IPN Types', 'withinweb-wwkc-keycodes'),
                'new_item_name' => __('New PayPal IPN Types Name', 'withinweb-wwkc-keycodes')
            ),
            'show_ui' => false,
            'query_var' => true,
            'rewrite' => array('slug' => 'paypal_ipn'),
            'update_count_callback' => '_update_generic_term_count'
            
                ))
        );
    }
	//----------------------------------------
    //post types
    public static function withinweb_wwkc_keycodes_register_post_types() {
        global $wpdb;
        if (post_type_exists('paypal_ipn')) {
            return;
        }

        do_action('withinweb_wwkc_keycodes_register_post_type');

        register_post_type('paypal_ipn', apply_filters('withinweb_wwkc_keycodes_register_post_type_ipn', array(
            'labels' => array(
                'name' => __('PayPal IPN', 'withinweb-wwkc-keycodes'),
                'singular_name' => __('PayPal IPN', 'withinweb-wwkc-keycodes'),
                'menu_name' => _x('PayPal IPN', 'Admin menu name', 'withinweb-wwkc-keycodes'),
                'add_new' => __('Add PayPal IPN', 'withinweb-wwkc-keycodes'),
                'add_new_item' => __('Add New PayPal IPN', 'withinweb-wwkc-keycodes'),
                'edit' => __('Edit', 'withinweb-wwkc-keycodes'),
                'edit_item' => __('IPN Details', 'withinweb-wwkc-keycodes'),
                'new_item' => __('New PayPal IPN', 'withinweb-wwkc-keycodes'),
                'view' => __('IPN Details', 'withinweb-wwkc-keycodes'),
                'view_item' => __('IPN Details', 'withinweb-wwkc-keycodes'),
                'search_items' => __('Search PayPal IPN', 'withinweb-wwkc-keycodes'),
                'not_found' => __('No PayPal IPN found', 'withinweb-wwkc-keycodes'),
                'not_found_in_trash' => __('No PayPal IPN found in trash', 'withinweb-wwkc-keycodes'),
                'parent' => __('Parent PayPal IPN', 'withinweb-wwkc-keycodes')
            ),
            'description' => __('This is where you can add new IPN to your store.', 'withinweb-wwkc-keycodes'),
            'public' => false,
            'show_ui' => true,
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => false, // Removes support for the "Add New" function
            ),
            'map_meta_cap' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => false, // Hierarchical causes memory issues - WP loads all records!
            'rewrite' => array('slug' => 'paypal_ipn'),
            'query_var' => true,
            'menu_icon' => wwkc_PLUGIN_URL . 'admin/images/withinweb_wwkc_keycodes_icon.png',
            'supports' => array('', ''),
            'has_archive' => true,
            'show_in_nav_menus' => true,			
			'show_in_menu' => false
			
                        )
                )
        );
    }
	//----------------------------------------
    //Register our custom post statuses, used for paypal_ipn status
    public static function withinweb_wwkc_keycodes_register_post_status() {
        global $wpdb;

        $ipn_post_status_list = self::withinweb_wwkc_keycodes_get_ipn_status();

        if (isset($ipn_post_status_list) && !empty($ipn_post_status_list)) {
            foreach ($ipn_post_status_list as $ipn_post_status) {
                $ipn_post_status_display_name = ucfirst(str_replace('_', ' ', $ipn_post_status));
                register_post_status($ipn_post_status, array(
                    'label' => _x($ipn_post_status_display_name, 'IPN status', 'withinweb-wwkc-keycodes'),
                    'public' => ($ipn_post_status == 'trash') ? false : true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => ($ipn_post_status == 'trash') ? false : true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop($ipn_post_status_display_name . ' <span class="count">(%s)</span>', $ipn_post_status_display_name . ' <span class="count">(%s)</span>', 'withinweb-wwkc-keycodes')
                ));
            }
        }
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_ipn_filter function used for IPN status and type that will display admin side.
    public static function withinweb_wwkc_keycodes_ipn_filter() {
        global $typenow, $wp_query;
        if ($typenow == 'paypal_ipn') {
            ?>
            <select name="test_ipn" class="dropdown_post_status">
                <option value="-1"><?php _e('Show All Transaction', 'withinweb-wwkc-keycodes'); ?></option>
                <?php
                $transaction_mode = array('0' => 'Live Transaction', '1' => 'Sandbox Transaction');
                foreach ($transaction_mode as $transaction_mode_key => $transaction_mode_value) :
                    if (isset($_GET['test_ipn']) && $_GET['test_ipn'] == $transaction_mode_key) {
                        $selected_status = 'selected="selected"';
                    } else {
                        $selected_status = '';
                    }
                    ?>
                    <option value="<?php echo esc_attr($transaction_mode_key); ?>" <?php echo esc_attr($selected_status); ?>><?php echo esc_html(ucwords($transaction_mode_value)); ?></option>
                <?php endforeach; ?>
            </select>
            <?php
            $taxonomy = 'paypal_ipn_type';
            $term = isset($wp_query->query['paypal_ipn_type']) ? $wp_query->query['paypal_ipn_type'] : '';
            $business_taxonomy = get_taxonomy($taxonomy);
            if( !empty($term) ) {
                wp_dropdown_categories(array(
                    'show_option_all' => __("Show all Payment Statuses", 'withinweb-wwkc-keycodes'),
                    'taxonomy' => $taxonomy,
                    'name' => 'paypal_ipn_type',
                    'orderby' => 'name',
                    'selected' => $term,
                    'hierarchical' => true,
                    'depth' => 3,
                    'show_count' => true, // Show # listings in parens
                    'hide_empty' => true,
                ));
            }

            // Forwarder URL Filter
            $paypal_ipn_forwarder_url_name_array = array();
            global $wpdb;
            $mata_key = 'paypal_ipn_forwarder_url_name';
            $posts = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s", $mata_key), ARRAY_A);

            if ($posts) {
                foreach ($posts as $post) {
                    $paypal_ipn_forwarder_url_name = get_post_meta($post['post_id'], 'paypal_ipn_forwarder_url_name', true);
                    if( !empty($paypal_ipn_forwarder_url_name) ) {
                        foreach ($paypal_ipn_forwarder_url_name as $key => $value) {
                            $paypal_ipn_forwarder_url_name_array[$value] = $value;
                        }
                    }
                    
                }
           
                $forwarder_url_filter = '<select name="paypal_ipn_forwarder_url_name" class="dropdown_ipn_url_name" id="ipn_url_name"><option value="-1">Show All Forwarder URL</option>';
            
                if (isset($paypal_ipn_forwarder_url_name_array) && !empty($paypal_ipn_forwarder_url_name_array) && is_array($paypal_ipn_forwarder_url_name_array) && count($paypal_ipn_forwarder_url_name_array) > 0) {
                    foreach ($paypal_ipn_forwarder_url_name_array as $key => $value) {
                        if (isset($_GET['paypal_ipn_forwarder_url_name']) && $_GET['paypal_ipn_forwarder_url_name'] == $value) {
                            $selected_status = 'selected';
                        } else {
                            $selected_status = '';
                        }
                        $forwarder_url_filter .= '<option ' . $selected_status . ' value="' . esc_html($value) . '">' . esc_html($value) . '</option>';
                    }
                }
                $forwarder_url_filter .= '</select>';
                echo $forwarder_url_filter;
            }
        }
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_remove_meta_boxes function used for remove submitdiv meta_box for paypal_ipn custom post type
    //https://core.trac.wordpress.org/ticket/12706
    //Removed submitdiv meta_box because it does not support custom register_post_status like  Completed | Denied
    public static function withinweb_wwkc_keycodes_remove_meta_boxes() {
        remove_meta_box('submitdiv', 'paypal_ipn', 'side');
        remove_meta_box('slugdiv', 'paypal_ipn', 'normal');
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_get_ipn_status helper function used for return IPN status
    public static function withinweb_wwkc_keycodes_get_ipn_status() {
        global $wpdb;

        return $wpdb->get_col($wpdb->prepare("SELECT DISTINCT post_status FROM {$wpdb->posts} WHERE post_type = %s AND post_status != %s  ORDER BY post_status", 'paypal_ipn', 'auto-draft'));
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_get_ipn_status helper function used for return IPN status for filter
    public static function withinweb_wwkc_keycodes_get_ipn_status_filter() {
        global $wpdb;

        return $wpdb->get_col($wpdb->prepare("SELECT DISTINCT post_status FROM {$wpdb->posts} WHERE post_type = %s AND post_status != %s AND post_status != %s ORDER BY post_status", 'paypal_ipn', 'auto-draft', 'not-available'));
    }
	//----------------------------------------
    //Define custom columns for IPN
    public static function withinweb_wwkc_keycodes_add_paypal_ipn_columns($existing_columns) {
        $columns = array();
        $columns['cb'] = '<input type="checkbox" />';
        $columns['title'] = _x('Transaction ID', 'withinweb-wwkc-keycodes');
        $columns['invoice'] = _x('Invoice ID', 'withinweb-wwkc-keycodes');
        $columns['payment_date'] = _x('Date', 'withinweb-wwkc-keycodes');
        $columns['first_name'] = _x('Name / Company', 'withinweb-wwkc-keycodes');
        $columns['mc_gross'] = __('Amount', 'withinweb-wwkc-keycodes');
        $columns['txn_type'] = __('Transaction Type', 'withinweb-wwkc-keycodes');
        $columns['payment_status'] = __('Payment Status', 'withinweb-wwkc-keycodes');
        return $columns;
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_render_paypal_ipn_columns helper function used add own column in IPN listing
    public static function withinweb_wwkc_keycodes_render_paypal_ipn_columns($column) {
        global $post;

        switch ($column) {
            case 'invoice' :
                $invoice = get_post_meta($post->ID, 'invoice', true);
                if (isset($invoice) && !empty($invoice)) {
                    echo esc_attr($invoice);
                } else {
                    $transaction_invoice_id = get_post_meta($post->ID, 'transaction_refund_id', true);
                    if (isset($transaction_invoice_id) && !empty($transaction_invoice_id)) {
                        echo esc_attr($transaction_invoice_id);
                    }
                }
                break;
            case 'payment_date' :
                $payment_date = esc_attr(get_post_meta($post->ID, 'payment_date', true));
                if (isset($payment_date) && !empty($payment_date)) {
                    self::withinweb_wwkc_keycodes_date_parsing($payment_date);
                } else {
                    $payment_date = esc_attr(get_post_meta($post->ID, 'payment_request_date', true));
                    $subscr_date = esc_attr(get_post_meta($post->ID, 'subscr_date', true));
                    if (isset($payment_date) && !empty($payment_date)) {
                        self::withinweb_wwkc_keycodes_date_parsing($payment_date);
                    } elseif (isset($subscr_date) && !empty($subscr_date)) {
			self::withinweb_wwkc_keycodes_date_parsing($subscr_date);
                    }
                }
                break;
            case 'first_name' :
                echo esc_attr(get_post_meta($post->ID, 'first_name', true) . ' ' . get_post_meta($post->ID, 'last_name', true));
                echo (get_post_meta($post->ID, 'payer_business_name', true)) ? '<br />' . get_post_meta($post->ID, 'payer_business_name', true) : '';
                break;
            case 'mc_gross' :
                $mc_gross = get_post_meta($post->ID, 'mc_gross', true);
                if (isset($mc_gross) && !empty($mc_gross)) {
                    echo esc_attr($mc_gross);
                } else {
                    $transaction_amount = get_post_meta($post->ID, 'transaction_amount', true);
                    $mc_amount3 = get_post_meta($post->ID, 'mc_amount3', true);
                    if (isset($transaction_amount) && !empty($transaction_amount)) {
                        echo esc_attr($transaction_amount);
                    } elseif (isset($mc_amount3) && !empty($mc_amount3)) {
                        echo esc_attr($mc_amount3);
                    }
                }
                break;
            case 'txn_type' :
                $txn_type = get_post_meta($post->ID, 'txn_type', true);
                if (isset($txn_type) && !empty($txn_type)) {
                    echo esc_attr($txn_type);
                } else {
                    $transaction_type = get_post_meta($post->ID, 'transaction_type', true);
                    if (isset($transaction_type) && !empty($transaction_type)) {
                        echo esc_attr($transaction_type);
                    }
                }
                break;

            case 'payment_status' :
                echo esc_attr(get_post_meta($post->ID, 'payment_status', true));
                break;
        }
    }
	//----------------------------------------
    //Disable the auto-save functionality for IPN.
    public static function disable_autosave() {
        global $post;

        if ($post && get_post_type($post->ID) === 'paypal_ipn') {
            wp_dequeue_script('autosave');
        }
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_paypal_ipn_sortable_columns helper function used for make column shortable.
    public static function withinweb_wwkc_keycodes_paypal_ipn_sortable_columns($columns) {

        $custom = array(
            'title' => 'txn_id',
            'invoice' => 'invoice',
            'payment_date' => 'payment_date',
            'first_name' => 'first_name',
            'mc_gross' => 'mc_gross',
            'txn_type' => 'txn_type',
            'payment_status' => 'payment_status',
            'payment_date' => 'payment_date'
        );

        return wp_parse_args($custom, $columns);
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_ipn_column_orderby helper function used for shorting query handler
    public static function withinweb_wwkc_keycodes_ipn_column_orderby($query) {
        global $wpdb;
        if (is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'paypal_ipn' && isset($_GET['orderby']) && $_GET['orderby'] != 'None') {
            $query->query_vars['orderby'] = 'meta_value';
            $query->query_vars['meta_key'] = sanitize_text_field($_GET['orderby']);
            if (isset($query->query_vars['s']) && empty($query->query_vars['s'])) {
                $query->is_search = false;
            }
            if (isset($_GET['test_ipn']) && $_GET['test_ipn'] != '-1') {
                $query->set('meta_query', array(array('key' => 'test_ipn', 'value' => sanitize_text_field($_GET['test_ipn']), 'compare' => '=')));
            }
        } else {

            if (isset($_GET['test_ipn']) && $_GET['test_ipn'] != '-1') {
                $query->set('meta_query', array(array('key' => 'test_ipn', 'value' => sanitize_text_field($_GET['test_ipn']), 'compare' => '=')));
                if (isset($query->query_vars['s']) && empty($query->query_vars['s'])) {
                    $query->is_search = false;
                }
            }
        }
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_ipn_section_name helper function used for Make section name when it upto 20 characters.
    public static function withinweb_wwkc_keycodes_ipn_section_name($sectionlist) {
        $sectiongroup = array('recurring_payments_p' => 'recurring_payments_profile', 'subscription_payment' => 'subscription_payments');
        if (!empty($sectionlist)) {
            foreach ($sectionlist as $sectionkey => $section) {
                if ($sectionkey == 'not-available') {
                    unset($sectionlist['not-available']);
                }
                if (array_key_exists($sectionkey, $sectiongroup)) {
                    $displayname = ucfirst(str_replace('_', ' ', $sectiongroup[$sectionkey]));
                    $displaysectionkey = ucfirst(str_replace('_', ' ', $sectionkey));
                    $section = str_replace($displaysectionkey, $displayname, $section);
                    $sectionlist[$sectiongroup[$sectionkey]] = ucwords($section);
                    unset($sectionlist[$sectionkey]);
                } else {
                    $sectionlist[$sectionkey] = ucwords($section);
                }
            }
        }
        return $sectionlist;
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_add_meta_boxes_ipn_data_custome_fields function used for 
	//register own meta_box for display IPN custome filed read only
    public static function withinweb_wwkc_keycodes_add_meta_boxes_ipn_data_custome_fields() {
        add_meta_box('paypal-ipn-ipn-data-custome-field', __('PayPal IPN Fields', 'withinweb-wwkc-keycodes'), array(__CLASS__, 'withinweb_wwkc_keycodes_display_ipn_custome_fields'), 'paypal_ipn', 'normal', 'high');
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_display_ipn_custome_fields helper function used for display raw dump in html format
    public static function withinweb_wwkc_keycodes_display_ipn_custome_fields() {
        if ($keys = get_post_custom_keys()) {
            echo "<div class='wrap'>";
            echo "<table class='widefat'><thead>
                        <tr>
                            <th>" . __('IPN Field Name', 'withinweb-wwkc-keycodes') . "</th>
                            <th>" . __('IPN Field Value', 'withinweb-wwkc-keycodes') . "</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>" . __('IPN Field Name', 'withinweb-wwkc-keycodes') . "</th>
                            <th>" . __('IPN Field Value', 'withinweb-wwkc-keycodes') . "</th>

                        </tr>
                    </tfoot>";
            foreach ((array) $keys as $key) {
                $keyt = trim($key);
                if (is_protected_meta($keyt, 'post'))
                    continue;
                $values = array_map('trim', get_post_custom_values($key));
                $value = implode($values, ', ');

                //Filter the HTML output of the li element in the post custom fields list.
                echo apply_filters('withinweb_wwkc_keycodes_the_meta_key', "<tr><th class='post-meta-key'>$key:</th> <td>$value</td></tr>", $key, $value);
            }
            echo "</table>";
            echo "</div>";
        }
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_the_meta_key_remove_raw_dump helper function used ignore specific key that will not display in raw dump
    public static function withinweb_wwkc_keycodes_the_meta_key_remove_raw_dump($row, $key, $value) {
        if ($key != 'ipn data serialized') {
            return $row;
        }
    }
	//----------------------------------------
    //withinweb_wwkc_keycodes_add_meta_boxes_hook_function_snippets helper function 
	//used for display IPN row data to bottom of detail section
    public static function withinweb_wwkc_keycodes_add_meta_boxes_hook_function_snippets() {     
    }	
	//----------------------------------------
    //withinweb_wwkc_keycodes_post_class_representation helper function used for IPN listing highlight when is invalid
    public static function withinweb_wwkc_keycodes_post_class_representation($classes, $class, $postid) {
        global $post;

        if ($post->post_type == 'paypal_ipn') {
            $transaction_type = get_post_meta($post->ID, 'IPN_status', true);
            if ($transaction_type == 'Invalid') {
                $classes[] = 'warning';
            }

            $test_ipn = get_post_meta($post->ID, 'test_ipn', true);
            if ($test_ipn == '1') {
                $classes[] = 'sandbox_warning';
            }
        }

        return $classes;
    }
	//----------------------------------------
    //Potential SQL Injection Vulnerability	
    public static function withinweb_wwkc_keycodes_parse_query($query) {
        global $pagenow, $typenow;
        if ($typenow == 'paypal_ipn') {
            $qv = &$query->query_vars;
            if ($pagenow == 'edit.php' && isset($qv['paypal_ipn_type']) && is_numeric($qv['paypal_ipn_type'])) {
                $term = get_term_by('id', $qv['paypal_ipn_type'], 'paypal_ipn_type');
                $qv['paypal_ipn_type'] = ($term ? $term->slug : '');
            }
            if (isset($_GET['paypal_ipn_forwarder_url_name']) && !empty($_GET['paypal_ipn_forwarder_url_name']) && $_GET['paypal_ipn_forwarder_url_name'] != -1) {
                $query->query_vars['meta_query'] = array(
                        'relation' => 'OR',
                        array(
                                'key'     => 'paypal_ipn_forwarder_url_name',
                                'value'   => sanitize_text_field($_GET['paypal_ipn_forwarder_url_name']),
                                'compare' => 'LIKE'
                        )
                );
            }
        }
    }
	//----------------------------------------    
    public static function withinweb_wwkc_keycodes_date_parsing($date){
		$string = preg_replace('/[(]+[^*]+/', '', $date);
        $date_format = get_option( 'date_format' );
        $time_format = get_option('time_format');
        if( !empty($date_format) && !empty($time_format) ) {
            $format = $date_format .' '. $time_format;
        } else {
            $format = 'Y-m-d H:i:s';
        }
        $current_offset = get_option('gmt_offset');
        $tzstring = get_option('timezone_string');
        $check_zone_info = true;
        // Remove old Etc mappings. Fallback to gmt_offset.
        if ( false !== strpos($tzstring,'Etc/GMT') ) {
            $tzstring = '';
        }
        if ( empty($tzstring) ) { // Create a UTC+- zone if no timezone string exists
            $check_zone_info = false;
            if ( 0 == $current_offset )
                $tzstring = 'UTC+0';
            elseif ($current_offset < 0)
                $tzstring = 'UTC' . $current_offset;
            else
                $tzstring = 'UTC+' . $current_offset;
        }
        $allowed_zones = timezone_identifiers_list();
        if ( in_array( $tzstring, $allowed_zones) ) {
            $tz = new DateTimeZone($tzstring);
        } else {
            $tz = new DateTimeZone('UTC');
        }
        $dt = new DateTime($string);	
        $dt->setTimezone($tz);
        echo $dt->format($format);
    }
}

withinweb_wwkc_keycodes_post_types::init();
<?php
if (!defined('ABSPATH')) exit;
 // Exit if accessed directly

if (!class_exists('Econt_Order')) {
    
    class Econt_Order
    {
        
        function __construct() {

            
            
            //add econt offices fields to checkout
            add_action('woocommerce_after_order_notes', array($this, 'econt_offices_checkout_fields'));
            
            //validate econt offices fields in checkout
            add_action('woocommerce_checkout_process', array($this, 'econt_offices_checkout_field_process'));
            
            //save econt office field to the order
            add_action('woocommerce_checkout_update_order_meta', array($this, 'econt_offices_checkout_field_update_order_meta'));
            
            //show chosen econt office in admin panel - order
            add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'econt_offices_checkout_field_display_admin_order_meta'), 10, 1);
            
            //thank you page message
            //add_filter( 'woocommerce_thankyou_order_received_text', array($this, 'econt_offices_order_recieved_text'),10,2 );
            
            //fliter leasing payment gateway if nesesery
            add_filter('woocommerce_available_payment_gateways',array($this, 'filter_cod_gateway'),1);
            
            //remove some billing fields
            add_filter('woocommerce_billing_fields', array($this, 'econt_filter_billing_fields'));
            
            //remove all shipping fields
            add_filter('woocommerce_shipping_fields', array($this, 'econt_filter_shipping_fields'));
            
            //add message about econt offices in thank you and order pages
            add_action('woocommerce_thankyou', array($this, 'econt_offices_display_order_data'), 20);
            //tuk shte pravq vrushtaneto na pratka ot klient
            add_action('woocommerce_view_order', array($this, 'econt_offices_display_order_data'), 20);
            
            //add econt fields to order email
            //if ( version_compare( WOOCOMMERCE_VERSION, '2.3.0', '>=' ) ) {
            //add_filter('woocommerce_email_order_meta_keys', 'econt_email_order_meta_fields_old');
            //}else{
            //add_filter('woocommerce_email_order_meta_fields', array($this, 'econt_email_order_meta_fields'), 10, 3 );
            //}
            
            //add econt fields to order emails.
            add_action('woocommerce_email_before_order_table', array($this, 'econt_email_details'), 10, 3);


        }



        public function woo_cart_has_virtual_product() {
            global $woocommerce;
            // By default, no virtual product
            $has_virtual_products = false;
            // Default virtual products number
            $virtual_products = 0;
            // Get all products in cart
            $products = $woocommerce->cart->get_cart();
            // Loop through cart products
            foreach( $products as $product ) {
               //  print_r($product);
                // Get product ID and '_virtual' post meta
                $product_id = $product['product_id'];
               // print_r(get_post_meta( $product_id, '_virtual', true ));
                //$is_virtual = $product['data']->virtual; //za stari versii na woocommerce predi 2.2 
                if (version_compare(WOOCOMMERCE_VERSION, '2.2', '>=')) {
                $is_virtual = get_post_meta( $product_id, '_virtual', true );
                }else{
                $is_virtual = $product['data']->virtual; //za stari versii na woocommerce predi 2.2  
                }
                // Update $has_virtual_product if product is virtual
                if( $is_virtual == 'yes' ){
                $virtual_products += 1;
                }
            }
            if( count($products) == $virtual_products ){
            $has_virtual_products = true;
            }
            return $has_virtual_products;
        } 





        
        /**
         * Add the  econt offices fields to the checkout
         */
        
        function econt_offices_checkout_fields($checkout) {
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
        $chosen_shipping = $chosen_methods[0];
        //echo $chosen_shipping;
            if( $this->woo_cart_has_virtual_product() == false && $chosen_shipping == 'econt_shipping_method'){
            echo '<div id="econt_custom_checkout_field"><h2>' . __('Econt Express', 'woocommerce-econt') . '</h2>';
            global $woocommerce;
            $wc_econt = new WC_Econt_Shipping_Method;
            $econt_mysql = new Econt_mySQL;
            
            //express city courier
            if( (int)$wc_econt->city_courier == 1 && $wc_econt->send_from == 'DOOR'){
            $sender_address = explode(';',$wc_econt->address);
            
            if( isset($sender_address[1]) ){
            $sender_city_id = $econt_mysql->getCityIdByCityName($sender_address[1]);
            //$sender_city                = $sender_address[1];
                        
            }else{
            $sender_city_id = $econt_mysql->getCityIdByCityName($wc_econt->office_town);
            //$sender_city             = $wc_econt->office_town;   


            }
            
            
            echo '<script type="text/javascript"> var sender_city_id = "' . $sender_city_id['city_id'] .'";</script>'; //define sender city for express city courier 
            }else{

            echo '<script type="text/javascript"> var sender_city_id = ""; </script>'; //define sender city for express city courier 
            }
            //end of city courier

            //office locator in colorbox jquery
            $office_locator = 'https://www.bgmaps.com/templates/econt?office_type=to_office_courier&shop_url=' . get_site_url(); //HTTPS_SERVER;
            $office_locator_domain = 'https://www.bgmaps.com';
            //end of office locator

            //delivery days
            if((int)$wc_econt->delivery_days == 1){
            $delivery_days = $econt_mysql->delivery_days($wc_econt->username, $wc_econt->password, $wc_econt->live);
            }
            //print_r($delivery_days);
            //$cart_total_weight = $woocommerce->cart->cart_contents_weight;
            //$cart_total_price  = $woocommerce->cart->total; //or get_cart_total()
            //$cart_total_count  = $woocommerce->cart->cart_contents_count;
            //$currency = get_woocommerce_currency();

            //echo 'weight:'. $cart_total_weight.' price:' .$cart_total_price . ' count:' .$cart_total_count. ' currency:'.$currency;
            //echo '<script type="text/javascript"> var weight = "' . $cart_total_weight .'";</script>';
            $econt_shipping_to = array();
            $econt_shipping_to[0] = __('please select...', 'woocommerce-econt');
            if($wc_econt->send_to_door == 1){
             $econt_shipping_to['DOOR'] = __('to door', 'woocommerce-econt');   
            }
            if($wc_econt->send_to_office == 1){
             $econt_shipping_to['OFFICE'] = __('to office', 'woocommerce-econt');              
            }
            if($wc_econt->send_to_machine == 1){
             $econt_shipping_to['MACHINE'] = __('to APS', 'woocommerce-econt');              
            }

            //select to Door or ot Office or to APS
            //if( $wc_econt->send_to_door == 1 && $wc_econt->send_to_office == 1 ){
            woocommerce_form_field(
            'econt_shipping_to', array('type' => 'select', 'class' => array('econt_shipping_to form-row-wide'), 'label' => __('Shipping to', 'woocommerce-econt'), 'placeholder' => __('Shipping to', 'woocommerce-econt'), 'options' => $econt_shipping_to), $checkout->get_value('econt_shipping_to'));
            //}
            //To office
            if( $wc_econt->send_to_office == 1 ){
            woocommerce_form_field(
            'econt_offices_town', array('type' => 'text', 'class' => array('econt_shipping_to_office form-row-wide'), 'label' => __('Town (Please, start typing and select from results.)', 'woocommerce-econt'), 'placeholder' => __('Enter your town', 'woocommerce-econt'),), $checkout->get_value('econt_offices_town'));
            
            woocommerce_form_field(
            'econt_offices_postcode', array('type' => 'text', 'class' => array('econt_shipping_to_office form-row-wide'), 'label' => __('Post Code', 'woocommerce-econt'), 'placeholder' => __('Post Code', 'woocommerce-econt'),), $checkout->get_value('econt_offices_postcode'));
            
            include_once( ECONT_PLUGIN_DIR.'/inc/view/html-checkout-view.php' ); //office locator

            woocommerce_form_field(
            'econt_offices', array('type' => 'select', 'class' => array('econt_shipping_to_office form-row-wide'), 'label' => __('Office', 'woocommerce-econt'), 'placeholder' => __('Select office', 'woocommerce-econt'), 'options' => array('0' => __('please select...', 'woocommerce-econt'))), $checkout->get_value('econt_offices'));
            
            if($wc_econt->partial_delivery == 1){
            echo '<div class="econt_shipping_to_office">' . __('We offer our customers partial delivery.', 'woocommerce-econt') . '</div>';    
            }
            if( $wc_econt->send_to_door == 0 )
            echo '<script type="text/javascript">jQuery(".econt_shipping_to_office").slideToggle();</script>';
            }
           // echo '<input name="" class="button econt_shipping_to_office" id="button_calculate_loading_office" value="'.__('Calculate Loading','woocommerce-econt').'" type="button">';

            //To Machine
            if( $wc_econt->send_to_machine == 1 ){
            woocommerce_form_field(
            'econt_machines_town', array('type' => 'text', 'class' => array('econt_shipping_to_machine form-row-wide'), 'label' => __('Town (Please, start typing and select from results.)', 'woocommerce-econt'), 'placeholder' => __('Enter your town', 'woocommerce-econt'),), $checkout->get_value('econt_offices_town'));
            
            woocommerce_form_field(
            'econt_machines_postcode', array('type' => 'text', 'class' => array('econt_shipping_to_machine form-row-wide'), 'label' => __('Post Code', 'woocommerce-econt'), 'placeholder' => __('Post Code', 'woocommerce-econt'),), $checkout->get_value('econt_offices_postcode'));
           

            woocommerce_form_field(
            'econt_machines', array('type' => 'select', 'class' => array('econt_shipping_to_machine form-row-wide'), 'label' => __('Office', 'woocommerce-econt'), 'placeholder' => __('Select office', 'woocommerce-econt'), 'options' => array('0' => __('please select...', 'woocommerce-econt'))), $checkout->get_value('econt_offices'));

            if( $wc_econt->send_to_office == 0 )
            echo '<script type="text/javascript">jQuery(".econt_shipping_to_machine").slideToggle();</script>';
            
            //echo '<script type="text/javascript">jQuery("a#office_locator").hide();</script>';

            }

            //to door
            if( $wc_econt->send_to_door == 1 ){
            woocommerce_form_field(
            'econt_door_town', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Town (Please, start typing and select from results.)', 'woocommerce-econt'), 'placeholder' => __('Enter your town', 'woocommerce-econt'),), $checkout->get_value('econt_door_town'));
            
            woocommerce_form_field(
            'econt_door_postcode', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Post Code', 'woocommerce-econt'), 'placeholder' => __('Post Code', 'woocommerce-econt'),), $checkout->get_value('econt_door_postcode'));
            
            woocommerce_form_field(
            'econt_door_street', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Street (Please, start typing and select from results.)', 'woocommerce-econt'), 'placeholder' => __('Enter your street', 'woocommerce-econt'),), $checkout->get_value('econt_door_street'));
            
            woocommerce_form_field(
            'econt_door_quarter', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Quarter (Please, start typing and select from results.)', 'woocommerce-econt'), 'placeholder' => __('Enter your quarter', 'woocommerce-econt'),), $checkout->get_value('econt_door_quarter'));
            
            woocommerce_form_field(
            'econt_door_street_num', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Street num.:', 'woocommerce-econt'), 'placeholder' => __('street num', 'woocommerce-econt'),), $checkout->get_value('econt_door_street_num'));
            
            woocommerce_form_field(
            'econt_door_street_bl', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Street block num.:', 'woocommerce-econt'), 'placeholder' => __('blok num', 'woocommerce-econt'),), $checkout->get_value('econt_door_street_bl'));
            
            woocommerce_form_field(
            'econt_door_street_vh', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Street entrance num.:', 'woocommerce-econt'), 'placeholder' => __('entr. num', 'woocommerce-econt'),), $checkout->get_value('econt_door_street_vh'));
            
            woocommerce_form_field(
            'econt_door_street_et', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Street floor num.:', 'woocommerce-econt'), 'placeholder' => __('fl. num', 'woocommerce-econt'),), $checkout->get_value('econt_door_street_et'));
            
            woocommerce_form_field(
            'econt_door_street_ap', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Street apartment num.:', 'woocommerce-econt'), 'placeholder' => __('ap. num', 'woocommerce-econt'),), $checkout->get_value('econt_door_street_ap'));
            //echo '<div><h4>' . __('If your address is not in the list please type it here:', 'woocommerce-econt') . '</h4></div>';
            woocommerce_form_field(
            'econt_door_other', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('If your address is not in the list please type it here:', 'woocommerce-econt'), 'placeholder' => __('Enter other adress info', 'woocommerce-econt'),), $checkout->get_value('econt_door_other'));
            if( (int)$wc_econt->delivery_days == 1 && !empty($delivery_days)){
            woocommerce_form_field(
            'econt_delivery_days', array('type' => 'select', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('Delivery Days', 'woocommerce-econt'), 'placeholder' => __('', 'woocommerce-econt'), 'options' => $delivery_days), $checkout->get_value('econt_delivery_days'));    
            }
            if( $wc_econt->city_courier == 1 ){
            woocommerce_form_field(
            'econt_city_courier', array('type' => 'select', 'class' => array('econt_city_courier form-row-wide'), 'label' => __('Express city courier', 'woocommerce-econt'), 'placeholder' => __('', 'woocommerce-econt'), 'options' => array('0' => __('please select...', 'woocommerce-econt'), 'e1' => __('up to 60 minutes', 'woocommerce-econt'), 'e2' => __('up to 90 minutes', 'woocommerce-econt'), 'e3' => __('up to 120 minutes', 'woocommerce-econt'))), $checkout->get_value('econt_city_courier'));
            }
            if($wc_econt->partial_delivery == 1){
            echo '<div class="econt_shipping_to_door">' . __('We offer our customers partial delivery.', 'woocommerce-econt') . '</div>';    
            }
            // if( $wc_econt->send_to_machine == 0 )
            //echo '<script type="text/javascript">jQuery(".econt_shipping_to_door").slideToggle(); jQuery("#econt_city_courier_field").hide();</script>';
            
            //echo '<script type="text/javascript">jQuery("a#office_locator").hide();</script>';

            woocommerce_form_field(
            'econt_customer_shipping_cost', array('type' => 'text', 'class' => array('econt_shipping_cost form-row-wide'), 'label' => __('Econt Customer Shipping Cost:', 'woocommerce-econt'), 'placeholder' => __('Enter Customer Shipping Cost', 'woocommerce-econt'), 'default' => '',), $checkout->get_value('econt_customer_shipping_cost'));
            woocommerce_form_field(
            'econt_total_shipping_cost', array('type' => 'text', 'class' => array('econt_shipping_cost form-row-wide'), 'label' => __('Econt Totam Shipping Cost:', 'woocommerce-econt'), 'placeholder' => __('Econt Total Shipping Cost', 'woocommerce-econt'), 'default' => '',), $checkout->get_value('econt_total_shipping_cost'));
            
            /*
            woocommerce_form_field(
            'econt_door_date', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('If you want to receive your order on specific day:', 'woocommerce-econt'), 'placeholder' => __('Enter other adress info', 'woocommerce-econt'),), $checkout->get_value('econt_door_other'));
            
            woocommerce_form_field(
            'econt_door_time', array('type' => 'text', 'class' => array('econt_shipping_to_door form-row-wide'), 'label' => __('If your address is not in the list please type it here:', 'woocommerce-econt'), 'placeholder' => __('Enter other adress info', 'woocommerce-econt'),), $checkout->get_value('econt_door_other'));
            */

            }  
            echo '<p id="calculate_loading" class="button" style="display: block;">
            <input name="" class="button" id="button_calculate_loading" value="'.__('Calculate Loading','woocommerce-econt').'" type="button"></p>';

            }
            
           

        }
        
        function econt_offices_checkout_field_process() {
          
            // Check if set, if its not set add an error.
            global $woocommerce;

            if ($_POST['econt_shipping_to'] == 'OFFICE') {
                
                if (empty($_POST['econt_offices_town']) || empty($_POST['econt_offices']) || empty($_POST['econt_offices_postcode'])) {
                    
                    if ( function_exists( 'wc_add_notice' ) ) {
                    wc_add_notice(__('Please fill all Econt Express office fileds.', 'woocommerce-econt'), 'error');
                    }else{
                    $woocommerce->add_error(sprintf(__('Please fill all Econt Express office fileds.', 'woocommerce-econt')));
                    }
                }
            } 
            elseif ($_POST['econt_shipping_to'] == 'DOOR') {
                
                if (empty($_POST['econt_door_town']) || empty($_POST['econt_door_postcode']) ) {
                    
                    //if ( empty( $econt_door_town ) || empty( $econt_door_postcode )  ||  (empty( $econt_door_street ) && empty( $econt_door_quarter )) ||  (empty( $econt_door_street_num ) && empty( $econt_door_street_bl )) ){
                    if ( function_exists( 'wc_add_notice' ) ) {
                    wc_add_notice(__('Please fill atleast Econt Express town, postcode and street fileds.', 'woocommerce-econt'), 'error');
                    }else{
                    $woocommerce->add_error(sprintf(__('Please fill atleast Econt Express town, postcode and street fileds.', 'woocommerce-econt')));
                    }
                    
                    }elseif((empty($_POST['econt_door_street']) && empty($_POST['econt_door_quarter'])) || (empty($_POST['econt_door_street_num']) && empty($_POST['econt_door_street_bl'])) ){
                    
                    if(empty($_POST['econt_door_other'])){
                    
                    if ( function_exists( 'wc_add_notice' ) ) {
                    wc_add_notice(__('Please fill atleast Econt Express town, postcode and street fileds.', 'woocommerce-econt'), 'error');
                    }else{
                    $woocommerce->add_error(sprintf(__('Please fill atleast Econt Express town, postcode and street fileds.', 'woocommerce-econt')));
                    }
                    
                    }
                    
                    }
                    

                //proverka za grad, ulica i kvartal suvpadenie s bazata danni na econt
                $address            = array();
                $econt_mysql        = new Econt_mySQL;
                $address['city']       = $_POST['econt_door_town'];
                $address['post_code']  = $_POST['econt_door_postcode'];
                if(!empty($_POST['econt_door_street'])){
                $address['street']     = $_POST['econt_door_street'];
                }
                If(!empty($_POST['econt_door_quarter'])){
                $address['quarter']    = $_POST['econt_door_quarter'];
                }
                
                $validate_address   = $econt_mysql->validateAddress($address);
                //print_r($validate_address);
                if($validate_address['total'] == 0){
                    if ( function_exists( 'wc_add_notice' ) ) {
                    wc_add_notice(__('Invalid city name, street name or quarter name. Please start typing in the box , wait until the list appear and select city name, street name or quarter name from the list.', 'woocommerce-econt'), 'error');
                    }else{
                    $woocommerce->add_error(sprintf(__('Invalid city name, street name or quarter name. Please start typing in the box , wait until the list appear and select city name, street name or quarter name from the list.', 'woocommerce-econt')));
                    }
                }


            }elseif ($_POST['econt_shipping_to'] == '0') {
                if ( function_exists( 'wc_add_notice' ) ) {
                wc_add_notice(sprintf(__('Please fill Econt Express fields.', 'woocommerce-econt')), 'error');
                }else{
                $woocommerce->add_error(sprintf(__('Please fill Econt Express fields.', 'woocommerce-econt')));
                }
            }
/*
            if(empty($_POST['econt_customer_shipping_cost'])){
                    
                if ( function_exists( 'wc_add_notice' ) ) {
                wc_add_notice(__('Please calculate shipping expenses by clicking the "Calculate Loading" button.', 'woocommerce-econt'), 'error');
                }else{
                $woocommerce->add_error(sprintf(__('Please calculate shipping expenses by clicking the "Calculate Loading" button.', 'woocommerce-econt')));
                }
                    
            }
*/
           
        }
        
        function econt_offices_checkout_field_update_order_meta($order_id) {
            
            if (!empty($_POST['econt_shipping_to'])) {
                update_post_meta($order_id, 'Econt_Shipping_To', sanitize_text_field($_POST['econt_shipping_to']));
            }
            if($_POST['econt_shipping_to'] == 'OFFICE'){

                if (!empty($_POST['econt_offices_town'])) {
                    update_post_meta($order_id, 'Econt_Office_Town', sanitize_text_field($_POST['econt_offices_town']));
                }
                if (!empty($_POST['econt_offices'])) {
                    update_post_meta($order_id, 'Econt_Office', sanitize_text_field($_POST['econt_offices']));
                }
                if (!empty($_POST['econt_offices_postcode'])) {
                    update_post_meta($order_id, 'Econt_Office_Postcode', sanitize_text_field($_POST['econt_offices_postcode']));
                }

            }elseif($_POST['econt_shipping_to'] == 'MACHINE'){ 

                if (!empty($_POST['econt_machines_town'])) {
                    update_post_meta($order_id, 'Econt_Machine_Town', sanitize_text_field($_POST['econt_machines_town']));
                }
                if (!empty($_POST['econt_machines'])) {
                    update_post_meta($order_id, 'Econt_Machine', sanitize_text_field($_POST['econt_machines']));
                }
                if (!empty($_POST['econt_machines_postcode'])) {
                    update_post_meta($order_id, 'Econt_Machine_Postcode', sanitize_text_field($_POST['econt_machines_postcode']));
                }


            }elseif($_POST['econt_shipping_to'] == 'DOOR'){

                if (!empty($_POST['econt_door_town'])) {
                    update_post_meta($order_id, 'Econt_Door_Town', sanitize_text_field($_POST['econt_door_town']));
                }
                if (!empty($_POST['econt_door_postcode'])) {
                    update_post_meta($order_id, 'Econt_Door_Postcode', sanitize_text_field($_POST['econt_door_postcode']));
                }
                if (!empty($_POST['econt_door_street'])) {
                    update_post_meta($order_id, 'Econt_Door_Street', sanitize_text_field($_POST['econt_door_street']));
                }
                if (!empty($_POST['econt_door_quarter'])) {
                    update_post_meta($order_id, 'Econt_Door_Quarter', sanitize_text_field($_POST['econt_door_quarter']));
                }
                if (!empty($_POST['econt_door_street_num'])) {
                    update_post_meta($order_id, 'Econt_Door_street_num', sanitize_text_field($_POST['econt_door_street_num']));
                }
                if (!empty($_POST['econt_door_street_bl'])) {
                    update_post_meta($order_id, 'Econt_Door_building_num', sanitize_text_field($_POST['econt_door_street_bl']));
                }
                if (!empty($_POST['econt_door_street_vh'])) {
                    update_post_meta($order_id, 'Econt_Door_Entrance_num', sanitize_text_field($_POST['econt_door_street_vh']));
                }
                if (!empty($_POST['econt_door_street_et'])) {
                    update_post_meta($order_id, 'Econt_Door_Floor_num', sanitize_text_field($_POST['econt_door_street_et']));
                }
                if (!empty($_POST['econt_door_street_ap'])) {
                    update_post_meta($order_id, 'Econt_Door_Apartment_num', sanitize_text_field($_POST['econt_door_street_ap']));
                }
                if (!empty($_POST['econt_door_other'])) {
                    update_post_meta($order_id, 'Econt_Door_Other', sanitize_text_field($_POST['econt_door_other']));
                }
                if (!empty($_POST['econt_city_courier'])) {
                    update_post_meta($order_id, 'Econt_City_Courier', sanitize_text_field($_POST['econt_city_courier']));
                }
                if (!empty($_POST['econt_delivery_days'])) {
                    update_post_meta($order_id, 'Econt_Delivery_Days', sanitize_text_field($_POST['econt_delivery_days']));
                }
            
            }

            if (!empty($_POST['econt_customer_shipping_cost'])) {
                update_post_meta($order_id, 'Econt_Customer_Shipping_Cost', sanitize_text_field($_POST['econt_customer_shipping_cost']));
                            //updateva shipping costa na econt express shipping method
            global $woocommerce;
            $orders = new WC_Order($order_id);
            $shipping_items = $orders->get_items('shipping');
            foreach ($shipping_items as $key => $value) {
                
                $shipping_method_id = $value['method_id'];
                $shipping_method_title = $value['name'];
                $shipping_item_id = $key;
                
            }

            $update_shipping_args = array(
                'method_id' => $shipping_method_id, 
                'method_title' => $shipping_method_title, 
                'cost' => $_POST['econt_customer_shipping_cost'],
                );
            
            //v starite versii na woocommerce nqma method 'update_shipping' i za tova proverqvam dali go ima
            if(method_exists($orders, 'update_shipping')){
            $orders->update_shipping($shipping_item_id, $update_shipping_args);
            
            }


            }
            if (!empty($_POST['econt_total_shipping_cost'])) {
                update_post_meta($order_id, 'Econt_Total_Shipping_Cost', sanitize_text_field($_POST['econt_total_shipping_cost']));
            }


        }
        
        function econt_offices_checkout_field_display_admin_order_meta($order) {
            
            $getoffice = new Econt_mySQL;
            $office_code = get_post_meta($order->id, 'Econt_Office', true);
            $office = $getoffice->getOfficeByOfficeCode($office_code);
            
            $machine_code = get_post_meta($order->id, 'Econt_Machine', true);
            $machine = $getoffice->getOfficeByOfficeCode($machine_code);
            
            if(get_post_meta($order->id, 'Econt_Shipping_To', true) == 'OFFICE') {
                
                //Econt Express Office
                echo '<p><strong>' . __('Econt Town', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Office_Town', true) . '</p>';
                echo '<p><strong>' . __('Econt Office', 'woocommerce-econt') . ':</strong> ' . $office['name'] . __(' address: ', 'woocommerce-econt') . $office['address'] . '</p>';
                echo '<p><strong>' . __('econt_offices_postcode', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Office_Postcode', true) . '</p>';
            }elseif(get_post_meta($order->id, 'Econt_Shipping_To', true) == 'MACHINE'){
            //Econt Express Office
                echo '<p><strong>' . __('Econt Town', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Office_Town', true) . '</p>';
                echo '<p><strong>' . __('Econt Machine', 'woocommerce-econt') . ':</strong> ' . $machine['name'] . __(' address: ', 'woocommerce-econt') . $machine['address'] . '</p>';
                echo '<p><strong>' . __('econt_machines_postcode', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Machine_Postcode', true) . '</p>';    
            }elseif(get_post_meta($order->id, 'Econt_Shipping_To', true) == 'DOOR') {
                
                //Econt Express Door
                echo '<p><strong>' . __('Econt_Door_Town', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_Town', true) . '</p>';
                echo '<p><strong>' . __('Econt_Door_Postcode', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_Postcode', true) . '</p>';
                echo '<p><strong>' . __('Econt_Door_Street', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_Street', true) . '</p>';
                echo '<p><strong>' . __('Econt_Door_Quarter', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_Quarter', true) . '</p>';
                echo '<p><strong>' . __('Econt_Door_street_num', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_street_num', true) . '</p>';
                echo '<p><strong>' . __('Econt_Door_building_num', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_building_num', true) . '</p>';
                echo '<p><strong>' . __('Econt_Door_Entrance_num', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_Entrance_num', true) . '</p>';
                echo '<p><strong>' . __('Econt_Door_Floor_num', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_Floor_num', true) . '</p>';
                echo '<p><strong>' . __('Econt_Door_Apartment_num', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_Apartment_num', true) . '</p>';
                echo '<p><strong>' . __('Econt_Door_Other', 'woocommerce-econt') . ':</strong> ' . get_post_meta($order->id, 'Econt_Door_Other', true) . '</p>';
            }
        }
        
        function econt_filter_billing_fields($fields) {
         global $woocommerce;
         $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
         $chosen_shipping = $chosen_methods[0];
        //echo $chosen_shipping;
            if($chosen_shipping == 'econt_shipping_method'){   
            unset($fields["billing_country"]);
            
            //unset( $fields["billing_company"] );
            unset($fields["billing_address_1"]);
            unset($fields["billing_address_2"]);
            unset($fields["billing_city"]);
            unset($fields["billing_state"]);
            unset($fields["billing_postcode"]);
            
            //unset( $fields["billing_phone"] );
            }

            return $fields;
        }
        
        function econt_filter_shipping_fields($fields) {

            global $woocommerce;
            $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
            $chosen_shipping = $chosen_methods[0];
        
            if($chosen_shipping == 'econt_shipping_method'){

            unset($fields["shipping_first_name"]);
            unset($fields["shipping_last_name"]);
            unset($fields["shipping_company"]);
            unset($fields["shipping_address_1"]);
            unset($fields["shipping_address_2"]);
            unset($fields["shipping_city"]);
            unset($fields["shipping_postcode"]);
            unset($fields["shipping_country"]);
            unset($fields["shipping_state"]);
            
            }

            return $fields;
        }
        
        function econt_offices_display_order_data($order_id) {
            global $woocommerce; // zaradi get_woocommerce_currency_symbol()
            $econt_mysql = new Econt_mySQL;
            //$wc_econt = new WC_Econt_Shipping_Method;
            $office_code = get_post_meta($order_id, 'Econt_Office', true);
            $office = $econt_mysql->getOfficeByOfficeCode($office_code);

            $machine_code = get_post_meta($order_id, 'Econt_Machine', true);
            $machine = $econt_mysql->getOfficeByOfficeCode($machine_code);

            $loading = $econt_mysql->getLoading($order_id);
?>
    <h2><?php
            _e('The goods will be shipped to:', 'woocommerce-econt'); ?></h2>
    <table class="shop_table shop_table_responsive additional_info">
        <tbody>
            
            <?php
            if (get_post_meta($order_id, 'Econt_Shipping_To', true) == 'OFFICE') { ?>
            
            <tr>
                <th><?php
                _e('Econt Town:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Office_Town', true); ?></td>
            </tr>
            <tr>
                <th><?php
                _e('Econt office:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo $office['name'] . __(' address: ', 'woocommerce-econt') . $office['address']; ?></td>
                 </tr>
            <?php
            } 
            elseif (get_post_meta($order_id, 'Econt_Shipping_To', true) == 'MACHINE') { ?>
                <tr>
                <th><?php
                _e('Econt Town:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Machine_Town', true); ?></td>
            </tr>
            <tr>
                <th><?php
                _e('Econt APS:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo $machine['name'] . __(' address: ', 'woocommerce-econt') . $machine['address']; ?></td>
                 </tr>

            
            <?php
            } 
            elseif (get_post_meta($order_id, 'Econt_Shipping_To', true) == 'DOOR') { ?>
                <tr>
                <th><?php
                _e('Econt Town:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_Town', true); ?></td>
            </tr>
            <tr>
                <th><?php
                _e('Econt Postcode:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_Postcode', true); ?></td>
            </tr>
            </tr>
             <tr>
                <th><?php
                _e('Econt Street:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_Street', true); ?></td>
            </tr>
            <tr>
                <th><?php
                _e('Econt Quarter:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_Quarter', true); ?></td>
            </tr>
             <tr>
                <th><?php
                _e('Econt Street Num.:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_street_num', true); ?></td>
            </tr>
            <tr>
                <th><?php
                _e('Econt Building Num.:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_building_num', true); ?></td>
            </tr>
             <tr>
                <th><?php
                _e('Econt Entrance:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_Entrance_num', true); ?></td>
            </tr>
            <tr>
                <th><?php
                _e('Econt Floor:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_Floor_num', true); ?></td>
            </tr>
             <tr>
                <th><?php
                _e('Econt Apartment:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_Apartment_num', true); ?></td>
            </tr>
            <tr>
                <th><?php
                _e('Econt Other:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Door_Other', true); ?></td>
            </tr>
            <?php
            } ?>
             <tr>
                <th><?php
                _e('Econt Shipping Cost:', 'woocommerce-econt'); ?></th>
                <td><?php
                echo get_post_meta($order_id, 'Econt_Customer_Shipping_Cost', true); 
                if(is_numeric( get_post_meta($order_id, 'Econt_Customer_Shipping_Cost', true) ))
                echo get_woocommerce_currency_symbol();
                ?></td>
            </tr>
            <?php // if((int)$wc_econt->return_item == 1){ 
//tuk trqbva da sloja usloviqta za generirane na tovaritelnica za vrushtane na poruchka

                ?>
            <tr>
                <th><?php
               // _e('Generate loading to return the received order:', 'woocommerce-econt'); ?></th>
                <td><?php
               // echo get_post_meta($order_id, 'Econt_Customer_Shipping_Cost', true) . get_woocommerce_currency_symbol(); ?></td>
            </tr>
            <?php // } ?>
        </tbody>
    </table>
<?php
           
    
    }
        
        //email data
        
        function econt_email_order_meta_fields($fields, $sent_to_admin, $order) {
            if($this->woo_cart_has_virtual_product() == false){
            $getoffice = new Econt_mySQL;
            $office_code = get_post_meta($order->id, 'Econt_Office', true);
            $office = $getoffice->getOfficeByOfficeCode($office_code);

            $machine_code = get_post_meta($order->id, 'Econt_Machine', true);
            $machine = $getoffice->getOfficeByOfficeCode($office_code);
            
            if(get_post_meta($order->id, 'Econt_Shipping_To', true) == 'OFFICE'){
                
                $fields['Econt_Office_Town'] = array('label' => __('Econt Town', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Office_Town', true),);
                
                $fields['Econt_Office'] = array('label' => __('Econt office', 'woocommerce-econt'), 'value' => $office['name'] . __(' address: ', 'woocommerce-econt') . $office['address'],);
            }elseif(get_post_meta($order->id, 'Econt_Shipping_To', true) == 'MACHINE'){
                $fields['Econt_Machine_Town'] = array('label' => __('Econt Town', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_MAchine_Town', true),);
                
                $fields['Econt_Machine'] = array('label' => __('Econt APS', 'woocommerce-econt'), 'value' => $machine['name'] . __(' address: ', 'woocommerce-econt') . $machine['address'],);

            }elseif (get_post_meta($order->id, 'Econt_Shipping_To', true) == 'DOOR'){
                
                $fields['Econt_Door_Town'] = array('label' => __('Econt Town', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_Town', true),);
                
                $fields['Econt_Door_Postcode'] = array('label' => __('Econt Postcode', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_Postcode', true),);
                
                $fields['Econt_Door_Street'] = array('label' => __('Econt Street', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_Street', true),);
                
                $fields['Econt_Door_Quarter'] = array('label' => __('Econt Quarter', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_Quarter', true),);
                
                $fields['Econt_Door_street_num'] = array('label' => __('Econt Street Num.', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_street_num', true),);
                
                $fields['Econt_Door_building_num'] = array('label' => __('Econt Building Num.', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_building_num', true),);
                
                $fields['Econt_Door_Entrance_num'] = array('label' => __('Econt Entrance', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_Entrance_num', true),);
                
                $fields['Econt_Door_Floor_num'] = array('label' => __('Econt Floor', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_Floor_num', true),);
                
                $fields['Econt_Door_Apartment_num'] = array('label' => __('Econt Apartment', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_Apartment_num', true),);
                
                $fields['Econt_Door_Other'] = array('label' => __('Econt Other', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Door_Other', true),);
            }
                $fields['Econt_Customer_Shipping_Cost'] = array('label' => __('Econt Shipping Cost', 'woocommerce-econt'), 'value' => get_post_meta($order->id, 'Econt_Customer_Shipping_Cost', true),);
        }
            return $fields;
        }
        
        //econt email
        public function econt_email_details($order, $sent_to_admin, $plain_text = false) {
           if (version_compare(WOOCOMMERCE_VERSION, '2.2', '>=')) {
            $shipping_items = $order->get_items('shipping');
            
            foreach ($shipping_items as $key => $value) {
                
                $shipping_method_id = $value['method_id'];
                $shipping_method_title = $value['name'];
                $shipping_item_id = $key;
           }
            
            if ('econt_shipping_method' === $shipping_method_id) {
                
                $this->econt_offices_display_order_data($order->id);
            }
        
            }else{
             $this->econt_offices_display_order_data($order->id);   
            }

         }
            //if card price is equal or less than nim_price for leasing, leasing payment gw is removed from checkout
        public function filter_cod_gateway($gateways){
            
            global $woocommerce;
            $wc_econt = new WC_Econt_Shipping_Method;
            //$leasing_price = floatval( preg_replace( '#[^\d.]#', '', $woocommerce->cart->get_cart_total() ) );
            //echo 'nim_price:'.$leasing_gw->min_price;
            //echo 'leasing_price:'. $leasing_price;
            if($wc_econt->cd != 1){
            unset($gateways['cod']);
            }
            
            return $gateways;

        }

    }
}


new Econt_Order();

?>
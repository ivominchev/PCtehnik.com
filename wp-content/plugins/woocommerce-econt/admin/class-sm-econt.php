<?php
/*
Plugin Name: Econt Shipping plugin
Plugin URI: http://mreja.net
Description: Mreja.net's Econt shipping method plugin
Version: 1.0.0
Author: Mreja.Net
Author URI: http://mreja.net
*/
 
/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
	function econt_shipping_method_init() {
		if ( ! class_exists( 'WC_Econt_Shipping_Method' ) ) {
			class WC_Econt_Shipping_Method extends WC_Shipping_Method {
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                                   = 'econt_shipping_method'; // Id for your shipping method. Should be uunique.
					$this->method_title                         = __( 'Econt Shipping Method', 'woocommerce-econt' );  // Title shown in admin
					$this->method_description                   = __( 'Ship you goods with Econt Express', 'woocommerce-econt' ); // Description shown in admin
 
					$this->enabled                              = $this->get_option( 'enabled' );
					$this->title                                = $this->get_option( 'title' );
 					
 					$this->username                             = $this->get_option( 'username' );
					$this->password                             = $this->get_option( 'password' );
					$this->live 				                = $this->get_option( 'live' );
					$this->company 				                = $this->get_option( 'company');
					$this->name 	                            = $this->get_option( 'name' );
					$this->phone 	                            = $this->get_option( 'phone' );
					$this->address 				                = $this->get_option( 'address');
 					$this->office_town                          = $this->get_option( 'office_town' );
					$this->office_postcode                      = $this->get_option( 'office_postcode' );
					$this->office_office 		                = $this->get_option( 'office_office');
					$this->office_code 			                = $this->get_option( 'office_code' );
                    $this->machine_town                         = $this->get_option( 'machine_town' );
                    $this->machine_postcode                     = $this->get_option( 'machine_postcode' );
                    $this->machine_machine                      = $this->get_option( 'machine_machine');
                    $this->machine_code                         = $this->get_option( 'machine_code' );
					//$this->client_credit_num	 = $this->get_option( 'client_credit_num' );
                    $this->payment_side                         = $this->get_option( 'payment_side' );
					$this->client_payment_type	                = $this->get_option( 'client_payment_type' );
					$this->client_voucher	 	                = $this->get_option( 'client_voucher' );
					$this->client_bonus_points	                = $this->get_option( 'client_bonus_points' );
                    $this->cd                                   = $this->get_option( 'cd' );
					$this->client_cd_num 	                    = $this->get_option( 'client_cd_num' );
					//$this->address 				                = $this->get_option( 'address');
					$this->free_shipping_sum	                = $this->get_option( 'free_shipping_sum');
                    $this->free_shipping_weight                 = $this->get_option( 'free_shipping_weight');
                    $this->free_shipping_count                  = $this->get_option( 'free_shipping_count');
 					$this->oc					                = $this->get_option( 'oc');
					$this->partial_delivery		                = $this->get_option( 'partial_delivery' );
					$this->send_from 			                = $this->get_option( 'send_from' );
                    $this->send_to_door                         = $this->get_option( 'send_to_door' );
                    $this->send_to_office                       = $this->get_option( 'send_to_office' );
                    $this->send_to_machine                      = $this->get_option( 'send_to_machine' );
                    $this->city_courier                         = $this->get_option( 'city_courier' );
                    $this->dc                                   = $this->get_option( 'dc' );
                    $this->dc_cp                                = $this->get_option( 'dc_cp' );
                    //predstoi da se premahne ot API-to na Econt
                    //$this->sms                                  = $this->get_option( 'sms' );
                    $this->sms                                  = 0;
                    $this->invoice                              = $this->get_option( 'invoice' );
                    $this->pay_after                            = $this->get_option( 'pay_after' );
                    //$this->pay_after_test                       = $this->get_option( 'pay_after_test' );
                    //$this->instruction_returns_shipping_returns = $this->get_option( 'instruction_returns_shipping_returns' );
                    //predstoi da se premahne ot API-to na Econt
                    //$this->instruction_returns                  = $this->get_option( 'instruction_returns' );
                    $this->instruction_returns                  = 0;
                    $this->priority_time                        = $this->get_option( 'priority_time' );
                    $this->delivery_days                        = $this->get_option( 'delivery_days' );
                    $this->inventory                            = $this->get_option( 'inventory' );
                    $this->return_item                          = $this->get_option( 'return_item' );
                    $this->instructions_take                    = $this->get_option( 'instructions_take' );
                    $this->instructions_give                    = $this->get_option( 'instructions_give' );
                    $this->instructions_return                  = $this->get_option( 'instructions_return' );
                    $this->instructions_services                = $this->get_option( 'instructions_services' );
 //                   $this->shipping_payment1                    = $this->get_option( 'shipping_payment1' );
 //                   $this->shipping_payment2                    = $this->get_option( 'shipping_payment2' );
                    

                    $this->shipping_payments                    = get_option( 'econt_shipping_payments');


 
					$this->init();
					
					
 					
 					//exit('live:'.$this->username);
				}
 
				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					// Load the settings API
					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
 					

					// Save settings in admin if you have any defined
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'save_shipping_payments_details' ) );
				}

				

					public function init_form_fields(){ 

					$cd_agreement_nums 		= array();
					$cd_agreement_nums[0] 	= __('please select', 'woocommerce-econt');

					$key_words 		   		= array();
					$key_words['CASH']		= __('Cash', 'woocommerce-econt');
                    
                    $instructions_give      = array();
                    $instructions_take      = array();
                    $instruction_return     = array();
                    $instruction_services   = array();


                    //$instructions[0]        = __('please select', 'woocommerce-econt');
                        
					
					$sender_addresses  		= array();
					$sender_addresses[0] 	= __('please select', 'woocommerce-econt');

					$office_office			= array();
					$office_office[0]	    = __('please select', 'woocommerce-econt');

                    $machine_machine        = array();
                    $machine_machine[0]     = __('please select', 'woocommerce-econt');

					$name 					= '';
					$phone 					= '';

					if($this->username && $this->password){ 
						
						if(class_exists('Econt_mySQL')){
						$econt_mysql 	= new Econt_mySQL;	
						}
						
						if($this->office_code){
						//$getoffice = new Econt_mySQL;
    					//$office_code = get_post_meta( $order->id, 'Econt_Office', true );
    					$office = $econt_mysql->getOfficeByOfficeCode($this->office_code);
    					unset($office_office[0]);
    					$office_office[$office['name'].';'.$office['address']] = $office['name'].__(', address: ', 'woocommerce-econt').$office['address'];

						}

                        if($this->machine_code){
                        //$getoffice = new Econt_mySQL;
                        //$office_code = get_post_meta( $order->id, 'Econt_Office', true );
                        $machine = $econt_mysql->getOfficeByOfficeCode($this->machine_code);
                        unset($machine_machine[0]);
                        $machine_machine[$machine['name'].';'.$machine['address']] = $machine['name'].__(', address: ', 'woocommerce-econt').$machine['address'];

                        }

						$profile 		= $econt_mysql->getProfile($this->username, $this->password, $this->live);
						if(!array_key_exists('error', $profile)){
						$name 			= $profile['client_info']->mol->__toString();
						}
					
						//exit(print_r($profile[addresses][0]->city_post_code->__toString()));
						$client_info = $econt_mysql->getClients($this->username, $this->password, $this->live);
						//print_r($client_info);
						$cd_agreement_nums[0] = __('No', 'woocommerce-econt');
						if(isset($client_info['cd_agreement_nums'])){
						foreach ($client_info['cd_agreement_nums'] as $key => $value) {
							$cd_agreement_nums[$value] = $value;
						}
						}
						
						//$key_words[0] = __('No', 'woocommerce-econt');
						if(isset($client_info['key_words'])){
						foreach ($client_info['key_words'] as $key => $value) {
							$key_words[$value] = $value;
						}
						}
                        $key_words['VOUCHER']   = __('Voucher', 'woocommerce-econt');
                        $key_words['BONUS']     = __('Bonus points', 'woocommerce-econt');

                        $instructions_take[0] = __('No', 'woocommerce-econt');
                        if(isset($client_info['instructions']['take'])){
                        foreach ($client_info['instructions']['take'] as $key => $value) {
                            $instructions_take[$value] = $value;
                        }
                        }

                        $instructions_give[0] = __('No', 'woocommerce-econt');
                        if(isset($client_info['instructions']['give'])){
                        foreach ($client_info['instructions']['give'] as $key => $value) {
                            $instructions_give[$value] = $value;
                        }
                        }

                        $instructions_return[0] = __('No', 'woocommerce-econt');
                        if(isset($client_info['instructions']['return'])){
                        foreach ($client_info['instructions']['return'] as $key => $value) {
                            $instructions_return[$value] = $value;
                        }
                        }

                        $instructions_services[0] = __('No', 'woocommerce-econt');
                        if(isset($client_info['instructions']['services'])){
                        foreach ($client_info['instructions']['services'] as $key => $value) {
                            $instructions_services[$value] = $value;
                        }
                        }

                        if(isset($profile['addresses'])){
						foreach ($profile['addresses'] as $key => $value) {
						   $sender_addresses[$value->city_post_code->__toString().';'.$value->city->__toString().';'.$value->quarter->__toString().';'.$value->street->__toString().';'.$value->street_num->__toString().';'.$value->other->__toString().';'.$value->city_id->__toString()] = __('p.c. ', 'woocommerce-econt').$value->city_post_code->__toString().__(', t./v. ', 'woocommerce-econt').$value->city->__toString().__(', q.: ', 'woocommerce-econt').$value->quarter->__toString().', '.$value->street->__toString().', №: '.$value->street_num->__toString().__(', other ', 'woocommerce-econt').$value->other->__toString().', '.$value->city_id->__toString();	
						}
                        }
					//exit(print_r($phone));

					}

		$this->form_fields = array(
			'enabled' => array(
			'title' => __( 'Enable/Disable', 'woocommerce-econt' ),
			'type' => 'checkbox',
			'label' => __( 'Enable Econt Express Shipping Method', 'woocommerce-econt' ),
			'default' => 'yes'
			),

			'title' => array(
			'title' => __( 'Title', 'woocommerce-econt' ),
			'type' => 'text',
			'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-econt' ),
			'default' => __( 'Econt Express Shipping Method', 'woocommerce-econt' ),
			'desc_tip'      => true,
			),

			'description' => array(
			'title' => __( 'Customer Message', 'woocommerce-econt' ),
			'type' => 'textarea',
			'description' => __( 'Checkout description', 'woocommerce-econt' ),
			'default' => __( 'Shipping your goods with Econt Express.', 'woocommerce-econt' ),
			),
            'live' => array(
            'title' => __( 'Live or test?', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(1 => 'live', 0 => 'test'),
            'description' => __('Choose live or test API server', 'woocommerce-econt'),
            ),

			'username' => array(
            'title' => __( 'username', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Econt Express username', 'woocommerce-econt' )
            ),
            
            'password' => array(
            'title' => __( 'Password', 'woocommerce-econt' ),
            'type' => 'password',
            'description' =>  __( 'Econt Express password.', 'woocommerce-econt' ),
            ),
            );
           // if($this->username && $this->password){
            $form_fileds2 = array(
            'refreshdata' => array(
            'title' => __( 'Refresh Data', 'woocommerce-econt' ),
            'type' => 'button',
            'default' => __('Refresh', 'woocommerce-econt'),
            'description' =>  __( 'Refresh Econt Express Ofices, Cities, Streets.', 'woocommerce-econt' ),
            ),

            'company' => array(
            'title' => __( 'Company', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Your Company Name', 'woocommerce-econt' )
            ),

            'name' => array(
            'title' => __( 'Name', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Your Name', 'woocommerce-econt' ),
            ),
            
            'phone' => array(
            'title' => __( 'phone', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Your Phone registered in Econt Express account', 'woocommerce-econt' ),
            ),
            
            'address' => array(
            'title' => __( 'Sender Address', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => $sender_addresses,
            'description' => __('The addresses are taken from your Econt Express profile at http://ee.econt.com. Choose one if you want to be able to send from your door.', 'woocommerce-econt'),
            ),

            'office_town' => array(
            'title' => __( 'Office Town', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Choose Econt Express office town if you want to be able to send from office', 'woocommerce-econt' )
            ),
			
			'office_postcode' => array(
            'title' => __( 'Econt Express Office Postcode', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Choose Econt Express office town postcode if you want to be able to send from office', 'woocommerce-econt' )
            ),

            'office_office' => array(
            'title' => __( 'Econt Express Office', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => $office_office,
            'description' => __('Choose Econt Express office if you want to be able to send from office', 'woocommerce-econt'),
            ),

            'office_code' => array(
            'title' => __( 'Econt Express Office Code', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Choose Econt Express office code if you want to be able to send from office', 'woocommerce-econt' )
            ),

            'machine_town' => array(
            'title' => __( 'APS Town', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Choose Econt Express office town if you want to be able to send from APS', 'woocommerce-econt' )
            ),
            
            'machine_postcode' => array(
            'title' => __( 'Econt Express APS Postcode', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Choose Econt Express APS town postcode if you want to be able to send from office', 'woocommerce-econt' )
            ),

            'machine_machine' => array(
            'title' => __( 'Econt Express APS', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => $machine_machine,
            'description' => __('Choose Econt Express office if you want to be able to send from office', 'woocommerce-econt'),
            ),

            'machine_code' => array(
            'title' => __( 'Econt Express APS Code', 'woocommerce-econt' ),
            'type' => 'text',
            'description' => __( 'Choose Econt Express APS code if you want to be able to send from office', 'woocommerce-econt' )
            ),
            
            'payment_side' => array(
            'title' => __( 'Payment side', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array('RECEIVER' => __('Receiver', 'woocommerce-econt'), 'SENDER' => __('Sender', 'woocommerce-econt')),
            'description' => __('Payment side', 'woocommerce-econt'),
            ),

            'client_payment_type' => array(
            'title' => __( 'Choose the way you pay.', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => $key_words,
            'description' => __('When the shipping is payed by the sender if you pay on credit please chose your client number or Cash, Bonus points and Voucher', 'woocommerce-econt'),
            ),
/*
            'client_voucher' => array(
            'title' => __( 'If you pay with voucher', 'woocommerce-econt' ),
            'type' => 'text',
            'default' => 0,
            'description' => __( 'When the shipping is payed by the sender and if you pay wth voucher please enter it.', 'woocommerce-econt' )
            ),

            'client_bonus_points' => array(
            'title' => __( 'If you pay with bonus points', 'woocommerce-econt' ),
            'type' => 'text',
            'default' => 0,
            'description' => __( 'When the shipping is payed by the sender and if you pay wth bonus points please enter them.', 'woocommerce-econt' )
            ),
*/

            'cd' => array(
            'title' => __( 'Will you allow Cash on delivery', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(1 => __('Yes', 'woocommerce-econt'), 0 => __('No', 'woocommerce-econt')),
            'description' => __('Will you allow Cash on delivery', 'woocommerce-econt'),
            ),

            'client_cd_num' => array(
            'title' => __( 'Are you going to use an agreement for CD', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => $cd_agreement_nums,
            'description' => __('If you are going to use and agreement for collecting your cashe on delivery please select it.', 'woocommerce-econt'),
            ),

            'free_shipping_sum' => array(
            'title' => __( 'Free shipping above this sum', 'woocommerce-econt' ),
            'type' => 'text',
            'default' => 0,
            'description' => __( 'Free shipping for orders above this sum if you whrite down 0 there will be no free shipping.', 'woocommerce-econt' )
            ),

            'free_shipping_count' => array(
            'title' => __( 'Free shipping above this count of items', 'woocommerce-econt' ),
            'type' => 'text',
            'default' => 0,
            'description' => __( 'Free shipping for orders above this count of items if you whrite down 0 there will be no free shipping.', 'woocommerce-econt' )
            ),

            'free_shipping_weight' => array(
            'title' => __( 'Free shipping above this weight', 'woocommerce-econt' ),
            'type' => 'text',
            'default' => 0,
            'description' => __( 'Free shipping for orders above this weight in kg if you whrite down 0 there will be no free shipping.', 'woocommerce-econt' )
            ),

            'oc' => array(
            'title' => __( 'Declared Value', 'woocommerce-econt' ),
            'type' => 'text',
            'default' => 0,
            'description' => __( '0 = no "DV", 1 = Always "DV", 2...n =  The cost above which "DV" will be enabled', 'woocommerce-econt' )
            ),

            'send_from' => array(
            'title' => __( 'Default send from', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array('OFFICE' => __('Office', 'woocommerce-econt'), 'DOOR' => __('Door', 'woocommerce-econt'), 'MACHINE' => __('Machine', 'woocommerce-econt')),
            'description' => __('Chose from where you will send your goods by default: Econt Office or your address.', 'woocommerce-econt'),
            ),
            //new
            'send_to_door' => array(
            'title' => __( 'Offer your clients delivery to door', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(1 => __('Yes', 'woocommerce-econt'), 0 => __('No', 'woocommerce-econt')),
            'description' => __('Offer your clients delivery to door', 'woocommerce-econt'),
            ),

            'send_to_office' => array(
            'title' => __( 'Offer your clients delivery to Econt offices', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(1 => __('Yes', 'woocommerce-econt'), 0 => __('No', 'woocommerce-econt')),
            'description' => __('Offer your clients delivery to Econt offices', 'woocommerce-econt'),
            ),

            'send_to_machine' => array(
            'title' => __( 'Offer your clients delivery to Econt machine offices', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(1 => __('Yes', 'woocommerce-econt'), 0 => __('No', 'woocommerce-econt')),
            'description' => __('Offer your clients delivery to Econt machine offices', 'woocommerce-econt'),
            ),

            'city_courier' => array(
            'title' => __( 'Offer your customers express city courier delivery up to 60, 90 or 120 minutes', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 1 => __('Yes', 'woocommerce-econt')),
            'description' => __('Offer your customers express city courier delivery up to 60, 90 or 120 minutes', 'woocommerce-econt'),
            ),

			'dc' => array(
            'title' => __( 'Attach a service acknowledgment', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 1 => __('Yes', 'woocommerce-econt')),
            'description' => __('Attach a service acknowledgment', 'woocommerce-econt'),
            ),

			'dc_cp' => array(
            'title' => __( 'Attach a service acknowledgment/bill of goods', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 1 => __('Yes', 'woocommerce-econt')),
            'description' => __('Attach a service acknowledgment/bill of goods', 'woocommerce-econt'),
            ),
//predstoi da se premahne ot API-to na Econt
/*
			'sms' => array(
            'title' => __( 'Attach a SMS service for delivery of shipment', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 1 => __('Yes', 'woocommerce-econt')),
            'description' => __('Attach a SMS service for delivery of shipment', 'woocommerce-econt'),
            ),
*/
			'invoice' => array(
            'title' => __( 'To add the service of delivering an invoice before paying cash on delivery', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 1 => __('Yes', 'woocommerce-econt')),
            'description' => __('To add the service of delivering an invoice before paying cash on delivery:', 'woocommerce-econt'),
            ),

            'pay_after' => array(
            'title' => __( 'Customer can pay after accepting or testing the goods', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('None', 'woocommerce-econt'), 'accept' => __('Accept', 'woocommerce-econt'), 'test' => __('Test', 'woocommerce-econt')),
            'description' => __('Customer can pay after accepting or testing the goods', 'woocommerce-econt'),
            ),
/*
            'pay_after_test' => array(
            'title' => __( 'Customer can pay after testing the goods', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 1 => __('Yes', 'woocommerce-econt')),
            'description' => __('Customer can pay after testing the goods', 'woocommerce-econt'),
            ),
*/

//predstoi da se premahne ot API-to na Econt
/*

            'instruction_returns' => array(
            'title' => __( 'dispose to refuse shipment after review:', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('None', 'woocommerce-econt'), 'shipping_returns' => __('Supply and return are at my expense', 'woocommerce-econt'), 'returns' => __('Return is at my expense', 'woocommerce-econt')),
            'description' => __('dispose to refuse shipment after review: supply and return or return are at my expense', 'woocommerce-econt'),
            ),
*/

/*
            'instruction_returns' => array(
            'title' => __( 'dispose to refuse shipment after review: the return is at my expense', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(1 => __('Yes', 'woocommerce-econt'), 0 => __('No', 'woocommerce-econt')),
            'description' => __('dispose to refuse shipment after review: the return is at my expense', 'woocommerce-econt'),
            ),
*/
            'priority_time' => array(
            'title' => __( 'Attach a time priority', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 0 => __('Yes', 'woocommerce-econt')),
            'description' => __('Attach a time priority', 'woocommerce-econt'),
            ),

            'delivery_days' => array(
            'title' => __( 'offer the customer a choice of day for delivery', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 1 => __('Yes', 'woocommerce-econt')),
            'description' => __('offer the customer a choice of day for delivery', 'woocommerce-econt'),
            ),

            'partial_delivery' => array(
            'title' => __( 'offer the customer partial delivery', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 1 => __('Yes', 'woocommerce-econt')),
            'description' => __('offer the customer partial delivery', 'woocommerce-econt'),
            ),

            'inventory' => array(
            'title' => __( 'Submission of packing list', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 'DIGITAL' => __('Digital', 'woocommerce-econt'), 'LOADING' => __('Attached to the parcel', 'woocommerce-econt')),
            'description' => __('Submission of packing list', 'woocommerce-econt'),
            ),

            'return_item' => array(
            'title' => __( 'Ability to return the item already purchased', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => array(0 => __('No', 'woocommerce-econt'), 1 => __('Yes', 'woocommerce-econt')),
            'description' => __('Ability to return the item already purchased:', 'woocommerce-econt'),
            ),

            'instructions_take' => array(
            'title' => __( 'Choose take custom instructions', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => $instructions_take,
            'description' => __('Chose take custom instructions', 'woocommerce-econt'),
            ),

            'instructions_give' => array(
            'title' => __( 'Choose give custom instructions', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => $instructions_give,
            'description' => __('Chose give custom instructions', 'woocommerce-econt'),
            ),

            'instructions_return' => array(
            'title' => __( 'Choose return custom instructions', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => $instructions_return,
            'description' => __('Chose return custom instructions', 'woocommerce-econt'),
            ),

            'instructions_services' => array(
            'title' => __( 'Choose custom instructions for services', 'woocommerce-econt' ),
            'type' => 'select',
            'options' => $instructions_services,
            'description' => __('Chose custom instructions for services', 'woocommerce-econt'),
            ),
/*
            'shipping_payment1' => array(
            'title' => __( 'Payment of delivery', 'woocommerce-econt' ),
            'type' => 'text',
            'default' => 0,
            'description' => __( 'If you want to 4BGN from the shipping cost for orders above 100BGN Example: 100;4', 'woocommerce-econt' )
            ),
            
            'shipping_payment2' => array(
            'title' => __( 'Payment of delivery', 'woocommerce-econt' ),
            'type' => 'text',
            'default' => 0,
            'description' => __( 'If you want to 5BGN from the shipping cost for orders above 150BGN Example: 150;5', 'woocommerce-econt' )
            ),
*/
            'shipping_payments' => array(
            //'title' => __( 'Payment of delivery', 'woocommerce-econt' ),
            'type' => 'shipping_payments',
            //'default' => 0,
            //'description' => __( 'If you want to 5BGN from the shipping cost for orders above 150BGN Example: 150;5', 'woocommerce-econt' )
            ),
       // }
		);
		if($this->username && $this->password){
         $this->form_fields =   array_merge($this->form_fields, $form_fileds2);
        }

	}


    public function generate_shipping_payments_html() {
        //if($this->username && $this->password){
        ob_start();

        ?>
        <tr valign="top">
            <th scope="row" class="titledesc"><?php _e( 'Shipping Payments', 'woocommerce-econt' ); ?>:</th>
            <td class="forminp" id="shipping_payments">
                <table class="widefat wc_input_table sortable" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="sort">&nbsp;</th>
                            <th><?php _e( 'Order Cost Above:', 'woocommerce-econt' ); ?></th>
                            <th><?php _e( 'The customer will pay for shipping to door:', 'woocommerce-econt' ); ?></th>
                            <th><?php _e( 'The customer will pay for shipping to office or APS', 'woocommerce-econt' ); ?></th>
                        </tr>
                    </thead>
                    <tbody class="shipping_payment">
                        <?php
                        $i = -1;
                        //print_r($this->shipping_payments);
                        if ( $this->shipping_payments ) {
                            foreach ( $this->shipping_payments as $shipping_payment ) {
                                $i++;
                                //print_r($shipping_payment);
                                echo '<tr class="shipping_payment">
                                    <td class="sort"></td>
                                    <td><input type="text" value="' . esc_attr( $shipping_payment['order_amount'] ) . '" name="shipping_payments[' . $i . '][order_amount]" /></td>
                                    <td><input type="text" value="' . esc_attr( $shipping_payment['receiver_amount'] ) . '" name="shipping_payments[' . $i . '][receiver_amount]" /></td>
                                    <td><input type="text" value="' . esc_attr( $shipping_payment['receiver_amount_office'] ) . '" name="shipping_payments[' . $i . '][receiver_amount_office]" /></td>
                                </tr>';
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7"><a href="#" class="add button"><?php _e( '+ Add Row', 'woocommerce-econt' ); ?></a> <a href="#" class="remove_rows button"><?php _e( 'Remove selected Row(s)', 'woocommerce-econt' ); ?></a></th>
                        </tr>
                    </tfoot>
                </table>
                <script type="text/javascript">
                    jQuery(function() {
                        jQuery('#shipping_payments').on( 'click', 'a.add', function(){

                            var size = jQuery('#shipping_payments tbody .shipping_payment').size();

                            jQuery('<tr class="shipping_payment">\
                                    <td class="sort"></td>\
                                    <td><input type="text" name="shipping_payments[' + size + '][order_amount]" /></td>\
                                    <td><input type="text" name="shipping_payments[' + size + '][receiver_amount]" /></td>\
                                    <td><input type="text" name="shipping_payments[' + size + '][receiver_amount_office]" /></td>\
                                </tr>').appendTo('#shipping_payments table tbody');

                            return false;
                        });
                    });
                </script>
            </td>
        </tr>
        <?php
        return ob_get_clean();
        //}
    }



    public function save_shipping_payments_details() {

        $shipping_payments = array();
        //print_r($_POST['shipping_payment']);
        if ( isset( $_POST['shipping_payments'] ) ) {

            $shipping_payments = $_POST['shipping_payments'];
        }
        
        update_option( 'econt_shipping_payments', $shipping_payments );

    }



    private function get_pages($title = false, $indent = true) {
        $wp_pages = get_pages('sort_column=menu_order');
        $page_list = array();
        if ($title) $page_list[] = $title;
        foreach ($wp_pages as $page) {
            $prefix = '';
            // show indented child pages?
            if ($indent) {
                $has_parent = $page->post_parent;
                while($has_parent) {
                    $prefix .=  ' - ';
                    $next_page = get_page($has_parent);
                    $has_parent = $next_page->post_parent;
                }
            }
            // add to page list array array
            $page_list[$page->ID] = $prefix . $page->post_title;
        }
        return $page_list;
    }

 
				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping( $package=array() ) {
			//		$econt_mysql = new Econt_mySQL;
			//		$loading = $econt_mysql->getLoading($thepostid);
	
			//if($loading != false){

			
			//$cost = $loading['order_total_sum'];

			//}else{
					$cost = 0;
			//	}
					$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						//'cost' => '10.99',
						'cost' => $cost,
						'taxes' =>false,
						//'calc_tax' => 'per_item'
					);

					$rate2 = array(
						'id' => $this->id.'2',
						'label' => $this->title.__(' to office', 'woocommerce-econt'),
						'cost' => '10.88',
						//'cost' => $cost,
						'taxes' =>false,
						//'calc_tax' => 'per_item'
					);
 
					// Register the rate
					$this->add_rate( $rate );
					//$this->add_rate( $rate2 );
				}
			}
		}
	}
 
	add_action( 'woocommerce_shipping_init', 'econt_shipping_method_init' );
 
	function add_econt_shipping_method( $methods ) {
		$methods[] = 'WC_Econt_Shipping_Method';
		return $methods;
	}
 function bla(){
 	exit( 'bla');
 }


	add_filter( 'woocommerce_shipping_methods', 'add_econt_shipping_method' );
	
//this filter removes the (free) next to "Econt Shipping Method" in Checkout
add_filter( 'woocommerce_cart_shipping_method_full_label', 'remove_local_pickup_free_label', 10, 2 );
function remove_local_pickup_free_label($full_label, $method){
	$label = ' (' . __( 'Free', 'woocommerce' ) . ')';
	$full_label = str_replace($label,"",$full_label);
/*
	if(WPLANG == "bg_BG"){
		$full_label = str_replace("(Безплатно)","",$full_label);
	}else{
    	$full_label = str_replace("(Free)","",$full_label);
	}
*/


return $full_label;
}

}

<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if(!class_exists('Econt_Admin_Order')) {

	class Econt_Admin_Order {
		private $data;

		public function __construct() {
			
			//add meta box to order details
			add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ) );
			
			//add my css and js scripts 
			add_action( 'wp_enqueue_scripts', array( &$this,'econt_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( &$this,'econt_scripts' ) );

			//add orders overview econt column
			add_filter( 'manage_edit-shop_order_columns', array(&$this, 'econt_add_orders_overview_columns') );

			//orders overview econt column values
			add_action( 'manage_shop_order_posts_custom_column', array(&$this, 'econt_orders_overview_columns_values'), 2 );
			
			//orders overview econt column sort
			//add_filter( "manage_edit-shop_order_sortable_columns", array(&$this, 'econt_orders_overview_columns_sort') );
		
		}

		public function add_meta_boxes() {

			add_meta_box( 'econt-order', __( 'Econt Express - Order Viewer', 'woocommerce-econt' ), array( &$this, 'econt_product_order'), 'shop_order', 'normal', 'default' );

		}

		public function add_order_item_header() {

			?>
			<th class="econt-express"><?php _e( 'Econt Express Woocommerce', 'woocommerce-econt' ); ?></th>
			<?php

		}


		//add econt panel to order post
		public function econt_product_order( $post ) {

			global $post, $woocommerce, $thepostid;
			$orders = new WC_Order($thepostid);
			$wc_econt = new WC_Econt_Shipping_Method;
			$econt_mysql = new Econt_mySQL;
			
			//get ordered products info
			$order_wp = $this->econt_order_products(); 
			//get loading details from sql
			$loading = $econt_mysql->getLoading($thepostid);




		//tracking

		//$loading_info = $this->model_sale_econt->getLoading($order_id);

		if ($loading) {
			$error = array();

			if ($loading['cd_send_sum'] && (strtotime($loading['cd_send_time']) > 0)) {
				$loading['trackings'] = $econt_mysql->getLoadingTrackings($loading['econt_loading_id']);

				$loading['next_parcels'] = $econt_mysql->getLoadingNextParcels($loading['loading_num']);

				foreach ($loading['next_parcels'] as $key => $next_parcel) {
					$loading['next_parcels'][$key]['trackings'] = $econt_mysql->getLoadingTrackings($next_parcel['econt_loading_id']);
				}
			} else {
				$data = array(
					'live' =>  $wc_econt->live,
					'username' => $wc_econt->username,
					'password' => $wc_econt->password,
					'type' => 'shipments',
					'xml'  => "<shipments full_tracking='ON'><num>" . $loading['loading_num'] . '</num></shipments>'
				);

				$results = $econt_mysql->serviceTool($data);

				$loading['trackings'] = array();
				$loading['next_parcels'] = array();

				if ($results) {
					if (isset($results->shipments->e->error)) {
						$this->error['warning'] = (string)$results->shipments->e->error;
					} elseif (isset($results->error)) {
						$this->error['warning'] = (string)$results->error->message;
					} elseif (isset($results->shipments->e)) {
						$loading['is_imported'] = $results->shipments->e->is_imported;
						$loading['storage'] = $results->shipments->e->storage;
						$loading['receiver_person'] = $results->shipments->e->receiver_person;
						$loading['receiver_person_phone'] = $results->shipments->e->receiver_person_phone;
						$loading['receiver_courier'] = $results->shipments->e->receiver_courier;
						$loading['receiver_courier_phone'] = $results->shipments->e->receiver_courier_phone;
						$loading['receiver_time'] = $results->shipments->e->receiver_time;
						$loading['cd_get_sum'] = $results->shipments->e->CD_get_sum;
						$loading['cd_get_time'] = $results->shipments->e->CD_get_time;
						$loading['cd_send_sum'] = $results->shipments->e->CD_send_sum;
						$loading['cd_send_time'] = $results->shipments->e->CD_send_time;
						$loading['total_sum'] = $results->shipments->e->total_sum;
						$loading['currency'] = $results->shipments->e->currency;
						$loading['sender_ammount_due'] = $results->shipments->e->sender_ammount_due;
						$loading['receiver_ammount_due'] = $results->shipments->e->receiver_ammount_due;
						$loading['other_ammount_due'] = $results->shipments->e->other_ammount_due;
						$loading['delivery_attempt_count'] = $results->shipments->e->delivery_attempt_count;
						$loading['blank_yes'] = $results->shipments->e->blank_yes;
						$loading['blank_no'] = $results->shipments->e->blank_no;

						if (isset($results->shipments->e->tracking)) {
							foreach ($results->shipments->e->tracking->row as $tracking) {
								$loading['trackings'][] = array(
									'time'       => $tracking->time,
									'is_receipt' => $tracking->is_receipt,
									'event'      => $tracking->event,
									'name'       => $tracking->name,
									'name_en'    => $tracking->name_en
								);
							}
						}

						if (isset($results->shipments->e->next_parcels)) {
							foreach ($results->shipments->e->next_parcels->e as $next_parcel) {
								$data_next_parcel = array(
									'live' =>  $wc_econt->live,
									'username' => $wc_econt->username,
									'password' => $wc_econt->password,
									'type' => 'shipments',
									'xml'  => "<shipments full_tracking='ON'><num>" . $next_parcel->num . '</num></shipments>'
								);

								$results_next_parcel = $econt_mysql->serviceTool($data_next_parcel);

								if ($results_next_parcel) {
									if (isset($results_next_parcel->shipments->e->error)) {
										$this->error['warning'] = (string)$results_next_parcel->shipments->e->error;
									} elseif (isset($results_next_parcel->error)) {
										$this->error['warning'] = (string)$results_next_parcel->error->message;
									} elseif (isset($results_next_parcel->shipments->e)) {
										$trackings_next_parcel = array();

										if (isset($results_next_parcel->shipments->e->tracking)) {
											foreach ($results_next_parcel->shipments->e->tracking->row as $tracking) {
												$trackings_next_parcel[] = array(
													'time'       => $tracking->time,
													'is_receipt' => $tracking->is_receipt,
													'event'      => $tracking->event,
													'name'       => $tracking->name,
													'name_en'    => $tracking->name_en
												);
											}
										}

										$loading['next_parcels'][] = array(
											'loading_num'            => $results_next_parcel->shipments->e->loading_num,
											'is_imported'            => $results_next_parcel->shipments->e->is_imported,
											'storage'                => $results_next_parcel->shipments->e->storage,
											'receiver_person'        => $results_next_parcel->shipments->e->receiver_person,
											'receiver_person_phone'  => $results_next_parcel->shipments->e->receiver_person_phone,
											'receiver_courier'       => $results_next_parcel->shipments->e->receiver_courier,
											'receiver_courier_phone' => $results_next_parcel->shipments->e->receiver_courier_phone,
											'receiver_time'          => $results_next_parcel->shipments->e->receiver_time,
											'cd_get_sum'             => $results_next_parcel->shipments->e->CD_get_sum,
											'cd_get_time'            => $results_next_parcel->shipments->e->CD_get_time,
											'cd_send_sum'            => $results_next_parcel->shipments->e->CD_send_sum,
											'cd_send_time'           => $results_next_parcel->shipments->e->CD_send_time,
											'total_sum'              => $results_next_parcel->shipments->e->total_sum,
											'currency'               => $results_next_parcel->shipments->e->currency,
											'sender_ammount_due'     => $results_next_parcel->shipments->e->sender_ammount_due,
											'receiver_ammount_due'   => $results_next_parcel->shipments->e->receiver_ammount_due,
											'other_ammount_due'      => $results_next_parcel->shipments->e->other_ammount_due,
											'delivery_attempt_count' => $results_next_parcel->shipments->e->delivery_attempt_count,
											'blank_yes'              => $results_next_parcel->shipments->e->blank_yes,
											'blank_no'               => $results_next_parcel->shipments->e->blank_no,
											'pdf_url'                => $next_parcel->pdf_url,
											'reason'                 => $next_parcel->reason,
											'trackings'              => $trackings_next_parcel
										);
									}
								} else {
									$error['warning'] = __('error_connect', 'woocommerce-econt');
								}
							}
						}

						if (!$error) {
							$econt_mysql->updateLoading($loading);
						}
					}
				} else {
					$error['warning'] = __('error_connect', 'woocommerce-econt');
				}
			}
/*
			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_yes'] = $this->language->get('text_yes');
			$this->data['text_no'] = $this->language->get('text_no');
			$this->data['text_view'] = $this->language->get('text_view');

			$this->data['entry_loading_num'] = $this->language->get('entry_loading_num');
			$this->data['entry_is_imported'] = $this->language->get('entry_is_imported');
			$this->data['entry_storage'] = $this->language->get('entry_storage');
			$this->data['entry_receiver_person'] = $this->language->get('entry_receiver_person');
			$this->data['entry_receiver_person_phone'] = $this->language->get('entry_receiver_person_phone');
			$this->data['entry_receiver_courier'] = $this->language->get('entry_receiver_courier');
			$this->data['entry_receiver_courier_phone'] = $this->language->get('entry_receiver_courier_phone');
			$this->data['entry_receiver_time'] = $this->language->get('entry_receiver_time');
			$this->data['entry_cd_get_sum'] = $this->language->get('entry_cd_get_sum');
			$this->data['entry_cd_get_time'] = $this->language->get('entry_cd_get_time');
			$this->data['entry_cd_send_sum'] = $this->language->get('entry_cd_send_sum');
			$this->data['entry_cd_send_time'] = $this->language->get('entry_cd_send_time');
			$this->data['entry_total_sum'] = $this->language->get('entry_total_sum');
			$this->data['entry_sender_ammount_due'] = $this->language->get('entry_sender_ammount_due');
			$this->data['entry_receiver_ammount_due'] = $this->language->get('entry_receiver_ammount_due');
			$this->data['entry_other_ammount_due'] = $this->language->get('entry_other_ammount_due');
			$this->data['entry_delivery_attempt_count'] = $this->language->get('entry_delivery_attempt_count');
			$this->data['entry_blank_yes'] = $this->language->get('entry_blank_yes');
			$this->data['entry_blank_no'] = $this->language->get('entry_blank_no');
			$this->data['entry_pdf_url'] = $this->language->get('entry_pdf_url');
			$this->data['entry_tracking'] = $this->language->get('entry_tracking');
			$this->data['entry_time'] = $this->language->get('entry_time');
			$this->data['entry_is_receipt'] = $this->language->get('entry_is_receipt');
			$this->data['entry_event'] = $this->language->get('entry_event');
			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_next_parcels'] = $this->language->get('entry_next_parcels');

			$this->data['button_courier'] = $this->language->get('button_courier');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
*/
			if (isset($error['warning'])) {
				$data['error_warning'] = $error['warning'];
			} else {
				$data['error_warning'] = '';
			}
/*
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text'      => __('Home', 'woocommerce-econt'),
				//'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);

			$data['breadcrumbs'][] = array(
				'text'      => __('order', 'woocommerce-econt'),
				//'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			);

			$data['breadcrumbs'][] = array(
				'text'      => __('heading title', 'woocommerce-econt'),
				//'href'      => $this->url->link('sale/econt', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . $url, 'SSL'),
				'separator' => ' :: '
			);
*/
			//$data['courier'] = 'http://ee.econt.com/?target=EeRequestOfCourier&eshop=1';
			//$data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$loading['receiver_time'] = (strtotime($loading['receiver_time']) > 0 ? date(__('d/m/Y', 'woocommerce-econt') . ' ' . __('hh:mm:ss', 'woocommerce-econt'), strtotime($loading['receiver_time'])) : '');
			$loading['cd_get_time'] = (strtotime($loading['cd_get_time']) > 0 ? date(__('d/m/Y', 'woocommerce-econt') . ' ' . __('hh:mm:ss', 'woocommerce-econt'), strtotime($loading['cd_get_time'])) : '');
			$loading['cd_send_time'] = (strtotime($loading['cd_send_time']) > 0 ? date(__('d/m/Y', 'woocommerce-econt') . ' ' . __('hh:mm:ss', 'woocommerce-econt'), strtotime($loading['cd_send_time'])) : '');

			foreach ($loading['trackings'] as $key => $tracking) {
				$loading['trackings'][$key] = array(
					'time'       => date(__('d/m/Y', 'woocommerce-econt') . ' ' . __('hh:mm:ss', 'woocommerce-econt'), strtotime($tracking['time'])),
					'is_receipt' => ((int)$tracking['is_receipt'] ? __('yes', 'woocommerce-econt') : __('no', 'woocommerce-econt')),
					'event'      => $this->language->get('text_' . $tracking['event']),
					'name'       => (get_locale() == 'bg_BG' ? $tracking['name'] : $tracking['name_en'])
				);
			}

			foreach ($loading['next_parcels'] as $key => $next_parcel) {
				$loading['next_parcels'][$key]['receiver_time'] = (strtotime($next_parcel['receiver_time']) > 0 ? date(__('d/m/Y', 'woocommerce-econt') . ' ' . __('hh:mm:ss', 'woocommerce-econt'), strtotime($next_parcel['receiver_time'])) : '');
				$loading['next_parcels'][$key]['cd_get_time'] = (strtotime($next_parcel['cd_get_time']) > 0 ? date(__('d/m/Y', 'woocommerce-econt') . ' ' . __('hh:mm:ss', 'woocommerce-econt'), strtotime($next_parcel['cd_get_time'])) : '');
				$loading['next_parcels'][$key]['cd_send_time'] = (strtotime($next_parcel['cd_send_time']) > 0 ? date(__('d/m/Y', 'woocommerce-econt') . ' ' . __('hh:mm:ss', 'woocommerce-econt'), strtotime($next_parcel['cd_send_time'])) : '');

				foreach ($next_parcel['trackings'] as $key2 => $tracking) {
					$loading['next_parcels'][$key]['trackings'][$key2] = array(
						'time'       => date(__('d/m/Y', 'woocommerce-econt') . ' ' . __('hh:mm:ss', 'woocommerce-econt'), strtotime($tracking['time'])),
						'is_receipt' => ((int)$tracking['is_receipt'] ? __('yes', 'woocommerce-econt') : __('no', 'woocommerce-econt')),
						'event'      => __($tracking['event'], 'woocommerce-econt'),
						'name'       => (get_locale() == 'bg_BG' ? $tracking['name'] : $tracking['name_en'])
					);
				}
			}
 }
		//tracking end





			//delivery days
            $delivery_days = $econt_mysql->delivery_days($wc_econt->username, $wc_econt->password, $wc_econt->live);

            //Priority time
            $priority_time_types = array(
				array('id' => 'BEFORE', 'name' => __('before', 'woocommerce-econt'), 'hours' => array(10, 11, 12, 13, 14, 15, 16, 17, 18)),
				array('id' => 'IN', 'name' => __('in', 'woocommerce-econt'), 'hours' => array(9, 10, 11, 12, 13, 14, 15, 16, 17, 18)),
				array('id' => 'AFTER', 'name' => __('after', 'woocommerce-econt'), 'hours' => array(9, 10, 11, 12, 13, 14, 15, 16, 17))
			);

            //client info
			$client_info = $econt_mysql->getClients($wc_econt->username, $wc_econt->password, $wc_econt->live);

			//instructions
			$instructions_give      = array();
            $instructions_take      = array();
            $instruction_return     = array();
            $instruction_services   = array();

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

            //default sender addresses
            $profile 		= $econt_mysql->getProfile($wc_econt->username, $wc_econt->password, $wc_econt->live);
			if(!array_key_exists('error', $profile)){
			$name 			= $profile['client_info']->mol->__toString();
			}

            $sender_addresses = array();
			foreach ($profile['addresses'] as $key => $value) {
			$sender_addresses[$value->city_post_code->__toString().';'.$value->city->__toString().';'.$value->quarter->__toString().';'.$value->street->__toString().';'.$value->street_num->__toString().';'.$value->other->__toString().';'.$value->city_id->__toString()] = __('p.c. ', 'woocommerce-econt').$value->city_post_code->__toString().__(', t./v. ', 'woocommerce-econt').$value->city->__toString().__(', q.: ', 'woocommerce-econt').$value->quarter->__toString().', '.$value->street->__toString().', №: '.$value->street_num->__toString().__(', other ', 'woocommerce-econt').$value->other->__toString().', '.$value->city_id->__toString();	
			}

			//receiver details
			if(get_post_meta( $thepostid, 'Econt_Door_Town', true )){
			
				$receiver_city 			= get_post_meta( $thepostid, 'Econt_Door_Town', true );
			
			}elseif(get_post_meta( $thepostid, 'Econt_Office_Town', true )){
			
				$receiver_city 			= get_post_meta( $thepostid, 'Econt_Office_Town', true );
			
			}elseif(get_post_meta( $thepostid, 'Econt_Machine_Town', true )){

				$receiver_city 			= get_post_meta( $thepostid, 'Econt_Machine_Town', true );
			}
			
			if(get_post_meta( $thepostid, 'Econt_Door_Postcode', true )){
				
				$receiver_post_code 	= get_post_meta( $thepostid, 'Econt_Door_Postcode', true );
			
			}elseif(get_post_meta( $thepostid, 'Econt_Office_Postcode', true )){

				$receiver_post_code 	= get_post_meta( $thepostid, 'Econt_Office_Postcode', true );
			
			}elseif(get_post_meta( $thepostid, 'Econt_Machine_Postcode', true )){

				$receiver_post_code 	= get_post_meta( $thepostid, 'Econt_Machine_Postcode', true );
			}

			if(get_post_meta( $thepostid, 'Econt_Office', true )){

				$receiver_office_code 		= get_post_meta( $thepostid, 'Econt_Office', true );
			
			}elseif(get_post_meta( $thepostid, 'Econt_Machine', true )){

				$receiver_office_code 		= get_post_meta( $thepostid, 'Econt_Machine', true );
			}

			if( get_post_meta( $thepostid, '_billing_company', true) ) { 
			
				$receiver_name 			= get_post_meta( $thepostid, '_billing_company', true);
			
			}else{
			
				$receiver_name 			=  get_post_meta( $thepostid, '_billing_first_name', true).' '.get_post_meta( $thepostid, '_billing_last_name',true);

			}
			
			$receiver_name_person 		= get_post_meta( $thepostid, '_billing_first_name', true).' '.get_post_meta( $thepostid, '_billing_last_name',true);
			$receiver_email 			= get_post_meta( $thepostid, '_billing_email', true);
			$receiver_street 			= get_post_meta( $thepostid, 'Econt_Door_Street', true );
			$receiver_quarter 			= get_post_meta( $thepostid, 'Econt_Door_Quarter', true );
			$receiver_street_num 		= get_post_meta( $thepostid, 'Econt_Door_street_num', true );
			$receiver_street_bl 		= get_post_meta( $thepostid, 'Econt_Door_building_num', true );
			$receiver_street_vh 		= get_post_meta( $thepostid, 'Econt_Door_Entrance_num', true );
			$receiver_street_et			= get_post_meta( $thepostid, 'Econt_Door_Floor_num', true );
			$receiver_street_ap 		= get_post_meta( $thepostid, 'Econt_Door_Apartment_num', true );
			$receiver_street_other 		= get_post_meta( $thepostid, 'Econt_Door_Other', true );
			$receiver_phone_num			= get_post_meta( $thepostid, '_billing_phone',true);
			$receiver_shipping_to		= get_post_meta( $thepostid, 'Econt_Shipping_To',true);


			$description				= implode(', ', $order_wp['product_name']);
			$currency 					= get_woocommerce_currency();
			$currency_symbol 			= get_woocommerce_currency_symbol();	

			//$sender_payment_method		= $wc_econt->client_credit_num; 
			$sender_payment_method		= $wc_econt->client_payment_type; 
			$cd_agreement_num			= $wc_econt->client_cd_num;

			$customer_shipping_cost		= get_post_meta( $thepostid, 'Econt_Customer_Shipping_Cost', true );
			$total_shipping_cost		= get_post_meta( $thepostid, 'Econt_Total_Shipping_Cost', true );

			//print_r($order_wp['products']);
			include_once( ECONT_PLUGIN_DIR.'/admin/view/html-order-view.php' );

		}


		public function econt_order_products($order_id = null) {


			global $post, $woocommerce, $the_order;
			if ( empty( $the_order ) || $the_order->id != $post->ID ) {
				$the_order = new WC_Order( $post->ID );
			}
			if (!empty($order_id)){
			$the_order = new WC_Order( $order_id );	
			}

			$result = array();

				$weight = 0;
				$price 	= 0;
				$i 		= 1;
				if ( sizeof( $the_order->get_items() ) > 0 ) {
					foreach( $the_order->get_items() as $item ) {
						if ( $item['product_id'] > 0 ) {
							$_product = $the_order->get_product_from_item( $item );

							if ( ! $_product->is_virtual() ) {
								if($_product->get_weight() <= 0){
								$result['no_weight'][$i]['name'] = $item['name'];
								$result['no_weight'][$i]['product_id'] = $item['product_id'];
								
								$i++;
								}
								//print_r($item);
								$result['product_name'][$i] = $item['name'];

								$result['products'][$i]['product_id'] = $item['product_id'];
								$result['products'][$i]['name'] = $item['name'];
								$result['products'][$i]['qty'] = $item['qty'];
								$result['products'][$i]['weight'] = $_product->get_weight() * $item['qty'];
								$result['products'][$i]['price'] = $_product->get_price() * $item['qty'];

								$weight += $_product->get_weight() * $item['qty'];
								$price	+= $_product->get_price() * $item['qty'];
							$i++;
							}
						}
					
					}
				}

			//	if ( $weight > 0 ){
					$result['weight'] = $weight;
			//	}else{ 
			//		$result['weight'] = 1;
			//	}
				$result['price'] = $price;
				$result['count'] = $i;

				return $result;
			//}
		}


		function econt_add_orders_overview_columns($columns){
    		$new_columns = (is_array($columns)) ? $columns : array();
    		unset( $new_columns['order_actions'] );

    		//edit this for you column(s)
    		//all of your columns will be added before the actions column
    		//$new_columns['MY_COLUMN_ID_1'] = 'MY_COLUMN_1_TITLE';
    		//$new_columns['MY_COLUMN_ID_2'] = 'MY_COLUMN_2_TITLE';
    		$new_columns['econt_loading'] = __('Econt Loading', 'woocommerce-econt');
    		//stop editing

    		$new_columns['order_actions'] = $columns['order_actions'];
    		return $new_columns;
		}

		function econt_orders_overview_columns_values($column){
    		global $post;
    		$data = get_post_meta( $post->ID );
    		$econt_mysql = new Econt_mySQL;
    		//start editing, I was saving my fields for the orders as custom post meta
    		//if you did the same, follow this code
    		//if ( $column == 'MY_COLUMN_ID_1' ) {    
        	//	echo (isset($data['MY_COLUMN_1_POST_META_ID']) ? $data['MY_COLUMN_1_POST_META_ID'] : '');
    		//}
    		//if ( $column == 'MY_COLUMN_ID_2' ) {    
        	//	echo (isset($data['MY_COLUMN_2_POST_META_ID']) ? $data['MY_COLUMN_2_POST_META_ID'] : '');
    		//}

    		if ($column == 'econt_loading') {
    			$loading = $econt_mysql->getLoading($post->ID);
    			//echo $post->ID;
    			if($loading['loading_num']){
    			echo '<a href="' . $loading['pdf_url'] . '" target="_blank">' . $loading['loading_num'] . '</a>';
    			}else{
    			echo '<a href="' . get_edit_post_link( $post->ID ) . '" target="_blank">' . __('prepare loading', 'woocommerce-econt') . '</a>';
    			}
    		}	
    		//stop editing
		}

		function econt_orders_overview_columns_sort($columns) {
    		$custom = array(
        	//start editing

        		'MY_COLUMN_ID_1'    => 'MY_COLUMN_1_POST_META_ID',
        		'MY_COLUMN_ID_2'    => 'MY_COLUMN_2_POST_META_ID'

       			//stop editing
    		);
    		return wp_parse_args( $custom, $columns );
		}


		function econt_scripts() {
			
			 wp_enqueue_style( 'style-jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css'); //tova trqbva da go prerabotq i da go mahna to e za fona na rezultatite ot autocomplete

			 wp_enqueue_script('jquery');
       		 wp_enqueue_script('jquery-ui-core');
       		 wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');

        	 //wp_enqueue_script( 'econt_js', plugins_url( '/js/econt.js', __FILE__ ), array( 'jquery' ), '1.0', true );
        	 //wp_enqueue_style( 'econt_style', plugins_url( '/css/econt.css', __FILE__ ));
        	 wp_enqueue_script( 'econt_js', ECONT_PLUGIN_URL . '/inc/js/econt.js', array( 'jquery' ), '1.0', true );
        	 //colorbox for office locator map
        	 wp_enqueue_script( 'colorbox', ECONT_PLUGIN_URL . '/inc/js/colorbox/jquery.colorbox-min.js', array( 'jquery' ), '1.6.1', true );
        	 wp_enqueue_style( 'colorbox_style1', ECONT_PLUGIN_URL . '/inc/css/colorbox.css');
        	 wp_enqueue_style( 'econt_style', ECONT_PLUGIN_URL . '/inc/css/econt.css');


		}


	}

}

new Econt_Admin_Order();

?>
<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if(!class_exists('Econt_Ajax')) {

	class Econt_Ajax {

public function __construct(){
 // Test #1
 add_action( 'wp_ajax_nopriv_handle_ajax', array( &$this, 'handle_ajax' ) );

 // Test #2 
 add_action( 'wp_ajax_handle_ajax', array(&$this, 'handle_ajax') ); //with and without '&' before $this

 //add the build in wordpress ajax url so we can use it in out js files var ajaxurl
 add_action('wp_head',array(&$this, 'pluginname_ajaxurl') );

}


		public function handle_ajax(){


	global $wpdb;
	$result = array();
	$res = array();

 //   print_r($_REQUEST);
 //   $file = '/home/martin/dev/woocommerce/econt/wordpress/wp-content/calc.txt';
	//	file_put_contents($file, print_r($_REQUEST));
$econt_mysql = new Econt_mySQL;


if(isset($_REQUEST['city'])){

$city = $_REQUEST['city'];

$results = $econt_mysql->getCityByName($city);


foreach ($results as $row) {

	$res['label'] 		= $row['type'].' '.$row['name'].'[ п.к.:'.$row['post_code'].']';
	$res['value'] 		= $row['name'];
	$res['id'] 			= $row['city_id'];
	$res['post_code'] 	= $row['post_code'];

	$result[] = $res;

}
}

if(isset($_REQUEST['office_city_id'])){

$city_id = $_REQUEST['office_city_id'];

$results = $econt_mysql->getOfficesByCityId($city_id,'',$_REQUEST['delivery_type']);

foreach ($results as $row) {

	$res['value'] = $row['name'].' ['.$row['address'].']';
	$res['id'] = $row['office_code'];

	$result[] = $res;
	
}

}


if(isset($_REQUEST['machine_city_id'])){

$city_id = $_REQUEST['machine_city_id'];
$is_machine = 1;
$results = $econt_mysql->getOfficesByCityId($city_id, $is_machine);

foreach ($results as $row) {

	$res['value'] = $row['name'].' ['.$row['address'].']';
	$res['id'] = $row['office_code'];

	$result[] = $res;
	
}

}


if(isset($_REQUEST['door_city_id']) && $_REQUEST['type'] == 'street'){

$city_id = $_REQUEST['door_city_id'];
$street_name = $_REQUEST['door_street_name'];

$results = $econt_mysql->getStreetsByCityId($city_id, $street_name);

foreach ($results as $row) {

	$res['value'] = $row['name'];
	$res['id'] = $row['street_id'];

	$result[] = $res;
	
}

}

if(isset($_REQUEST['door_city_id']) && $_REQUEST['type'] == 'quarter'){

$city_id = $_REQUEST['door_city_id'];
$quarter_name = $_REQUEST['door_quarter_name'];

$results = $econt_mysql->getQuartersByCityId($city_id, $quarter_name);

foreach ($results as $row) {

	$res['value'] = $row['name'];
	$res['id'] = $row['quarter_id'];

	$result[] = $res;
	
}

}


if(isset($_REQUEST['refresh_data']) && isset($_REQUEST['username']) && isset($_REQUEST['password'])){

$username 	= $_REQUEST['username'];
$password 	= $_REQUEST['password'];
$live		= $_REQUEST['live'];
$results = $econt_mysql->refreshData($username, $password, $live);
if($results['error']){
$result[] =  $results['error'];
}else{
$result[] = __('refresh data success', 'woocommerce-econt');	
}

}

//order loading
if(isset($_REQUEST['action2'])){
//	print_r($_REQUEST);
	//exit();
global $woocommerce;
$woocommerce->shipping;
$wc_econt = new WC_Econt_Shipping_Method;
$econt_order = new Econt_Admin_Order;
if( $_REQUEST['action2'] == 'only_calculate_loading' || $_REQUEST['action2'] == 'create_loading' ){
//if(!$_REQUEST['order_id']){ };
(isset($_REQUEST['order_id']) ?: $_REQUEST['order_id'] = -1); //if there is no order id yet set order id to -1
$_REQUEST['weight'] = $this->getIfSet($_REQUEST['weight'], $woocommerce->cart->cart_contents_weight);
if($_REQUEST['weight'] == 0){

$result = array(
					'order_id'    				=> $_REQUEST['order_id'],
					'econt_shipping_expenses' 	=>  array('total_shipping_cost' => '',
											  			  'customer_shipping_cost' => __('calculating', 'woocommerce-econt'),
										      			  'currency_symbol'	=> '',
										),
				);	

}else{

$data = array();


$data['client']['username'] 						= $_REQUEST['username'] 				= $this->getIfSet($_REQUEST['username'], $wc_econt->username);
$data['client']['password'] 						= $_REQUEST['password'] 				= $this->getIfSet($_REQUEST['password'], $wc_econt->password);
$data['live'] 										= $_REQUEST['live'] 					= $this->getIfSet($_REQUEST['live'], $wc_econt->live);

$data['system']['request_type'] 					= 'shipping';
$data['system']['response_type'] 					= 'XML';

$data['loadings']['row']['sender']['name'] 			= $_REQUEST['sender_name'] 				= $this->getIfSet($_REQUEST['sender_name'], $wc_econt->company);
$data['loadings']['row']['sender']['name_person'] 	= $_REQUEST['sender_name_person'] 		= $this->getIfSet($_REQUEST['sender_name_person'], $wc_econt->name);
$data['loadings']['row']['sender']['phone_num'] 	= $_REQUEST['sender_phone_num'] 		= $this->getIfSet($_REQUEST['sender_phone_num'], $wc_econt->phone);

$_REQUEST['sender_door_or_office'] 	= $this->getIfSet($_REQUEST['sender_door_or_office'], $wc_econt->send_from);

if($_REQUEST['sender_door_or_office'] == 'DOOR' || $_REQUEST['sender_door_or_office'] == 'DOOR2' ){
if($_REQUEST['sender_door_or_office'] == 'DOOR2'){
$address 							= explode(';', $_REQUEST['sender_door']);
}else{
$address 							= explode(';', $wc_econt->address);
}
$data['loadings']['row']['sender']['city'] 			= $_REQUEST['sender_city'] 				= $this->getIfSet($_REQUEST['sender_city'], $address[1]);
$data['loadings']['row']['sender']['post_code'] 	= $_REQUEST['sender_post_code'] 		= $this->getIfSet($_REQUEST['sender_post_code'], $address[0]);
$data['loadings']['row']['sender']['street'] 		= $_REQUEST['sender_street'] 			= $this->getIfSet($_REQUEST['sender_street'], $address[3]);
$data['loadings']['row']['sender']['quarter'] 		= $_REQUEST['sender_quarter'] 			= $this->getIfSet($_REQUEST['sender_quarter'], $address[2]);
$data['loadings']['row']['sender']['street_num'] 	= $_REQUEST['sender_street_num'] 		= $this->getIfSet($_REQUEST['sender_street_num'], $address[4]);
$data['loadings']['row']['sender']['street_bl'] 	= $_REQUEST['sender_street_bl'] 		= $this->getIfSet($_REQUEST['sender_street_bl']);
$data['loadings']['row']['sender']['street_vh'] 	= $_REQUEST['sender_street_vh'] 		= $this->getIfSet($_REQUEST['sender_street_vh']);
$data['loadings']['row']['sender']['street_et'] 	= $_REQUEST['sender_street_et'] 		= $this->getIfSet($_REQUEST['sender_street_et']);
$data['loadings']['row']['sender']['street_ap'] 	= $_REQUEST['sender_street_ap'] 		= $this->getIfSet($_REQUEST['sender_street_ap']);
$data['loadings']['row']['sender']['street_other'] 	= $_REQUEST['sender_street_other'] 		= $this->getIfSet($_REQUEST['sender_street_other'], $address[5]);

}elseif($_REQUEST['sender_door_or_office'] == 'OFFICE'){

$data['loadings']['row']['sender']['city'] 			= $_REQUEST['sender_city'] 				= $this->getIfSet($_REQUEST['sender_city'], $wc_econt->office_town);
$data['loadings']['row']['sender']['post_code'] 	= $_REQUEST['sender_post_code'] 		= $this->getIfSet($_REQUEST['sender_post_code'], $wc_econt->office_postcode);
$data['loadings']['row']['sender']['office_code'] 	= $_REQUEST['sender_office_code'] 		= $this->getIfSet($_REQUEST['sender_office_code'], $wc_econt->office_code);

}elseif($_REQUEST['sender_door_or_office'] == 'MACHINE'){

$data['loadings']['row']['sender']['city'] 			= $_REQUEST['sender_city'] 				= $this->getIfSet($_REQUEST['sender_city'], $wc_econt->machine_town);
$data['loadings']['row']['sender']['post_code'] 	= $_REQUEST['sender_post_code'] 		= $this->getIfSet($_REQUEST['sender_post_code'], $wc_econt->machine_postcode);
$data['loadings']['row']['sender']['office_code'] 	= $_REQUEST['sender_office_code'] 		= $this->getIfSet($_REQUEST['sender_office_code'], $wc_econt->machine_code);

}


$data['loadings']['row']['receiver']['name'] 				= $_REQUEST['receiver_name'];
$data['loadings']['row']['receiver']['name_person'] 		= $_REQUEST['receiver_name_person'];
$data['loadings']['row']['receiver']['phone_num'] 			= $_REQUEST['receiver_phone_num'];
$data['loadings']['row']['receiver']['city'] 				= $_REQUEST['receiver_city'];
$data['loadings']['row']['receiver']['post_code'] 			= $_REQUEST['receiver_post_code'];

if($_REQUEST['receiver_shipping_to'] == 'DOOR'){


$data['loadings']['row']['receiver']['office_code'] 		= '';
$data['loadings']['row']['receiver']['street'] 				= $_REQUEST['receiver_street'];
$data['loadings']['row']['receiver']['quarter'] 			= $_REQUEST['receiver_quarter'];
$data['loadings']['row']['receiver']['street_num'] 			= $_REQUEST['receiver_street_num'];
$data['loadings']['row']['receiver']['street_bl'] 			= $_REQUEST['receiver_street_bl'];
$data['loadings']['row']['receiver']['street_vh'] 			= $_REQUEST['receiver_street_vh'];
$data['loadings']['row']['receiver']['street_et'] 			= $_REQUEST['receiver_street_et'];
$data['loadings']['row']['receiver']['street_ap'] 			= $_REQUEST['receiver_street_ap'];
$data['loadings']['row']['receiver']['street_other'] 		= $_REQUEST['receiver_street_other'];

}elseif($_REQUEST['receiver_shipping_to'] == 'OFFICE' || $_REQUEST['receiver_shipping_to'] == 'MACHINE'){

$data['loadings']['row']['receiver']['office_code'] 		= $_REQUEST['receiver_office_code'];
$data['loadings']['row']['receiver']['street'] 				= '';
$data['loadings']['row']['receiver']['quarter'] 			= '';
$data['loadings']['row']['receiver']['street_num'] 			= '';
$data['loadings']['row']['receiver']['street_bl'] 			= '';
$data['loadings']['row']['receiver']['street_vh'] 			= '';
$data['loadings']['row']['receiver']['street_et'] 			= '';
$data['loadings']['row']['receiver']['street_ap'] 			= '';
$data['loadings']['row']['receiver']['street_other'] 		= '';

}

$_REQUEST['payment_side'] 			= $this->getIfSet($_REQUEST['payment_side'], $wc_econt->payment_side);
$_REQUEST['sender_payment_method'] 	= $this->getIfSet($_REQUEST['sender_payment_method'], $wc_econt->client_payment_type);
//$_REQUEST[''] 					= $this->getIfSet($_REQUEST[''], $wc_econt->client_voucher);
//$_REQUEST[''] 					= $this->getIfSet($_REQUEST[''], $wc_econt->client_bonus_points);
$_REQUEST['order_cd'] 				= $this->getIfSet($_REQUEST['order_cd'], $wc_econt->cd);
$_REQUEST['cd_agreement_num'] 		= $this->getIfSet($_REQUEST['cd_agreement_num'], $wc_econt->client_cd_num);
$_REQUEST['free_shipping_sum'] 		= $this->getIfSet($_REQUEST['free_shipping_sum'], $wc_econt->free_shipping_sum);
$_REQUEST['free_shipping_weight'] 	= $this->getIfSet($_REQUEST['free_shipping_weight'], $wc_econt->free_shipping_weight);
$_REQUEST['free_shipping_count'] 	= $this->getIfSet($_REQUEST['free_shipping_count'], $wc_econt->free_shipping_count);
$_REQUEST['order_oc'] 				= $this->getIfSet($_REQUEST['order_oc'], $wc_econt->oc);
$_REQUEST['partial_delivery'] 		= $this->getIfSet($_REQUEST['partial_delivery'], $wc_econt->partial_delivery);
$_REQUEST['city_courier'] 			= $this->getIfSet($_REQUEST['city_courier'], $wc_econt->city_courier);
$_REQUEST['dc'] 					= $this->getIfSet($_REQUEST['dc'], $wc_econt->dc);
$_REQUEST['dc_cp'] 					= $this->getIfSet($_REQUEST['dc_cp'], $wc_econt->dc_cp);
$_REQUEST['sms'] 					= $this->getIfSet($_REQUEST['sms'], $wc_econt->sms);
$_REQUEST['invoice'] 				= $this->getIfSet($_REQUEST['invoice'], $wc_econt->invoice);
$_REQUEST['order_pay_after'] 		= $this->getIfSet($_REQUEST['order_pay_after'], $wc_econt->pay_after);
$_REQUEST['instruction_returns'] 	= $this->getIfSet($_REQUEST['instruction_returns'], $wc_econt->instruction_returns);
$_REQUEST['inventory'] 				= $this->getIfSet($_REQUEST['inventory'], $wc_econt->inventory);
$_REQUEST['instructions_take'] 		= $this->getIfSet($_REQUEST['instructions_take'], $wc_econt->instructions_take);
$_REQUEST['instructions_give'] 		= $this->getIfSet($_REQUEST['instructions_give'], $wc_econt->instructions_give);
$_REQUEST['instructions_return'] 	= $this->getIfSet($_REQUEST['instructions_return'], $wc_econt->instructions_return);
$_REQUEST['instructions_services'] 	= $this->getIfSet($_REQUEST['instructions_services'], $wc_econt->instructions_services);
//$_REQUEST['shipping_payment1'] 		= $this->getIfSet($_REQUEST['shipping_payment1'], $wc_econt->shipping_payment1);
//$_REQUEST['shipping_payment2'] 		= $this->getIfSet($_REQUEST['shipping_payment2'], $wc_econt->shipping_payment2);
$_REQUEST['shipping_payments'] 		= $this->getIfSet($_REQUEST['shipping_payments'], $wc_econt->shipping_payments);

$_REQUEST['receiver_name'] 			= $this->getIfSet($_REQUEST['receiver_name']);
$_REQUEST['receiver_name_person'] 	= $this->getIfSet($_REQUEST['receiver_name_person']);
$_REQUEST['description'] 			= $this->getIfSet($_REQUEST['description']);
$_REQUEST['delivery_days'] 			= $this->getIfSet($_REQUEST['delivery_days'], $wc_econt->delivery_days);
$_REQUEST['delivery_day_id']		= $this->getIfSet($_REQUEST['delivery_day_id']);
$_REQUEST['priority_time'] 			= $this->getIfSet($_REQUEST['priority_time']);

$_REQUEST['currency'] 				= get_woocommerce_currency();
$_REQUEST['currency_symbol'] 		= get_woocommerce_currency_symbol();

//ako se izchislqva poruchka
if((int)$_REQUEST['order_id'] > 0){
$wpc = $econt_order->econt_order_products($_REQUEST['order_id']);
$_REQUEST['order_cd_amount'] 		= $this->getIfSet($_REQUEST['order_cd_amount'], $wpc['price']);
$_REQUEST['weight'] 				= $this->getIfSet($_REQUEST['weight'], $wpc['weight']);
$_REQUEST['count'] 					= $this->getIfSet($_REQUEST['count'], $wpc['count']);
}
//ako se izchislqva koshnica
if($_REQUEST['order_id'] == -1){
	if($_REQUEST['payment_method_cod'] == 0){
	$_REQUEST['order_cd'] = 0;
	}
	$_REQUEST['order_cd_amount'] 		= $woocommerce->cart->total;	
	$_REQUEST['weight'] 				= $woocommerce->cart->cart_contents_weight;
	$_REQUEST['count'] 					= $woocommerce->cart->cart_contents_count;

//echo 'order_cd: '.$_REQUEST['order_cd'] .' payment_cod: ' .$_REQUEST['payment_method_cod'];
}
$_REQUEST['order_oc_amount'] 		= $this->getIfSet($_REQUEST['order_oc_amount'], $_REQUEST['order_cd_amount']);



$cd_type 	= 'GET';


if ((int)$_REQUEST['sms'] == 1) {
$sms_no = $_REQUEST['receiver_phone_num'];
} else {
$sms_no = '';
}

$data['loadings']['row']['receiver']['sms_no'] = $sms_no;


if((float)$_REQUEST['weight'] <= 100){
$data['loadings']['row']['shipment']['shipment_type'] 		= 'PACK';
}else{
$data['loadings']['row']['shipment']['shipment_type'] 		= 'CARGO';
$data['loadings']['row']['shipment']['cargo_code']			= 81;	
}

$tariff_sub_code = preg_replace('/\d/','',$_REQUEST['sender_door_or_office']).'_'.$_REQUEST['receiver_shipping_to'];
$tariff_sub_code = str_replace('MACHINE', 'OFFICE', $tariff_sub_code);

$tariff_code = 0;

if (!empty($_REQUEST['econt_city_courier']) && $_REQUEST['receiver_shipping_to'] == 'DOOR') {
$tariff_code = 1;
} elseif ($tariff_sub_code == 'OFFICE_OFFICE' || $tariff_sub_code == 'MACHINE_MACHINE' || $tariff_sub_code == 'MACHINE_OFFICE' || $tariff_sub_code == 'OFFICE_MACHINE') {
$tariff_code = 2;
} elseif ($tariff_sub_code == 'OFFICE_DOOR' || $tariff_sub_code == 'DOOR_OFFICE' || $tariff_sub_code == 'MACHINE_DOOR' || $tariff_sub_code == 'DOOR_MACHINE' ) {
$tariff_code = 3;
} elseif ($tariff_sub_code == 'DOOR_DOOR') {
$tariff_code = 4;
}

$data['loadings']['row']['shipment']['description'] 		= $_REQUEST['description'];
$data['loadings']['row']['shipment']['pack_count'] 			= $_REQUEST['pack_count'];
$data['loadings']['row']['shipment']['weight'] 				= $_REQUEST['weight'];

if($_REQUEST['sender_door_or_office'] == 'MACHINE' || $_REQUEST['receiver_shipping_to'] == 'MACHINE'){
if((float)$_REQUEST['weight'] <= 5){
$data['loadings']['row']['shipment']['aps_box_size'] 		= 'Small';
}elseif((float)$_REQUEST['weight'] > 5 && $_REQUEST['weight'] <= 10){
$data['loadings']['row']['shipment']['aps_box_size'] 		= 'Medium';
}elseif((float)$_REQUEST['weight'] > 10 && $_REQUEST['weight'] <= 50){
$data['loadings']['row']['shipment']['aps_box_size'] 		= 'Large';
}
}

$data['loadings']['row']['shipment']['tariff_code'] 		= $tariff_code;
$data['loadings']['row']['shipment']['tariff_sub_code'] 	= $tariff_sub_code;

if($_REQUEST['order_pay_after'] == 'accept' && $_REQUEST['receiver_shipping_to'] != 'MACHINE'){ 
$data['loadings']['row']['shipment']['pay_after_accept'] 	= 1;
$data['loadings']['row']['shipment']['pay_after_test'] 		= 0;
}elseif($_REQUEST['order_pay_after'] == 'test' && $_REQUEST['receiver_shipping_to'] != 'MACHINE'){
$data['loadings']['row']['shipment']['pay_after_accept'] 	= 0;
$data['loadings']['row']['shipment']['pay_after_test'] 		= 1;
}elseif($_REQUEST['order_pay_after'] == 0 || $_REQUEST['receiver_shipping_to'] == 'MACHINE'){
$data['loadings']['row']['shipment']['pay_after_accept'] 	= 0;
$data['loadings']['row']['shipment']['pay_after_test'] 		= 0;
}

$data['loadings']['row']['shipment']['instruction_returns'] = $_REQUEST['instruction_returns'];
$data['loadings']['row']['shipment']['invoice_before_pay_CD'] = $_REQUEST['invoice'];
//da go dovursha v checkout
if($_REQUEST['delivery_days'] == 1 && isset($_REQUEST['delivery_day_id'])) {
$delivery_day = $_REQUEST['delivery_day_id'];
}else{
$delivery_day = '';
}

$data['loadings']['row']['shipment']['delivery_day'] = $delivery_day;
//


//

if($_REQUEST['priority_time'] == 1 && $_REQUEST['receiver_shipping_to'] == 'DOOR') {
	$priority_time_type = $_REQUEST['priority_time_type'];
	$priority_time_value = $_REQUEST['priority_time_hour'];
}else{
	$priority_time_type = '';
	$priority_time_value = '';
}

$data['loadings']['row']['services']['p'] = array('type' => $priority_time_type, 'value' => $priority_time_value);

$city_courier_e1 = '';
$city_courier_e2 = '';
$city_courier_e3 = '';

if((int)$_REQUEST['city_courier'] == 1 && $_REQUEST['receiver_shipping_to'] == 'DOOR' && ($_REQUEST['sender_door_or_office'] == 'DOOR' || $_REQUEST['sender_door_or_office'] == 'DOOR2')) {
	if($_REQUEST['econt_city_courier'] == 'e1') {
		$city_courier_e1 = 'ON';
	}elseif($_REQUEST['econt_city_courier'] == 'e2') {
		$city_courier_e2 = 'ON';
	}elseif($_REQUEST['econt_city_courier'] == 'e3') {
		$city_courier_e3 = 'ON';
	}
}

$data['loadings']['row']['services']['e1'] = $city_courier_e1;
$data['loadings']['row']['services']['e2'] = $city_courier_e2;
$data['loadings']['row']['services']['e3'] = $city_courier_e3;
//


//

if((int)$_REQUEST['dc'] == 1) {
			$dc = 'ON';
		} else {
			$dc = '';
		}

		$data['loadings']['row']['services']['dc'] = $dc;

		if ((int)$_REQUEST['dc_cp'] == 1) {
			$dc_cp = 'ON';
		} else {
			$dc_cp = '';
		}

		$data['loadings']['row']['services']['dc_cp'] = $dc_cp;

		if ((int)$_REQUEST['count'] > 1 && (int)$_REQUEST['partial_delivery'] == 1) {
			$data['loadings']['row']['packing_list']['partial_delivery'] = $_REQUEST['partial_delivery'];
		}

		if ($_REQUEST['inventory'] != '0') {
			$data['loadings']['row']['packing_list']['type'] = $_REQUEST['inventory'];

			if ($_REQUEST['inventory'] == 'DIGITAL' && $_REQUEST['products']) { //trqbva da suzdan $_REQUEST['products'] v order class
				foreach ($_REQUEST['products'] as $product) {
					$data['loadings']['row']['packing_list']['row'][]['e'] = array(
						'inventory_num' => $product['product_id'],
						'description'   => $product['name'],
						'weight'        => $product['weight'],
						'price'         => $product['price']
					);
				}
			}
		}
//		
//instructions
	if($_REQUEST['receiver_shipping_to'] != 'MACHINE'){

		if ($_REQUEST['instructions_take']) {
					$data['loadings']['row']['instructions'][]['e'] = array(
						'type'     => 'take',
						'template' => $_REQUEST['instructions_take']
					);
		}
		if ($_REQUEST['instructions_give']) {
					$data['loadings']['row']['instructions'][]['e'] = array(
						'type'     => 'give',
						'template' => $_REQUEST['instructions_give']
					);
		}
		if ($_REQUEST['instructions_return']) {
					$data['loadings']['row']['instructions'][]['e'] = array(
						'type'     => 'return',
						'template' => $_REQUEST['instructions_return']
					);
		}
		if ($_REQUEST['instructions_services']) {
					$data['loadings']['row']['instructions'][]['e'] = array(
						'type'     => 'services',
						'template' => $_REQUEST['instructions_services']
					);
		}
	}
//




if((int)$_REQUEST['order_cd'] == 1){

$data['loadings']['row']['services']['cd'] 					= array('type' => $cd_type, 'value' => $_REQUEST['order_cd_amount']);
$data['loadings']['row']['services']['cd_currency'] 		= $_REQUEST['currency'];
$data['loadings']['row']['services']['cd_agreement_num'] 	= $_REQUEST['cd_agreement_num'];

}

//if($_REQUEST['order_oc'] == '1' && $sender_door_or_office != 'MACHINE' && $receiver_shipping_to != 'MACHINE' ){
if( ($_REQUEST['order_oc'] == 1 && $_REQUEST['sender_door_or_office'] != 'MACHINE' && $_REQUEST['receiver_shipping_to'] != 'MACHINE') || ($_REQUEST['order_oc'] > 1 && $_REQUEST['order_cd_amount'] > $_REQUEST['order_oc'] && $_REQUEST['sender_door_or_office'] != 'MACHINE' && $_REQUEST['receiver_shipping_to'] != 'MACHINE')){
$data['loadings']['row']['services']['oc'] 					= $_REQUEST['order_oc_amount']; //$oc_amount;
$data['loadings']['row']['services']['oc_currency'] 		= $_REQUEST['currency'];

}

$data['loadings']['row']['payment']['side'] 				= $_REQUEST['payment_side'];

/*
if( $_REQUEST['sender_payment_method'] == 'CASH' || $_REQUEST['payment_side'] == 'RECEIVER'){
	
	$payment_method = 'CASH';

}elseif($_REQUEST['sender_payment_method'] == 'BONUS' || $_REQUEST['sender_payment_method'] == 'VOUCHER'){

	$payment_method = $_REQUEST['sender_payment_method'];

}else{

	$payment_method = 'CASH';

}

$data['loadings']['row']['payment']['method'] 				= $payment_method;
*/
if($_REQUEST['sender_payment_method'] == 'CASH' || $_REQUEST['sender_payment_method'] == 'BONUS' || $_REQUEST['sender_payment_method'] == 'VOUCHER' ) {
$data['loadings']['row']['payment']['method'] 				= $_REQUEST['sender_payment_method'];
}else{
$data['loadings']['row']['payment']['method'] 				= 'CREDIT';
$data['loadings']['row']['payment']['key_word'] 			= $_REQUEST['sender_payment_method'];	
}

//
$receiver_share_sum = '';
$receiver_share_sum_door = '';
$receiver_share_sum_office = '';
$free_shipping = '';
		if ((float)$_REQUEST['free_shipping_sum'] && (float)($_REQUEST['order_cd_amount'] >= (float)$_REQUEST['free_shipping_sum']) || (int)$_REQUEST['free_shipping_count'] && ($_REQUEST['count'] >=  $_REQUEST['free_shipping_count']) || (float)$_REQUEST['free_shipping_weight'] && ($_REQUEST['weight'] >= $_REQUEST['free_shipping_weight'])) {
			
			$data['loadings']['row']['payment']['side'] = 'SENDER';
			if($_REQUEST['action2'] == 'only_calculate_loading'){ 
			$free_shipping = 1;
			}
/*
		} elseif ($_REQUEST['shipping_payment1'] || $_REQUEST['shipping_payment2']) {
			$shipping_payment1 = explode(';', $_REQUEST['shipping_payment1']);
			$shipping_payment2 = explode(';', $_REQUEST['shipping_payment2']);
			//print_r($shipping_payment1);
			//echo $_REQUEST['shipping_payment1'];
				if (isset($shipping_payment1) && $_REQUEST['order_cd_amount'] > $shipping_payment1[0] ) {

					$receiver_share_sum = $shipping_payment1[1];
				}
				if (isset($shipping_payment2) && $_REQUEST['order_cd_amount'] > $shipping_payment2[0] ) {
					$receiver_share_sum = $shipping_payment2[1];
				}

		}
*/

		}elseif (!empty($_REQUEST['shipping_payments'])){
			//print_r($_REQUEST['shipping_payments']);
			$shipping_payments = $_REQUEST['shipping_payments'];
			$order_amount = 0;
			foreach ($shipping_payments as $shipping_payment) {

				if ($_REQUEST['order_cd_amount'] >= $shipping_payment['order_amount'] && $shipping_payment['order_amount'] >= $order_amount) {
					$order_amount = $shipping_payment['order_amount'];
					$receiver_share_sum_door = $shipping_payment['receiver_amount'];
					$receiver_share_sum_office = $shipping_payment['receiver_amount_office'];
				}
			}



		}

		if ($_REQUEST['receiver_shipping_to'] == 'OFFICE' || $_REQUEST['receiver_shipping_to'] == 'MACHINE') {
			$receiver_share_sum = number_format((float)$receiver_share_sum_office, 2, '.', '');
		} else {
			$receiver_share_sum = number_format((float)$receiver_share_sum_door, 2, '.', '');
		}

		if ($receiver_share_sum > 0) {
			$data['loadings']['row']['payment']['side'] = 'SENDER';
		}
//			$data['loadings']['row']['payment']['side'] = 'SENDER';

		$data['loadings']['row']['payment']['receiver_share_sum'] = $receiver_share_sum;
		$data['loadings']['row']['payment']['share_percent'] = '';

		if ($data['loadings']['row']['payment']['side'] == 'RECEIVER') {
			$data['loadings']['row']['payment']['method'] = 'CASH';
		}

//





if( $_REQUEST['action2'] == 'only_calculate_loading' ){

//$result[] =	$_REQUEST['cd_agreement_num'];
	$data['system']['only_calculate'] = 1;
	$data['system']['validate'] = 0;

}elseif( $_REQUEST['action2'] == 'create_loading' ){
	$data['system']['only_calculate'] = 0;
	$data['system']['validate'] = 0;
}

//print_r($data);
$results = $econt_mysql->parcelImport($data);


	if ($results) {
			if (!empty($results->result->e->error)) {
				$result = array();
				$result['warning'] = (string)$results->result->e->error;
			} elseif (isset($results->result->e->loading_price->total)) {

				$order_total_sum = number_format((float)$results->result->e->loading_price->total, 2, '.', '');
//				echo $order_total_sum;
				if( $_REQUEST['action2'] == 'create_loading' ){
				
				$result = array(
					'order_id'    				=> $_REQUEST['order_id'],
					'loading_id'  				=> (string) $results->result->e->loading_id,
					'loading_num' 				=> (string) $results->result->e->loading_num,
					'pdf_url'     				=> (string) $results->result->e->pdf_url,
					'total_shipping_cost' 		=> $order_total_sum,
					'customer_shipping_cost'	=> $order_total_sum,
					'currency_symbol'			=> $_REQUEST['currency_symbol'],
					'currency'					=> $_REQUEST['currency'],
				);

				//$result['total_sum'] 		= $order_total_sum;
				//$result['order_total_sum'] 	= $order_total_sum;
				
				if($receiver_share_sum > 0){

				$result['customer_shipping_cost'] 	= $receiver_share_sum;
				}
				if($free_shipping == 1 || $_REQUEST['payment_side'] == 'SENDER'){ //da go testvam ?
				
				$result['customer_shipping_cost'] 	= 0;
				
				}

				//print_r($result);
				if (isset($results->pdf)) {
					
					$result['blank_yes'] = (string) $results->pdf->blank_yes;
					$result['blank_no'] = (string) $results->pdf->blank_no;
				
				} else {

					$result['blank_yes'] = '';
					$result['blank_no'] = '';
				}
				
				
				
					$econt_mysql->addLoading($result);

			

			//$loading = $econt_mysql->getLoading($_REQUEST['order_id']);
			//$loading_num = '';
			//$loading_total_sum = '';
			//$loading_pdf_url = '';
			//if($loading != false){
			$orders = new WC_Order($_REQUEST['order_id']);
			
			//$loading_num = $loading['loading_num'];
			//$loading_total_sum = $loading['order_total_sum'];
			//$loading_pdf_url = $loading['pdf_url'];

			//updateva shipping costa na econt express shipping method
			$shipping_items = $orders->get_items('shipping');
			foreach ($shipping_items as $key => $value) {
				$shipping_method_id = $value['method_id'];
				$shipping_method_title = $value['name'];
				$shipping_item_id = $key;
				
			}
/*
			$free_shipping_sum = $_REQUEST['free_shipping_sum'];
			if( $_REQUEST['order_price'] >  $free_shipping_sum && $free_shipping_sum != '0'){
			$shipping_cost = 0;
			}else{
			$shipping_cost = $loading_total_sum;	
			}
*/
			$update_shipping_args = array(
				'method_id' => $shipping_method_id, 
				'method_title' => $shipping_method_title, 
				'cost' => $result['customer_shipping_cost'],
				);
			if(method_exists($orders, 'update_shipping')){
			
			$orders->update_shipping($shipping_item_id, $update_shipping_args);
			
			}
            
			update_post_meta($_REQUEST['order_id'], 'Econt_Customer_Shipping_Cost', sanitize_text_field($result['customer_shipping_cost']));
			update_post_meta($_REQUEST['order_id'], 'Econt_Total_Shipping_Cost', sanitize_text_field($result['total_shipping_cost'] ));
		
 				
			//}





				}elseif($_REQUEST['action2'] == 'only_calculate_loading'){
				
				$result = array(
					'order_id'    				=> $_REQUEST['order_id'],
					'econt_shipping_expenses' 	=> array('total_shipping_cost' => $order_total_sum,
														 'customer_shipping_cost' => $order_total_sum,
												 		 'currency_symbol'	=> $_REQUEST['currency_symbol'],
													),
				);	

			    if($receiver_share_sum > 0){
				
				$result = array(
					'order_id'    					=> $_REQUEST['order_id'],
					'econt_shipping_expenses' 		=> array('total_shipping_cost' => $order_total_sum,
												 			 'customer_shipping_cost' => $receiver_share_sum,
												 			 'currency_symbol'	=> $_REQUEST['currency_symbol'],
													    ),
				);	
				
				}

				if($free_shipping == 1 || $_REQUEST['payment_side'] == 'SENDER'){
				
				$result = array(
					'order_id'    					=> $_REQUEST['order_id'],
					'econt_shipping_expenses' 		=>  array('total_shipping_cost' => $order_total_sum,
											  				  'customer_shipping_cost' => __('free shipping', 'woocommerce-econt'),
										      				  'currency_symbol'	=> '',
														),
				);	

				}


				}
			
	
			}

		} else {
			$result['warning'] = __('error_connect', 'woocommerce-econt');
		}

  } //end of weight if
}elseif($_REQUEST['action2'] == 'delete_loading'){
$econt_mysql->deleteLoading(array('loading_num' => $_REQUEST['loading_num']));
$result = array('status' => $_REQUEST['loading_num']);
} //end of 'action2 == delete loading' if

} //end of action2 if

//$response = json_encode($result, JSON_UNESCAPED_UNICODE);
$response = json_encode($result);

	echo $response;
	exit();


	}


	function pluginname_ajaxurl() {
		?>
		<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		</script>
		<?php

	}

	private function getIfSet(&$value, $default = null){
    return isset($value) ? $value : $default;
	}



}
}
new Econt_Ajax;
?>
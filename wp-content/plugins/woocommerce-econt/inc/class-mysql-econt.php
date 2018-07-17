<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('Econt_mySQL')) {
class Econt_mySQL {
	
	
	private $delivery_type = 'to_office';
	function __construct(){
		
		//$this->createTables();

		$new_version = '1.0.0';

	if (get_option(MYPLUGIN_VERSION_NUM) != $new_version) {
    	 $this->myplugin_update_database_table();
    	update_option(MYPLUGIN_VERSION_KEY, $new_version);
		}
		//create tables when the plugin is installed 
		register_activation_hook( __FILE__, array($this, 'createTables') );
		
	}

	public static function myplugin_update_database_table(){
       //mysql tables updates for new version of the plugin

    }

	public static function createTables() {
    global $wpdb;
	//to enable dbDelta
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
/*
	$collate = '';

                if ( $wpdb->has_cap( 'collation' ) ) {
                        if ( ! empty( $wpdb->charset ) ) {
                                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
                        }
                        if ( ! empty( $wpdb->collate ) ) {
                                $collate .= " COLLATE $wpdb->collate";
                        }
                }
*/
     $collate = $wpdb->get_charset_collate();


		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_city'") != $wpdb->prefix . 'econt_city'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_city (
		  city_id int(11) NOT NULL AUTO_INCREMENT,
		  post_code varchar(10) NOT NULL DEFAULT '',
		  type varchar(3) NOT NULL DEFAULT '' COMMENT '‘гр.’ или ‘с.’',
		  name varchar(255) NOT NULL DEFAULT '',
		  name_en varchar(255) NOT NULL DEFAULT '',
		  zone_id int(11) NOT NULL DEFAULT '3' COMMENT '3 - Зона В',
		  country_id int(11) NOT NULL DEFAULT '1033' COMMENT '1033 - България',
		  office_id int(11) NOT NULL DEFAULT '0' COMMENT 'главния офис',
		  PRIMARY KEY  (city_id),
		  KEY post_code (post_code),
		  KEY name (name),
		  KEY name_en (name_en),
		  KEY office_id (office_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}

		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_city_office'") != $wpdb->prefix . 'econt_city_office'){
		
		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_city_office (
		  city_office_id int(11) NOT NULL AUTO_INCREMENT,
		  office_code varchar(10) NOT NULL DEFAULT '',
		  shipment_type varchar(32) NOT NULL DEFAULT '',
		  delivery_type varchar(32) NOT NULL DEFAULT '',
		  city_id int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY  (city_office_id),
		  KEY office_code (office_code),
		  KEY city_id (city_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}


		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_country'") != $wpdb->prefix . 'econt_country'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_country (
		  country_id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL DEFAULT '',
		  name_en varchar(255) NOT NULL DEFAULT '',
		  zone_id int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY  (country_id),
		  KEY zone_id (zone_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}


		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_customer'") != $wpdb->prefix . 'econt_customer'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_customer (
		  customer_id int(11) NOT NULL,
		  shipping_to varchar(32) NOT NULL DEFAULT '',
		  company varchar(32) NOT NULL DEFAULT '',
		  postcode varchar(10) NOT NULL DEFAULT '',
		  city varchar(255) NOT NULL DEFAULT '',
		  quarter varchar(255) NOT NULL DEFAULT '',
		  street varchar(255) NOT NULL DEFAULT '',
		  street_num varchar(10) NOT NULL DEFAULT '',
		  other varchar(255) NOT NULL DEFAULT '',
		  city_id int(11) NOT NULL DEFAULT '0',
		  office_id int(11) NOT NULL DEFAULT '0',
		  KEY customer_id (customer_id),
		  KEY city_id (city_id),
		  KEY office_id (office_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}


		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_loading'") != $wpdb->prefix . 'econt_loading'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_loading (
		  econt_loading_id int(11) NOT NULL AUTO_INCREMENT,
		  order_id int(11) NOT NULL DEFAULT '0',
		  loading_id varchar(32) NOT NULL DEFAULT '',
		  loading_num varchar(32) NOT NULL DEFAULT '',
		  is_imported tinyint(1) NOT NULL DEFAULT '0',
		  storage varchar(255) NOT NULL DEFAULT '',
		  receiver_person varchar(255) NOT NULL DEFAULT '',
		  receiver_person_phone varchar(255) NOT NULL DEFAULT '',
		  receiver_courier varchar(255) NOT NULL DEFAULT '',
		  receiver_courier_phone varchar(255) NOT NULL DEFAULT '',
		  receiver_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  cd_get_sum varchar(32) NOT NULL DEFAULT '',
		  cd_get_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  cd_send_sum varchar(32) NOT NULL DEFAULT '',
		  cd_send_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  total_sum varchar(32) NOT NULL DEFAULT '',
		  order_total_sum varchar(32) NOT NULL DEFAULT '',
		  currency varchar(10) NOT NULL DEFAULT '',
		  sender_ammount_due varchar(32) NOT NULL DEFAULT '',
		  receiver_ammount_due varchar(32) NOT NULL DEFAULT '',
		  other_ammount_due varchar(32) NOT NULL DEFAULT '',
		  delivery_attempt_count varchar(10) NOT NULL DEFAULT '',
		  customer_shipping_cost float(8,2) NOT NULL DEFAULT '0.00',
		  total_shipping_cost float(8,2) NOT NULL DEFAULT '0.00',
		  pdf_url varchar(255) NOT NULL DEFAULT '',
		  prev_parcel_num varchar(32) NOT NULL DEFAULT '',
		  next_parcel_reason varchar(32) NOT NULL DEFAULT '',
		  is_returned tinyint(1) NOT NULL DEFAULT '0',
		  returned_blank_yes varchar(255) NOT NULL DEFAULT '',
		  blank_yes varchar(255) NOT NULL DEFAULT '',
		  blank_no varchar(255) NOT NULL DEFAULT '',
		  PRIMARY KEY  (econt_loading_id),
		  KEY order_id (order_id)
		) ENGINE=InnoDB " .$collate;
		
		dbDelta($sql);
		}

		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_loading_tracking'") != $wpdb->prefix . 'econt_loading_tracking'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_loading_tracking (
		  econt_loading_tracking_id int(11) NOT NULL AUTO_INCREMENT,
		  econt_loading_id int(11) NOT NULL DEFAULT '0',
		  loading_num varchar(32) NOT NULL DEFAULT '',
		  time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  is_receipt tinyint(1) NOT NULL DEFAULT '0',
		  event varchar(32) NOT NULL DEFAULT '',
		  name varchar(255) NOT NULL DEFAULT '',
		  name_en varchar(255) NOT NULL DEFAULT '',
		  PRIMARY KEY  (econt_loading_tracking_id),
		  KEY econt_loading_id (econt_loading_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}

		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_office'") != $wpdb->prefix . 'econt_office'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_office (
		  office_id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL DEFAULT '',
		  name_en varchar(255) NOT NULL DEFAULT '',
		  office_code varchar(10) NOT NULL DEFAULT '',
		  is_machine varchar(10) NOT NULL DEFAULT '',
		  address varchar(255) NOT NULL DEFAULT '',
		  address_en varchar(255) NOT NULL DEFAULT '',
		  phone varchar(32) NOT NULL DEFAULT '',
		  work_begin time DEFAULT '09:00:00',
		  work_end time DEFAULT '18:00:00',
		  work_begin_saturday time DEFAULT '09:00:00',
		  work_end_saturday time DEFAULT '13:00:00',
		  time_priority time DEFAULT '12:00:00' COMMENT 'минимален приоритетен час',
		  city_id int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY  (office_id),
		  KEY office_code (office_code),
		  KEY city_id (city_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}


		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_order'") != $wpdb->prefix . 'econt_order'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_order (
		  econt_order_id int(11) NOT NULL AUTO_INCREMENT,
		  order_id int(11) NOT NULL DEFAULT '0',
		  data text NOT NULL,
		  PRIMARY KEY  (econt_order_id),
		  KEY order_id (order_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}

		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_quarter'") != $wpdb->prefix . 'econt_quarter'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_quarter (
		  quarter_id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL DEFAULT '',
		  name_en varchar(255) NOT NULL DEFAULT '',
		  city_id int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY  (quarter_id),
		  KEY name (name),
		  KEY name_en (name_en),
		  KEY city_id (city_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}
		
		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_region'") != $wpdb->prefix . 'econt_region'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_region (
		  region_id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL DEFAULT '',
		  code varchar(10) NOT NULL DEFAULT '',
		  city_id int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY  (region_id),
		  KEY name (name),
		  KEY code (code),
		  KEY city_id (city_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}

		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_street'") != $wpdb->prefix . 'econt_street'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_street (
		  street_id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL DEFAULT '',
		  name_en varchar(255) NOT NULL DEFAULT '',
		  city_id int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY  (street_id),
		  KEY name (name),
		  KEY name_en (name_en),
		  KEY city_id (city_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}

		if($wpdb->get_var("show tables like '".$wpdb->prefix."econt_zone'") != $wpdb->prefix . 'econt_zone'){

		$sql = "CREATE TABLE " . $wpdb->prefix . "econt_zone (
		  zone_id int(11) NOT NULL AUTO_INCREMENT,
		  name varchar(255) NOT NULL DEFAULT '',
		  name_en varchar(255) NOT NULL DEFAULT '',
		  national tinyint(1) NOT NULL DEFAULT '1',
		  is_ee tinyint(1) NOT NULL DEFAULT '1',
		  PRIMARY KEY  (zone_id)
		) ENGINE=InnoDB " .$collate;

		dbDelta($sql);
		}
	
		add_option(MYPLUGIN_VERSION_KEY, MYPLUGIN_VERSION_NUM);
	

	}

	public function updateTablesRS() {
		global $wpdb;
		$wpdb->query("CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "econt_loading_tracking` (
		  `econt_loading_tracking_id` int(11) NOT NULL AUTO_INCREMENT,
		  `econt_loading_id` int(11) NOT NULL DEFAULT '0',
		  `loading_num` varchar(32) NOT NULL DEFAULT '',
		  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `is_receipt` tinyint(1) NOT NULL DEFAULT '0',
		  `event` varchar(32) NOT NULL DEFAULT '',
		  `name` varchar(255) NOT NULL DEFAULT '',
		  `name_en` varchar(255) NOT NULL DEFAULT '',
		  PRIMARY KEY (`econt_loading_tracking_id`),
		  KEY `econt_loading_id` (`econt_loading_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "econt_loading` ADD `pdf_url` varchar(255) NOT NULL DEFAULT ''");
		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "econt_loading` ADD `prev_parcel_num` varchar(32) NOT NULL DEFAULT ''");
		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "econt_loading` ADD `next_parcel_reason` varchar(32) NOT NULL DEFAULT ''");
		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "econt_loading` ADD `is_returned` tinyint(1) NOT NULL DEFAULT '0'");
		$wpdb->query("ALTER TABLE `" . $wpdb->prefix . "econt_loading` ADD `returned_blank_yes` varchar(255) NOT NULL DEFAULT ''");
	}

	public function deleteTables() {
		global $wpdb;
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_city`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_city_office`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_country`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_customer`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_loading`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_office`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_order`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_quarter`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_region`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_street`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_zone`");
		$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "econt_loading_tracking`");
	}

	public function deleteCountries() {
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "econt_country");
	}

	public function addCountry($data) {
		global $wpdb;
		$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_country SET name = '" . $data['name'] . "', name_en = '" . $data['name_en'] . "', zone_id = '" . (int)$data['zone_id'] . "'");
	}

	public function deleteZones() {
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "econt_zone");
	}

	public function addZone($data) {
		global $wpdb;
		$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_zone SET zone_id = '" . (int)$data['zone_id'] . "', name = '" . $data['name'] . "', name_en = '" . $data['name_en'] . "', national = '" . (int)$data['national'] . "', is_ee = '" . (int)$data['is_ee'] . "'");
	}

	public function deleteRegions() {
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "econt_region");
	}

	public function addRegion($data) {
		global $wpdb;
		$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_region SET region_id = '" . (int)$data['region_id'] . "', name = '" . $data['name'] . "', code = '" . $data['code'] . "', city_id = '" . (int)$data['city_id'] . "'");
	}

	public function deleteQuarters() {
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "econt_quarter");
	}

	public function addQuarter($data) {
		global $wpdb;
		$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_quarter SET quarter_id = '" . (int)$data['quarter_id'] . "', name = '" . $data['name'] . "', name_en = '" . $data['name_en'] . "', city_id = '" . (int)$data['city_id'] . "'");
	}

	public function deleteStreets() {
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "econt_street");
	}

	public function addStreet($data) {
		global $wpdb;
		$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_street SET street_id = '" . (int)$data['street_id'] . "', name = '" . $data['name'] . "', name_en = '" . $data['name_en'] . "', city_id = '" . (int)$data['city_id'] . "'");
	}

	public function deleteOffices() {
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "econt_office");
	}

	public function addOffice($data) {
		global $wpdb;
		$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_office SET office_id = '" . (int)$data['office_id'] . "', name = '" . $data['name'] . "', name_en = '" . $data['name_en'] . "', office_code = '" . $data['office_code'] . "', is_machine = '" . $data['is_machine'] . "', address = '" . $data['address'] . "', address_en = '" . $data['address_en'] . "', phone = '" . $data['phone'] . "', work_begin = '" . $data['work_begin'] . "', work_end = '" . $data['work_end'] . "', work_begin_saturday = '" . $data['work_begin_saturday'] . "', work_end_saturday = '" . $data['work_end_saturday'] . "', time_priority = '" . $data['time_priority'] . "', city_id = '" . (int)$data['city_id'] . "'");
	}

	public function deleteCities() {
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "econt_city");
	}

	public function addCity($data) {
		global $wpdb;
		$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_city SET city_id = '" . (int)$data['city_id'] . "', post_code = '" . $data['post_code'] . "', type = '" . $data['type'] . "', name = '" . $data['name'] . "', name_en = '" . $data['name_en'] . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', office_id = '" . (int)$data['office_id'] . "'");
	}

	public function deleteCitiesOffices() {
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . $wpdb->prefix . "econt_city_office");
	}

	public function addCityOffice($data) {
		global $wpdb;
		$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_city_office SET office_code = '" . $data['office_code'] . "', shipment_type = '" . $data['shipment_type'] . "', delivery_type = '" . $data['delivery_type'] . "', city_id = '" . (int)$data['city_id'] . "'");
	}

	public function getCitiesByName($name, $limit = 10) {
		global $wpdb;	
		
		if (strtolower( WPLANG ) == 'bg_bg') {
			$suffix = '';
		} else {
			$suffix = '_en';
		}

		$sql = "SELECT *, c.name" . $suffix . " AS name FROM " . $wpdb->prefix . "econt_city c";

		if ($name) {
			$sql .= " WHERE (LCASE(c.name) LIKE '%" . utf8_strtolower($name) . "%' OR LCASE(c.name_en) LIKE '%" . utf8_strtolower($name) . "%')";
		}

		$sql .= " ORDER BY c.name" . $suffix;

		$sql .= " LIMIT " . (int)$limit;

		$query = $wpdb->query($sql);

		return $query->rows;
	}

	public function getCityByNameAndPostcode($name, $postcode) {
		global $wpdb;
		$query = $wpdb->query("SELECT * FROM " . $wpdb->prefix . "econt_city c WHERE (LCASE(TRIM(c.name)) = '" . trim($name) . "' OR LCASE(TRIM(c.name_en)) = '" . trim($name) . "') AND TRIM(c.post_code) = '" . trim($postcode) . "'");

		return $query;
	}
	public function getCityByName($name) {
		global $wpdb;
		strtolower($name);
		$sql = "SELECT * FROM " . $wpdb->prefix . "econt_city c WHERE (LCASE(TRIM(c.name)) like '" . trim($name) . "%' OR LCASE(TRIM(c.name_en)) like '" . trim($name) . "%')";
		$results = $wpdb->get_results( $sql, ARRAY_A);
		//file_put_contents('/home/martin/dev/woocommerce/econt/wordpress/wp-content/citiesd.txt', 'cities: '.$sql );

		return $results;
	}

	public function getQuartersByName($name, $city_id, $limit = 10) {
		global $wpdb;

		if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		} else {
			$suffix = '_en';
		}

		$sql = "SELECT *, q.name" . $suffix . " AS name FROM " . $wpdb->prefix . "econt_quarter q WHERE 1";

		if ($name) {
			$sql .= " AND (LCASE(q.name) LIKE '%" . utf8_strtolower($name) . "%' OR LCASE(q.name_en) LIKE '%" . utf8_strtolower($name) . "%')";
		}

		if ($city_id) {
			$sql .= " AND q.city_id = '" . (int)$city_id . "'";
		}

		$sql .= " ORDER BY q.name" . $suffix;

		$sql .= " LIMIT " . (int)$limit;

		$query = $wpdb->query($sql);

		return $query->rows;
	}




	public function getQuartersByCityId($city_id, $name, $limit = 10) {
		global $wpdb;
		$name = strtolower($name);
		//if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		//} else {
		//	$suffix = '_en';
		//}

		$sql = "SELECT *, q.name" . $suffix . " AS name FROM " . $wpdb->prefix . "econt_quarter q WHERE 1";

		if ($name) {
			$sql .= " AND (LCASE(q.name) LIKE '%" . $name . "%' OR LCASE(q.name_en) LIKE '%" . $name . "%')";
		}

		if ($city_id) {
			$sql .= " AND q.city_id = '" . (int)$city_id . "'";
		}

		$sql .= " ORDER BY q.name" . $suffix;

		$sql .= " LIMIT " . (int)$limit;

		$query = $wpdb->get_results($sql, ARRAY_A);

		return $query;
	}



	public function getStreetsByName($name, $city_id, $limit = 10) {
		global $wpdb;

		if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		} else {
			$suffix = '_en';
		}

		$sql = "SELECT *, s.name" . $suffix . " AS name FROM " . $wpdb->prefix . "econt_street s WHERE 1";

		if ($name) {
			$sql .= " AND (LCASE(s.name) LIKE '%" . utf8_strtolower($name) . "%' OR LCASE(s.name_en) LIKE '%" . utf8_strtolower($name) . "%')";
		}

		if ($city_id) {
			$sql .= " AND s.city_id = '" . (int)$city_id . "'";
		}

		$sql .= " ORDER BY s.name" . $suffix;

		$sql .= " LIMIT " . (int)$limit;

		$query = $wpdb->query($sql);

		return $query->rows;
	}



	public function getStreetsByCityId($city_id, $name, $limit = 10) {
		global $wpdb;
		$name = strtolower($name);
		//if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		//} else {
		//	$suffix = '_en';
		//}

		$sql = "SELECT *, s.name" . $suffix . " AS name FROM " . $wpdb->prefix . "econt_street s WHERE 1";

		if ($name) {
			$sql .= " AND (LCASE(s.name) LIKE '%" . $name . "%' OR LCASE(s.name_en) LIKE '%" . $name . "%')";
		}

		if ($city_id) {
			$sql .= " AND s.city_id = '" . (int)$city_id . "'";
		}

		$sql .= " ORDER BY s.name" . $suffix;

		$sql .= " LIMIT " . (int)$limit;

		$query = $wpdb->get_results($sql, ARRAY_A);

		return $query;
	}




	public function getCitiesWithOffices($delivery_type = '') {
		global $wpdb;

		if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		} else {
			$suffix = '_en';
		}

		$sql = "SELECT c.city_id, c.name" . $suffix . " AS name FROM " . $wpdb->prefix . "econt_city c INNER JOIN " . $wpdb->prefix . "econt_office o ON (c.city_id = o.city_id) ";

		if ($delivery_type) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "econt_city_office eco ON o.office_code = eco.office_code AND o.city_id = eco.city_id AND eco.delivery_type = '" . $delivery_type . "' ";
		}

		$sql .= " GROUP BY c.city_id ORDER BY c.name" . $suffix;

		$query = $wpdb->query($sql);

		return $query->rows;
	}

	public function getOfficesByCityId($city_id, $is_machine = '', $delivery_type = '') {
		global $wpdb;

		//if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		//} else {
		//	$suffix = '_en';
		//}

		$sql = "SELECT *, o.name" . $suffix . " AS name, o.address" . $suffix . " AS address FROM " . $wpdb->prefix . "econt_office o ";

		if ($delivery_type) {
			$sql .= " INNER JOIN " . $wpdb->prefix . "econt_city_office eco ON o.office_code = eco.office_code AND o.city_id = eco.city_id AND eco.delivery_type = '" . $delivery_type . "' ";
		}

		$sql .= " WHERE o.city_id = '" . (int)$city_id . "' ";
		
		if($is_machine) {
		$sql .= " AND o.is_machine = 1 ";
		}else{
		$sql .= " AND o.is_machine != 1 ";	
		}
		
		$sql .= " GROUP BY o.office_id ORDER BY o.name" . $suffix;

		$query = $wpdb->get_results($sql, ARRAY_A);

		return $query;
	}

	public function getOffice($office_id) {
		global $wpdb;

		//if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		//} else {
		//	$suffix = '_en';
		//}

		$query = $wpdb->get_row("SELECT *, o.name" . $suffix . " AS name, o.address" . $suffix . " AS address FROM " . $wpdb->prefix . "econt_office o WHERE o.office_id = '" . (int)$office_id . "'", ARRAY_A);

		return $query;
	}



	public function getCityByCityId($city_id) {
		global $wpdb;

		if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		} else {
			$suffix = '_en';
		}

		$sql = "SELECT c.city_id, c.post_code, c.name" . $suffix . " AS name FROM " . $wpdb->prefix . "econt_city c WHERE city_id = '" . (int)$city_id . "'";

		$query = $wpdb->query($sql);
		if ($query->num_rows == 1) {
			return $query->row;
		} else {
			return false;
		}
	}
	
	public function getCityIdByCityName($city_name) {
		global $wpdb;

		if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		} else {
			$suffix = '_en';
		}

		$sql = "SELECT c.city_id, c.post_code, c.name" . $suffix . " AS name FROM " . $wpdb->prefix . "econt_city c WHERE name = '" . $city_name . "' or name_en = '" . $city_name . "'";

		$query = $wpdb->get_row($sql, ARRAY_A);
		if ($wpdb->num_rows == 1) {
			
			return $query;
		} else {
			return false;
		
		}
	}

	public function getOfficeByOfficeCode($office_code) {
		global $wpdb;

		//if (strtolower(WPLANG) == 'bg_bg') {
			$suffix = '';
		//} else {
		//	$suffix = '_en';
		//}

		$sql = "SELECT o.*, o.name" . $suffix . " AS name, o.address" . $suffix . " AS address, c.name" . $suffix . " as city_name FROM " . $wpdb->prefix . "econt_office o INNER JOIN " . $wpdb->prefix . "econt_city c ON o.city_id = c.city_id WHERE o.office_code = '" . (int)$office_code . "' ";

		$query = $wpdb->get_row($sql, ARRAY_A);
		if ($wpdb->num_rows == 1) {
			return $query;
		} else {
			return false;
		}
	}

	public function validateAddress($data) {
		global $wpdb;

		$sql = "SELECT COUNT(c.city_id) AS total FROM " . $wpdb->prefix . "econt_city c LEFT JOIN " . $wpdb->prefix . "econt_quarter q ON (c.city_id = q.city_id) LEFT JOIN " . $wpdb->prefix . "econt_street s ON (c.city_id = s.city_id) WHERE TRIM(c.post_code) = '". trim($data['post_code']) . "' AND (LCASE(TRIM(c.name)) = '" . strtolower(trim($data['city'])) . "' OR LCASE(TRIM(c.name_en)) = '" . strtolower(trim($data['city'])) . "')";

		if ($data['quarter']) {
			$sql .= " AND (LCASE(TRIM(q.name)) = '" . strtolower(trim($data['quarter'])) . "' OR LCASE(TRIM(q.name_en)) = '" . strtolower(trim($data['quarter'])) . "')";
		}

		if ($data['street']) {
			$sql .= " AND (LCASE(TRIM(s.name)) = '" . strtolower(trim($data['street'])) . "' OR LCASE(TRIM(s.name_en)) = '" . strtolower(trim($data['street'])) . "')";
		}

		//$query = $wpdb->query($sql);
		$query = $wpdb->get_row($sql, ARRAY_A);
		//$file = '/home/martin/dev/woocommerce/econt/wordpress/wp-content/validate_econt.txt';
		//file_put_contents($file, print_r($query));
		return $query;
		//return $query->row['total'];
		//return $wpdb->num_rows;
	}
//}


	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "econt_order WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}

	public function getLoading($order_id) {
		global $wpdb;
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "econt_loading WHERE order_id = '" . (int)$order_id . "'");
		//$sql = "SELECT loading_num, pdf_url, order_total_sum, total_sum FROM " . $wpdb->prefix . "econt_loading WHERE order_id='". $order_id . "' order by econt_loading_id DESC limit 1;"  ;
		$sql = "SELECT * FROM " . $wpdb->prefix . "econt_loading WHERE order_id='". $order_id . "' order by econt_loading_id DESC limit 1;"  ;

		$query = $wpdb->get_row($sql, ARRAY_A);
		if ($wpdb->num_rows == 1) {
			return $query;
		} else {
			return false;
		}
		//return $query->row;
	}

	public function getLoadingNextParcels($loading_num) {
		global $wpdb;
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "econt_loading WHERE prev_parcel_num = '" . $this->db->escape($loading_num) . "'");
		$sql = "SELECT * FROM " . $wpdb->prefix . "econt_loading WHERE prev_parcel_num = '" . $this->db->escape($loading_num) . "'";
		
		$query = $wpdb->get_row($sql, ARRAY_A);
		if ($wpdb->num_rows == 1) {
			return $query;
		} else {
			return false;
		}

		//return $query->rows;
	}

	public function getLoadingTrackings($econt_loading_id) {
		global $wpdb;
		//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "econt_loading_tracking WHERE econt_loading_id = '" . (int)$econt_loading_id . "'");
		$sql = "SELECT * FROM " . $wpdb->prefix . "econt_loading_tracking WHERE econt_loading_id = '" . (int)$econt_loading_id . "'";
		$query = $wpdb->get_row($sql, ARRAY_A);
		if ($wpdb->num_rows == 1) {
			return $query;
		} else {
			return false;
		}
		//return $query->rows;
	}

	public function addLoading($data) {
		//$this->db->query("INSERT INTO " . DB_PREFIX . "econt_loading SET order_id = '" . (int)$data['order_id'] . "', loading_id = '" . $this->db->escape($data['loading_id']) . "', loading_num = '" . $this->db->escape($data['loading_num']) . "', blank_yes = '" . $this->db->escape(trim($data['blank_yes'])) . "', blank_no = '" . $this->db->escape(trim($data['blank_no'])) . "', pdf_url = '" . $this->db->escape(trim($data['pdf_url'])) . "'");
	global $wpdb;
	//$wpdb->insert( $wpdb->prefix . "econt_loading", $data, array( '%d', '%d', '%d', '%s', '%d' ) ); 
	//$wpdb->query( "INSERT INTO " . $wpdb->prefix . "econt_loading (`order_id`, `loading_id`, `loading_num`, `pdf_url`, `order_total_sum`) VALUES ('" . $data['order_id'] . "','" . $data['loading_id'] . "','" . $data['loading_num'] . "','" . $data['pdf_url'] . "','" . $data['order_total_sum'] . "')"  );
    $wpdb->query( "INSERT INTO " . $wpdb->prefix . "econt_loading SET order_id = '" . (int)$data['order_id'] . "', loading_id = '" . $data['loading_id'] . "', loading_num = '" . $data['loading_num'] . "', blank_yes = '" . trim($data['blank_yes']) . "', blank_no = '" . trim($data['blank_no']) . "', total_sum = '" . $data['total_sum'] . "', order_total_sum = '" . trim($data['order_total_sum']) . "', pdf_url = '" . trim($data['pdf_url']) . "'"  );


	}
	public function deleteLoading($data) {
		//$this->db->query("INSERT INTO " . DB_PREFIX . "econt_loading SET order_id = '" . (int)$data['order_id'] . "', loading_id = '" . $this->db->escape($data['loading_id']) . "', loading_num = '" . $this->db->escape($data['loading_num']) . "', blank_yes = '" . $this->db->escape(trim($data['blank_yes'])) . "', blank_no = '" . $this->db->escape(trim($data['blank_no'])) . "', pdf_url = '" . $this->db->escape(trim($data['pdf_url'])) . "'");
	global $wpdb;
	//$wpdb->insert( $wpdb->prefix . "econt_loading", $data, array( '%d', '%d', '%d', '%s', '%d' ) ); 
	//$wpdb->query( "INSERT INTO " . $wpdb->prefix . "econt_loading (`order_id`, `loading_id`, `loading_num`, `pdf_url`, `order_total_sum`) VALUES ('" . $data['order_id'] . "','" . $data['loading_id'] . "','" . $data['loading_num'] . "','" . $data['pdf_url'] . "','" . $data['order_total_sum'] . "')"  );
    //$wpdb->query( "DELETE FROM " . $wpdb->prefix . "econt_loading WHERE loading_num = '" . (int)$data['loading_num'] . "'" );
    //$wpdb->query( "DELETE FROM " . $wpdb->prefix . "econt_loading_tracking WHERE loading_num = '" . (int)$data['loading_num'] . "'" );
    $wpdb->delete($wpdb->prefix . "econt_loading", $data); //$data = array('loading_num' => some_number) 

	}

	public function updateLoading($data) {
		global $wpdb;
		$wpdb->query("UPDATE " . $wpdb->prefix . "econt_loading SET is_imported = '" . (int)$data['is_imported'] . "', storage = '" . $data['storage'] . "', receiver_person = '" . $data['receiver_person'] . "', receiver_person_phone = '" . $data['receiver_person_phone'] . "', receiver_courier = '" . $data['receiver_courier'] . "', receiver_courier_phone = '" . $data['receiver_courier_phone'] . "', receiver_time = '" . date('Y-m-d H:i:s', strtotime($data['receiver_time'])) . "', cd_get_sum = '" . $data['cd_get_sum'] . "', cd_get_time = '" . date('Y-m-d H:i:s', strtotime($data['cd_get_time'])) . "', cd_send_sum = '" . $data['cd_send_sum'] . "', cd_send_time = '" . date('Y-m-d H:i:s', strtotime($data['cd_send_time'])) . "', total_sum = '" . $data['total_sum'] . "', currency = '" . $data['currency'] . "', sender_ammount_due = '" . $data['sender_ammount_due'] . "', receiver_ammount_due = '" . $data['receiver_ammount_due'] . "', other_ammount_due = '" . $data['other_ammount_due'] . "', delivery_attempt_count = '" . $data['delivery_attempt_count'] . "', blank_yes = '" . trim($data['blank_yes']) . "', blank_no = '" . trim($data['blank_no']) . "', pdf_url = '" . trim($data['pdf_url']) . "' WHERE econt_loading_id  = '" . (int)$data['econt_loading_id'] . "'");

		if (isset($data['trackings'])) {
			foreach ($data['trackings'] as $tracking) {
				$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_loading_tracking SET econt_loading_id = '" . (int)$data['econt_loading_id'] . "', loading_num = '" . $data['loading_num'] . "', time = '" . date('Y-m-d H:i:s', strtotime($tracking['time'])) . "', is_receipt = '" . (int)$tracking['is_receipt'] . "', event = '" . $tracking['event'] . "', name = '" . $tracking['name'] . "', name_en = '" . $tracking['name_en'] . "'");
			}
		}

		if (isset($data['next_parcels'])) {
			foreach ($data['next_parcels'] as $next_parcel) {
				$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_loading SET loading_num = '" . $next_parcel['loading_num'] . "', is_imported = '" . (int)$next_parcel['is_imported'] . "', storage = '" . $next_parcel['storage'] . "', receiver_person = '" . $next_parcel['receiver_person'] . "', receiver_person_phone = '" . $next_parcel['receiver_person_phone'] . "', receiver_courier = '" . $next_parcel['receiver_courier'] . "', receiver_courier_phone = '" . $next_parcel['receiver_courier_phone'] . "', receiver_time = '" . date('Y-m-d H:i:s', strtotime($next_parcel['receiver_time'])) . "', cd_get_sum = '" . $next_parcel['cd_get_sum'] . "', cd_get_time = '" . date('Y-m-d H:i:s', strtotime($next_parcel['cd_get_time'])) . "', cd_send_sum = '" . $next_parcel['cd_send_sum'] . "', cd_send_time = '" . date('Y-m-d H:i:s', strtotime($next_parcel['cd_send_time'])) . "', total_sum = '" . $next_parcel['total_sum'] . "', currency = '" . $next_parcel['currency'] . "', sender_ammount_due = '" . $next_parcel['sender_ammount_due'] . "', receiver_ammount_due = '" . $next_parcel['receiver_ammount_due'] . "', other_ammount_due = '" . $next_parcel['other_ammount_due'] . "', delivery_attempt_count = '" . $next_parcel['delivery_attempt_count'] . "', blank_yes = '" . trim($next_parcel['blank_yes']) . "', blank_no = '" . trim($next_parcel['blank_no']) . "', pdf_url = '" . trim($next_parcel['pdf_url']) . "', prev_parcel_num = '" . trim($data['loading_num']) . "', next_parcel_reason = '" . trim($next_parcel['reason']) . "'");

				if (isset($next_parcel['trackings'])) {
					//$econt_loading_next_id = $this->db->getLastId();
					$econt_loading_next_id = $wpdb->insert_id;

					foreach ($next_parcel['trackings'] as $tracking) {
						$wpdb->query("INSERT INTO " . $wpdb->prefix . "econt_loading_tracking SET econt_loading_id = '" . (int)$econt_loading_next_id . "', loading_num = '" . $next_parcel['loading_num'] . "', time = '" . date('Y-m-d H:i:s', strtotime($tracking['time'])) . "', is_receipt = '" . (int)$tracking['is_receipt'] . "', event = '" . $tracking['event'] . "', name = '" . $tracking['name'] . "', name_en = '" . $tracking['name_en'] . "'");
					}
				}
			}
		}
	}


	//vzima dannite za profila na sobstvenika na magazina ot profila mu v econt
	public function getProfile($username, $password, $live) {

		$data = array(
			'live'     => $live,
			'username' => $username,
			'password' => $password,
			'type'     => 'profile'
		);
		//exit(print_r($data));
		$profile_data = array();

		$results = $this->serviceTool($data);

		if ($results) {
			if (isset($results->error)) {
				$profile_data['error'] = (string)$results->error->message;
			} else {
				if (isset($results->client_info)) {
					$profile_data['client_info'] = $results->client_info;

					if (!empty($results->client_info->id)) {
						if ($data['live']) {
							$instructions_form_url = 'http://ee.econt.com/load_direct.php?target=EeLoadingInstructions';
						} else {
							$instructions_form_url = 'http://demo.econt.com/ee/load_direct.php?target=EeLoadingInstructions';
						}

						$profile_data['instructions_form_url'] = $instructions_form_url . '&login_username=' . $username . '&login_password=' . md5($password) . '&target_type=client&id_target=' . (string)$results->client_info->id;
					}
				}

				if (isset($results->addresses)) {
					foreach ($results->addresses->e as $address) {

						if (isset($address->city) && isset($address->city_post_code)) {
							$city = $this->getCityByNameAndPostcode($address->city, $address->city_post_code);

							if ($city) {
								$address->city_id = $city['city_id'];
							}
						}

						$profile_data['addresses'][] = $address;
					}
				}
			}
		} else {
			$profile_data['error'] = $this->language->get('error_connect');
		}


		return $profile_data;
	}

//tova e funkcia za vzimane na sporazumenie za nalojen platej CD
	public function getClients($username, $password, $live) {
		
		$data = array(
			'live'     => $live,
			'username' => $username,
			'password' => $password,
			'type'     => 'access_clients'
		);

		$clients_data = array();

		$results = $this->serviceTool($data);

		if ($results) {
			if (isset($results->error)) {
				$clients_data['error'] = (string)$results->error->message;
			} else {
				if (isset($results->clients)) {
					foreach ($results->clients->client as $client) {
						$clients_data['key_words'][] = (string)$client->key_word;

						if (isset($client->cd_agreements)) {
							foreach ($client->cd_agreements->cd_agreement as $cd_agreement) {
								$clients_data['cd_agreement_nums'][] = (string)$cd_agreement->num;
							}
						}

						if (isset($client->instructions)) {
							foreach ($client->instructions->e as $instruction) {
								$clients_data['instructions'][(string)$instruction->type][] = (string)$instruction->template;
							}
						}
					}
				}
			}
		} else {
			$clients_data['error'] = 'error_connect';
		}

		return $clients_data;
	}



		function refreshData($username, $password, $live) {
		@ini_set('memory_limit', '512M');
		@ini_set('max_execution_time', 3600);


		$data = array(
			'live'     => $live,
			'username' => $username,
			'password' => $password
		);

		$results_data = array();

		if (!isset($results_data['error'])) {
			$data['type'] = 'countries';

			$results = $this->serviceTool($data);

			if ($results) {
				if (isset($results->error)) {
					$results_data['error'] = (string)$results->error->message;
				} else {
					if (isset($results->e)) {
						$this->deleteCountries();

						foreach ($results->e as $country) {
							$country_data = array(
								'name'    => $country->country_name,
								'name_en' => $country->country_name_en,
								'zone_id' => $country->id_zone
							);

							$this->addCountry($country_data);
						}
					}

				}
			} else {
				$results_data['error'] = __('error_connect_countries', 'woocommerce-econt');
			}
		} 

		if (!isset($results_data['error'])) {
			$data['type'] = 'cities_zones';

			$results = $this->serviceTool($data);
			
			//$this->write_log($results);

			if ($results) {
				if (isset($results->error)) {
					$results_data['error'] = (string)$results->error->message;
				} else {
					if (isset($results->zones)) {
						$this->deleteZones();

						foreach ($results->zones->e as $zone) {
							$zone_data = array(
								'zone_id'  => $zone->id,
								'name'     => $zone->name,
								'name_en'  => $zone->name_en,
								'national' => $zone->national,
								'is_ee'    => $zone->is_ee
							);

							$this->addZone($zone_data);
						}
					}

				}
			} else {
				$results_data['error'] = __('error_connect cities_zones', 'woocommerce-econt');
			}
		}

		if (!isset($results_data['error'])) {
			$data['type'] = 'cities_regions';

			$results = $this->serviceTool($data);

			if ($results) {
				if (isset($results->error)) {
					$results_data['error'] = (string)$results->error->message;
				} else {
					if (isset($results->cities_regions)) {
						$this->deleteRegions();

						foreach ($results->cities_regions->e as $region) {
							$region_data = array(
								'region_id' => $region->id,
								'name'      => $region->name,
								'code'      => $region->code,
								'city_id'   => $region->id_city
							);

							$this->addRegion($region_data);
						}
					}

				}
			} else {
				$results_data['error'] = __('error_connect_cities_regions', 'woocommerce-econt');
			}
		}

		if (!isset($results_data['error'])) {
			$data['type'] = 'cities_quarters';

			$results = $this->serviceTool($data);

			if ($results) {
				if (isset($results->error)) {
					$results_data['error'] = (string)$results->error->message;
				} else {
					if (isset($results->cities_quarters)) {
						$this->deleteQuarters();

						foreach ($results->cities_quarters->e as $quarter) {
							$quarter_data = array(
								'quarter_id'     => $quarter->id,
								'name'           => $quarter->name,
								'name_en'        => $quarter->name_en,
								'city_id'        => $quarter->id_city
							);

							$this->addQuarter($quarter_data);
						}
					}

				}
			} else {
				$results_data['error'] = __('error_connect_cities_quarters', 'woocommerce-econt');
			}
		}

		if (!isset($results_data['error'])) {
			$data['type'] = 'cities_streets';

			$results = $this->serviceTool($data);

			if ($results) {
				if (isset($results->error)) {
					$results_data['error'] = (string)$results->error->message;
				} else {
					if (isset($results->cities_street)) {
						$this->deleteStreets();

						foreach ($results->cities_street->e as $street) {
							$street_data = array(
								'street_id'      => $street->id,
								'name'           => $street->name,
								'name_en'        => $street->name_en,
								'city_id'        => $street->id_city
							);

							$this->addStreet($street_data);
						}
					}
					
				}
			} else {
				$results_data['error'] = __('error_connect_cities_streets', 'woocommerce-econt');
			}
		}

		if (!isset($results_data['error'])) {
			$data['type'] = 'offices';

			$results = $this->serviceTool($data);

			if ($results) {
				if (isset($results->error)) {
					$results_data['error'] = (string)$results->error->message;
				} else {
					if (isset($results->offices)) {
						$this->deleteOffices();

						foreach ($results->offices->e as $office) {
							$office_data = array(
								'office_id'           => $office->id,
								'name'                => $office->name,
								'name_en'             => $office->name_en,
								'office_code'         => $office->office_code,
								'is_machine'		  => $office->is_machine,
								'address'             => $office->address,
								'address_en'          => $office->address_en,
								'phone'               => $office->phone,
								'work_begin'          => $office->work_begin,
								'work_end'            => $office->work_end,
								'work_begin_saturday' => $office->work_begin_saturday,
								'work_end_saturday'   => $office->work_end_saturday,
								'time_priority'       => $office->time_priority,
								'city_id'             => $office->id_city
							);

							$this->addOffice($office_data);
						}
					}

				}
			} else {
				$results_data['error'] = __('error_connect_offices', 'woocommerce-econt');
			}
		}

		if (!isset($results_data['error'])) {
			$data['type'] = 'cities';

			$results = $this->serviceTool($data);

			if ($results) {
				if (isset($results->error)) {
					$results_data['error'] = (string)$results->error->message;
				} else {
					if (isset($results->cities)) {
						$this->deleteCities();
						$this->deleteCitiesOffices();

						foreach ($results->cities->e as $city) {
							$city_data = array(
								'city_id'    => $city->id,
								'post_code'  => $city->post_code,
								'type'       => $city->type,
								'name'       => $city->name,
								'name_en'    => $city->name_en,
								'zone_id'    => $city->id_zone,
								'country_id' => $city->id_country,
								'office_id'  => $city->id_office
							);

							$this->addCity($city_data);

							if (isset($city->attach_offices)) {
								foreach ($city->attach_offices->children() as $shipment_type) {
									foreach ($shipment_type->children() as $delivery_type) {
										foreach ($delivery_type->office_code as $office_code) {
											$city_office_data = array(
												'office_code' => $office_code,
												'shipment_type' => $shipment_type->getName(),
												'delivery_type' => $delivery_type->getName(),
												'city_id' => $city->id
											);

											$this->addCityOffice($city_office_data);
										}
									}
								}
							}
						}

						$results_data['cities'] = $this->getCitiesWithOffices($this->delivery_type);
					}
				}
			} else {
				$results_data['error'] = __('error_connect_cities', 'woocommerce-econt');
			}
		}


		return $results_data;
	}


private function generateLoading($data) {
		$order_id = $this->request->get['order_id'];

		$data['system']['validate'] = 0;
		$data['system']['only_calculate'] = 0;

		if (!is_array($this->config->get('econt_addresses'))) {
			$addresses = unserialize($this->config->get('econt_addresses'));
		} else {
			$addresses = $this->config->get('econt_addresses');
		} 

		$address_id = $this->request->post['address_id'];

		if (isset($addresses[$address_id])) {
			$address = $addresses[$address_id];

			$data['loadings']['row']['sender']['city'] = $address['city'];
			$data['loadings']['row']['sender']['post_code'] = $address['post_code'];
			$data['loadings']['row']['sender']['quarter'] = $address['quarter'];
			$data['loadings']['row']['sender']['street'] = $address['street'];
			$data['loadings']['row']['sender']['street_num'] = $address['street_num'];
			$data['loadings']['row']['sender']['street_other'] = $address['other'];
		}

		if ($this->request->post['sms']) {
			$sms_no = $this->request->post['sms_no'];
		} else {
			$sms_no = '';
		}

		$data['loadings']['row']['receiver']['sms_no'] = $sms_no;

		if ($this->request->post['shipping_to'] == 'OFFICE') {
			if (isset($this->request->post['office_city_id'])) {
				$econt_city = $this->model_shipping_econt->getCityByCityId($this->request->post['office_city_id']);
				$data['loadings']['row']['receiver']['city'] = $econt_city['name'];
				$data['loadings']['row']['receiver']['post_code'] = $econt_city['post_code'];
			}

			$receiver_office_code = '';

			if (isset($this->request->post['office_id'])) {
				$receiver_office = $this->model_shipping_econt->getOffice($this->request->post['office_id']);
				if ($receiver_office) {
					$receiver_office_code = $receiver_office['office_code'];
				}
			}

			$data['loadings']['row']['receiver']['office_code'] = $receiver_office_code;
			$data['loadings']['row']['receiver']['quarter'] = '';
			$data['loadings']['row']['receiver']['street'] = '';
			$data['loadings']['row']['receiver']['street_num'] = '';
			$data['loadings']['row']['receiver']['street_other'] = '';
		} else {
			if (isset($this->request->post['city'])) {
				$data['loadings']['row']['receiver']['city'] = $this->request->post['city'];
			}

			if (isset($this->request->post['post_code'])) {
				$data['loadings']['row']['receiver']['post_code'] = $this->request->post['post_code'];
			}

			if (isset($this->request->post['quarter'])) {
				$data['loadings']['row']['receiver']['quarter'] = $this->request->post['quarter'];
			}

			if (isset($this->request->post['street'])) {
				$data['loadings']['row']['receiver']['street'] = $this->request->post['street'];
			}

			if (isset($this->request->post['street_num'])) {
				$data['loadings']['row']['receiver']['street_num'] = $this->request->post['street_num'];
			}

			if (isset($this->request->post['other'])) {
				$data['loadings']['row']['receiver']['street_other'] = $this->request->post['other'];
			}
		}

		$weight = 0;
		$description = array();
		$product_count = 0;
		$total = 0;

		$this->load->model('catalog/product');
		$order_products = $this->model_sale_order->getOrderProducts($order_id);

		foreach ($order_products as $product) {
			$description[] = $product['name'];
			$product_count += (int)$product['quantity'];

			$product_info = $this->model_catalog_product->getProduct($product['product_id']);

			if ($product_info) {
				$weight += $this->weight->convert($product_info['weight'] * $product['quantity'], $product_info['weight_class_id'], $this->config->get('econt_weight_class_id'));
			}
		}

		$this->load->model('sale/order');
		$order_totals = $this->model_sale_order->getOrderTotals($order_id);	
		foreach ($order_totals as $order_total) {
			if ($order_total['code'] == 'shipping') {
				$order_totals_shipping = (float)$order_total['value'];
			}
			if ($order_total['code'] == 'total') {
				$order_totals_total = (float)$order_total['value'];
			}
		}
		$total = $order_totals_total - $order_totals_shipping;

		$data['loadings']['row']['shipment']['description'] = implode(', ', $description);

		$data['loadings']['row']['shipment']['weight'] = $weight;

		if ($data['loadings']['row']['shipment']['weight'] > 100) {
			$data['loadings']['row']['shipment']['shipment_type'] = 'CARGO';
			$data['loadings']['row']['shipment']['cargo_code'] = 81;
		} else {
			$data['loadings']['row']['shipment']['shipment_type'] = 'PACK';
		}

		$total = round($this->currency->format($total, $this->config->get('econt_currency'), '', false), 2);

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info['payment_code'] == 'econt_cod') {
			$cd_type = 'GET';
			$cd_value = $total;
			$cd_currency = $this->config->get('econt_currency');

			if ($this->config->get('econt_cd_agreement')) {
				$cd_agreement_num = $this->config->get('econt_cd_agreement_num');
			} else {
				$cd_agreement_num = '';
			}
		} else {
			$cd_type = '';
			$cd_value = '';
			$cd_currency = '';
			$cd_agreement_num = '';
		}

		$data['loadings']['row']['services']['cd'] = array('type' => $cd_type, 'value' => $cd_value);
		$data['loadings']['row']['services']['cd_currency'] = $cd_currency;
		$data['loadings']['row']['services']['cd_agreement_num'] = $cd_agreement_num;

		$data['loadings']['row']['payment']['side'] = $this->config->get('econt_side');
		$data['loadings']['row']['payment']['method'] = $this->config->get('econt_payment_method');

		$receiver_share_sum_door = '';
		$receiver_share_sum_office = '';

		if ((float)$this->config->get('econt_total_for_free') && ($total >= $this->config->get('econt_total_for_free')) || (int)$this->config->get('econt_count_for_free') && ($product_count >= $this->config->get('econt_count_for_free')) || (float)$this->config->get('econt_weight_for_free') && ($weight >= $this->config->get('econt_weight_for_free'))) {
			$data['loadings']['row']['payment']['side'] = 'SENDER';
		} elseif ($this->config->get('econt_shipping_payments')) {
			if (!is_array($this->config->get('econt_shipping_payments'))) {
				$shipping_payments = unserialize($this->config->get('econt_shipping_payments'));
			} else {
				$shipping_payments = $this->config->get('econt_shipping_payments');
			}
			$order_amount = 0;

			foreach ($shipping_payments as $shipping_payment) {
				if ($total >= $shipping_payment['order_amount'] && $shipping_payment['order_amount'] >= $order_amount) {
					$order_amount = $shipping_payment['order_amount'];
					$receiver_share_sum_door = $shipping_payment['receiver_amount'];
					$receiver_share_sum_office = $shipping_payment['receiver_amount_office'];
				}
			}
		}

		if ($this->request->post['shipping_to'] == 'OFFICE') {
			$receiver_share_sum = $receiver_share_sum_office;
		} else {
			$receiver_share_sum = $receiver_share_sum_door;
		}

		if ($receiver_share_sum) {
			$data['loadings']['row']['payment']['side'] = 'SENDER';
		}

		$data['loadings']['row']['payment']['receiver_share_sum'] = $receiver_share_sum;
		$data['loadings']['row']['payment']['share_percent'] = '';

		if ($data['loadings']['row']['payment']['side'] == 'RECEIVER') {
			$data['loadings']['row']['payment']['method'] = 'CASH';
		}

		if ($data['loadings']['row']['payment']['method'] == 'CREDIT') {
			$key_word = $this->config->get('econt_key_word');
		} else {
			$key_word = '';
		}

		$data['loadings']['row']['payment']['key_word'] = $key_word;

		if ($this->config->get('econt_oc') && ($total >= $this->config->get('econt_total_for_oc'))) {
			$oc = $total;
			$oc_currency = $this->config->get('econt_currency');
		} else {
			$oc = '';
			$oc_currency = '';
		}

		$data['loadings']['row']['services']['oc'] = $oc;
		$data['loadings']['row']['services']['oc_currency'] = $oc_currency;

		if ($data['loadings']['row']['payment']['side'] == 'RECEIVER') {
			$data['loadings']['row']['payment']['method'] = 'CASH';
		}

		$data['loadings']['row']['shipment']['invoice_before_pay_CD'] = (int)$this->request->post['invoice_before_cd'];

		if (isset($this->request->post['pay_after_accept'])) {
			$pay_after_accept = (int)$this->request->post['pay_after_accept'];
		} else {
			$pay_after_accept = 0;
		}

		$data['loadings']['row']['shipment']['pay_after_accept'] = $pay_after_accept;

		$tariff_sub_code = $this->config->get('econt_shipping_from') . '_' . $this->request->post['shipping_to'];

		$tariff_code = 0;

		if (isset($this->request->post['express_city_courier_cb']) && $this->request->post['shipping_to'] == 'DOOR') {
			$tariff_code = 1;
		} elseif ($tariff_sub_code == 'OFFICE_OFFICE') {
			$tariff_code = 2;
		} elseif ($tariff_sub_code == 'OFFICE_DOOR' || $tariff_sub_code == 'DOOR_OFFICE') {
			$tariff_code = 3;
		} elseif ($tariff_sub_code == 'DOOR_DOOR') {
			$tariff_code = 4;
		}

		$data['loadings']['row']['shipment']['tariff_code'] = $tariff_code;
		$data['loadings']['row']['shipment']['tariff_sub_code'] = $tariff_sub_code;

		if (isset($this->request->post['pay_after_test'])) {
			$pay_after_test = (int)$this->request->post['pay_after_test'];
		} else {
			$pay_after_test = 0;
		}

		$data['loadings']['row']['shipment']['pay_after_test'] = $pay_after_test;

		if (isset($this->request->post['instruction_returns'])) {
			$instruction_returns = $this->request->post['instruction_returns'];
		} else {
			$instruction_returns = '';
		}

		$data['loadings']['row']['shipment']['instruction_returns'] = $instruction_returns;

		if (isset($this->request->post['delivery_day_cb']) && isset($this->request->post['delivery_day_id'])) {
			$delivery_day = $this->request->post['delivery_day_id'];
		} else {
			$delivery_day = '';
		}

		$data['loadings']['row']['shipment']['delivery_day'] = $delivery_day;

		$data['loadings']['row']['shipment']['pack_count'] = (int)$this->request->post['pack_count'];

		if (isset($this->request->post['priority_time_cb']) && $this->request->post['shipping_to'] == 'DOOR') {
			$priority_time_type = $this->request->post['priority_time_type_id'];
			$priority_time_value = $this->request->post['priority_time_hour_id'];
		} else {
			$priority_time_type = '';
			$priority_time_value = '';
		}

		$data['loadings']['row']['services']['p'] = array('type' => $priority_time_type, 'value' => $priority_time_value);

		$city_courier_e1 = '';
		$city_courier_e2 = '';
		$city_courier_e3 = '';

		if (isset($this->request->post['express_city_courier_cb']) && $this->request->post['shipping_to'] == 'DOOR') {
			if ($this->request->post['express_city_courier_e'] == 'e1') {
				$city_courier_e1 = 'ON';
			} elseif ($this->request->post['express_city_courier_e'] == 'e2') {
				$city_courier_e2 = 'ON';
			} elseif ($this->request->post['express_city_courier_e'] == 'e3') {
				$city_courier_e3 = 'ON';
			}
		}

		$data['loadings']['row']['services']['e1'] = $city_courier_e1;
		$data['loadings']['row']['services']['e2'] = $city_courier_e2;
		$data['loadings']['row']['services']['e3'] = $city_courier_e3;

		if ($this->request->post['dc']) {
			$dc = 'ON';
		} else {
			$dc = '';
		}

		$data['loadings']['row']['services']['dc'] = $dc;

		if ($this->request->post['dc_cp']) {
			$dc_cp = 'ON';
		} else {
			$dc_cp = '';
		}

		$data['loadings']['row']['services']['dc_cp'] = $dc_cp;

		if ($this->request->post['products_count'] > 1 && $this->request->post['partial_delivery']) {
			$data['loadings']['row']['packing_list']['partial_delivery'] = $this->request->post['partial_delivery_instruction'];
		}

		if ($this->request->post['inventory']) {
			$data['loadings']['row']['packing_list']['type'] = $this->request->post['inventory_type'];

			if ($this->request->post['inventory_type'] == 'DIGITAL') {
				foreach ($this->request->post['products'] as $product) {
					$data['loadings']['row']['packing_list']['row'][]['e'] = array(
						'inventory_num' => $product['product_id'],
						'description'   => $product['name'],
						'weight'        => $product['weight'],
						'price'         => $product['price']
					);
				}
			}
		}

		if ($this->request->post['instruction']) {
			foreach ($this->request->post['instructions'] as $type => $instruction) {
				if ($instruction != '') {
					$data['loadings']['row']['instructions'][]['e'] = array(
						'type'     => $type,
						'template' => $instruction
					);
				}
			}
		}

		$results = $this->parcelImport($data);

		if ($results) {
			if (!empty($results->result->e->error)) {
				$this->error['warning'] = (string)$results->result->e->error;
			} elseif (isset($results->result->e->loading_price->total)) {
				$loading_data = array(
					'order_id'    => $order_id,
					'loading_id'  => $results->result->e->loading_id,
					'loading_num' => $results->result->e->loading_num,
					'pdf_url'     => $results->result->e->pdf_url
				);

				if (isset($results->pdf)) {
					$loading_data['blank_yes'] = $results->pdf->blank_yes;
					$loading_data['blank_no'] = $results->pdf->blank_no;
				} else {
					$loading_data['blank_yes'] = '';
					$loading_data['blank_no'] = '';
				}

				$this->model_sale_econt->addLoading($loading_data);

				if ((float)$this->config->get('econt_total_for_free') && ($total >= $this->config->get('econt_total_for_free')) || (int)$this->config->get('econt_count_for_free') && ($product_count >= $this->config->get('econt_count_for_free')) || (float)$this->config->get('econt_weight_for_free') && ($weight >= $this->config->get('econt_weight_for_free')) || !$receiver_share_sum && $this->config->get('econt_side') == 'SENDER') {
					$order_total = 0.00;
				} elseif (isset($data['error']['weight'])) {
					$order_total = 0.00;
				} elseif ($receiver_share_sum) {
					$order_total = (float)$receiver_share_sum;
				} else {
					$order_total = (float)$results->result->e->loading_price->total;
				}

				$comment = $this->model_sale_econt->updateOrderTotal($order_id, (float)$order_total);

				$history_data = array(
					'order_status_id' => $this->config->get('econt_order_status_id'),
					'append' => true,
					'notify' => true,
					'comment' => $comment
				);

				$this->load->model('sale/order');

				$this->model_sale_order->addOrderHistory($order_id, $history_data);
			}
		} else {
			$this->error['warning'] = $this->language->get('error_connect');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}




	public function serviceTool($data) {
		if ($data['live']) {
			$url = 'http://www.econt.com/e-econt/xml_service_tool.php';
		} else {
			$url = 'http://demo.econt.com/e-econt/xml_service_tool.php';
		}
		//file_put_contents('/home/martin/dev/woocommerce/econt/wordpress/wp-content/service_tool_url.txt', $url);

		$request = '<?xml version="1.0" ?>
					<request>
						<client>
							<username>' . $data['username'] . '</username>
							<password>' . $data['password'] . '</password>
						</client>
						<request_type>' . $data['type'] . '</request_type>
						<mediator>mrejanet</mediator>';

		if (isset($data['xml'])) {
			$request .= $data['xml'];
		}

		$request .= '</request>';
		
		//file_put_contents('/home/martin/dev/woocommerce/econt/wordpress/wp-content/service_tool.xml', $request);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('xml' => $request));

		$response = curl_exec($ch);

		curl_close($ch);

		libxml_use_internal_errors(true);
		return simplexml_load_string($response);
	}

	public function parcelImport($data) {
		if ($data['live']) {
			$url = 'http://www.econt.com/e-econt/xml_parcel_import2.php';
		} else {
			$url = 'http://demo.econt.com/e-econt/xml_parcel_import2.php';
		}
		unset($data['live']);

		$data['loadings']['row']['mediator'] = 'mrejanet';

		$request = '<?xml version="1.0" ?>';
		$request .= '<parcels>';
		$request .= $this->prepareXML($data);
		$request .= '</parcels>';
		//$file = '/home/martin/dev/woocommerce/econt/wordpress/wp-content/loading_request.xml';
		$file = ECONT_PLUGIN_DIR .'/loading_request.xml';
		file_put_contents($file, $request);
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('xml' => $request));

		$response = curl_exec($ch);

		curl_close($ch);

		libxml_use_internal_errors(TRUE);
		return simplexml_load_string($response);
	}


	private function prepareXML($data) {
		$xml = '';

		foreach ($data as $key => $value) {
			if ($key && $key == 'error') {
				continue;
			}

			if ($key && ($key == 'p' || $key == 'cd')) {
				$xml .= '<' . $key . ' type="' . $value['type'] . '">' . $value['value'] . '</' . $key . '>' . "\r\n";
			} else {
				if (!is_numeric($key)) {
					$xml .= '<' . $key . '>';
				}

				if (is_array($value)) {
					$xml .= "\r\n" . $this->prepareXML($value);
				} else {
					$xml .= $value;
				}

				if (!is_numeric($key)) {
					$xml .= '</' . $key . '>' . "\r\n";
				}
			}
		}

		return $xml;
	}


    function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }

    public function wc_econt_admin_settings(){
    	$wc_econt 	= new WC_Econt_Shipping_Method;
    	return $wc_econt->username;
    }

    public function delivery_days($username, $password, $live){


            //delivery days
         //   if($wc_econt->delivery_day == 1){
            $data = array();
            $data['error_delivery_day'] = '';
            $data['delivery_days'] = array();
            $data['priority_date'] = '';
            $delivery_days = array();

            $delivery_days_data = array(
                'live'     => $live,
                'username' => htmlspecialchars_decode($username),
                'password' => htmlspecialchars_decode($password),
                'type'     => 'delivery_days',
                'xml'      => '<delivery_days>' . date('Y-m-d') . '</delivery_days>',
                //'xml'      => '<delivery_days>2015-06-19</delivery_days>',
            );

            $delivery_days_results = $this->serviceTool($delivery_days_data);

            if ($delivery_days_results) {
                if (isset($delivery_days_results->error)) {
                    $data['error_delivery_day'] = (string)$delivery_days_results->error->message;
                } else {
                    if (isset($delivery_days_results->delivery_days)) {
                        foreach ($delivery_days_results->delivery_days->e as $delivery_day) {
                          //  $data['delivery_days'][] = array(
                          //      'id' => $delivery_day->date,
                          //      'day' => date('w', strtotime($delivery_day->date)),
                          //      'name' => __('text_day_' . date('w', strtotime($delivery_day->date)), 'woocommerce-econt'),
                          //  );
                            $delivery_days[(string)$delivery_day->date] = date("D, d M y",strtotime($delivery_day->date));

                            if (date('w', strtotime($delivery_day->date)) == 6) {
                            $delivery_days[(string)$delivery_day->date] = __('with priority: ', 'woocommerce-econt') . date("D, d M y",strtotime($delivery_day->date));

                           //     $data['priority_date'] = $delivery_day->date;
                            } 
                            //elseif (!$data['delivery_day_id']) {
                            //    $data['delivery_day_id'] = $delivery_day->date;
                            //}
                        }
                    }
                }
            } else {
                $data['error_delivery_day'] = __('error_connect', 'woocommerce-econt');
                $delivery_days['error'] = __('error_connect', 'woocommerce-econt');
            }
            //print_r($delivery_days);
          //  }
            //end of delivery days
            return $delivery_days;



    }



 }
 }

new Econt_mySQL;

//register_activation_hook( __FILE__, array( 'Econt_mySQL', 'createTables' ) );
?>
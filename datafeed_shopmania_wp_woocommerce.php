<?php

##########################################################################################
# In order to be able to use this script you need to join the merchant program depending on the country where your store is selling the products
#
# AUSTRALIA - http://shopmania.com.au/ (only supporting AUD, NZD datafeeds)
# ARGENTINA - http://www.shopmania.com.ar/ (only supporting ARS, EUR, USD)       *NEW
# BRASIL - http://www.shopmania.com.br/ (only supporting BRL, USD) 
# BULGARY - http://www.shopmania.bg/ (only supporting BGN, EUR, USD)
# CZECH REPUBLIC - http://www.shop-mania.cz/ (only supporting CZK, EUR, USD)		*NEW
# CHILE - http://www.shopmania.cl/ (only supporting CLP, USD, EUR)       *NEW
# CHINA - http://www.shopmania.cn/ (only supporting CNY, USD)       
# DEUTSCHLAND - http://www.shopmania.de/ (only supporting EUR, USD) 
# FRANCE - http://www.shopmania.fr/ (only supporting EUR, USD datafeeds)
# HUNGARY - http://www.shopmania.hu/ (only supporting HUF, EUR, USD datafeeds)
# INDIA - http://www.shopmania.in/ (only supporting INR, USD datafeeds)
# IRELAND - http://www.shopmania.ie/ (only supporting EUR, GBP datafeeds)
# ITALY - http://www.shopmania.it/ (only supporting EUR, USD datafeeds)
# JAPAN - http://www.shopmania.jp/  (only supporting JPY, USD datafeeds)       
# MEXICO - http://www.shopmania.com.mx/ (only supporting MXN (Mexican peso), USD, EUR datafeeds)
# NETHERLANDS - http://www.shopmania.nl/ (only supporting EUR datafeeds)		*NEW
# POLSKA - http://www.shopmania.pl/ (only supporting PLN, EUR, USD) 
# PORTUGAL - http://www.shopmania.pt/ (only supporting EUR, USD) 
# ROMANIA - http://www.shopmania.ro/ (only supporting RON, EUR, USD datafeeds)
# RUSSIA - http://www.shopmania.ru/ (only supporting RUB, EUR, USD)       
# SERBIA - http://www.shopmania.rs/ (only supporting RSD, EUR)		*NEW	
# SLOVAKIA - http://www.shop-mania.sk/ (only supporting EUR, USD)
# SOUTH AFRICA - http://www.shopmania.co.za/ (only supporting ZAR, USD, EUR)       *NEW
# SPAIN - http://www.shopmania.es/ (only supporting EUR datafeeds) 
# SWEDEN - http://www.shopmania.se/ (only supporting SEK, EUR, USD datafeeds)		*NEW
# TURKEY - http://www.shopmania.com.tr/ (only supporting TRY, EUR, USD)
# US - http://www.shopmania.com/ (only supporting USD, CAD datafeeds)
# UK - http://www.shopmania.co.uk/ (only supporting GBP, EUR, USD datafeeds)
#
# Once you join the program and your application is approved you need to place the file on your server and set up the path to the file on the Merchant Interface
# Files will be  retrieved daily from your server having the products listed automatically on ShopMania
# 
# 
# Options
# @url_param taxes=on (on,off) 
# @url_param storetaxes=on (on,off) 
# @url_param add_vat=off (on,off) 
# @url_param vat_value=24 (VAT_VALUE) 
# @url_param add_tagging=on (on,off) 
# @url_param tagging_params=&utm_source=shopmania&utm_medium=cpc&utm_campaign=direct_link (TAGGING_PARAMS) 
# @url_param description=on (on,off) 
# @url_param image=on (on,off) 
# @url_param specialprice=on (on,off) 
# @url_param on_stock=off (on,off) 
# @url_param forcepath=off (on,off) 
# @url_param forcefolder= (FORCEFOLDER) 
# @url_param currency= (CURRENCY_CODE) 
#
# 
##########################################################################################

// Current datafeed script version
$script_version = "1.06(combinations)";

// Print current Script version
if (@$_GET['get'] == "version") {
	echo "<b>Datafeed WP WooCommerce</b> <br />";
	echo "version <b>" . $script_version . "</b><br />";
	exit;
}

session_start();

// Set no time limit only if php is not running in Safe Mode
if (!ini_get("safe_mode")) {
    @set_time_limit(0);
	if (((int)substr(ini_get("memory_limit"), 0, -1)) < 512) {
		ini_set("memory_limit", "512M");
	}
}

ignore_user_abort();
error_reporting(E_ALL^E_NOTICE);

$_SVR = array();

# If you use a default configuration you need to do place this file in your /catalog/ directory. 
# Otherwise if you place this file in another directory please modify the line below with the path to the catalog directory. 

##### Include configuration files ################################################

$site_base_path = "./";

// Include required files
if(file_exists($site_base_path . "wp-load.php")) {
	require_once($site_base_path . 'wp-load.php');
}
else {
	exit('<HTML><HEAD><TITLE>404 Not Found</TITLE></HEAD><BODY><H1>Not Found</H1>Please ensure that datafeed_shopmania_eshop.php is in the root directory</BODY></HTML>');
}

####################################################################

# Once all is set up you need to check the result and make sure the output is correct
# Point the browser to http://www.example.com/path_to_datafeed/shopmania_datafeed.php and look into the source code of the out put
# What you need to see is something like this
# Category | Manufacturer | Part Number | Merchant Code | Product Name | Product Description | Product URL | Product Image URL | Product Price | Currency 

##### Avoid any modifications below this line #####

// Datafeed specific settings
$datafeed_separator = "|"; // Possible options are \t or |


##### Extract params from url ################################################

$apply_taxes = (@$_GET['taxes'] == "off") ? "off" : "on";
$apply_storetaxes = (@$_GET['storetaxes'] == "off") ? "off" : "on";
$apply_discount = (@$_GET['discount'] == "off") ? "off" : "on";
$apply_special = (@$_GET['special'] == "off") ? "off" : "on";
$add_vat = (@$_GET['add_vat'] == "on") ? "on" : "off";
$vat_value = (@$_GET['vat_value'] > 0) ? ((100 + $_GET['vat_value']) / 100) : 1.24; // default value
$add_shipping = (@$_GET['shipping'] == "off") ? "off" : "on";
$add_availability = (@$_GET['availability'] == "off") ? "off" : "on";
$add_gtin = (@$_GET['gtin'] == "off") ? "off" : "on";
$add_tagging = (@$_GET['add_tagging'] == "off") ? "off" : "on";
$tagging_params = (@$_GET['tagging_params'] != "") ? urldecode($_GET['tagging_params']) : "utm_source=shopmania&utm_medium=cpc&utm_campaign=direct_link";
$show_description = (@$_GET['description'] == "off") ? "off" : ((@$_GET['description'] == "limited") ? "limited" : "on");
$show_image = (@$_GET['image'] == "off") ? "off" : "on";
$show_specialprice = (@$_GET['specialprice'] == "off") ? "off" : "on";
$sef = (@$_GET['sef'] == "on") ? "on" : "off";
$on_stock_only = (@$_GET['on_stock'] == "on") ? "on" : "off";
$force_path = (@$_GET['forcepath'] == "on") ? "on" : "off";
$force_folder = (@$_GET['forcefolder'] != "") ? $_GET['forcefolder'] : "";
$language_code = (@$_GET['language'] != "") ? $_GET['language'] : "";
$language_id = (@$_GET['language_id'] != "") ? $_GET['language_id'] : "";
$currency = (@$_GET['currency'] != "") ? $_GET['currency'] : "";
$use_compression = (@$_GET['compression'] == "off") ? "off" : "on";
$display_currency = (@$_GET['display_currency'] != "") ? $_GET['display_currency'] : "";
$limit = (@$_GET['limit'] > 0) ? $_GET['limit'] : "";
$show_combinations = (@$_GET['combinations'] == "on") ? "on" : "off";
$show_attribute = (@$_GET['attribute'] == "on") ? "on" : "off";

####################################################################

// Print URL options
if (@$_GET['get'] == "options") {
	$script_basepath = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	
	echo "<b>Datafeed WP WooCommerce</b> <br />";
	echo "version <b>" . $script_version . "</b><br /><br /><br />";

	//echo "<b>Taxes options</b> - possible values on, off default value on<br />";
	//echo "taxes=on (on,off) <a href=\"" . $script_basepath . "?taxes=off" . "\" >" . $script_basepath . "?taxes=off" . "</a><br /><br />";
	
	//echo "<b>Store taxes options</b> - possible values on, off default value on<br />";
	//echo "storetaxes = on (on,off) <a href=\"" . $script_basepath . "?storetaxes=off" . "\" >" . $script_basepath . "?storetaxes=off" . "</a><br /><br />";
	
	//echo "<b>Discount options</b> - possible values on, off default value on<br />";
	//echo "discount=on (on,off) <a href=\"" . $script_basepath . "?discount=off" . "\" >" . $script_basepath . "?discount=off" . "</a><br /><br />";
	
	echo "<b>Add VAT to prices</b> - possible values on, off default value off<br />";
	echo "add_vat=off (on,off) <a href=\"" . $script_basepath . "?add_vat=on" . "\" >" . $script_basepath . "?add_vat=on" . "</a><br /><br />";
		
	echo "<b>VAT value</b> - possible values percent value default value 24  - interger or float number ex 19 or 19.5<br />";
	echo "vat_value=24 (VAT_VALUE) <a href=\"" . $script_basepath . "?add_vat=on&vat_value=19" . "\" >" . $script_basepath . "?add_vat=on&vat_value=19" . "</a><br /><br />";
		
	//echo "<b>Add shipping to datafeed</b> - possible values on, off default value off<br />";
	//echo "shipping=off (on,off) <a href=\"" . $script_basepath . "?shipping=off" . "\" >" . $script_basepath . "?shipping=off" . "</a><br /><br />";
	
	echo "<b>Add shipping to datafeed</b> - possible values on, off default value off<br />";
	echo "shipping=on (on,off) <a href=\"" . $script_basepath . "?shipping=off" . "\" >" . $script_basepath . "?shipping=off" . "</a><br /><br />";
	
	echo "<b>Add availability to datafeed</b> - possible values on, off default value on<br />";
	echo "availability=on (on,off) <a href=\"" . $script_basepath . "?availability=off" . "\" >" . $script_basepath . "?availability=off" . "</a><br /><br />";
	
	//echo "<b>Add GTIN to datafeed</b> - possible values on, off default value on<br />";
	//echo "gtin=on (on,off) <a href=\"" . $script_basepath . "?gtin=off" . "\" >" . $script_basepath . "?gtin=off" . "</a><br /><br />";
	
	echo "<b>Add GA Tagging to product URL</b> - possible values on, off default value on<br />";
	echo "add_tagging=on (on,off) <a href=\"" . $script_basepath . "?add_tagging=off" . "\" >" . $script_basepath . "?add_tagging=off" . "</a><br /><br />";
	
	echo "<b>Add custom Tagging to product URL</b> - possible values url_encode(TAGGING_PARAMS) default value tagging_params=utm_source=shopmania&utm_medium=cpc&utm_campaign=direct_link<br />";
	echo "tagging_params=utm_source=shopmania&utm_medium=cpc&utm_campaign=direct_link (TAGGING_PARAMS) <a href=\"" . $script_basepath . "?tagging_params=from%3Dshopmania" . "\" >" . $script_basepath . "?tagging_params=from%3Dshopmania" . "</a><br /><br />";
	
	echo "<b>Show only products in stock</b> - possible values on, off default value off<br />";
	echo "on_stock=off (on,off) <a href=\"" . $script_basepath . "?on_stock=on" . "\" >" . $script_basepath . "?on_stock=on" . "</a><br /><br />";
	
	echo "<b>Display Description options</b> - possible values on, off, limited on<br />";
	echo "<ul><li><b>description=off</b> - do not display descriptions<br/ > <a href=\"" . $script_basepath . "?description=off" . "\" >" . $script_basepath . "?description=off" . "</a></li>";
	echo "<li><b>description=limited</b> - display limited descriptions (descriptions limited to 300 chars)<br/ > <a href=\"" . $script_basepath . "?description=limited" . "\" >" . $script_basepath . "?description=limited" . "</a></li></ul>";
	

	//echo "<b>Display image options</b> - possible values on, off default value on<br />";
	//echo "image=on (on,off) <a href=\"" . $script_basepath . "?image=off" . "\" >" . $script_basepath . "?image=off" . "</a><br /><br />";
	
	//echo "<b>Special price options</b> - possible values on, off default value on<br />";
	//echo "specialprice=on (on,off) <a href=\"" . $script_basepath . "?specialprice=off" . "\" >" . $script_basepath . "?specialprice=off" . "</a><br /><br />";
	
	//echo "Get prices in specified currency - possible values USD,EUR etc. <br />";
	//echo "currency=DEFAULT_CURRENCY <a href=\"" . $script_basepath . "?currency=EUR" . "\" >" . $script_basepath . "?currency=EUR" . "</a><br /><br />";
	
	//echo "Get texts in specified language code - possible values en,ro etc. <br />";
	//echo "language=DEFAULT_LANGUAGE_CODE <a href=\"" . $script_basepath . "?language=en" . "\" >" . $script_basepath . "?language=en" . "</a><br /><br />";
	
	//echo "Get texts in specified language id - possible values 1,2 etc. <br />";
	//echo "language_id=DEFAULT_LANGUAGE_ID <a href=\"" . $script_basepath . "?language_id=1" . "\" >" . $script_basepath . "?language_id=1" . "</a><br /><br />";
	
	echo "<b>Display currency code</b> - force the display of certain currency code, possible values USD,EUR etc. <br />";
	echo "display_currency=DEFAULT_CURRENCY <a href=\"" . $script_basepath . "?display_currency=EUR" . "\" >" . $script_basepath . "?display_currency=EUR" . "</a><br /><br />";
		
	echo "<b>Use compression</b> - possible values on, off default value on<br />";
	echo "compression=on (on,off) <a href=\"" . $script_basepath . "?compression=off" . "\" >" . $script_basepath . "?compression=off" . "</a><br /><br />";
	
	echo "<b>Get feed paginated</b> - possible values 1,2,..  etc. <br />";
	echo "pg=PAGE <a href=\"" . $script_basepath . "?pg=1" . "\" >" . $script_basepath . "?pg=1" . "</a><br />";
	echo "pg=PAGE&limit=PAGE_SIZE <a href=\"" . $script_basepath . "?pg=1&limit=100" . "\" >" . $script_basepath . "?pg=1&limit=100" . "</a><br /><br />";
	
	echo "<b>Limit displayed products</b> - possible values integer <br />";
	echo "limit=no_limit <a href=\"" . $script_basepath . "?limit=10" . "\" >" . $script_basepath . "?limit=10" . "</a><br /><br />";
	
	echo "<b>Use category group</b> - possible values int <br />";
	echo "cat_group=all (int) <a href=\"" . $script_basepath . "?cat_group=1" . "\" >" . $script_basepath . "?cat_group=1" . "</a><br /><br />
	<a href=\"" . $script_basepath . "?cat_group=2" . "\" >" . $script_basepath . "?cat_group=2" . "</a><br /><br />";
		
	echo "<b>Display product combinations</b> - possible values on, off default value on<br />";
	echo "combinations=off (on,off) <a href=\"" . $script_basepath . "?combinations=on" . "\" >" . $script_basepath . "?combinations=on" . "</a><br /><br />";
	
	echo "<b>Display product attributess</b> - possible values on, off default value off<br />";
	echo "attribute=off (on,off) <a href=\"" . $script_basepath . "?attribute=on" . "\" >" . $script_basepath . "?attribute=on" . "</a><br /><br />";
	
	exit;
}

##### Extract product keys, options from database ###############################################

// Force displayed currency
$datafeed_currency = ($display_currency != "") ? $display_currency : get_option('woocommerce_currency');

global $wpdb, $wp_query, $post, $woocommerce;

if (isset($_GET['pg']) && @$_GET['pg'] > 0) {
	$_pg = $_GET['pg'];
	$_step = ($limit > 0) ? $limit : 1000;
	$_start = ($_GET['pg'] - 1) * $_step;
	$_start = ($_start >= 0) ? $_start : 0;
	$_end = $_start + $_step - 1;
}
elseif ($limit > 0) {
	$_step = $limit;
	$_start = 0;
}
else {
	$_start = "";
	$_step = "99999";
	$_pg = 1;
}

// Extract products
$args = array("posts_per_page" => $_step, "post_status" => "publish", "post_type" => "product", "paged" => $_pg);
$products = query_posts($args); 
global $product;
foreach ($products as $post) {
	setup_postdata($post);
	$product_data = get_post_custom( $post->ID );
	$product_data['meta'] = maybe_unserialize( $product_data );
	if ($show_attribute == "on") {
		$ATTRIBUTE = maybe_unserialize( get_post_meta( $post->ID, '_product_attributes', true ) );
		$attr = "";
		$ATTR_VAL = array();
		foreach ($ATTRIBUTE as $i=>$v) {
			$names = wp_get_post_terms($post->ID, $v['name']);
			$ATTR_VALUES = array();
			foreach ($names as $ii) {
				$attr_name = array();
				$attr_name = explode("_", $v['name'], 2);
				$name = ucfirst($attr_name[1]);
				$ATTR_VALUES[$name][$ii->term_id] = $ii->name;
			}
			
			foreach ($ATTR_VALUES as $iii=>$vvv) {
				$ATTR_VAL[$iii] = join (", ", array_values($vvv));
			}
		}
		$tmp = array();
		foreach ($ATTR_VAL as $i=>$v) {
			$tmp[] = $i . ": " . $v;
		}
		$attr = join("; ", array_values($tmp));
	}
	if ($show_combinations == "on") {
		$COMB_ARR = array();
		if ($product->product_type == "variable") {
			$variations = $product->get_available_variations();
			foreach ($variations as $combination) {
				if ($combination['variation_is_visible'] == "1") {
					$COMB_ARR[$combination['variation_id']]['name'] = join(", ", array_values($combination['attributes']));
					$COMB_ARR[$combination['variation_id']]['variation_id'] = $combination['variation_id'];
					$COMB_ARR[$combination['variation_id']]['price'] = $combination['display_price'];
					$COMB_ARR[$combination['variation_id']]['sku'] = $combination['sku'];
					$COMB_ARR[$combination['variation_id']]['quantity'] = $combination['is_in_stock'];
				}
			}
		}
	}
	
	// Show only on stock products
	if ($on_stock_only == "on") {
		if (($product_data['meta']['_manage_stock'][0] == "yes" && $product_data["_stock_status"][0] != "instock") || $product_data['meta']['_manage_stock'][0] != "yes") {
			continue;
		}
	}

	$prod_name = $post->post_title;
	
	if ($show_description == "limited") {
		$prod_desc = substr($post->post_content, 0, 600);
		$prod_desc = smfeed_replace_not_in_tags("\n", "<BR />", $prod_desc);
		$prod_desc = strip_tags($prod_desc);
		$prod_desc = substr($prod_desc, 0, 300);
	}
	else {
		if ($show_description == "on") {
			// Get description
			$prod_desc = $post->post_content;
		}
		else {
			$prod_desc = "";
		}
	}
	
	$prod_id = $post->ID;
	$prod_url = esc_url( get_permalink($post->ID) );
	$prod_price = $product_data['meta']['_price'][0];

	$img_url = "";
	if (get_post_thumbnail_id($post->ID)) {
		$img_url_arr = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "large");
		$img_url = ($img_url_arr[0] != "") ? $img_url_arr[0] : "";
	}

	// Get category
	$terms = wp_get_object_terms( $post->ID, 'product_cat' );

	$cat_name = "";
	
	// Init max cat parents
	$max_parents = -1;
	foreach ($terms as $term) {
		$this_cat_name = "";
		
		$parents = array();
		$parent = $term->parent;

		while ($parent) {
			$parents[] = $parent;
			$new_parent = get_term_by( 'id', $parent, 'product_cat');
			$parent = $new_parent->parent;
			
		}

		// Display cat with max parents
		if (count($parents) > $max_parents) {
			$max_parents = count($parents);
			
			if(!empty($parents)) {
				$parents = array_reverse($parents);
				foreach ($parents as $parent) {
					$item = get_term_by( 'id', $parent, 'product_cat');
					$this_cat_name = $this_cat_name . $item->name . " > ";
				}
			}

			$cat_name = $this_cat_name . $term->name;
		}

	}

	// Add VAT to prices
	if ($add_vat == "on") {
		$prod_price = $prod_price * $vat_value;
	}


	// Add GA Tagging parameters to url
	if ($add_tagging == "on") {
		$and_param = (preg_match("/\?/", $prod_url)) ? "&" : "?";
		$prod_url = $prod_url . $and_param . $tagging_params;
	}

	// Clean product name (new lines)	
	$prod_name = str_replace("\n", " ", strip_tags($prod_name));		
	$prod_name = str_replace("\r", "", strip_tags($prod_name));
	$prod_name = str_replace("\t", " ", strip_tags($prod_name));
	
	// Clean product description (Replace new line with <BR>). In order to make sure the code does not contains other HTML code it might be a good ideea to strip_tags()	
	//$prod_desc = str_replace("\n", "<BR>", $prod_desc);
	$prod_desc = smfeed_replace_not_in_tags("\n", "<BR />", $prod_desc);
	$prod_desc = str_replace("\n", " ", $prod_desc);
	$prod_desc = str_replace("\r", "", $prod_desc);
	$prod_desc = str_replace("\t", " ", $prod_desc);
	
	// Clean product names and descriptions (separators)
	if ($datafeed_separator == "\t") {
		$cat_name = str_replace("\t", " ", $cat_name);
		// Continue... tabs were already removed
	}
	elseif ($datafeed_separator == "|") {
		$prod_name = str_replace("|", " ", strip_tags($prod_name));
		$prod_desc = str_replace("|", " ", $prod_desc);
		$cat_name = str_replace("|", " ", $cat_name);
	}
	else {
		print "Incorrect columns separator.";
		exit;			
	}
	
	// Build stock conditions
	if ($product_data['meta']['_manage_stock'][0] == "yes" && $add_availability == "on") {
		if ($product_data["_stock_status"][0] == "instock") {
			$availability = "In stock";
		}
		else {
			$availability = "Out of stock";
		}
	}
	elseif($add_availability == "on") {
		$availability = "";
	}
	else {
		$availability = "";
	}	
	
	// Add Shipping
	$shipping = "";
	$shipping_value = ($add_shipping == "on") ? $shipping : "";
	
	// Add gtin
	$gtin = "";
	
	// Output the datafeed content
	// Category, Manufacturer, Model, ProdCode, ProdName, ProdDescription, ProdURL, ImageURL, Price, Currency, Shipping value, Availability, GTIN (UPC/EAN/ISBN) 
	if ($show_combinations == "on" && is_array($COMB_ARR) && sizeof($COMB_ARR) > 0) {
		foreach ($COMB_ARR AS $k => $combination) {
			if ($combination['quantity'] == "1") {				
				$availability = "In stock";
			}
			else {
				$availability = "Out of stock";
			}

			print  
			$cat_name . $datafeed_separator . 
			$basebrand . $datafeed_separator . 
			$basempn . $datafeed_separator . 
			$prod_id . "_". $combination['variation_id'] . $datafeed_separator . 
			$prod_name . ", " . $combination['name'] . $datafeed_separator . 
			$prod_desc . $datafeed_separator . 
			$prod_url . $datafeed_separator . 
			$img_url . $datafeed_separator . 
			$combination['price'] . $datafeed_separator . 
			$datafeed_currency . $datafeed_separator .
			$shipping_value . $datafeed_separator .
			$availability . $datafeed_separator . (($show_attribute == "on") ? $attr . $datafeed_separator . $gtin : $gtin ) . 
			$gtin . "\n";
		}
	}
	else {
		print  
		$cat_name . $datafeed_separator . 
		$basebrand . $datafeed_separator . 
		$basempn . $datafeed_separator . 
		$prod_id . $datafeed_separator . 
		$prod_name . $datafeed_separator . 
		$prod_desc . $datafeed_separator . 
		$prod_url . $datafeed_separator . 
		$img_url . $datafeed_separator . 
		$prod_price . $datafeed_separator . 
		$datafeed_currency . $datafeed_separator .
		$shipping_value . $datafeed_separator .
		$availability . $datafeed_separator . (($show_attribute == "on") ? $attr . $datafeed_separator . $gtin : $gtin ) . 
		"\n";
	}
	
}

exit;

###################################################################


##### Functions ########################################################

function smfeed_html_to_text($string){

	$search = array (
		"'<script[^>]*?>.*?</script>'si",  // Strip out javascript
		"'<[\/\!]*?[^<>]*?>'si",  // Strip out html tags
		"'([\r\n])[\s]+'",  // Strip out white space
		"'&(quot|#34);'i",  // Replace html entities
		"'&(amp|#38);'i",
		"'&(lt|#60);'i",
		"'&(gt|#62);'i",
		"'&(nbsp|#160);'i",
		"'&(iexcl|#161);'i",
		"'&(cent|#162);'i",
		"'&(pound|#163);'i",
		"'&(copy|#169);'i",
		"'&(reg|#174);'i",
		"'&#8482;'i",
		"'&#149;'i",
		"'&#151;'i"
		);  // evaluate as php
	
	$replace = array (
		" ",
		" ",
		"\\1",
		"\"",
		"&",
		"<",
		">",
		" ",
		"&iexcl;",
		"&cent;",
		"&pound;",
		"&copy;",
		"&reg;",
		"<sup><small>TM</small></sup>",
		"&bull;",
		"-",
		);
	
	$text = preg_replace ($search, $replace, $string);
	return $text;
	
}

function smfeed_clean_description($string){

	$search = array (
		"'<html>'i",
		"'</html>'i",
		"'<body>'i",
		"'</body>'i",
		"'<head>.*?</head>'si",
		"'<!DOCTYPE[^>]*?>'si"
		); 
		
	$replace = array (
		"",
		"",
		"",
		"",
		"",
		""
		); 
		
	$text = preg_replace ($search, $replace, $string);
	return $text;

}

function smfeed_replace_not_in_tags($find_str, $replace_str, $string) {
	
	$find = array($find_str);
	$replace = array($replace_str);	
	preg_match_all('#[^>]+(?=<)|[^>]+$#', $string, $matches, PREG_SET_ORDER);
	if (is_array($matches) && sizeof($matches) > 0) {	
		foreach ($matches as $val) {	
			if (trim($val[0]) != "") {
				$string = str_replace($val[0], str_replace($find, $replace, $val[0]), $string);
			}
		}
	}
	return $string;
}

function smfeed_compression_start(){

	global $_SERVER, $_SVR;	
	$_SVR['NO_END_COMPRESSION'] = false;
	$_SVR['IDX_DO_GZIP_COMPRESS'] = false;
	
	// We have headers already sent so we cannot start the compression
	if (headers_sent()) {
		$_SVR['NO_END_COMPRESSION'] = true;
		return false;
	}
	
	$idx_phpver = phpversion();
	$useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;
	if ($idx_phpver >= "4.0.4pl1" && (strstr($useragent, "compatible") || strstr($useragent, "Gecko"))) {
		if (extension_loaded("zlib"))	{
			// SET COMPRESSION LEVEL
			ini_set("zlib.output_compression_level", 5);
			ob_start("ob_gzhandler");
		}
	}
	elseif ($idx_phpver > "4.0") {
	
		if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) {
		
			if (extension_loaded("zlib")) {
			
				// SET COMPRESSION LEVEL
				ini_set("zlib.output_compression_level", 5);
				$_SVR['IDX_DO_GZIP_COMPRESS'] = true;
				ob_start();
				ob_implicit_flush(0);
				header("Content-Encoding: gzip");
			}			
		}
	}
}

function smfeed_compression_end(){

	global $_SERVER, $_SVR;
	
	// We have not started the compression as we have headers already sent
	if ($_SVR['NO_END_COMPRESSION']) {
		return false;
	}
	// COMPRESS BUFFERED OUTPUT IF REQUIRED AND SEND TO BROWSER
	if ($_SVR['IDX_DO_GZIP_COMPRESS']) {
		$gzip_contents = ob_get_contents();
		ob_end_clean();
		$gzip_size = strlen($gzip_contents);
		$gzip_crc = crc32($gzip_contents);
		$gzip_contents = gzcompress($gzip_contents, 9);
		$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);
		print "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		print $gzip_contents;
		print pack("V", $gzip_crc);
		print pack("V", $gzip_size);
	}
}

###################################################################

if ($use_compression == "on") {
	// Start compressing
	smfeed_compression_end();
}

session_destroy();

exit;

?>
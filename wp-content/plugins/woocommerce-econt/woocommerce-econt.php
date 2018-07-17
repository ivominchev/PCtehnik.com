<?php
/*
Plugin Name: Econt Express - WooCommerce
Plugin URI: http://mreja.net
Description: Integrate Econt Express in WooCommerce and ship your goods.
Version: 1.0.0
Author: Martin Vasilev
Author URI: http://mreja.net
Text Domain: woocommerce-econt
Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


global $wpdb;

if (!defined('ECONT_PLUGIN_DIR'))
    define( 'ECONT_PLUGIN_DIR', dirname(__FILE__) );

if (!defined('ECONT_PLUGIN_ROOT_PHP'))
    define( 'ECONT_PLUGIN_ROOT_PHP', dirname(__FILE__).'/'.basename(__FILE__)  );

if (!defined('ECONT_PLUGIN_ADMIN_DIR'))
    define( 'ECONT_PLUGIN_ADMIN_DIR', dirname(__FILE__) . '/admin' );

if (!defined('ECONT_PLUGIN_URL'))
    define( 'ECONT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


if (!defined('ECONT_VIEWS_TABLE'))
    define( 'ECONT_VIEWS_TABLE', $wpdb->prefix . 'fpd_views' );

if (!defined('ECONT_TEMPLATES_TABLE'))
    define( 'ECONT_TEMPLATES_TABLE', $wpdb->prefix . 'fpd_templates' );


if (!defined('MYPLUGIN_VERSION_KEY'))
    define('MYPLUGIN_VERSION_KEY', 'woocommerce-econt_version');

if (!defined('MYPLUGIN_VERSION_NUM'))
    define('MYPLUGIN_VERSION_NUM', '1.0.0');


if( !class_exists('Econt_Express') ) {

	class Econt_Express {

		const VERSION = '1.0.0';
		const ECONT_VERSION = '1.0.0';
		const CAPABILITY = "edit_econt_express";
		const DEMO = false;

		public function __construct() {

			require_once(ECONT_PLUGIN_ADMIN_DIR.'/class-sm-econt.php'); //adds shipping method econt to woocommerce settings 
			require_once(ECONT_PLUGIN_DIR.'/inc/class-mysql-econt.php'); //create econt tables and do all mysql queries
			require_once(ECONT_PLUGIN_DIR.'/inc/class-ajax-econt.php'); 
			require_once(ECONT_PLUGIN_DIR.'/inc/class-checkout-econt.php'); //class s funkcii dobavqshti meta poleta za ekon v checkout
			require_once(ECONT_PLUGIN_ADMIN_DIR.'/class-order-econt.php'); //order details create loading (ot tuk zarejdam i js i css scriptovete)

			add_action( 'plugins_loaded', array( &$this,'plugins_loaded' ) );
	//		add_action( 'init', array( &$this, 'init') );
			include_once dirname( __FILE__ ) . '/inc/class-mysql-econt.php';
			register_activation_hook( __FILE__, array( 'Econt_mySQL', 'createTables' ) );

		}

		public function plugins_loaded() {

			load_plugin_textdomain( 'woocommerce-econt', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

			if( !is_admin() ) {
//				require_once(ECONT_PLUGIN_DIR.'/inc/class-debug.php');
			}

		}
/*
		public function init() {

		}
*/
	}
}

new Econt_express();

?>
<?php
/**
 * Moove_Redirect_Actions File Doc Comment.
 *
 * @category  Moove_Redirect_Actions
 * @package   moove-404-redirect
 * @author    Gaspar Nemes
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly. ?>
<?php

/**
 * Moove_redirect_Actions Class Doc Comment.
 *
 * @category Class
 * @package  Moove_Redirect_Actions
 * @author   Gaspar Nemes
 */
class Moove_redirect_Actions {
	/**
	 * Global variable used in localization.
	 *
	 * @var array.
	 */
	var $redirect_loc_data;
	/**
	 * Construct
	 */
	function __construct() {
		$this->moove_register_scripts();
	}
	/**
	 * Register Front-end / Back-end scripts.
	 *
	 * @return void
	 */
	function moove_register_scripts() {
		if ( is_admin() ) :
			add_action( 'admin_enqueue_scripts', array( &$this, 'moove_redirect_admin_scripts' ) );
		endif;
	}

	/**
	 * Registe BACK-END Javascripts and Styles.
	 *
	 * @return void
	 */
	public function moove_redirect_admin_scripts() {
		wp_enqueue_script( 'moove_redirect_backend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/js/moove_redirect_backend.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_style( 'moove_redirect_backend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/css/moove_redirect_backend.css' );
	}
}
$moove_redirect_actions_provider = new Moove_Redirect_Actions();


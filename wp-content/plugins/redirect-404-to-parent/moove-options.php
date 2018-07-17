<?php
/**
 * Moove_Redirect_Options File Doc Comment
 *
 * @category Moove_Redirect_Options
 * @package   moove-404-redirect
 * @author    Gaspar Nemes
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

/**
 * Moove_redirect_Options Class Doc Comment
 *
 * @category Class
 * @package  Moove_Redirect_Options
 * @author   Gaspar Nemes
 */
class Moove_redirect_Options {
	/**
	 * Global options
	 *
	 * @var array
	 */
	private $options;
	/**
	 * Construct
	 */
	function __construct() {
		add_action( 'admin_menu', array( &$this, 'moove_redirect_admin_menu' ) );
	}

	/**
	 * Moove redirect settings page added to settings
	 *
	 * @return  void
	 */
	function moove_redirect_admin_menu() {
		add_options_page(
			'Set up a redirect rule',
			'Moove redirect 404',
			'manage_options',
			'moove-redirect-settings',
			array( &$this, 'moove_redirect_settings_page' )
		);
	}

	/**
	 * Settings page registration
	 *
	 * @return void
	 */
	function moove_redirect_settings_page() {
		$this->options = get_option( 'moove_options' );
		$data = array( 'options' => $this->options );
		echo Moove_Redirect_View::load( 'moove.admin.settings.settings_page', $data );
	}
}
$moove_redirect_options_provider = new Moove_Redirect_Options();

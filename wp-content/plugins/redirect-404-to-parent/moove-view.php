<?php
/**
 * Moove_Redirect_View File Doc Comment
 *
 * @category Moove_Redirect_View
 * @package   moove-404-redirect
 * @author    Gaspar Nemes
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}
/**
 * Moove_Redirect_View Class Doc Comment
 *
 * @category Class
 * @package  Moove_Redirect_View
 * @author   Gaspar Nemes
 */
class Moove_Redirect_View {
	/**
	 * Load and update view
	 *
	 * Parameters:
	 *
	 * @param string $view // the view to load, dot used as directory separator, no file extension given.
	 * @param mixed  $data // The data to display in the view (could be anything, even an object).
	 */
	public static function load( $view, $data ) {
		$view_file_origin = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'views';
		$view_name = str_replace( '.' , DIRECTORY_SEPARATOR , $view ) . '.php';
		if ( locate_template( 'moove-externals' . DIRECTORY_SEPARATOR . $view_name ) ) :
			$view_file_origin = get_template_directory() . DIRECTORY_SEPARATOR . 'moove-externals';
		endif;
		ob_start();
		include $view_file_origin . DIRECTORY_SEPARATOR . $view_name;
		return ob_get_clean();
	}
}

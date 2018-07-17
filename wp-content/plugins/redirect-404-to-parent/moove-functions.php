<?php
/**
 * Moove_Functions File Doc Comment
 *
 * @category Moove_Functions
 * @package   moove-404-redirect
 * @author    Gaspar Nemes
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}
add_action( 'admin_menu', 'moove_redirect_create_csv' );
/**
 * Create a downloadable csv file with logs.
 */
function moove_redirect_create_csv() {
	$export_log = isset(  $_GET['download_csv'] ) ? sanitize_text_field( wp_unslash( $_GET['download_csv'] ) ) : '';
	if ( isset( $export_log ) && '' !== $export_log ) :
		$stats = json_decode( get_option( 'moove_404_redirect_statistics' ), true );
		if ( isset( $stats[ $export_log ] ) ) :
			$log_array = array_reverse( $stats[ $export_log ] );

			/** Open raw memory as file, no need for temp files, be careful not to run out of memory thought. */
			$f = fopen( 'php://memory', 'w' );
			/** Loop through array.  */
			$array_keys = array( 'Date/Time', 'IP_Address', 'City', 'Target_URL' );
			fputcsv( $f, $array_keys );
			foreach ( $log_array as $line ) {
				/** Default php csv handler. */
				fputcsv( $f, $line, ',' );
			}
			/** Rewrind the "file" with the csv lines. */
			fseek( $f, 0 );
			/** Modify header to be downloadable csv file. */
			header( 'Content-Type: application/csv' );
			header( 'Content-Disposition: attachement; filename="' . '404-redirect-log.csv' . '";' );
			/** Send file to browser for download. */
			fpassthru( $f );
			exit;
		endif;
	endif;
}

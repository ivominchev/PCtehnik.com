<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/**
 * 	Contributors: mooveagency, gaspar.nemes
 *  Plugin Name: Redirect 404 to parent
 *  Plugin URI: http://www.mooveagency.com
 *  Description: This plugin helps you define redirect rules that will redirect any 404 request under a defined URL base to the parent URL base.
 *  Version: 1.0.1
 *  Author: Moove Agency
 *  Author URI: http://www.mooveagency.com
 *  License: GPLv2
 *  Text Domain: moove
 */

register_activation_hook( __FILE__ , 'moove_redirect_activate' );
register_deactivation_hook( __FILE__ , 'moove_redirect_deactivate' );

/**
 * Functions on plugin activation, create relevant pages and defaults for settings page.
 */
function moove_redirect_activate() {
    $activate = get_option( 'moove_404_redirect_activate' );
    if ( ! $activate ) :
        update_option( 'moove_404_redirect_activate', false );
    endif;
}


/**
 * Function on plugin deactivation. It removes the pages created before.
 */
function moove_redirect_deactivate() {
    $activate = get_option( 'moove_404_redirect_activate' );
    if ( ! $activate ) :
        update_option( 'moove_404_redirect_options', null );
        update_option( 'moove_404_redirect_statistics', null );
    endif;
}

include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-view.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-options.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-controller.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-actions.php' );
include_once( dirname( __FILE__ ).DIRECTORY_SEPARATOR.'moove-functions.php' );


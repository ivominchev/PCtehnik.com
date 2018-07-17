<?php
/*
Plugin Name: Paypal BGN support
Description: Plugin description here.
Author: Nikolay Nikolov
Version: 1.0.0
*/

/////////////////////BEGIN segment 1
//this segment I got from here and changed it a little: http://devseon.com/en/wordpress-tutorials/woocommerce-add-a-paypal-unsupported-currency
//it lies to PayPal that the BGN currency is supported, and converts it to EUR when the payment is made
//it does not change the currency in the WooCommerce order, it remains the same, and WooCommerce detects an error and it puts the order on hold
//but we fix that in segment 3

add_filter( 'woocommerce_paypal_supported_currencies', 'pbte_add_bgn_paypal_valid_currency' );

function pbte_add_bgn_paypal_valid_currency( $currencies )
{
    array_push ( $currencies , 'BGN' );
    return $currencies;
}

add_filter('woocommerce_paypal_args', 'pbte_convert_bgn_to_eur');

function pbte_convert_bgn_to_eur($paypal_args)
{
    if ( $paypal_args['currency_code'] == 'BGN')
    {
        $convert_rate = get_option('pbte_eur_to_bgn_rate'); //set the converting rate
        $paypal_args['currency_code'] = 'EUR'; //change BGN to EUR
        $i = 1;

        while (isset($paypal_args['amount_' . $i]))
        {
            $paypal_args['amount_' . $i] = round( $paypal_args['amount_' . $i] / $convert_rate, 2);
            ++$i;
        }
    }
    return $paypal_args;
}

/////////////////////END segment 1


/////////////////////BEGIN segment 2
//I made this segment so the exchange rate is updated automatically with a wordpress cron job twice daily

//runs on plugin activation
register_activation_hook( plugin_dir_path( __FILE__ )."paypal-bgn-to-euro.php", 'pbte_activate_plugin' );

//runs on plugin deactivation
register_deactivation_hook( plugin_dir_path( __FILE__ )."paypal-bgn-to-euro.php", 'pbte_deactivate_plugin' );

//when the cron job runs, we call a function to update the exchange rate option value
add_action('pbte_twicedaily_check_eur_event', 'pbte_update_eur_rate_option');

//runs on plugin activation
function pbte_activate_plugin()
{
    pbte_update_eur_rate_option();  //we update the exchange rate option

    if (!wp_next_scheduled('pbte_twicedaily_check_eur_event'))  //adds an cron job (if it is not already added) to udpate the exchange rate twice daily
        wp_schedule_event(time(), 'twicedaily', 'pbte_twicedaily_check_eur_event');
}

//runs on plugin deactivation
function pbte_deactivate_plugin()
{
    wp_clear_scheduled_hook('pbte_twicedaily_check_eur_event'); //removes the cron job we added
}

//gets the exchange rate from a free api and updates our option
function pbte_update_eur_rate_option()
{
    $data = json_decode(file_get_contents("http://api.fixer.io/latest?symbols=BGN&base=EUR")); //gets the exchange rate from a free api
    if(!empty($data->rates->BGN))
    {
        if(get_option('pbte_eur_to_bgn_rate'))
            update_option('pbte_eur_to_bgn_rate', floatval($data->rates->BGN));
        else
            add_option('pbte_eur_to_bgn_rate', floatval($data->rates->BGN)); //if the option does not exist for some reason, we create it
    }
    else //something went wrong while getting the data from the api so we will email the admin
    {
        $message = "This is a message from ".get_site_url()
        .". There is a problem getting the API data in the plugin PayPal BGN support.";
        $subject = "Problem with Paypal BGN support";
        $to_email = get_bloginfo('admin_email');
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        wp_mail($to_email, $subject, $message, $headers);
    }
}

/////////////////////END segment 2


/////////////////////BEGIN segment 3
//Since the currencies do not match, WooCommerce puts the order on hold. We fix this with this segment.

//this runs when a new note is added to the order
add_filter( 'woocommerce_new_order_note_data', 'pbte_fix_order_status', 10, 2 );

//if the note says that the PayPal currencies or amounts do not match, then we will change the status to processing
function pbte_fix_order_status($a_note, $a_order)
{
    //the check is done in two languages
    if ( strpos($a_note['comment_content'],'PayPal валутите не съвпадат') !== false
    || strpos($a_note['comment_content'],'PayPal currencies do not match') !== false
    || strpos($a_note['comment_content'],'PayPal наличността не отговаря') !== false
    || strpos($a_note['comment_content'],'PayPal amounts do not match') !== false )
    {
        //we create the order var
        $order = new WC_Order($a_order['order_id']);
        //if the current status is on-hold - we change it to processing and add an optional note
        if($order->status == 'on-hold')
            $order->update_status('processing', 'The PayPal BGN support plugin did this note.');
    }

    return $a_note;
}

/////////////////////END segment 3

?>
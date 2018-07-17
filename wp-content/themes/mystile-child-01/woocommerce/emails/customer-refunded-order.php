<?php
/**
 * Customer refunded order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-refunded-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see      https://docs.woothemes.com/document/template-structure/
 * @author   WooThemes
 * @package  WooCommerce/Templates/Emails
 * @version  2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php
	if ( $partial_refund ) {
		printf( __( 'Hi there. Your order on %s has been partially refunded.', 'woocommerce' ), get_option( 'blogname' ) );
	}
	else {
		printf( __( 'Hi there. Your order on %s has been refunded.', 'woocommerce' ), get_option( 'blogname' ) );
	}
?></p>

<table>
	<td><h4><img src="https://pctehnik.com/wp-content/uploads/Signal_attention.png" alt="Внимание!!!" /></td>
	<td>Можете да преследите стауса на Вашата поръчка в <a href="https://pctehnik.com/magazin/prosledi-porachka/"> в страницата ни</a>, като въведете Вашият <?php _e( 'Email:', 'woocommerce' ); ?></strong> <?php echo $order->billing_email; ?> и номер на <?php echo __( 'Order:', 'woocommerce' ) . ' ' . $order->get_order_number(); ?>.</h4></td>
</table>

<?php

/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );

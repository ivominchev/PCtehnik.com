<?php
/*
* Generated By Orbisius Child Theme Creator - your favorite plugin for Child Theme creation :)
* https://wordpress.org/plugins/orbisius-child-theme-creator/
*
* Unlike style.css, the functions.php of a child theme does not override its counterpart from the parent.
* Instead, it is loaded in addition to the parent’s functions.php. (Specifically, it is loaded right before the parent theme's functions.php).
* Source: https://codex.wordpress.org/Child_Themes#Using_functions.php
*
* Be sure not to define functions, that already exist in the parent theme!
* A common pattern is to prefix function names with the (child) theme name.
* Also if the parent theme supports pluggable functions you can use function_exists( 'put_the_function_name_here' ) checks.
*/

/**
 * Add the field to order emails
 **/
add_filter('woocommerce_email_order_meta_keys', 'my_woocommerce_email_order_meta_keys');

function my_woocommerce_email_order_meta_keys( $keys ) {
	$keys['Оплакване'] = 'Оплакване';
  $keys['Проблем'] = 'Проблем';
  $keys['Решение'] = 'Решение';
  $keys['Устройство'] = 'Устройство';
	return $keys;
}

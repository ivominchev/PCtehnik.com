<?php
/**
 * WooCommerce Call for Price - General Section Settings
 *
 * @version 3.2.1
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Call_For_Price_Settings_General' ) ) :

class Alg_WC_Call_For_Price_Settings_General {

	/**
	 * Constructor.
	 *
	 * @version 3.0.0
	 */
	function __construct() {

		$this->id   = '';
		$this->desc = __( 'General', 'woocommerce-call-for-price' );

		add_filter( 'woocommerce_get_sections_alg_call_for_price',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_call_for_price_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_terms.
	 *
	 * @version 3.2.0
	 * @since   3.2.0
	 */
	function get_terms( $args ) {
		if ( ! is_array( $args ) ) {
			$_taxonomy = $args;
			$args = array(
				'taxonomy'   => $_taxonomy,
				'orderby'    => 'name',
				'hide_empty' => false,
			);
		}
		global $wp_version;
		if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
			$_terms = get_terms( $args );
		} else {
			$_taxonomy = $args['taxonomy'];
			unset( $args['taxonomy'] );
			$_terms = get_terms( $_taxonomy, $args );
		}
		$_terms_options = array();
		if ( ! empty( $_terms ) && ! is_wp_error( $_terms ) ){
			foreach ( $_terms as $_term ) {
				$_terms_options[ $_term->term_id ] = $_term->name;
			}
		}
		return $_terms_options;
	}

	/**
	 * get_settings.
	 *
	 * @version 3.2.1
	 */
	function get_settings() {

		$plugin_settings = array(
			array(
				'title'     => __( 'Call for Price Options', 'woocommerce-call-for-price' ),
				'type'      => 'title',
				'id'        => 'alg_wc_call_for_price_options',
			),
			array(
				'title'     => __( 'WooCommerce Call for Price', 'woocommerce-call-for-price' ),
				'desc'      => '<strong>' . __( 'Enable plugin', 'woocommerce-call-for-price' ) . '</strong>',
				'desc_tip'  => __( 'Create any custom price label for all WooCommerce products with empty price.', 'woocommerce-call-for-price' ) .
					'<p><a class="button" style="font-style: italic;" href="https://wpfactory.com/item/woocommerce-call-for-price-plugin/" target="_blank">' .
						__( 'Documentation', 'woocommerce-call-for-price' ) . '</a></p>',
				'id'        => 'alg_wc_call_for_price_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_call_for_price_options',
			),
		);

		$general_settings = array(
			array(
				'title'     => __( 'General Options', 'woocommerce-call-for-price' ),
				'type'      => 'title',
				'id'        => 'alg_wc_call_for_price_general_options',
			),
			array(
				'title'     => __( 'Per product', 'woocommerce-call-for-price' ),
				'desc'      => __( 'Enable', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'This will add new meta box to each product\'s admin edit page.', 'woocommerce-call-for-price' ) . ' ' .
					apply_filters( 'alg_call_for_price', '<br>' . sprintf( __( 'You will need %s plugin to enable "Per Product" option.', 'woocommerce-call-for-price' ),
						'<a target="_blank" href="https://wpfactory.com/item/woocommerce-call-for-price-plugin/">' .
							__( 'Call for Price for WooCommerce Pro', 'woocommerce-call-for-price' ) . '</a>' ), 'settings' ),
				'id'        => 'alg_wc_call_for_price_per_product_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_call_for_price', array( 'disabled' => 'disabled' ), 'settings' ),

			),
			array(
				'title'     => __( 'Sale tag', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'Hides sale tag for products with empty prices.', 'woocommerce-call-for-price' ),
				'desc'      => __( 'Hide', 'woocommerce-call-for-price' ),
				'id'        => 'alg_wc_call_for_price_hide_sale_sign',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Variations "add to cart" button', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'Hides disabled "add to cart" button for variations with empty prices.', 'woocommerce-call-for-price' ),
				'desc'      => __( 'Hide', 'woocommerce-call-for-price' ),
				'id'        => 'alg_wc_call_for_price_hide_variations_add_to_cart_button',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Button text', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'Changes add to cart "Read more" button text for "Call for Price" products on archives.', 'woocommerce-call-for-price' ),
				'desc'      => __( 'Enable', 'woocommerce-call-for-price' ),
				'id'        => 'alg_call_for_price_change_button_text',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'desc'      => __( 'Button text', 'woocommerce-call-for-price' ) .
					apply_filters( 'alg_call_for_price', '<br>' . sprintf( __( 'You will need %s plugin to change this text.', 'woocommerce-call-for-price' ),
						'<a target="_blank" href="https://wpfactory.com/item/woocommerce-call-for-price-plugin/">' .
							__( 'Call for Price for WooCommerce Pro', 'woocommerce-call-for-price' ) . '</a>' ), 'settings' ),
				'id'        => 'alg_call_for_price_button_text',
				'default'   => __( 'Call for Price', 'woocommerce-call-for-price' ),
				'type'      => 'text',
				'custom_attributes' => apply_filters( 'alg_call_for_price', array( 'readonly' => 'readonly' ), 'settings' ),
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_call_for_price_general_options',
			),
		);

		$price_step = 1 / pow( 10, get_option( 'woocommerce_price_num_decimals', 2 ) );
		$force_settings = array(
			array(
				'title'     => __( 'Force Products "Call for Price"', 'woocommerce-call-for-price' ),
				'desc'      => __( 'By default only products with empty price display "Call for Price" labels, however you can additionally force products with not empty price to display "Call for Price" label also.', 'woocommerce-call-for-price' ),
				'id'        => 'alg_call_for_price_make_options',
				'type'      => 'title',
			),
			array(
				'title'     => __( 'All products', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'Makes all your shop\'s products "Call for Price".', 'woocommerce-call-for-price' ),
				'desc'      => __( 'Enable', 'woocommerce-call-for-price' ),
				'id'        => 'alg_call_for_price_make_all_empty',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( '"Out of stock" products', 'woocommerce-call-for-price' ),
				'desc'      => __( 'Enable', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'Makes "Call for Price" for all products that can not be purchased (not "in stock" or "on backorder" stock statuses).', 'woocommerce-call-for-price' ) .
					apply_filters( 'alg_call_for_price', '<br>' . sprintf( __( 'You will need %s plugin to enable this option.', 'woocommerce-call-for-price' ),
						'<a target="_blank" href="https://wpfactory.com/item/woocommerce-call-for-price-plugin/">' .
							__( 'Call for Price for WooCommerce Pro', 'woocommerce-call-for-price' ) . '</a>' ), 'settings' ),
				'id'        => 'alg_call_for_price_make_out_of_stock_empty_price',
				'default'   => 'no',
				'type'      => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_call_for_price', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'     => __( 'Per product taxonomy', 'woocommerce-call-for-price' ),
				'desc'      => __( 'Enable', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'Makes "Call for Price" for all products from selected product categories and/or product tags.', 'woocommerce-call-for-price' ),
				'id'        => 'alg_call_for_price_make_empty_price_per_taxonomy',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'desc'      => __( 'Product categories', 'woocommerce-call-for-price' ),
				'id'        => 'alg_call_for_price_make_empty_price_product_cat',
				'default'   => '',
				'type'      => 'multiselect',
				'class'     => 'chosen_select',
				'options'   => $this->get_terms( 'product_cat' ),
			),
			array(
				'desc'      => __( 'Product tags', 'woocommerce-call-for-price' ),
				'id'        => 'alg_call_for_price_make_empty_price_product_tag',
				'default'   => '',
				'type'      => 'multiselect',
				'class'     => 'chosen_select',
				'options'   => $this->get_terms( 'product_tag' ),
			),
			array(
				'title'     => __( 'By product price', 'woocommerce-call-for-price' ),
				'desc'      => __( 'Enable', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'Makes "Call for Price" for all products in selected price range.', 'woocommerce-call-for-price' ),
				'id'        => 'alg_call_for_price_make_empty_price_by_product_price',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'desc'      => __( 'Min price', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'Ignored, if set set to zero.', 'woocommerce-call-for-price' ),
				'id'        => 'alg_call_for_price_make_empty_price_min_price',
				'default'   => 0,
				'type'      => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => $price_step ),
			),
			array(
				'desc'      => __( 'Max price', 'woocommerce-call-for-price' ),
				'desc_tip'  => __( 'Ignored, if set set to zero.', 'woocommerce-call-for-price' ),
				'id'        => 'alg_call_for_price_make_empty_price_max_price',
				'default'   => 0,
				'type'      => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => $price_step ),
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_call_for_price_make_options',
			),
		);

		return array_merge( $plugin_settings, $general_settings, $force_settings );
	}

}

endif;

return new Alg_WC_Call_For_Price_Settings_General();

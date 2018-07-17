<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php

/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Define the theme-specific key to be sent to PressTrends.
define( 'WOO_PRESSTRENDS_THEMEKEY', 'zdmv5lp26tfbp7jcwiw51ix9sj389e712' );

// WooFramework init
require_once ( get_template_directory() . '/functions/admin-init.php' );

/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files, with support for overriding via a child theme.
/*-----------------------------------------------------------------------------------*/

$includes = array(
				'includes/theme-options.php', 			// Options panel settings and custom settings
				'includes/theme-functions.php', 		// Custom theme functions
				'includes/theme-actions.php', 			// Theme actions & user defined hooks
				'includes/theme-comments.php', 			// Custom comments/pingback loop
				'includes/theme-js.php', 				// Load JavaScript via wp_enqueue_script
				'includes/sidebar-init.php', 			// Initialize widgetized areas
				'includes/theme-widgets.php',			// Theme widgets
				'includes/theme-install.php',			// Theme installation
				'includes/theme-woocommerce.php',		// WooCommerce options
				'includes/theme-plugin-integrations.php'	// Plugin integrations
				);

// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'woo_includes', $includes );

foreach ( $includes as $i ) {
	locate_template( $i, true );
}

/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/
//Set Default Meta Value
function set_default_meta($post_ID){
    $current_field_value = get_post_meta($post_ID,'Устройство',true);
		$current_field_value = get_post_meta($post_ID,'Оплакване',true);
		$current_field_value = get_post_meta($post_ID,'Проблем',true);
		$current_field_value = get_post_meta($post_ID,'Решение',true);
        $default_meta = ''; // value
        
        if ($current_field_value == '' && !wp_is_post_revision($post_ID)){
        add_post_meta($post_ID,'Устройство',$default_meta,true);
				add_post_meta($post_ID,'Оплакване',$default_meta,true);
				add_post_meta($post_ID,'Проблем',$default_meta,true);
				add_post_meta($post_ID,'Решение',$default_meta,true);
        }
        return $post_ID;
}
add_action('wp_insert_post','set_default_meta');

// Add product categories to the "Product" breadcrumb in WooCommerce.
 
// Get breadcrumbs on product pages that read: Home > Shop > Product category > Product Name
add_filter( 'woo_breadcrumbs_trail', 'woo_custom_breadcrumbs_trail_add_product_categories', 20 );
 
function woo_custom_breadcrumbs_trail_add_product_categories ( $trail ) {
  if ( ( get_post_type() == 'product' ) && is_singular() ) {
		global $post;
		
		$taxonomy = 'product_cat';
		
		$terms = get_the_terms( $post->ID, $taxonomy );
		$links = array();
 
		if ( $terms && ! is_wp_error( $terms ) ) {
		$count = 0;
			foreach ( $terms as $c ) {
				$count++;
				if ( $count > 1 ) { continue; }
				$parents = woo_get_term_parents( $c->term_id, $taxonomy, true, ', ', $c->name, array() );
 
				if ( $parents != '' && ! is_wp_error( $parents ) ) {
					$parents_arr = explode( ', ', $parents );
					
					foreach ( $parents_arr as $p ) {
						if ( $p != '' ) { $links[] = $p; }
					}
				}
			}
			
			// Add the trail back on to the end.
			// $links[] = $trail['trail_end'];
			$trail_end = get_the_title($post->ID);
 
			// Add the new links, and the original trail's end, back into the trail.
			array_splice( $trail, 2, count( $trail ) - 1, $links );
			
			$trail['trail_end'] = $trail_end;
		}
	}
 
	return $trail;  
} 

// End woo_custom_breadcrumbs_trail_add_product_categories()
 
/**
 * Retrieve term parents with separator.
 *
 * @param int $id Term ID.
 * @param string $taxonomy.
 * @param bool $link Optional, default is false. Whether to format with link.
 * @param string $separator Optional, default is '/'. How to separate terms.
 * @param bool $nicename Optional, default is false. Whether to use nice name for display.
 * @param array $visited Optional. Already linked to terms to prevent duplicates.
 * @return string
 */
 
if ( ! function_exists( 'woo_get_term_parents' ) ) {
function woo_get_term_parents( $id, $taxonomy, $link = false, $separator = '/', $nicename = false, $visited = array() ) {
	$chain = '';
	$parent = &get_term( $id, $taxonomy );
	if ( is_wp_error( $parent ) )
		return $parent;
 
	if ( $nicename ) {
		$name = $parent->slug;
	} else {
		$name = $parent->name;
	}
 
	if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
		$visited[] = $parent->parent;
		$chain .= woo_get_term_parents( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
	}
 
	if ( $link ) {
		$chain .= '<a href="' . get_term_link( $parent, $taxonomy ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $parent->name ) ) . '">'.$parent->name.'</a>' . $separator;
	} else {
		$chain .= $name.$separator;
	}
	return $chain;
} // End woo_get_term_parents()
}



function example_product_image( $product ) { 
    if( isset( $product->id ) && has_post_thumbnail( $product->id ) ) {
        echo get_the_post_thumbnail( $product->id, array( 40, 40 ) );
    }
}
add_action( 'wcdn_order_item_before', 'example_product_image' );

function example_custom_order_fields( $fields, $order ) {
    $new_fields = array();
        
    if( get_post_meta( $order->id, 'Устройство', true ) ) {
        $new_fields['Устройство'] = array( 
            'label' => 'Устройство',
            'value' => get_post_meta( $order->id, 'Устройство', true )
        );
    }

  if( get_post_meta( $order->id, 'Оплакване', true ) ) {
        $new_fields['Оплакване'] = array( 
            'label' => 'Оплакване',
            'value' => get_post_meta( $order->id, 'Оплакване', true )
        );
    }
    
    if( get_post_meta( $order->id, 'Проблем', true ) ) {
        $new_fields['Проблем'] = array( 
            'label' => 'Проблем',
            'value' => get_post_meta( $order->id, 'Проблем', true )
        );
    }
	
    if( get_post_meta( $order->id, 'Решение', true ) ) {
        $new_fields['Решение'] = array( 
            'label' => 'Решение',
            'value' => get_post_meta( $order->id, 'Решение', true )
        );
    }
    
    return array_merge( $fields, $new_fields );
}
add_filter( 'wcdn_order_info_fields', 'example_custom_order_fields', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/
?>
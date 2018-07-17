<?php
	/* template name: mouse-overlay */
	
	//slider layout
	$slider_gallery.= '<div class="wa_wps_image_carousel" id="wa_wps_image_carousel'.$id.'">';

	$slider_gallery.='<ul id="wa_wps_foo'.$id.'" style="height:'.$wa_wps_query_posts_item_height.'px; overflow: hidden;" >';
	
	foreach($myposts as $wa_post) {
	
		$post_title = $wa_post->post_title; //post title 
		$post_link =  get_permalink($wa_post->ID); //post link
		$post_content = $wa_post->post_content; //post content
		$post_id=	$wa_post->ID; //post id
		$post_excerpt = $wa_post->post_excerpt;//post excerpt

		$text_type = $this->get_text_type($wa_post, $wa_wps_query_display_from_excerpt);

		//woocommerce get data
		if ( function_exists( 'get_product' ) ) {
		$_product = wc_get_product( $wa_post->ID );
		} else {
			//check if woocommerce active
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
   				$_product = new WC_Product( $wa_post->ID );
			}
		}

		//get product category name
		$first_cat_name = $this->get_product_category_first_name($qp_post_type, $wa_post->ID);
		
		$slider_gallery.= '<li style="width:'.$wa_wps_query_posts_item_width.'px; height:'.$wa_wps_query_posts_item_height.'px;" id="wa_wps_foo_content'.$id.'" class="wa_wps_foo_content">';

		$slider_gallery.= '<div class="wa_wps_text_overlay_p_container"><div class="wa_wps_text_overlay_caption">'; 

		if($displayimage) {

			$featured_img = '';
			$image = '';

			if($wa_wps_query_posts_image_type=='featured_image'){

			$image = $this->wa_wps_get_post_image($post_content, $post_id, 'featured_image', 'full' , $id);		
			$image_thumb = $this->wa_wps_get_post_image($post_content, $post_id, 'featured_image', $wa_wps_query_image_size, $id);

			if($wa_wps_query_lazy_loading) {
			$featured_img = "<img alt='".$post_title."' class='wa_lazy'  id='wa_wps_img_".$id."' data-original='".$image_thumb . "' width='". $wa_wps_query_posts_image_width . "' height='". $wa_wps_query_posts_image_height . "'  />";	
			}else{
			$featured_img = "<img alt='".$post_title."'  id='wa_wps_img_".$id."' src='".$image_thumb."' width='". $wa_wps_query_posts_image_width . "' height='". $wa_wps_query_posts_image_height . "'  />";	
			}
			
			}else if(isset($wa_wps_query_posts_image_type)&&$wa_wps_query_posts_image_type=='first_image'){

				$image = $this->wa_wps_get_post_image($post_content, $post_id, 'first_image', 'full', $id);
				
			if($wa_wps_query_lazy_loading) {
			$featured_img = "<img alt='".$post_title."' class='wa_lazy'  id='wa_wps_img_".$id."' data-original='".$image. "' width='". $wa_wps_query_posts_image_width . "' height='". $wa_wps_query_posts_image_height . "'  />";	
			}else{
			$featured_img = "<img alt='".$post_title."'   id='wa_wps_img_".$id."' src='". $image. "' width='". $wa_wps_query_posts_image_width . "' height='". $wa_wps_query_posts_image_height . "'  />";	
			}

			}else if($wa_wps_query_posts_image_type=='last_image'){

				$image = $this->wa_wps_get_post_image($post_content, $post_id, 'last_image', 'full', $id);
			if($wa_wps_query_lazy_loading) {
			$featured_img = "<img alt='".$post_title."' class='wa_lazy'  id='wa_wps_img_".$id."' data-original='".$image . "' width='". $wa_wps_query_posts_image_width . "' height='". $wa_wps_query_posts_image_height . "'  />";	
			}else{
			$featured_img = "<img alt='".$post_title."'   id='wa_wps_img_".$id."' src='". $image . "' width='". $wa_wps_query_posts_image_width . "' height='". $wa_wps_query_posts_image_height . "'  />";	
			}

			}

			//display sale text over product image
			if($wa_wps_query_display_sale_text_over_product_image=="1"&&$qp_post_type=='product')	{

				if( $_product->is_type( 'variable' ) )	{
				  
					// a variable product
					$available_variations = $_product->get_available_variations();
					$variation_id=$available_variations[0]['variation_id'];
					$variable_product1= new WC_Product_Variation( $variation_id );
					$sales_price = $variable_product1 ->get_sale_price();

					if(!empty($sales_price)) {
						$slider_gallery.= '<div style="margin: 2px; "><span class="wa_wps_onsale">'.__('Sale!', 'woocommerce').'</span>'; 
					}
				  
				} else {


					$sales_price = $_product->get_sale_price(); 

					if(!empty( $sales_price  ) ) {
						$slider_gallery.= '<div style="margin: 2px; "><span class="wa_wps_onsale">'.__('Sale!', 'woocommerce').'</span>'; 
					}
				}

			} 	


			//display image
			if($wa_wps_query_posts_lightbox){$slider_gallery.= '<a href="'.$image.'">'.$featured_img.'</a>';}else{
			$slider_gallery.= '<a href="'.$post_link.'">'.$featured_img.'</a>'; }

			//end display sale text over product image
			if($wa_wps_query_display_sale_text_over_product_image=="1"&&!empty($_product->get_sale_price())){
			$slider_gallery.= '</div>'; } 
			
		}

		$slider_gallery.= '<div class="wa_wps_text_overlay_caption_overlay">';

		/**********   Post title, Post Description, read more  **********/

		//display post title
		if($wa_wps_query_posts_title=='1') {

			$slider_gallery.= '<div class="wa_wps_text_overlay_caption_overlay_title">';
			$slider_gallery.= '<br/><div style="color:'.$wa_wps_query_font_colour.';" class="wa_wps_slider_title" id="wa_wps_slider_title'.$id.'"><a style="color:'.$wa_wps_query_font_colour.';" style=" text-decoration:none;" href="'.$post_link.'">'.$post_title.'</a></div>';
			$slider_gallery.= '</div>';
		
		}


		//display category
		if($wa_wps_query_show_categories=='1') {

			$slider_gallery.= '<div class="wa_wps_slider_show_cats" id="wa_wps_slider_show_cats'.$id.'">'.$first_cat_name.'</a></div>';
			
		}

		//display excerpt
		$slider_gallery.= '<div class="wa_wps_text_overlay_caption_overlay_content">';

		//display rating and review section
		if($wa_wps_query_posts_display_ratings=='1') {

			$slider_gallery.= '<div class="wa_wps_rating" id="wa_wps_rating'.$id.'">';

			$count   = $_product->get_rating_count();

			$average = $_product->get_average_rating();

			if ( $count > 0 ) : 

				$slider_gallery.= '<div class="wps_rating" id="wps_rating_'.$id.'" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';

				$wps_rate = sprintf( __( 'Rated %s out of 5', 'woocommerce' ), $average );

				$wps_avg = ( ( $average / 5 ) * 100 );

				$wps_review = sprintf( _n( '%s customer review', '%s customer reviews', $count, 'woocommerce' ), '<span itemprop="ratingCount" class="count">' . $count . '</span>' );

						$slider_gallery.='<div class="sr" title="'.$wps_rate.'">';

								$slider_gallery.='<span style="width:'.$wps_avg.'%">';

									$slider_gallery.='<strong itemprop="ratingValue" class="rating">';

									$slider_gallery.= esc_html( $average ); 

									$slider_gallery.='</strong>';

									$slider_gallery.= 'out of 5'; 
							
								$slider_gallery.= '</span>';

						$slider_gallery.= '</div>';//end sr
		
					$slider_gallery.= '</div>';//end wps_rating

	 		endif; 

	 		$slider_gallery.= '</div>';//end .wa_wps_rating

		}

		if($wa_wps_query_posts_display_excerpt=='1') {

			$slider_gallery.= '<div style="color:'.$wa_wps_query_font_colour.';" class="wa_wps_foo_con" id="wa_wpsjj_foo_con'.$id.'">'.$this->wa_wps_clean($text_type, $word_imit).'</div>';
		
		}
		//display read more text
		if($wa_wps_query_posts_display_read_more=='1') {

			$slider_gallery.= '<span style="color:'.$wa_wps_query_font_colour.';" class="wa_wps_more" id="wa_wps_more'.$id.'"><a style="color:'.$wa_wps_query_font_colour.';" href="'.$post_link.'">'.$wa_wps_read_more.'</a></span>';
		
		}

		//display price
		if($wa_wps_query_display_price=='1'&&$qp_post_type=='product') {

			$slider_gallery.= '<div class="wa_wps_price" id="wa_wps_price'.$id.'">'.$_product->get_price_html().'</div>';
		
		}

		//display add to cart
		if($wa_wps_query_display_add_to_cart=='1'&&$qp_post_type=='product') {


			$slider_gallery.= '<div class="wa_wps_add_to_cart" id="wa_wps_add_to_cart'.$id.'"><a  rel="nofollow" data-product_id="'.$post_id.'" data-product_sku="'.$_product->get_sku().'" class="wa_wps_button add_to_cart_button product_type_simple" href="'.do_shortcode('[add_to_cart_url id="'.$post_id.'"]').'">'.__('Add to cart', 'woocommerce').'</a></div>';
		
		}
		
		$slider_gallery.= '</div></div></div></div></li>';
	}

	$slider_gallery.='</ul>';
	$slider_gallery.='<div class="wa_wps_clearfix"></div>';

	if($wa_wps_show_controls=='1') {

	if($wps_pre_direction=="up"||$wps_pre_direction=="down") {

		$slider_gallery.='<a class="wa_wps_prev_v" id="foo'.$id.'_prev" href="#"><span style="">›</span></a>';
		$slider_gallery.='<a class="wa_wps_next_v" id="foo'.$id.'_next" href="#"><span>‹</span></a>';
	
	}else{

			$slider_gallery.='<a class="wa_wps_prev" id="foo'.$id.'_prev" href="#"><span>‹</span></a>';
			$slider_gallery.='<a class="wa_wps_next" id="foo'.$id.'_next" href="#"><span>›</span></a>';
		
		}
	}
	if($wa_wps_show_paging=='1') {

		$slider_gallery.='<div class="wa_wps_pagination" id="wa_wps_pager_'.$id.'"></div>';
	
	}
	$slider_gallery.='</div>';
<table class="wa-wps_input widefat" id="wa-wps_slider_options">
	<tbody>	
		<?php do_action( 'wa_options_meta_box_start', $slider_id ); ?>

		<!-- post type -->
		<tr id="slider_post_order">
			<td class="label">
				<label>
					<?php _e( 'Type of the content', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select id="wa_wps_query_posts_post_type" name="slider_options[post_type]" required>
					<option value=''>choose...</option>

			
				
<option value="product" <?php selected( "product", $slider_options['post_type'] ) ?> >product</option>


				</select>
				<p class="description"><?php _e( 'Please, select a type of the content (post type) which you want to display.', 'wps' ); ?></p>
			</td>
		</tr>

		<!-- woocommerce product types -->
		<tr id="product_type">
			<td class="label">
				<label>
					<?php _e( 'Product type', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select id="wa_wps_query_product_type" name="slider_options[product_type]">
					<option value=''>choose...</option>
					<option value="category" 
					<?php selected( "category", $slider_options['product_type'] ) ?>
					>Categories</option>

				</select>
				
			</td>
		</tr>

		<!-- Post type -->
		<tr id="content_type">
			<td class="label">
				<label>
					<?php _e( 'Display posts', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select id="wa_wps_query_content_type" name="slider_options[content_type]">
					<option value=''>choose...</option>
					<option value="category" 
					<?php selected( "category", $slider_options['content_type'] ) ?>
					>Categories</option>
						<option value="tag" 
					<?php selected( "tag", $slider_options['content_type'] ) ?>
					>Tags</option>
					<option value="most_viewed" 
					<?php selected( "most_viewed", $slider_options['content_type'] ) ?>
					>Most viewed</option>
					<option value="newest" 
					<?php selected( "newest", $slider_options['content_type'] ) ?>
					>Newest</option>
					<option value="related" 
					<?php selected( "related", $slider_options['content_type'] ) ?>
					>Related products (Only applies on carousels which are located in single post page.)</option>
					<option value="specific" 
					<?php selected( "specific", $slider_options['content_type'] ) ?>
					>Specific posts by IDs</option>

				</select>
				
			</td>
		</tr>

		<!-- taxonomy -->
		<tr id="slider_post_order">
			<td class="label">
				<label>
					<?php _e( 'Taxonomy', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select id="wa_wps_query_posts_taxonomy" name="slider_options[post_taxonomy]">
					
 				<option value=''>choose...</option>
				<?php 

				$taxonomy_names = get_object_taxonomies($slider_options['post_type'] );
				if(!empty( $taxonomy_names )) {
		 		foreach ($taxonomy_names as $key => $value) {?>
				<option value="<?php echo $value; ?>" 
					<?php selected( $value, $slider_options['post_taxonomy'] ) ?>
					><?php echo $value; ?></option><?php } }?></select>
			
					<ul>
					 	<li><p class="description"><?php _e( 'If you want to display posts from categories, First select post type as post then select taxonomy as category. After that you will be able to select categories from the  categories field which will appear below. Please, leave this empty, if you want to display specific posts by posts Ids which you can fill in the post ids field in below.', 'wps' ); ?></p></li>
					</ul>
			
			</td>
		</tr>

		<!-- terms -->
		<tr id="slider_post_order">
			<td class="label">
				<label>
					<?php _e( 'Categories / Terms ', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
					<?php 
					if($slider_options['post_type']=='product') {

						$tax_selected = 'product_cat';

					} else if($slider_options['post_type']=='post') {

						$tax_selected = 'category';


					} else {

						$tax_selected = isset($slider_options['post_taxonomy']) ? $slider_options['post_taxonomy'] : '';

					}
					?>

					<select id="wa_wps_query_posts_terms" name="slider_options[post_terms][]" multiple>
					<option value=''>choose...</option>
					<?php

					 $categories = get_terms( $tax_selected , array(
						    'post_type' => array($slider_options['post_type']  ),
						    'fields' => 'all'

						));

					 if(!empty( $categories )) {
					 foreach ($categories as $key => $value) { ?>
						<option value="<?php echo $value->slug; ?>"


					<?php 
					if(!empty( $slider_options['post_terms'] )) {

					foreach ($slider_options['post_terms'] as $contractor) {

							if($value->slug==$contractor){ selected( $value->slug, $value->slug ); }
					}
				}
					?> ><?php echo $value->name; ?></option><?php } }?>
				</select>
				<p class="description"><?php echo __('Please, hold down the control or command button to select multiple options.', 'wps'); ?></p>
			</td>
		</tr>



	<!-- tags -->
		<tr id="slider_post_order">
			<td class="label">
				<label>
					<?php _e( 'Tags', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
					<?php 

					$tax_selected = 'product_tag';
					if($slider_options['post_type']=='product') {

						$tax_selected = 'product_tag'; 

					} else if($slider_options['post_type']=='post') {

						$tax_selected = 'post_tag';

}
					// } else {

					// 	//$tax_selected = isset($slider_options['post_taxonomy']) ? $slider_options['post_taxonomy'] : '';

					// }
					?>

					<select id="wa_wps_query_posts_tags" name="slider_options[post_tags][]" multiple>
					<option value=''>choose...</option>
					<?php

					 $categories = get_terms( $tax_selected , array(
						    'post_type' => array($slider_options['post_type']  ),
						    'fields' => 'all'

						));

					 if(!empty( $categories )) {
					 foreach ($categories as $key => $value) { ?>
						<option value="<?php echo $value->slug; ?>"


					<?php 
					if(!empty( $slider_options['post_tags'] )) {

					foreach ($slider_options['post_tags'] as $contractor) {

							if($value->slug==$contractor){ selected( $value->slug, $value->slug ); }
					}
				}
					?> ><?php echo $value->name; ?></option><?php } }?>
				</select>
				<p class="description"><?php echo __('Please, hold down the control or command button to select multiple options.', 'wps'); ?></p>
			</td>
		</tr>
		<!-- end of tags -->



		<!-- post Order by -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Post order by', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[posts_order_by]"value="<?php if(empty($slider_options['posts_order_by'])){ echo'id'; }else{echo $slider_options['posts_order_by']; };?>" />
			<p class="description"><?php _e( 'e.g. ID (Possible values: id, author, _price,  title, date, category, modified)', 'wps' ); ?></p></td>
		</tr>

		<!-- post order -->
		<tr id="slider_post_order">
			<td class="label">
				<label>
					<?php _e( 'Post order', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[post_order]">
					<option value="asc" 
					<?php selected( "asc", $slider_options['post_order'] ) ?>
					>Ascending</option>
					<option value="desc" 
					<?php selected( "desc", $slider_options['post_order'] ) ?>
					>Descending</option>
					<option value="rand" 
					<?php selected( "rand", $slider_options['post_order'] ) ?>
					>Random</option>
				</select>
			</td>
		</tr>

		<!-- Theme -->
				<tr id="slider_post_order">
			<td class="label">
				<label>
					<?php _e( 'Theme', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[template]" required>
							<option value="">choose...</option>
							<?php foreach ($this->list_themes() as  $value) { ?>
							<option value="<?php echo $value; ?>" 
							<?php selected( $value, $slider_options['template'] ) ?>
							><?php echo $value; ?></option>

							<?php }	?>

				</select><p class="description"><?php _e( 'The themes which are located in the theme directry of the plugin are showing here. ', 'wps' ); ?></p></td>
		</tr>

		<!-- image hover effect -->
		<tr id="image_hover_effect">
			<td class="label">
				<label>
					<?php _e( 'Image hover effects', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[image_hover_effect]">
					<option value="none" 
					<?php selected( "none", $slider_options['image_hover_effect'] ) ?>
					>none</option>
					<option value="hover_image" 
					<?php selected( "hover_image", $slider_options['image_hover_effect'] ) ?>
					>image hover</option>
					<option value="grayscale" 
					<?php selected( "grayscale", $slider_options['image_hover_effect'] ) ?>
					>greyscale</option>
					<option value="sepia" 
					<?php selected( "sepia", $slider_options['image_hover_effect'] ) ?>
					>sepia</option>
					<option value="saturate" 
					<?php selected( "saturate", $slider_options['image_hover_effect'] ) ?>
					>saturate</option>
					<option value="border" 
					<?php selected( "border", $slider_options['image_hover_effect'] ) ?>
					>border around the image</option>
				</select>
				<p class="description"><?php _e( 'This will turn images to selected effect until user places their mouse over. Applies on basic theme.', 'wps' ); ?></p>
			</td>
		</tr>

		<!-- read more text -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Read more text', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[read_more_text]"value="<?php if(empty($slider_options['read_more_text'])){ echo'Read more'; }else{echo $slider_options['read_more_text']; };?>" />
			<p class="description"><?php _e( 'This text will be shown after the excerpt. e.g. Read more', 'wps' ); ?></p></td>
		</tr>

		<!-- Excerpt length -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Excerpt Length', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[word_limit]"value="<?php if(empty($slider_options['word_limit'])){ echo'15'; }else{echo $slider_options['word_limit']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'Character Limit. e.g. 10', 'wps' ); ?></p></td>
		</tr>

		<!-- Number of post to be shown -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Number of post to be shown in the slider', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[show_posts]"value="<?php if(empty($slider_options['show_posts'])){ echo'20'; }else{echo $slider_options['show_posts']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'e.g. 20', 'wps' ); ?></p></td>
		</tr>

		<!-- Number of posts to be shown in the page -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Number of items to be shown in the page', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[show_posts_per_page]"value="<?php if(empty($slider_options['show_posts_per_page'])){ echo'20'; }else{echo $slider_options['show_posts_per_page']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'e.g. 3', 'wps' ); ?></p></td>
		</tr>

		<!-- Number of items to be scroll -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Number of items to be scroll in one transition', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[items_to_be_slide]"value="<?php if(empty($slider_options['items_to_be_slide'])){ echo'0'; }else{echo $slider_options['items_to_be_slide']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'e.g. 0 (if zero, value will be automatically set to the size of the page.', 'wps' ); ?></p></td>
		</tr>

		<!-- Speed of transition -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Speed of transition', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[duration]"value="<?php if(empty($slider_options['duration'])){ echo'500'; }else{echo $slider_options['duration']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'he duration of the scroll animation in milliseconds.', 'wps' ); ?></p></td>
		</tr>

		<!-- Time out -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Timeout between elements', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[timeout]"value="<?php if(empty($slider_options['timeout'])){ echo'500'; }else{echo $slider_options['timeout']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'Set the time between transitions. Only applies if Auto scroll true.', 'wps' ); ?></p></td>
		</tr>

		<!-- Item width -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('General width of items', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[item_width]"value="<?php if(empty($slider_options['item_width'])){ echo'250'; }else{echo $slider_options['item_width']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'Width of one item in the carousel(PX). e.g. 250', 'wps' ); ?></p></td>
		</tr>

		<!-- Item height -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('General height of items', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[item_height]"value="<?php if(empty($slider_options['item_height'])){ echo'320'; }else{echo $slider_options['item_height']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'Height of one item in the carousel(PX). e.g. 320', 'wps' ); ?></p></td>
		</tr>

				<!-- Thumbnail image -->
		<tr id="image_type">
			<td class="label">
				<label>
					<?php _e( 'Image size', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[image_size]">
					<option value="thumbnail" 
					<?php selected( "thumbnail", $slider_options['image_size'] ) ?>
					>thumbnail</option>
					<option value="medium" 
					<?php selected( "medium", $slider_options['image_size'] ) ?>
					>medium</option>
						<option value="large" 
					<?php selected( "large", $slider_options['image_size'] ) ?>
					>large</option>
						<option value="full" 
					<?php selected( "full", $slider_options['image_size'] ) ?>
					>full</option>
						<option value="other" 
					<?php selected( "other", $slider_options['image_size'] ) ?>
					>other</option>
				</select>
				<p class="description"><?php _e( 'The default image sizes of WordPress are "thumbnail" (and its "thumb" alias), "medium", "large" and "full" (the image you uploaded). These image sizes can be configured in the WordPress Administration Media panel under Settings > Media. If you select other, the size will be automatically selected to the hight or width provided otherwise Thumbnail size will be used. only applies on featured image.', 'wps' ); ?></p></td>
	
			</td>
		</tr>

		<!-- Post image width -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Post image width (optional)', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[post_image_width]"value="<?php if(empty($slider_options['post_image_width'])){ echo''; }else{echo $slider_options['post_image_width']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'Width of image in the carousel(PX). e.g. 250 If this field empty, images width will be determined to the image height. if the both height and width fields are empty, the images sizes will be changed to the size of the image automatically.', 'wps' ); ?></p></td>
		</tr>

		<!-- Post image height -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Post image height (optional)', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[post_image_height]"value="<?php if(empty($slider_options['post_image_height'])){ echo''; }else{echo $slider_options['post_image_height']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'Height of image in the carousel(PX). e.g. 150. If this field empty, images height will be determined to the image width. if the both height and width fields are empty, the images sizes will be changed to the size of the image automatically.', 'wps' ); ?></p></td>
		</tr>

		<!-- Align of the text-->
		<tr id="image_type">
			<td class="label">
				<label>
					<?php _e( 'Alignment of the text', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[text_align]">
					<option value="left" 
					<?php selected( "left", $slider_options['text_align'] ) ?>
					>Left</option>
					<option value="right" 
					<?php selected( "right", $slider_options['text_align'] ) ?>
					>Right</option>
					<option value="center" 
					<?php selected( "center", $slider_options['text_align'] ) ?>
					>Center</option>
				</select>
			</td>
		</tr>

		<!-- Align of the image-->
		<tr id="image_type">
			<td class="label">
				<label>
					<?php _e( 'Alignment of the image', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[image_align]">
					<option value="left" 
					<?php selected( "left", $slider_options['image_align'] ) ?>
					>Left</option>
					<option value="right" 
					<?php selected( "right", $slider_options['image_align'] ) ?>
					>Right</option>
					<option value="center" 
					<?php selected( "center", $slider_options['image_align'] ) ?>
					>Center</option>
				</select>
			</td>
		</tr>

		<!-- Post image type -->
		<tr id="image_type">
			<td class="label">
				<label>
					<?php _e( 'Post image type', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[image_type]">
					<option value="featured_image" 
					<?php selected( "featured_image", $slider_options['image_type'] ) ?>
					>Featured image of post</option>
					<option value="first_image" 
					<?php selected( "first_image", $slider_options['image_type'] ) ?>
					>First image of post</option>
					<option value="last_image" 
					<?php selected( "last_image", $slider_options['image_type'] ) ?>
					>Last image of post</option>
				</select>
			</td>
		</tr>

		<!-- Slider easing effect -->
		<tr id="display_image">
			<td class="label">
				<label>
					<?php _e( 'Slider transition effect', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[easing_effect]">
					<option value="linear" 
					<?php selected( "linear", $slider_options['easing_effect'] ) ?>
					>linear</option>
					<option value="swing" 
					<?php selected( "swing", $slider_options['easing_effect'] ) ?>
					>swing</option>
					<option value="quadratic" 
					<?php selected( "quadratic", $slider_options['easing_effect'] ) ?>
					>quadratic</option>
					<option value="cubic" 
					<?php selected( "cubic", $slider_options['easing_effect'] ) ?>
					>cubic</option>
					<option value="elastic" 
					<?php selected( "elastic", $slider_options['easing_effect'] ) ?>
					>elastic</option>
				</select>
			</td>
		</tr>

		<!-- Transition effect -->
		<tr id="display_image">
			<td class="label">
				<label>
					<?php _e( 'Easing effects', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[fx]">
					<option value="scroll" 
					<?php selected( "scroll", $slider_options['fx'] ) ?>
					>scroll</option>
					<option value="directscroll" 
					<?php selected( "directscroll", $slider_options['fx'] ) ?>
					>directscroll</option>
					<option value="fade" 
					<?php selected( "fade", $slider_options['fx'] ) ?>
					>fade</option>
					<option value="crossfade" 
					<?php selected( "crossfade", $slider_options['fx'] ) ?>
					>crossfade</option>
					<option value="cover" 
					<?php selected( "cover", $slider_options['fx'] ) ?>
					>cover</option>
					<option value="cover-fade" 
					<?php selected( "cover-fade", $slider_options['fx'] ) ?>
					>cover-fade</option>
					<option value="uncover" 
					<?php selected( "uncover", $slider_options['fx'] ) ?>
					>uncover</option>
					<option value="uncover-fade" 
					<?php selected( "uncover-fade", $slider_options['fx'] ) ?>
					>uncover-fade</option>
					<option value="none" 
					<?php selected( "none", $slider_options['fx'] ) ?>
					>none</option>
				</select>
			</td>
		</tr>

		<!-- Direction to scroll -->
		<tr id="display_image">
			<td class="label">
				<label>
					<?php _e( 'Direction to scroll the carousel', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[direction]">
					<option value="right" 
					<?php selected( "right", $slider_options['direction'] ) ?>
					>right</option>
					<option value="left" 
					<?php selected( "left", $slider_options['direction'] ) ?>
					>left</option>
					<option value="up" 
					<?php selected( "up", $slider_options['direction'] ) ?>
					>up</option>
					<option value="down" 
					<?php selected( "down", $slider_options['direction'] ) ?>
					>down</option>
				</select>
				<p class="description"><?php _e( 'Please, select right or left to display slider horizontally up or down to display vertically.', 'wps' ); ?></p>
			</td>
		</tr>

		<!-- Align items -->
		<tr id="display_image">
			<td class="label">
				<label>
					<?php _e( 'Align the items in Slider', 'wps' ); ?>
				</label>
				<p class="description"></p>
			</td>
			<td>
				<select name="slider_options[align_items]">
					<option value="center" 
					<?php selected( "center", $slider_options['align_items'] ) ?>
					>center</option>
					<option value="left" 
					<?php selected( "left", $slider_options['align_items'] ) ?>
					>left</option>
					<option value="right" 
					<?php selected( "right", $slider_options['align_items'] ) ?>
					>right</option>
				</select>
			</td>
		</tr>

		<!-- Font colour -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Font colour', 'wps'); ?></label></td>
			<td><input type="text" id ="wa_wps_font_colour" name="slider_options[font_colour]"value="<?php if(empty($slider_options['font_colour'])){ echo'#000'; }else{echo $slider_options['font_colour']; };?>" />
			<p class="description"><?php _e( 'Font colour', 'wps' ); ?></p></td>
		</tr>

		<!-- Control colour -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Direction arrows colour', 'wps'); ?></label></td>
			<td><input type="text" id ="wa_wps_control_colour" name="slider_options[control_colour]"value="<?php if(empty($slider_options['control_colour'])){ echo'#000'; }else{echo $slider_options['control_colour']; };?>" />
			<p class="description"><?php _e( 'Next and Prev controls colour', 'wps' ); ?></p></td>
		</tr>

		<!-- Control background colour -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Direction arrows background colour', 'wps'); ?></label></td>
			<td><input type="text" id ="wa_wps_control_bg_colour" name="slider_options[control_bg_colour]"value="<?php if(empty($slider_options['control_bg_colour'])){ echo'#fff'; }else{echo $slider_options['control_bg_colour']; };?>" />
			<p class="description"><?php _e( 'Next and Prev controls background colour', 'wps' ); ?></p></td>
		</tr>

		<!-- Control colour -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Direction arrows hover colour', 'wps'); ?></label></td>
			<td><input type="text" id ="wa_wps_control_hover_colour" name="slider_options[arrows_hover_colour]"value="<?php if(empty($slider_options['arrows_hover_colour'])){ echo'#000'; }else{echo $slider_options['arrows_hover_colour']; };?>" />
			<p class="description"><?php _e( 'Next and Prev controls, paging and Rating hover colour', 'wps' ); ?></p></td>
		</tr>

		<!-- Image hover colour -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Image hover colour', 'wps'); ?></label></td>
			<td><input type="text" id ="wa_wps_image_hover_colour" name="slider_options[hover_image_bg]"value="<?php if(empty($slider_options['hover_image_bg'])){ echo'rgba(40,168,211,.85)'; }else{echo $slider_options['hover_image_bg']; };?>" />
			<p class="description"><?php _e( 'Image hover effect background colour', 'wps' ); ?></p></td>
		</tr>

		<!-- Next pre length -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Size of direction arrows', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[size_arrows]"value="<?php if(empty($slider_options['size_arrows'])){ echo'18'; }else{echo $slider_options['size_arrows']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'e.g. 18', 'wps' ); ?></p></td>
		</tr>

		<!-- Font size -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Title font size', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[title_font_size]"value="<?php if(empty($slider_options['title_font_size'])){ echo'18'; }else{echo $slider_options['title_font_size']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'e.g. 18', 'wps' ); ?></p></td>
		</tr>

		<!-- general font size -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('General font size', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[font_size]"value="<?php if(empty($slider_options['font_size'])){ echo'18'; }else{echo $slider_options['font_size']; };?>" onkeypress='return event.charCode >= 48 && event.charCode <= 57' />
			<p class="description"><?php _e( 'e.g. 18', 'wps' ); ?></p></td>
		</tr>

		<!-- default image -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Default image', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[default_image]"value="<?php if(empty($slider_options['default_image'])){ echo plugins_url().'/woocommerce-product-slider-pro/assets/images/default-image.jpg'; }else{echo $slider_options['default_image']; };?>"  />
			<p class="description"><?php _e( 'This image will be shown for posts which does not have images', 'wps' ); ?></p></td>
		</tr>

		<!-- loading image -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('loader image', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[lazy_load_image]"value="<?php if(empty($slider_options['lazy_load_image'])){ echo plugins_url().'/woocommerce-product-slider-pro/assets/images/default-image.jpg'; }else{echo $slider_options['lazy_load_image']; };?>"  />
			<p class="description"><?php _e( 'This image will be used for lazy loading', 'wps' ); ?></p></td>
		</tr>

		<!-- hover image -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Image hover', 'wps'); ?></label></td>
			<td><input type="text" name="slider_options[hover_image_url]"value="<?php if(empty($slider_options['hover_image_url'])){ echo plugins_url().'/woocommerce-product-slider-pro/assets/images/hover.png'; }else{echo $slider_options['hover_image_url']; };?>"  />
			<p class="description"><?php _e( 'This image will be used for image hover effect.', 'wps' ); ?></p></td>
		</tr>

		<!-- start date -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Start date', 'wps'); ?></label></td>
			<td><input id="start_date"  type="text" name="slider_options[start_date]"value="<?php if(empty($slider_options['start_date'])){ echo''; }else{echo $slider_options['start_date']; };?>"  />
			<p class="description"><?php _e( 'Please, leave empty to always show this carousel', 'wps' ); ?></p></td>
		</tr>

		<!-- end date -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('End date', 'wps'); ?></label></td>
			<td><input id="end_date"  type="text" name="slider_options[end_date]"value="<?php if(empty($slider_options['end_date'])){ echo''; }else{echo $slider_options['end_date']; };?>"  />
			<p class="description"><?php _e( 'Please, leave empty to always show this carousel', 'wps' ); ?></p></td>
		</tr>


		<tr id="slider_controls">
			
			<td class="label">
				<label>
					<?php _e( 'Display options', 'wps' ); ?>
				</label>
				<p class="description">
					<?php _e( 'Enable or Disable different navigation and control options' , 'wps' ); ?>
				</p>
			</td>
			<td>

				<p>
					<label>
						<input type="checkbox" name="slider_options[show_title]" value="true"<?php if(isset($slider_options['show_title'])){ checked( true, $slider_options['show_title'] ); } ?> 
						/><?php _e( 'Show post title' , 'wps' ); ?>
					</label>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[show_image]" value="true"<?php if(isset($slider_options['show_image'])){ checked( true, $slider_options['show_image'] ); } ?> 
						/><?php _e( 'Show image' , 'wps' ); ?>
					</label>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[show_excerpt]" value="true"<?php if(isset($slider_options['show_excerpt'])){ checked( true, $slider_options['show_excerpt'] ); } ?> 
						/><?php _e( 'Show excerpt' , 'wps' ); ?>
					</label>
				</p>


				<p>
					<label>
						<input type="checkbox" name="slider_options[show_read_more_text]" value="true"<?php if(isset($slider_options['show_read_more_text'])){ checked( true, $slider_options['show_read_more_text'] ); } ?> 
						/><?php _e( 'Show read more text' , 'wps' ); ?>
					</label>
				</p>

				<p>
					<label>
						<input type="checkbox" id="wa_wps_show_rating" name="slider_options[show_rating]" value="true"<?php if(isset($slider_options['show_rating'])){ checked( true, $slider_options['show_rating'] ); } ?> 
						/><?php _e( 'Show rating' , 'wps' ); ?>
					</label>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[show_cats]" value="true"<?php if(isset($slider_options['show_cats'])){ checked( true, $slider_options['show_cats'] ); } ?> 
						/><?php _e( 'Show category name' , 'wps' ); ?>
					</label>
					<p class="description"><?php _e( 'This will show the name of category a post belongs to on top of the item.' , 'wps' ); ?></p>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[excerpt_type]" value="true"<?php if(isset($slider_options['excerpt_type'])){ checked( true, $slider_options['excerpt_type'] ); } ?> 
						/><?php _e( 'Pick text in excerpt field' , 'wps' ); ?>
					</label>
					<p class="description"><?php _e( 'If checked, text will be picked from excerpt field instead of post content area.' , 'wps' ); ?></p>
			
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[responsive]" value="true"<?php if(isset($slider_options['responsive'])){ checked( true, $slider_options['responsive'] ); } ?> 
						/><?php _e( 'Change general width of items to fill the carousel' , 'wps' ); ?>
					</label>
						<p class="description"><?php _e( 'If uncheck, items will be centered to the page and width will not be changed. (only applies on horizontal carousels)' , 'wps' ); ?></p>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[lightbox]" value="true"<?php if(isset($slider_options['lightbox'])){ checked( true, $slider_options['lightbox'] ); } ?> 
						/><?php _e( 'Lightbox' , 'wps' ); ?>
					</label>
					<p class="description"><?php _e( 'Uses Magnific popup for displaying images in lightbox.' , 'wps' ); ?></p>

				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[lazy_loading]" value="true"<?php if(isset($slider_options['lazy_loading'])){ checked( true, $slider_options['lazy_loading'] ); } ?> 
						/><?php _e( 'Lazy loading' , 'wps' ); ?>
					</label>
					<p class="description"><?php _e( 'If checked, images outside of viewport wont be loaded before user scrolls to them. Uses lazyload plugin.' , 'wps' ); ?></p>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[auto_scroll]" value="true"<?php if(isset($slider_options['auto_scroll'])){ checked( true, $slider_options['auto_scroll'] ); } ?> 
						/><?php _e( 'Auto scroll slider' , 'wps' ); ?>
					</label>
				</p>


				<p>
					<label>
						<input type="checkbox" name="slider_options[touch_swipe]" value="true"<?php if(isset($slider_options['touch_swipe'])){ checked( true, $slider_options['touch_swipe'] ); } ?> 
						/><?php _e( 'Touch Swipe' , 'wps' ); ?>
					</label>
					<p class="description"><?php _e( 'A carousel scrolled by swiping (or dragging on non-touch-devices). Uses touchSwipe plugin.' , 'wps' ); ?></p>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[show_controls]" value="true"<?php if(isset($slider_options['show_controls'])){ checked( true, $slider_options['show_controls'] ); } ?> 
						/><?php _e( 'Show direction arrows' , 'wps' ); ?>
					</label>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[animate_controls]" value="true"<?php if(isset($slider_options['animate_controls'])){ checked( true, $slider_options['animate_controls'] ); } ?> 
						/><?php _e( 'Show direction arrows only when mouse hovers over it. (only applies on horizontal carousels).' , 'wps' ); ?>
					</label>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[show_paging]" value="true"<?php if(isset($slider_options['show_paging'])){ checked( true, $slider_options['show_paging'] ); } ?> 
						/><?php _e( 'Show pagination ' , 'wps' ); ?>
					</label>
				</p>


				<p>
					<label>
						<input type="checkbox" name="slider_options[css_transitions]" value="true"<?php if(isset($slider_options['css_transitions'])){ checked( true, $slider_options['css_transitions'] ); } ?> 
						/><?php _e( 'CSS3 Transtitions' , 'wps' ); ?>
					</label>
					<p class="description"><?php _e( 'Transition effect will be used CSS3 or hardware acceleration. Uses jquery.transit plugin.' , 'wps' ); ?></p>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[circular]" value="true"<?php if(isset($slider_options['circular'])){ checked( true, $slider_options['circular'] ); } ?> 
						/><?php _e( 'Circular' , 'wps' ); ?>
					</label>
					<p class="description"><?php _e( 'Determines whether the carousel should be circular.' , 'wps' ); ?></p>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[infinite]" value="true"<?php if(isset($slider_options['infinite'])){ checked( true, $slider_options['infinite'] ); } ?> 
						/><?php _e( 'Infinite' , 'wps' ); ?>
					</label>
					<p class="description"><?php _e( 'Determines whether the carousel should be infinite.' , 'wps' ); ?></p>
				</p>

				<p>
					<label>
						<input type="checkbox" name="slider_options[pause_on_hover]" value="true"<?php if(isset($slider_options['pause_on_hover'])){ checked( true, $slider_options['pause_on_hover'] ); } ?> 
						/><?php _e( 'Make carousel pause when mouse hovers over it.' , 'wps' ); ?>
					</label>
				</p>

				<!-- woocommerce product options -->
				<p>
					<label>
						<input type="checkbox" id="wa_wps_show_price" name="slider_options[show_price]" value="true"<?php if(isset($slider_options['show_price'])){ checked( true, $slider_options['show_price'] ); } ?> 
						/><?php _e( 'Show price ' , 'wps' ); ?>
					</label>
				</p>

				<p>
					<label>
						<input type="checkbox" id="wa_wps_show_sale_text" name="slider_options[show_sale_text_over_image]" value="true"<?php if(isset($slider_options['show_sale_text_over_image'])){ checked( true, $slider_options['show_sale_text_over_image'] ); } ?> 
						/><?php _e( 'Show post sale text over top of post image' , 'wps' ); ?>
					</label>
				</p>


				<p>
					<label>
						<input type="checkbox" id="wa_wps_show_add_to_cart"  name="slider_options[show_add_to_cart]" value="true"<?php if(isset($slider_options['show_add_to_cart'])){ checked( true, $slider_options['show_add_to_cart'] ); } ?> 
						/><?php _e( 'Show add to cart' , 'wps' ); ?>
					</label>
				</p>

			</td>
		</tr>

		<!-- custom style -->
		<tr class="form-field form-required">
			<td class="label"><label for="name"><?php echo __('Custom css', 'wps'); ?></label></td>
			<td><textarea name="slider_options[custom_css]" placeholder=".wa_wps_slider_title { color: #ccc !important; }"><?php if(empty($slider_options['custom_css'])){ echo''; }else{echo $slider_options['custom_css']; };?></textarea>
			<p class="description"><?php _e( 'custom styles or override existing styles to meet your requirements.', 'wps' ); ?></p></td>
		</tr>

	<?php do_action( 'wa_options_meta_box_end', $slider_id ); ?>
	</tbody>
</table>
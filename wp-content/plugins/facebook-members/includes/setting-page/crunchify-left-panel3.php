<?php
/**
 * @author Crunchify.com
 * Plugin: Facebook Members
 */
?>
<div class="postbox">
	<div class="crunchify">
		<h3>Option panel for Widget/Sidebar Area</h3>

		<div>
			<table class="form-table">

				<tr valign="top">
					<th scope="row" style="width: 29%;"><label>Widget Title</label></th>
					<td><textarea id="styled" name="as_facebook_mem_widget_title"
							cols="18" rows="1"><?php echo get_option('as_facebook_mem_widget_title'); ?></textarea></td>
				</tr>
				<tr valign="top" class="alternate">
					<th scope="row" style="width: 29%;"><label>Facebook Page Name</label></th>
					<td><textarea id="styled" name="as_facebook_mem_widget_page_name"
							cols="18" rows="1"><?php echo get_option('as_facebook_mem_widget_page_name'); ?></textarea>
                    &nbsp;<?=$fb_pagename1?>
                    <br> <a
						href="http://www.facebook.com/pages/create.php" target="_blank">Create
							Fanpage</a></td>
				</tr>

				<tr valign="top" class="alternate">
					<th scope="row" style="width: 29%;"><label>Data tabs (timeline/messages/events)</label></th>
					<td><textarea id="styled" name="as_facebook_mem_widget_data_tabs"
							cols="18" rows="1"><?php echo get_option('as_facebook_mem_widget_data_tabs'); ?></textarea>
                    &nbsp;<?=$fb_widget_datatabs?>
          </td>
				</tr>


				<tr valign="top">
					<th scope="row" style="width: 29%;"><label>Widget Width</label></th>
					<td><textarea id="styled" name="as_facebook_mem_widget_width"
							cols="18" rows="1"><?php echo get_option('as_facebook_mem_widget_width'); ?></textarea></td>
				</tr>
				<tr valign="top" class="alternate">
					<th scope="row" style="width: 29%;"><label>Widget Height</label></th>
					<td><textarea id="styled" name="as_facebook_mem_widget_height"
							cols="18" rows="1"><?php echo get_option('as_facebook_mem_widget_height'); ?></textarea></td>
				</tr>
				<tr valign="top" class="alternate">
					<th scope="row"><label>Small Header?</label></th>
					<td><input name="as_facebook_mem_widget_small_header" type="radio" value="true"
						<?php checked('true', $as_facebook_mem_widget_small_header); ?> /> &nbsp;YES <input
						name="as_facebook_mem_widget_small_header" type="radio" value="false"
						<?php checked('false', $as_facebook_mem_widget_small_header); ?> />
										&nbsp;NO (default)
								</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label>Adapt Container Width?</label></th>
					<td><input name="as_facebook_mem_widget_adapt_container_width" type="radio" value="true"
						<?php checked('true', $as_facebook_mem_widget_adapt_container_width); ?> /> &nbsp;YES
						(default) <input name="as_facebook_mem_widget_adapt_container_width" type="radio"
						value="false" <?php checked('false', $as_facebook_mem_widget_adapt_container_width); ?> />
						&nbsp;NO</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label>Hide Cover?</label></th>
					<td><input name="as_facebook_mem_widget_hide_cover" type="radio" value="true"
						<?php checked('true', $as_facebook_mem_widget_hide_cover); ?> /> &nbsp;YES
						<input name="as_facebook_mem_widget_hide_cover" type="radio"
						value="false" <?php checked('false', $as_facebook_mem_widget_hide_cover); ?> />
						&nbsp;NO (default)</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label>Show Facepile?</label></th>
					<td><input name="as_facebook_mem_widget_show_facepile" type="radio" value="true"
						<?php checked('true', $as_facebook_mem_widget_show_facepile); ?> /> &nbsp;YES
						(default) <input name="as_facebook_mem_widget_show_facepile" type="radio"
						value="false" <?php checked('false', $as_facebook_mem_widget_show_facepile); ?> />
						&nbsp;NO</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label>Background Color <?=$new_icon?></label></th>

					<td><textarea id="styled"
							name="as_facebook_mem_widget_color_scheme" cols="18" rows="1"><?php echo get_option('as_facebook_mem_widget_color_scheme'); ?></textarea>
						<br> Keep it blank for transparent background <br> <a
						href="http://www.w3schools.com/html/html_colors.asp"
						target="_blank">Color Codes (Do not put #)</a></td>
				</tr>

				<tr valign="top" class="alternate">
					<th scope="row" style="width: 29%;"><label>Border Color <?=$new_icon?></label></th>
					<td><textarea id="styled"
							name="as_facebook_mem_widget_border_color" cols="18" rows="1"><?php echo get_option('as_facebook_mem_widget_border_color'); ?></textarea></td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 29%;"><label>If you like, help
							promote a plugin</label></th>
					<td><input name="as_fbmembers_show_sponser_link" type="checkbox"
						<?php if (get_option('as_fbmembers_show_sponser_link') != '-1') echo 'checked="checked"'; ?>
						value="1" /> <code>Check</code> to hide promotion link after
						widget</td>
				</tr>

			</table>
		</div>
	</div>
</div>

<a href="https://crunchify.com/facebook-members/" target="_blank">Feedback</a>
|
<a href="http://twitter.com/Crunchify" target="_blank">Twitter</a>
|
<a href="http://www.facebook.com/Crunchify" target="_blank">Facebook</a>

<div class="submit">

	<input name="my_fmz_update_setting" type="hidden"
		value="<?php echo wp_create_nonce('fmz-update-setting'); ?>" /> <input
		type="submit" name="info_update" class="button-primary"
		value="<?php _e('Update options'); ?> &raquo;" />

</div>
</form>

<?php
/**
 * Speedy Shipping From Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


?>
<tr class="speedy_row">
<td colspan="2" style="padding: 6px">

<form method="post" enctype="multipart/form-data" id="speedy_form">
	<?php if (!$abroad) { ?>
		<table>
		  <tr>
			<td style="text-align: right;"><?php _e( 'Доставка:', SPEEDY_TEXT_DOMAIN ); ?></td>
			<td><input type="radio" id="speedy_shipping_to_door" name="to_office" value="0" <?php if (!$to_office) { ?> checked="checked"<?php } ?> onclick="jQuery('#speedy_quarter_container,#speedy_street_container,#speedy_block_no_container,#speedy_note_container,#speedy_postcode_container,#speedy_street_no_container,#speedy_entrance_no_container,#speedy_floor_no_container,#speedy_apartment_no_container').show(); jQuery('#speedy_office_container').hide();" />
			  <label for="speedy_shipping_to_door"><?php _e( 'до врата', SPEEDY_TEXT_DOMAIN ); ?></label>
			  <input type="radio" id="speedy_shipping_to_office" name="to_office" value="1" <?php if ($to_office) { ?> checked="checked"<?php } ?> onclick="jQuery('#speedy_quarter_container,#speedy_street_container,#speedy_block_no_container,#speedy_note_container,#speedy_postcode_container,#speedy_street_no_container,#speedy_entrance_no_container,#speedy_floor_no_container,#speedy_apartment_no_container').hide(); jQuery('#speedy_office_container').show();" />
			  <label for="speedy_shipping_to_office"><?php _e( 'до офис', SPEEDY_TEXT_DOMAIN ); ?></label></td>
		  </tr>
		  <tr>
			<td style="text-align: right;"><label for="speedy_city"><?php _e( 'Населено място:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="speedy_city" name="city" value="<?php echo $city; ?>" />
			  <input type="hidden" id="speedy_city_id" name="city_id" value="<?php echo $city_id; ?>" />
			  <input type="hidden" id="speedy_city_nomenclature" name="city_nomenclature" value="<?php echo $city_nomenclature; ?>" />
			  <label for="speedy_postcode"><?php _e( 'ПК:', SPEEDY_TEXT_DOMAIN ); ?></label>
			  <input type="text" id="speedy_postcode" name="postcode" value="<?php echo $postcode; ?>" disabled="disabled" />
			</td>
		  </tr>
		  <tr id="speedy_quarter_container" <?php if ($to_office) { ?> style="display: none;"<?php } ?>>
			<td style="text-align: right;"><label for="speedy_quarter"><?php _e( 'Квартал:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="speedy_quarter" name="quarter" value="<?php echo $quarter; ?>" />
				<input type="hidden" id="speedy_quarter_id" name="quarter_id" value="<?php echo $quarter_id; ?>" /></td>
		  </tr>
		  <tr id="speedy_street_container" <?php if ($to_office) { ?> style="display: none;"<?php } ?>>
			<td style="text-align: right;"><label for="speedy_street"><?php _e( 'Улица:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="speedy_street" name="street" value="<?php echo $street; ?>" />
				<input type="hidden" id="speedy_street_id" name="street_id" value="<?php echo $street_id; ?>" />
				<label for="speedy_street_no"><?php _e( '№:', SPEEDY_TEXT_DOMAIN ); ?></label>
				<input type="text" id="speedy_street_no" name="street_no" value="<?php echo $street_no; ?>" />
			</td>
		  </tr>
		  <tr id="speedy_block_no_container" <?php if ($to_office) { ?> style="display: none;"<?php } ?>>
			<td style="text-align: right;"><label for="speedy_block_no"><?php _e( 'Бл.:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<input type="text" id="speedy_block_no" name="block_no" value="<?php echo $block_no; ?>" />
				<label for="speedy_entrance_no"><?php _e( 'Вх.:', SPEEDY_TEXT_DOMAIN ); ?></label>
				<input type="text" id="speedy_entrance_no" name="entrance_no" value="<?php echo $entrance_no; ?>" />
				<label for="speedy_floor_no"><?php _e( 'Ет.:', SPEEDY_TEXT_DOMAIN ); ?></label>
				<input type="text" id="speedy_floor_no" name="floor_no" value="<?php echo $floor_no; ?>" />
				<label for="speedy_apartment_no"><?php _e( 'Ап.:', SPEEDY_TEXT_DOMAIN ); ?></label>
				<input type="text" id="speedy_apartment_no" name="apartment_no" value="<?php echo $apartment_no; ?>" />
			</td>
		  </tr>
		  <tr id="speedy_note_container" <?php if ($to_office) { ?> style="display: none;"<?php } ?>>
			<td style="text-align: right;"><label for="speedy_note"><?php _e( 'Забележка към адреса:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="speedy_note" name="note" value="<?php echo $note; ?>" /></td>
		  </tr>
		  <tr id="speedy_office_container" <?php if (!$to_office) { ?> style="display: none;"<?php } ?>>
			<td style="text-align: right;"><label for="speedy_office_id"><?php _e( 'Офис:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><select id="speedy_office_id" name="office_id" style="width: 400px;">
				<?php if (!$offices) { ?>
					<option value="0" selected="selected"><?php _e( '--- Моля, въведете населено място ---', SPEEDY_TEXT_DOMAIN ); ?></option>
				<?php } else { ?>
					<option value="0" <?php if (!$office_id) { ?>selected="selected"<?php } ?>><?php _e( '--- Моля, въведете населено място ---', SPEEDY_TEXT_DOMAIN ); ?></option>
				<?php } ?>
				<?php foreach ($offices as $office) { ?>
				<?php if ($office['id'] == $office_id) { ?>
				<option value="<?php echo $office['id']; ?>" selected="selected"><?php echo $office['label']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $office['id']; ?>"><?php echo $office['label']; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			</td>
		  </tr>
		</table>
	<?php } else { ?>
		<table>
		  <tr>
			<td style="text-align: right;" width="75"><label for="speedy_country" class="speedy_required"><?php _e( 'Държава:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="speedy_country" name="country" value="<?php echo $country; ?>" <?php if ($country_disabled) { ?>disabled="disabled"<?php } ?> />
			  <input type="hidden" id="speedy_country_id" name="country_id" value="<?php echo $country_id; ?>" />
			  <input type="hidden" id="speedy_country_nomenclature" name="country_nomenclature" value="<?php echo $country_nomenclature; ?>" />
			  <label for="speedy_state" id="speedy_state_label" class="<?php if ($required_state) { ?>speedy_required<?php } ?>"><?php _e( 'Щат:', SPEEDY_TEXT_DOMAIN ); ?></label>
			  <input type="text" id="speedy_state" name="state" value="<?php echo $state; ?>" <?php if ($state_disabled) { ?>disabled="disabled"<?php } ?> />
			  <input type="hidden" id="speedy_state_id" name="state_id" value="<?php echo $state_id; ?>" />
			  <input type="hidden" id="speedy_required_state" name="required_state" value="<?php echo $required_state; ?>" />
			</td>
		  </tr>
		  <tr>
			<td style="text-align: right;"><label for="speedy_city" class="speedy_required"><?php _e( 'Населено място:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="speedy_city" name="city" value="<?php echo $city; ?>" />
			  <input type="hidden" id="speedy_city_id" name="city_id" value="<?php echo $city_id; ?>" />
			  <input type="hidden" id="speedy_city_nomenclature" name="city_nomenclature" value="<?php echo $city_nomenclature; ?>" />
			  <label for="speedy_postcode" id="speedy_postcode_label" class="<?php if ($required_postcode) { ?>speedy_required<?php } ?>"><?php _e( 'ПК:', SPEEDY_TEXT_DOMAIN ); ?></label>
			  <input type="text" id="speedy_postcode" name="postcode" value="<?php echo $postcode; ?>" />
			  <input type="hidden" id="speedy_required_postcode" name="required_postcode" value="<?php echo $required_postcode; ?>" />
			</td>
		  </tr>
		  <tr>
			<td style="text-align: right;"><label for="speedy_address_1" class="speedy_required"><?php _e( 'Адрес 1:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="speedy_address_1" name="address_1" value="<?php echo $address_1; ?>" /></td>
		  </tr>
		  <tr>
			<td style="text-align: right;"><label for="speedy_address_2"><?php _e( 'Адрес 2:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="speedy_address_2" name="address_2" value="<?php echo $address_2; ?>" /></td>
		  </tr>
		</table>
	<?php } ?>
	<input type="hidden" id="abroad" name="abroad" value="<?php echo $abroad; ?>" />
	<input type="hidden" id="speedy_cod_status" name="cod_status" value="<?php echo $cod_status; ?>" />
	<input type="hidden" id="speedy_active_currency_code" name="active_currency_code" value="<?php echo $active_currency_code; ?>" />
	<table id="speedy_cod_table" <?php if (!$cod_status) { ?> style="display: none;"<?php } ?>>
	  <tr>
		<td><?php _e( 'Спиди наложен платеж:', SPEEDY_TEXT_DOMAIN ); ?></td>
		<td><input type="radio" id="speedy_cod_yes" name="cod" value="1" <?php if ($cod) { ?> checked="checked"<?php } ?> />
		  <label for="speedy_cod_yes"><?php _e( 'Да', SPEEDY_TEXT_DOMAIN ); ?></label>
		  <input type="radio" id="speedy_cod_no" name="cod" value="0" <?php if (!$cod && !is_null($cod)) { ?> checked="checked"<?php } ?> />
		  <label for="speedy_cod_no"><?php _e( 'Не', SPEEDY_TEXT_DOMAIN ); ?></label>
		</td>
	  </tr>
	</table>

	<table>
		<tr>
			<td align="left"><b><?php _e( 'Кликнете Изчисли цена след въвеждане/промяна на данните.', SPEEDY_TEXT_DOMAIN ) ?></b></td>
			<td align="right">
				<input type="hidden" name="changed_data" id="changed_data" value="0" /> 
				<input type="button" class="button alt" name="speedy_submit" value="<?php _e( 'Изчисли цена', SPEEDY_TEXT_DOMAIN ) ?>" onclick="speedySubmit(false);" />
			</td>
	</tr>
	</table>


	<div id="speedy_methods" <?php if (empty($speedy_methods)) { ?> style="display: none;"<?php } ?>>
		<?php if (!empty($speedy_methods)) : ?>
		<table><tr><td colspan="3"><?php _e( 'Изберете услуга', SPEEDY_TEXT_DOMAIN ); ?></td></tr>
		<?php foreach ($speedy_methods as $speedy_method)  : ?>
		 <tr>
			<td>
				<input type="radio" name="speedy_shipping_method_id" id="speedy.<?php echo $speedy_method['code']; ?>" value="<?php echo $speedy_method['code']; ?>" <?php if ($speedy_method['code'] == $speedy_shipping_method_id) { ?>checked="checked"<?php } ?> /> <label for="speedy.<?php echo $speedy_method['code']; ?>"><?php echo $speedy_method['title']; ?></label>
				<input type="hidden" name="shipping_method_price" id="speedy_price_<?php echo $speedy_method['code']; ?>" value="<?php echo $speedy_method['cost']; ?>"  disabled="disabled" /> 
				<br />
				<?php if ( $speedy_method['default_fixed_time'] ) : ?>
					<div>
						<input <?php if ($speedy_method['code'] != $speedy_shipping_method_id) { ?>style="display: none;"<?php } ?> class="fixed_time" type="checkbox" id="speedy_fixed_time_cb_<?php echo $speedy_method['code']; ?>" <?php if ( $speedy_method['fixed_time_cb'] ) { ?>checked="checked"<?php } ?> name="fixed_time_cb[<?php echo $speedy_method['code']; ?>]" value="1" onclick="speedyCheckFixedTime(<?php echo $speedy_method['code']; ?>);" />
						<label <?php if ($speedy_method['code'] != $speedy_shipping_method_id) { ?>style="display: none;"<?php } ?> id="speedy_fixed_time_cb_label_<?php echo $speedy_method['code']; ?>" class="fixed_time" for="speedy_fixed_time_cb_<?php echo $speedy_method['code']; ?>"> <?php _e( 'Фиксиран час:', SPEEDY_TEXT_DOMAIN ); ?></label>
						<select <?php if ($speedy_method['code'] != $speedy_shipping_method_id) { ?>style="display: none;"<?php } ?> class="fixed_time" id="speedy_fixed_time_hour_<?php echo $speedy_method['code']; ?>" name="fixed_time_hour[<?php echo $speedy_method['code']; ?>]" <?php if ( !$speedy_method['fixed_time_cb'] ) { ?>disabled="disabled"<?php } ?> onchange="speedySetFixedTime(<?php echo $speedy_method['code']; ?>);">
							<?php for ($i = 10; $i <= 17; $i++) :
								$hour = str_pad($i, 2, '0', STR_PAD_LEFT);
								if ($hour == $speedy_method['fixed_time_hour'] || !$speedy_method['fixed_time_hour'] ) :
									$speedy_method['fixed_time_hour'] = $hour; ?>
									<option value="<?php echo $hour; ?>" selected="selected"><?php echo $hour; ?></option>
								<?php else : ?>
									<option value="<?php echo $hour; ?>"><?php echo $hour; ?></option>
								<?php endif; ?>
							<?php endfor; ?>
						</select>
						
						<select <?php if ($speedy_method['code'] != $speedy_shipping_method_id) { ?>style="display: none;"<?php } ?> class="fixed_time" id="speedy_fixed_time_min_<?php echo $speedy_method['code']; ?>" name="fixed_time_min[<?php echo $speedy_method['code']; ?>]" <?php if ( !$speedy_method['fixed_time_cb'] ) { ?>disabled="disabled"<?php } ?> >
							<?php if ($speedy_method['fixed_time_hour'] == 10 ) :
								$min_fixed_time_mins = 30;
							else :
								$min_fixed_time_mins = 0;
							endif; 
							if ($speedy_method['fixed_time_hour'] == 17 ) :
								$max_fixed_time_mins = 30;
							else :
								$max_fixed_time_mins = 59;
							endif; 

							for ($i = $min_fixed_time_mins; $i <= $max_fixed_time_mins; $i++) :
								$hour = str_pad($i, 2, '0', STR_PAD_LEFT);
								if ($hour == $speedy_method['fixed_time_min'] ) : ?>
									<option value="<?php echo $hour; ?>" selected="selected"><?php echo $hour; ?></option>
								<?php else : ?>
								<option value="<?php echo $hour; ?>"><?php echo $hour; ?></option>
								<?php endif; ?>
							<?php endfor; ?>
						</select>
						<?php if ($speedy_method['error_fixed_time'] ) : ?>
							<br />
							<span style="color: red;" class="error_fixed_time"><?php _e( 'Моля, въведете валиден час!', SPEEDY_TEXT_DOMAIN ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<?php if ($speedy_method['total_form']) : ?>
					<table <?php if ( $speedy_shipping_method_id != $speedy_method['code'] ) { ?>style="display:none;"<?php } ?> class="speedy_<?php echo $speedy_method['code']; ?> speedy_table">
					<?php foreach ($speedy_method['total_form'] as $total_form) : ?>
						<tr>
							<td><?php echo $total_form['label']; ?></td>
							<td><?php echo $total_form['value']; ?></td>
						</tr>
					<?php endforeach; ?>
					</table>
				<?php endif; ?>
			</td>
			<td class="right speedy_table_right"><?php echo $speedy_method['text']; ?></td>
		</tr>
		<?php endforeach; ?>
		</table>
		<?php endif; ?>
	</div>

	<div id="speedy_compare_address_warning" class="woocommerce-info" style="display: none;"></div>

</form>
</td>
</tr>

<script type="text/javascript"><!--
var wc_speedy_shipping_method_id = '<?php echo $wc_speedy_shipping_method_id; ?>';
var error_continue_message = '<?php _e( 'За да продължите, трябва да изчислите цена и да изберете услуга за доставка!', SPEEDY_TEXT_DOMAIN ); ?>';
var error_cyrillic = '<?php _e( 'Моля, използвайте само латински символи!', SPEEDY_TEXT_DOMAIN ); ?>';

jQuery( 'body' ).on( 'updated_checkout', function () {
	// Check COD payment method availability
	if ( jQuery('select.shipping_method :selected, input[name^=shipping_method][type=radio]:checked').val() == wc_speedy_shipping_method_id ) {
		if (jQuery('input[name=\'cod\']:checked').val() == 1) {
			jQuery('#payment ul li').hide();
			jQuery('#payment ul li').each(function( index ) {
				jQuery(this).find('input:radio').attr('checked', '');
				if (jQuery(this).hasClass('payment_method_cod')) {
					jQuery(this).show();
					jQuery(this).find('input:radio').attr('checked', 'checked');
					jQuery(this).find('div.payment_box').show();
				}
			});
		} else if (jQuery('input[name=\'cod\']:checked').val() == 0) {
			jQuery('#payment ul li').hide();
			jQuery('#payment ul li').each(function( index ) {
				jQuery(this).find('input:radio').attr('checked', '');
				if (!jQuery(this).hasClass('payment_method_cod')) {
					jQuery(this).show();
				}
			});
			jQuery('#payment ul li').each(function( index ) {
				if (!jQuery(this).hasClass('payment_method_cod')) {
					jQuery(this).find('input:radio').attr('checked', 'checked');
					jQuery(this).find('div.payment_box').show();
					return false;
				}
			});
		} else {
			jQuery('#payment ul li').hide();
			jQuery('input#place_order').hide();
			jQuery('input#place_order').after('<span id="calculate_price">'+error_continue_message+'</span>');
		}
	}

	// Check for fixed time error
	if (jQuery('#speedy_form').find('.error_fixed_time').length != 0) {
		if (jQuery('#calculate_price').length == 0) {
			jQuery('input#place_order').hide();
			jQuery('input#place_order').after('<span id="calculate_price">'+error_continue_message+'</span>');
		}
	}
});

jQuery(document).ready(function() {
	jQuery('input[name=\'cod\'], input[name=\'to_office\'], input[name=\'postcode\'], input[name=\'city\'], input[name=\'quarter\'], input[name=\'street\'], input[name=\'street_no\'], input[name=\'object\'], input[name=\'block_no\'], input[name=\'entrance_no\'], input[name=\'floor_no\'], input[name=\'apartment_no\'], input[name=\'note\'], select[name=\'office_id\']').live('change', function() {
		setChangedDataValue(false);
	});

	jQuery('input[name^=\'fixed_time_cb\'], select[name^=\'fixed_time_hour\'], select[name^=\'fixed_time_min\']').live('change', function() {
		setChangedDataValue(true);
	});

	if ( jQuery('select.shipping_method :selected, input[name^=shipping_method][type=radio]:checked').val() == wc_speedy_shipping_method_id ) {
		jQuery('input#place_order').hide();
		jQuery('input#place_order').after('<span id="calculate_price">'+error_continue_message+'</span>');
	}

	jQuery('#speedy_form input').keypress(function(event){
		if ((jQuery('#abroad').val() == 1) && event.key.match(/[а-яА-я]/)) {
			event.preventDefault();
			alert(error_cyrillic);
		}
	});

	jQuery('#speedy_form input:text').focusout(function(event){
		speedy_clear_input(jQuery(this));
	});

	jQuery('#speedy_form input:text').each(function(index) {
		speedy_clear_input(jQuery(this));
	});
});

function setChangedDataValue(fixed_time) {
		if (!fixed_time) {
			jQuery('#speedy_methods').html('');
			jQuery('#speedy_compare_address_warning').hide();
		}

		jQuery('input#changed_data').val('1');

		jQuery('input#place_order').hide();

		if (jQuery("#calculate_price").length == 0) {
			jQuery('input#place_order').after('<span id="calculate_price">'+error_continue_message+'</span>');
		}
}

function speedySubmit(next) {
	speedy_disabled = jQuery('#speedy_form input:disabled');
	jQuery('#speedy_form :input').removeAttr('disabled');

	jQuery.ajax({
		url: '<?php echo admin_url('admin-ajax.php'); ?>',
		type: 'POST',
		data: {
			action: 'speedy_submit_form',
			data: jQuery('#speedy_form').serialize()
		},
		dataType: 'json',
		beforeSend: function() {
			jQuery(".speedy_error").remove();

			jQuery( '.woocommerce-checkout-review-order-table' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			jQuery( '.woocommerce-checkout-payment' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			if (jQuery('#calculate_price').length == 0) {
				jQuery('input#place_order').hide();
				jQuery('input#place_order').after('<span id="calculate_price">'+error_continue_message+'</span>');
			}
		},
		complete: function( data ) {
			speedy_disabled.attr('disabled', true);

			if (jQuery('#speedy_form').find('.error_fixed_time').length != 0) {
				jQuery('#speedy_form').prepend( '<ul class="woocommerce-error speedy_error"><?php _e( 'Моля, изберете валиден час и натиснете изчисли!', SPEEDY_TEXT_DOMAIN ); ?></ul>' );

				add_offset = 0;
				if (jQuery('#wpadminbar').length != 0) {
					add_offset = jQuery('#wpadminbar').height();
				}
				jQuery('html, body').animate({
					scrollTop: jQuery('ul.woocommerce-error.speedy_error').offset().top - add_offset
				}, 500);
			} else if (jQuery(".speedy_error").length == 0) {
				if (jQuery("#calculate_price").length != 0) {
					jQuery("#calculate_price").remove();
					jQuery('input#place_order').show();
				}
			}
		},
		success: function(data) {
			if (data.status == false) {
				jQuery.each(data.error, function(i) {
					jQuery('#speedy_form').prepend( '<ul class="woocommerce-error speedy_error">' + data.error[i] + '</ul>' );
				});

				add_offset = 0;
				if (jQuery('#wpadminbar').length != 0) {
					add_offset = jQuery('#wpadminbar').height();
				}
				jQuery('html, body').animate({
					scrollTop: jQuery('ul.woocommerce-error.speedy_error').offset().top - add_offset
				}, 500);

				jQuery('#speedy_form :input :disabled').attr('disabled', true);
				jQuery( '.woocommerce-checkout-review-order-table' ).unblock();
				jQuery( '.woocommerce-checkout-payment' ).unblock();
			} else {
				jQuery('#speedy_methods').html('');
				jQuery('#speedy_compare_address_warning').hide();
				<?php if (!$abroad) { ?>
					jQuery('#speedy_postcode').attr('disabled', 'disabled');
				<?php } ?>

				if (data.methods) {
					html = "<table><tr><td colspan=\"3\"><?php _e( 'Изберете услуга', SPEEDY_TEXT_DOMAIN ); ?></td></tr>";

					if (data.methods.length) {
						for (i = 0; i < data.methods.length; i++) {
							html += '<tr>';
							html += '  <td>';
							html += '    <input type="radio" name="speedy_shipping_method_id" id="speedy.'+data.methods[i]['code']+'" value="'+data.methods[i]['code']+'" ';
							if (data.shipping_method_id == data.methods[i]['code']) {
								html += 'checked="checked"';
							}

							html += ' /> <label for="speedy.'+data.methods[i]['code']+'">'+data.methods[i]['title']+'</label><br /> <input type="hidden" name="shipping_method_price" id="speedy_price_'+data.methods[i]['code']+'" value="'+data.methods[i]['cost']+'" disabled="disabled" /> ';

							if (data.methods[i]['default_fixed_time']) {
								html += '<div>';
									style = ''
									if (data.shipping_method_id != data.methods[i]['code']) {
										style = 'style="display: none;"';
									}
	
								html += '<input ' + style + ' class="fixed_time" type="checkbox" ';
								if (data.methods[i]['fixed_time_cb']) {
									html += 'checked="checked"';
								}
								html += ' id="speedy_fixed_time_cb_'+data.methods[i]['code']+'" name="fixed_time_cb['+data.methods[i]['code']+']" value="1" onclick="speedyCheckFixedTime('+data.methods[i]['code']+');" />';
								html += "<label " + style + " id='speedy_fixed_time_cb_label_" + data.methods[i]['code'] + "' class=\"fixed_time\" for=\"speedy_fixed_time_cb_"+data.methods[i]['code']+"\"> <?php _e( 'Фиксиран час:', SPEEDY_TEXT_DOMAIN ); ?></label>";
								html += '<select ' + style + ' class="fixed_time" id="speedy_fixed_time_hour_'+data.methods[i]['code']+'" name="fixed_time_hour['+data.methods[i]['code']+']" ';
								if (!data.methods[i]['fixed_time_cb']) {
									html += 'disabled="disabled"';
								}
								html += ' onchange="speedySetFixedTime('+data.methods[i]['code']+');">';
								for (n=10; n<18; n++) {
									hour_str_pad = str_pad(n, 2, '0', 'STR_PAD_LEFT');
									if (hour_str_pad == data.methods[i]['fixed_time_hour'] || !data.methods[i]['fixed_time_hour']) {
										html += '<option value="'+hour_str_pad+'" selected="selected">'+hour_str_pad+'</option>';
									} else {
										html += '<option value="'+hour_str_pad+'">'+hour_str_pad+'</option>';
									}
								}
								html += '</select>';

								if (data.methods[i]['fixed_time_hour'] == 10) {
									min_fixed_time_mins = 30;
								} else {
									min_fixed_time_mins = 0;
								}

								if (data.methods[i]['fixed_time_hour'] == 17) {
									max_fixed_time_mins = 30;
								} else {
									max_fixed_time_mins = 59;
								}

								html += '<select ' + style + ' class="fixed_time" id="speedy_fixed_time_min_'+data.methods[i]['code']+'" name="fixed_time_min['+data.methods[i]['code']+']" ';
								if (!data.methods[i]['fixed_time_cb']) {
									html += 'disabled="disabled"';
								}
								html += '>';
								for (n=min_fixed_time_mins; n<=max_fixed_time_mins; n++) {
									hour_str_pad = str_pad(n, 2, '0', 'STR_PAD_LEFT');
									if (data.methods[i]['code'] && hour_str_pad == data.methods[i]['fixed_time_min']) {
										html += '<option value="'+hour_str_pad+'" selected="selected">'+hour_str_pad+'</option>';
									} else {
										html += '<option value="'+hour_str_pad+'">'+hour_str_pad+'</option>';
									}
								}
								html += '</select>';
								if (data.methods[i]['error_fixed_time']) {
									html += '<br /><span style="color: red;" class="error_fixed_time"><?php _e( 'Моля, изберете валиден час!', SPEEDY_TEXT_DOMAIN ); ?></span>';
								}
								html += '</div>';
							}

							if (data.methods[i]['total_form'].length) {
								html += '  <table ';
								if (data.shipping_method_id != data.methods[i]['code']) {
									html += 'style="display: none;"';
								}
								html += ' class="speedy_'+data.methods[i]['code']+' speedy_table">';
								for (j = 0; j< data.methods[i]['total_form'].length; j++) {
								html += '    <tr>';
								html += '      <td>'+data.methods[i]['total_form'][j]['label']+'</td>';
								html += '      <td>'+data.methods[i]['total_form'][j]['value']+'</td>';
								html += '    </tr>';
								}
								html += '  </table>';
							}

							html += '</td>';
							html += '<td class="right speedy_table_right">'+data.methods[i]['text']+'</td></tr>';
						}
					} else {
						html += "<tr><td colspan=\"3\"><?php _e( 'Няма намерени услуги!', SPEEDY_TEXT_DOMAIN ); ?></td></tr>";
					}

					html += '</table>';

					jQuery('#speedy_methods').html(html);
					jQuery('#speedy_methods').show();

					jQuery('input[name=\'speedy_shipping_method_id\']:checked').trigger('change');

					if (jQuery('input#changed_data').val() == 1) {
						setSpeedyMethod(jQuery('input[name=\'speedy_shipping_method_id\']:checked').val(), jQuery('#speedy_price_' + jQuery('input[name=\'speedy_shipping_method_id\']:checked').val()).val(), false);
					} else {
						jQuery('#speedy_form :input :disabled').attr('disabled', true);
						jQuery( '.woocommerce-checkout-review-order-table' ).unblock();
						jQuery( '.woocommerce-checkout-payment' ).unblock();
					}
				}

				jQuery('input[name=\'speedy_shipping_method_id\']').change( function () {
					if (jQuery(this).is(':checked')) {
						setSpeedyMethod(jQuery(this).val(), jQuery('#speedy_price_' + jQuery(this).val()).val(), true);
					}
				});

				jQuery('#speedy_form :input').removeAttr('disabled');
				/*
				jQuery.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: 'POST',
					data: {
						action: 'speedy_compare_address',
						data: jQuery('#speedy_form').serialize()
					},
					dataType: 'json',
					beforeSend: function() {
						jQuery('#speedy_compare_address_warning').hide();
					},
					success: function(json) {
						if (json.error) {
							jQuery('#speedy_compare_address_warning').html(json.warning);
							jQuery('#speedy_compare_address_warning').show();
						} else {
							jQuery('#speedy_compare_address_warning').hide();
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
					}
				});
				*/
				speedy_disabled.attr('disabled', true);

			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
		}
	});
}

jQuery('input[name=\'speedy_shipping_method_id\']').change( function () {
	if (jQuery(this).is(':checked')) {
		setSpeedyMethod(jQuery(this).val(), jQuery('#speedy_price_' + jQuery(this).val()).val(), true);
	}
});


jQuery('input[name=\'cod\']').change(function () {
	if (jQuery('input[name=\'cod\']:checked').val() == 1) {
		jQuery('#payment ul li').hide();
		jQuery('#payment ul li').each(function( index ) {
			jQuery(this).find('input:radio').attr('checked', '');
			if (jQuery(this).hasClass('payment_method_cod')) {
				jQuery(this).show();
				jQuery(this).find('input:radio').attr('checked', 'checked');
				jQuery(this).find('div.payment_box').show();
			}
		});
	} else {
		jQuery('#payment ul li').hide();
		jQuery('#payment ul li').each(function( index ) {
			jQuery(this).find('input:radio').attr('checked', '');
			if (!jQuery(this).hasClass('payment_method_cod')) {
				jQuery(this).show();
			}
		});
		jQuery('#payment ul li').each(function( index ) {
			if (!jQuery(this).hasClass('payment_method_cod')) {
				jQuery(this).find('input:radio').attr('checked', 'checked');
				return false;
			}
		});
	}
});

function speedyCheckFixedTime(method_id) {
	if (jQuery('#speedy_fixed_time_cb_'+method_id+':checked').length) {
		jQuery('#speedy_fixed_time_hour_'+method_id).removeAttr('disabled');
		jQuery('#speedy_fixed_time_min_'+method_id).removeAttr('disabled');
	} else {
		jQuery('#speedy_fixed_time_hour_'+method_id).attr('disabled', 'disabled');
		jQuery('#speedy_fixed_time_min_'+method_id).attr('disabled', 'disabled');
	}
}

function setSpeedyMethod(method_id, price, block) {
	if (method_id) {
		jQuery.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			type: 'POST',
			data: {
				action: 'set_speedy_method',
				method_id: encodeURIComponent(method_id),
				method_price: encodeURIComponent(price)
			},
			dataType: 'json',
			beforeSend: function() {
				if (block) {
					jQuery( '.woocommerce-checkout-review-order-table' ).block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					});
					jQuery( '.woocommerce-checkout-payment' ).block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					});
				}
			},
			complete: function( data ) {
				jQuery( '.woocommerce-checkout-review-order-table' ).unblock();
				jQuery( '.woocommerce-checkout-payment' ).unblock();
			},
			success: function(json) {
				jQuery('table.speedy_table').hide();
				jQuery('table.speedy_' + method_id).show();
				

				jQuery('input[name^=\'fixed_time_cb\']').attr('disabled', true);
				jQuery('input[name^=\'fixed_time_cb\']').addClass('fixed_time');
				jQuery('select[name^=\'fixed_time_hour\']').addClass('fixed_time');
				jQuery('select[name^=\'fixed_time_min\']').addClass('fixed_time');
				jQuery('input[name^=\'fixed_time_cb\']').parent().hide();
				jQuery('.fixed_time').hide();

				jQuery('#speedy_fixed_time_cb_'+method_id).parent().show();
				jQuery('#speedy_fixed_time_cb_'+method_id).show();
				jQuery('#speedy_fixed_time_cb_'+method_id).removeAttr('disabled');
				jQuery('#speedy_fixed_time_cb_'+method_id).removeClass('fixed_time');
				jQuery('#speedy_fixed_time_cb_label_'+method_id).show();
				jQuery('#speedy_fixed_time_hour_'+method_id).show();
				jQuery('#speedy_fixed_time_hour_'+method_id).removeClass('fixed_time');
				jQuery('#speedy_fixed_time_min_'+method_id).show();
				jQuery('#speedy_fixed_time_min_'+method_id).removeClass('fixed_time');

				jQuery( '.order-total' ).find( '.amount' ).html(json.new_total);

				if ( json.woocommerce_shipping_method_format == 'select' ) {
					jQuery('select.shipping_method :selected').html(json.price_text);
				} else {
					jQuery('input[name^=shipping_method][type=radio]:checked').parent().find('label').html(json.price_text);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
			}
		});
	} else {
		jQuery( '.woocommerce-checkout-review-order-table' ).unblock();
		jQuery( '.woocommerce-checkout-payment' ).unblock();
	}
}

function speedySetFixedTime(method_id) {
	if (jQuery('#speedy_fixed_time_hour_'+method_id).val() == 10) {
		min_fixed_time_mins = 30;
	} else {
		min_fixed_time_mins = 0;
	}

	if (jQuery('#speedy_fixed_time_hour_'+method_id).val() == 17) {
		max_fixed_time_mins = 30;
	} else {
		max_fixed_time_mins = 59;
	}

	html = '';

	for (i = min_fixed_time_mins; i <= max_fixed_time_mins; i++) {
		iStr = i.toString();

		if (iStr.length < 2) {
			fixed_time_min = '0' + i;
		} else {
			fixed_time_min = i;
		}

		html += '<option value="' + fixed_time_min + '">' + fixed_time_min + '</option>';
	}

	jQuery('#speedy_fixed_time_min_'+method_id).html(html);
}

// Autocomplete functions
var speedy_city = '<?php echo $city; ?>';
var speedy_quarter = '<?php echo $quarter; ?>';
var speedy_street = '<?php echo $street; ?>';
var speedy_country = '<?php echo $country; ?>';
var speedy_state = '<?php echo $state; ?>';

jQuery(document).ready(function() {
	jQuery( "#speedy_city" ).autocomplete({
		source: function(request, response) {
			var $this = jQuery(this);
			var $element = jQuery(this.element);
			var jqXHR = $element.data('jqXHR');
			if (jqXHR) {
				jqXHR.abort();
			}
			$element.data('jqXHR', jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				dataType: 'json',
				data: {
					action: 'get_cities',
					term: request.term,
					country_id: jQuery('#speedy_country_id').val(),
					abroad: '<?php echo $abroad; ?>'
				},
				complete: function() {
					$this.removeData('jqXHR');
				},
				success: function(data) {
					if (jQuery('#speedy_country_nomenclature').val() == 'FULL') {
						if (data.length) {
							response(data);
						}
					} else {
						response(data);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
				}
			}));
		},
		delay: 500,
		minLength: 1,
		select: function(event, ui) {
			if (ui.item) {
				speedy_city = ui.item.value;
				jQuery('#speedy_postcode').val(ui.item.postcode);
				jQuery('#speedy_city_id').val(ui.item.id);
				jQuery('#speedy_city_nomenclature').val(ui.item.nomenclature);
				jQuery('#speedy_quarter').val('');
				jQuery('#speedy_quarter_id').val('');
				jQuery('#speedy_street').val('');
				jQuery('#speedy_street_id').val('');
				jQuery('#speedy_street_no').val('');
				jQuery('#speedy_block_no').val('');
				jQuery('#speedy_entrance_no').val('');
				jQuery('#speedy_floor_no').val('');
				jQuery('#speedy_apartment_no').val('');
				jQuery('#speedy_note').val('');
				jQuery('#speedy_office_id').html('<option value="0"><?php _e( ' --- Моля, изчакайте --- ', SPEEDY_TEXT_DOMAIN ); ?></option>');

				jQuery.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					dataType: 'json',
					data: {
						action: 'get_offices',
						city_id: ui.item.id,
						abroad: '<?php echo $abroad; ?>'
					},
					success: function(data) {
						if (data.error) {
							alert(data.error);
						} else {
							html = '';

							if (data.length) {
								html += '<option value="0"><?php _e( ' --- Моля, изберете офис --- ', SPEEDY_TEXT_DOMAIN ); ?></option>';
								for (i = 0; i < data.length; i++) {
									html += '<option value="' + data[i]['id'] + '">' + data[i]['label'] + '</option>';
								}
							} else {
								html += '<option value="0"><?php _e( ' --- Моля, въведете населено място --- ', SPEEDY_TEXT_DOMAIN ); ?></option>';
							}

							jQuery('#speedy_office_id').html(html);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
					}
				});
			}
		},
		change: function(event, ui) {
			if(!ui.item && jQuery('#speedy_country_nomenclature').val() == 'FULL') {
				jQuery('#speedy_city').val('');
				jQuery('#speedy_city_id').val('');
				jQuery('#speedy_city_nomenclature').val('');
				jQuery('#speedy_postcode').val('');
				jQuery('#speedy_office_id').html('<option value="0"><?php _e( ' --- Моля, въведете населено място --- ', SPEEDY_TEXT_DOMAIN ); ?></option>');
			}

			jQuery('#speedy_quarter').val('');
			jQuery('#speedy_quarter_id').val('');
			jQuery('#speedy_street').val('');
			jQuery('#speedy_street_id').val('');
			jQuery('#speedy_street_no').val('');
			jQuery('#speedy_block_no').val('');
			jQuery('#speedy_entrance_no').val('');
			jQuery('#speedy_floor_no').val('');
			jQuery('#speedy_apartment_no').val('');
			jQuery('#speedy_note').val('');
		}
	});

	jQuery('#speedy_city').blur(function() {
		var $this = jQuery(this);
		var jqXHR = jQuery(this).data('jqXHR');
		if (jqXHR) {
			jqXHR.abort();
		}
		$this.removeData('jqXHR');

		if ($this.val() != speedy_city) {

			if (!jQuery('#abroad').val() || (jQuery('#abroad').val() && (jQuery('#speedy_country_nomenclature').val() == 'FULL'))) {
				jQuery('#speedy_city').val('');
			}

			jQuery('#speedy_city_id').val('');
			jQuery('#speedy_city_nomenclature').val('');
			jQuery('#speedy_postcode').val('');
			jQuery('#speedy_office_id').html('<option value="0"><?php _e( ' --- Моля, въведете населено място --- ', SPEEDY_TEXT_DOMAIN ); ?></option>');
			jQuery('#speedy_quarter').val('');
			jQuery('#speedy_quarter_id').val('');
			jQuery('#speedy_street').val('');
			jQuery('#speedy_street_id').val('');
			jQuery('#speedy_street_no').val('');
			jQuery('#speedy_block_no').val('');
			jQuery('#speedy_entrance_no').val('');
			jQuery('#speedy_floor_no').val('');
			jQuery('#speedy_apartment_no').val('');
			jQuery('#speedy_note').val('');
		}
	});

	jQuery('#speedy_quarter').autocomplete({
		source: function(request, response) {
			var $this = jQuery(this);
			var $element = jQuery(this.element);
			var jqXHR = $element.data('jqXHR');
			if (jqXHR) {
				jqXHR.abort();
			}
			$element.data('jqXHR', jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				dataType: 'json',
				data: {
					action: 'get_quarters',
					term: request.term,
					city_id: function() { return jQuery('#speedy_city_id').val(); },
					abroad: '<?php echo $abroad; ?>'
				},
				complete: function() {
					$this.removeData('jqXHR');
				},
				success: function(data) {
					if (data.error) {
						jQuery('#speedy_quarter').val('');
						jQuery('#speedy_quarter_id').val('');
						alert(data.error);
					} else {
						if (jQuery('#speedy_city_nomenclature').val() == 'FULL') {
							if (data.length) {
								response(data);
							}
						} else {
							response(data);
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
				}
			}));
		},
		minLength: 1,
		select: function(event, ui) {
			if (ui.item) {
				speedy_quarter = ui.item.value;
				jQuery('#speedy_quarter_id').val(ui.item.id);
			}
		},
		change: function(event, ui) {
			if(!ui.item && jQuery('#speedy_city_nomenclature').val() == 'FULL') {
				jQuery('#speedy_quarter').val('');
				jQuery('#speedy_quarter_id').val('');
			}
		}
	});

	jQuery('#speedy_quarter').blur(function() {
		var $this = jQuery(this);
		var jqXHR = jQuery(this).data('jqXHR');
		if (jqXHR) {
			jqXHR.abort();
		}
		$this.removeData('jqXHR');

		if (($this.val() != speedy_quarter) && (jQuery('#speedy_city_nomenclature').val() == 'FULL')) {
			jQuery('#speedy_quarter').val('');
			jQuery('#speedy_quarter_id').val('');
		}
	});

	jQuery('#speedy_street').autocomplete({
		source: function(request, response) {
			var $this = jQuery(this);
			var $element = jQuery(this.element);
			var jqXHR = $element.data('jqXHR');
			if (jqXHR) {
				jqXHR.abort();
			}
			$element.data('jqXHR', jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				dataType: 'json',
				data: {
					action: 'get_streets',
					term: request.term,
					city_id: function() { return jQuery('#speedy_city_id').val(); },
					abroad: '<?php echo $abroad; ?>'
				},
				complete: function() {
					$this.removeData('jqXHR');
				},
				success: function(data) {
					if (data.error) {
						jQuery('#speedy_street').val('');
						jQuery('#speedy_street_id').val('');
						alert(data.error);
					} else {
						if (jQuery('#speedy_city_nomenclature').val() == 'FULL') {
							if (data.length) {
								response(data);
							}
						} else {
							response(data);
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
				}
			}));
		},
		minLength: 1,
		select: function(event, ui) {
			if (ui.item) {
				speedy_street = ui.item.value;
				jQuery('#speedy_street_id').val(ui.item.id);
			}
		},
		change: function(event, ui) {
			if(!ui.item && jQuery('#speedy_city_nomenclature').val() == 'FULL') {
				jQuery('#speedy_street').val('');
				jQuery('#speedy_street_id').val('');
			}
		}
	});

	jQuery('#speedy_street').blur(function() {
		var $this = jQuery(this);
		var jqXHR = jQuery(this).data('jqXHR');
		if (jqXHR) {
			jqXHR.abort();
		}
		$this.removeData('jqXHR');

		if (($this.val() != speedy_street) && (jQuery('#speedy_city_nomenclature').val() == 'FULL')) {
			jQuery('#speedy_street').val('');
			jQuery('#speedy_street_id').val('');
		}
	});

	jQuery('#speedy_block_no').autocomplete({
		source: function(request, response) {
			var $this = jQuery(this);
			var $element = jQuery(this.element);
			var jqXHR = $element.data('jqXHR');
			if (jqXHR) {
				jqXHR.abort();
			}
			$element.data('jqXHR', jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				dataType: 'json',
				data: {
					action: 'get_blocks',
					term: request.term,
					city_id: function() { return jQuery('#speedy_city_id').val(); },
					abroad: '<?php echo $abroad; ?>'
				},
				complete: function() {
					$this.removeData('jqXHR');
				},
				success: function(data) {
					if (data.error) {
						jQuery('#speedy_block_no').val('');
						alert(data.error);
					} else {
						response(data);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
				}
			}));
		},
		minLength: 1
	});

	jQuery('#speedy_block_no').blur(function() {
		var $this = jQuery(this);
		var jqXHR = jQuery(this).data('jqXHR');
		if (jqXHR) {
			jqXHR.abort();
		}
		$this.removeData('jqXHR');
	});

	jQuery('#speedy_country').autocomplete({
		source: function(request, response) {
			var $this = jQuery(this);
			var $element = jQuery(this.element);
			var jqXHR = $element.data('jqXHR');
			if (jqXHR) {
				jqXHR.abort();
			}
			$element.data('jqXHR', jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				dataType: 'json',
				data: {
					action: 'get_countries',
					term: request.term,
					abroad: '<?php echo $abroad; ?>'
				},
				complete: function() {
					$this.removeData('jqXHR');
				},
				success: function(data) {
					if (data.error) {
						jQuery('#speedy_country').val('');
						jQuery('#speedy_country_id').val('');
						jQuery('#speedy_country_nomenclature').val('');
						jQuery('#speedy_state').val('');
						jQuery('#speedy_state_id').val('');
						alert(data.error);
					} else {
						response(data);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
				}
			}));
		},
		minLength: 1,
		select: function(event, ui) {
			if (ui.item) {
				speedy_country = ui.item.value;
				jQuery('#speedy_country').val(ui.item.value);
				jQuery('#speedy_country_id').val(ui.item.id);
				jQuery('#speedy_country_nomenclature').val(ui.item.nomenclature);
				jQuery('#speedy_required_state').val(ui.item.required_state);
				jQuery('#speedy_required_postcode').val(ui.item.required_postcode);
				jQuery('#speedy_active_currency_code').val(ui.item.active_currency_code);

				if (!ui.item.active_currency_code) {
					jQuery('#speedy_cod_table').hide();
					jQuery('#speedy_cod_no').click();
					jQuery('#speedy_cod_status').val(0);
				} else {
					jQuery('#speedy_cod_table').show();
					jQuery('#speedy_cod_status').val(1);
				}

				if (ui.item.required_state) {
					jQuery('#speedy_state_label').addClass('speedy_required');
				} else {
					jQuery('#speedy_state_label').removeClass('speedy_required');
				}

				if (ui.item.required_postcode) {
					jQuery('#speedy_postcode_label').addClass('speedy_required');
				} else {
					jQuery('#speedy_postcode_label').removeClass('speedy_required');
				}
			}
		},
		change: function(event, ui) {
			if (!ui.item) {
				jQuery('#speedy_country').val('');
				jQuery('#speedy_country_id').val('');
				jQuery('#speedy_country_nomenclature').val('');
			}
			jQuery('#speedy_state').val('');
			jQuery('#speedy_state_id').val('');
			jQuery('#speedy_city').val('');
			jQuery('#speedy_city_id').val('');
			jQuery('#speedy_city_nomenclature').val('');
			jQuery('#speedy_postcode').val('');
			}
		}
	);

	jQuery('#speedy_country').blur(function() {
		var $this = jQuery(this);
		var jqXHR = jQuery(this).data('jqXHR');
		if (jqXHR) {
			jqXHR.abort();
		}
		$this.removeData('jqXHR');

		if ($this.val() != speedy_country) {
			$this.val('');
			jQuery('#speedy_country_id').val('');
			jQuery('#speedy_country_nomenclature').val('');
			jQuery('#speedy_state').val('');
			jQuery('#speedy_state_id').val('');
			jQuery('#speedy_city').val('');
			jQuery('#speedy_city_id').val('');
			jQuery('#speedy_city_nomenclature').val('');
			jQuery('#speedy_postcode').val('');
		}

		if (jQuery('#speedy_country_container .wait').length != 0) {
			jQuery('#speedy_country_container .wait').remove();
		}
	});

	jQuery('#speedy_state').autocomplete({
		source: function(request, response) {
			var $this = jQuery(this);
			var $element = jQuery(this.element);
			var jqXHR = $element.data('jqXHR');
			if (jqXHR) {
				jqXHR.abort();
			}
			$element.data('jqXHR', jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				dataType: 'json',
				data: {
					action: 'get_states',
					term: request.term,
					country_id: function() { return jQuery('#speedy_country_id').val(); },
					abroad: '<?php echo $abroad; ?>'
				},
				complete: function() {
					$this.removeData('jqXHR');
				},
				success: function(data) {
					if (data.error) {
						jQuery('#speedy_state').val('');
						jQuery('#speedy_state_id').val('');
						alert(data.error);
					} else {
						response(data);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
				}
			}));
		},
		minLength: 1,
		select: function(event, ui) {
			if (ui.item) {
				speedy_state = ui.item.value;
				jQuery('#speedy_state').val(ui.item.value);
				jQuery('#speedy_state_id').val(ui.item.id);
			}
		},
		change: function(event, ui) {
			if (!ui.item) {
				jQuery('#speedy_state').val('');
				jQuery('#speedy_state_id').val('');
			}
		}
	});

	jQuery('#speedy_state').blur(function() {
		var $this = jQuery(this);
		var jqXHR = jQuery(this).data('jqXHR');
		if (jqXHR) {
			jqXHR.abort();
		}
		$this.removeData('jqXHR');

		if ($this.val() != speedy_state) {
			jQuery(this).val('');
			jQuery('#speedy_state_id').val('');
		}
	});
});
// End Autocomplete functions

function speedy_clear_input(element) {
	if ((jQuery('#abroad').val() == 1) && element.val().match(/[а-яА-я]/)) {
		element.val('');
	}
}

function str_pad(input, pad_length, pad_string, pad_type) {
	  // From: http://phpjs.org/functions
	  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // + namespaced by: Michael White (http://getsprink.com)
	  // +      input by: Marco van Oort
	  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	  // *     example 1: str_pad('Kevin van Zonneveld', 30, '-=', 'STR_PAD_LEFT');
	  // *     returns 1: '-=-=-=-=-=-Kevin van Zonneveld'
	  // *     example 2: str_pad('Kevin van Zonneveld', 30, '-', 'STR_PAD_BOTH');
	  // *     returns 2: '------Kevin van Zonneveld-----'
	  var half = '',
		pad_to_go;

	  var str_pad_repeater = function (s, len) {
		var collect = '',
		  i;

		while (collect.length < len) {
		  collect += s;
		}
		collect = collect.substr(0, len);

		return collect;
	  };

	  input += '';
	  pad_string = pad_string !== undefined ? pad_string : ' ';

	  if (pad_type !== 'STR_PAD_LEFT' && pad_type !== 'STR_PAD_RIGHT' && pad_type !== 'STR_PAD_BOTH') {
		pad_type = 'STR_PAD_RIGHT';
	  }
	  if ((pad_to_go = pad_length - input.length) > 0) {
		if (pad_type === 'STR_PAD_LEFT') {
		  input = str_pad_repeater(pad_string, pad_to_go) + input;
		} else if (pad_type === 'STR_PAD_RIGHT') {
		  input = input + str_pad_repeater(pad_string, pad_to_go);
		} else if (pad_type === 'STR_PAD_BOTH') {
		  half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
		  input = half + input + half;
		  input = input.substr(0, pad_length);
		}
	  }

	  return input;
}
--></script>
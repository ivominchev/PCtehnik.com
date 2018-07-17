<?php
/**
 * Shows a speedy section when there isn`t generated loading
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="speedy_form_admin">
	<table class="speedy_generate">
		<input id="taking_date" type="hidden" name="taking_date" value="<?php echo $taking_date; ?>" />
		<input id="order_id" type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
		<input id="is_bol_recalculated" type="hidden" name="is_bol_recalculated" value="0" />
		<input id="recalculate" type="hidden" name="recalculate" value="0" />
		<tr>
			<td><label for="contents" class="speedy_required"><?php _e( 'Съдържание:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<input type="text" id="contents" name="contents" value="<?php echo $contents; ?>" />
				<br />
				<span style="color: red; display:none;" id="error_contents"><?php _e( 'Съдържанието трябва да е между 1 и 100 символа!', SPEEDY_TEXT_DOMAIN ); ?></span>
			</td>
		</tr>
		<tr>
			<td><label for="weight" class="speedy_required"><?php _e( 'Тегло (кг):', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<input type="text" id="weight" name="weight" value="<?php echo $weight; ?>" />
				<br />
				<span style="color: red; display:none;" id="error_weight"><?php _e( 'Моля, попълнете тегло!', SPEEDY_TEXT_DOMAIN ); ?></span>
			</td>
		</tr>
		<tr>
			<td><label for="packing" class="speedy_required"><?php _e( 'Опаковка:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<input type="text" id="packing" name="packing" value="<?php echo $packing; ?>" />
				<br />
				<span style="color: red; display:none;" id="error_packing"><?php _e( 'Моля, въведете опаковка!', SPEEDY_TEXT_DOMAIN ); ?></span>
			</td>
		</tr>
		<tr>
			<td><label for="packing" class="speedy_required"><?php _e( 'Обект, от който тръгват пратките:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<select id="client_id" name="client_id" style="width: 400px;">
					<?php foreach ( $clients as $client ) : ?>
					<?php if ( $client['clientId'] == $client_id ) : ?>
					<option value="<?php echo $client['clientId']; ?>" selected="selected"><?php echo sprintf(__( 'ID: %s, Име: %s, Адрес: %s', SPEEDY_TEXT_DOMAIN ), $client['clientId'], $client['name'], $client['address']); ?></option>
					<?php else : ?>
					<option value="<?php echo $client['clientId']; ?>"><?php echo sprintf(__( 'ID: %s, Име: %s, Адрес: %s', SPEEDY_TEXT_DOMAIN ), $client['clientId'], $client['name'], $client['address']); ?></option>
					<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="insurance"><?php _e( 'Страна платец:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<select id="speedy_payer_type" name="payer_type">
					<?php if ($payer_type) : ?>
					<option value="1" selected="selected"><?php _e( 'Получател', SPEEDY_TEXT_DOMAIN ); ?></option>
					<option value="0"><?php _e( 'Подател', SPEEDY_TEXT_DOMAIN ); ?></option>
					<?php else : ?>
					<option value="1"><?php _e( 'Получател', SPEEDY_TEXT_DOMAIN ); ?></option>
					<option value="0" selected="selected"><?php _e( 'Подател', SPEEDY_TEXT_DOMAIN ); ?></option>
					<?php endif; ?>
				</select>
			</td>
		</tr>
		<tr style="<?php echo $shipping_method_id == 500 ? 'display:none' : ''; ?>">
			<td><label for="count" class="speedy_required"><?php _e( 'Брой пакети:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<input type="text" id="count" name="count" value="<?php echo $count; ?>" />
				<br />
				<span style="color: red; display:none;" id="error_count"><?php _e( 'Моля, попълнете брой пакети!', SPEEDY_TEXT_DOMAIN ); ?></span>
			</td>
		</tr>
		<tr style="<?php echo $shipping_method_id == 500 ? 'display:none' : ''; ?>">
			<td><label for="width"><?php _e( 'Размери на пакети (см):', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<table class="list" id="parcels_size">
					<tbody>
						<?php $parcels_size_row = 1; ?>
						<?php foreach ($parcels_sizes as $parcels_size) { ?>
						<tr id="parcel-size-row<?php echo $parcels_size_row; ?>">
						  <td class="text-left"><input type="text" name="parcels_size[<?php echo $parcels_size_row; ?>][width]" value="<?php echo $parcels_size['width']; ?>" placeholder="<?php _e( 'Широчина', SPEEDY_TEXT_DOMAIN ); ?>" /></td>
						  <td class="text-left"><input type="text" name="parcels_size[<?php echo $parcels_size_row; ?>][height]" value="<?php echo $parcels_size['height']; ?>" placeholder="<?php _e( 'Височина', SPEEDY_TEXT_DOMAIN ); ?>" /></td>
						  <td class="text-left"><input type="text" name="parcels_size[<?php echo $parcels_size_row; ?>][depth]" value="<?php echo $parcels_size['depth']; ?>" placeholder="<?php _e( 'Дълбочина', SPEEDY_TEXT_DOMAIN ); ?>" /></td>
						  <td class="text-left"><input type="text" name="parcels_size[<?php echo $parcels_size_row; ?>][weight]" value="<?php echo $parcels_size['weight']; ?>" placeholder="<?php _e( 'Тегло', SPEEDY_TEXT_DOMAIN ); ?>" /></td>
						</tr>
						<?php $parcels_size_row++; ?>
						<?php } ?>
					</tbody>
				</table>
			</td>
		</tr>
		<tr style="<?php echo $shipping_method_id != 500 ? 'display:none' : ''; ?>">
			<td><label for="parcel_size"><?php _e( 'Минимален транспортен размер:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<select name="parcel_size" id="speedy_parcel_sizes" class="form-control">
					<?php foreach ($parcel_sizes as $key => $option) { ?>
					<?php if ($key == $parcel_size) { ?>
					<option value="<?php echo $key; ?>" selected="selected"><?php echo $option; ?></option>
					<?php } else { ?>
					<option value="<?php echo $key; ?>"><?php echo $option; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="deffered_days"><?php _e( 'Брой дни за отлагане на доставката:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<select id="deffered_days" name="deffered_days">
					<?php foreach ( $days as $day_id => $day ) : ?>
					<?php if ( $day_id == $deffered_days ) : ?>
					<option value="<?php echo $day_id; ?>" selected="selected"><?php echo $day; ?></option>
					<?php else : ?>
					<option value="<?php echo $day_id; ?>"><?php echo $day; ?></option>
					<?php endif; ?>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="client_note"><?php _e( 'Забележка (клиент):', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="client_note" name="client_note" value="<?php echo $client_note; ?>" size="50" /></td>
		</tr>
		<tr id="speedy_cod_status_container" <?php if ( !$cod_status ) { ?>style="display: none;"<?php } ?>>
			<td><label><?php _e( 'Наложен платеж:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<input type="radio" id="speedy_cod_yes" name="cod" value="1" <?php if ( $cod ) { ?>checked="checked" <?php } ?> onclick="jQuery(this).parent().parent().next().show(); <?php if (!$abroad) { ?>jQuery('#speedy_option_before_payment_container').show();<?php } ?>" />
				<label for="speedy_cod_yes"><?php _e( 'Да', SPEEDY_TEXT_DOMAIN ); ?></label>
				<input type="radio" id="speedy_cod_no" name="cod" value="0" <?php if ( !$cod ) { ?>checked="checked"<?php } ?> onclick="jQuery(this).parent().parent().next().hide(); <?php if (!$abroad) { ?>jQuery('#speedy_option_before_payment_container').hide();<?php } ?>" />
				<label for="speedy_cod_no"><?php _e( 'Не', SPEEDY_TEXT_DOMAIN ); ?></label>
			</td>
		</tr>
		<tr <?php if ( !$cod || !$cod_status ) { ?>style="display: none;"<?php } ?>>
			<td><label for="total"><?php _e( 'Сума на наложения платеж:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="total" name="total" value="<?php echo $total; ?>" /></td>
		</tr>
		<tr id="speedy_option_before_payment_container" <?php if (!$cod || $abroad) { ?> style="display: none;" <?php } ?>>
			<td><label for="speedy_office_id"><?php _e( 'Опции преди плащане:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<select name="option_before_payment" id="speedy_option_before_payment">
				<?php foreach ( $options_before_payment as $option_id => $option ) : ?>
				<?php if ( $option_id == $option_before_payment ) : ?>
				<option value="<?php echo $option_id; ?>" selected="selected"><?php echo $option; ?></option>
				<?php else : ?>
				<option value="<?php echo $option_id; ?>"><?php echo $option; ?></option>
				<?php endif; ?>
				<?php endforeach; ?>
				</select>
				<br />
			</td>
		</tr>
		<tr>
			<td><label for="insurance"><?php _e( 'Добавете oбявена стойност:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td>
				<select id="insurance" name="insurance" onchange="jQuery('#fragile').parent().parent().toggle(); jQuery('#speedy_total_insurance').parent().parent().toggle();">
					<?php if ( $insurance ) : ?>
					<option value="1" selected="selected"><?php _e( 'Да', SPEEDY_TEXT_DOMAIN ); ?></option>
					<option value="0"><?php _e( 'Не', SPEEDY_TEXT_DOMAIN ); ?></option>
					<?php else : ?>
					<option value="1"><?php _e( 'Да', SPEEDY_TEXT_DOMAIN ); ?></option>
					<option value="0" selected="selected"><?php _e( 'Не', SPEEDY_TEXT_DOMAIN ); ?></option>
					<?php endif; ?>
				</select>
			</td>
		</tr>
		<tr <?php if ( !$insurance ) { ?>style="display: none;"<?php } ?>>
			<td><label for="fragile"><?php _e( 'Чупливи стоки:', SPEEDY_TEXT_DOMAIN ); ?>
					<!--<br/><span class="help"><?php // _e( 'Може да бъде избрано, само ако е избрана и oбявена стойност.', SPEEDY_TEXT_DOMAIN ); ?></span>-->
				</label>
			</td>
			<td>
				<select id="fragile" name="fragile">
					<?php if ( $fragile ) : ?>
					<option value="1" selected="selected"><?php _e( 'Да', SPEEDY_TEXT_DOMAIN ); ?></option>
					<option value="0"><?php _e( 'Не', SPEEDY_TEXT_DOMAIN ); ?></option>
					<?php else : ?>
					<option value="1"><?php _e( 'Да', SPEEDY_TEXT_DOMAIN ); ?></option>
					<option value="0" selected="selected"><?php _e( 'Не', SPEEDY_TEXT_DOMAIN ); ?></option>
					<?php endif; ?>
				</select>
			</td>
		</tr>
		<tr <?php if ( !$insurance ) { ?>style="display: none;"<?php } ?>>
			<td><label for="speedy_total_insurance"><?php _e( 'Сума на oбявената стойност:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td><input type="text" id="speedy_total_insurance" name="totalNoShipping" value="<?php echo $totalNoShipping; ?>" /></td>
		</tr>
		<?php if (!$abroad) { ?>
			<tr>
				<td><label><?php _e( 'Доставка:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td>
					<input type="radio" id="speedy_shipping_to_door" name="to_office" value="0" <?php if ( !$to_office ) { ?>checked="checked"<?php } ?> onclick="jQuery('#speedy_quarter_container,#speedy_street_container,#speedy_block_no_container,#speedy_note_container, #speedy_object_container').show(); jQuery('#speedy_office_container').hide();" />
					<label for="speedy_shipping_to_door"><?php _e( 'до врата', SPEEDY_TEXT_DOMAIN ); ?></label>
					<input type="radio" id="speedy_shipping_to_office" name="to_office" value="1" <?php if ( $to_office ) { ?>checked="checked"<?php } ?> onclick="jQuery('#speedy_quarter_container,#speedy_street_container,#speedy_block_no_container,#speedy_note_container, #speedy_object_container').hide(); jQuery('#speedy_office_container').show();" />
					<label for="speedy_shipping_to_office"><?php _e( 'до офис', SPEEDY_TEXT_DOMAIN ); ?></label>
				</td>
			</tr>
			<tr>
				<td><label for="speedy_city"><?php _e( 'Населено място:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td>
					<input type="text" id="speedy_city" name="city" value="<?php echo $city; ?>" size="39" />
					<input type="hidden" id="speedy_city_id" name="city_id" value="<?php echo $city_id; ?>" />
					<input type="hidden" id="speedy_city_nomenclature" name="city_nomenclature" value="<?php echo $city_nomenclature; ?>" />
					<label for="speedy_postcode"><?php _e( 'ПК:', SPEEDY_TEXT_DOMAIN ); ?></label>
					<input type="text" id="speedy_postcode" name="postcode" value="<?php echo $postcode; ?>" disabled="disabled" size="3" />
				</td>
			</tr>
			<tr id="speedy_quarter_container" <?php if ( $to_office ) { ?>style="display: none;"<?php } ?>>
				<td><label for="speedy_quarter"><?php _e( 'Квартал:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td>
					<input type="text" id="speedy_quarter" name="quarter" value="<?php echo $quarter; ?>" size="52" />
					<input type="hidden" id="speedy_quarter_id" name="quarter_id" value="<?php echo $quarter_id; ?>" />
				</td>
			</tr>
			<tr id="speedy_street_container" <?php if ( $to_office ) { ?>style="display: none;"<?php } ?>>
				<td><label for="speedy_street"><?php _e( 'Улица:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td>
					<input type="text" id="speedy_street" name="street" value="<?php echo $street; ?>" size="39" />
					<input type="hidden" id="speedy_street_id" name="street_id" value="<?php echo $street_id; ?>" />
					<label for="speedy_street_no"><?php _e( '№:', SPEEDY_TEXT_DOMAIN ); ?></label>
					<input type="text" id="speedy_street_no" name="street_no" value="<?php echo $street_no; ?>" size="3" />
				</td>
			</tr>
			<tr id="speedy_block_no_container" <?php if ( $to_office ) { ?>style="display: none;"<?php } ?>>
				<td><label for="speedy_block_no"><?php _e( 'Бл.:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td>
					<input type="text" id="speedy_block_no" name="block_no" value="<?php echo $block_no; ?>" size="12" />
					<label for="speedy_entrance_no"><?php _e( 'Вх.:', SPEEDY_TEXT_DOMAIN ); ?></label>
					<input type="text" id="speedy_entrance_no" name="entrance_no" value="<?php echo $entrance_no; ?>" size="3" />
					<label for="speedy_floor_no"><?php _e( 'Ет.:', SPEEDY_TEXT_DOMAIN ); ?></label>
					<input type="text" id="speedy_floor_no" name="floor_no" value="<?php echo $floor_no; ?>" size="3" />
					<label for="speedy_apartment_no"><?php _e( 'Ап.:', SPEEDY_TEXT_DOMAIN ); ?></label>
					<input type="text" id="speedy_apartment_no" name="apartment_no" value="<?php echo $apartment_no; ?>" size="3" />
					<br />
					<span style="color: red; display:none;" id="error_address"><?php _e( 'Моля, въведете валиден адрес!', SPEEDY_TEXT_DOMAIN ); ?></span>
				</td>
			</tr>
			<tr id="speedy_note_container" <?php if ( $to_office ) { ?>style="display: none;"<?php } ?>>
				<td><label for="speedy_note"><?php _e( 'Забележка към адреса:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td><input type="text" id="speedy_note" name="note" value="<?php echo $note; ?>" size="52" /></td>
			</tr>
			<tr id="speedy_office_container" <?php if ( ! $to_office ) { ?>style="display: none;"<?php } ?>>
				<td><label for="speedy_office_id"><?php _e( 'Офис:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td>
					<select id="speedy_office_id" name="office_id" style="width: 400px;">
						<?php if ( ! $offices ) : ?>
						<option value="0" selected="selected"><?php _e( ' --- Моля, въведете населено място --- ', SPEEDY_TEXT_DOMAIN ); ?></option>
						<?php endif; ?>
						<?php foreach ( $offices as $office ) : ?>
						<?php if ( $office['id'] == $office_id ) : ?>
						<option value="<?php echo $office['id']; ?>" selected="selected"><?php echo $office['label']; ?></option>
						<?php else : ?>
						<option value="<?php echo $office['id']; ?>"><?php echo $office['label']; ?></option>
						<?php endif; ?>
						<?php endforeach; ?>
					</select>
					<br />
					<span style="color: red; display:none;" id="error_office"><?php _e( 'Моля, въведете населено място и изберете офис!', SPEEDY_TEXT_DOMAIN ); ?></span>
				</td>
			</tr>
		<?php } else { ?>
			<tr>
				<td><label for="speedy_country" class="speedy_required"><?php _e( 'Държава:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td>
					<input type="text" id="speedy_country" name="country" value="<?php echo $country; ?>" size="39" />
					<input type="hidden" id="speedy_country_id" name="country_id" value="<?php echo $country_id; ?>" />
					<input type="hidden" id="speedy_country_nomenclature" name="country_nomenclature" value="<?php echo $country_nomenclature; ?>" />
					<input type="hidden" id="speedy_active_currency_code" name="active_currency_code" value="<?php echo $active_currency_code; ?>" />
		<label for="speedy_state" id="speedy_state_label" <?php if ($required_state) { ?>class="speedy_required"<?php } ?>><?php _e( 'Щат:', SPEEDY_TEXT_DOMAIN ); ?></label>
					<input type="text" id="speedy_state" name="state" value="<?php echo $state; ?>" size="3" />
					<input type="hidden" id="speedy_state_id" name="state_id" value="<?php echo $state_id; ?>" />
					<input type="hidden" id="speedy_required_state" name="required_state" value="<?php echo $required_state; ?>" />
				</td>
			</tr>
			<tr>
				<td><label for="speedy_city" class="speedy_required"><?php _e( 'Населено място:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td>
					<input type="text" id="speedy_city" name="city" value="<?php echo $city; ?>" size="39" />
					<input type="hidden" id="speedy_city_id" name="city_id" value="<?php echo $city_id; ?>" />
					<input type="hidden" id="speedy_city_nomenclature" name="city_nomenclature" value="<?php echo $city_nomenclature; ?>" />
					<label for="speedy_postcode" id="speedy_postcode_label" <?php if ($required_postcode) { ?>class="speedy_required"<?php } ?>><?php _e( 'ПК:', SPEEDY_TEXT_DOMAIN ); ?></label>
					<input type="text" id="speedy_postcode" name="postcode" value="<?php echo $postcode; ?>" size="3" />
					<input type="hidden" id="speedy_required_postcode" name="required_postcode" value="<?php echo $required_postcode; ?>" />
				</td>
			</tr>
			<tr>
				<td><label for="speedy_address_1" class="speedy_required"><?php _e( 'Адрес 1:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td><input type="text" id="speedy_address_1" name="address_1" value="<?php echo $address_1; ?>" size="52" /></td>
			</tr>
			<tr>
				<td><label for="speedy_address_1"><?php _e( 'Адрес 2:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
				<td><input type="text" id="speedy_address_2" name="address_2" value="<?php echo $address_2; ?>" size="52" />
					<br/>
					<span style="color: red; display:none;" id="error_address"></span>
				</td>
			</tr>
		<?php } ?>
		<input type="hidden" id="abroad" name="abroad" value="<?php echo $abroad; ?>" />
		<tr style="display: none;">
			<td><label><?php _e( 'Метод за доставката:', SPEEDY_TEXT_DOMAIN ); ?></label></td>
			<td id="speedy_methods">

			</td>
		</tr>
		<tr>
			<td></td>
			<td><a onclick="jQuery('#speedy_form_admin :input').removeAttr('disabled'); speedyCalculate();" class="button"><?php _e( 'Изчисли цена', SPEEDY_TEXT_DOMAIN ); ?></a></td>
		</tr>
		<tr>
			<td></td>
			<td><a id="button_generate_loading" onclick="speedySubmit(); return false;" class="button button-primary" ><?php _e( 'Генериране', SPEEDY_TEXT_DOMAIN ); ?></a></td>
		</tr>
	</table>
</div>

<script type="text/javascript"><!--
var error_cyrillic = '<?php _e( 'Моля, използвайте само латински символи!', SPEEDY_TEXT_DOMAIN ); ?>';

function printLoading(bol_id) {
	if (bol_id) {
		jQuery('#bol_id').val(bol_id);
		jQuery('#do_action').val('print_pdf');
		jQuery('#loading_form').submit();
	}
}


function showTableForm(method_id) {
	if (method_id) {
		jQuery('#loading_speedy').hide();
		jQuery('table.speedy_table').hide();
		jQuery('.speedy_' + method_id).show();

	}
}

function speedyCalculate() {
	jQuery('#recalculate').val('0');
	jQuery('#loading_speedy').show();

	jQuery('#error_message').remove();
	jQuery('.speedy_error').hide();
	jQuery('#error_address').hide();
	jQuery('#error_office').hide();
	jQuery('#error_packing').hide();
	jQuery('#error_count').hide();
	jQuery('#error_contents').hide();
	jQuery('#error_weight').hide();
	jQuery('#error_fixed_time').hide();
	jQuery('#speedy_methods').parent().hide();

	jQuery('#speedy_form_admin :input').removeAttr('disabled');

	jQuery('.fixed_time').attr('disabled', true);

	jQuery.ajax({
		url: '<?php echo admin_url('admin-ajax.php'); ?>',
		type: 'POST',
		data: {
			action: 'speedy_calculate_price',
			order_id: encodeURIComponent('<?php echo $order_id; ?>'),
			data: jQuery('#speedy_form_admin input, #speedy_form_admin textarea, #speedy_form_admin select, input#email, input#firstname, input#lastname, input#phone, input#phone_mobile').serialize()
		},
		dataType: 'json',
		beforeSend: function() {
			jQuery( '#woocommerce-speedy-data' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
		},
		complete: function() {
			jQuery( '#woocommerce-speedy-data' ).unblock();
		},
		success: function(data) {

			if (data) {
				<?php if (!$abroad) { ?>
					jQuery('#speedy_postcode').attr('disabled', 'disabled');
				<?php } ?>

				if (data.error) {
					checkErrors(data.error);
				} else if (data.methods) {
					html = '';

					if (data.methods.length) {
						for (i = 0; i < data.methods.length; i++) {
							html += '<div>';
							html += '<input type="radio" name="shipping_method_id" id="'+data.methods[i]['code']+'" value="'+data.methods[i]['code']+'" ';
							if (data.shipping_method_id == data.methods[i]['code']) {
								html += 'checked="checked"';
							}
							html += ' /> <label for="'+data.methods[i]['code']+'">'+data.methods[i]['title']+'</label> | ';

							if (data.methods[i]['default_fixed_time']) {
								html += '<div style="display: inline;">';
								html += '<input style="display: none;" class="fixed_time" type="checkbox" ';
								if (!data.methods[i]['fixed_time_cb']) {
									html += 'disabled="disabled"';
								} else {
									html += 'checked="checked"';
								}
								html += ' id="speedy_fixed_time_cb_'+data.methods[i]['code']+'" name="fixed_time_cb['+data.methods[i]['code']+']" value="1" onclick="speedyCheckFixedTime('+data.methods[i]['code']+');" />';
								html += "<label style=\"display: none;\" id='speedy_fixed_time_cb_label_" + data.methods[i]['code'] + "' class=\"fixed_time\" for=\"speedy_fixed_time_cb_"+data.methods[i]['code']+"\"><?php _e( 'Фиксиран час:', SPEEDY_TEXT_DOMAIN ); ?></label>";
								html += '<select style="display: none;" class="fixed_time" id="speedy_fixed_time_hour_'+data.methods[i]['code']+'" name="fixed_time_hour['+data.methods[i]['code']+']" ';
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

								html += '<select style="display: none;" class="fixed_time" id="speedy_fixed_time_min_'+data.methods[i]['code']+'" name="fixed_time_min['+data.methods[i]['code']+']" ';
								if (!data.methods[i]['fixed_time_cb']) {
									html += 'disabled="disabled"';
								}
								html += '>';
								for (n=min_fixed_time_mins; n <= max_fixed_time_mins; n++) {
									hour_str_pad = str_pad(n, 2, '0', 'STR_PAD_LEFT');
									if (hour_str_pad == data.methods[i]['fixed_time_min']) {
										html += '<option value="'+hour_str_pad+'" selected="selected">'+hour_str_pad+'</option>';
									} else {
										html += '<option value="'+hour_str_pad+'">'+hour_str_pad+'</option>';
									}
								}
								html += '</select> | ';
								html += '</div>';
							}

							html += data.methods[i]['text']+'<br />';

							if (data.methods[i]['default_fixed_time']) {
								if (data.methods[i]['error_fixed_time']) {
									html += "<span style=\"color: red;\" class=\"error_fixed_time\"><?php _e( 'Моля, изберете валиден час!', SPEEDY_TEXT_DOMAIN ); ?></span>";
								}
							}
							if (data.methods[i]['total_form'].length) {
								html += '  <table style="display: none;" class="speedy_'+data.methods[i]['code']+' speedy_table speedy_table_admin">';
								for (j = 0; j< data.methods[i]['total_form'].length; j++) {
									html += '    <tr>';
									html += '      <td>'+data.methods[i]['total_form'][j]['label']+'</td>';
									html += '      <td>'+data.methods[i]['total_form'][j]['value']+'</td>';
									html += '    </tr>';
								}
								html += '  </table>';
							}
							html += '</div>';
						}
					} else {
						html += '<?php _e( 'Няма намерени услуги!', SPEEDY_TEXT_DOMAIN ); ?>';
					}

					jQuery('#speedy_methods').html(html);

					if (jQuery('input[name=\'shipping_method_id\']:checked').length == 0) {
						jQuery('input[name=\'shipping_method_id\']:first').attr('checked', true);
					}

					showGenerateButton();

					if (jQuery("#calculate_price").length != 0) {
						jQuery('#calculate_price').remove();
					}

					jQuery('input[name=\'shipping_method_id\']').change(function () {
						if (jQuery(this).is(':checked')) {
							showTableForm(jQuery(this).attr('id'));
							setSpeedyMethod(jQuery(this).val());
						}
					});

					if (jQuery('input[name=\'shipping_method_id\']:checked').length) {
						setSpeedyMethod(jQuery('input[name=\'shipping_method_id\']:checked').val());
					}

					// Change payer type
					if ( false !== data.payer_type ) {
						jQuery('#speedy_payer_type').val(data.payer_type);
					}

					jQuery('#is_bol_recalculated').val(1);
					jQuery('#recalculate').val(0);
					jQuery('#button_generate_loading').removeAttr('disabled');
				} else {
					if (data.redirect) {
						window.location.href = data.redirect;
					} else {
						jQuery('#speedy').html(data.html);
					}
				}
			}
		}
	});
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

jQuery('#speedy_parcel_sizes, #client_id').on('change', function() {
	if (jQuery('#recalculate').val() == 0) {
		alert("<?php _e( 'Моля, преди да генерирате товарителница преизчислете цената за доставка!', SPEEDY_TEXT_DOMAIN ); ?>");

		jQuery('#button_generate_loading').attr('disabled', 'disabled');
		jQuery('#recalculate').val('1');
	}
});
function speedySubmit() {
	if (jQuery('#recalculate').val() == 1) {
		return;
	}

	if (jQuery('input[name=\'shipping_method_id\']:checked').parent().find('.error_fixed_time').length != 0) {
		alert("<?php _e( 'Моля, изберете валиден час и натиснете изчисли!', SPEEDY_TEXT_DOMAIN ); ?>");
	} else {
		if (jQuery('input[name=\'shipping_method_id\']:checked').length) {
			shipping_method_id = jQuery('input[name=\'shipping_method_id\']:checked').val();
		} else {
			shipping_method_id = '<?php echo $shipping_method_id; ?>';
		}

		var post_data = {
			'action'              : 'speedy_validate_bill_of_lading',
			'shipping_method_id'  : encodeURIComponent(shipping_method_id),
			'abroad'              : jQuery('#abroad').val()
		};

		if(!parseInt(jQuery('#abroad').val())) {
			post_data.speedy_shipping_to_office = jQuery('input[name=to_office]:checked').val();
			post_data.speedy_option_before_payment = jQuery('#speedy_option_before_payment').val();
			post_data.speedy_city_id = jQuery('#speedy_city_id').val();
			post_data.speedy_office_id = jQuery('#speedy_office_id').val();
		}

		jQuery.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			type: 'POST',
			data: post_data,
			dataType: 'json',
			beforeSend: function() {
				jQuery( '#woocommerce-speedy-data' ).block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
			},
			complete: function() {
				jQuery( '#woocommerce-speedy-data' ).unblock();
			},
			success: function(data) {
				if (data.error) {
					var confurm = 1;

					if (data.taking_date) {
						jQuery('#speedy_taking_date').val(data.taking_date);
					}

					for(error in data.errors) {
						if (!confirm(data.errors[error])) {
							confurm = 0;
						}
					}

					if(confurm) {
						jQuery('#taking_date').val(data.taking_date);
						jQuery('#speedy_form_admin :input').removeAttr('disabled');

						generateLoading();
					}
				} else {
					jQuery('#speedy_form_admin :input').removeAttr('disabled');

					generateLoading();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				// alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

	}
}

function generateLoading() {
	jQuery.ajax({
		url: '<?php echo admin_url('admin-ajax.php'); ?>',
		type: 'POST',
		data: {
			action: 'speedy_generate_loading',
			data: jQuery('#speedy_form_admin .speedy_generate :input').serialize()
		},
		dataType: 'json',
		beforeSend: function() {
			jQuery( '#woocommerce-speedy-data' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
		},
		complete: function() {
			jQuery( '#woocommerce-speedy-data' ).unblock();
		},
		success: function(data) {
			if (data.status == true) {
				window.location.replace('<?php echo admin_url(); ?>admin.php?page=speedy-orders');
			} else {
				checkErrors(data.error);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
		}
	});
}

function checkErrors( error ) {
	if (error.error_warning || error.error_currency) {
		if (error.error_warning) {
			var error_msg = error.error_warning;
		} else if (error.error_currency) {
			var error_msg = error.error_currency;
		}

		if (jQuery('#error_message').length != 0) {
			jQuery('#error_message').remove();
		}

		html = '<div id="error_message" class="error"><p>'+error_msg+'</p></div>';
		jQuery( '#woocommerce-speedy-data' ).before(html);

		add_offset = 0;
		if (jQuery('#wpadminbar').length != 0) {
			add_offset = jQuery('#wpadminbar').height();
		}
		jQuery('html, body').animate({
			scrollTop: jQuery('#error_message').offset().top - add_offset
		}, 500);
	}

	if (error.error_address) {
		<?php if (!$abroad) { ?>
			jQuery('#error_address').show();
		<?php } else { ?>
			jQuery('#error_address').html(error.error_address).show();
		<?php } ?>
	}

	if (error.error_office) {
		jQuery('#error_office').show();
	}

	if (error.error_contents) {
		jQuery('#error_contents').show();
	}

	if (error.error_weight) {
		jQuery('#error_weight').show();
	}

	if (error.error_count) {
		jQuery('#error_count').show();
	}

	if (error.error_packing) {
		jQuery('#error_packing').show();
	}
}

function speedyCheckFixedTime(method_id) {
	if (jQuery('#speedy_fixed_time_cb_'+method_id+':checked').length) {
		jQuery('#speedy_fixed_time_hour_'+method_id).removeAttr('disabled');
		jQuery('#speedy_fixed_time_min_'+method_id).removeAttr('disabled');
	} else {
		jQuery('#speedy_fixed_time_hour_'+method_id).attr('disabled', 'disabled');
		jQuery('#speedy_fixed_time_min_'+method_id).attr('disabled', 'disabled');
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

function setSpeedyMethod(method_id) {
	if (method_id) {
		jQuery('input[name^=\'fixed_time_cb\']').attr('disabled', true);
		jQuery('input[name^=\'fixed_time_cb\']').addClass('fixed_time');
		jQuery('select[name^=\'fixed_time_hour\']').addClass('fixed_time');
		jQuery('select[name^=\'fixed_time_min\']').addClass('fixed_time');
		jQuery('input[name^=\'fixed_time_cb\']').parent().hide();
		jQuery('.error_fixed_time').hide();
		jQuery('.fixed_time').hide();

		jQuery('#speedy_fixed_time_cb_'+method_id).parent().show();
		jQuery('#speedy_fixed_time_cb_'+method_id).parent().parent().find('.error_fixed_time').show();
		jQuery('#speedy_fixed_time_cb_'+method_id).show();
		jQuery('#speedy_fixed_time_cb_'+method_id).removeAttr('disabled');
		jQuery('#speedy_fixed_time_cb_'+method_id).removeClass('fixed_time');
		jQuery('#speedy_fixed_time_cb_label_'+method_id).show();
		jQuery('#speedy_fixed_time_hour_'+method_id).show();
		jQuery('#speedy_fixed_time_hour_'+method_id).removeClass('fixed_time');
		jQuery('#speedy_fixed_time_min_'+method_id).show();
		jQuery('#speedy_fixed_time_min_'+method_id).removeClass('fixed_time');

		jQuery('.speedy_' + method_id).show();
	}
}

function hideGenerateButton(fixed_time) {
	if ( ! fixed_time ) {
		jQuery('#speedy_methods').parent().hide();
	}
	jQuery('#button_generate_loading').hide();
	if (jQuery("#calculate_price").length == 0) {
		jQuery('#button_generate_loading').after('<span id="calculate_price"><b>'+error_generate_message+'</b></span>');
	}
}

function showGenerateButton() {
	jQuery('#speedy_methods').parent().show();
	jQuery('#button_generate_loading').show();
}

var speedy_count_previous;

jQuery('#count').keydown(function (e) {
	if (!e.key.match(/\d/)) {
		e.preventDefault()
	}
}).on('focus', function () {
	speedy_count_previous = parseInt(jQuery(this).val());
}).change(function() {
	// Do something with the previous value after the change

	if (parseInt(speedy_count_previous) < parseInt(jQuery(this).val())) {
		addParcelsSize(parseInt(speedy_count_previous) + 1, parseInt(jQuery(this).val()));
	} else {
		removeParcelsSize(parseInt(jQuery(this).val()), parseInt(speedy_count_previous));
	}

	// Make sure the previous value is updated
	speedy_count_previous = parseInt(jQuery(this).val());
});

function addParcelsSize(old_rows, new_rows) {
	for (i = old_rows; i <= new_rows; i++) {
		html  = '<tr id="parcel-size-row' + i + '">'; 
		html += '  <td class="left"><input type="text" name="parcels_size[' + i + '][width]" value="" placeholder="<?php _e( 'Широчина', SPEEDY_TEXT_DOMAIN ); ?>" /></td>';
		html += '  <td class="left"><input type="text" name="parcels_size[' + i + '][height]" value="" placeholder="<?php _e( 'Височина', SPEEDY_TEXT_DOMAIN ); ?>" /></td>';
		html += '  <td class="left"><input type="text" name="parcels_size[' + i + '][depth]" value="" placeholder="<?php _e( 'Дълбочина', SPEEDY_TEXT_DOMAIN ); ?>"  /></td>';
		html += '  <td class="left"><input type="text" name="parcels_size[' + i + '][weight]" value="" placeholder="<?php _e( 'Тегло', SPEEDY_TEXT_DOMAIN ); ?>"  /></td>';
		html += '</tr>';

		jQuery('#parcels_size tbody').append(html);
	}
}

function removeParcelsSize(old_rows, new_rows) {
	for (i = new_rows; i > old_rows; i--) {
		jQuery('#parcel-size-row' + i).remove();
	}
}

var speedy_city = '<?php echo $city; ?>';
var speedy_quarter = '<?php echo $quarter; ?>';
var speedy_street = '<?php echo $street; ?>';
var speedy_country = '<?php echo $country; ?>';
var speedy_state = '<?php echo $state; ?>';
var abroad = '<?php echo $abroad; ?>';
var error_generate_message = '<?php _e( 'Моля, изчислете цена, за да генерирате товарителница!', SPEEDY_TEXT_DOMAIN ); ?>';

jQuery(document).ready(function() {

// Hide generate loading button
	jQuery('input[name=\'cod\'], input[name=\'to_office\'], input[name=\'postcode\'], input[name=\'country\'], input[name=\'state\'], input[name=\'city\'], input[name=\'quarter\'], input[name=\'street\'], input[name=\'street_no\'], input[name=\'object\'], input[name=\'block_no\'], input[name=\'entrance_no\'], input[name=\'floor_no\'], input[name=\'apartment_no\'], input[name=\'note\'], select[name=\'office_id\']').live('change', function() {
		hideGenerateButton(false);

	});

	jQuery('input[name^=\'fixed_time_cb\'], select[name^=\'fixed_time_hour\'], select[name^=\'fixed_time_min\']').live('change', function() {
		hideGenerateButton(true);
	});

	jQuery( "#speedy_city" ).autocomplete({
		source: function(request, response) {
			var $this = jQuery(this);
			var $element = jQuery(this.element);
			var jqXHR = $element.data('jqXHR');
			if (jqXHR) {
				jqXHR.abort();
			}
			$element.data('jqXHR', jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>' + "?action=get_cities",
				dataType: 'json',
				data: {
					term: request.term,
					country_id: jQuery('#speedy_country_id').val(),
					abroad: abroad
				},
				complete: function() {
					$this.removeData('jqXHR');
					jQuery( "#speedy_city" ).removeClass('ui-autocomplete-loading');
				},
				success: function(data) {
					if (data.error) {
						alert(data.error);
					} else {
						if (jQuery('#speedy_country_nomenclature').val() == 'FULL') {
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
					url: '<?php echo admin_url('admin-ajax.php'); ?>' + "?action=get_offices",
					dataType: 'json',
					data: {
						city_id: ui.item.id,
						abroad: abroad
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
			if (!abroad || (abroad && (jQuery('#speedy_country_nomenclature').val() == 'FULL'))) {
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
				url: '<?php echo admin_url('admin-ajax.php'); ?>' + "?action=get_quarters",
				dataType: 'json',
				data: {
					term: request.term,
					city_id: function() { return jQuery('#speedy_city_id').val(); },
					abroad: abroad
				},
				complete: function() {
					$this.removeData('jqXHR');
					jQuery('#speedy_quarter').removeClass('ui-autocomplete-loading');
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
				url: '<?php echo admin_url('admin-ajax.php'); ?>' + "?action=get_streets",
				dataType: 'json',
				data: {
					term: request.term,
					city_id: function() { return jQuery('#speedy_city_id').val(); },
					abroad: abroad
				},
				complete: function() {
					$this.removeData('jqXHR');
					jQuery('#speedy_street').removeClass('ui-autocomplete-loading');
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
				url: '<?php echo admin_url('admin-ajax.php'); ?>' + "?action=get_blocks",
				dataType: 'json',
				data: {
					term: request.term,
					city_id: function() { return jQuery('#speedy_city_id').val(); },
					abroad: abroad
				},
				complete: function() {
					$this.removeData('jqXHR');
					jQuery('#speedy_block_no').removeClass('ui-autocomplete-loading');
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
				url: '<?php echo admin_url('admin-ajax.php'); ?>' + "?action=get_countries",
				dataType: 'json',
				data: {
					term: request.term,
					abroad: abroad
				},
				complete: function() {
					$this.removeData('jqXHR');
					jQuery('#speedy_country').removeClass('ui-autocomplete-loading');
				},
				success: function(data) {
					if (data.error) {
						jQuery('#speedy_country').val('');
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
				jQuery('#speedy_state_label, #speedy_postcode_label').removeClass('speedy_required');

				speedy_country = ui.item.value;
				jQuery('#speedy_country').val(ui.item.value);
				jQuery('#speedy_country_id').val(ui.item.id);
				jQuery('#speedy_country_nomenclature').val(ui.item.nomenclature);
				jQuery('#required_state').val(ui.item.required_state);
				jQuery('#required_postcode').val(ui.item.required_postcode);
				jQuery('#speedy_active_currency_code').val(ui.item.active_currency_code);

				if (!ui.item.active_currency_code) {
					jQuery('#speedy_cod_status_container').hide();
					jQuery('#speedy_cod_no').click();
				} else {
					jQuery('#speedy_cod_status_container').show();
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
	});

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
				url: '<?php echo admin_url('admin-ajax.php'); ?>' + "?action=get_states",
				dataType: 'json',
				data: {
					term: request.term,
					country_id: jQuery('#speedy_country_id').val(),
					abroad: abroad
				},
				complete: function() {
					$this.removeData('jqXHR');
					jQuery('#speedy_state').removeClass('ui-autocomplete-loading');
				},
				success: function(data) {
					if (data.error) {
						jQuery('#speedy_state').val('');
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

		if (jQuery('#speedy_country_container .wait').length != 0) {
			jQuery('#speedy_country_container .wait').remove();
		}
	});
});

jQuery(document).keypress(function(e) {
    if(e.which == 13) {
		return false;
    }
});

 // Click Save Order items
jQuery(document).ready(function() {
	jQuery('#woocommerce-order-items').on('click', 'button.calculate-action', function() {
		jQuery.ajax({
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			type: 'POST',
			data: {
				action: 'speedy_after_save_order_items',
				order_id: encodeURIComponent('<?php echo $order_id; ?>')
			},
			dataType: 'json',
			success: function(data) {
				if ( data.status == true ) {
					jQuery('#weight').val(data.weight);
					jQuery('#total').val(data.total);
					jQuery('#speedy_total_insurance').val(data.totalNoShipping);
					hideGenerateButton(false);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				// alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	jQuery('#speedy_form_admin input').keypress(function(event){
		if ((abroad == 1) && event.key.match(/[а-яА-я]/)) {
			event.preventDefault();
			alert(error_cyrillic);
		}
	});
});
jQuery(document).on('change','input[name=shipping_method_id]', function() {
	console.log(1);
	if(jQuery(this).val() == 500) { // id for speedy POST method
		jQuery('#count').parent().parent().hide();
		jQuery('#parcels_size').parent().parent().hide();
		jQuery('#speedy_parcel_sizes').parent().parent().show();
	} else {
		jQuery('#count').parent().parent().show();
		jQuery('#parcels_size').parent().parent().show();
		jQuery('#speedy_parcel_sizes').parent().parent().hide();
	}
});
//--></script>
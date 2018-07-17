
<p>
<a href="javascript:void(0);"  class="econt_office_locator button" id="office_locator" style="display:none;" title="<?php _e('office locator','woocommerce-econt') ?>"><?php _e('office locator','woocommerce-econt') ?></a>
</p>
<script type="text/javascript">
	function receiveMessage(event) {
		if (event.origin !== '<?php echo $office_locator_domain; ?>')
			return;

		message_array = event.data.split('||');
		getOfficeByOfficeCode(message_array[0]);
		jQuery.colorbox.close();
	}

	if (window.addEventListener) {
		window.addEventListener('message', receiveMessage, false);
	} else if (window.attachEvent) {
		window.attachEvent('onmessage', receiveMessage);
	}

	jQuery(document).ready(function() {
		
		if (jQuery('#econt_offices_town').val()) {
			url = '<?php echo $office_locator; ?>&address=' + jQuery('#econt_offices_town').val();
		} else {
			url = '<?php echo $office_locator; ?>';
		}

		jQuery('a#office_locator').colorbox({
			overlayClose: true,
			href : url,
			iframe : true,
			opacity: 0.5,
			width  : '1000',
			height : '700'
		});
		
		//jQuery('#econt_offices_town').change(function () {
		jQuery('#econt_offices_town').on('input',function(e){
			if (jQuery('#econt_offices_town').val()) {
				url = '<?php echo $office_locator; ?>&address=' + jQuery('#econt_offices_town').val();
			} else {
				url = '<?php echo $office_locator; ?>';
			}

			jQuery('a#office_locator').colorbox({
				overlayClose: true,
				href : url,
				iframe : true,
				opacity: 0.5,
				width  : '1000',
				height : '700'
			});
		});
		
		jQuery('#econt_offices_town').change(function () {
		//jQuery('#econt_offices_town').on('input',function(e){
			if (jQuery('#econt_offices_town').val()) {
				url = '<?php echo $office_locator; ?>&address=' + jQuery('#econt_offices_town').val();
			} else {
				url = '<?php echo $office_locator; ?>';
			}

			jQuery('a#office_locator').colorbox({
				overlayClose: true,
				href : url,
				iframe : true,
				opacity: 0.5,
				width  : '1000',
				height : '700'
			});
		});


	});
            
	function getOfficeByOfficeCode(office_code) {
		//alert(office_code);
		jQuery("#econt_offices").val(office_code);
		if (parseInt(office_code)) {
			jQuery.ajax({
				url: 'index.php?route=shipping/econt/getOfficeByOfficeCode',
				type: 'POST',
				data: 'office_code=' + parseInt(office_code),
				dataType: 'json',
				success: function(data) {
					if (!data.error) {
						jQuery('#office_city_id').val(data.city_id);
						html = '<option value="0"><?php _e('please select', 'woocommerce-econt') ?></option>';

						for (i = 0; i < data.offices.length; i++) {
							html += '<option ';
							if (data.offices[i]['office_id'] == data.office_id) {
								html += 'selected="selected"';
							}
							html += 'value="' + data.offices[i]['office_id'] + '">' + data.offices[i]['office_code'] + ', ' + data.offices[i]['name'] + ', ' + data.offices[i]['address'] +  '</option>';
						}

						jQuery('#office_id').html(html);
						jQuery('#office_code').val(office_code);
					}
				}
			});
		}
	}

jQuery(document).ready(function() {
	
function calculate_loading(){

var econt_shipping_to = jQuery("#econt_shipping_to").val();

if(jQuery('#payment_method_cod').is(':checked')){ 
var payment_method_cod = 1; 
}else{ 
var payment_method_cod = 0; 
}


var pack_count = 1;

var receiver_name = jQuery("#billing_company").val();
var receiver_name_person = jQuery("#billing_first_name").val()+' '+jQuery("#billing_last_name").val();
var receiver_phone_num = jQuery("#billing_phone").val();
var receiver_email = jQuery("#billing_email").val();

if ( econt_shipping_to == 'DOOR' ){

var receiver_city =  jQuery("#econt_door_town").val();
var receiver_post_code = jQuery("#econt_door_postcode").val();
var receiver_street = jQuery("#econt_door_street").val();
var receiver_quarter = jQuery("#econt_door_quarter").val();
var receiver_street_num = jQuery("#econt_door_street_num").val();
var receiver_street_bl = jQuery("#econt_door_street_bl").val();
var receiver_street_vh = jQuery("#econt_door_street_vh").val();
var receiver_street_et = jQuery("#econt_door_street_et").val();
var receiver_street_ap = jQuery("#econt_door_street_ap").val();
var receiver_street_other = jQuery("#econt_door_other").val();
var econt_city_courier = jQuery("#econt_city_courier").val();
var delivery_day_id = jQuery("#econt_delivery_days").val();

var receiver_office_code = '';

}else if (econt_shipping_to == 'OFFICE'){

var receiver_city =  jQuery("#econt_offices_town").val();
var receiver_post_code = jQuery("#econt_offices_postcode").val();
var receiver_office_code = jQuery("#econt_offices").val();

var receiver_street = '';
var receiver_quarter = '';
var receiver_street_num = '';
var receiver_street_bl = '';
var receiver_street_vh = '';
var receiver_street_et = '';
var receiver_street_ap = '';
var receiver_street_other = '';
var econt_city_courier = '';
var delivery_day_id = '';

}else if (econt_shipping_to == 'MACHINE'){

var receiver_city =  jQuery("#econt_machines_town").val();
var receiver_post_code = jQuery("#econt_machines_postcode").val();
var receiver_office_code = jQuery("#econt_machines").val();

var receiver_street = '';
var receiver_quarter = '';
var receiver_street_num = '';
var receiver_street_bl = '';
var receiver_street_vh = '';
var receiver_street_et = '';
var receiver_street_ap = '';
var receiver_street_other = '';
var econt_city_courier = '';
var delivery_day_id = '';

}

jQuery.ajax({

        url: ajaxurl,
        dataType: "json",
        
        data: {
        action: 'handle_ajax',
        action2: 'only_calculate_loading',
        receiver_name: receiver_name,
        receiver_name_person: receiver_name_person,
        receiver_phone_num: receiver_phone_num,
        receiver_email: receiver_email,
        receiver_shipping_to: econt_shipping_to,
        receiver_city: receiver_city,
        receiver_post_code: receiver_post_code,
        receiver_office_code: receiver_office_code,
        receiver_street: receiver_street,
        receiver_quarter: receiver_quarter,
        receiver_street_num: receiver_street_num,
        receiver_street_bl: receiver_street_bl,
        receiver_street_vh: receiver_street_vh,
        receiver_street_et: receiver_street_et,
        receiver_street_ap: receiver_street_ap,
        receiver_street_other: receiver_street_other,
        econt_city_courier: econt_city_courier,
        delivery_day_id: delivery_day_id,
        pack_count: pack_count,
        payment_method_cod: payment_method_cod,

        },

        success: function(data){

            jQuery.each(data, function(key, val) {
            if(key == 'econt_shipping_expenses'){
            jQuery("#button_calculate_loading").prop('value', 'Цена на доставка: '+val['customer_shipping_cost']);
            jQuery("#econt_customer_shipping_cost").attr('value', val['customer_shipping_cost'] );
            jQuery("#econt_total_shipping_cost").attr('value', val['total_shipping_cost'] );
            jQuery('#'+key).remove();
            jQuery('.woocommerce-checkout-review-order-table tr:last').after('<tr id="'+key+'"><td>Цена на доставка с Еконт Експрес:</td><td><strong>'+val['customer_shipping_cost']+val['currency_symbol']+'</strong></td><tr>').appendTo('.woocommerce-checkout-review-order-table');
            }else if (key == 'warning'){
                alert( val );
             }

            });

        },


 });

}
	//avtomatizirano kalkulirane na cenata za shipping i nalojen platej
	jQuery("#econt_city_courier, #econt_delivery_days, #econt_door_other, #button_calculate_loading, #econt_door_street, #econt_door_quarter").on('click' , function(e){

		calculate_loading();

	});

	jQuery("#econt_city_courier, #econt_delivery_days").on('change' , function(e){

		calculate_loading();

	});


 });


</script>
            
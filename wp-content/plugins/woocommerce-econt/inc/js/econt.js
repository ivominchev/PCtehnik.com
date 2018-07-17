
//var sender_city = 'Sofia';

//jQuery(function() {
jQuery(document).ready(function(){
//econt checkout and order office autocomplete

jQuery( "#econt_offices_town" ).autocomplete({
minLength: 2,
source: function( request, response ) {
jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
//city: request.term
city: request.term

},
success: function( data ) {
//response( data );
  response(jQuery.map(data, function(item) {
                        return {
                            label:      item.label,
                            value:      item.value,
                            city_id:    item.id,
                            post_code:   item.post_code
                            
                       };
                }));

},




});
},


select: function( event, ui ) {
   
var city_id = ui.item.city_id;
var post_code = ui.item.post_code;

jQuery('#econt_offices_postcode').val(post_code);
jQuery('#office_locator').show(); //show office locator button after the city is selected
jQuery('#econt_offices_postcode, label[for="econt_offices_postcode"], #econt_offices, label[for="econt_offices"]').show();

jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
office_city_id: city_id, 
delivery_type: 'to_office'
},
success: function( data ) {

 jQuery('#econt_offices').empty()

//selectValues = { "1": "test 1", "2": "test 2" };
jQuery.each(data, function(key, value) {
    jQuery('#econt_offices').append(jQuery("<option/>", {
        value: value.id,
        text: value.value + ' [о.к.:' + value.id + ']'
    }));
});

//if(typeof(data) != "undefined" && data !== null) {
calculate_loading(); //calculate loading cost for shipping to office
//}

}
});



},


});
//end of econt checkout and order office autocomplete

//econt checkout and order machine autocomplete
jQuery( "#econt_machines_town" ).autocomplete({
minLength: 2,
source: function( request, response ) {
jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
//city: request.term
city: request.term

},
success: function( data ) {
//response( data );
  response(jQuery.map(data, function(item) {
                        return {
                            label:      item.label,
                            value:      item.value,
                            city_id:    item.id,
                            post_code:   item.post_code
                            
                       };
                }));

},




});
},


select: function( event, ui ) {
   
var city_id = ui.item.city_id;
var post_code = ui.item.post_code;

jQuery('#econt_machines_postcode').val(post_code);
jQuery('#econt_machines_postcode, label[for="econt_machines_postcode"], #econt_machines, label[for="econt_machines"]').show();

jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
machine_city_id: city_id,
//delivery_type: 'to_office'

},
success: function( data ) {

 jQuery('#econt_machines').empty()

//selectValues = { "1": "test 1", "2": "test 2" };
jQuery.each(data, function(key, value) {
    jQuery('#econt_machines').append(jQuery("<option/>", {
        value: value.id,
        text: value.value + ' [о.к.:' + value.id + ']'
    }));
});

calculate_loading(); //calculate loading cost for shipping to machine


}
});



},


});
//end of econt checkout and order machine autocomplete


//econt admin settings office autocomplete
jQuery( "#woocommerce_econt_shipping_method_office_town" ).autocomplete({
minLength: 2,
source: function( request, response ) {
jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
city: request.term

},
success: function( data ) {
  response(jQuery.map(data, function(item) {
                        return {
                            label:      item.label,
                            value:      item.value,
                            city_id:    item.id,
                            post_code:   item.post_code
                            
                       };
                }));

},


});
},



select: function( event, ui ) {
   
var city_id = ui.item.city_id;
var post_code = ui.item.post_code;

//if (ui.item.label == sender_city){
//    alert( sender_city );
//}


jQuery('#woocommerce_econt_shipping_method_office_postcode').val(post_code);

jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
office_city_id: city_id,
delivery_type: 'from_office'

},
success: function( data ) {

 jQuery('#woocommerce_econt_shipping_method_office_office').empty()

//selectValues = { "1": "test 1", "2": "test 2" };
jQuery.each(data, function(key, value) {
    jQuery('#woocommerce_econt_shipping_method_office_office').append(jQuery("<option/>", {
        value: value.id,
        text: value.value
    }));
    
});
jQuery('#woocommerce_econt_shipping_method_office_office').on('change, click', function() {
jQuery('#woocommerce_econt_shipping_method_office_code').val(this.value);

});


}
});



},


});
//econt admin settings office autocomplete




//econt admin settings to APS autocomplete
jQuery( "#woocommerce_econt_shipping_method_machine_town" ).autocomplete({
minLength: 2,
source: function( request, response ) {
jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
city: request.term

},
success: function( data ) {
  response(jQuery.map(data, function(item) {
                        return {
                            label:      item.label,
                            value:      item.value,
                            city_id:    item.id,
                            post_code:   item.post_code
                            
                       };
                }));

},


});
},



select: function( event, ui ) {
   
var city_id = ui.item.city_id;
var post_code = ui.item.post_code;

//if (ui.item.label == sender_city){
//    alert( sender_city );
//}


jQuery('#woocommerce_econt_shipping_method_machine_postcode').val(post_code);

jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
machine_city_id: city_id 

},
success: function( data ) {

 jQuery('#woocommerce_econt_shipping_method_machine_machine').empty()

//selectValues = { "1": "test 1", "2": "test 2" };
jQuery.each(data, function(key, value) {
    jQuery('#woocommerce_econt_shipping_method_machine_machine').append(jQuery("<option/>", {
        value: value.id,
        text: value.value
    }));
    
});
jQuery('#woocommerce_econt_shipping_method_machine_machine').on('change, click', function() {
jQuery('#woocommerce_econt_shipping_method_machine_code').val(this.value);

});


}
});



},


});
//end of econt admin settings to APS autocomplete




//econt checkout and order to/from door autocomplete
jQuery( "#econt_door_town" ).autocomplete({
minLength: 2,
source: function( request, response ) {
jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
//city: request.term
city: request.term

},
success: function( data ) {
//response( data );
  response(jQuery.map(data, function(item) {
                        return {
                            label:      item.label,
                            value:      item.value,
                            city_id:    item.id,
                            post_code:   item.post_code
                            
                       };
                }));

},


});

},


select: function( event, ui ) {
   
var city_id = ui.item.city_id;
var post_code = ui.item.post_code;
//var city_name = ui.item.label;
//alert(  sender_city_id );
//show express city courier if sender city = customer city
if( city_id == sender_city_id ){
jQuery("#econt_city_courier_field").slideToggle();
}else{
jQuery("#econt_city_courier_field").hide();
}

jQuery('#econt_door_postcode').val(post_code);

//calculate_loading(); //calculate loading cost for shipping to door

//show door fields after town is selected
jQuery('#econt_door_postcode, label[for="econt_door_postcode"], #econt_door_street, label[for="econt_door_street"], #econt_door_quarter, label[for="econt_door_quarter"], #econt_door_street_num, label[for="econt_door_street_num"], #econt_door_street_bl, label[for="econt_door_street_bl"], #econt_door_street_vh, label[for="econt_door_street_vh"], #econt_door_street_et, label[for="econt_door_street_et"], #econt_door_street_ap, label[for="econt_door_street_ap"], #econt_door_other, label[for="econt_door_other"], #econt_delivery_days, label[for="econt_delivery_days"]').show();

jQuery( "#econt_door_street" ).autocomplete({
minLength: 2,
source: function( request, response ) {
jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
//city: request.term
door_city_id: city_id,
door_street_name: request.term,
type: 'street'

},
success: function( data ) {
//response( data );
  response(jQuery.map(data, function(item) {
                        return {
                            label:      item.label,
                            value:      item.value,
                            //city_id:    item.id,
                            //post_code:   item.post_code
                            
                       };
                }));



},




});
//calculate_loading(); //calculate loading to door
},

});





jQuery( "#econt_door_quarter" ).autocomplete({
minLength: 2,
source: function( request, response ) {
jQuery.ajax({
url: ajaxurl,
dataType: "json",
data: {

action:'handle_ajax', 
//city: request.term
door_city_id: city_id,
door_quarter_name: request.term,
type: 'quarter'

},
success: function( data ) {
//response( data );
  response(jQuery.map(data, function(item) {
                        return {
                            label:      item.label,
                            value:      item.value,
                            //city_id:    item.id,
                            //post_code:   item.post_code
                            
                       };
                }));

},


});
},

});


},


});
//end of econt checkout and order to/from door autocomplete


//});


//hide and show fields in checkout and order
//jQuery(document).ready(function(){

jQuery('#econt_shipping_to').on('change', function () {
    if(this.value == 'DOOR'){

jQuery('.econt_shipping_to_office').hide();
jQuery('.econt_shipping_to_machine').hide();
jQuery("a#office_locator").hide();

jQuery('#econt_door_town').removeAttr('value');
jQuery('#econt_door_postcode').removeAttr('value');
jQuery('#econt_door_street').removeAttr('value');
jQuery('#econt_door_quarter').removeAttr('value');
jQuery('#econt_door_street_num').removeAttr('value');
jQuery('#econt_door_street_bl').removeAttr('value');
jQuery('#econt_door_street_vh').removeAttr('value');
jQuery('#econt_door_street_et').removeAttr('value');
jQuery('#econt_door_street_ap').removeAttr('value');
jQuery('#econt_door_other').removeAttr('value');


jQuery('.econt_shipping_to_door').slideToggle();
jQuery('#econt_door_postcode, label[for="econt_door_postcode"], #econt_door_street, label[for="econt_door_street"], #econt_door_quarter, label[for="econt_door_quarter"], #econt_door_street_num, label[for="econt_door_street_num"], #econt_door_street_bl, label[for="econt_door_street_bl"], #econt_door_street_vh, label[for="econt_door_street_vh"], #econt_door_street_et, label[for="econt_door_street_et"], #econt_door_street_ap, label[for="econt_door_street_ap"], #econt_door_other, label[for="econt_door_other"], #econt_delivery_days, label[for="econt_delivery_days"]').hide();

}else if(this.value == 'OFFICE'){ 

jQuery('.econt_shipping_to_door').hide();
jQuery('.econt_shipping_to_machine').hide();
jQuery("#econt_city_courier_field").hide();

jQuery('#econt_offices_town').removeAttr('value');
jQuery('#econt_offices_postcode').removeAttr('value');
jQuery('#econt_offices').empty();

jQuery('.econt_shipping_to_office').slideToggle();
jQuery('#econt_offices_postcode, label[for="econt_offices_postcode"], #econt_offices, label[for="econt_offices"]').hide();

} else if(this.value == 'MACHINE'){

jQuery('.econt_shipping_to_door').hide();
jQuery('.econt_shipping_to_office').hide();
jQuery("#econt_city_courier_field").hide();
jQuery("a#office_locator").hide();

jQuery('#econt_machines_town').removeAttr('value');
jQuery('#econt_machines_postcode').removeAttr('value');
jQuery('#econt_machines').empty();

jQuery('.econt_shipping_to_machine').slideToggle();
jQuery('#econt_machines_postcode, label[for="econt_machines_postcode"], #econt_machines, label[for="econt_machines"]').hide();

} else if(this.value == 0) {

jQuery('.econt_shipping_to_door').hide();
jQuery('.econt_shipping_to_office').hide();
jQuery('.econt_shipping_to_machine').hide();
jQuery("#econt_city_courier_field").hide();
jQuery("a#office_locator").hide();

}

});

//admin order details show only the needed field when to/from APS
var receiver_shipping_to = jQuery("#receiver_shipping_to").val();
var sender_door_or_office = jQuery("#sender_door_or_office").val();
if( receiver_shipping_to == 'MACHINE' || sender_door_or_office == 'MACHINE' ){
//jQuery('#row_order_oc, #row_order_pay_after, #row_instruction_returns, #row_invoice, #row_dc, #row_dc_cp, #row_instructions_take, #row_instructions_give, #row_instructions_return, #row_instructions_services, #row_priority_time, #row_city_courier, #row_delivery_day_id, #row_partial_delivery').hide();

if( client_cd_agreement == 0 ){
 //jQuery(jQuery(".order_cd option[value='1']").remove();
    ///jQuery("#order_cd").find('option:selected').removeAttr("selected");
   // jQuery("#order_cd").find("option[value='0']").Attr("selected");
    ///jQuery("#order_cd option[value='0']").attr("selected","selected");

    //jQuery("#order_cd option[value='1']").remove();
    jQuery('#order_cd').val(0);
    jQuery('#row_order_cd').hide();
//jQuery('#order_cd').attr('disabled','disabled');
}

 jQuery('.not_used_to_aps').hide();
 jQuery('.used_from_aps').hide();
jQuery('.priority_time').hide();
 
 if ( sender_door_or_office == 'MACHINE' && receiver_shipping_to != 'MACHINE' ) {
 
 jQuery('.used_from_aps').show();
 
 if( receiver_shipping_to == 'DOOR' ){
   jQuery('.priority_time').show(); 
 }


 }


}

jQuery('#sender_door_or_office').on('change', function () {
 if( (this.value == 'DOOR' && receiver_shipping_to != 'MACHINE') || (this.value == 'DOOR2' && receiver_shipping_to != 'MACHINE') || (this.value == 'OFFICE' && receiver_shipping_to != 'MACHINE') ){
//jQuery('.econt_shipping_from_office').hide();
//jQuery('.econt_shipping_from_door').slideToggle();
//jQuery('#row_order_oc, #row_order_pay_after, #row_instruction_returns, #row_invoice, #row_dc, #row_dc_cp, #row_instructions_take, #row_instructions_give, #row_instructions_return, #row_instructions_services, #row_priority_time, #row_city_courier, #row_delivery_day_id, #row_partial_delivery').show();
 
jQuery('#order_cd').removeAttr('disabled');
 jQuery('.not_used_to_aps').show();
 jQuery('.used_from_aps').show();
 jQuery('.priority_time').show();
 jQuery('#row_order_cd').show();


 } else if(this.value == 'MACHINE' || receiver_shipping_to == 'MACHINE') {
//jQuery('#row_order_oc, #row_order_pay_after, #row_instruction_returns, #row_invoice, #row_dc, #row_dc_cp, #row_instructions_take, #row_instructions_give, #row_instructions_return, #row_instructions_services, #row_priority_time, #row_city_courier, #row_delivery_day_id, #row_partial_delivery').hide();

if( client_cd_agreement == 0 ){

 //jQuery(jQuery(".order_cd option[value='1']").remove();

///jQuery("#order_cd").find('option:selected').removeAttr("selected");

//jQuery("#order_cd").find("option[value='0']").Attr("selected");

///jQuery("#order_cd option[value='0']").attr("selected","selected");

//jQuery("#order_cd option[value='1']").remove();
jQuery('#order_cd').val(0);
jQuery('#row_order_cd').hide();
//jQuery('#order_cd').attr('disabled','disabled');
}

 jQuery('.not_used_to_aps').hide();
 jQuery('.used_from_aps').hide();
 jQuery('.priority_time').hide();

 if ( this.value == 'MACHINE' && receiver_shipping_to != 'MACHINE' ) {
 
 jQuery('.used_from_aps').show();
 
 if( receiver_shipping_to == 'DOOR' ){
   Query('.priority_time').show(); 
 }


 }
//jQuery('.econt_shipping_from_office').hide();
}

});

var woocommerce_econt_shipping_method_send_from = jQuery('#woocommerce_econt_shipping_method_send_from').val();

jQuery('#woocommerce_econt_shipping_method_send_from').on('change', function () {
if(this.value == 'MACHINE'){
   // alert(jQuery('#woocommerce_econt_shipping_method_cd').val());
if(jQuery('#woocommerce_econt_shipping_method_cd').val() == 1 && jQuery('#woocommerce_econt_shipping_method_client_cd_num').val() == 0 ){

alert('Когато изпращате от АПС и активирате услугата плащане при доставка (наложен платеж) е задължително да изпозвате споразумение за събиране на наложен платеж!');
jQuery('#woocommerce_econt_shipping_method_send_from').val( woocommerce_econt_shipping_method_send_from );


} else {
    alert('услуги, които можете да използвате, когато изпращате от АПС са:\n- наложен платеж (когато използвате споразумение за събиране на НП) \n- обратна разписка\n- двупосочна пратка\n- Час за приоритет (когато изпращате до адрес)\n- Преглед\n- Преглед и тест\n- Преглед, тест и избор');
} 

}

});


//});

//econt admin settings refresh econt offices and adresses
jQuery('#woocommerce_econt_shipping_method_refreshdata').click(function(){

    var username  = jQuery("#woocommerce_econt_shipping_method_username").val();
    var password  = jQuery("#woocommerce_econt_shipping_method_password").val();
    var live      = jQuery("#woocommerce_econt_shipping_method_live").val();



jQuery("#woocommerce_econt_shipping_method_refreshdata").prop('value', 'Please Wait. Loading...');
    jQuery.ajax({

        url: ajaxurl,
        dataType: "json",
        data:{
        action:'handle_ajax',
        refresh_data: 1,
        username: username,
        password: password,
        live: live,
        }, 
        //type: 'post',


        success: function(data){

        jQuery("#woocommerce_econt_shipping_method_refreshdata").prop('value', data);
   console.log(data);
        },
    });


});
//end of econt admin settings refresh econt offices and adresses


//jQuery(document).ready( function() {
  var form = jQuery('#order_loading_form');
//admin order calculate or create loading
   jQuery("#order_only_calculate_loading, #place_order").click( function() { 

    var data2 = jQuery('#order_loading_form').serialize();
   
    jQuery.ajax({

        url: ajaxurl,
        dataType: "json",
        data: data2 + '&action=handle_ajax&action2=only_calculate_loading',

        
        success: function(data){

            jQuery('#create_loading tr').remove();
            jQuery.each(data, function(key, val) {
                if(key == 'econt_shipping_expenses'){
            jQuery('<tr><td>Обща цена за доставка:</td><td id="'+key+'"><strong>'+val['total_shipping_cost']+val['currency_symbol']+'</strong></td><tr>').appendTo('#create_loading');
            jQuery('<tr><td>Цена за доставка за плащане от клиента:</td><td id="'+key+'"><strong>'+val['customer_shipping_cost']+val['currency_symbol']+'</strong></td><tr>').appendTo('#create_loading');
            }else if (key == 'warning'){
                alert( val );
             }

            });

        },
    });
  } );

      jQuery("#order_create_loading").click( function() { 

    var data2 = jQuery('#order_loading_form').serialize();
   
    jQuery.ajax({

        url: ajaxurl,
        dataType: "json",
        data: data2 + '&action=handle_ajax&action2=create_loading',

        
        success: function(data){

        jQuery('#create_loading tr').remove();
        //alert(data['currency']);
            //jQuery.each(data, function(key, val) {
           // if(key == 'pdf_url'){
            if( data['warning'] ){ 
            alert( data['warning'] ); 
            } else { 
                //jQuery("#order_loading_form").hide();
                jQuery('<tr><td>линк към товарителницата в PDF формат:</td><td id="pdf_url"><strong><a href="'+data['pdf_url']+'" target="_blank">'+data['pdf_url']+'</a></strong></td><tr>').appendTo('#create_loading');
            
           // }else if(key == 'loading_num'){
            
               jQuery('<tr><td>Номер на товарителница:</td><td id="loading_num"><strong>'+data['loading_num']+'</strong></td><tr>').appendTo('#create_loading');
             
            // }else if(key == 'total_sum'){
                
                jQuery('<tr><td>Обща цена за доставка:</td><td id="total_sum"><strong>'+data['total_shipping_cost']+data['currency_symbol']+'</strong></td><tr>').appendTo('#create_loading');
             
            // }else if(key == 'order_total_sum'){
                
                jQuery('<tr><td>Цена на доставка за плащане от клиента:</td><td id="order_total_sum"><strong>'+data['customer_shipping_cost']+data['currency_symbol']+'</strong></td><tr>').appendTo('#create_loading');
             
            // }else if(key == 'loading_id' || key == 'order_id' || key == 'blank_yes' || key == 'blank_no' ){

            // }else if(key == 'warning'){
            //    alert( val );
            // }

            //});
                location.reload();
            }
        },
    });
  } );
//end of admin order calculate or create loading


//} );

//turn off autocomplete in econt checkout filed due to some bugs reported 
//jQuery(function() {
jQuery("#econt_offices_town", "#woocommerce_econt_shipping_method_office_town", "#econt_door_town", "#econt_door_street", "#econt_door_quarter").attr("autocomplete","off");
//});


//jQuery(document).ready(function(){
//jQuery(function(){

//var data = jQuery("#econt_door_town, #econt_door_postcode, #econt_door_street, #econt_door_quarter, #econt_door_street_num, #econt_door_street_bl, #econt_door_street_vh, #econt_door_street_et, #econt_door_street_ap, #econt_door_other, #econt_city_courier").serializeArray();
//var data2 = jQuery('#econt_city_courier').val();

//jQuery("#econt_city_courier").change(function () {
//   var data2 = jQuery("#econt_city_courier").val();
//   //$(this).next('span.out').text(txt);
//});
//var data = jQuery(".econt_shipping_to_door").serialize();
//ajax: $.post("you action url", data);



/*
jQuery("#button_calculate_loading, #place_order").click(function(e){

var econt_shipping_to = jQuery("#econt_shipping_to").val();
//var descrioption = jQuery("#econt_admin_settings_live").val();

var pack_count = 1;

var receiver_name = jQuery("#billing_company").val();
var receiver_name_person = jQuery("#billing_first_name").val()+' '+jQuery("#billing_last_name").val();
var receiver_phone_num = jQuery("#billing_phone").val();
var receiver_email = jQuery("#billing_email").val();
//var sms = jQuery("#econt_admin_settings_sms").val();

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


//alert( econt_shipping_to );
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

        },

        success: function(data){

            //jQuery('#create_loading tr').remove();
            jQuery.each(data, function(key, val) {
            if(key == 'econt_shipping_expenses'){
            //jQuery('<tr><td>Цена за доставка с Еконт Експрес:</td><td><strong>'+val+'лв.</strong></td><tr>').appendTo('.woocommerce-checkout-review-order-table');
            jQuery("#button_calculate_loading").prop('value', 'Цена на доставка: '+val['customer_shipping_cost']);
            jQuery("#econt_customer_shipping_cost").attr('value', val['customer_shipping_cost'] );
            jQuery("#econt_total_shipping_cost").attr('value', val['total_shipping_cost'] );
            jQuery('#'+key).remove();
            jQuery('.woocommerce-checkout-review-order-table tr:last').after('<tr id="'+key+'"><td>Цена на доставка с Еконт Експрес:</td><td><strong>'+val['customer_shipping_cost']+val['currency_symbol']+'</strong></td><tr>').appendTo('.woocommerce-checkout-review-order-table');
            //jQuery('.amount').text(val);
            }else if (key == 'warning'){
                alert( val );
             }

            });

        },


 });

 });

*/












//jQuery("#econt_offices_town").change(function(e){
//jQuery('#econt_offices_postcode').on('input', function() {
function calculate_loading(){
 // this code prevents form from actually being submitted
    // e.preventDefault();
    // e.returnValue = false;

var econt_shipping_to = jQuery("#econt_shipping_to").val();

if(jQuery('#payment_method_cod').is(':checked')){ 
var payment_method_cod = 1; 
}else{ 
var payment_method_cod = 0; 
}
//var payment_method_cod = jQuery("#payment_method_cod").val();
//var descrioption = jQuery("#econt_admin_settings_live").val();

var pack_count = 1;

var receiver_name = jQuery("#billing_company").val();
var receiver_name_person = jQuery("#billing_first_name").val()+' '+jQuery("#billing_last_name").val();
var receiver_phone_num = jQuery("#billing_phone").val();
var receiver_email = jQuery("#billing_email").val();
//var sms = jQuery("#econt_admin_settings_sms").val();

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


//alert( econt_shipping_to );
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

            //jQuery('#create_loading tr').remove();
            jQuery.each(data, function(key, val) {
            if(key == 'econt_shipping_expenses'){
            //jQuery('<tr><td>Цена за доставка с Еконт Експрес:</td><td><strong>'+val+'лв.</strong></td><tr>').appendTo('.woocommerce-checkout-review-order-table');
            jQuery("#button_calculate_loading").prop('value', 'Цена на доставка: '+val['customer_shipping_cost']);
            jQuery("#econt_customer_shipping_cost").attr('value', val['customer_shipping_cost'] );
            jQuery("#econt_total_shipping_cost").attr('value', val['total_shipping_cost'] );
            jQuery('#'+key).remove();
            jQuery('.woocommerce-checkout-review-order-table tr:last').after('<tr id="'+key+'"><td>Цена на доставка с Еконт Експрес:</td><td><strong>'+val['customer_shipping_cost']+val['currency_symbol']+'</strong></td><tr>').appendTo('.woocommerce-checkout-review-order-table');
            //jQuery('.amount').text(val);
            }else if (key == 'warning'){
                alert( val );
             }

            });

        },


 });

 //});
}







//jQuery("#econt_city_courier, #econt_delivery_days, #econt_door_other, #button_calculate_loading, #econt_door_street, #econt_door_quarter").on('click' , function(e){

//calculate_loading();

//});

//jQuery("#econt_city_courier, #econt_delivery_days").on('change' , function(e){

//calculate_loading();

//});






//function deleteLoading() {
jQuery("#delete_loading").click(function(e){

var loading_num = jQuery("#loading_num").val();

//alert( econt_shipping_to );
jQuery.ajax({

        url: ajaxurl,
        dataType: "json",
        
        data: {
        action: 'handle_ajax',
        action2: 'delete_loading',
        loading_num: loading_num,

        },

        success: function(data){
        //alert('delete_loading');
        location.reload(); 

        },


 });

 });

//}








jQuery("#button_request_of_courier").click(function(e){

window.open('http://ee.econt.com/?target=EeRequestOfCourier&eshop=1', '_blank');

});

//zabranqva promqnata na ofis kod i poshtenski ofis kod v admin nastrojkite na plugina
jQuery('#woocommerce_econt_shipping_method_office_postcode, #woocommerce_econt_shipping_method_office_code, #woocommerce_econt_shipping_method_machine_postcode, #woocommerce_econt_shipping_method_machine_code').prop('readonly', true);

//zatvarq sekciqta "customer fileds" v poruchkata
jQuery('#postcustom').addClass('closed');


});

//inputText =  jQuery("#econt_offices_town").attr('value') 

//    if('София' == inputText ){
 //       alert( inputText );

//}
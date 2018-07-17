<?php if(isset($order_wp['no_weight'])){ ?>
<H3><?php _e('Please, add weight to the products in the list then you\'ll able to create Econt Express loading.', 'woocommerce-econt') ?></H3>
<table border='0'>
<tr><td colspan='3'><?php _e('Products without weight:', 'woocommerce-econt') ?></td></tr>
<tr><td><?php _e('product id', 'woocommerce-econt') ?></td><td><?php _e('product name', 'woocommerce-econt') ?></td><td></td></tr>  
<?php foreach ($order_wp['no_weight'] as $key => $value) {
echo '<tr><td>'.$value['product_id'].'</td><td><a href="' .get_edit_post_link( $value['product_id'] ). '" target="_blank">'.$value['name'].'</a></td><td><a href="' .get_edit_post_link( $value['product_id'] ). '" target="_blank">' . __('Edit', 'woocommerce-econt'). '</a></td></tr>';
}
?>
</table>
<?php }else{ ?>


<?php if($loading == false){ ?>
<H3><?php _e('Create Loading', 'woocommerce-econt') ?></H3>
</form>
<form id='order_loading_form' onsubmit="return false;">
<table>
<tbody>
<tr>
<td>
<?php _e('Send:', 'woocommerce-econt') ?>
</td>
<td>
<select id='sender_door_or_office' name='sender_door_or_office' onchange="displaySenderDoor();">
  <option value='OFFICE' <?php echo ($wc_econt->send_from == 'OFFICE') ?  'selected="selected"' : '' ; ?>><?php _e('from default office', 'woocommerce-econt') ?></option>
  <option value='DOOR' <?php echo ($wc_econt->send_from == 'DOOR') ?  'selected="selected"' : '' ; ?>><?php _e('from default door', 'woocommerce-econt') ?></option>
  <option value='MACHINE' <?php echo ($wc_econt->send_from == 'MACHINE') ?  'selected="selected"' : '' ; ?>><?php _e('from default machine', 'woocommerce-econt') ?></option>
  <option value='DOOR2'><?php _e('from door', 'woocommerce-econt') ?></option>
</select>
</td>
</tr>

<tr id='sender_door' style='display:none'>
<td>
<?php _e('Sender Address:', 'woocommerce-econt') ?>
</td>
<td>
<select id='sender_door' name='sender_door'>
  <?php foreach ($sender_addresses as $key => $value) { ?>
  <option value='<?php echo $key ?>' ><?php echo $value ?></option>  
<?php } ?>
</select>
</td>
</tr>

<tr id='row_payment_side'>
<td>
<?php _e('Payment Side:', 'woocommerce-econt') ?>
</td>
<td>
<select id='payment_side' name='payment_side'>
  <option value='RECEIVER' <?php echo ($wc_econt->payment_side == 'RECEIVER' && (float)$customer_shipping_cost != 0) ?  'selected="selected"' : '' ; ?>><?php _e('receiver', 'woocommerce-econt') ?></option>
  <option value='SENDER' <?php echo ($wc_econt->payment_side == 'SENDER' || (float)$customer_shipping_cost == 0) ?  'selected="selected"' : '' ; ?>><?php _e('sender', 'woocommerce-econt') ?></option>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Weight:', 'woocommerce-econt') ?>
</td>
<td>
<input type='text' name='weight' value='<?php echo $order_wp['weight']; ?>' size='4'>
</td>
</tr>
<tr>
<td>	
<?php _e('Pack count:', 'woocommerce-econt') ?>
</td>
<td>
<input type='text' name='pack_count' value='1' size='3'>
</td>
</tr>
<tr id='row_order_cd'>
<td>
<?php _e('Cash on delivery', 'woocommerce-econt') ?>
</td>
<td>
<select id='order_cd' name='order_cd'>
  <option value='1' <?php echo ((int)$wc_econt->cd == 1) ?  'selected="selected"' : '' ; ?>><?php _e('yes', 'woocommerce-econt') ?></option>
  <option value='0' <?php echo ((int)$wc_econt->cd == 0) ?  'selected="selected"' : '' ; ?>><?php _e('no', 'woocommerce-econt') ?></option>
</select>
<?php _e('amount: ', 'woocommerce-econt') ?>
<input type='text' id='order_cd_amount' name='order_cd_amount' value='<?php echo $order_wp['price'] ?>' size='6'><?php echo $currency_symbol ?>
</td>
</tr>
</tbody>
<tbody class='used_from_aps'>
<tr id='row_order_pay_after'>
<td>
<?php _e('Pay after:', 'woocommerce-econt') ?>  
</td>
<td>
<select id='order_pay_after' name='order_pay_after'>
  <option value='0' <?php echo ((int)$wc_econt->pay_after == 0) ?  'selected="selected"' : '' ; ?>><?php _e('None', 'woocommerce-econt') ?></option>
  <option value='accept' <?php echo ($wc_econt->pay_after == 'accept') ?  'selected="selected"' : '' ; ?>><?php _e('Accept', 'woocommerce-econt') ?></option>
  <option value='test' <?php echo ($wc_econt->pay_after == 'test') ?  'selected="selected"' : '' ; ?>><?php _e('Test', 'woocommerce-econt') ?></option>
</select> 
</td> 
</tr>
<tr id='row_dc'>
<td>
<?php _e('Attach a service acknowledgment:', 'woocommerce-econt') ?>
</td>
<td>
<select id='dc' name='dc'>
  <option value='0' <?php echo ((int)$wc_econt->dc == 0) ?  'selected="selected"' : '' ; ?>><?php _e('no', 'woocommerce-econt') ?></option>
  <option value='1' <?php echo ((int)$wc_econt->dc == 1) ?  'selected="selected"' : '' ; ?>><?php _e('yes', 'woocommerce-econt') ?></option>
</select>
</td>
</tr>
</tbody>

<tbody class='priority_time'>

<?php 
$priority_time_type_id = '11'; 
$error_priority_time = '';
$priority_time_hours = array();
?>
          <tr id="row_priority_time">
            <td><input type="checkbox" id="priority_time" name="priority_time" value="1" <?php if ((int)$wc_econt->priority_time == 1) { ?> checked="checked"<?php } ?> onclick="checkPriorityTime();" />
              <label for="priority_time"><?php echo _e('priority time', 'woocommerce-econt') ?></label></td>
            <td><select id="priority_time_type" name="priority_time_type" <?php if ((int)$wc_econt->priority_time == 0) { ?> disabled="disabled"<?php } ?> onchange="setPriorityTime();">
              <option value="0"><?php _e('choose', 'woocommerce-econt') ?></option>
              <?php foreach ($priority_time_types as $priority_time_type) { ?>
              <?php if ($priority_time_type['id'] == $priority_time_type_id) { ?>
              <?php $priority_time_hours = $priority_time_type['hours']; ?>
              <option value="<?php echo $priority_time_type['id']; ?>" selected="selected"><?php echo $priority_time_type['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $priority_time_type['id']; ?>"><?php echo $priority_time_type['name']; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
              <select id="priority_time_hour" name="priority_time_hour" <?php if ((int)$wc_econt->priority_time == 0) { ?> disabled="disabled"<?php } ?>>
              <?php foreach ($priority_time_hours as $priority_time_hour) { ?>
              <?php if ($priority_time_hour == $priority_time_hour_id) { ?>
              <option value="<?php echo $priority_time_hour; ?>" selected="selected"><?php echo $priority_time_hour; ?></option>
              <?php } else { ?>
              <option value="<?php echo $priority_time_hour; ?>"><?php echo $priority_time_hour; ?></option>
              <?php } ?>
              <?php } ?>
              </select>
              <label for="priority_time_hour"><?php _e('hour', 'woocommerce-econt') ?></label>
              <?php if ($error_priority_time) { ?>
              <span class="error"><?php echo $error_priority_time; ?></span>
              <?php } ?></td>
          </tr>



</tbody>

<tbody class='not_used_to_aps'>
<tr id='row_order_oc'>
<td>
<?php _e('Declared Value:', 'woocommerce-econt') ?>
</td>
<td>
<select id='order_oc' name='order_oc'>
  <option value='0' <?php echo ((int)$wc_econt->oc == 0) ?  'selected="selected"' : '' ; ?>><?php _e('no', 'woocommerce-econt') ?></option>
  <option value='1' <?php echo ((int)$wc_econt->oc == 1 || $wc_econt->oc >= $order_wp['price'] ) ?  'selected="selected"' : '' ; ?>><?php _e('yes', 'woocommerce-econt') ?></option>
</select>
<?php _e('amount: ', 'woocommerce-econt') ?>
<input type='text' id='order_oc_amount' name='order_cd_amount' value='<?php echo $order_wp['price'] ?>' size='6'><?php echo $currency_symbol ?>

</td>
</tr>
<tr>
<td>
<?php _e('Dispose to refuse shipment after review:', 'woocommerce-econt') ?>	
</td>
<td>
<select id='instruction_returns' name='instruction_returns'>
  <option value='0' <?php echo ((int)$wc_econt->instruction_returns == 0) ?  'selected="selected"' : '' ; ?>><?php _e('none', 'woocommerce-econt') ?></option>
  <option value='returns' <?php echo ($wc_econt->instruction_returns == 'returns') ?  'selected="selected"' : '' ; ?>><?php _e('returns', 'woocommerce-econt') ?></option>
  <option value='shipping_returns' <?php echo ($wc_econt->instruction_returns == 'shipping_returns') ?  'selected="selected"' : '' ; ?>><?php _e('shipping returns', 'woocommerce-econt') ?></option>
</select>	
</td>	
</tr>
<tr>
<td>
<?php _e('SMS on delivery:', 'woocommerce-econt') ?>
</td>
<td>
<select id='sms' name='sms'>
  <option value='0' <?php echo ((int)$wc_econt->sms == 0) ?  'selected="selected"' : '' ; ?>><?php _e('no', 'woocommerce-econt') ?></option>
  <option value='1' <?php echo ((int)$wc_econt->sms == 1) ?  'selected="selected"' : '' ; ?>><?php _e('yes', 'woocommerce-econt') ?></option>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Invoice before Cash on Delivery:', 'woocommerce-econt') ?>
</td>
<td>
<select id='invoice' name='invoice'>
  <option value='0' <?php echo ((int)$wc_econt->invoice == 0) ?  'selected="selected"' : '' ; ?>><?php _e('no', 'woocommerce-econt') ?></option>
  <option value='1' <?php echo ((int)$wc_econt->invoice == 1) ?  'selected="selected"' : '' ; ?>><?php _e('yes', 'woocommerce-econt') ?></option>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Attach a service acknowledgment/bill of goods:', 'woocommerce-econt') ?>
</td>
<td>
<select id='dc_cp' name='dc_cp'>
  <option value='0' <?php echo ((int)$wc_econt->dc_cp == 0) ?  'selected="selected"' : '' ; ?>><?php _e('no', 'woocommerce-econt') ?></option>
  <option value='1' <?php echo ((int)$wc_econt->dc_cp == 1) ?  'selected="selected"' : '' ; ?>><?php _e('yes', 'woocommerce-econt') ?></option>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Instructions take:', 'woocommerce-econt') ?>
</td>
<td>
<select id='instructions_take' name='instructions_take'>
  <?php foreach ($instructions_take as $key => $value) { ?>
  <option value='<?php echo $key ?>' <?php echo ($wc_econt->instructions_take == $key) ?  'selected="selected"' : '' ; ?>><?php echo $value ?></option>  
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Instructions give:', 'woocommerce-econt') ?>
</td>
<td>
<select id='instructions_give' name='instructions_give'>
  <?php foreach ($instructions_give as $key => $value) { ?>
  <option value='<?php echo $key; ?>' <?php echo ($wc_econt->instructions_give == $key) ?  'selected="selected"' : '' ; ?>><?php echo $value; ?></option>  
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Instructions return:', 'woocommerce-econt') ?>
</td>
<td>
<select id='instructions_return' name='instructions_return'>
  <?php foreach ($instructions_return as $key => $value) { ?>
  <option value='<?php echo $key; ?>' <?php echo ($wc_econt->instructions_return == $key) ?  'selected="selected"' : '' ; ?>><?php echo $value; ?></option>  
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Instructions Services:', 'woocommerce-econt') ?>
</td>
<td>
<select id='instructions_services' name='instructions_services'>
  <?php foreach ($instructions_services as $key => $value) { ?>
  <option value='<?php echo $key; ?>' <?php echo ($wc_econt->instructions_services == $key) ?  'selected="selected"' : '' ; ?>><?php echo $value; ?></option>  
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Express City Courier', 'woocommerce-econt') ?>
</td>
<td>
<select id='city_courier' name='city_courier' onchange="displayCityCourierType();">
  <option value='0' <?php echo ((int)$wc_econt->partial_delivery == 0) ?  'selected="selected"' : '' ; ?>><?php _e('no', 'woocommerce-econt') ?></option>
  <option value='1' <?php echo ((int)$wc_econt->partial_delivery == 1) ?  'selected="selected"' : '' ; ?>><?php _e('yes', 'woocommerce-econt') ?></option>
  </select>
</td>
</tr>
<tr id='econt_city_courier' style='<?php echo ((int)$wc_econt->city_courier == 0) ?  'display:none' : '' ; ?>'>
<td>
<?php _e('Express City Courier Type', 'woocommerce-econt') ?>
</td>
<td>
<select id='econt_city_courier' name='econt_city_courier'>
  <option value='0' <?php echo (!get_post_meta( $thepostid, 'Econt_City_Courier', true)) ?  'selected="selected"' : '' ; ?>><?php _e('choose', 'woocommerce-econt') ?></option>
  <option value='e1' <?php echo (get_post_meta( $thepostid, 'Econt_City_Courier', true) == 'e1') ?  'selected="selected"' : '' ; ?>><?php _e('up to 60 minutes', 'woocommerce-econt') ?></option>
  <option value='e2' <?php echo (get_post_meta( $thepostid, 'Econt_City_Courier', true) == 'e2') ?  'selected="selected"' : '' ; ?>><?php _e('up to 90 minutes', 'woocommerce-econt') ?></option>
  <option value='e3' <?php echo (get_post_meta( $thepostid, 'Econt_City_Courier', true) == 'e3') ?  'selected="selected"' : '' ; ?>><?php _e('up to 120 minutes', 'woocommerce-econt') ?></option>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Delivery Days:', 'woocommerce-econt') ?>
</td>
<td>
<select id='delivery_day_id' name='delivery_day_id'>
  <option value='0'><?php _e('No', 'woocommerce-econt') ?></option>  
  <?php foreach ($delivery_days as $key => $value) { ?>
  <option value='<?php echo $key; ?>' <?php echo (get_post_meta( $thepostid, 'Econt_Delivery_Days', true) == $key) ?  'selected="selected"' : '' ; ?>><?php echo $value; ?></option>  
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
<?php _e('Partial Delivery:', 'woocommerce-econt') ?>
</td>
<td>
<select id='partial_delivery' name='partial_delivery' onchange="displayInventory();">
  <option value='0' <?php echo ((int)$wc_econt->partial_delivery == 0) ?  'selected="selected"' : '' ; ?>><?php _e('no', 'woocommerce-econt') ?></option>
  <option value='1' <?php echo ((int)$wc_econt->partial_delivery == 1) ?  'selected="selected"' : '' ; ?>><?php _e('yes', 'woocommerce-econt') ?></option>
</select>
</td>
</tr>
<tr id='inventory' style='<?php echo ((int)$wc_econt->partial_delivery == 0) ?  'display:none' : '' ; ?>'>
<td>
<?php _e('List Type:', 'woocommerce-econt') ?>
</td>
<td>
<select id='inventory' name='inventory' onchange="displayInventoryType();">
  <option value='0' ><?php _e('choose', 'woocommerce-econt') ?></option>
  <option value='DIGITAL' ><?php _e('digital', 'woocommerce-econt') ?></option>
  <option value='LOADING' ><?php _e('loading', 'woocommerce-econt') ?></option>
</select>
</td>
</tr>
 <tr id="inventory_type_loading" style="display: none;"><td colspan="2"><?php _e('You must print an inventory shipping list and attach it to the loading.', 'woocommerce-econt') ?></td></tr>

<tr>
<td colspan='2'>
         <table id="inventory_type_digital" style="display:none">
              <thead>
                <tr>
                  <td class="left" style="width: 13%;"><?php _e('product id', 'woocommerce-econt') ?></td>
                  <td class="left"><?php _e('product name', 'woocommerce-econt') ?></td>
                  <td class="left"><?php _e('product weight', 'woocommerce-econt') ?></td>
                  <td class="left"><?php _e('product price', 'woocommerce-econt') ?></td>
                  <td>&nbsp;</td>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <td colspan="4">&nbsp;</td>
                  <td class="left"><a onclick="addProduct();" class="button"><span><?php _e('add', 'woocommerce-econt') ?></span></a></td>
                </tr>
              </tfoot>
              <tbody id="products">
                <?php $product_row = 0; ?>
                <?php foreach ($order_wp['products'] as $product) { ?>
                <tr id="product_<?php echo $product_row; ?>">
                  <td class="left"><input type="text" id="product_id_<?php echo $product_row; ?>" name="products[<?php echo $product_row; ?>][product_id]" value="<?php echo $product['product_id']; ?>" size="3" /></td>
                  <td class="left"><input type="text" id="product_name_<?php echo $product_row; ?>" name="products[<?php echo $product_row; ?>][name]" value="<?php echo $product['name'] .' - '. $product['qty'] . __('qty','woocommerce-econt'); ?>" size="50" /></td>
                  <td class="left"><input type="text" id="product_weight_<?php echo $product_row; ?>" name="products[<?php echo $product_row; ?>][weight]" value="<?php echo $product['weight']; ?>" size="10" /></td>
                  <td class="left"><input type="text" id="product_price_<?php echo $product_row; ?>" name="products[<?php echo $product_row; ?>][price]" value="<?php echo $product['price']; ?>" size="10" /></td>
                  <td class="left"><a onclick="$('#product_<?php echo $product_row; ?>').remove();" class="button"><span><?php _e('remove', 'woocommerce-econt') ?></span></a></td>
                </tr>
                <?php $product_row++; ?>
                <?php } ?>
              </tbody>
            </table>
</td>
</tr>
</tbody>


<tr id='row_description'>
<td>
<?php _e('Description:', 'woocommerce-econt') ?>	
</td>	
<td>
 <input type='text' name='description' value='<?php echo $description ?>'> 	
</td>
</tr>
<tr>
<td colspan='2'>
<button id='order_only_calculate_loading' class='button' type='submit' name='action2' value='only_calculate_loading' ><?php _e('Calculate Shipping Cost', 'woocommerce-econt') ?></button>
<button id='order_create_loading' class='button button-primary' type='submit' name='action2' value='create_loading'><?php _e('Create Loading', 'woocommerce-econt') ?></button>
<button id='button_request_of_courier' class='button' type='' name='' value=''><?php _e('Request for courier', 'woocommerce-econt') ?></button>

</td>
</tr>
</table>

 <input type='hidden' name='receiver_city' value='<?php echo $receiver_city ?>'>
 <input type='hidden' name='receiver_post_code' value='<?php echo $receiver_post_code ?>'> 
 <input type='hidden' name='receiver_office_code' value='<?php echo $receiver_office_code ?>'>
 <input type='hidden' name='receiver_name' value='<?php echo $receiver_name ?>'> 
 <input type='hidden' name='receiver_name_person' value='<?php echo $receiver_name_person ?>'>
 <input type='hidden' name='receiver_email' value='<?php echo $receiver_email ?>'> 
 <input type='hidden' name='receiver_street' value='<?php echo $receiver_street ?>'>
 <input type='hidden' name='receiver_quarter' value='<?php echo $receiver_quarter ?>'> 
 <input type='hidden' name='receiver_street_num' value='<?php echo $receiver_street_num ?>'>
 <input type='hidden' name='receiver_street_bl' value='<?php echo $receiver_street_bl ?>'> 
 <input type='hidden' name='receiver_street_vh' value='<?php echo $receiver_street_vh ?>'>
 <input type='hidden' name='receiver_street_et' value='<?php echo $receiver_street_et ?>'> 
 <input type='hidden' name='receiver_street_ap' value='<?php echo $receiver_street_ap ?>'>
 <input type='hidden' name='receiver_street_other' value='<?php echo $receiver_street_other ?>'> 
 <input type='hidden' name='receiver_phone_num' value='<?php echo $receiver_phone_num ?>'>
 <input type='hidden' id='receiver_shipping_to' name='receiver_shipping_to' value='<?php echo $receiver_shipping_to ?>'>

 <input type='hidden' name='order_id' value='<?php echo $thepostid ?>'>

<table id='create_loading'></table>
</form>
<p></p>
<?php  }else{ //$loading == true ?>
<?php $product_row = 0; ?>
 <input type='hidden' id='loading_num' name='loading_num' value='<?php echo $loading['loading_num'] ?>'>
<table>
	<tr><td coolspan='2'><strong><?php _e('Loading Details', 'woocommerce-econt') ?></strong></td></tr>
	<tr><td><?php _e('Loading number:', 'woocommerce-econt') ?></td><td><a href='<?php echo $loading['pdf_url'] ?>' target='_blank'><?php echo $loading['loading_num'] ?></a></td></tr>
	<tr><td><?php _e('Loading shipping cost:', 'woocommerce-econt') ?></td><td><strong><?php echo $total_shipping_cost . ' '. $currency ?></strong></td></tr>
  <tr><td><?php _e('Loading shipping cost to be paid by the customer:', 'woocommerce-econt') ?></td><td><strong><?php echo $customer_shipping_cost . ' '. $currency ?></strong></td></tr>
</table>

<!-- tracking -->

<div id="content">

  <?php if ($data['error_warning']) { ?>
  <div class="warning"><?php __('error_connect', 'woocommerce-econt') ?></div>
  <?php } ?>
  <div class="box">
  <div class="content">
    <table class="form">
      <tr>
        <td style="width: 300px;"><?php _e('Loading number:', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['loading_num']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Is imported', 'woocommerce-econt') ?></td>
        <td><?php if ((int)$loading['is_imported']) { ?>
          <?php  _e('yes', 'woocommerce-econt') ?>
          <?php } else { ?>
          <?php _e('no', 'woocommerce-econt') ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php _e('Storage', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['storage']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver person', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['receiver_person']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver person phone', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['receiver_person_phone']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver Courier', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['receiver_courier']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver Courier phone', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['receiver_courier_phone']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver Time', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['receiver_time']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver cd get sum', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['cd_get_sum']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver cd get time ', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['cd_get_time']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver cd send sum', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['cd_send_sum']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver cd send time', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['cd_send_time']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Total sum', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['total_sum']; ?> <?php echo $loading['currency']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Sender amount due', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['sender_ammount_due']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver amount due', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['receiver_ammount_due']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Other amount due', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['other_ammount_due']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Delivery attempt count', 'woocommerce-econt') ?></td>
        <td><?php echo $loading['delivery_attempt_count']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Blank yes', 'woocommerce-econt') ?></td>
        <td><?php if ($loading['blank_yes']) { ?>
          <a href="<?php echo $loading['blank_yes']; ?>" target="_blank"><?php _e('View', 'woocommerce-econt') ?></a>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php _e('Blank no', 'woocommerce-econt') ?></td>
        <td><?php if ($loading['blank_no']) { ?>
          <a href="<?php echo $loading['blank_no']; ?>" target="_blank"><?php _e('View', 'woocommerce-econt') ?></a>
          <?php } ?></td>
      </tr>
      <?php if ($loading['pdf_url']) { ?>
      <tr>
        <td><?php _e('PDF URL', 'woocommerce-econt') ?></td>
        <td><a href="<?php echo $loading['pdf_url']; ?>" target="_blank"><?php _e('View', 'woocommerce-econt') ?></a></td>
      </tr>
      <?php } ?>
    </table>
    <?php if ($loading['trackings']) { ?>
    <b><?php _e('Tracking', 'woocommerce-econt') ?></b>
    <table class="list">
      <thead>
        <tr>
          <td class="left"><?php _e('Time', 'woocommerce-econt') ?></td>
          <td class="left"><?php _e('Is receipt', 'woocommerce-econt') ?></td>
          <td class="left"><?php _e('Event', 'woocommerce-econt') ?></td>
          <td class="left"><?php _e('Name', 'woocommerce-econt') ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($loading['trackings'] as $tracking) { ?>
        <tr>
          <td class="left"><?php echo $tracking['time']; ?></td>
          <td class="left"><?php echo $tracking['is_receipt']; ?></td>
          <td class="left"><?php echo $tracking['event']; ?></td>
          <td class="left"><?php echo $tracking['name']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php } ?>
    <?php if ($loading['next_parcels']) { ?>
    <b><?php _e('Next Parcels', 'woocommerce-econt') ?></b>
    <?php foreach ($loading['next_parcels'] as $next_parcel) { ?>
    <table class="form">
      <tr>
        <td style="width: 300px;"><?php _e('Loading Number', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['loading_num']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Is imported', 'woocommerce-econt') ?></td>
        <td><?php if ((int)$next_parcel['is_imported']) { ?>
          <?php _e('yes', 'woocommerce-econt') ?>
          <?php } else { ?>
          <?php _e('no', 'woocommerce-econt') ?>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php _e('Storage', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['storage']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver person', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['receiver_person']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver person Phone', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['receiver_person_phone']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver courier', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['receiver_courier']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver courier phone', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['receiver_courier_phone']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver time', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['receiver_time']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Cash on delivery get sum', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['cd_get_sum']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Cash on delivery get time', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['cd_get_time']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Cash on delivery send sum', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['cd_send_sum']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Cash on delivery send time', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['cd_send_time']; ?></td>
      </tr>
      <tr>
        <td><?php _e('total sum', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['total_sum']; ?> <?php echo $next_parcel['currency']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Sender amount due', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['sender_ammount_due']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Receiver amount due', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['receiver_ammount_due']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Other amount due', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['other_ammount_due']; ?></td>
      </tr>
      <tr>
        <td><?php _e('Delivery attempt count', 'woocommerce-econt') ?></td>
        <td><?php echo $next_parcel['delivery_attempt_count']; ?></td>
      </tr>
      <tr>
        <td><?php _e('blank yes', 'woocommerce-econt') ?></td>
        <td><?php if ($next_parcel['blank_yes']) { ?>
          <a href="<?php echo $next_parcel['blank_yes']; ?>" target="_blank"><?php _e('View', 'woocommerce-econt') ?></a>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php _e('blank no', 'woocommerce-econt') ?></td>
        <td><?php if ($next_parcel['blank_no']) { ?>
          <a href="<?php echo $next_parcel['blank_no']; ?>" target="_blank"><?php _e('View', 'woocommerce-econt') ?></a>
          <?php } ?></td>
      </tr>
      <?php if ($next_parcel['pdf_url']) { ?>
      <tr>
        <td><?php _e('PDF URL', 'woocommerce-econt') ?></td>
        <td><a href="<?php echo $next_parcel['pdf_url']; ?>" target="_blank"><?php _e('View', 'woocommerce-econt') ?></a></td>
      </tr>
      <?php } ?>
    </table>
    <?php if ($next_parcel['trackings']) { ?>
    <b><?php _e('Tracking', 'woocommerce-econt') ?></b>
    <table class="list">
      <thead>
        <tr>
          <td class="left"><?php _e('Time', 'woocommerce-econt') ?></td>
          <td class="left"><?php _e('Is receipt', 'woocommerce-econt') ?></td>
          <td class="left"><?php _e('Event', 'woocommerce-econt') ?></td>
          <td class="left"><?php _e('Name', 'woocommerce-econt') ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($next_parcel['trackings'] as $tracking) { ?>
        <tr>
          <td class="left"><?php echo $tracking['time']; ?></td>
          <td class="left"><?php echo $tracking['is_receipt']; ?></td>
          <td class="left"><?php echo $tracking['event']; ?></td>
          <td class="left"><?php echo $tracking['name']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php } ?>
    <?php } ?>
    <?php } ?>
    </div>
  </div>
</div>

<!--end of tracking -->
<table>
<tr><td>
<?php if ($loading['is_imported'] == 0){ ?>
<button id='delete_loading' class='button' type='button' ><?php _e('Delete loading', 'woocommerce-econt') ?></button>
<?php } ?>
</td><td>
<button id='button_request_of_courier' class='button' type='' name='' value=''><?php _e('Request for courier', 'woocommerce-econt') ?></button>
</td></tr>
</table>

<?php } //end of $loading == true

 } ?>

<script type="text/javascript">

function displaySenderDoor() {
  if (jQuery('#sender_door_or_office').val() == 'DOOR2') {
    //jQuery('#inventory_type_loading').hide();
    jQuery('#sender_door').show();
  } else {
    //jQuery('#inventory_type_loading').hide();
    jQuery('#sender_door').hide();
  }
}

function checkPriorityTime() {
  if (jQuery('#priority_time:checked').length) {
    jQuery('#priority_time_type').removeAttr('disabled');
    jQuery('#priority_time_hour').removeAttr('disabled');
  } else {
    jQuery('#priority_time_type').attr('disabled', 'disabled');
    jQuery('#priority_time_hour').attr('disabled', 'disabled');
  }
}

function setPriorityTime() {
  var type = jQuery('#priority_time_type').val();
  var hour = jQuery('#priority_time_hour').val();

  var html = '<option value="10">10</option>';
  html += '<option value="11">11</option>';
  html += '<option value="12">12</option>';
  html += '<option value="13">13</option>';
  html += '<option value="14">14</option>';
  html += '<option value="15">15</option>';
  html += '<option value="16">16</option>';
  html += '<option value="17">17</option>';

  if (type == 'BEFORE') {
    jQuery('#priority_time_hour').html(html + '<option value="18">18</option>');
  } else if (type == 'IN') {
    jQuery('#priority_time_hour').html('<option value="9">9</option>' + html + '<option value="18">18</option>');
  } else if (type == 'AFTER') {
    jQuery('#priority_time_hour').html('<option value="9">9</option>' + html);
  }

  jQuery('#priority_time_hour').val(hour).attr('selected', 'selected');
}

function displayCityCourierType() {
  if (jQuery('#city_courier').val() == 1) {
    //jQuery('#inventory_type_loading').hide();
    jQuery('#econt_city_courier').show();
  } else if (jQuery('#city_courier').val() == 0) {
    //jQuery('#inventory_type_loading').show();
    jQuery('#econt_city_courier').hide();
    //jQuery('#inventory_type_digital').hide();
  } else {
    //jQuery('#inventory_type_loading').hide();
    jQuery('#econt_city_courier').hide();
    //jQuery('#inventory_type_digital').hide();
  }
}


function displayInventory() {
  if (jQuery('#partial_delivery').val() == 1) {
    //jQuery('#inventory_type_loading').hide();
    jQuery('#inventory').show();
  } else if (jQuery('#partial_delivery').val() == 0) {
    //jQuery('#inventory_type_loading').show();
    jQuery('#inventory').hide();
    jQuery('#inventory_type_digital').hide();
  } else {
    //jQuery('#inventory_type_loading').hide();
    jQuery('#inventory').hide();
    jQuery('#inventory_type_digital').hide();
  }
}


function displayInventoryType() {
  //alert(jQuery('select[name="inventory"]').val() );
  if (jQuery('select[name="inventory"]').val() == 'DIGITAL') {
    //alert('d');
    jQuery('#inventory_type_loading').hide();
    jQuery('#inventory_type_digital').show();
  } else if (jQuery('select[name="inventory"]').val() == 'LOADING') {
    jQuery('#inventory_type_loading').show();
    jQuery('#inventory_type_digital').hide();
  //alert('l');
  } else {
    //alert('e');
    jQuery('#inventory_type_loading').hide();
    jQuery('#inventory_type_digital').hide();
  }
}


var product_row = <?php echo $product_row; ?>;

function addProduct() {
  html  = '<tr id="product_' + product_row + '">';
  html += '  <td class="left"><input type="text" id="product_id_' + product_row + '" name="products[' + product_row + '][product_id]" value="" size="3" /></td>';
  html += '  <td class="left"><input type="text" id="product_name_' + product_row + '" name="products[' + product_row + '][name]" value="" size="50" /></td>';
  html += '  <td class="left"><input type="text" id="product_weight_' + product_row + '" name="products[' + product_row + '][weight]" value="" size="10" /></td>';
  html += '  <td class="left"><input type="text" id="product_price_' + product_row + '" name="products[' + product_row + '][price]" value="" size="10" /></td>';
  html += '  <td class="left"><a onclick="jQuery(\'#product_' + product_row + '\').remove();" class="button"><span><?php _e('remove', 'woocommerce-econt') ?></span></a></td>';
  html += '</tr>';

  jQuery('#products').append(html);

  product_row++;
}

//var client_cd_agreement = <?php echo ($wc_econt->client_cd_num == 0) ?  0 : 1 ; ?>;
var client_cd_agreement = <?php echo $wc_econt->client_cd_num; ?>;

</script>
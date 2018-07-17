jQuery(document).ready(function() {

	//date picker start and end date
	jQuery( "#start_date" ).datepicker();
	jQuery( "#end_date" ).datepicker();

	//font colour
	jQuery("#wa_wps_font_colour").spectrum({

		showAlpha:true,showInput:true,preferredFormat:"rgb",move:function(c){

		jQuery(this).val(c.toRgbString())

		}

	});

	//direction arrows colour
	jQuery("#wa_wps_control_colour").spectrum({

		showAlpha:true,showInput:true,preferredFormat:"rgb",move:function(c){

		jQuery(this).val(c.toRgbString())

		}

	});

	jQuery("#wa_wps_control_bg_colour").spectrum({

		showAlpha:true,showInput:true,preferredFormat:"rgb",move:function(c){

		jQuery(this).val(c.toRgbString())

		}

	});

	jQuery("#wa_wps_image_hover_colour").spectrum({

		showAlpha:true,showInput:true,preferredFormat:"rgb",move:function(c){

		jQuery(this).val(c.toRgbString())

		}

	});


	jQuery("#wa_wps_control_hover_colour").spectrum({

		showAlpha:true,showInput:true,preferredFormat:"rgb",move:function(c){

		jQuery(this).val(c.toRgbString())

		}

	});

});
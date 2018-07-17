jQuery(document).ready(function($) {

    tinymce.create('tinymce.plugins.wa_wps_plugin', {
        init : function(ed, url) {

                // register command for when button is clicked
                ed.addCommand('wa_wps_insert_shortcode', function() {

                var wa_wps_selected = false;
                var wa_wps_content = wa_wps_selected = tinyMCE.activeEditor.selection.getContent();
                var h2titleclass = prompt("Please, enter your slider ID", "");
                             
                    if(h2titleclass != '') {

                        if (h2titleclass.length != 0){
                        h2titleclass = ' id= "'+h2titleclass+'"';

                        wa_wps_content = '[wa-wps'+h2titleclass+']';

                        tinymce.execCommand('mceInsertContent', false, wa_wps_content);

                        }
                    }      
                });

            // register buttons - trigger above command when clicked
            ed.addButton('wa_wps_button', {title : 'Insert shortcode', cmd : 'wa_wps_insert_shortcode', image: url + '/b_img.png' });
        },   
    });
    tinymce.PluginManager.add('wa_wps_button', tinymce.plugins.wa_wps_plugin);
});
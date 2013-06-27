var imageWidget;

jQuery(document).ready(function(){

	/* Post Format */
	var checkpostformat = function() {
		var pfvalue = jQuery('#post-formats-select input[type=radio]:checked').val();
		if(pfvalue=='video'){
			jQuery('#video-settings').show();
			jQuery('#audio-settings').fadeOut();
			jQuery('#quote-settings').fadeOut();
			jQuery('#link-settings').fadeOut();
			jQuery('#gallery-settings').fadeOut();
		}else if(pfvalue=='audio'){
			jQuery('#audio-settings').show();
			jQuery('#video-settings').fadeOut();
			jQuery('#quote-settings').fadeOut();
			jQuery('#link-settings').fadeOut();
			jQuery('#gallery-settings').fadeOut();
		}else if(pfvalue=='quote'){
			jQuery('#quote-settings').show();
			jQuery('#audio-settings').fadeOut();
			jQuery('#video-settings').fadeOut();
			jQuery('#link-settings').fadeOut();
			jQuery('#gallery-settings').fadeOut();
		}else if(pfvalue=='link'){
			jQuery('#link-settings').show();
			jQuery('#quote-settings').fadeOut();
			jQuery('#audio-settings').fadeOut();
			jQuery('#video-settings').fadeOut();
			jQuery('#gallery-settings').fadeOut();
		}else if(pfvalue=='gallery'){
			jQuery('#gallery-settings').show();
			jQuery('#link-settings').fadeOut();
			jQuery('#quote-settings').fadeOut();
			jQuery('#audio-settings').fadeOut();
			jQuery('#video-settings').fadeOut();
		}else{
			jQuery('#link-settings').fadeOut();
			jQuery('#quote-settings').fadeOut();
			jQuery('#video-settings').fadeOut();
			jQuery('#audio-settings').fadeOut();
			jQuery('#gallery-settings').fadeOut();
		}
	}
	
	jQuery(document).ready(checkpostformat);
	jQuery('#post-formats-select input[type=radio]').click(checkpostformat);
	
	
	imageWidget = {

        sendToEditor : function(h) {
           jQuery( '#widget-'+self.cyon_ad_instance+'-ad_img_'+self.cyon_ad_instance_numb ).val(self.cyon_ad_url);
		   jQuery( '#widget-'+self.cyon_ad_instance+'-ad_name_'+self.cyon_ad_instance_numb ).val(self.cyon_ad_title);
           tb_remove();
		   jQuery( '#add_image-widget-'+self.cyon_ad_instance+'-ad_img_'+self.cyon_ad_instance_numb).html(jQuery( '#add_image-widget-'+self.cyon_ad_instance+'-ad_img_'+self.cyon_ad_instance_numb).html().replace(/Add Image/g, 'Change'));
        },
        imgHandler : function(event) {
            event.preventDefault();
            window.send_to_editor = imageWidget.sendToEditor;
            tb_show("Add an Image", event.target.href, false);
        },
        setActiveWidget : function(instance,numb) {
            self.cyon_ad_instance = instance;
            self.cyon_ad_instance_numb = numb;
        }

    };

	jQuery("a.thickbox-image-widget").live('click', imageWidget.imgHandler);

	jQuery('.cyon_contact_map_check').live('click', function(){
		if(jQuery(this).is(':checked')){
			jQuery(this).parents('form').find('fieldset').fadeIn();
		}else{
			jQuery(this).parents('form').find('fieldset').fadeOut();
		}
	});
});
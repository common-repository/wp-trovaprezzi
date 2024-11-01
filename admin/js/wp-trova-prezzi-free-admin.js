jQuery(document).ready( function(){
	jQuery('input').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '10%'
	});
	jQuery('.trovaprezzi_row_feed_tax').pwstabs({
		defaultTab: 1,
		containerWidth: '100%',
		tabsPosition: 'vertical',
		responsive: false,
		theme: 'pws_theme_dark_red'
	});

	jQuery('.trovaprezzi_row_feed_tax').width((jQuery('.pws_tabs_container').width() - jQuery('ul.pws_tabs_controll').width()) -100);

	jQuery('input[name="wp_trovaprezzi_general_settings"]').on('ifChecked', function(event){
		if(jQuery(this).val() == 'manual'){
			jQuery('#wp_trovaprezzi_option_page_select').fadeOut();
		}
		else if(jQuery(this).val() == 'automatic'){
			jQuery('#wp_trovaprezzi_option_page_select').fadeIn();
			jQuery('.trovaprezzi_row_feed_tax').width((jQuery('.pws_tabs_container').width() - jQuery('ul.pws_tabs_controll').width()) -100);
		}
	});

	jQuery('input[name="wp_trovaprezzi_category_tp_settings"]').on('ifChecked', function(event){
		jQuery('.wp_tax_esclude').fadeOut();
		jQuery('#wp_tax_esclude_'+jQuery(this).val()).fadeIn();
	});


	jQuery('input[name="wp_trovaprezzi_shipping_cost_settings"]').on('ifChecked', function(event){
		if(jQuery(this).val() == 'custom'){
		jQuery('ul.custom_shipping_cost_settings').addClass('visible');
	}
	else{
		jQuery('ul.custom_shipping_cost_settings').removeClass('visible');
	}
	});

	jQuery('input[name="wp_trovaprezzi_brand_settings[choice]"]').on('ifChecked', function(event){
		if(jQuery(this).val() == 'custom_post_meta'){
		jQuery('ul.custom_post_meta_value_settings').addClass('visible');
	}
	else{
		jQuery('ul.custom_post_meta_value_settings').removeClass('visible');
	}
	});

	jQuery('input[name="wp_trovaprezzi_code_settings[choice]"]').on('ifChecked', function(event){
		if(jQuery(this).val() == 'custom_post_meta'){
		jQuery('ul.code_custom_post_meta_value_settings').addClass('visible');
	}
	else{
		jQuery('ul.code_custom_post_meta_value_settings').removeClass('visible');
	}
});


	jQuery('input[name="wp_trovaprezzi_eancode_settings[choice]"]').on('ifChecked', function(event){
		if(jQuery(this).val() == 'custom_post_meta'){
		jQuery('ul.eancode_custom_post_meta_value_settings').addClass('visible');
	}
	else{
		jQuery('ul.eancode_custom_post_meta_value_settings').removeClass('visible');
	}
	});
	jQuery('input[name="wp_trovaprezzi_part_number_settings[choice]"]').on('ifChecked', function(event){
		if(jQuery(this).val() == 'custom_post_meta'){
		jQuery('ul.part_number_custom_post_meta_value_settings').addClass('visible');
	}
	else{
		jQuery('ul.part_number_custom_post_meta_value_settings').removeClass('visible');
	}
	});


	jQuery('#wp_trovaprezzi_products').tablesorter({theme: 'blue',widgets: ['zebra'] });



});

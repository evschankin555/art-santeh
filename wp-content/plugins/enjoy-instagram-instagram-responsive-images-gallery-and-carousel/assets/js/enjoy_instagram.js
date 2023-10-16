jQuery(document).on("click", ".input_hashtag_moderate", function () {
	jQuery('#dynamic_pos').append('<div class="pos_dynamic_div"><input type="text" class="class_hashtag_moderate" name="hashtag_moderate[]" value="" size="25" /><a href="#" class="elimina_dynamic_pos button"> - </a><input type="submit" class="button-primary" id="button_enjoyinstagram_advanced" name="button_enjoyinstagram_advanced" value="Save Hashtag"></div>');
	return false;
});


jQuery(document).on("click", ".elimina_dynamic_pos", function () {
	jQuery(this).parent().remove();
	jQuery('#button_enjoyinstagram_advanced').click();
	return false;
});

jQuery(document).ready(function () {
	jQuery('.users_moderator:checkbox').click(function () {
		jQuery('#button_enjoyinstagram_advanced').click();
	});
});


jQuery(document).on("click", "input[name*='first_select_enjoyinstagram_user_or_hashtag_widget']", function () {
	var test = jQuery(this).val();
	var id = jQuery(this).parent().attr('id');
	id = id.replace('widget-enjoyinstagram_widget_slider-', '');
	id = id.replace('-div_enjoyinstagram_user_or_hashtag_widget', '');

	if (test == 'user') {
		if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_carousel_moderate_widget option:selected").val() == 'true') {
			console.log(' user select type SI ALLA MODERAZIONE');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeOut();
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_moderate_widget").fadeIn();
		} else if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_carousel_moderate_widget option:selected").val() == 'false') {
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget select").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_moderate_widget").fadeOut();
		}
		jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_widget").fadeOut();
		jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeOut();
	} else if (test == 'hashtag') {
		if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_carousel_moderate_widget option:selected").val() == 'true') {
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_widget").fadeOut();
		} else if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_carousel_moderate_widget option:selected").val() == 'false') {
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeOut();
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_widget").fadeIn();
		}
		jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeOut();
		jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_moderate_widget").fadeOut();

	} else if (test == 'likes') {
		if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_carousel_moderate_widget option:selected").val() == 'true') {
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeOut();
		} else if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_carousel_moderate_widget option:selected").val() == 'false') {
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget select").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_moderate_widget").fadeOut();
		}
		jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_widget").fadeOut();
		jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeOut();
	}

});


jQuery(document).on("change", ".class_enjoyinstagram_carousel_moderate_widget", function () {
	var id = jQuery(this, 'option:selected').attr('id');
	id = id.replace('widget-enjoyinstagram_widget_slider-', '');
	id = id.replace('-enjoyinstagram_carousel_moderate_widget', '');

	if (jQuery(this, 'option:selected').val() == 'true') {
		if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_widget']:checked").val() == 'user') {
			console.log('user');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_moderate_widget").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeOut('slow');
		} else if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_widget']:checked").val() == 'hashtag') {
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_widget").fadeOut('slow');
		} else if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_widget']:checked").val() == 'likes') {

			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[value='user']").click();
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[value='likes']").attr('disabled', true);
		}
		jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[value='likes']").attr('disabled', true);
	} else if (jQuery(this, 'option:selected').val() == 'false') {
		if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_widget']:checked").val() == 'user') {
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget select").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_widget").fadeOut('slow');
		} else if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_widget']:checked").val() == 'hashtag') {
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_widget").fadeIn('slow');
		} else if (jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_widget']:checked").val() == 'likes') {
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[value='likes']").attr('disabled', false);
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_widget select").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_user_carousel_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_carousel_widget").fadeOut('slow');
		}
		jQuery("#widget-enjoyinstagram_widget_slider-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[value='likes']").attr('disabled', false);

	}
});


jQuery(document).on("click", "input[name*='first_select_enjoyinstagram_user_or_hashtag_grid_widget']", function () {
	var test = jQuery(this).val();
	var id = jQuery(this).parent().attr('id');
	id = id.replace('widget-enjoyinstagram_widget_grid-', '');
	id = id.replace('-div_enjoyinstagram_user_or_hashtag_widget', '');

	if (test == 'user') {
		if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_grid_moderate_widget option:selected").val() == 'true') {
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget").fadeOut();
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_moderate_widget").fadeIn();
		} else if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_grid_moderate_widget option:selected").val() == 'false') {
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget select").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_moderate_widget").fadeOut();

		}
		jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_widget").fadeOut();
		jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_moderate_widget").fadeOut();
	} else if (test == 'hashtag') {
		//jQuery('#enjoyinstagram_hashtag').attr('disabled',false);
		if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_grid_moderate_widget option:selected").val() == 'true') {
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_moderate_widget").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_widget").fadeOut();
		} else if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_grid_moderate_widget option:selected").val() == 'false') {
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_moderate_widget").fadeOut();
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_widget").fadeIn();
		}
		jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget").fadeOut();
		jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_moderate_widget").fadeOut();

	} else if (test == 'likes') {
		if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_grid_moderate_widget option:selected").val() == 'true') {
		} else if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_grid_moderate_widget option:selected").val() == 'false') {
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget select").fadeIn();
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_moderate_widget").fadeOut();

		}
		jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_widget").fadeOut();
		jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_moderate_widget").fadeOut();
	}

});


jQuery(document).on("change", ".class_enjoyinstagram_grid_moderate_widget", function () {
	var id = jQuery(this, 'option:selected').attr('id');
	id = id.replace('widget-enjoyinstagram_widget_grid-', '');
	id = id.replace('-enjoyinstagram_grid_moderate_widget', '');

	if (jQuery(this, 'option:selected').val() == 'true') {
		if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_grid_widget']:checked").val() == 'user') {
			console.log('user');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_moderate_widget").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget").fadeOut('slow');
		} else if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_grid_widget']:checked").val() == 'hashtag') {
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_moderate_widget").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_widget").fadeOut('slow');
		} else if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_grid_widget']:checked").val() == 'likes') {
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[value='user']").click();
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[value='likes']").attr('disabled', true);

		}
		jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[value='likes']").attr('disabled', true);
	} else if (jQuery(this, 'option:selected').val() == 'false') {
		if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_grid_widget']:checked").val() == 'user') {

			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget select").fadeIn('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_widget").fadeOut('slow');
		} else if (jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[name*='first_select_enjoyinstagram_user_or_hashtag_grid_widget']:checked").val() == 'hashtag') {
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_user_grid_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_moderate_widget").fadeOut('slow');
			jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-enjoyinstagram_user_or_hashtag_hashtag_grid_widget").fadeIn('slow');
		}
		jQuery("#widget-enjoyinstagram_widget_grid-" + id + "-div_enjoyinstagram_user_or_hashtag_widget input[value='likes']").attr('disabled', false);

	}
});


jQuery(document).on("click", ".moderate_reload", function () {
	var id = jQuery(this).attr('id');
	id = id.replace('moderate_reload_', '');
	jQuery('#article_reload_' + id).load(document.URL + " #article_reload_" + id, function () {

	});
	return false;
});


jQuery(document).on('keydown', '.class_hashtag_moderate', function () {
	jQuery('#button_enjoyinstagram_advanced').fadeIn();
});

// altezza immagine in pannello
/*
jQuery(document).ready( function(){
 jQuery('.enjoyinstagram_view').height(jQuery('.enjoyinstagram_view').css('width'));
});
*/

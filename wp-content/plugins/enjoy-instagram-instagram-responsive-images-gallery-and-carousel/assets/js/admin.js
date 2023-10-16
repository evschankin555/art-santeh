/**
 * EnjoyInstagram main admin Javascript file
 */
jQuery(document).ready(
	(($) => {
		const $appareanceSettings = $("#enjoyinstagram-appearance-settings");

		$(".enjoy-instagram-help").tooltip({
			content: function () {
				return $(this).prop("title");
			},
		});

		$(".ei-premium")
			.find("input, textarea, select")
			.attr("disabled", "disabled");
		$(".ei-premium")
			.append(
				'<a href="' +
				ei_admin.premium_url +
				'" title="' +
				ei_admin.premium_button_tooltip +
				'" class="ei-premium-button" target="_blank">' +
				ei_admin.premium_button_title +
				"</a>"
			)
			.tooltip();

		// DEPS HANDLER
		$appareanceSettings.on("change", "input, select", function () {
			const t = $(this),
				deps = $(document).find('[data-deps="' + t.attr("name") + '"]'),
				value = t.val();

			if (!deps.length || (t.attr("type") === "radio" && !t.is(":checked"))) {
				return;
			}

			$.each(deps, function () {
				const deps_value = String($(this).data("deps_value"));
				deps_value === value ? $(this).fadeIn("slow") : $(this).fadeOut("slow");
			});
		});

		$appareanceSettings.find("input,select").change();


		var form_shortcode = $(".shortcode-settings-form"),
			shortcode_preview = form_shortcode.find(".shortcode-preview"),
			shortcode_code = shortcode_preview.find(".code"),
			form_shortcode_content = form_shortcode.find(
				".main > .enjoy_tabs_content"
			),
			shortcode_attr = function (item, shortcode) {

				var value = item.value,
					reg = new RegExp(item.name + '="([^"]*)"', 'g');

				if (item.type === 'radio' && !$(item).is(':checked')) {
					return shortcode;
				}

				if (item.type === 'checkbox' && !$(item).is(':checked')) {
					value = $(item).hasClass('checkbox-multiple') ? '' : 'off';
				}

				if (shortcode.match(reg)) {
					if ($(item).hasClass('checkbox-multiple')) {
						var temp_value = shortcode.match(reg),
							temp_value = temp_value[0].replace(item.name + '="', '').replace('"', '').split(',').filter(function (value) {
								return value !== '';
							}),
							key = $.inArray(item.value, temp_value);

						if (key !== -1 && !$(item).is(':checked')) {
							temp_value.splice(key, 1);
						} else if (key === -1 && $(item).is(':checked')) {
							temp_value.push(item.value);
						}

						value = temp_value.join(',');
					}

					shortcode = shortcode.replace(reg, item.name + '="' + value + '"');
				} else {
					shortcode += ' ' + item.name + '="' + value + '"';
				}

				return shortcode;
			};

		$('.enjoy_tabs_content > div').each(function() {
			var $input_referer = $("input[name='_wp_http_referer']",this)
			$input_referer.val($input_referer.val() + '#shortcode_tab=' + $(this).attr('id'))
		})

		form_shortcode
			.find('input[name="enjoy_tab_checked"]')
			.on("change", function () {
				const val = $(this).val(),
					tab = form_shortcode_content.find("#" + val);

				if (!tab.length) {
					return;
				}

				window.location = '#shortcode_tab=' + val;

				// build shortcode
				let shortcode = "[enjoyinstagram_" + val;
				let $inputs = tab.find("input, select");

				if ($inputs.length === 0) {
					shortcode_preview.hide()
				} else {
					shortcode_preview.show();

					if (tab.find('.ei-premium').length === 0) {
						$inputs.each(function () {
							shortcode = shortcode_attr(this, shortcode);
						});
					}
				}

				shortcode += "]";

				shortcode_code.html(shortcode);
				tab.addClass("active").siblings().removeClass("active");
			})

		if(window.location.hash.indexOf('shortcode_tab') !== -1) {
			var active_tab = window.location.hash.replace('#shortcode_tab=', '')
			$('.enjoy_tabs input[value=\''+active_tab+'\']').prop('selected', 'selected').trigger('click').change()
		} else {
			$('.enjoy_tabs input').eq(0).trigger('click').change()
		}

		form_shortcode.on(
			"change",
			".enjoy_tabs_content > .active input, .enjoy_tabs_content > .active select",
			function () {
				var full_shortcode = $(this).closest('.display_content_tabs').find('.ei-premium').length === 0;
				shortcode_code.text(full_shortcode ? shortcode_attr(this, shortcode_code.text()) : shortcode_code.text());
			}
		);

		shortcode_preview.on("click", function () {
			const temp = $("<input>"),
				copyText = shortcode_code.text();

			$("body").append(temp);
			temp.val(copyText).select();
			document.execCommand("copy");
			temp.remove();
		});

		form_shortcode_content.find("[data-deps]").each(function () {
			const t = $(this),
				deps = t.attr("data-deps").split(","),
				values = t.attr("data-deps_values").split(","),
				conditions = [];

			$.each(deps, function (i, dep) {
				$('[name="' + dep + '"]')
					.on("change", function () {
						let value = this.value;
						let check_values = "";

						// exclude radio
						if (this.type === "radio" && !$(this).is(":checked")) {
							return;
						}

						if (this.type === "checkbox") {
							value = $(this).is(":checked") ? "on" : "off";
						}

						check_values = values[i].toString();
						check_values = check_values.split("|");
						conditions[i] = $.inArray(value, check_values) !== -1;

						if ($.inArray(false, conditions) === -1) {
							t.show();
						} else {
							t.hide();
						}
					})
					.change();
			});
		});
	})(jQuery)
);

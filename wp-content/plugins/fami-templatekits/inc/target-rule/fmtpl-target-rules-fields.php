<?php

class Fmtpl_Target_Rules_Fields {

	private static $instance;
	private static $meta_option;
	private static $current_page_type = null;
	private static $current_page_data = array();
	private static $user_selection;
	private static $location_selection;
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	public function __construct() {
		define( 'TARGET_RULE_URI', plugins_url( '/', __FILE__ ) );
		define( 'TARGET_RULE_VER', '1.0.0' );
		define( 'TARGET_RULE_DIR', plugin_dir_path( __FILE__ ) );

		add_action( 'admin_action_edit', array( $this, 'initialize_options' ) );
		add_action( 'wp_ajax_fmtpl_get_posts_by_query', array( $this, 'fmtpl_get_posts_by_query' ) );
	}
	public function initialize_options() {
		self::$user_selection     = self::get_user_selections();
		self::$location_selection = self::get_location_selections();
	}
	public static function get_location_selections() {
		$args = array(
			'public'   => true,
			'_builtin' => true,
		);

		$post_types = get_post_types( $args, 'objects' );
		unset( $post_types['attachment'] );

		$args['_builtin'] = false;
		$custom_post_type = get_post_types( $args, 'objects' );

		if (isset($post_types['fmtpl_blocks'])) {
		    unset( $post_types['fmtpl_blocks'] );
        }

		$post_types = array_merge( $post_types, $custom_post_type );

		$special_pages = array(
			'special-404'    => __( '404 Page', 'fami-templatekits' ),
			'special-search' => __( 'Search Page', 'fami-templatekits' ),
			'special-blog'   => __( 'Blog / Posts Page', 'fami-templatekits' ),
			'special-front'  => __( 'Front Page', 'fami-templatekits' ),
			'special-date'   => __( 'Date Archive', 'fami-templatekits' ),
			'special-author' => __( 'Author Archive', 'fami-templatekits' ),
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$special_pages['special-woo-shop'] = __( 'WooCommerce Shop Page', 'fami-templatekits' );
		}

		$selection_options = array(
			'basic'         => array(
				'label' => __( 'Basic', 'fami-templatekits' ),
				'value' => array(
					'basic-global'    => __( 'Entire Website', 'fami-templatekits' ),
					'basic-singulars' => __( 'All Singulars', 'fami-templatekits' ),
					'basic-archives'  => __( 'All Archives', 'fami-templatekits' ),
				),
			),

			'special-pages' => array(
				'label' => __( 'Special Pages', 'fami-templatekits' ),
				'value' => $special_pages,
			),
		);

		$args = array(
			'public' => true,
		);

		$taxonomies = get_taxonomies( $args, 'objects' );

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {

				// skip post format taxonomy.
				if ( 'post_format' == $taxonomy->name ) {
					continue;
				}

				foreach ( $post_types as $post_type ) {
					$post_opt = self::get_post_target_rule_options( $post_type, $taxonomy );

					if ( isset( $selection_options[ $post_opt['post_key'] ] ) ) {
						if ( ! empty( $post_opt['value'] ) && is_array( $post_opt['value'] ) ) {
							foreach ( $post_opt['value'] as $key => $value ) {
								if ( ! in_array( $value, $selection_options[ $post_opt['post_key'] ]['value'] ) ) {
									$selection_options[ $post_opt['post_key'] ]['value'][ $key ] = $value;
								}
							}
						}
					} else {
						$selection_options[ $post_opt['post_key'] ] = array(
							'label' => $post_opt['label'],
							'value' => $post_opt['value'],
						);
					}
				}
			}
		}

		$selection_options['specific-target'] = array(
			'label' => __( 'Specific Target', 'fami-templatekits' ),
			'value' => array(
				'specifics' => __( 'Specific Pages / Posts / Taxanomies, etc.', 'fami-templatekits' ),
			),
		);
		return $selection_options;
	}

	public static function get_user_selections() {
		$selection_options = array(
			'basic'    => array(
				'label' => __( 'Basic', 'fami-templatekits' ),
				'value' => array(
					'all'        => __( 'All', 'fami-templatekits' ),
					'logged-in'  => __( 'Logged In', 'fami-templatekits' ),
					'logged-out' => __( 'Logged Out', 'fami-templatekits' ),
				),
			),

			'advanced' => array(
				'label' => __( 'Advanced', 'fami-templatekits' ),
				'value' => array(),
			),
		);

		/* User roles */
		$roles = get_editable_roles();

		foreach ( $roles as $slug => $data ) {
			$selection_options['advanced']['value'][ $slug ] = $data['name'];
		}

		return $selection_options;
	}

	public static function get_location_by_key( $key ) {
		if ( ! isset( self::$location_selection ) || empty( self::$location_selection ) ) {
			self::$location_selection = self::get_location_selections();
		}
		$location_selection = self::$location_selection;

		foreach ( $location_selection as $location_grp ) {
			if ( isset( $location_grp['value'][ $key ] ) ) {
				return $location_grp['value'][ $key ];
			}
		}

		if ( strpos( $key, 'post-' ) !== false ) {
			$post_id = (int) str_replace( 'post-', '', $key );
			return get_the_title( $post_id );
		}

		// taxonomy options.
		if ( strpos( $key, 'tax-' ) !== false ) {
			$tax_id = (int) str_replace( 'tax-', '', $key );
			$term   = get_term( $tax_id );

			if ( ! is_wp_error( $term ) ) {
				$term_taxonomy = ucfirst( str_replace( '_', ' ', $term->taxonomy ) );
				return $term->name . ' - ' . $term_taxonomy;
			} else {
				return '';
			}
		}

		return $key;
	}

	public static function get_user_by_key( $key ) {
		if ( ! isset( self::$user_selection ) || empty( self::$user_selection ) ) {
			self::$user_selection = self::get_user_selections();
		}
		$user_selection = self::$user_selection;

		if ( isset( $user_selection['basic']['value'][ $key ] ) ) {
			return $user_selection['basic']['value'][ $key ];
		} elseif ( $user_selection['advanced']['value'][ $key ] ) {
			return $user_selection['advanced']['value'][ $key ];
		}
		return $key;
	}

	function fmtpl_get_posts_by_query() {
		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : '';
		$data          = array();
		$result        = array();

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$output     = 'names'; // names or objects, note names is the default.
		$operator   = 'and'; // also supports 'or'.
		$post_types = get_post_types( $args, $output, $operator );

		$post_types['Posts'] = 'post';
		$post_types['Pages'] = 'page';

		foreach ( $post_types as $key => $post_type ) {
			$data = array();

			add_filter( 'posts_search', array( $this, 'search_only_titles' ), 10, 2 );

			$query = new \WP_Query(
				array(
					's'              => $search_string,
					'post_type'      => $post_type,
					'posts_per_page' => - 1,
				)
			);

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$title  = get_the_title();
					$title .= ( 0 != $query->post->post_parent ) ? ' (' . get_the_title( $query->post->post_parent ) . ')' : '';
					$id     = get_the_id();
					$data[] = array(
						'id'   => 'post-' . $id,
						'text' => $title,
					);
				}
			}

			if ( is_array( $data ) && ! empty( $data ) ) {
				$result[] = array(
					'text'     => $key,
					'children' => $data,
				);
			}
			wp_reset_query();
		}

		$data = array();

		//wp_reset_postdata();

		$args = array(
			'public' => true,
		);

		$output     = 'objects'; // names or objects, note names is the default.
		$operator   = 'and'; // also supports 'or'.
		$taxonomies = get_taxonomies( $args, $output, $operator );

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms(
				$taxonomy->name,
				array(
					'orderby'    => 'count',
					'hide_empty' => 0,
					'name__like' => $search_string,
				)
			);

			$data = array();

			$label = ucwords( $taxonomy->label );

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$term_taxonomy_name = ucfirst( str_replace( '_', ' ', $taxonomy->name ) );

					$data[] = array(
						'id'   => 'tax-' . $term->term_id,
						'text' => $term->name . ' archive page',
					);

					$data[] = array(
						'id'   => 'tax-' . $term->term_id . '-single-' . $taxonomy->name,
						'text' => 'All singulars from ' . $term->name,
					);
				}
			}

			if ( is_array( $data ) && ! empty( $data ) ) {
				$result[] = array(
					'text'     => $label,
					'children' => $data,
				);
			}
		}

		// return the result in json.
		wp_send_json( $result );
	}

	function search_only_titles( $search, $wp_query ) {
		if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
			global $wpdb;

			$q = $wp_query->query_vars;
			$n = ! empty( $q['exact'] ) ? '' : '%';

			$search = array();

			foreach ( (array) $q['search_terms'] as $term ) {
				$search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
			}

			if ( ! is_user_logged_in() ) {
				$search[] = "$wpdb->posts.post_password = ''";
			}

			$search = ' AND ' . implode( ' AND ', $search );
		}

		return $search;
	}

	public function admin_styles() {
		wp_enqueue_script( 'fmtpl-select2', TARGET_RULE_URI . 'select2.js', array( 'jquery' ), TARGET_RULE_VER, true );

		$wp_lang  = get_locale();
		$fmtpl_lang = '';
		if ( '' !== $wp_lang ) {
			$select2_lang = array(
				''               => 'en',
				'hi_IN'          => 'hi',
				'mr'             => 'mr',
				'af'             => 'af',
				'ar'             => 'ar',
				'ary'            => 'ar',
				'as'             => 'as',
				'azb'            => 'az',
				'az'             => 'az',
				'bel'            => 'be',
				'bg_BG'          => 'bg',
				'bn_BD'          => 'bn',
				'bo'             => 'bo',
				'bs_BA'          => 'bs',
				'ca'             => 'ca',
				'ceb'            => 'ceb',
				'cs_CZ'          => 'cs',
				'cy'             => 'cy',
				'da_DK'          => 'da',
				'de_CH'          => 'de',
				'de_DE'          => 'de',
				'de_DE_formal'   => 'de',
				'de_CH_informal' => 'de',
				'dzo'            => 'dz',
				'el'             => 'el',
				'en_CA'          => 'en',
				'en_GB'          => 'en',
				'en_AU'          => 'en',
				'en_NZ'          => 'en',
				'en_ZA'          => 'en',
				'eo'             => 'eo',
				'es_MX'          => 'es',
				'es_VE'          => 'es',
				'es_CR'          => 'es',
				'es_CO'          => 'es',
				'es_GT'          => 'es',
				'es_ES'          => 'es',
				'es_CL'          => 'es',
				'es_PE'          => 'es',
				'es_AR'          => 'es',
				'et'             => 'et',
				'eu'             => 'eu',
				'fa_IR'          => 'fa',
				'fi'             => 'fi',
				'fr_BE'          => 'fr',
				'fr_FR'          => 'fr',
				'fr_CA'          => 'fr',
				'gd'             => 'gd',
				'gl_ES'          => 'gl',
				'gu'             => 'gu',
				'haz'            => 'haz',
				'he_IL'          => 'he',
				'hr'             => 'hr',
				'hu_HU'          => 'hu',
				'hy'             => 'hy',
				'id_ID'          => 'id',
				'is_IS'          => 'is',
				'it_IT'          => 'it',
				'ja'             => 'ja',
				'jv_ID'          => 'jv',
				'ka_GE'          => 'ka',
				'kab'            => 'kab',
				'km'             => 'km',
				'ko_KR'          => 'ko',
				'ckb'            => 'ku',
				'lo'             => 'lo',
				'lt_LT'          => 'lt',
				'lv'             => 'lv',
				'mk_MK'          => 'mk',
				'ml_IN'          => 'ml',
				'mn'             => 'mn',
				'ms_MY'          => 'ms',
				'my_MM'          => 'my',
				'nb_NO'          => 'nb',
				'ne_NP'          => 'ne',
				'nl_NL'          => 'nl',
				'nl_NL_formal'   => 'nl',
				'nl_BE'          => 'nl',
				'nn_NO'          => 'nn',
				'oci'            => 'oc',
				'pa_IN'          => 'pa',
				'pl_PL'          => 'pl',
				'ps'             => 'ps',
				'pt_BR'          => 'pt',
				'pt_PT_ao90'     => 'pt',
				'pt_PT'          => 'pt',
				'rhg'            => 'rhg',
				'ro_RO'          => 'ro',
				'ru_RU'          => 'ru',
				'sah'            => 'sah',
				'si_LK'          => 'si',
				'sk_SK'          => 'sk',
				'sl_SI'          => 'sl',
				'sq'             => 'sq',
				'sr_RS'          => 'sr',
				'sv_SE'          => 'sv',
				'szl'            => 'szl',
				'ta_IN'          => 'ta',
				'te'             => 'te',
				'th'             => 'th',
				'tl'             => 'tl',
				'tr_TR'          => 'tr',
				'tt_RU'          => 'tt',
				'tah'            => 'ty',
				'ug_CN'          => 'ug',
				'uk'             => 'uk',
				'ur'             => 'ur',
				'uz_UZ'          => 'uz',
				'vi'             => 'vi',
				'zh_CN'          => 'zh',
				'zh_TW'          => 'zh',
				'zh_HK'          => 'zh',
			);

			if ( isset( $select2_lang[ $wp_lang ] ) && file_exists( TARGET_RULE_DIR . 'i18n/' . $select2_lang[ $wp_lang ] . '.js' ) ) {
				$fmtpl_lang = $select2_lang[ $wp_lang ];
				wp_enqueue_script(
					'fmtpl-select2-lang',
					TARGET_RULE_URI . 'i18n/' . $select2_lang[ $wp_lang ] . '.js',
					array(
						'jquery',
						'fmtpl-select2',
					),
					TARGET_RULE_VER,
					true
				);
			}
		}

		wp_register_script(
			'fmtpl-target-rule',
			TARGET_RULE_URI . 'target-rule.js',
			array(
				'jquery',
				'fmtpl-select2',
			),
			TARGET_RULE_VER,
			true
		);

		wp_enqueue_script( 'fmtpl-target-rule' );

		wp_register_script(
			'fmtpl-user-role',
			TARGET_RULE_URI . 'user-role.js',
			array(
				'jquery',
			),
			TARGET_RULE_VER,
			true
		);

		wp_enqueue_script( 'fmtpl-user-role' );

		wp_register_style( 'fmtpl-select2', TARGET_RULE_URI . 'select2.css', '', TARGET_RULE_VER );
		wp_enqueue_style( 'fmtpl-select2' );
		wp_register_style( 'fmtpl-target-rule', TARGET_RULE_URI . 'target-rule.css', '', TARGET_RULE_VER );
		wp_enqueue_style( 'fmtpl-target-rule' );

		/**
		 * Registered localize vars
		 */
		$localize_vars = array(
			'fmtpl_lang'      => $fmtpl_lang,
			'please_enter'  => __( 'Please enter', 'fami-templatekits' ),
			'please_delete' => __( 'Please delete', 'fami-templatekits' ),
			'more_char'     => __( 'or more characters', 'fami-templatekits' ),
			'character'     => __( 'character', 'fami-templatekits' ),
			'loading'       => __( 'Loading more results…', 'fami-templatekits' ),
			'only_select'   => __( 'You can only select', 'fami-templatekits' ),
			'item'          => __( 'item', 'fami-templatekits' ),
			'char_s'        => __( 's', 'fami-templatekits' ),
			'no_result'     => __( 'No results found', 'fami-templatekits' ),
			'searching'     => __( 'Searching…', 'fami-templatekits' ),
			'not_loader'    => __( 'The results could not be loaded.', 'fami-templatekits' ),
			'search'        => __( 'Search pages / post / categories', 'fami-templatekits' ),
		);
		wp_localize_script( 'fmtpl-select2', 'Fmtpl_Rules', $localize_vars );
	}

	public static function target_rule_settings_field( $name, $settings, $value ) {
		$input_name     = $name;
		$type           = isset( $settings['type'] ) ? $settings['type'] : 'target_rule';
		$class          = isset( $settings['class'] ) ? $settings['class'] : '';
		$rule_type      = isset( $settings['rule_type'] ) ? $settings['rule_type'] : 'target_rule';
		$add_rule_label = isset( $settings['add_rule_label'] ) ? $settings['add_rule_label'] : __( 'Add Rule', 'fami-templatekits' );
		$saved_values   = $value;
		$output         = '';

		if ( isset( self::$location_selection ) || empty( self::$location_selection ) ) {
			self::$location_selection = self::get_location_selections();
		}
		$selection_options = self::$location_selection;

		/* WP Template Format */
		$output .= '<script type="text/html" id="tmpl-fmtpl-target-rule-' . $rule_type . '-condition">';
		$output .= '<div class="fmtpl-target-rule-condition fmtpl-target-rule-{{data.id}}" data-rule="{{data.id}}" >';
		$output .= '<span class="target_rule-condition-delete dashicons dashicons-dismiss"></span>';
		/* Condition Selection */
		$output .= '<div class="target_rule-condition-wrap" >';
		$output .= '<select name="' . esc_attr( $input_name ) . '[rule][{{data.id}}]" class="target_rule-condition form-control fmtpl-input">';
		$output .= '<option value="">' . __( 'Select', 'fami-templatekits' ) . '</option>';

		foreach ( $selection_options as $group => $group_data ) {
			$output .= '<optgroup label="' . $group_data['label'] . '">';
			foreach ( $group_data['value'] as $opt_key => $opt_value ) {
				$output .= '<option value="' . $opt_key . '">' . $opt_value . '</option>';
			}
			$output .= '</optgroup>';
		}
		$output .= '</select>';
		$output .= '</div>';

		$output .= '</div> <!-- fmtpl-target-rule-condition -->';

		/* Specific page selection */
		$output .= '<div class="target_rule-specific-page-wrap" style="display:none">';
		$output .= '<select name="' . esc_attr( $input_name ) . '[specific][]" class="target-rule-select2 target_rule-specific-page form-control fmtpl-input " multiple="multiple">';
		$output .= '</select>';
		$output .= '</div>';

		$output .= '</script>';

		/* Wrapper Start */
		$output .= '<div class="fmtpl-target-rule-wrapper fmtpl-target-rule-' . $rule_type . '-on-wrap" data-type="' . $rule_type . '">';
		// $output .= '<input type="hidden" class="form-control fmtpl-input fmtpl-target_rule-input" name="' . esc_attr( $input_name ) . '" value=' . $value . ' />';
		$output .= '<div class="fmtpl-target-rule-selector-wrapper fmtpl-target-rule-' . $rule_type . '-on">';
		$output .= self::generate_target_rule_selector( $rule_type, $selection_options, $input_name, $saved_values, $add_rule_label );
		$output .= '</div>';

		/* Wrapper end */
		$output .= '</div>';

		echo $output;
	}

	public static function get_post_target_rule_options( $post_type, $taxonomy ) {
		$post_key    = str_replace( ' ', '-', strtolower( $post_type->label ) );
		$post_label  = ucwords( $post_type->label );
		$post_name   = $post_type->name;
		$post_option = array();

		/* translators: %s post label */
		$all_posts                          = sprintf( __( 'All %s', 'fami-templatekits' ), $post_label );
		$post_option[ $post_name . '|all' ] = $all_posts;

		if ( 'pages' != $post_key ) {
			/* translators: %s post label */
			$all_archive                                = sprintf( __( 'All %s Archive', 'fami-templatekits' ), $post_label );
			$post_option[ $post_name . '|all|archive' ] = $all_archive;
		}

		if ( in_array( $post_type->name, $taxonomy->object_type ) ) {
			$tax_label = ucwords( $taxonomy->label );
			$tax_name  = $taxonomy->name;

			/* translators: %s taxonomy label */
			$tax_archive = sprintf( __( 'All %s Archive', 'fami-templatekits' ), $tax_label );

			$post_option[ $post_name . '|all|taxarchive|' . $tax_name ] = $tax_archive;
		}

		$post_output['post_key'] = $post_key;
		$post_output['label']    = $post_label;
		$post_output['value']    = $post_option;

		return $post_output;
	}

	public static function generate_target_rule_selector( $type, $selection_options, $input_name, $saved_values, $add_rule_label ) {
		$output = '<div class="target_rule-builder-wrap">';

		if ( ! is_array( $saved_values ) || ( is_array( $saved_values ) && empty( $saved_values ) ) ) {
			$saved_values                = array();
			$saved_values['rule'][0]     = '';
			$saved_values['specific'][0] = '';
		}

		$index = 0;

		foreach ( $saved_values['rule'] as $index => $data ) {
			$output .= '<div class="fmtpl-target-rule-condition fmtpl-target-rule-' . $index . '" data-rule="' . $index . '" >';
			/* Condition Selection */
			$output .= '<span class="target_rule-condition-delete dashicons dashicons-dismiss"></span>';
			$output .= '<div class="target_rule-condition-wrap" >';
			$output .= '<select name="' . esc_attr( $input_name ) . '[rule][' . $index . ']" class="target_rule-condition form-control fmtpl-input">';
			$output .= '<option value="">' . __( 'Select', 'fami-templatekits' ) . '</option>';

			foreach ( $selection_options as $group => $group_data ) {
				$output .= '<optgroup label="' . $group_data['label'] . '">';
				foreach ( $group_data['value'] as $opt_key => $opt_value ) {

					// specific rules.
					$selected = '';

					if ( $data == $opt_key ) {
						$selected = 'selected="selected"';
					}

					$output .= '<option value="' . $opt_key . '" ' . $selected . '>' . $opt_value . '</option>';
				}
				$output .= '</optgroup>';
			}
			$output .= '</select>';
			$output .= '</div>';

			$output .= '</div>';

			/* Specific page selection */
			$output .= '<div class="target_rule-specific-page-wrap" style="display:none">';
			$output .= '<select name="' . esc_attr( $input_name ) . '[specific][]" class="target-rule-select2 target_rule-specific-page form-control fmtpl-input " multiple="multiple">';

			if ( 'specifics' == $data && isset( $saved_values['specific'] ) && null != $saved_values['specific'] && is_array( $saved_values['specific'] ) ) {
				foreach ( $saved_values['specific'] as $data_key => $sel_value ) {
					// posts.
					if ( strpos( $sel_value, 'post-' ) !== false ) {
						$post_id    = (int) str_replace( 'post-', '', $sel_value );
						$post_title = get_the_title( $post_id );
						$output    .= '<option value="post-' . $post_id . '" selected="selected" >' . $post_title . '</option>';
					}

					// taxonomy options.
					if ( strpos( $sel_value, 'tax-' ) !== false ) {
						$tax_data = explode( '-', $sel_value );

						$tax_id    = (int) str_replace( 'tax-', '', $sel_value );
						$term      = get_term( $tax_id );
						$term_name = '';

						if ( ! is_wp_error( $term ) ) {
							$term_taxonomy = ucfirst( str_replace( '_', ' ', $term->taxonomy ) );

							if ( isset( $tax_data[2] ) && 'single' === $tax_data[2] ) {
								$term_name = 'All singulars from ' . $term->name;
							} else {
								$term_name = $term->name . ' - ' . $term_taxonomy;
							}
						}

						$output .= '<option value="' . $sel_value . '" selected="selected" >' . $term_name . '</option>';
					}
				}
			}
			$output .= '</select>';
			$output .= '</div>';
		}

		$output .= '</div>';

		/* Add new rule */
		$output .= '<div class="target_rule-add-rule-wrap">';
		$output .= '<a href="#" class="button" data-rule-id="' . absint( $index ) . '" data-rule-type="' . $type . '">' . $add_rule_label . '</a>';
		$output .= '</div>';

		if ( 'display' == $type ) {
			/* Add new rule */
			$output .= '<div class="target_rule-add-exclusion-rule">';
			$output .= '<a href="#" class="button">' . __( 'Add Exclusion Rule', 'fami-templatekits' ) . '</a>';
			$output .= '</div>';
		}

		return $output;
	}

	public function get_current_layout( $layout_id, $option ) {
		$post_id        = ( ! is_404() && ! is_search() && ! is_archive() && ! is_home() ) ? get_the_id() : false;
		$current_layout = false;
		$is_exclude     = false;
		$is_user_role   = false;
		$display_on     = get_post_meta( $layout_id, $option . '-location', true );
		$exclude_on     = get_post_meta( $layout_id, $option . '-exclusion', true );
		$user_roles     = get_post_meta( $layout_id, $option . '-users', true );

		/* Parse Display On Condition */
		$is_display = $this->parse_layout_display_condition( $post_id, $display_on );

		if ( true == $is_display ) {
			/* Parse Exclude On Condition */
			$is_exclude = $this->parse_layout_display_condition( $post_id, $exclude_on );
			/* Parse User Role Condition */
			$is_user_role = $this->parse_user_role_condition( $post_id, $user_roles );
		}

		if ( $is_display && ! $is_exclude && $is_user_role ) {
			$current_layout = $layout_id;
		}

		// filter target page settings.
		$current_layout = apply_filters( 'fmtpl_target_page_settings', $current_layout, $layout_id );

		return $current_layout;
	}

	public function parse_layout_display_condition( $post_id, $rules ) {
		$display           = false;
		$current_post_type = get_post_type( $post_id );

		if ( isset( $rules['rule'] ) && is_array( $rules['rule'] ) && ! empty( $rules['rule'] ) ) {
			foreach ( $rules['rule'] as $key => $rule ) {
				if ( strrpos( $rule, 'all' ) !== false ) {
					$rule_case = 'all';
				} else {
					$rule_case = $rule;
				}

				switch ( $rule_case ) {
					case 'basic-global':
						$display = true;
						break;

					case 'basic-singulars':
						if ( is_singular() ) {
							$display = true;
						}
						break;

					case 'basic-archives':
						if ( is_archive() ) {
							$display = true;
						}
						break;

					case 'special-404':
						if ( is_404() ) {
							$display = true;
						}
						break;

					case 'special-search':
						if ( is_search() ) {
							$display = true;
						}
						break;

					case 'special-blog':
						if ( is_home() ) {
							$display = true;
						}
						break;

					case 'special-front':
						if ( is_front_page() ) {
							$display = true;
						}
						break;

					case 'special-date':
						if ( is_date() ) {
							$display = true;
						}
						break;

					case 'special-author':
						if ( is_author() ) {
							$display = true;
						}
						break;

					case 'special-woo-shop':
						if ( function_exists( 'is_shop' ) && is_shop() ) {
							$display = true;
						}
						break;

					case 'all':
						$rule_data = explode( '|', $rule );

						$post_type     = isset( $rule_data[0] ) ? $rule_data[0] : false;
						$archieve_type = isset( $rule_data[2] ) ? $rule_data[2] : false;
						$taxonomy      = isset( $rule_data[3] ) ? $rule_data[3] : false;
						if ( false === $archieve_type ) {
							$current_post_type = get_post_type( $post_id );

							if ( false !== $post_id && $current_post_type == $post_type ) {
								$display = true;
							}
						} else {
							if ( is_archive() ) {
								$current_post_type = get_post_type();
								if ( $current_post_type == $post_type ) {
									if ( 'archive' == $archieve_type ) {
										$display = true;
									} elseif ( 'taxarchive' == $archieve_type ) {
										$obj              = get_queried_object();
										$current_taxonomy = '';
										if ( '' !== $obj && null !== $obj ) {
											$current_taxonomy = $obj->taxonomy;
										}

										if ( $current_taxonomy == $taxonomy ) {
											$display = true;
										}
									}
								}
							}
						}
						break;

					case 'specifics':
						if ( isset( $rules['specific'] ) && is_array( $rules['specific'] ) ) {
							foreach ( $rules['specific'] as $specific_page ) {
								$specific_data = explode( '-', $specific_page );

								$specific_post_type = isset( $specific_data[0] ) ? $specific_data[0] : false;
								$specific_post_id   = isset( $specific_data[1] ) ? $specific_data[1] : false;
								if ( 'post' == $specific_post_type ) {
									if ( $specific_post_id == $post_id ) {
										$display = true;
									}
								} elseif ( isset( $specific_data[2] ) && ( 'single' == $specific_data[2] ) && 'tax' == $specific_post_type ) {
									if ( is_singular() ) {
										$term_details = get_term( $specific_post_id );

										if ( isset( $term_details->taxonomy ) ) {
											$has_term = has_term( (int) $specific_post_id, $term_details->taxonomy, $post_id );

											if ( $has_term ) {
												$display = true;
											}
										}
									}
								} elseif ( 'tax' == $specific_post_type ) {
									$tax_id = get_queried_object_id();
									if ( $specific_post_id == $tax_id ) {
										$display = true;
									}
								}
							}
						}
						break;

					default:
						break;
				}

				if ( $display ) {
					break;
				}
			}
		}

		return $display;
	}

	public static function target_user_role_settings_field( $name, $settings, $value ) {
		$input_name     = $name;
		$type           = isset( $settings['type'] ) ? $settings['type'] : 'target_rule';
		$class          = isset( $settings['class'] ) ? $settings['class'] : '';
		$rule_type      = isset( $settings['rule_type'] ) ? $settings['rule_type'] : 'target_rule';
		$add_rule_label = isset( $settings['add_rule_label'] ) ? $settings['add_rule_label'] : __( 'Add Rule', 'fami-templatekits' );
		$saved_values   = $value;
		$output         = '';

		if ( ! isset( self::$user_selection ) || empty( self::$user_selection ) ) {
			self::$user_selection = self::get_user_selections();
		}
		$selection_options = self::$user_selection;

		/* WP Template Format */
		$output         .= '<script type="text/html" id="tmpl-fmtpl-user-role-condition">';
			$output     .= '<div class="fmtpl-user-role-condition fmtpl-user-role-{{data.id}}" data-rule="{{data.id}}" >';
				$output .= '<span class="user_role-condition-delete dashicons dashicons-dismiss"></span>';
				/* Condition Selection */
				$output     .= '<div class="user_role-condition-wrap" >';
					$output .= '<select name="' . esc_attr( $input_name ) . '[{{data.id}}]" class="user_role-condition form-control fmtpl-input">';
					$output .= '<option value="">' . __( 'Select', 'fami-templatekits' ) . '</option>';

		foreach ( $selection_options as $group => $group_data ) {
			$output .= '<optgroup label="' . $group_data['label'] . '">';
			foreach ( $group_data['value'] as $opt_key => $opt_value ) {
				$output .= '<option value="' . $opt_key . '">' . $opt_value . '</option>';
			}
			$output .= '</optgroup>';
		}
					$output .= '</select>';
				$output     .= '</div>';
			$output         .= '</div> <!-- fmtpl-user-role-condition -->';
		$output             .= '</script>';

		if ( ! is_array( $saved_values ) || ( is_array( $saved_values ) && empty( $saved_values ) ) ) {
			$saved_values    = array();
			$saved_values[0] = '';
		}

		$index = 0;

		$output         .= '<div class="fmtpl-user-role-wrapper fmtpl-user-role-display-on-wrap" data-type="display">';
			$output     .= '<div class="fmtpl-user-role-selector-wrapper fmtpl-user-role-display-on">';
				$output .= '<div class="user_role-builder-wrap">';
		foreach ( $saved_values as $index => $data ) {
			$output     .= '<div class="fmtpl-user-role-condition fmtpl-user-role-' . $index . '" data-rule="' . $index . '" >';
				$output .= '<span class="user_role-condition-delete dashicons dashicons-dismiss"></span>';
				/* Condition Selection */
				$output     .= '<div class="user_role-condition-wrap" >';
					$output .= '<select name="' . esc_attr( $input_name ) . '[' . $index . ']" class="user_role-condition form-control fmtpl-input">';
					$output .= '<option value="">' . __( 'Select', 'fami-templatekits' ) . '</option>';

			foreach ( $selection_options as $group => $group_data ) {
				$output .= '<optgroup label="' . $group_data['label'] . '">';
				foreach ( $group_data['value'] as $opt_key => $opt_value ) {
					$output .= '<option value="' . $opt_key . '" ' . selected( $data, $opt_key, false ) . '>' . $opt_value . '</option>';
				}
				$output .= '</optgroup>';
			}
					$output .= '</select>';
				$output     .= '</div>';
					$output .= '</div> <!-- fmtpl-user-role-condition -->';
		}
				$output .= '</div>';
				/* Add new rule */
				$output .= '<div class="user_role-add-rule-wrap">';
				$output .= '<a href="#" class="button" data-rule-id="' . absint( $index ) . '">' . $add_rule_label . '</a>';
				$output .= '</div>';
			$output     .= '</div>';
		$output         .= '</div>';

		echo $output;
	}

	public function parse_user_role_condition( $post_id, $rules ) {
		$show_popup = true;

		if ( is_array( $rules ) && ! empty( $rules ) ) {
			$show_popup = false;

			foreach ( $rules as $i => $rule ) {
				switch ( $rule ) {
					case '':
					case 'all':
						$show_popup = true;
						break;

					case 'logged-in':
						if ( is_user_logged_in() ) {
							$show_popup = true;
						}
						break;

					case 'logged-out':
						if ( ! is_user_logged_in() ) {
							$show_popup = true;
						}
						break;

					default:
						if ( is_user_logged_in() ) {
							$current_user = wp_get_current_user();

							if ( isset( $current_user->roles )
									&& is_array( $current_user->roles )
									&& in_array( $rule, $current_user->roles )
								) {
								$show_popup = true;
							}
						}
						break;
				}

				if ( $show_popup ) {
					break;
				}
			}
		}

		return $show_popup;
	}

	public function get_current_page_type() {
		if ( null === self::$current_page_type ) {
			$page_type  = '';
			$current_id = false;

			if ( is_404() ) {
				$page_type = 'is_404';
			} elseif ( is_search() ) {
				$page_type = 'is_search';
			} elseif ( is_archive() ) {
				$page_type = 'is_archive';
				if ( is_category() || is_tag() || is_tax() ) {
					$page_type = 'is_tax';
				} elseif ( is_date() ) {
					$page_type = 'is_date';
				} elseif ( is_author() ) {
					$page_type = 'is_author';
				} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
					$page_type = 'is_woo_shop_page';
				}
			} elseif ( is_home() ) {
				$page_type = 'is_home';
			} elseif ( is_front_page() ) {
				$page_type  = 'is_front_page';
				$current_id = get_the_id();
			} elseif ( is_singular() ) {
				$page_type  = 'is_singular';
				$current_id = get_the_id();
			} else {
				$current_id = get_the_id();
			}

			self::$current_page_data['ID'] = $current_id;
			self::$current_page_type       = $page_type;
		}

		return self::$current_page_type;
	}

	public function get_posts_by_conditions( $post_type, $option ) {
		global $wpdb;
		global $post;

		$post_type = $post_type ? esc_sql( $post_type ) : esc_sql( $post->post_type );

		if ( is_array( self::$current_page_data ) && isset( self::$current_page_data[ $post_type ] ) ) {
			return apply_filters( 'fmtpl_get_display_posts_by_conditions', self::$current_page_data[ $post_type ], $post_type );
		}

		$current_page_type = $this->get_current_page_type();

		self::$current_page_data[ $post_type ] = array();

		$option['current_post_id'] = self::$current_page_data['ID'];
		$meta_header               = self::get_meta_option_post( $post_type, $option );

		/* Meta option is enabled */
		if ( false === $meta_header ) {
			$current_post_type = esc_sql( get_post_type() );
			$current_post_id   = false;
			$q_obj             = get_queried_object();

			$location = isset( $option['location'] ) ? esc_sql( $option['location'] ) : '';

			$query = "SELECT p.ID, pm.meta_value FROM {$wpdb->postmeta} as pm
						INNER JOIN {$wpdb->posts} as p ON pm.post_id = p.ID
						WHERE pm.meta_key = '{$location}'
						AND p.post_type = '{$post_type}'
						AND p.post_status = 'publish'";

			$orderby = ' ORDER BY p.post_date DESC';

			/* Entire Website */
			$meta_args = "pm.meta_value LIKE '%\"basic-global\"%'";

			switch ( $current_page_type ) {
				case 'is_404':
					$meta_args .= " OR pm.meta_value LIKE '%\"special-404\"%'";
					break;
				case 'is_search':
					$meta_args .= " OR pm.meta_value LIKE '%\"special-search\"%'";
					break;
				case 'is_archive':
				case 'is_tax':
				case 'is_date':
				case 'is_author':
					$meta_args .= " OR pm.meta_value LIKE '%\"basic-archives\"%'";
					$meta_args .= " OR pm.meta_value LIKE '%\"{$current_post_type}|all|archive\"%'";

					if ( 'is_tax' == $current_page_type && ( is_category() || is_tag() || is_tax() ) ) {
						if ( is_object( $q_obj ) ) {
							$meta_args .= " OR pm.meta_value LIKE '%\"{$current_post_type}|all|taxarchive|{$q_obj->taxonomy}\"%'";
							$meta_args .= " OR pm.meta_value LIKE '%\"tax-{$q_obj->term_id}\"%'";
						}
					} elseif ( 'is_date' == $current_page_type ) {
						$meta_args .= " OR pm.meta_value LIKE '%\"special-date\"%'";
					} elseif ( 'is_author' == $current_page_type ) {
						$meta_args .= " OR pm.meta_value LIKE '%\"special-author\"%'";
					}
					break;
				case 'is_home':
					$meta_args .= " OR pm.meta_value LIKE '%\"special-blog\"%'";
					break;
				case 'is_front_page':
					$current_id      = esc_sql( get_the_id() );
					$current_post_id = $current_id;
					$meta_args      .= " OR pm.meta_value LIKE '%\"special-front\"%'";
					$meta_args      .= " OR pm.meta_value LIKE '%\"{$current_post_type}|all\"%'";
					$meta_args      .= " OR pm.meta_value LIKE '%\"post-{$current_id}\"%'";
					break;
				case 'is_singular':
					$current_id      = esc_sql( get_the_id() );
					$current_post_id = $current_id;
					$meta_args      .= " OR pm.meta_value LIKE '%\"basic-singulars\"%'";
					$meta_args      .= " OR pm.meta_value LIKE '%\"{$current_post_type}|all\"%'";
					$meta_args      .= " OR pm.meta_value LIKE '%\"post-{$current_id}\"%'";

					$taxonomies = get_object_taxonomies( $q_obj->post_type );
					$terms      = wp_get_post_terms( $q_obj->ID, $taxonomies );

					foreach ( $terms as $key => $term ) {
						$meta_args .= " OR pm.meta_value LIKE '%\"tax-{$term->term_id}-single-{$term->taxonomy}\"%'";
					}

					break;
				case 'is_woo_shop_page':
					$meta_args .= " OR pm.meta_value LIKE '%\"special-woo-shop\"%'";
					break;
				case '':
					$current_post_id = get_the_id();
					break;
			}

			// Ignore the PHPCS warning about constant declaration.
			// @codingStandardsIgnoreStart
			$posts  = $wpdb->get_results( $query . ' AND (' . $meta_args . ')' . $orderby );
			// @codingStandardsIgnoreEnd

			foreach ( $posts as $local_post ) {
				self::$current_page_data[ $post_type ][ $local_post->ID ] = array(
					'id'       => $local_post->ID,
					'location' => unserialize( $local_post->meta_value ),
				);
			}

			$option['current_post_id'] = $current_post_id;

			$this->remove_exclusion_rule_posts( $post_type, $option );
			$this->remove_user_rule_posts( $post_type, $option );
		}

		return apply_filters( 'fmtpl_get_display_posts_by_conditions', self::$current_page_data[ $post_type ], $post_type );
	}

	public function remove_exclusion_rule_posts( $post_type, $option ) {
		$exclusion       = isset( $option['exclusion'] ) ? $option['exclusion'] : '';
		$current_post_id = isset( $option['current_post_id'] ) ? $option['current_post_id'] : false;

		foreach ( self::$current_page_data[ $post_type ] as $c_post_id => $c_data ) {
			$exclusion_rules = get_post_meta( $c_post_id, $exclusion, true );
			$is_exclude      = $this->parse_layout_display_condition( $current_post_id, $exclusion_rules );

			if ( $is_exclude ) {
				unset( self::$current_page_data[ $post_type ][ $c_post_id ] );
			}
		}
	}

	public function remove_user_rule_posts( $post_type, $option ) {
		$users           = isset( $option['users'] ) ? $option['users'] : '';
		$current_post_id = isset( $option['current_post_id'] ) ? $option['current_post_id'] : false;

		foreach ( self::$current_page_data[ $post_type ] as $c_post_id => $c_data ) {
			$user_rules = get_post_meta( $c_post_id, $users, true );
			$is_user    = $this->parse_user_role_condition( $current_post_id, $user_rules );

			if ( ! $is_user ) {
				unset( self::$current_page_data[ $post_type ][ $c_post_id ] );
			}
		}
	}

	public static function same_display_on_notice( $post_type, $option ) {
		global $wpdb;
		global $post;

		$all_rules        = array();
		$already_set_rule = array();

		$location = isset( $option['location'] ) ? $option['location'] : '';

		$all_headers = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT p.ID, p.post_title, pm.meta_value FROM {$wpdb->postmeta} as pm
			INNER JOIN {$wpdb->posts} as p ON pm.post_id = p.ID
			WHERE pm.meta_key = %s
			AND p.post_type = %s
			AND p.post_status = 'publish'",
				$location,
				$post_type
			)
		);

		foreach ( $all_headers as $header ) {
			$location_rules = unserialize( $header->meta_value );

			if ( is_array( $location_rules ) && isset( $location_rules['rule'] ) ) {
				foreach ( $location_rules['rule'] as $key => $rule ) {
					if ( ! isset( $all_rules[ $rule ] ) ) {
						$all_rules[ $rule ] = array();
					}

					if ( 'specifics' == $rule && isset( $location_rules['specific'] ) && is_array( $location_rules['specific'] ) ) {
						foreach ( $location_rules['specific'] as $s_index => $s_value ) {
							$all_rules[ $rule ][ $s_value ][ $header->ID ] = array(
								'ID'   => $header->ID,
								'name' => $header->post_title,
							);
						}
					} else {
						$all_rules[ $rule ][ $header->ID ] = array(
							'ID'   => $header->ID,
							'name' => $header->post_title,
						);
					}
				}
			}
		}
		if ( empty( $post ) ) {
			return;
		}
		$current_post_data = get_post_meta( $post->ID, $location, true );

		if ( is_array( $current_post_data ) && isset( $current_post_data['rule'] ) ) {
			foreach ( $current_post_data['rule'] as $c_key => $c_rule ) {
				if ( ! isset( $all_rules[ $c_rule ] ) ) {
					continue;
				}

				if ( 'specifics' === $c_rule ) {
					foreach ( $current_post_data['specific'] as $s_index => $s_id ) {
						if ( ! isset( $all_rules[ $c_rule ][ $s_id ] ) ) {
							continue;
						}

						foreach ( $all_rules[ $c_rule ][ $s_id ] as $p_id => $data ) {
							if ( $p_id == $post->ID ) {
								continue;
							}

							$already_set_rule[] = $data['name'];
						}
					}
				} else {
					foreach ( $all_rules[ $c_rule ] as $p_id => $data ) {
						if ( $p_id == $post->ID ) {
							continue;
						}

						$already_set_rule[] = $data['name'];
					}
				}
			}
		}

		if ( ! empty( $already_set_rule ) ) {
			add_action(
				'admin_notices',
				function() use ( $already_set_rule ) {
					$rule_set_titles = '<strong>' . implode( ',', $already_set_rule ) . '</strong>';

					/* translators: %s post title. */
					$notice = sprintf( __( 'The same display setting is already exist in %s post/s.', 'fami-templatekits' ), $rule_set_titles );

					echo '<div class="error">';
					echo '<p>' . $notice . '</p>';
					echo '</div>';
				}
			);
		}
	}

	public static function get_meta_option_post( $post_type, $option ) {
		$page_meta = ( isset( $option['page_meta'] ) && '' != $option['page_meta'] ) ? $option['page_meta'] : false;

		if ( false !== $page_meta ) {
			$current_post_id = isset( $option['current_post_id'] ) ? $option['current_post_id'] : false;
			$meta_id         = get_post_meta( $current_post_id, $option['page_meta'], true );

			if ( false !== $meta_id && '' != $meta_id ) {
				self::$current_page_data[ $post_type ][ $meta_id ] = array(
					'id'       => $meta_id,
					'location' => '',
				);

				return self::$current_page_data[ $post_type ];
			}
		}

		return false;
	}

	public static function get_post_selection( $post_type ) {
		$query_args = array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);

		$all_headers = get_posts( $query_args );
		$headers     = array();

		if ( ! empty( $all_headers ) ) {
			$headers = array(
				'' => __( 'Select', 'fami-templatekits' ),
			);

			foreach ( $all_headers as $i => $data ) {
				$headers[ $data->ID ] = $data->post_title;
			}
		}

		return $headers;
	}

	public static function get_format_rule_value( $save_data, $key ) {
		$meta_value = array();

		if ( isset( $save_data[ $key ]['rule'] ) ) {
			$save_data[ $key ]['rule'] = array_unique( $save_data[ $key ]['rule'] );
			if ( isset( $save_data[ $key ]['specific'] ) ) {
				$save_data[ $key ]['specific'] = array_unique( $save_data[ $key ]['specific'] );
			}

			// Unset the specifics from rule. This will be readded conditionally in next condition.
			$index = array_search( '', $save_data[ $key ]['rule'] );
			if ( false !== $index ) {
				unset( $save_data[ $key ]['rule'][ $index ] );
			}
			$index = array_search( 'specifics', $save_data[ $key ]['rule'] );
			if ( false !== $index ) {
				unset( $save_data[ $key ]['rule'][ $index ] );

				// Only re-add the specifics key if there are specific rules added.
				if ( isset( $save_data[ $key ]['specific'] ) && is_array( $save_data[ $key ]['specific'] ) ) {
					array_push( $save_data[ $key ]['rule'], 'specifics' );
				}
			}

			foreach ( $save_data[ $key ] as $meta_key => $value ) {
				if ( ! empty( $value ) ) {
					$meta_value[ $meta_key ] = array_map( 'esc_attr', $value );
				}
			}
			if ( ! isset( $meta_value['rule'] ) || ! in_array( 'specifics', $meta_value['rule'] ) ) {
				$meta_value['specific'] = array();
			}

			if ( empty( $meta_value['rule'] ) ) {
				$meta_value = array();
			}
		}

		return $meta_value;
	}
}

Fmtpl_Target_Rules_Fields::get_instance();

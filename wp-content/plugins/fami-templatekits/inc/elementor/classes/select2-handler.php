<?php

defined( 'ABSPATH' ) || die();

class Fmtpl_Select2_Handler {
    private static $_instance = null;

    public static function instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	public function __construct () {
		add_action( 'wp_ajax_fmtpl_post_list_query', [ __CLASS__, 'fmtpl_post_list_query' ] );
		//add_action( 'wp_ajax_fmtpl_post_tab_select_query', [ __CLASS__, 'post_tab_query' ] );
		add_action( 'wp_ajax_fmtpl_taxonomy_list_query', [ __CLASS__, 'fmtpl_taxonomy_list_query' ] );
	}

	/**
	 * Return Post list based on post type
	 */
	public static function fmtpl_post_list_query () {
		$security = check_ajax_referer( 'Fmtpl_Select2_Secret', 'security' );
		if ( ! $security ) return;
		$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : '';
		if ( ! $post_type ) return;

		$select_type = isset( $_POST['select_type'] ) ? $_POST['select_type'] : false;
		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : '';
		$ids = isset( $_POST['id'] ) ? $_POST['id'] : array();

		$data = [];
		$arg = [
			'post_status' => 'publish',
			'post_type' => $post_type,
			'posts_per_page' => -1,
		];
		$arg['s'] = $search_string;
		$arg['post__in'] = $ids;
		$query = new \WP_Query( $arg );
		if ( $select_type === 'choose' && $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$data[] = [
					'id' => get_the_id(),
					'text' => get_the_title(),
				];
			}
		}
		if ( $select_type === 'selected' && $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$data[get_the_id()] = get_the_title();
			}
		}
		wp_reset_query();
		// return the results in json.
		wp_send_json( $data );
	}


	/**
	 * Return Taxonomy query value
	 */
	public static function fmtpl_taxonomy_list_query () {
		$security = check_ajax_referer( 'Fmtpl_Select2_Secret', 'security' );
		if ( ! $security ) return;
		$taxonomy_type = isset( $_POST['taxonomy_type'] ) ? sanitize_text_field( $_POST['taxonomy_type'] ) : '';
		if ( ! $taxonomy_type ) {
            $tax_id = isset( $_POST['tax_id'] ) ? sanitize_text_field( $_POST['tax_id'] ) : '';
            if ( ! $tax_id ) return;
            $taxonomy_type = $tax_id;
        }


		$select_type = isset( $_POST['select_type'] ) ? $_POST['select_type'] : 'choose';
		$search = isset( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : '';
		$ids = isset( $_POST['id'] ) ? $_POST['id'] : array();

		$arg = [
			'taxonomy' => $taxonomy_type,
			'hide_empty' => true,
			'include' => $ids,
		];
		if($search)
			$arg['search'] = $search;
		$terms = get_terms( $arg );

		$data = [];
		if (!empty($terms)) {
            if ( $select_type === 'choose' ) {
                foreach ($terms as $value){
                    $data[] = [
                        'id' => $value->term_id,
                        'text' => $value->name,
                    ];
                }
            }
            elseif ( $select_type === 'selected' ) {
                foreach ($terms as $value){
                    $data[ $value->term_id ] = $value->name;
                }
            }
        }
		// return the results in json.
		wp_send_json( $data );

	}

}
Fmtpl_Select2_Handler::instance();
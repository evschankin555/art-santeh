<?php
/**
 * Class Grid_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Grid_Widget extends WP_Widget {

	/**
	 * Grid_Widget constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct(
			'Grid_Widget', // Base ID
			__( 'EnjoyInstagram - Grid', 'enjoy-instagram' ), // Name
			[
				'description' => __( 'EnjoyInstagram Widget for Grid View', 'enjoy-instagram' ),
			]
		);

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ], 15 );
	}

	/**
	 * Preload shortcode assets
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		if ( is_active_widget( false, false, $this->id_base ) && ! ei_shortcode()->enqueued ) {
			ei_shortcode()->enqueue_scripts();
		}
	}

	/**
	 * Widget callback
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$title           = apply_filters( 'widget_title', $instance['title'] );
		$cols            = apply_filters( 'widget_content', $instance['number_cols_in_grid'] );
		$rows            = apply_filters( 'widget_content', $instance['number_rows_in_grid'] );
		$user_or_hashtag = apply_filters( 'widget_content', $instance['user_or_hashtag_in_grid'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo do_shortcode( '[enjoyinstagram_mb_grid cols="' . $cols . '" rows="' . $rows . '" user_hashtag="' . $user_or_hashtag . '"]' );
		echo $args['after_widget'];
	}

	/**
	 * Admin widget
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {

		$title    = isset( $instance['title'] ) ? $instance['title'] : __( 'EnjoyInstagram', 'enjoy-instagram' );
		$instance = wp_parse_args(
			(array) $instance,
			[
				'number_cols_in_grid'     => '4',
				'number_rows_in_grid'     => '2',
				'user_or_hashtag_in_grid' => 'user',
			]
		);

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _ex( 'Title:', 'option label', 'enjoy-instagram' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_cols_in_grid' ); ?>">
				<?php _ex( 'Number of Columns', 'option label', 'enjoy-instagram' ); ?>:
			</label><br>
			<select name="<?php echo $this->get_field_name( 'number_cols_in_grid' ); ?>" id="<?php echo $this->get_field_id( 'number_cols_in_grid' ); ?>">
				<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
					<option value="<?php echo $i; ?>" <?php selected( $i, $instance['number_cols_in_grid'] ); ?>>
						<?php echo '&nbsp;' . $i; ?>
					</option>
				<?php endfor; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_rows_in_grid' ); ?>">
				<?php _ex( 'Number of Rows', 'option label', 'enjoy-instagram' ); ?>:
			</label><br>
			<select name="<?php echo $this->get_field_name( 'number_rows_in_grid' ); ?>" id="<?php echo $this->get_field_id( 'number_rows_in_grid' ); ?>">
				<?php for ( $i = 1; $i <= 10; $i ++ ) : ?>
					<option value="<?php echo $i; ?>" <?php selected( $i, $instance['number_rows_in_grid'] ); ?>>
						<?php echo '&nbsp;' . $i; ?>
					</option>
				<?php endfor; ?>
			</select>
		</p>
		<p>
			<?php _e( 'Show pics', 'enjoy-instagram' ); ?>: <br/>
			<input type="radio" name="<?php echo $this->get_field_name( 'user_or_hashtag_in_grid' ); ?>" <?php checked( 'user', $instance['user_or_hashtag_in_grid'] ); ?> value="user">
			<?php _e( 'of Your Profile', 'enjoy-instagram' ); ?><br>
			<input type="radio" name="<?php echo $this->get_field_name( 'user_or_hashtag_in_grid' ); ?>" <?php checked( 'hashtag', $instance['user_or_hashtag_in_grid'] ); ?> value="hashtag">
			<?php _e( 'by Hashtag', 'enjoy-instagram' ); ?><br>
			<?php if ( enjoyinstagram()->has_business_user() ) : ?>
				<input type="radio" name="<?php echo $this->get_field_name( 'user_or_hashtag_in_grid' ); ?>" <?php checked( 'public_hashtag', $instance['user_or_hashtag_in_grid'] ); ?> value="public_hashtag">
				<?php _e( 'by Public Hashtag', 'enjoy-instagram' ); ?><br>
			<?php endif; ?>
		</p>
		<?php
	}


	public function update( $new_instance, $old_instance ) {
		$instance                            = [];
		$instance['title']                   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['number_cols_in_grid']     = ( ! empty( $new_instance['number_cols_in_grid'] ) ) ? strip_tags( $new_instance['number_cols_in_grid'] ) : '';
		$instance['number_rows_in_grid']     = ( ! empty( $new_instance['number_rows_in_grid'] ) ) ? strip_tags( $new_instance['number_rows_in_grid'] ) : '';
		$instance['user_or_hashtag_in_grid'] = ( ! empty( $new_instance['user_or_hashtag_in_grid'] ) ) ? strip_tags( $new_instance['user_or_hashtag_in_grid'] ) : '';

		return $instance;
	}

}

/**
 * Called during widgets_init action
 *
 * @return void
 */
function register_grid_widget() {
	register_widget( 'Grid_Widget' );
}

add_action( 'widgets_init', 'register_grid_widget' );

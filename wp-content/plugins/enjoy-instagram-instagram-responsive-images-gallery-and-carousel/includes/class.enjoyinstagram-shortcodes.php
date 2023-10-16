<?php
/**
 * This class handles plugin shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if call directly
}

class EnjoyInstagram_Shortcodes {

	/**
	 * Single plugin instance
	 *
	 * @var EnjoyInstagram_Shortcodes
	 */
	protected static $instance;

	/**
	 * Users array
	 *
	 * @var array
	 */
	public $users = [];

	/**
	 * Shortcode index
	 *
	 * @var integer
	 */
	public static $index = 1;

	/**
	 * @var boolean
	 */
	public $enqueued = false;

	/**
	 * Returns single instance of the class
	 *
	 * @return EnjoyInstagram_Shortcodes
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Construct
	 *
	 * @return void
	 */
	private function __construct() {
		add_action( 'init', [ $this, 'init' ], 1 );
		// register common scripts.
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 5 );
		add_action( 'wp_head', [ $this, 'functions_in_head' ] );
		// Code optimization option.
		if ( ! get_option( 'enjoyinstagram_code_opt' ) && ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 15 );
		}
		add_filter( 'the_posts', [ $this, 'conditionally_add_scripts_and_styles' ] );

		add_shortcode( 'enjoyinstagram_grid', [ $this, 'grid_shortcode' ] );
		add_shortcode( 'enjoyinstagram_mb_grid', [ $this, 'grid_shortcode' ] );
		add_shortcode( 'enjoyinstagram_mb', [ $this, 'carousel_shortcode' ] );

		do_action( 'enjoyinstagram_add_shortcodes', $this );
	}

	/**
	 * Init variables
	 *
	 * @return void
	 */
	public function init() {
		$this->users = enjoyinstagram()->get_users();
	}

	/**
	 * Register common scripts
	 *
	 * @return void
	 */
	public function register_scripts() {

		$scripts = [
			'ei-carousel'            => 'swiper-bundle.min.js',
			'fancybox'               => 'jquery.fancybox.min.js',
			'gridrotator'            => 'jquery.gridrotator.min.js',
			'modernizr.custom.26633' => 'modernizr.custom.26633.min.js',
			'orientationchange'      => 'ios-orientationchange-fix.min.js',
			'classie'                => 'classie.min.js',
			'modernizer'             => 'modernizr.min.js',
		];

		foreach ( $scripts as $handle => $script_name ) {
			wp_register_script(
				$handle,
				ENJOYINSTAGRAM_ASSETS_URL . '/js/' . $script_name,
				[ 'jquery' ],
				ENJOYINSTAGRAM_VERSION
			);
		}

		$styles = [
			'animate'                    => 'animate.min.css',
			'ei-carousel'                => 'swiper-bundle.min.css',
			'ei-carousel-theme'          => 'carousel-theme.css',
			'ei-polaroid-carousel-theme' => 'polaroid_carousel.css',
			'ei-showcase-carousel-theme' => 'showcase_carousel.css',
			'fancybox_css'               => 'jquery.fancybox.min.css',
			'grid_fallback'              => 'grid_fallback.min.css',
			'grid_style'                 => 'grid_style.min.css',
			'enjoy_instagramm_css'       => 'enjoy-instagram.css',
			'polaroid_carousel'          => 'polaroid_carousel.css',
			'showcase_carousel'          => 'showcase_carousel.css',
			'fontawesome'                => 'font-awesome.min.css',
		];

		foreach ( $styles as $handle => $style_name ) {
			wp_register_style(
				$handle,
				ENJOYINSTAGRAM_ASSETS_URL . '/css/' . $style_name,
				[],
				ENJOYINSTAGRAM_VERSION
			);
		}

		do_action( 'enjoyinstagram_register_scripts' );
	}

	/**
	 * Enqueue scripts ans styles
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		if ( $this->enqueued ) {
			return;
		}

		wp_enqueue_script( 'ei-carousel' );
		wp_enqueue_script( 'fancybox' );
		wp_enqueue_script( 'modernizr.custom.26633' );
		wp_enqueue_script( 'gridrotator' );
		wp_enqueue_script( 'magnificlightbox-min' );
		wp_enqueue_script( 'fancybox' );
		wp_localize_script( 'gridrotator', 'GridRotator', [ 'assetsUrl' => ENJOYINSTAGRAM_ASSETS_URL ] );

		wp_enqueue_script( 'orientationchange' );
		wp_enqueue_script( 'modernizer' );
		wp_enqueue_script( 'classie' );

		wp_enqueue_style( 'animate' );
		wp_enqueue_style( 'ei-carousel' );
		wp_enqueue_style( 'ei-carousel-theme' );
		wp_enqueue_style( 'ei-polaroid-carousel-theme' );
		wp_enqueue_style( 'ei-showcase-carousel-theme' );
		wp_enqueue_style( 'fancybox_css' );
		wp_enqueue_style( 'grid_fallback' );
		wp_enqueue_style( 'grid_style' );
		wp_enqueue_style( 'enjoy_instagramm_css' );

		do_action( 'enjoyinstagram_enqueue_scripts' );

		$this->enqueued = true;
	}

	/**
	 * JS functions in head
	 *
	 * @return void
	 */
	public function functions_in_head() {
		?>
		<script type="text/javascript">
			//Grid displaying after loading of images
			function display_grid() {
				jQuery('[id^="ei-grid-loading-"]').hide();
				jQuery('[id^="ei-grid-list-"]').show();
			}

			window.onload = display_grid;

			jQuery(function () {
				jQuery(document).on('click', '.fancybox-caption__body', function () {
					jQuery(this).toggleClass('full-caption')
				})
			});
		</script>
		<?php
	}

	/**
	 * Conditionally_add_scripts_and_styles
	 *
	 * @param array $posts
	 *
	 * @return array
	 */
	public function conditionally_add_scripts_and_styles( $posts ) {
		if ( empty( $posts ) ) {
			return $posts;
		}

		foreach ( $posts as $post ) {
			if ( stripos( $post->post_content, '[enjoyinstagram_' ) !== false ) {
				add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 15 );
				add_filter( 'clean_url', 'enjoyinstagram_defer_parsing_of_js', 11, 1 );
				break;
			}
		}

		return $posts;
	}

	/**
	 * Return empty shortcode result
	 *
	 * @param string $hashtag
	 *
	 * @return string
	 */
	public function empty_shortcode_text( $hashtag ) {
		if ( empty( $hashtag ) ) {
			return __( 'There are not media published by you.', 'enjoy-instagram' );
		}

		return sprintf(
		// translators: %s is the name of the hashtag
			__(
				'There are not media published by you with the hashtag %s. Please choose a different hashtag or publish a picture with the hashtag chosen and try again.',
				'enjoy-instagram'
			),
			$hashtag
		);
	}

	/**
	 * Grid shortcode callback
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function grid_shortcode( $atts = [] ) {
		$settings = array_merge( enjoyinstagram()->settings->get_grid_settings(), (array) $atts );

		return $this->render( 'grid', $settings );
	}

	/**
	 * Carousel shortcode callback
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function carousel_shortcode( $atts = [] ) {
		$settings = array_merge( enjoyinstagram()->settings->get_carousel_settings(), (array) $atts );

		return $this->render( 'carousel', $settings );
	}

	/**
	 * Renders a specific view
	 *
	 * @param string $view
	 * @param array $settings
	 *
	 * @return string
	 */
	public function render( $view, $settings ) {
		if ( empty( $this->users ) ) {
			return '';
		}

		if ( ! $this->enqueued ) {
			$this->enqueue_scripts();
		}

		if ( isset( $settings['user'] ) && ! empty( $settings['user'] ) && isset( $this->users[ $settings['user'] ] ) ) {
			$user = $this->users[ $settings['user'] ]['username'];
		} else {
			$user = array_values( $this->users )[0]['username'];
		}

		$hashtags = isset( $settings['hashtag'] )
			? array_map( 'trim', explode( ',', $settings['hashtag'] ) )
			: [];

		if ( isset( $settings['results'] ) ) {
			$result = $settings['results'];
		} else {
			$result = ei_get_images(
				$settings['user_hashtag'],
				[
					'user'    => $user,
					'hashtag' => $hashtags,
				],
				isset( $settings['moderate'] ) ? $settings['moderate'] : ''
			);
		}

		if ( empty( $result ) ) {
			return $this->empty_shortcode_text( implode( ', ', $hashtags ) );
		}

		$i = self::$index;
		self::$index ++;

		if ( ! file_exists( $view ) ) {
			$view = ENJOYINSTAGRAM_TEMPLATE_PATH . "/shortcodes/$view.php";
		}

		ob_start();

		/**
		 * Add HTML or execute code before the shortcode displays.
		 *
		 * @param array $settings
		 *
		 * @since 1.1.0
		 */
		do_action( 'enjoyinstagram_before_shortcode', $settings );
		do_action( 'enjoyinstagram_before_shortcode_' . $view, $settings );

		// include shortcode template
		include $view;

		/**
		 * Add HTML or execute code after the shortcode displays.
		 *
		 * @param array $settings
		 *
		 * @since 1.1.0
		 */
		do_action( 'enjoyinstagram_after_shortcode', $settings );
		do_action( 'enjoyinstagram_after_shortcode_' . $view, $settings );
		$content = ob_get_clean();

		return $content ? $content : '';
	}
}

/**
 * Unique access to instance of EnjoyInstagram_Shortcodes class
 *
 * @return EnjoyInstagram_Shortcodes
 */
function ei_shortcode() {
	return EnjoyInstagram_Shortcodes::get_instance();
}

ei_shortcode();

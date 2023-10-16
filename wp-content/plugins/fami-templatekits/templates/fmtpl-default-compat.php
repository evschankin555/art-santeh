<?php
class Fmtpl_Default_Compat {

	/**
	 *  Initiator
	 */
	public function __construct() {
		add_action( 'wp', [ $this, 'hooks' ] );
	}

	public function hooks() {
		if ( fmtpl_header_enabled() ) {
			add_action( 'get_header', [ $this, 'override_header' ] );
			add_action( 'fmtpl_header', 'fmtpl_render_header' );
		}

		if ( fmtpl_footer_enabled()) {
			add_action( 'get_footer', [ $this, 'override_footer' ] );
            add_action( 'fmtpl_footer', 'fmtpl_render_footer' );
		}
	}

	public function override_header() {
		require FAMI_TPL_DIR . '/templates/fmtpl-header.php';
		$templates   = [];
		$templates[] = 'header.php';
		// Avoid running wp_head hooks again.
		remove_all_actions( 'wp_head' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

	public function override_footer() {
		require FAMI_TPL_DIR . '/templates/fmtpl-footer.php';
		$templates   = [];
		$templates[] = 'footer.php';
		// Avoid running wp_footer hooks again.
		remove_all_actions( 'wp_footer' );
		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

}

new Fmtpl_Default_Compat();

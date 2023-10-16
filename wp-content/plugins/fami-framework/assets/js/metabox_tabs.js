/* global jQuery, google */
jQuery( function ( $ ) {
	'use strict';

	$( '.fmfw-tab-nav' ).on( 'click', 'a', function ( e ) {
		e.preventDefault();

		var $li = $( this ).parent(),
			panel = $li.data( 'panel' ),
			$wrapper = $li.closest( '.fmfw-tabs' ),
			$panel = $wrapper.find( '.fmfw-tab-panel-' + panel );

		$li.addClass( 'fmfw-tab-active' ).siblings().removeClass( 'fmfw-tab-active' );
		$panel.addClass('show').siblings().removeClass('show');

		// Refresh maps, make sure they're fully loaded, when it's in hidden div (tab).
		$( window ).trigger( 'rwmb_map_refresh' );
	} );

	// Set active tab based on visible pane to better works with Meta Box Conditional Logic.
	if ( ! $( '.fmfw-tab-active' ).is( 'visible' ) ) {
		// Find the active pane.
		var activePane = $( '.fmfw-tab-panel[style*="block"]' ).index();

		if ( activePane >= 0 ) {
			$( '.fmfw-tab-nav li' ).removeClass( 'fmfw-tab-active' ).eq( activePane ).addClass( 'fmfw-tab-active' );
		}
	}

	$( '.fmfw-tab-active a' ).trigger( 'click' );

	// Remove wrapper.
	$( '.fmfw-tabs-no-wrapper' ).closest( '.postbox' ).addClass( 'fmfw-tabs-no-controls' );
} );

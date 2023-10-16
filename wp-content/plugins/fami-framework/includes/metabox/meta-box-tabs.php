<?php
/*
 * Plugin Name: Fami Framework
 * Plugin URI: https://familab.net/
 * Description: Core functions for WordPress theme
 * Author: Familab
 * Version: 1.0.0
 * Author URI: https://familab.net/
 * Text Domain: fami-framework
 * Domain Path: languages
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Fmfw_Metabox_Tabs' ) ) {
	class Fmfw_Metabox_Tabs {
		protected $active = false;
		protected $fields_output = array();
        public function __construct() {
            $this->init();
        }

		public function init() {
			add_action( 'rwmb_enqueue_scripts', array( $this, 'enqueue' ) );

			add_action( 'rwmb_before', array( $this, 'opening_div' ), 1 );
			add_action( 'rwmb_after', array( $this, 'closing_div' ), PHP_INT_MAX );

			add_action( 'rwmb_before', array( $this, 'show_nav' ) );
			add_action( 'rwmb_after', array( $this, 'show_panels' ) );

			add_filter( 'rwmb_outer_html', array( $this, 'capture_fields' ), 10, 2 );
		}
		public function enqueue() {
            wp_enqueue_style( 'fmfw-metabox-tabs', FAMI_FRAMEWORK_PLUGIN_URL . '/assets/css/metabox_tabs.css',[],'1.0.0' );
            wp_enqueue_script( 'fmfw-metabox-tabs', FAMI_FRAMEWORK_PLUGIN_URL. '/assets/js/metabox_tabs.js', array( 'jquery' ), '1.0.0',true );
		}
		public function opening_div( RW_Meta_Box $obj ) {
			if ( empty( $obj->meta_box['tabs'] ) ) {
				return;
			}

			$class = 'fmfw-tabs';
			if ( isset( $obj->meta_box['tab_style'] ) && 'default' !== $obj->meta_box['tab_style'] ) {
				$class .= ' fmfw-tabs-' . $obj->meta_box['tab_style'];
			}

			if ( isset( $obj->meta_box['tab_wrapper'] ) && false === $obj->meta_box['tab_wrapper'] ) {
				$class .= ' fmfw-tabs-no-wrapper';
			}

			echo '<div class="' . esc_attr( $class ) . '">';

			// Set 'true' to let us know that we're working on a meta box that has tabs.
			$this->active = true;
		}
		public function closing_div() {
			if ( ! $this->active ) {
				return;
			}
			echo '</div>';
			// Reset to initial state to be ready for other meta boxes.
			$this->active        = false;
			$this->fields_output = array();
		}
		public function show_nav( RW_Meta_Box $obj ) {
			if ( ! $this->active ) {
				return;
			}
			$tabs           = $obj->meta_box['tabs'];
			$default_active = $obj->tab_default_active;
			echo '<ul class="fmfw-tab-nav">';
			$i = 0;
			foreach ( $tabs as $key => $tab_data ) {
				if ( is_string( $tab_data ) ) {
					$tab_data = array(
						'label' => $tab_data,
					);
				}
				$tab_data = wp_parse_args(
					$tab_data,
					array(
						'icon'  => '',
						'label' => '',
					)
				);
				if ( filter_var( $tab_data['icon'], FILTER_VALIDATE_URL ) ) { // If icon is an URL.
					$icon = '<img src="' . esc_url( $tab_data['icon'] ) . '">';
				} else { // If icon is icon font.
					// If icon is dashicons, auto add class 'dashicons' for users.
					if ( false !== strpos( $tab_data['icon'], 'dashicons' ) ) {
						$tab_data['icon'] .= ' dashicons';
					}
					// Remove duplicate classes.
					$tab_data['icon'] = array_filter( array_map( 'trim', explode( ' ', $tab_data['icon'] ) ) );
					$tab_data['icon'] = implode( ' ', array_unique( $tab_data['icon'] ) );

					$icon = $tab_data['icon'] ? '<i class="' . esc_attr( $tab_data['icon'] ) . '"></i>' : '';
				}
				$class = "fmfw-tab-$key";
				if ( ( $default_active && $default_active === $key ) || ( ! $default_active && ! $i ) ) {
					$class .= ' fmfw-tab-active';
				}
				printf( // WPCS: XSS OK.
					'<li class="%s" data-panel="%s"><a href="#">%s%s</a></li>',
					esc_attr( $class ),
					esc_attr( $key ),
					$icon,
					$tab_data['label']
				);
				$i ++;
			} // End foreach().
			echo '</ul>';
		}
		public function show_panels( RW_Meta_Box $obj ) {
			if ( ! $this->active ) {
				return;
			}
			// Store all tabs.
			$tabs = $obj->meta_box['tabs'];
			echo '<div class="fmfw-tab-panels">';
			foreach ( $this->fields_output as $tab => $fields ) {
				// Remove rendered tab.
				if ( isset( $tabs[ $tab ] ) ) {
					unset( $tabs[ $tab ] );
				}
				echo '<div class="fmfw-tab-panel fmfw-tab-panel-' . esc_attr( $tab ) . '">';
				echo implode( '', $fields ); // WPCS: XSS OK.
				echo '</div>';
			}

			foreach ( $tabs as $tab_id => $tab_data ) {
				echo '<div class="fmfw-tab-panel fmfw-tab-panel-' . esc_attr( $tab_id ) . '">';
				echo '</div>';
			}
			echo '</div>';
		}
		public function capture_fields( $output, $field ) {
			// If meta box doesn't have tabs, do nothing.
			if ( ! $this->active || ! isset( $field['tab'] ) ) {
				return $output;
			}
			$tab = $field['tab'];
			if ( ! isset( $this->fields_output[ $tab ] ) ) {
				$this->fields_output[ $tab ] = array();
			}
			$this->fields_output[ $tab ][] = $output;
			return '';
		}
	}
	new Fmfw_Metabox_Tabs();
} // End if().

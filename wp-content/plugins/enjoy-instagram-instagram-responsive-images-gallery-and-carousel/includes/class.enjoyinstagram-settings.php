<?php

/**
 * This class handles plugin settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if call directly
}

class EnjoyInstagram_Settings {

	/**
	 * Default settings
	 *
	 * @var array
	 */
	protected $_default_settings = [];

	public function __construct() {
		$this->_default_settings = include ENJOYINSTAGRAM_DIR . 'plugin-options/options.php';

		foreach ( $this->_default_settings as $group => $options ) {
			foreach ( $options as $option_id => $default ) {
				register_setting( $group, $option_id );
			}
		}
	}

	/**
	 * Returns the config options for a specific group
	 *
	 * @param string $group
	 *
	 * @return array
	 */
	public function get_group( $group ) {
		if ( ! isset( $this->_default_settings[ $group ] ) ) {
			return [];
		}

		$settings = [];

		foreach ( $this->_default_settings[ $group ] as $name => $default_value ) {
			$settings[ $name ] = get_option( $name, $default_value );
		}

		return $settings;
	}

	/**
	 * Returns the default config options for a specific group
	 *
	 * @param string $prefix
	 *
	 * @return array
	 */
	public function get_defaults( $prefix ) {
		$settings = $this->get_base_options();
		$len      = strlen( $prefix );
		$defaults = $this->_default_settings['enjoyinstagram_options_default_group'];

		foreach ( $defaults as $key => $value ) {
			if ( substr( $key, 0, $len ) === $prefix ) {
				$settings[ (string) str_replace( '_default', '', $key ) ] = $value;
			}
		}

		return $settings;
	}

	/**
	 * Return the carousel view settings
	 *
	 * @return array
	 */
	public function get_carousel_settings() {
		if ( ! isset( $this->_default_settings['enjoyinstagram_options_default_group'] ) ) {
			return [];
		}

		$defaults          = $this->_default_settings['enjoyinstagram_options_default_group'];
		$carousel_settings = $this->get_group( 'enjoyinstagram_carousel_options' );
		$all_settings      = array_merge( $defaults, $carousel_settings );
		$settings          = [];

		foreach ( $all_settings as $name => $default_value ) {
			$name = str_replace( '_default', '', $name );
			if ( strpos( $name, '_carousel_' ) !== false ) {
				$new_name              = str_replace( 'enjoyinstagram_carousel_', '', $name );
				$settings[ $new_name ] = $default_value;
			} else {
				$new_name              = str_replace( 'enjoyinstagram_', '', $name );
				$settings[ $new_name ] = $default_value;
			}
		}

		return $settings;
	}

	/**
	 * Return the grid view settings
	 *
	 * @return array
	 */
	public function get_grid_settings() {
		if ( ! isset( $this->_default_settings['enjoyinstagram_options_default_group'] ) ) {
			return [];
		}

		$defaults          = $this->_default_settings['enjoyinstagram_options_default_group'];
		$carousel_settings = $this->get_group( 'enjoyinstagram_grid_options' );
		$all_settings      = array_merge( $defaults, $carousel_settings );
		$settings          = [];

		foreach ( $all_settings as $name => $default_value ) {
			$name = str_replace( '_default', '', $name );
			if ( strpos( $name, '_grid_' ) !== false ) {
				$new_name              = str_replace( 'enjoyinstagram_grid_', '', $name );
				$settings[ $new_name ] = $default_value;
			} else {
				$new_name              = str_replace( 'enjoyinstagram_', '', $name );
				$settings[ $new_name ] = $default_value;
			}
		}

		return $settings;
	}

	/**
	 * Returns a set of base options
	 *
	 * @return array
	 */
	public function get_base_options() {
		if ( ! isset( $this->_default_settings['enjoyinstagram_options_default_group'] ) ) {
			return [];
		}

		return array_slice( $this->_default_settings['enjoyinstagram_options_default_group'], 0, 7 );
	}
}

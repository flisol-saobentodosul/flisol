<?php

defined( 'ABSPATH' ) || exit;

class FLISoL_Certificates_Frontend_Scripts {

	private static $scripts = array();

	private static $styles = array();

	private static $wp_localize_scripts = array();

	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
		add_action( 'wp_print_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
	}

	private static function get_asset_url( $path ) {
		return apply_filters( 'flisol_get_asset_url', plugins_url( $path, FLISOL_PLUGIN_FILE ), $path );
	}

	private static function register_script( $handle, $path, $deps = array( 'jquery' ), $version = FLISOL_VERSION, $in_footer = true ) {
		self::$scripts[] = $handle;
		wp_register_script( $handle, $path, $deps, $version, $in_footer );
	}

	private static function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = FLISOL_VERSION, $in_footer = true ) {
		if ( ! in_array( $handle, self::$scripts, true ) && $path ) {
			self::register_script( $handle, $path, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	private static function register_scripts() {
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$version = FLISOL_VERSION;

		$register_scripts = array(
			'flisol-certificates' => array(
				'src'     => self::get_asset_url( 'assets/js/frontend/flisol-certificates.js' ),
				'deps'    => array( 'jquery' ),
				'version' => $version,
			),
		);
		foreach ( $register_scripts as $name => $props ) {
			self::register_script( $name, $props['src'], $props['deps'], $props['version'] );
		}
	}

	public static function load_scripts() {
		if ( ! did_action( 'before_flisol_init' ) ) {
			return;
		}

		self::register_scripts();

		self::enqueue_script( 'flisol-certificates' );
	}

	private static function localize_script( $handle ) {
		if ( ! in_array( $handle, self::$wp_localize_scripts, true ) && wp_script_is( $handle ) ) {
			$data = self::get_script_data( $handle );

			if ( ! $data ) {
				return;
			}

			$name = str_replace( '-', '_', $handle ) . '_params';

			$name = explode( '_', $name );
			foreach ( $name as &$part ) {
				if ( reset( $name ) !== $part ) {
					$part = ucfirst( strtolower( $part ) );
				} else {
					$part = strtolower( $part );
				}
			}
			$name = implode( '', $name );

			self::$wp_localize_scripts[] = $handle;
			wp_localize_script( $handle, $name, apply_filters( $name, $data ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
		}
	}

	private static function get_script_data( $handle ) {
		global $wp;

		switch ( $handle ) {
			case 'flisol-certificates':
				$params = array(
					'ajaxUrl'      => FLISoL()->ajax_url(),
					'calendarIcon' => self::get_asset_url( 'assets/images/calendar.svg' ),
				);
				break;
			default:
				$params = false;
		}

		return apply_filters( 'flisol_get_script_data', $params, $handle );
	}

	public static function localize_printed_scripts() {
		foreach ( self::$scripts as $handle ) {
			self::localize_script( $handle );
		}
	}
}

FLISoL_Certificates_Frontend_Scripts::init();

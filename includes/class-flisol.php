<?php

defined( 'ABSPATH' ) || exit;

final class FLISoL {

	public $version = '1.1.0-alpha';

	protected static $_instance = null; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

	public $certificates = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	private function init_hooks() {
		do_action( 'before_flisol_init' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		add_action( 'init', array( $this, 'init' ), 0 );
	}

	private function define_constants() {
		$this->define( 'FLISOL_ABSPATH', dirname( FLISOL_PLUGIN_FILE ) . '/' );
		$this->define( 'FLISOL_VERSION', $this->version );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.VariableConstantNameFound
		}
	}

	public function includes() {
		/**
		 * Certificates class.
		 */
		include_once FLISOL_ABSPATH . 'includes/certificates/class-flisol-certificates.php';
	}

	public function init() {
		$this->load_plugin_textdomain();

		$this->certificates = new FLISoL_Certificates();
	}

	public function load_plugin_textdomain() {
		$locale = determine_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'flisol' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		unload_textdomain( 'flisol' );
		load_textdomain( 'flisol', FLISOL_ABSPATH . 'languages/' . $locale . '.mo' );
	}

	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}
}

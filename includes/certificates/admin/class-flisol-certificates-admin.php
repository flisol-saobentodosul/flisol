<?php

defined( 'ABSPATH' ) || exit;

class FLISoL_Certificates_Admin {

	public function __construct() {
		self::init();
	}

	public function init() {
		add_action( 'init', array( $this, 'includes' ) );
	}

	public function includes() {
		include_once __DIR__ . '/class-flisol-certificates-admin-post-types.php';
	}
}

return new FLISoL_Certificates_Admin();

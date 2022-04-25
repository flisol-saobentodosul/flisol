<?php

defined( 'ABSPATH' ) || exit;

class FLISoL_Certificates_Admin_Post_Types {

	public function __construct() {
		include_once __DIR__ . '/class-flisol-certificates-admin-meta-boxes.php';
	}
}

return new FLISoL_Certificates_Admin_Post_Types();

<?php
/**
 * Plugin Name: FLISoL
 * Description: FLISoL plugin to certificates and other features.
 * Author: FLISoL São Bento do Sul
 * Author[]: Gilberto Tavares <camaleaun@gmail.com>
 * Text Domain: flisol
 * Domain Path: languages
 * Version: 1.1.0-alpha
 *
 * @package FLISoL
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'FLISOL_PLUGIN_FILE' ) ) {
	define( 'FLISOL_PLUGIN_FILE', __FILE__ );
}

if ( ! class_exists( 'FLISoL', false ) ) {
	include_once dirname( FLISOL_PLUGIN_FILE ) . '/includes/class-flisol.php';
}

function FLISoL() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return FLISoL::instance();
}

FLISoL();

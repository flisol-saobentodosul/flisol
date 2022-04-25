<?php

defined( 'ABSPATH' ) || exit;

class FLISoL_Certificates {

	public function __construct() {
		self::init();
	}

	public function init() {
		$this->includes();
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'add_rewrite_rule' ) );
		add_filter( 'query_vars', array( __CLASS__, 'query_vars' ) );
		add_action( 'parse_request', array( __CLASS__, 'parse_request' ) );
		add_action( 'template_redirect', array( __CLASS__, 'download_certificate' ) );
	}

	protected function is_frontend() {
		$is_rest_api_request = true;
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			$is_rest_api_request = false;
		}
		$rest_prefix         = trailingslashit( rest_get_url_prefix() );
		$is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) );
		$is_rest_api_request = false;
		$doing_ajax          = defined( 'DOING_AJAX' );
		$doing_cron          = defined( 'DOING_CRON' );
		$is_frontent         = ( ! is_admin() || $doing_ajax ) && ! $doing_cron && ! $is_rest_api_request;
		return $is_frontent;
	}

	public function includes() {
		/**
		 * Core classes.
		 */
		include_once __DIR__ . '/class-flisol-certificates-ajax.php';

		if ( is_admin() ) {
			include_once __DIR__ . '/admin/class-flisol-certificates-admin.php';
		}

		if ( $this->is_frontend() ) {
			$this->frontend_includes();
		}
	}

	public function frontend_includes() {
		include_once __DIR__ . '/class-flisol-certificates-frontend-scripts.php';
	}

	public static function post_type() {
		return apply_filters( 'flisol_certificates_post_type', 'certificate' );
	}

	public static function types() {
		return apply_filters(
			'flisol_certificates_types',
			array(
				'participation' => __( 'Participation', 'flisol' ),
				'speaker'       => __( 'Speaker', 'flisol' ),
				'organization'  => __( 'Organization', 'flisol' ),
			)
		);
	}

	public static function register_post_types() {
		$certificate = self::post_type();

		if ( ! is_blog_installed() || post_type_exists( $certificate ) ) {
			return;
		}

		register_post_type(
			$certificate,
			array(
				'labels'              => array(
					'name'          => __( 'Certificates', 'flisol' ),
					'singular_name' => __( 'Certificate', 'flisol' ),
				),
				'rewrite'             => array( 'slug' => 'certificate' ),
				'supports'            => array( 'title' ),
				'public'              => false,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'exclude_from_search' => false,
				'show_in_nav_menus'   => false,
				'has_archive'         => false,
				'rewrite'             => false,
				'menu_icon'           => 'dashicons-awards',
			)
		);

		register_post_type(
			'certificate_template',
			array(
				'labels'             => array(
					'name'          => __( 'Certificate Templates', 'flisol' ),
					'singular_name' => __( 'Certificate Template', 'flisol' ),
				),
				'publicly_queryable' => false,
				'public'             => true,
				'show_in_menu'       => 'edit.php?post_type=' . self::post_type(),
				'supports'           => array( 'title' ),
			)
		);
	}

	public static function add_rewrite_rule() {
		add_rewrite_rule(
			'(\d{4})/([^\.]+).pdf$',
			add_query_arg(
				array(
					self::post_type() => '$matches[1]-$matches[2]',
				),
				'index.php'
			),
			'top'
		);
	}

	public static function parse_request( $query ) {
		if ( isset( $query->query_vars[ self::post_type() ] ) ) {
			$year_name = $query->query_vars[ self::post_type() ];
			preg_match( '/^(\d{4})-/', $year_name, $match );
			$year = intval( $match[1] );
			$name = preg_replace( '/^' . $year . '-/', '', $year_name );

			$query->query_vars[ self::post_type() ] = $name;

			$query->query_vars['name'] = $name;

			$query->query_vars['certificate_year'] = $year;
		}
		return $query;
	}

	public static function query_vars( $vars ) {
		$vars[] = 'certificate_year';
		return $vars;
	}

	public static function download_certificate() {
		global $post;

		if ( ! is_singular() || self::post_type() !== get_post_type() ) {
			return;
		}

		$year = get_query_var( 'certificate_year' );

		include_once __DIR__ . '/class-flisol-certificates-tcpdf.php';
		include_once __DIR__ . '/class-flisol-certificates-pdf.php';
		$pdf = new FLISoL_Certificates_PDF( $year, $post->post_name );
		$pdf->generate_pdf();
		exit;
	}
}

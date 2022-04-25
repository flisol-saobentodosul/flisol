<?php

defined( 'ABSPATH' ) || exit;

class FLISoL_Certificates_AJAX {

	public static function init() {
		self::add_ajax_events();
	}

	public static function add_ajax_events() {
		$ajax_events_nopriv = array(
			'get_certificates',
		);

		foreach ( $ajax_events_nopriv as $ajax_event ) {
			add_action( 'wp_ajax_flisol_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			add_action( 'wp_ajax_nopriv_flisol_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}
	}

	public static function get_certificates() {
		ob_start();

		if ( empty( $_POST['cpf'] ) ) {
			wp_die();
		}
		$cpf = sanitize_text_field( $_POST['cpf'] );

		$result = array();

		$ids = get_posts(
			array(
				'post_type'   => FLISoL_Certificates::post_type(),
				'post_status' => 'publish',
				'meta_key'    => 'cpf',
				'meta_value'  => $cpf,
				'fields'      => 'ids',
			)
		);

		$certificates = _x( 'certificates', 'acf field', 'flisol' );
		foreach ( $ids as $id ) {
			$count = intval( get_post_meta( $id, $certificates, true ) );

			$result = array(
				'name'         => get_the_title( $id ),
				'certificates' => array(),
			);

			$fields = array(
				'year' => _x( 'year', 'acf field', 'flisol' ),
				'type' => _x( 'type', 'acf field', 'flisol' ),
				'url'  => _x( 'file', 'acf field', 'flisol' ),
			);

			for ( $i = 0; $i < $count; $i++ ) {
				foreach ( $fields as $key => $field ) {
					$meta  = sprintf(
						'%s_%d_%s_%s',
						$certificates,
						$i,
						_x( 'certificate', 'acf field', 'flisol' ),
						$field
					);
					$value = get_post_meta( $id, $meta, true );
					if ( 'year' === $key ) {
						preg_match( '/^\d{4}/', $value, $year );
						$value = intval( current( $year ) );
					} elseif ( 'url' === $key && is_numeric( $value ) ) {
						$value = wp_get_attachment_url( intval( $value ) );
					}
					$certificate[ $key ] = $value;
				}

				if ( empty( $certificate['url'] ) ) {
					$certificate['url'] = home_url(
						sprintf(
							'/%d/%s.pdf',
							$certificate['year'],
							sanitize_title( $result['name'] )
						)
					);
				}
				$result['certificates'][] = $certificate;
			}
		}

		wp_send_json( $result );
	}
}

FLISoL_Certificates_AJAX::init();

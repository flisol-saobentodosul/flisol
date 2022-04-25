<?php

defined( 'ABSPATH' ) || exit;

class FLISoL_Certificates_Admin_Meta_Boxes {

	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'acf_add_local_field_group' ) );
	}

	public function acf_add_local_field_group() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}
		$types = array_values( FLISoL_Certificates::types() );
		acf_add_local_field_group(
			array(
				'key'                   => 'group_60afa870935b8',
				'title'                 => __( 'Certificates', 'flisol' ),
				'fields'                => array(
					array(
						'key'               => 'field_60afa8764a00b',
						'label'             => 'CPF',
						'name'              => 'cpf',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => 'cpf',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'maxlength'         => '',
					),
					array(
						'key'               => 'field_60afae672d998',
						'label'             => 'Certificados',
						'name'              => 'certificados',
						'type'              => 'repeater',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'collapsed'         => '',
						'min'               => 0,
						'max'               => 0,
						'layout'            => 'table',
						'button_label'      => '',
						'sub_fields'        => array(
							array(
								'key'               => 'field_60afae852d999',
								'label'             => 'Certificado',
								'name'              => 'certificado',
								'type'              => 'group',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'layout'            => 'block',
								'sub_fields'        => array(
									array(
										'key'            => 'field_60afae932d99a',
										'label'          => 'Ano',
										'name'           => 'ano',
										'type'           => 'date_picker',
										'instructions'   => '',
										'required'       => 1,
										'conditional_logic' => 0,
										'wrapper'        => array(
											'width' => '',
											'class' => '',
											'id'    => '',
										),
										'display_format' => 'Y',
										'return_format'  => 'Y',
										'first_day'      => 1,
									),
									array(
										'key'           => 'field_60afaeda911e4',
										'label'         => 'Tipo',
										'name'          => 'tipo',
										'type'          => 'select',
										'instructions'  => '',
										'required'      => 1,
										'conditional_logic' => 0,
										'wrapper'       => array(
											'width' => '',
											'class' => '',
											'id'    => '',
										),
										'choices'       => array_combine(
											$types,
											$types
										),
										'default_value' => current( $types ),
										'allow_null'    => 0,
										'multiple'      => 0,
										'ui'            => 0,
										'return_format' => 'value',
										'ajax'          => 0,
										'placeholder'   => '',
									),
									array(
										'key'           => 'field_60afaf6edf2a5',
										'label'         => 'Arquivo',
										'name'          => 'arquivo',
										'type'          => 'file',
										'instructions'  => '',
										'required'      => 0,
										'conditional_logic' => 0,
										'wrapper'       => array(
											'width' => '',
											'class' => '',
											'id'    => '',
										),
										'return_format' => 'url',
										'library'       => 'all',
										'min_size'      => '',
										'max_size'      => '',
										'mime_types'    => '',
									),
								),
							),
						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => FLISoL_Certificates::post_type(),
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			)
		);

		acf_add_local_field_group(
			array(
				'key'                   => 'group_6265f7b2d1189',
				'title'                 => __( 'Certificate Front Page Background Image', 'flisol' ),
				'fields'                => array(
					array(
						'key'               => 'field_6265f82c860a7',
						'label'             => '',
						'name'              => '_thumbnail_id',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'return_format'     => 'id',
						'preview_size'      => 'full',
						'library'           => 'all',
						'min_width'         => '',
						'min_height'        => '',
						'min_size'          => '',
						'max_width'         => '',
						'max_height'        => '',
						'max_size'          => '',
						'mime_types'        => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'certificate_template',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			)
		);

		acf_add_local_field_group(
			array(
				'key'                   => 'group_6265fa8685630',
				'title'                 => __( 'Messages', 'flisol' ),
				'fields'                => array(
					array(
						'key'               => 'field_6265fa868967a',
						'label'             => __( 'Participation', 'flisol' ),
						'name'              => '_participation',
						'type'              => 'textarea',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => 'O Festival Latino Americano de Instalação de Software Livre (FLISoL) confere o presente certificado a {{nome}} por ter participado do evento que ocorreu em 23 de abril de 2022.',
						'placeholder'       => '',
						'maxlength'         => '',
						'rows'              => 2,
						'new_lines'         => '',
					),
					array(
						'key'               => 'field_6265fb7995abb',
						'label'             => __( 'Speaker', 'flisol' ),
						'name'              => '_speaker',
						'type'              => 'textarea',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => 'O Festival Latino Americano de Instalação de Software Livre (FLISoL) confere o presente certificado a {{nome}} por ter palestrado no evento que ocorreu em 23 de abril de 2022.',
						'placeholder'       => '',
						'maxlength'         => '',
						'rows'              => 2,
						'new_lines'         => '',
					),
					array(
						'key'               => 'field_6265fbbc95abc',
						'label'             => __( 'Organization', 'flisol' ),
						'name'              => '_organization',
						'type'              => 'textarea',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => 'O Festival Latino Americano de Instalação de Software Livre (FLISoL) confere o presente certificado a {{nome}} por ter organizado o evento que ocorreu em 23 de abril de 2022.',
						'placeholder'       => '',
						'maxlength'         => '',
						'rows'              => 2,
						'new_lines'         => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'certificate_template',
						),
					),
				),
				'menu_order'            => 1,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			)
		);

		acf_add_local_field_group(
			array(
				'key'                   => 'group_6265f9d167486',
				'title'                 => __( 'Certificate Back Page Background Image', 'flisol' ),
				'fields'                => array(
					array(
						'key'               => 'field_6265f9d16b509',
						'label'             => '',
						'name'              => '_back_id',
						'type'              => 'image',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'return_format'     => 'id',
						'preview_size'      => 'full',
						'library'           => 'all',
						'min_width'         => '',
						'min_height'        => '',
						'min_size'          => '',
						'max_width'         => '',
						'max_height'        => '',
						'max_size'          => '',
						'mime_types'        => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'certificate_template',
						),
					),
				),
				'menu_order'            => 2,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			)
		);
	}
}

return new FLISoL_Certificates_Admin_Meta_Boxes();

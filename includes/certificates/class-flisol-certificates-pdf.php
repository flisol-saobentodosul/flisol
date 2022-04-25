<?php

defined( 'ABSPATH' ) || exit;

class FLISoL_Certificates_PDF {

	public $year;

	public $name;

	public function __construct( $year, $name ) {
		$this->year = $year;
		$this->name = $name;
	}

	public function generate_pdf() {
		$post = get_page_by_path( $this->name, OBJECT, FLISoL_Certificates::post_type() );
		$cpf  = get_post_meta( $post->ID, 'cpf', true );

		$certificates = _x( 'certificates', 'acf field', 'flisol' );
		$count        = intval( get_post_meta( $post->ID, $certificates, true ) );
		$fields       = array(
			'year' => _x( 'year', 'acf field', 'flisol' ),
			'type' => _x( 'type', 'acf field', 'flisol' ),
		);

		$type  = 'participation';
		$types = FLISoL_Certificates::types();
		for ( $i = 0; $i < $count; $i++ ) {
			$certificate = array();
			foreach ( $fields as $key => $field ) {
				$meta  = sprintf(
					'%s_%d_%s_%s',
					$certificates,
					$i,
					_x( 'certificate', 'acf field', 'flisol' ),
					$field
				);
				$value = get_post_meta( $post->ID, $meta, true );
				if ( 'year' === $key ) {
					preg_match( '/^\d{4}/', $value, $year );
					$value = intval( current( $year ) );
				} elseif ( 'type' === $key && in_array( $value, array_values( $types ), true ) ) {
					$value = array_search( $value, $types, true );
				}
				$certificate[ $key ] = $value;
			}
			if ( $certificate['year'] === $this->year ) {
				$type = $certificate['type'];
			}
		}

		$template    = get_page_by_path( $this->year, OBJECT, 'certificate_template' );
		$template_id = $template->ID;

		$thumbnail_id = get_post_meta( $template_id, '_thumbnail_id', true );
		$image        = get_attached_file( $thumbnail_id );

		$image_attr  = getimagesize( $image );
		$orientation = 'P';
		if ( $image_attr[0] > $image_attr[1] ) {
			$orientation = 'L';
		}

		$pdf = FLISoL_Certificates_TCPDF::get_tcpdf_object(
			$orientation,
			'pt',
			array(
				$image_attr[0],
				$image_attr[1],
			)
		);

		$pdf->SetTitle( $post->post_title );

		$pdf->AddPage();

		$pdf->Image( $image, 0, 0, $image_attr[0], $image_attr[1] );

		$pdf->SetFont( 'times', '', 36 );

		$from = sprintf( '{{%s}}', _x( 'name', 'placeholder', 'flisol' ) );

		$to  = sprintf( '<b>%s</b>', $post->post_title );
		$to .= sprintf( ' CPF <b>%s</b>', $cpf );

		$message = get_post_meta( $template_id, "_{$type}", true );
		$html    = str_replace( $from, $to, $message );

		$pdf->writeHTMLCell( 650, '', 383, 362, $html, 0, 0, false, true, 'C' );

		$back_id = get_post_meta( $template_id, '_back_id', true );
		$back    = get_attached_file( $back_id );

		$back_attr  = getimagesize( $back );

		$pdf->AddPage();

		$pdf->Image( $back, 0, 0, $back_attr[0], $back_attr[1] );

		// $pdf->SetTextColor( 255, 0, 0 );

		$pdf->SetFont( 'times', '', 16.7 );
		$html = '<b>Nome do evento:</b> Festival Latino-americano de Instalação de Software Livre (FLISoL) - 2021<br><b>Carga horária total:</b> 09h';
		$pdf->writeHTMLCell( 930, '', 120, 93, $html, 0, 0, false, true, 'C' );

		$pdf->SetFont( 'times', 'BU', 16.7 );
		$pdf->MultiCell( 930, 0, 'PROGRAMAÇÃO', 0, 'C', false, 1, 120, 177 );

		$pdf->SetFont( 'times', '', 16.7 );
		$pdf->setCellHeightRatio( 1.9 );
		$html = '<b>Palestra:</b> Software Livre: Você utiliza e nem imagina! - <b>Palestrante:</b> Ailem Ferreira dos Santos<br><b>Palestra:</b> Arquiteturas Para Engenharia Com Uso De Múltiplas Linguagens De Programação - <b>Palestrante:</b> Thiago Schaedler Uhlmann<br><b>Palestra:</b> O licenciamento de software e a proteção do direito autoral - <b>Palestrante:</b> Alcemir Nabir Kowal<br><b>Palestra:</b> Carreira em Data Science - <b>Palestrante:</b> Aline da Silva Souza Oliveira<br><b>Palestra:</b> Blog com venda de produtos em poucos minutos - <b>Palestrante:</b> Gilberto Tavares<br><b>Palestra:</b> Liberdade a qualquer preço - <b>Palestrante:</b> Huberto Araldi Leal<br><b>Palestra:</b> OnlyOffice - Sua nova suíte de escritório - <b>Palestrante:</b> Klaibson Natal Ribeiro Borges<br><b>Palestra:</b> Software livre & Universidade: Como a UDESC utiliza nas atividades de Ensino, Pesquisa e Extesão - <b>Palestrante:</b> Alexandre<br>Veloso de Matos, Alex Luiz de Souza, Luiz Claudio Dalmolin e Nilson Modro<br><b>Meetup:</b> Testes automatizados com ferramentas open source. - <b>Speakers:</b> Jeferson Luis Indejejczak, João Vitor Martins dos Santos e
		Sandro Luis Galvão Ferreira';
		$pdf->writeHTMLCell( 930, '', 120, 199, $html, 0, 0, false, true, 'C' );

		$pdf->SetFont( 'helvetica', '', 14.3 );
		$html = 'Confira a altencidade em: <a href="https://saobentodosul.flisol.org.br/certificados/">https://saobentodosul.flisol.org.br/certificados</a>';
		$html = 'Confira a altencidade em: https://saobentodosul.flisol.org.br/certificados';
		$pdf->writeHTMLCell( 1028, 0, 73, 732, $html, 0, 0, false, true, 'L' );

		FLISoL_Certificates_TCPDF::output_to_http(
			$pdf,
			$this->year . '-' . $this->name . '.pdf'
		);
	}
}

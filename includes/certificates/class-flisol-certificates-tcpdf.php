<?php

defined( 'ABSPATH' ) || exit;

class FLISoL_Certificates_TCPDF {

	public static function get_tcpdf_object( $orientation, $units, $size ) {
		include_once __DIR__ . '/lib/tcpdf/tcpdf.php';
		$pdf = new TCPDF( $orientation, $units, $size, true, 'UTF-8', false );
		$pdf->setPrintHeader( false );
		$pdf->setPrintFooter( false );

		$pdf->SetMargins( 0, 0, 0 );
		$pdf->SetHeaderMargin( 0 );
		$pdf->SetFooterMargin( 0 );

		$pdf->SetAutoPageBreak( true, 0 );

		$pdf->setImageScale( 1.25 );
		return $pdf;
	}

	public static function output_to_http( $pdf, $filename ) {
		header( 'Content-Type: application/pdf' );
		header( "Content-Disposition: inline; filename=\"$filename\"" );
		header( 'Cache-Control: private, max-age=0, must-revalidate' );
		header( 'Pragma: public' );

		echo $pdf->output( $filename );
	}
}

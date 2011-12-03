<?php

/********************************************************\
 * File: 	generatedpfg.php							*
 * Author: 	Andreas Göransson							*
 * Date: 	2011-12-02									*
 * Organization: Andreas Göransson						*
 *														*
 * Project: 	phpPortfolio.							*
 *														*
 * Description:	PDF generation for cv.php				*
\********************************************************/

require("fpdf.php");

// For reference:
//  A4 size = 297 x 210 mm
//  Point -> mm conversion: pt * 0.35 = mm   [pt = 1/72in, in = 25.4mm]

// Settings
$headersize = 26;
$titlesize = 18;
$tinytitlesize = 0;
$textsize = 12;

/* CV format*/
class CV extends FPDF {
	// Page header
	function Header(){
		// TODO add a header (contact details?)
	}

	// Page footer
	function Footer(){
		$this->SetY( -15 );
		$this->SetFont( "Arial", "I", 8 );
		$this->Cell( 0, 10, "Page " . $this->PageNo() . "/{nb}", 0, 0, "C" );
	}
}

// Create the pdf
$pdf = new CV( "P", "mm", "A4" );
$pdf->SetMargins( 25, 12.5, 25 );
$pdf->AliasNbPages();

// Start doc
$pdf->AddPage();

// Header
$pdf->SetFont( "Helvetica", "B", $headersize );
$pdf->SetTextColor( 0, 0, 77 );

$pdf->Cell( 0, $headersize * 0.35, "Curriculum Vitae", 1, 0, "C" );	
$pdf->Ln( $textsize );

// Set standard colors
$pdf->SetTextColor( 0, 0, 0 );
$pdf->SetDrawColor( 99, 99, 99 );

// Add table cv_main
$query = "SELECT name, street, city, phone, email FROM cv_main ORDER BY id DESC LIMIT 1";
$result = mysql_query($query, $link) or die( mysql_error() );
while( $row = mysql_fetch_array($result, MYSQL_NUM) ){
	// Surrounding box
	//$pdf->Cell( 0, count($row) * 10, "", 1, 0, "C" );
	// TODO!
	$pdf->SetFont( "Helvetica", "B", $textsize );
	for( $i = 0; $i < count($row); $i++){
		$pdf->Text( 0, 10 * $i, $row[$i] );
		//$pdf->Cell( 0, $textsize * 0.35, $row[$i], 1, 0, 'C' );
		$pdf->Ln();
	}
}

// Add table cv_main (ambitions)
$query = "SELECT ambitions FROM cv_main ORDER BY id DESC LIMIT 1";
$result = mysql_query($query, $link) or die( mysql_error() );


$pdf->SetFont( "Helvetica", "", $textsize );
while( $row = mysql_fetch_assoc($result) ){
	$pdf->Write( $textsize, $row["ambitions"] );
	//$pdf->Cell( 0, 20, $row["ambitions"], 1, 0, "C" );
}

// Send the pdf to user
$pdf->Output();?>

<?php
require_once('../tcpdf/tcpdf.php');
require_once('function.php');
require_once('company_data.php');

$czyj = $_GET['czyj'];

$dane = $pdo->prepare('SELECT * FROM reklamacje WHERE czyj=:czyj AND token="1"');
$dane->bindValue("czyj", $czyj);
$dane->execute();
$row = $dane->fetch();
$klient = znakipdf($row['czyj']);
$telefon = znakipdf($row['telefon']);
$kiedy = $row['data1'];

$klient = $klient . '<br>' . $telefon;
$danedw = '<table><tr><td width="320">SAT-SERWIS s.c. ZPHU<br>ul.Północna 36<br>91-425 Łódź<br>tel:42-6319277<br>mail:sat@satserwis.pl</td><td width="350">' . $klient . '</td></tr></table>';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

$pdf->SetAutoPageBreak(TRUE, 20);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
	require_once(dirname(__FILE__) . '/lang/eng.php');
	$pdf->setLanguageArray($l);
}
$pdf->setCellPaddings(0);
$pdf->setCellMargins(0);
$pdf->AddPage();

for ($strony = 0; $strony < 2; $strony++) {
	$pdf->SetFont('dejavusans', 'B', 12);
	$pdf->MultiCell(189, 6, 'Zgłoszenie reklamacyjne z dnia: ' . $kiedy, 0, 'C', 0, 1, '', '', true);
	$y = $pdf->getY();
	$pdf->setY($y + 3);
	$pdf->SetFont('dejavusans', '', 12);
	$pdf->MultiCell(90, 5, 'Dane firmy:', 0, 'L', 0, 0, '', '', true);
	$pdf->MultiCell(80, 5, 'Dane reklamującego:', 0, 'L', 0, 1, '', '', true);
	$x = $pdf->getX();
	$y = $pdf->getY();
	$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150));
	$pdf->Line($x, $y, $x + 190, $y, $style);
	$pdf->SetFont('dejavusans', '', 10);
	$x = $pdf->getX();
	$y = $pdf->getY();
	$pdf->writeHTMLCell(190, 0, $x, $y + 1, $danedw, 0, 1, 0, true, 'L');
	$pdf->SetFont('dejavusans', 'B', 11);
	$pdf->MultiCell(90, 8, 'Produkty:', 0, 'L', 0, 1, '', '', true, 0, false, true, 10, 'B');
	$x = $pdf->getX();
	$y = $pdf->getY();
	$pdf->Line($x, $y, $x + 190, $y, $style);
	$dane->execute();
	foreach ($dane->fetchAll() as $wybrany) {
		$pdf->SetFont('dejavusans', 'B', 10);
		$pdf->MultiCell(189, 6, $wybrany['product_name'] . ' s/n / kod: ' . $wybrany['serial'], 0, 'L', 0, 1, '', '', true);
		$pdf->SetFont('dejavusans', '', 10);
		$pdf->MultiCell(189, 6, 'Opis usterki: ' . $wybrany['usterka'], 0, 'L', 0, 1, '', '', true);
		$pdf->MultiCell(189, 6, 'Uwagi: ' . $wybrany['uwagi1'], 0, 'L', 0, 1, '', '', true);
		$x = $pdf->getX();
		$y = $pdf->getY();
		$pdf->Line($x, $y, $x + 190, $y, $style);
	}
	$y = $pdf->getY();
	if ($strony == 0) {
		if ($y > 140) {
			$pdf->AddPage();
		} else {
			$pdf->setY(140);
			$y = $pdf->getY();
			$x = $pdf->getX();
			$dotdot = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150));
			$pdf->Line($x, $y, $x + 190, $y, $dotdot);
		}
	}
}
$pdo->query('UPDATE reklamacje SET token="0" WHERE token="1"');

$pdf->Output("Zgłoszenie.pdf", 'I');

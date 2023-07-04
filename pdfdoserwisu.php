<?php
require_once('../tcpdf/tcpdf.php');
require_once('function.php');
require_once('company_data.php');

$idkontrahent = $_GET['idkontrahent'];
$wybrane = $_GET['wybrane'];
$data = date('Y-m-d');
$idzbioru = $_GET['idzbioru'];

$sprawdzzbior = $pdo->prepare('SELECT * FROM zbiory WHERE idkontrahent=:idkontrahent AND datawys=:data');
$dodajzbior = $pdo->prepare('INSERT INTO zbiory (idkontrahent, datawys) VALUES (:idkontrahent, :datawys)');
$kontrahent = $pdo->prepare('SELECT * FROM kontrahent WHERE idkontrahent=:idkontrahent');
$reklamacje = $pdo->prepare('SELECT * FROM reklamacje WHERE FIND_IN_SET(lp,:wybrane)');

if (empty($idzbioru)) {
	$sprawdzzbior->bindValue("idkontrahent", $idkontrahent);
	$sprawdzzbior->bindValue("data", $data);
	$sprawdzzbior->execute();
	if ($sprawdzzbior->rowCount() == 0) {
		$dodajzbior->bindValue("idkontrahent", $idkontrahent);
		$dodajzbior->bindValue("datawys", $data);
		$dodajzbior->execute();
		$sprawdzzbior->bindValue("idkontrahent", $idkontrahent);
		$sprawdzzbior->bindValue("data", $data);
		$sprawdzzbior->execute();
	}
	$zbior = $sprawdzzbior->fetch();
	$idzbioru = $zbior["idzbioru"];
}

$kontrahent->bindValue("idkontrahent", $idkontrahent);
$kontrahent->execute();
$kontrahent = $kontrahent->fetch();
$kontrahent = $kontrahent[1] . '<br>' . $kontrahent[3] . '<br>' . $kontrahent[4] . ' ' . $kontrahent[5] . '<br>' . $kontrahent[2];
$danedw = '<table><tr><td width="320">SAT-SERWIS s.c. ZPHU<br>ul.Północna 36<br>91-425 Łódź<br>tel:42-6319277<br>mail:sat@satserwis.pl</td><td width="350">' . $kontrahent . '</td></tr></table>';

$reklamacje->bindValue("wybrane", implode(',', $wybrane));


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

for ($strony = 0; $strony < 2; $strony++) {
	$pdf->AddPage();
	$pdf->SetFont('dejavusans', 'B', 12);
	$pdf->MultiCell(189, 6, 'Zgłoszenie reklamacyjne nr: ' . $idzbioru . ' z dnia: ' . $data, 0, 'C', 0, 1, '', '', true);
	$y = $pdf->getY();
	$pdf->setY($y + 3);
	$pdf->SetFont('dejavusans', '', 12);
	$pdf->MultiCell(90, 5, 'Zgłaszający:', 0, 'L', 0, 0, '', '', true);
	$pdf->MultiCell(80, 5, 'Serwis:', 0, 'L', 0, 1, '', '', true);
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
	$reklamacje->execute();
	foreach ($reklamacje->fetchAll() as $wybrany) {
		$pdf->SetFont('dejavusans', 'B', 10);
		$pdf->MultiCell(150, 6, $wybrany['product_name'] . ' s/n / kod: ' . $wybrany['serial'], 0, 'L', 0, 0, '', '', true);
		$pdf->SetFont('dejavusans', '', 10);
		$czyj = ($strony > 0) ? $wybrany['czyj'] : '';
		$pdf->MultiCell(39, 6, $czyj, 0, 'R', 0, 1, '', '', true);
		$pdf->MultiCell(189, 6, 'Opis usterki: ' . $wybrany['usterka'], 0, 'L', 0, 1, '', '', true);
		$pdf->MultiCell(189, 6, 'Uwagi: ' . $wybrany['uwagi1'], 0, 'L', 0, 1, '', '', true);
		$x = $pdf->getX();
		$y = $pdf->getY();
		$pdf->Line($x, $y, $x + 190, $y, $style);
	}
}
$pdf->Output($idzbioru . '.pdf', 'I');

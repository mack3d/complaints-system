<?php
$title = 'Edycja towaru';

require "function.php";

@$test = $_GET['test'];
$lp = $_GET['lp'];
$data = date('Y-m-d');
if ($test == 1) {
	$title2 = '<meta http-equiv="Refresh" content="2; url=reklamacje.php">';
	include('navi.html');
	@$czyj = znaki($_GET['czyj']);
	@$telefon = znaki($_GET['telefon']);
	@$towar = znaki($_GET['product_name']);
	@$serial = $_GET['serial'];
	@$usterka = znaki($_GET['usterka']);
	@$uwagi1 = znaki($_GET['uwagi1']);
	@$status = $_GET['status'];

	if ($status == 1) {
		$idzbioru = ', idzbioru=""';
	} else {
		$idzbioru = '';
	}

	$query = 'UPDATE reklamacje SET czyj="' . $czyj . '", serial="' . $serial . '", telefon="' . $telefon . '", product_name="' . $towar . '", usterka="' . $usterka . '", uwagi1="' . $uwagi1 . '",
	 status="' . $status . '"' . $idzbioru . ' WHERE lp=' . $lp;
	$pdo->query($query);
	echo '<center>Trwa zapisywanie...';
} else {

	$title2 = '';
	include('navi.html');
	echo '<center>';

	$towar = $pdo->query('SELECT * FROM reklamacje LEFT OUTER JOIN zbiory USING (idzbioru) LEFT OUTER JOIN kontrahent USING (idkontrahent) WHERE lp=' . $lp);
	$row = $towar->fetch();

	echo '<form><fieldset style="width: 800px"><legend>Edycja towaru</legend>';
	echo '<table><tr>';
	echo '<td>Czyj</td><td><input required type=text name=czyj value="' . $row['5'] . '" onblur="duzeliterki(this)"></tr>';
	echo '<tr><td>Telefon</td><td><input type=text name=telefon value="' . $row['6'] . '"></tr>';
	echo '<tr><td>Towar</td><td><input required type=text name=towar value="' . $row['3'] . '" onblur="duzeliterki(this)"></tr>';
	echo '<tr><td>Serial</td><td><input type=text name=serial value="' . $row['4'] . '" onblur="duzeliterki(this)"></tr>';
	echo '<tr><td>Usterka</td><td><textarea required rows=4 cols=25 type=text name=usterka>' . $row['7'] . '</textarea></tr>';
	echo '<tr><td>Uwagi</td><td><textarea rows=2 cols=25 type=text name=uwagi1>' . $row['8'] . '</textarea></tr>';
	echo '<tr><td>Status</td><td><input type=text name=status value="' . $row['9'] . '"></tr>';
	echo '<tr><td>Data wys.</td><td><input type=text disabled name=datawys value="' . $row['13'] . '"></tr>';
	echo '<tr><td>Serwis</td><td><input type=text disabled value="' . $row['16'] . '"></tr>';
	echo '</table>';
	echo '</fieldset><br>';
	echo '<fieldset style="width: 800px">';
	echo '<input type=text name=test value=1 hidden><input type=text name=lp value=' . $lp . ' hidden>';
	echo '<input type=submit value=Zapisz>';
	echo '</fieldset>';
	echo '</form>';
}
echo '</center>';
include('stopka.html');

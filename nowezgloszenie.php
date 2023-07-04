<?php
$title = 'Nowe zgłoszenie';
include('navi.html');
require "function.php";

@$lp = $_GET['lp'];
@$towar = znaki(trim($_GET['product_name']));
@$serial = znaki(trim($_GET['serial']));
@$czyj = znaki(trim($_GET['czyj']));
@$telefon = znaki(trim($_GET['telefon']));
@$usterka = znaki(trim($_GET['usterka']));
@$uwagi1 = znaki(trim($_GET['uwagi1']));
@$token = $_GET['token'];
$status = 1;
$data1 = date('Y-m-d');

echo '<center>';
echo '<fieldset class="nowezg">';
echo '<legend>Nowe zgłoszenie</legend>';
echo '<table><form>';
echo '<tr><td>Zgłaszający:</td><td><input onfocus type=text size=20 name="czyj" value="' . $czyj . '" required onblur="duzeliterki(this)"></td></tr>';
echo '<tr><td>Telefon:</td><td><input type="text" size=20 name="telefon" id="telefon" value="' . $telefon . '"></td></tr>';
echo '<tr><td>Towar:</td><td><input id="product_name" type=text size=20 name="product_name" value="" required onblur="duzeliterki(this)"></td></tr>';
echo '<tr><td>Serial:</td><td><input type="text" size=20 name="serial" value="" onblur="duzeliterki(this)"><br></td></tr>';
echo '<tr><td>Usterka</td><td><textarea cols=25 rows=4 name="usterka" value="" required></textarea></td></tr>';
echo '<tr><td>Uwagi</td><td><textarea cols=25 rows=1 value="" name="uwagi1"></textarea></td></tr>';
echo '<tr><td><input hidden name="token" value="1"><input type="submit" value="Wyslij"></td></tr>';
echo '</form></table>';
echo '</fieldset><br><br>';

$dodaj = $pdo->prepare('INSERT INTO reklamacje (product_name, serial, czyj, telefon, usterka, uwagi1, status, data1, token) VALUES (:product_name, :serial, :czyj, :telefon, :usterka, :uwagi1, :status, :data1, :token)');
$sprawdz = $pdo->prepare('SELECT * FROM reklamacje WHERE czyj=:czyj AND token="1"');

function brakdanych($str)
{
	return (!isset($str) | $str == '') ? 'brak' : $str;
}

if ($czyj != "" & $towar != "" & $usterka != "") {
	$dodaj->bindValue("product_name", $towar);
	$dodaj->bindValue("serial", $serial);
	$dodaj->bindValue("czyj", $czyj);
	$dodaj->bindValue("telefon", $telefon);
	$dodaj->bindValue("usterka", $usterka);
	$dodaj->bindValue("uwagi1", $uwagi1);
	$dodaj->bindValue("status", $status);
	$dodaj->bindValue("data1", $data1);
	$dodaj->bindValue("token", $token);
	$dodaj->execute();
}


//--sprawdza czy sa nie wydrukowane dla tego zglaszajacego i wyswietla je--
$tmp = 0;
if ($czyj != "") {
	$sprawdz->bindValue("czyj", $czyj);
	$sprawdz->execute();
	if ($sprawdz->rowCount() > 0) {
		echo '<fieldset class="nowezgzb"><legend>Lista dodanych</legend>';
		echo '<table><form action="pdf.php">';
		echo '<tr><td> </td><td>Nazwa</td><td>Serial</td><td>Usterka</td><td>Uwagi</td></tr>';
		foreach ($sprawdz->fetchAll() as $row) {
			echo '<tr>';
			echo '<td><input type=checkbox style="width:10px;" checked name=lp[] value="' . $row["lp"] . '"></td>';
			echo '<input hidden type=text name=lp[] value="' . $row["lp"] . '">';
			echo '<td><input readonly type=text style="border:none;" value="' . $row["product_name"] . '"></td>';
			echo '<td><input readonly type=text style="border:none;" value="' . $row["serial"] . '"></td>';
			echo '<td><input readonly type=text style="border:none;" value="' . $row["usterka"] . '"></td>';
			echo '<td><input readonly type=text style="border:none;" value="' . $row["uwagi1"] . '"></td>';
			echo '</tr>';
		}
		echo '<tr><td colspan="2"><input hidden name=czyj value="' . $czyj . '"><input hidden name=telefon value="' . $telefon . '"><input id="wydruk" onclick="ukryj(hidden)" type="submit" value="Wydruk"></td></tr></form></table>';
	}
}
echo '</center>';
include('stopka.html');

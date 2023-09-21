<?php
$title = 'Reklamacje';
include('navi.html');
require "function.php";

@$lp = trim($_GET['lp']);
@$towar = trim($_GET['product_name']);
@$serial = trim($_GET['serial']);
@$czyj = trim($_GET['czyj']);
@$telefon = trim($_GET['telefon']);
@$usterka = trim($_GET['usterka']);
@$uwagi1 = trim($_GET['uwagi1']);
@$status = trim($_GET['status']);
@$data1 = trim($_GET['data1']);
@$wybrane = $_GET['wybrane'];
@$button = $_GET['button'];
@$nazwa = $_GET['nazwa'];
@$datawys = $_GET['datawys'];
@$idzbioru = $_GET['idzbioru'];
@$idkontrahent = $_GET['idkontrahent'];
$tmp = 0;
$tmpp = 0;
$data = date('Y-m-d');

if ($idkontrahent <> "") {
	$dodajtowar = '<a onclick="return forma();" class="button_reklamacje" href="doserwisu.php?idkontrahent=' . $idkontrahent . '&idzbioru=' . $idzbioru . '">Dodaj towar</a>';
	$usunbutton = '';
} else {
	$dodajtowar = '';
	$usunbutton = '<input onclick="return forma();" class="button_reklamacje" type="submit" name="button" value="Usun"/>';
}

$usun = $pdo->prepare('DELETE FROM reklamacje WHERE FIND_IN_SET(lp,?)');
$wrocilo = $pdo->prepare('UPDATE reklamacje SET status=3 WHERE FIND_IN_SET(lp,?)');

if (!is_null($wybrane)) {
	$lpwyb = implode(",", $wybrane);
	if ($button == "Usun") {
		$usun->execute(array($lpwyb));
	}
	if ($button == "Zmiennawrocilo") {
		$wrocilo->execute(array($lpwyb));
	}
}

$query = 'SELECT * FROM reklamacje LEFT OUTER JOIN zbiory USING (idzbioru) LEFT OUTER JOIN kontrahent USING (idkontrahent)';

if (empty($lp) <> 1 | empty($towar) <> 1 | empty($serial) <> 1 | empty($czyj) <> 1 | empty($telefon) <> 1 | empty($usterka) <> 1 | empty($uwagi1) <> 1 | empty($status) <> 1 | empty($data1) <> 1 | empty($nazwa) <> 1 | empty($datawys) <> 1 | empty($idzbioru) <> 1) {
	$query = $query . ' WHERE';
	if ($lp <> "") {
		$query = $query . ' lp=' . $lp;
		$tmp++;
	}
	if ($towar <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' product_name LIKE "%' . $towar . '%"';
		$tmp++;
	}
	if ($serial <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' serial LIKE "%' . $serial . '%"';
		$tmp++;
	}
	if ($czyj <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' czyj LIKE "%' . $czyj . '%"';
		$tmp++;
	}
	if ($telefon <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' telefon LIKE "%' . $telefon . '%"';
		$tmp++;
	}
	if ($usterka <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' usterka LIKE "%' . $usterka . '%"';
		$tmp++;
	}
	if ($uwagi1 <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' uwagi1 LIKE "%' . $uwagi1 . '%"';
		$tmp++;
	}
	if ($status <> "") {
		if ($status <> 0) {
			if ($tmp >= 1) $query = $query . ' AND ';
			$query = $query . ' status LIKE "%' . $status . '%"';
			$tmp++;
		}
	}
	if ($data1 <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' data1 LIKE "%' . $data1 . '%"';
		$tmp++;
	}
	if ($nazwa <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' nazwa LIKE "%' . $nazwa . '%"';
		$tmp++;
	}
	if ($datawys <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' datawys LIKE "%' . $datawys . '%"';
		$tmp++;
	}
	if ($idzbioru <> "") {
		if ($tmp >= 1) $query = $query . ' AND ';
		$query = $query . ' idzbioru LIKE "' . $idzbioru . '"';
		$tmp++;
	}
}
$query = $query . ' ORDER BY status ASC, data1 DESC LIMIT 200';
$reklamacje = $pdo->query($query);

echo '<center><form>' . $dodajtowar . $usunbutton . '<input onclick="return forma();" class="button_reklamacje" type="submit" name="button" value="Zmiennawrocilo" /><table id="tabelka_reklamacje">';

echo '<thead><tr class="top"><th></th><th>LP</th><th>Towar</th><th>S/N / kod</th><th>Czyj</th><th>Telefon</th><th>Usterka</th><th>Status</th><th>Data PZ</th><th>Serwis</th><th>Data wys.</th></tr></thead>';

echo '<tbody><tr class="search"><td><input type="checkbox" id="selecctall"/></td>
<td><input name="lp" size=3 value="' . $lp . '"></td>
<td><input name="product_name" size=23 value="' . $towar . '"></td>
<td><input name="serial" size=11 value="' . $serial . '"></td>
<td><input name="czyj" size=11 value="' . $czyj . '"></td>
<td><input name="telefon" size=11 value="' . $telefon . '"></td>
<td><input name="usterka" size=11 value="' . $usterka . '"></td>
<td><select name="status" size=1><option value="0" selcted>Wszystkie</option><option value="1">Przyjete</option><option value="2">Wyslano</option><option value="3">Wrocilo</option></select></td>
<td><input name="data1" size=11 value="' . $data1 . '"></td>
<td><input name="nazwa" size=11 value="' . $nazwa . '"></td>
<td><input name="datawys" size=11 value="' . $datawys . '"></td>
<td><input hidden=hidden type="submit" value=submit></td></tr>';

foreach ($reklamacje->fetchAll() as $row) {
	$statusik = $row["status"];
	if ($statusik == "1") {
		$statusik1 = 'Przyjeto';
	} elseif ($statusik == "2") {
		$statusik1 = 'W serwisie';
	} else {
		$statusik1 = 'Wrocilo';
	}
	$dataalarm5 = date("Y-m-d", strtotime("-3 day"));
	$dataalarm14 = date("Y-m-d", strtotime("-8 day"));

	$datawys = $row["datawys"];

	$datawysalarm = strtotime(date("Y-m-d", strtotime($datawys)) . "+12 day"); //data wys + 12dni
	$datawysalarmm = date("Y-m-d", $datawysalarm);

	$data1 = $row["data1"];

	$kolor = '';

	if ($statusik == "1") {
		if ($data1 <= $dataalarm5) {
			$kolor = ' style="background-color:#FFBF00"';
		}
		//--od 5
		else {
			$kolor = ' style="background-color:#F3F781"';
		}
	}

	if ($statusik == "2") {
		if ($data > $datawysalarmm) {
			$kolor = ' style="background-color:#FE9A2E"';
		}
	}

	echo '<tr ' . $kolor . '><td style="border:0px;">';
	if ($statusik <> "3") {
		echo '<input class="checkbox1" type="checkbox" name="wybrane[]" value=' . $row["lp"] . ' id=' . $row["lp"] . '>';
	}

	echo '</td><td width=18px><input class="tabelka" readonly value="' . $row["lp"] . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $row["product_name"] . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $row["serial"] . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $row["czyj"] . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $row["telefon"] . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $row["usterka"] . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $row["uwagi1"] . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $statusik1 . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $row["data1"] . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $row["nazwa"] . '"></td>';
	echo '<td width=80px><input ' . $kolor . ' type="text" readonly class="tabelka" value="' . $row["datawys"] . '"></td>';
	echo '<td width=50px><a href="towaredit.php?lp=' . $row["lp"] . '" style="text-decoration: none;"><input type=button value="Edytuj"></a></td></tr>';
}
echo '</tbody></table></form>';
echo '</center>';
include('stopka.html');

<?php
$title = 'Do wydruku';
include('navi.html');
require "function.php";

@$towar = trim($_GET['towar']);
@$serial = trim($_GET['serial']);
@$czyj = trim($_GET['czyj']);
@$telefon = trim($_GET['telefon']);
@$usterka = trim($_GET['usterka']);
@$uwagi1 = trim($_GET['uwagi1']);
@$token = $_GET['token'];
$status = 1;
$data1 = date('Y-m-d');

@$oznacz = $_GET['oznacz'];
@$lp = $_GET['lp'];
$petle = count($lp);

$zmienoznaczenie = $pdo->prepare('UPDATE reklamacje SET token="" WHERE lp=:lp');
$niewydrukowane = $pdo->prepare('SELECT * FROM reklamacje WHERE token="1"');

if ($oznacz <> "") {
	foreach ($lp as $towar) {
		$zmienoznaczenie->bindValue("lp", $towar);
		$zmienoznaczenie->execute();
	}
}

//--sprawdza czy sa nie wydrukowane i wyswietla je--
$niewydrukowane->execute();
if ($niewydrukowane->rowCount() > 0) {
	echo '<table><form><input type="submit" name="oznacz" value="Oznacz jako wydrukowane">';
	echo '<tr><td> </td><td>LP</td><td>czyj</td><td>towar</td><td>serial</td><td>opis</td><td>uwagi</td><td>data</td></tr>';
	foreach ($niewydrukowane->fetchAll() as $row) {
		echo '<tr>';
		echo '<td><input type=checkbox checked name="lp[]" value="' . $row["lp"] . '">';
		echo '</td><td><input readonly type=text style="border:none;" value="' . $row["lp"] . '">' .
			'</td><td><input readonly type=text style="border:none;" value="' . $row["czyj"] . '">' .
			'</td><td><input readonly type=text style="border:none;" value="' . $row["product_name"] . '">' .
			'</td><td><input readonly type=textarea style="border:none; cols:1; rows:3;" value="' . $row["serial"] . '">' .
			'</td><td><input readonly type=text style="border:none;" value="' . $row["usterka"] . '">' .
			'</td><td><input readonly type=text style="border:none;" value="' . $row["uwagi1"] . '">' .
			'</td><td><input readonly type=text style="border:none;" value="' . $row["data1"] . '">' .
			'</td><td><a style="text-decoration: none; width: 115px; background: #a6a6a6; padding: 2px; text-align: center; border-radius: 2px; color: black;" href="nowezgloszenie.php?czyj=' . $row["czyj"] . '&co=edit">EDYTUJ</a>' .
			'</td></tr>';
	}
	echo '</form></table></center>';
}
?>
</body>

</html>
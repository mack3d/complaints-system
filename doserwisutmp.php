<?php
$title = 'tworzenie zbioru';
include('navi.html');
require "function.php";

echo '<center>';

@$wybrane = $_GET['wybrane'];
@$idkontrahent = $_GET['idkontrahent'];
@$idzbioru = $_GET['idzbioru'];
$data = date('Y-m-d');

$kontrahent = $pdo->prepare('SELECT * FROM kontrahent WHERE idkontrahent=:id');
$reklamacje = $pdo->prepare('SELECT * FROM reklamacje WHERE FIND_IN_SET(lp,:wybrane)');

if (count($wybrane) > 0) {
	if ($idkontrahent <> 0) {
		$kontrahent->bindValue("id", $idkontrahent);
		$kontrahent->execute();
		$row = $kontrahent->fetch();
		echo '<fieldset style="width:800px;"><legend>Podsumowanie wysyłki dnia: ' . $data . '</legend>';
		echo '<fieldset style="width:600px;"><legend>Dane kontrahenta</legend>';
		echo '<table><tr><td>';
		echo $row['nazwa'] . '<br>';
		echo $row['ulica'] . '<br>';
		echo $row['kod'] . '  ' . $row['miasto'] . '<br>';
		echo $row['telefonkontrahent'] . '<br>';
		echo $row['mail'] . '<br>';
		echo '</td></tr>';
	} else {
		echo '<table><tr><td>Nie wybrano kontrahenta !!!<a href="doserwisu.php">WRÓĆ</a><br></td></tr>';
	}
	echo '</table></fieldset>';
	echo '<fieldset style="width:600px;"><legend>Zestawienie</legend><table>';
	$reklamacje->bindvalue("wybrane", implode(',', $wybrane));
	$reklamacje->execute();
	foreach ($reklamacje->fetchAll() as $row) {
		echo '<tr><td><b>Towar: ' . $row['product_name'] . ' - s/n:' . $row['serial'] . '</b><br>';
		echo 'Usterka: ' . $row['usterka'] . '<br>';
		echo 'Uwagi: ' . $row['uwagi1'] . '<br><br></td></tr>';
	};
	echo '</table></fieldset></fieldset>';

	//---przyciski--
	echo '<fieldset style="width: 800px;"><legend>Polecenia</legend>';
	echo '<table><tr>';
	if ($idkontrahent <> 0) {
		echo '<td>';
		echo '<form action="pdfdoserwisu.php"><input hidden type="text" name="idkontrahent" value="' . @$idkontrahent . '">
		<input type="submit" value="Wydruk">';
		foreach ($wybrane as $towar) {
			echo '<input hidden type=text name=wybrane[] value="' . $towar . '">';
		}
		echo '<input hidden name=idzbioru value="' . $idzbioru . '"></form></td>';
	}

	if ($idkontrahent <> 0) {
		echo '<td>';
		echo '<form action="zapiszwz.php"><input hidden type="text" name="idkontrahent" value="' . @$idkontrahent . '">
		<input type="submit" value="Zamknij/Zapisz">';
		foreach ($wybrane as $towar) {
			echo '<input hidden type=text name=wybrane[] value="' . $towar . '">';
		}
		echo '<input hidden name=idzbioru value="' . $idzbioru . '"></form></td>';
	}
	echo '</tr></table>';
} else {
	echo '<center>Proszę wybrać minimum jeden przedmiot.</center><a href="doserwisu.php">WRÓĆ</a>';
}
echo '</center>';
include('stopka.html');

<?php
$title = 'Kontrahenci';
include('navi.html');
require "function.php";

@$idkontrahent = $_GET['idkontrahent'];
@$nazwa = $_GET['nazwa'];
@$telefonkontrahent = $_GET['telefonkontrahent'];
@$ulica = $_GET['ulica'];
@$kod = $_GET['kod'];
@$miasto = $_GET['miasto'];
@$mail = $_GET['mail'];
@$test = $_GET['test'];

echo '<center>';

if ($test == 4) {
	$kontrahent = $pdo->prepare('INSERT INTO kontrahent (idkontrahent,nazwa,telefonkontrahent,ulica,kod,miasto,mail) VALUES ("NULL", :nazwa, :telefonkontrahent, :ulica, :kod, :miasto, :mail)');
}
if ($test == 2) {
	$kontrahent = $pdo->prepare('UPDATE kontrahent SET nazwa=:nazwa, telefonkontrahent=:telefonkontrahent, ulica=:ulica, kod=:kod, miasto=:miasto, mail=:mail WHERE idkontrahent=:idkontrahent');
	$kontrahent->bindValue("idkontrahent", $idkontrahent);
}
if ($test == 2 | $test == 4) {
	$kontrahent->bindValue("nazwa", $nazwa);
	$kontrahent->bindValue("telefonkontrahent", $telefonkontrahent);
	$kontrahent->bindValue("ulica", $ulica);
	$kontrahent->bindValue("kod", $kod);
	$kontrahent->bindValue("miasto", $miasto);
	$kontrahent->bindValue("mail", $mail);
	$kontrahent->execute();
}

//--glowny widok
$kontrahenci = $pdo->query('SELECT * FROM kontrahent ORDER BY nazwa ASC');
$czycosmamy = $pdo->prepare('SELECT * FROM reklamacje LEFT OUTER JOIN zbiory USING (idzbioru) WHERE zbiory.idkontrahent=:idkontrahent AND reklamacje.status="2"');
$editkontrahent = $pdo->prepare('SELECT * FROM kontrahent WHERE idkontrahent=:idkontrahent');

if ($test == 0 | !isset($test)) {
	$kontrahenci->execute();
	foreach ($kontrahenci->fetchAll() as $row) {
		$czycosmamy->bindValue("idkontrahent", $row["idkontrahent"]);
		$czycosmamy->execute();
		if ($czycosmamy->rowCount() > 0) {
			$kolor = 'style="background-color: #FE2E2E; color:white;"';
		} else {
			$kolor = '';
		}
		echo '<a class="kontrahencik" ' . $kolor . ' href="kontrahenci.php?idkontrahent=' . $row["idkontrahent"] . '&test=1">
		<p class="knazwa">' . $row["nazwa"] . '</p>
		<p class="ktele">' . $row["telefonkontrahent"] . '</p>
		<p class="kkod">' . $row["kod"] . '</p>
		<p class="kmiasto">' . $row["miasto"] . '</p>
		<p class="kmail">' . $row["mail"] . '</p></a>';
	}
	echo '<form><input hidden name="test" value="3"><input type=submit value="Dodaj"></form>';
}

//--dodaj nowa pozycje
if ($test == 3) {
	echo '<table>';
	echo '<tr><td>NAZWA</td><td>TELEFON</td><td>ULICA</td><td>KOD</td><td>MIASTO</td><td>MAIL</td></tr>';
	echo '<form method="GET"><tr>';
	echo '<td><input name="nazwa" value=""></td>';
	echo '<td><input name="telefonkontrahent" value=""></td>';
	echo '<td><input name="ulica" value=""></td>';
	echo '<td><input name="kod" value=""></td>';
	echo '<td><input name="miasto" value=""></td>';
	echo '<td><input name="mail" value="" onkeyup="spacje(this)"></td>';
	echo '<td><input hidden name="test" value="4"><input type=submit value="Dodaj"></td>';
	echo '</tr></form>';
	echo '</tr><td><form><input hidden name="test" value="0"><input type=submit value="Powrót"></form></td></tr></table>';
}

//--edytuj istniejacego
if ($test == 1) {
	$editkontrahent->bindValue('idkontrahent', $idkontrahent);
	$editkontrahent->execute();
	$row = $editkontrahent->fetch();
	echo '<table>';
	echo '<tr><td>ID</td><td>NAZWA</td><td>TELEFON</td><td>ULICA</td><td>KOD</td><td>MIASTO</td><td>MAIL</td></tr>';
	echo '<form method="GET"><tr>';
	echo '<td><input readonly name="idkontrahent" value="' . $row['idkontrahent'] . '"></td>';
	echo '<td><input name="nazwa" value="' . $row['nazwa'] . '"></td>';
	echo '<td><input name="telefonkontrahent" value="' . $row['telefonkontrahent'] . '"></td>';
	echo '<td><input name="ulica" value="' . $row['ulica'] . '"></td>';
	echo '<td><input name="kod" value="' . $row['kod'] . '"></td>';
	echo '<td><input name="miasto" value="' . $row['miasto'] . '"></td>';
	echo '<td><input name="mail" value="' . $row['mail'] . '" onkeyup="spacje(this)"></td>';
	echo '<td><input hidden name="test" value="2"><input type=submit value="Zapisz"></td>';
	echo '</tr></form>';
	echo '</table>';

	$czycosmamy->bindValue("idkontrahent", $idkontrahent);
	$czycosmamy->execute();
	if ($czycosmamy->rowCount() > 0) {
		foreach ($czycosmamy->fetchAll() as $row) {
			echo '<a class="indexserwis" href="reklamacje.php?idzbioru=' . $row['idzbioru'] . '">' . $row["product_name"] . ' - <span style="color: green;">' . $row["czyj"] . '</span></a>';
		}
	}
}
echo '</center>';
include('stopka.html');

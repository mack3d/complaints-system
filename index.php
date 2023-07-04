<?php
$title = 'Strona glowna';
include('navi.html');
require "function.php";

$dowydruku = $pdo->prepare('SELECT lp FROM reklamacje WHERE token=:token');
$licznik = $pdo->prepare('SELECT * FROM reklamacje WHERE status=:status');

$dowydruku->bindValue("token", "1");
$dowydruku->execute();
$dowydruku = $dowydruku->rowCount();
$opiswydruki = ($dowydruku > 0) ? '<a style="color: red; font-weight: bold;" href="dowydruku.php">Do wydruku:</a>' : 'Do wydruku:';

$licznik->bindValue("status", "1");
$licznik->execute();
$dowysylki = $licznik->rowCount();
$licznik->bindValue("status", "2");
$licznik->execute();
$wserwisie = $licznik->rowCount();

echo '<center><table>';
echo '<tr style="font-size: 15px;"><td>' . $opiswydruki . '</td><td>Do wysylki:</td><td>W serwisie:</td>';
echo '<tr style="background-color: #d8d8d8;"><td>' . $dowydruku . '</td><td color="red">' . $dowysylki . '</td><td>' . $wserwisie . '</td></tr>';
echo '</table>';

if ($wserwisie > 0) {
	foreach ($licznik->fetchAll() as $row) {
		echo '<a class="indexserwis" href="reklamacje.php?idzbioru=' . $row['idzbioru'] . '">' . $row["product_name"] . ' - <span style="color: green;">' . $row["czyj"] . '</span></a>';
	}
}

echo '</center>';
include('stopka.html');

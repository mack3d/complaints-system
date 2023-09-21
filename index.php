<?php
$title = 'Strona glowna';
include('navi.html');
require "function.php";

$dowydruku = $pdo->prepare('SELECT lp FROM reklamacje WHERE token=:token');
$counter = $pdo->prepare('SELECT * FROM reklamacje WHERE status=:status');

$dowydruku->bindValue("token", "1");
$dowydruku->execute();
$dowydruku = $dowydruku->rowCount();
$opiswydruki = ($dowydruku > 0) ? '<a style="color: red; font-weight: bold;" href="dowydruku.php">Do wydruku:</a>' : 'Do wydruku:';

$counter->bindValue("status", "1");
$counter->execute();
$dowysylki = $counter->rowCount();
$counter->bindValue("status", "2");
$counter->execute();
$wserwisie = $counter->rowCount();

echo '<center><table>';
echo '<tr style="font-size: 15px;"><td>' . $opiswydruki . '</td><td>Do wysylki:</td><td>W serwisie:</td>';
echo '<tr style="background-color: #d8d8d8;"><td>' . $dowydruku . '</td><td color="red">' . $dowysylki . '</td><td>' . $wserwisie . '</td></tr>';
echo '</table>';

if ($wserwisie > 0) {
	foreach ($counter->fetchAll() as $row) {
		echo '<a class="indexserwis" href="reklamacje.php?idzbioru=' . $row['idzbioru'] . '">' . $row["product_name"] . ' - <span style="color: green;">' . $row["czyj"] . '</span></a>';
	}
}

echo '</center>';
include('stopka.html');

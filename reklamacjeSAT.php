<?php
$title = 'Sat-serwis';
include('navi.html');
require "function.php";

$satserwis = $pdo->query('SELECT * FROM reklamacje LEFT OUTER JOIN zbiory USING (idzbioru) LEFT OUTER JOIN kontrahent USING (idkontrahent) WHERE (czyj LIKE "%SAT-SERWIS%" OR czyj LIKE "%SATSERWIS%") AND status="2" ORDER BY status ASC, data1 DESC');

echo '<center><table border=1px border-style=solid cellspacing=5px frame=void bordercolor=#D8D8D8>';
foreach ($satserwis->fetchAll() as $row) {
	echo '<tr><td style="border:0px;">';
	echo '<td width=250px><input type="text" readonly class="tabelka" value="' . $row["product_name"] . '"></td>';
	echo '<td width=160px><input type="text" readonly class="tabelka" value="' . $row["usterka"] . '"></td>';
	echo '<td width=80px><input type="text" readonly class="tabelka" value="' . $row["uwagi1"] . '"></td>';
	echo '<td width=80px><input type="text" readonly class="tabelka" value="' . $row["nazwa"] . '"></td>';
	echo '<td width=80px><input type="text" readonly class="tabelka" value="' . $row["datawys"] . '"></td>';
	echo '</tr>';
}
echo '</center>';

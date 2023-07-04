<head>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js" />
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#selecctall').click(function(event) { //on click
				if (this.checked) { // check select status
					$('.checkbox1').each(function() { //loop through each checkbox
						this.checked = true; //select all checkboxes with class "checkbox1"              
					});
				} else {
					$('.checkbox1').each(function() { //loop through each checkbox
						this.checked = false; //deselect all checkboxes with class "checkbox1"                      
					});
				}
			});

		});
	</script>
</head>

<?php
$title = 'Tworzenie zbioru';
include('navi.html');
require "function.php";

echo '<center>';

$kontrahenci = $pdo->query('SELECT idkontrahent, nazwa FROM kontrahent ORDER BY nazwa ASC');

@$idzbioru = $_GET['idzbioru'];
@$idkontrahent = $_GET['idkontrahent'];

echo '<form action="doserwisutmp.php">';
echo '<select name="idkontrahent" ';
if (!isset($idkontrahent)) {
	echo 'selected';
} else {
	echo 'disabled="disabled"';
}
echo '>';
echo '<option value="0"> </option>';
foreach ($kontrahenci->fetchAll() as $kontrahent) {
	echo '<option value="' . $kontrahent['idkontrahent'] . '"';
	if (isset($idkontrahent) & $kontrahent['idkontrahent'] == $idkontrahent) {
		echo 'selected';
	}
	echo '>' . $kontrahent['nazwa'] . '</option>';
}
echo '</select>';
if (isset($idkontrahent)) {
	echo '<input hidden name="idkontrahent" value="' . $idkontrahent . '">';
}
echo '<table border=1px border-style=solid cellspacing=5px frame=void bordercolor=#D8D8D8>';
echo '<tr class="top"><td><input type="checkbox" id="selecctall"/></td><td>LP</td><td>Towar</td><td>Num.seryjny</td><td>Kogo?</td><td>Usterka</td><td>Uwagi 1</td></tr>';
echo '</td></tr>';

$reklamacje = $pdo->prepare('SELECT * FROM reklamacje LEFT OUTER JOIN zbiory USING (idzbioru) WHERE FIND_IN_SET(status,:status) ORDER BY status ASC, data1 ASC');
if (!isset($idzbioru)) {
	$reklamacje->bindValue(":status", "1");
} else {
	$reklamacje->bindValue(":status", "1,2");
}
$reklamacje->execute();

foreach ($reklamacje->fetchAll() as $row) {
	$statusik = $row["status"];
	if ($statusik == "2" & $idzbioru <> $row['idzbioru']) {
		echo '';
	} else {
		echo '<tr><td style="border:0px;">';
		echo '<input class="checkbox1" type="checkbox" name="wybrane[]"';
		if (isset($idzbioru) & $idzbioru == $row['idzbioru'] & $statusik == "2") {
			echo ' checked="checked" ';
		}
		echo 'value=' . $row["lp"] . ' id=' . $row["lp"] . '>';

		$tabelka = '"></td><td width=100px><input type="text" readonly class="tabelka" value="';
		$tabelka200 = '"></td><td width=200px><input type="text" readonly class="tabelka" value="';

		echo '</td><td width=30px><input style="font-size: 9px;" class="tabelka" readonly value="' . $row["lp"] .
			$tabelka200 . $row["product_name"] .
			$tabelka . $row["serial"] .
			$tabelka . $row["czyj"] .
			$tabelka200 . $row["usterka"] .
			$tabelka200 . $row["uwagi1"] .
			'"></td></tr>';
	}
}
echo '<input type="submit" value="Dalej"><input hidden name=idzbioru value="' . $idzbioru . '"></form></table>';
echo '</center>';
include('stopka.html');
?>
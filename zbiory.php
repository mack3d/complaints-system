<?php
$title = 'Zbiory';
include('navi.html');
require "function.php";

@$nazwa = $_GET['nazwa'];
@$more = $_GET['more'];
$morecheck = 'checked';

$query = 'SELECT DISTINCT idzbioru,nazwa,datawys,idkontrahent FROM zbiory LEFT OUTER JOIN kontrahent USING (idkontrahent) LEFT OUTER JOIN reklamacje USING (idzbioru)';

if ($nazwa <> "") {
	$query = $query . ' WHERE nazwa LIKE "%' . $nazwa . '%"';
}

$query = $query . ' ORDER BY idzbioru DESC';
if (!isset($more)) {
	$query = $query . ' LIMIT 20';
	$morecheck = '';
}
$lista = $pdo->query($query);

echo '<center>';
echo '<div id="tabelkazbiory">';
echo 'Szukaj: <form>
<input id="szukajzbiory" name="nazwa" size=20 value="' . $nazwa . '">
<input hidden=hidden type="submit" value=submit>
Wiecej<input type="checkbox" ' . $morecheck . ' name="more" value="1"></form>';

foreach ($lista->fetchAll() as $row) {
	$test = $pdo->query('SELECT GROUP_CONCAT(status) as status FROM reklamacje WHERE idzbioru=' . $row['idzbioru']);
	$arr = explode(',', $test->fetch()['status']);
	if (count($arr) > 0) {
		if (min($arr) == max($arr)) {
			$odp = max($arr);
		} else {
			$odp = '<span style="color:red;">czesciowo</span>';
		}
	}
	if (empty($arr)) {
		$odp = 'blad';
	}

	if ($odp == 2) {
		$odp = '<span style="color:blue;">w serwisie</span>';
	}
	if ($odp == 3) {
		$odp = '<span style="color:green;">wrocilo</span>';
	}
	//---koniec sprawdzania

	echo '<a class="zbiorek" href="reklamacje.php?idzbioru=' . $row["idzbioru"] . '&idkontrahent=' . $row["idkontrahent"] . '">
	<p class="idzbioru">' . $row["idzbioru"] . '</p>
	<p class="nazwa">' . $row["nazwa"] . '</p>
	<p class="datawys">' . $row["datawys"] . '</p>
	<p class="datawys">' . $odp . '</p></a>';
}
echo '</div>';
echo '</center>';
include('stopka.html');

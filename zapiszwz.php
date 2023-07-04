<?php
$title = 'Zapisywanie';
$title2 = '<meta http-equiv="Refresh" content="1; url=zbiory.php">';
include('navi.html');
echo '<b>Zapisuje...</b>';
?>

<?php
require "function.php";

$wybrane = $_GET['wybrane'];
$idkontrahent = $_GET['idkontrahent'];
$idzbioru = $_GET['idzbioru'];
$data = date('Y-m-d');

$sprawdzzbior = $pdo->prepare('SELECT * FROM zbiory WHERE idkontrahent=:idkontrahent AND datawys=:data');
$dodajzbior = $pdo->prepare('INSERT INTO zbiory (idkontrahent, datawys) VALUES (:idkontrahent, :datawys)');
$updatezbior = $pdo->prepare('UPDATE zbiory SET datawys=:datawys WHERE idzbioru=:idzbioru');
$status = $pdo->prepare('UPDATE reklamacje SET status=2, idzbioru=? WHERE lp=?');

if (empty($idzbioru)) {
	$sprawdzzbior->bindValue("idkontrahent", $idkontrahent);
	$sprawdzzbior->bindValue("data", $data);
	$sprawdzzbior->execute();
	if ($sprawdzzbior->rowCount() == 0) {
		$dodajzbior->bindValue("idkontrahent", $idkontrahent);
		$dodajzbior->bindValue("datawys", $data);
		$dodajzbior->execute();
		$sprawdzzbior->bindValue("idkontrahent", $idkontrahent);
		$sprawdzzbior->bindValue("data", $data);
		$sprawdzzbior->execute();
	}
	$zbior = $sprawdzzbior->fetch();
	$idzbioru = $zbior["idzbioru"];
} else {
	$updatezbior->bindValue("idzbioru", $idzbioru);
	$updatezbior->bindValue("datawys", $data);
	$updatezbior->execute();
}

foreach ($wybrane as $wybrany) {
	$status->execute(array($idzbioru, $wybrany));
}

echo '</center>';
include('stopka.html');
?>

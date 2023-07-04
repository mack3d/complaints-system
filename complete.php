<?php
include("function.php");

$towar = trim($_GET['term']);

$words = explode(' ', $towar);
$query = 'SELECT DISTINCT product_name FROM reklamacje WHERE';

if (count($words) > 0) {
	foreach ($words as $key => $word) {
		$query .= ' product_name LIKE ? AND';
		$words[$key] = "%$word%";
	}
	$query = trim($query, ' AND');
	$query .= ' ORDER BY data1 DESC LIMIT 10';
	$sth = $pdo->prepare($query);
	$sth->execute($words);
	foreach ($sth->fetchAll() as $row) {
		$data[] = strtoupper($row['product_name']);
	}
}

echo json_encode($data);

<?php
include_once("../database.class.php");
$pdo = new DBconn();

$pdo->query('CREATE TABLE IF NOT EXISTS testowy3 (
    lp INT(11) AUTO_INCREMENT key,
    product_name VARCHAR(255) NOT NULL,
    serial_number VARCHAR(255) NULL,
    czyj VARCHAR(255) NOT NULL,
    telefon VARCHAR(30) NULL,
    description VARCHAR(255) NOT NULL,
    uwagi1 VARCHAR(255) NULL,
    status INT(1) NOT NULL,
    data1 TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data2 DATETIME,
    token INT(5) NULL,
    idzbioru INT(11) NULL)');

function znaki($pole)
{
  $pole = mb_ereg_replace("\"", "&quot;", $pole);
  $pole = mb_ereg_replace("\'", "&lsquo;", $pole);
  return $pole;
}

function znakipdf($pole)
{
  $pole = mb_ereg_replace("&quot;", "\"", $pole);
  $pole = mb_ereg_replace("&lsquo;", "\'", $pole);
  return $pole;
}

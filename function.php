<?php
$pdo = new PDO('mysql:host=localhost;dbname=satserwis;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

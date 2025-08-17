<?php
$DB_HOST = "mysql03.sannergmbh.beep.pl";
$DB_USER = "sanner";
$DB_PASS = "Sanner123$%^";
$DB_NAME = "sanner";

function pdo_conn() {
  static $pdo = null;
  if ($pdo === null) {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",$DB_USER,$DB_PASS,[
      PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
    ]);
  }
  return $pdo;
}

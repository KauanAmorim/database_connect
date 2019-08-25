<?php

require_once "vendor/autoload.php";

echo "<pre>";
$connect = new \connection\connectdb("buy_register");
$connection = $connect->getConnection();

$query = "SELECT * FROM cliente";

var_dump($connect->transctions('beginTransaction'));
var_dump($connect->transctions('rollback'));
var_dump($connect->transctions('commit'));

var_dump($connect->execute($query, 'fetch'));
var_dump($connect->setConnection($connection));
var_dump($connect->getConnectionData());
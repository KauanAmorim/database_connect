<?php

require_once "vendor/autoload.php";

$connect = new \connection\connectdb("config/config.json", 'financas');
$connection = $connect->getConnection();

$query = "SELECT * FROM usuarios";

var_dump($connect->transctions('beginTransaction'));
// var_dump($connect->transctions('rollback'));
// var_dump($connect->transctions('commit'));

var_dump($connect->execute($query, 'fetch'));
// var_dump($connect->setConnection($connection));
// var_dump($connect->getConnectionData());
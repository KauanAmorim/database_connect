<?php

require_once "vendor/autoload.php";

$connect = new \connection\connectdb("config/config.json", 'financas');
$connection = $connect->getConnection();

$query = "SELECT * FROM usuarios";

// var_dump($connection->beginTransaction());

// $statement = $connection->query($query);
// var_dump($statement->fetchAll(\PDO::FETCH_CLASS));

// var_dump($usuario);
<?php

require_once "vendor/autoload.php";

$connect = new \connection\connectdb("config/config.json", 'financas');
$connection = $connect->getConnection();

$query = "SELECT * FROM usuarios";

// var_dump($connection->beginTransaction());

$statement = $connection->query($query);
var_dump($statement->fetchObject());

// $statement = $connection->prepare($query);
// $statement->execute();
// $usuario = $statement->fetch(\PDO::FETCH_OBJ);

// var_dump($usuario);
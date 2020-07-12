<?php

require_once "vendor/autoload.php";

$Connect = new \DatabaseConnect\Connect("config/config.json", 'financas');
$Connection = $Connect->getConnection();

$query = "SELECT * FROM usuarios";

// var_dump($Connection->beginTransaction());

// $statement = $connection->query($query);
// var_dump($statement->fetchAll(\PDO::FETCH_CLASS));

// var_dump($usuario);
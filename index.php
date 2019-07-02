<?php

require_once "vendor/autoload.php";

echo "<pre>";
$connect = new \connection\connectdb("buy_register");
var_dump($connect->getConnection());


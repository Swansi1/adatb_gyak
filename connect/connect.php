<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adatb";

$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
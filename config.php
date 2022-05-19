<?php 

$dsn = 'mysql:host=localhost;dbname=pizza_db';
$username = 'root';
$pass = '';

try {
    $con = new PDO($dsn, $username, $pass);
}

catch (PDOException $ex) {
    echo 'cannot connect' . $ex->getMessage();
}
<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_registro";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Algo de errado aconteceu;");
}

?>
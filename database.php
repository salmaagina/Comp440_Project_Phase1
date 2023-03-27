<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "phase1";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Connection failed;");
}

?>
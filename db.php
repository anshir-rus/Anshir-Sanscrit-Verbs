<?php
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/


$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sanskrit";

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
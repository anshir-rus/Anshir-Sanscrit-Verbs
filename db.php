<?php
/* 
Based on method of Ivan Tolchelnikov
Programming by Andrei Shirobokov 2023 
*/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sanskrit";
$secret_word="Yogaścittavṛttinirodhaḥ";

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
<?php

include "../db.php";

$name=$_REQUEST['name'];
$email=$_REQUEST['email'];
$password=md5($_REQUEST['password']);

//echo $email."<BR>".$password;

$query = "INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')";
$result = mysqli_query($connection, $query);

echo $result;
//echo "INSERT INTO users (name,email,password) VALUES ('','$email','$password')";

?>

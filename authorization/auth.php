<?php

include "../db.php";

//setcookie('login', md5("123".$secret_word)); 

$email=$_REQUEST['email'];
$password=md5($_REQUEST['password']);

//setcookie('login', md5($_REQUEST['email'].$secret_word)); 

$query = "SELECT id,name FROM users WHERE email='$email' AND password='$password'";
$result = mysqli_query($connection, $query);
$row = $result->fetch_assoc();

//echo $query;
//echo  md5($_REQUEST['username'].$secret_word);

if($row['name']!='')
{
    setcookie('login', md5($_REQUEST['email'].$secret_word),time()+60*60*24*365,'/'); 
    setcookie('name', $row['name'],time()+60*60*24*365,'/');
    setcookie('id', $row['id'],time()+60*60*24*365,'/');
    echo $row['name'];
}




?>
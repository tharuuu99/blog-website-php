<?php

$hostName = 'localhost';
$userName = 'root';
$password ="";
$dbName = 'blog';

$connection = mysqli_connect($hostName,$userName,$password,$dbName);
if(!$connection){
    die("Connection fail!".mysqli_connect_error());
}

?>
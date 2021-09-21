<?php
    header("Access-Control-Allow-Origin: * ");
    
    $server = 'localhost';
    $username = 'root';
    $password = "";
    $db_name = "facebook";

    $connection = new mysqli($server, $username, $password, $db_name);

    if($connection -> connect_error){
        die('Connection Failed');
    }
?>
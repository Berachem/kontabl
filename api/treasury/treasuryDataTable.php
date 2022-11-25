<?php

session_start();


// TEST
/*
$_SESSION["num"] = "louisvi";
$_SESSION["type"]="user";
*/

// if it is a user include the file merchantDataTable.php
if (!(isset($_SESSION["num"])) || !(isset($_SESSION["type"]))){
    // return json
    $response = [
        "success" => false,
        "error" => "Vous n'êtes pas connecté",
        "notLogged" => true
    ];
}else{
    if ($_SESSION["type"] == "user") {
        include("treasury/merchantDataTable.php");
    } else if ($_SESSION["type"] == "productowner" || $_SESSION["type"] == "admin") {
        include("treasury/pOwnerDataTable.php");
    }
}









?>
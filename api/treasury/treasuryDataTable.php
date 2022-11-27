<?php

session_start();


// TEST
/*
$_SESSION["num"] = "louisvi";
$_SESSION["type"]="user";

$_SESSION["num"] = "louisvi";
$_SESSION["type"] = "productowner";
*/

// if it is a user include the file merchantDataTable.php
if (!(isset($_SESSION["num"])) || !(isset($_SESSION["type"]) || $_SESSION["type"] == "admin")){
    // return json
    $response = [
        "success" => false,
        "error" => "Vous n'avez pas les droits.",
        "notLogged" => true
    ];
}else{
    if ($_SESSION["type"] == "user") {
        include("treasury/merchantDataTable.php");
    } else if ($_SESSION["type"] == "productowner" ) {
        include("treasury/pOwnerDataTable.php");
    }
}









?>
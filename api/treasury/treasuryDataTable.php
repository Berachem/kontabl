<?php

session_start();

// TEST
/* $_SESSION["num"] = 722003936;
$_SESSION["type"] = "utilisateur"; */

// if it is a user include the file merchantDataTable.php
if (!(isset($_SESSION["num"]))){
    // return json
    $response = [
        "success" => false,
        "error" => "Vous n'êtes pas connecté"
    ];
}


if ($_SESSION["type"] == "user") {
    include("/treasury/merchantDataTable.php");
} else if ($_SESSION["type"] == "productowner" || $_SESSION["type"] == "admin") {
    include("/treasury/pOwnerDataTable.php");
}

header('Content-Type: application/json');
echo json_encode($response);






?>
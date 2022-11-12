<?php

// return the list of sirens in json format only if the user is logged in AND is an admin or productowner

session_start();

include("include/Functions.inc.php");

if (isset($_SESSION['num']) && ($_SESSION['type'] == 'admin' || $_SESSION['type'] == 'productowner')) {
    $data = getSirenList();
    $response = array(
        'success' => true,
        'data' => $data
    );

} else {
    $response = array(
        'success' => false,
        'message' => 'Impossible de récupérer la liste des sirens',
        "notLogged" => true
    );
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}



?>
<?php

session_start();




if (isset($_SESSION['num'])) {
    $response = array(
        'success' => true,
        'message' => 'L\'utilisateur est connecté',
        'id' => $_SESSION['num'],
        "notLogged" => true
    );
} else {
    $response = array(
        'success' => false,
        'message' => 'L\'utilisateur n\'est pas connecté',
        "notLogged" => true
    );
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);



?>
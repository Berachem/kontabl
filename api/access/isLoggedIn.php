<?php

session_start();




if (isset($_SESSION['num'])) {
    $response = array(
        'success' => true,
        'id' => $_SESSION['num'],
        "isLogged" => true
    );
} else {
    $response = array(
        'success' => false,
        'message' => 'L\'utilisateur n\'est pas connecté',
        "isLogged" => false
    );
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);



?>
<?php

session_start();

if (isset($_SESSION['user'])) {
    $response = array(
        'success' => true,
        'message' => 'L\'utilisateur est connecté',
        'id' => $_SESSION['id']
    );
} else {
    $response = array(
        'success' => false,
        'message' => 'L\'utilisateur n\'est pas connecté'
    );
}
header('Content-Type: application/json');
echo json_encode($response);



?>
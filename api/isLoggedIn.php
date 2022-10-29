<?php
    session_start();
    if (isset($_SESSION["id"])){
        $response = [
            "success" => true
        ];
        header('Content-Type: application/json');
        return json_encode($response);
        exit();
    }
    else{
        $response = [
            "success" => false
        ];
        header('Content-Type: application/json');
        return json_encode($response);
        exit();
    }
?>
<?php

// Prend en GET uniquement le numéro de siren
// renvoie dans un json un tableau contenant les informations de toutes les transactions du marchand

// GET:
// -numSiren (must) in table merchant_temp

if (isset($_GET["numSiren"])){
    $sql="SELECT * FROM transaction WHERE numSiren = :numSiren";
    $param = array(
        array(':numSiren', $_GET['numSiren'], PDO::PARAM_STR),
    );
    $transactions = $db->q($sql, $param);
    if ($transactions){
        $response = [
            "success" => true,
            "transactions" => $transactions
        ];
    }else{
        $response = [
            "success" => true,
            "transactions" => []
        ];
    }
}else{
    $response = [
        "success" => false,
        "error" => "Paramètre manquant"
    ];
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);




?>
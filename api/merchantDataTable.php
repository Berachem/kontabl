<?php

/*
GET:
 - date début (optional)
 - date fin (optional)

Return a JSON object with the following parameters:
    - success
    - error 
    OR 
    [
        [
            "NumSiren" => "string", ex: "123456789"
            "RaisonSociale" => "string", ex: "Société X"
            "NombreTransactions" => "int", ex: 5
            "Devise"=> "string", ex: "EUR"
            "MontantTotal" => "int", ex: 123 450

        ],...
        
    ]
*/
if (isset($_SESSION["num"]) && isset($_SESSION["type"])=="utilisateur"){ 
    global $db;
    $numSiren = $_SESSION["num"];
    $dateDebut = isset($_GET["dateDebut"]) ? $_GET["dateDebut"] : null;
    $dateFin = isset($_GET["dateFin"]) ? $_GET["dateFin"] : null;

    $sql = "SELECT raisonSociale, siren, transaction.currency, COUNT(*) AS nbTransaction, SUM(amount) AS totalAmount FROM merchant JOIN transaction ON numSiren = siren WHERE numSiren = :numSiren;";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    if ($dateDebut){
        $sql .= " AND dateTransaction < :date";
        array_push($cond,array(":date", $dateDebut));
    }
    if ($dateFin){
        $sql .= " AND dateTransaction > :date";
        array_push($cond,array(":date", $dateFin));
    }
    $response = $db->q($sql, $cond);
    $response = $response[0]; // pour avoir la data 
    $response = array(array(
        "raisonSociale" => $response->raisonSociale,
        "siren" => $response->siren,
        "currency" => $response->currency,
        "nbTransaction" => $response->nbTransaction,
        "totalAmount" => $response->totalAmount,
    ));
    $response = [
        "success" => true,
        "data" => $response 
    ];
} else{
    $response = [
        "success" => false,
        "error" => "Vous n'êtes pas connecté"
    ];
}
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>

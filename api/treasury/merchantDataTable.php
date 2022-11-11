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



if (isset($_SESSION["num"]) && $_SESSION["type"]=="user"){ 
    global $db;
    $numSiren = $_SESSION["num"];
    $dateDebut = isset($_GET["dateDebut"]) ? $_GET["dateDebut"] : null;
    $dateFin = isset($_GET["dateFin"]) ? $_GET["dateFin"] : null;

    $sql = "SELECT raisonSociale, siren, transaction.currency, COUNT(siren) AS nbTransaction, SUM(amount) AS totalAmount FROM merchant JOIN transaction ON numSiren = siren WHERE numSiren = :numSiren GROUP BY raisonSociale, siren, transaction.currency;";
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
    $result = $db->q($sql, $cond);

    if ($result){
        $result = $result[0]; // pour avoir la data 
        $responseData = array(array(
            "raisonSociale" => $result->raisonSociale,
            "siren" => $result->siren,
            "currency" => $result->currency,
            "nbTransaction" => $result->nbTransaction,
            "totalAmount" => $result->totalAmount,
        ));
        $response = [
            "success" => true,
            "data" => $responseData 
        ];
    }else{
        $response = [
            "success" => false,
            "error" => "Aucune donnée trouvée"
        ];
    }

} else{
    $response = [
        "success" => false,
        "error" => "Vous n'êtes pas connecté",
        "notLogged" => true
    ];
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>

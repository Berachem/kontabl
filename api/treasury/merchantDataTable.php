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

    $sql = "SELECT raisonSociale, siren, transaction.currency, COUNT(siren) AS nbTransaction FROM merchant JOIN transaction ON numSiren = siren WHERE idLogin = :idLogin";

    $sqlnegative = "SELECT SUM(amount) AS totalAmount FROM merchant JOIN transaction ON numSiren = siren JOIN discount ON discount.numTransaction = idTransaction WHERE idLogin = :idLogin AND sens = '-'";
    $sqlpositive = "SELECT SUM(amount) AS totalAmount FROM merchant JOIN transaction ON numSiren = siren JOIN discount ON discount.numTransaction = idTransaction WHERE idLogin = :idLogin AND sens = '+'";

    $cond = array(
        array(":idLogin", $numSiren)
    );
    if ($dateDebut){
        $sql .= " AND dateTransaction < :date";
        $sqlnegative .= " AND dateTransaction < :date";
        $sqlpositive .= " AND dateTransaction < :date";
        array_push($cond,array(":date", $dateDebut));
    }
    if ($dateFin){
        $sql .= " AND dateTransaction > :date";
        $sqlnegative .= " AND dateTransaction > :date";
        $sqlpositive .= " AND dateTransaction > :date";
        array_push($cond,array(":date", $dateFin));
    }

    $sql .= " GROUP BY raisonSociale, siren, transaction.currency;";

    $result = $db->q($sql, $cond);
    $resultpositive = $db->q($sqlpositive, $cond);
    $resultnegative = $db->q($sqlnegative, $cond);



    if ($result){
        $result = $result[0]; // pour avoir la data 
        $responseData = array(array(
            "RaisonSociale" => $result->raisonSociale,
            "numSiren" => $result->siren,
            "Devise" => $result->currency,
            "NombreTransaction" => $result->nbTransaction,
            "MontantTotal" => $resultpositive[0]->totalAmount-$resultnegative[0]->totalAmount,
        ));
        $response = [
            "success" => true,
            "data" => $responseData 
        ];
    }else{
        $response = [
            "success" => true,
            "data" => array()
        ];
    }

} else{
    $response = [
        "success" => false,
        "error" => "Vous n'avez pas les droits.",
        "notLogged" => true
    ];
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>

<?php
session_start();
/*
GET:
 - numSiren (optional)
 - raisonSociale (optional)
 - date (optional)

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
include("include/Functions.inc.php");


function getNbTransactions($db, $numSiren, $date){
    $sql = "SELECT COUNT(*) nombre FROM transaction WHERE numSiren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    if ($date){
        $sql .= " AND dateTransaction = :date";
        $cond[] = array(":date", $date);
    }
    $result = $db->q($sql, $cond);
    return $result[0]->nombre;
}

function getMontantTotal($db, $numSiren, $date){
    $sql = "SELECT SUM(amount) somme FROM transaction WHERE numSiren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    if ($date){
        $sql .= " AND dateTransaction = :date";
        array_push($cond,array(":date", $date));
    }
    $result = $db->q($sql, $cond);
    if ($result[0]->somme == null){
        return 0;
    }
    return $result[0]->somme;
}


function getDevise($db, $numSiren){
    // get the currency of the merchant
    $sql = "SELECT currency FROM merchant WHERE siren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->currency;
}

if (isset($_SESSION["id"]) && isset($_SESSION["type"])=="productowner"){ 
    // check GET parameters
    $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
    $raisonSociale = isset($_GET["raisonSociale"]) ? $_GET["raisonSociale"] : null;
    $date = isset($_GET["date"]) ? $_GET["date"] : null;
    $data = array();


    // get the merchants
    $sql = "SELECT * FROM merchant";
    $cond = array();
    if ($numSiren){
        $sql .= " WHERE siren = :numSiren";
        array_push($cond, array(":numSiren", $numSiren));
    }
    if ($raisonSociale){
        if ($numSiren){
            $sql .= " AND name LIKE :raisonSociale";
        } else {
            $sql .= " WHERE name LIKE :raisonSociale";
        }
        array_push($cond, array(":raisonSociale", "%".$raisonSociale."%"));
    }
    $result = $db->q($sql, $cond);

    // get the data
    foreach ($result as $merchant){
        $data[] = array(
            "NumSiren" => $merchant->siren,
            "RaisonSociale" => $merchant->name,
            "NombreTransactions" => getNbTransactions($db, $merchant->siren, $date),
            "Devise"=> getDevise($db, $merchant->siren),
            "MontantTotal" => getMontantTotal($db, $merchant->siren, $date)
        );
    }

    // return the response

    header('Content-Type: application/json');
    echo json_encode($data);
    return json_encode($data);
    exit();


}
else{
    $response = [
        "success" => false,
        "error" => "You are not logged in as a product owner"
    ];
    header('Content-Type: application/json');
    return json_encode($response);
    exit();
}




?>
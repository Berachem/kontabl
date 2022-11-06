<?php 
session_start();


/*
GET:
 - numSiren (optional)
 - raisonSociale (optional)
 - dateValeurDebut (optional)
 - dateValeurFin (optional)
 - numDossierImpayé (optional)

Return a JSON object with the following parameters:
    - success
    - error 
    OR 
    [
        [
            "NumSiren" => "string", ex: "123456789"
            "DateVente" => "string", ex: "2019-01-01"
            "DateRemise" => "string", ex: "2019-02-01"
            "NumCarte" => "string", ex: "1234567890123456"
            "Reseau" => "string", ex: "VISA"
            "numDossierImpayé" => "string", ex: "1234567890123456"
            "Devise"=> "string", ex: "EUR"
            "Montant" => "int", ex: - 123 450
            "LibImpayé" => "string", ex: "Impayé"
        ],...
        
    ]
*/


include("include/Functions.inc.php");

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
    //check GET parameters
    $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
    $raisonSociale = isset($_GET["raisonSociale"]) ? $_GET["raisonSociale"] : null;
    $dateValeurDebut = isset($_GET["dateValeurDebut"]) ? $_GET["dateValeurDebut"] : null;
    $dateValeurFin = isset($_GET["dateValeurFin"]) ? $_GET["dateValeurFin"] : null;
    $numDossierImpayé = isset($_GET["numDossierImpayé"]) ? $_GET["numDossierImpayé"] : null;

    $data = array();

    //get the unpaid transactions

    $sql = "SELECT * FROM transactions WHERE sens = '-'";
    $cond = array();

    if ($numSiren){
        $sql .= " WHERE siren = :numSiren";
        array_push($cond, array(":numSiren", $numSiren));
    }

    $result = $db->q($sql, $cond);

    foreach ($result as $unpaid){
        $data[] = array(
            "NumSiren" => $unpaid->siren,
            // "DateVente" => $unpaid->dateTransaction,
            // "DateRemise" => $unpaid->dateValeur,
            // "NumCarte" => $unpaid->cardNumber,
            // "Reseau" => $unpaid->cardNetwork,
            // "numDossierImpayé" => $unpaid->numDossierImpayé,
            "Devise"=> getDevise($db, $unpaid->siren),
            // "Montant" => $unpaid->amount,
            // "LibImpayé" => $unpaid->libImpayé
        );
    }

    // return the reponse 

    header('Content-Type: application/json');
    echo json_encode($data);
    return json_encode($data);
    exit();

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
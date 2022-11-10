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
            "numRemise" => "int", ex: 5
            "NumCarte" => "string", ex: "1234567890123456"
            "Reseau" => "string", ex: "VISA"
            "numDossierImpayé" => "string", ex: "1234567890123456"
            "Devise"=> "string", ex: "EUR"
            "Montant" => "int", ex: - 123 450
            "LibImpayé" => "string", ex: "Impayé"
        ],...
        
    ]
*/


include("discountDataTable.php");

function getDateVente($db, $numRemise){
    $sql = "SELECT dateTransaction FROM transaction,discount WHERE numTransaction = idTransaction AND numRemise = :numRemise";
    $cond = array(
        array(":numSiren", $numRemise)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->dateVente;
}

function getDateRemise($db,$numRemise ){
    $sql = "SELECT dateDiscount FROM discount WHERE numDiscount = :numRemise";
    $cond = array(
        array(":numRemise", $numRemise)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->dateRemise;
}



function getNumCarte($db, $numSiren){
    $sql = "SELECT numCarte FROM merchant WHERE siren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->numCarte;
}

function getReseau($db, $numSiren){
    $sql = "SELECT reseau FROM merchant WHERE siren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->reseau;
}


function getUnpaidFile($db, $numRemise) {
    $sql = "SELECT numUnpaidFile FROM discount WHERE numDiscount = :numRemise";
    $cond = array(
        array(":numRemise", $numRemise)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->numUnpaidFile;
}


function getLibImpaye($db, $numRemise) {
    $sql = "SELECT unpaidWording FROM discount WHERE numDiscount = :numRemise";
    $cond = array(
        array(":numRemise", $numRemise)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->libUnpaidFile;
}




function getMontant($db, $numRemise){
    $sql = "SELECT amount FROM transaction,discount WHERE numTransaction = idTransaction AND numRemise = :numRemise";
    $cond = array(
        array(":numRemise", $numRemise)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->amount;
}



$finaldata = array();
$data = array();

include("discountDataTable.php");



if ($response["success"] == true){
    // filtrer la réponse en gardant que les lignes dont le sens est "impayé"
    $data = array_filter((array) $response["data"], function($row){
        return $row["sens"] == "-";
    });


    // renvoie le json avec le numSiren, la dateVente, la dateRemise, le numCarte, le reseau, le numDossierImpayé, la devise, le montant et le libImpayé

    foreach ($data as $row){
        $finaldata[] = array(
            "NumSiren" => $row["numSiren"], 
            "DateVente" => getDateVente($db, $row["Numero de remise"]), 
            "DateRemise" => getDateRemise($db, $row["Numero de remise"]), 
            "NumCarte" => getNumCarte($db, $row["numSiren"]), 
            "Reseau" => getReseau($db, $row["numSiren"]),
            "numDossierImpayé" => getUnpaidFile($db, $row["Numero de remise"]),
            "Devise"=> $row["devise"],
            "Montant" => getMontant($db, $row["Numero de remise"]),
            "LibImpayé" => getLibImpaye($db, $row["Numero de remise"])
        );
        
    }

    $response = [
        "success" => true,
        "data" => $finaldata
    ];
    header('Content-Type: application/json');
    return json_encode($response);
    exit();


}






?>
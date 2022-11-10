<?php 


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
            "DateDiscount" => "string", ex: "2019-02-01"
            "numDiscount" => "int", ex: 5
            "NumCarte" => "string", ex: "1234567890123456"
            "Reseau" => "string", ex: "VISA"
            "numDossierImpayé" => "string", ex: "1234567890123456"
            "currency"=> "string", ex: "EUR"
            "Montant" => "int", ex: - 123 450
            "LibImpayé" => "string", ex: "Impayé"
        ],...
        
    ]
*/


$inUnpaidDinscountDataTable = true;

include("discountDataTable.php");
function getDateVente($numDiscount){
    global $db;
    $sql = "SELECT dateTransaction FROM transaction,discount WHERE numTransaction = idTransaction AND numDiscount = :numDiscount";
    $cond = array(
        array(":numDiscount", $numDiscount)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->dateTransaction;
}

function getDateDiscount($numDiscount ){
    global $db;
    $sql = "SELECT dateDiscount FROM discount WHERE numDiscount = :numDiscount";
    $cond = array(
        array(":numDiscount", $numDiscount)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->dateDiscount;
}



function getNumCarte($numSiren){
    global $db;
    $sql = "SELECT numCarte FROM merchant WHERE siren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->numCarte;
}

function getReseau($numSiren){
    global $db;
    $sql = "SELECT network FROM merchant WHERE siren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->network;
}


function getUnpaidFile($numDiscount) {
    global $db;
    $sql = "SELECT numUnpaidFile FROM discount WHERE numDiscount = :numDiscount";
    $cond = array(
        array(":numDiscount", $numDiscount)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->numUnpaidFile;
}


function getLibImpaye($numDiscount) {
    global $db; 
    $sql = "SELECT unpaidWording FROM discount WHERE numDiscount = :numDiscount";
    $cond = array(
        array(":numDiscount", $numDiscount)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->unpaidWording;
}




function getMontant($numDiscount){
    global $db; 
    $sql = "SELECT amount FROM transaction,discount WHERE numTransaction = idTransaction AND numDiscount = :numDiscount";
    $cond = array(
        array(":numDiscount", $numDiscount)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->amount;
}



$finaldata = array();
$data = array();


if ($response["success"] == true){
    //echo "lol";
    // filtrer la réponse en gardant que les lignes dont le sens est "impayé"
    $data = array_filter((array) $response["data"], function($row){
        return $row["Sens"] == '-';
    });


    // renvoie le json avec le numSiren, la dateVente, la dateDiscount, le numCarte, le reseau, le numDossierImpayé, la currency, le montant et le libImpayé

    foreach ($data as $row){
        $finaldata[] = array(
            "NumSiren" => $row["NumSiren"], 
            "DateVente" => getDateVente($row["numDiscount"]), 
            "DateDiscount" => getDateDiscount($row["numDiscount"]), 
            "NumCarte" => getNumCarte($row["NumSiren"]), 
            "Reseau" => getReseau($row["NumSiren"]),
            "numDossierImpayé" => getUnpaidFile($row["numDiscount"]),
            "currency"=> $row["Currency"],
            "Montant" => getMontant($row["numDiscount"]),
            "LibImpayé" => getLibImpaye($row["numDiscount"])
        );
        
    }

    $response = [
        "success" => true,
        "data" => $finaldata
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();


}






?>
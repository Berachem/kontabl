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


include("include/Functions.inc.php");


function getDateVente($numRemise){
    global $db;
    $sql = "SELECT dateTransaction FROM transaction,discount WHERE numTransaction = idTransaction AND numDiscount = :numRemise";
    $cond = array(
        array(":numRemise", $numRemise)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->dateTransaction;
}

function getDiscountDetails($numRemise){
    global $db;
    $sql = "SELECT dateDiscount,numUnpaidFile, unpaidWording  FROM discount  WHERE numDiscount = :numRemise";
    $cond = array(
        array(":numRemise", $numRemise)
    );
    $result = $db->q($sql, $cond);
    return $result[0];
}

function getTransactionDetails($numRemise){
    global $db;
    $sql = "SELECT endingFoursCardNumbers, amount FROM transaction,discount  WHERE  numTransaction = idTransaction AND numDiscount = :numRemise";
    $cond = array(
        array(":numRemise", $numRemise)
    );
    $result = $db->q($sql, $cond);
    return $result[0];
}

function getNetworkMerchant($numSiren){
    global $db;
    $sql = "SELECT network FROM merchant WHERE siren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->network;
}



// IL FAUT REUTILISER LE FICHIER DISCOUNTDATATABLE.
// (tu as un attribut "sens", donc tu pourras filtrer)


if(isset($_SESSION['num'])){
    if ($_SESSION["type"] == "productowner" || $_SESSION["type"] == "admin") {
        $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
        $raisonSociale = isset($_GET["raisonSociale"]) ? $_GET["raisonSociale"] : null;
    } else {
        $numSiren = $_SESSION['num'];
        $raisonSociale = null;
    }

        $date_debut = isset($_GET["date_debut"]) ? $_GET["date_debut"] : null;
        $date_fin = isset($_GET["date_fin"]) ? $_GET["date_fin"] : null;
        $sens= "-";
        $numDossierImpaye = isset($_GET["numDossierImpaye"]) ? $_GET["numDossierImpaye"] : null;
        $data = array();

        $tmpData = getDiscounts($numSiren, $raisonSociale, $date_debut, $date_fin, $sens, $numDossierImpaye);



    // renvoie le json avec le numSiren, la dateVente, la dateRemise, le numCarte, le reseau, le numDossierImpayé, la devise, le montant et le libImpayé
    $data = array();
    foreach ($tmpData as $row){
        array_push($data, array(
            "NumSiren" => $row["NumSiren"],
            "DateVente" => getDateVente($row["NumeroRemise"]),
            "DateRemise" => getDiscountDetails($row["NumeroRemise"])->dateDiscount,
            "NumCarte" => getTransactionDetails($row["NumeroRemise"])->endingFoursCardNumbers,
            "Reseau" => getNetworkMerchant($row["NumSiren"]),
            "numDossierImpayé" => getDiscountDetails($row["NumeroRemise"])->numUnpaidFile,
            "Devise"=> $row["Devise"],
            "Montant" => getTransactionDetails($row["NumeroRemise"])->amount,
            "LibImpayé" => getDiscountDetails($row["NumeroRemise"])->unpaidWording
        ));
    }

    $response = array(
        "success" => true,
        "data" => $data
    );


   
}
else{
    $response = array(
        "success" => false,
        "error" => "Vous n'êtes pas connecté",
        "notLogged" => true
    );
}

header('Content-Type: application/json ; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);





?>
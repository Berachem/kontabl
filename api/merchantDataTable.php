<?php
session_start();
$_SESSION["num"] = 384560942;
$_SESSION["type"] = "utilisateur";
/*
GET:
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
if (isset($_SESSION["num"]) && isset($_SESSION["type"])=="utilisateur"){ 
    global $db;
    $numSiren = $_SESSION["num"];
    $date = isset($_GET["date"]) ? $_GET["date"] : null;

    $sql = "SELECT raisonSociale, siren, transaction.currency, COUNT(*) AS nbTransaction, SUM(amount) AS totalAmount FROM merchant JOIN transaction ON numSiren = siren WHERE numSiren = :numSiren;";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    if ($date){
        $sql .= " AND dateTransaction = :date";
        array_push($cond,array(":date", $date));
    }
    $response = $db->q($sql, $cond);
    $response = $response[0]; // pour avoir la data
} else{
    $response = [
        "success" => false,
        "error" => "You are not logged in as a user"
    ];
}
header('Content-Type: application/json');
echo json_encode($response);
return json_encode($response);
exit();
?>

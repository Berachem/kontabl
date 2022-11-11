<?php
session_start();
$session_num=347662454;
$session_type='productowner';
/*
GET:
 - numSiren (optional)
 - raisonSociale (optional)
 - date_debut (optional)
 - date_fin (optional)

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

if($session_type){
    if ($session_type == "productowner") {
        $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
        $raisonSociale = isset($_GET["raisonSociale"]) ? $_GET["raisonSociale"] : null;
    } else {
        $numSiren = $session_num;
        $raisonSociale = null;
    }

        $date_debut = isset($_GET["date_debut"]) ? $_GET["date_debut"] : null;
        $date_fin = isset($_GET["date_fin"]) ? $_GET["date_fin"] : null;
        $sens=isset($_GET["sens"]) ? $_GET["sens"] : null;
        $data = array();

        $data = getDiscounts($numSiren, $raisonSociale, $date_debut, $date_fin, $sens);

        $response = array(
            "success" => true,
            "data" => $data
        );
} else{
    $response = [
        "success" => false,
        "error" => "You are not logged in"
    ];
}

header('Content-Type: application/json');
echo json_encode($response);

?>
<?php
// session_start();




/*
GET:
 - numSiren (optional)
 - raisonSociale (optional)
 - date_debut (optional)
 - date_fin (optional)
 - numRemise (optional)

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

if(isset($_SESSION['num'])){
    if ($_SESSION["type"] == "productowner" ) {
        $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
        $raisonSociale = isset($_GET["raisonSociale"]) ? $_GET["raisonSociale"] : null;
       
    } else {
        $numSiren = $_SESSION['num'];
        $raisonSociale = null;
    }

        $date_debut = isset($_GET["date_debut"]) ? $_GET["date_debut"] : null;
        $date_fin = isset($_GET["date_fin"]) ? $_GET["date_fin"] : null;
        $sens=isset($_GET["sens"]) ? $_GET["sens"] : null;
        $numRemise = isset($_GET["numRemise"]) ? $_GET["numRemise"] : null;
        $data = array();


        $data = getDiscounts($numSiren, $raisonSociale, $date_debut, $date_fin, $sens,null);
        //var_dump($data);
        if ($numRemise){
            // On filtre les données pour ne garder que celles qui correspondent au numéro de remise
            $data = array_filter($data, function($item) use ($numRemise){
                //var_dump($item);
                return $item["NumeroRemise"] == $numRemise;
            });
        }


        $response = array(
            "success" => true,
            "data" => $data
        );
} else{
    $response = [
        "success" => false,
        "error" => "Vous n'avez pas les droits.",
        "notLogged" => true
    ];
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);

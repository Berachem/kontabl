<?php
// session_start();

/*
GET:
-numSiren (must) in table merchant_temp

*/

// check if it is the productowner
/*
$_SESSION['type']= "productowner";
$_GET["numSiren"] = "154685474";
*/

if (isset($_SESSION['type']) && $_SESSION['type'] == 'productowner' && isset($_GET["numSiren"])) {
    
    // transfer the merchant from merchant_temp to merchant
    // merchant : name, siren, currency, numCarte, network,password, idLogin 

    $param = array(
        array(':siren', $_GET['numSiren'], PDO::PARAM_STR),
    );
    $merchant = $db->q("SELECT * FROM merchant_temp WHERE siren = :siren;", $param);

    if ($merchant) {
        // insert the merchant in the merchant table
        $param = array(
            array(':raisonSociale', $merchant[0]->raisonSociale, PDO::PARAM_STR),
            array(':siren', $merchant[0]->siren, PDO::PARAM_STR),
            array(':currency', $merchant[0]->currency, PDO::PARAM_STR),
            array(':numCarte', $merchant[0]->numCarte, PDO::PARAM_STR),
            array(':network', $merchant[0]->network, PDO::PARAM_STR),
            array(':password', $merchant[0]->password, PDO::PARAM_STR),
            array(':idLogin', $merchant[0]->idLogin, PDO::PARAM_STR),
        );
        $db->q("INSERT INTO merchant (raisonSociale, siren, currency, numCarte, network, password, idLogin) VALUES (:raisonSociale, :siren, :currency, :numCarte, :network, :password, :idLogin);", $param);

        // delete the merchant from the merchant_temp table
        $param = array(
            array(':siren', $_GET['numSiren'], PDO::PARAM_STR),
        );
        $db->q("DELETE FROM merchant_temp WHERE siren = :siren;", $param);

        $response = [
            "success" => true,
            "numSiren" => $_GET['numSiren']
        ];
    } else {
        $response = [
            "success" => false,
            "error" => "Le numéro de SIREN n'existe pas dans la table temporaire"
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);




}else{
    $response = [
        "success" => false,
        "error" => "Vous n'êtes pas productowner"
    ];
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

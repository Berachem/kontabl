<?php
// session_start();
/*
Return a JSON object with the following parameters for each user:
    "NumSiren" => "string", ex: "123456789"
    "RaisonSociale" => "string", ex: "Société X"
    and a succes
*/

if (isset($_SESSION["num"]) && ($_SESSION["type"] == "productowner")) {

    $users = $db->q("SELECT raisonSociale, siren, currency, network, numCarte, idLogin FROM merchant_temp;");

    $data = array();
    foreach ($users as $user) {
        array_push($data, array(
            "NumSiren" => $user->siren,
            "RaisonSociale" => $user->raisonSociale,
            "Currency" => $user->currency,
            "Network" => $user->network,
            "NumCarte" => $user->numCarte,
            "IdLogin" => $user->idLogin,
        ));
    }

    // return the response
    $response = array(
        "success" => true,
        "data" => $data
    );
} else {
    // return the response
    $response = array(
        "success" => false,
        "error" => "Vous n'avez pas les droits. en tant que PO",
    );
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);

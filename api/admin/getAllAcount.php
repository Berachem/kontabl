<?php   
session_start();
/*
Return a JSON object with the following parameters for each user:
    "NumSiren" => "string", ex: "123456789"
    "RaisonSociale" => "string", ex: "Société X"
    and a succes
*/

function getAllAcount($db) {
    if (isset($_SESSION["num"]) && ($_SESSION["type"]=="admin")){  

        $users = $db -> q("SELECT raisonSociale, siren FROM merchant;"); 

        $data = array();
        foreach ($users as $user){
            array_push($data,array(
                "NumSiren" => $user->siren,
                "RaisonSociale" => $user->raisonSociale,
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
            "error" => "not loged like an admin"
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

?>
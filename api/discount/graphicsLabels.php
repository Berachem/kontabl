<?php

/*
Renvoie un json avec une liste de couple (label, nombre d'impayés)

*/

session_start();




if(isset($_SESSION['num'])){
    if ($_SESSION["type"] == "productowner" ) {
        $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
    } else {
        $numSiren = $_SESSION['num'];
    }

        $param = array();
        if ($numSiren){
            // on récupère tous les impayés sur la base de données
        $sql = "SELECT unpaidWording FROM discount, transaction WHERE numTransaction = idTransaction AND numSiren = :numSiren AND sens = '-' ";

        $param = array(
            array(':numSiren', $numSiren, PDO::PARAM_STR),
        
        );
        }
        else{
            $sql = "SELECT unpaidWording FROM discount, transaction WHERE numTransaction = idTransaction  AND sens = '-'";
        }
        $result = $db->q($sql, $param);

        
        
        
        $data = array();

        // foreach 
        foreach ($result as $unPaidLabel) {
            if (isset($data[$unPaidLabel->unpaidWording])) {
                $data[$unPaidLabel->unpaidWording] += 1;
            } else {
                $data[$unPaidLabel->unpaidWording] = 1;
            }
            
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

header('Content-Type: application/json ; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);


?>
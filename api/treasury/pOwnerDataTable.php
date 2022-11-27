<?php

/*
GET:
 - numSiren (optional)
 - raisonSociale (optional)
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






function getNbTransactionsByOneDate($db, $numSiren, $date){
    $sql = "SELECT COUNT(*) nombre FROM transaction WHERE numSiren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    if ($date){
        $sql .= " AND dateTransaction = :date";
        array_push($cond, array(":date", $date));
    }
    $result = $db->q($sql, $cond);
    return $result[0]->nombre;
}

function getMontantTotalByOneDate($db, $numSiren, $date){
    $sqlpositive = "SELECT SUM(amount) somme FROM transaction JOIN discount ON discount.numTransaction = idTransaction WHERE numSiren = :numSiren AND sens = '+'";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    if ($date) {
        $sqlpositive .= " AND dateTransaction = :date";
        array_push($cond,array(":date", $date));
    }
    $resultpositive = $db->q($sqlpositive, $cond);
    $sqlnegative = "SELECT SUM(amount) somme FROM transaction JOIN discount ON discount.numTransaction = idTransaction WHERE numSiren = :numSiren AND sens = '-'";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    if ($date){
        $sqlnegative .= " AND dateTransaction = :date";
        array_push($cond,array(":date", $date));
    }
    $resultnegative = $db->q($sqlnegative, $cond);
    if ($resultnegative[0]->somme == null && $resultpositive[0]->somme == null){
        return 0;
    }
    return strval($resultpositive[0]->somme-$resultnegative[0]->somme); // parce que tout étéait en string donc j'ai mis en string
}




if (isset($_SESSION["num"]) && ($_SESSION["type"]=="productowner")){  
    // check GET parameters
    $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
    $raisonSociale = isset($_GET["raisonSociale"]) ? $_GET["raisonSociale"] : null;
    $date = isset($_GET["date"]) ? $_GET["date"] : null;
    $data = array();

   
    // get the merchants
    $sql = "SELECT * FROM merchant";
    $cond = array();
         if ($numSiren ){
            if (numSirenInDB($numSiren)){
                
                array_push($cond, array(":numSiren", $numSiren));
            }
            else{
                array_push($cond, array(":numSiren", "0"));
            }
            $sql .= " WHERE siren = :numSiren";
        
        }

    
    if ($raisonSociale){
        if ($numSiren){
            $sql .= " AND raisonSociale LIKE :raisonSociale";
        } else {
            $sql .= " WHERE raisonSociale LIKE :raisonSociale";
        }
        array_push($cond, array(":raisonSociale", "%".$raisonSociale."%"));
    }
    $result = $db->q($sql, $cond);

    if (!$result){
        echo json_encode(array(
            "success" => false,
            "error" => "Aucun marchand trouvé"
        ), JSON_UNESCAPED_UNICODE);
        exit();
        
    }

    // get the data
    foreach ($result as $merchant){
        array_push($data,array(
            "NumSiren" => $merchant->siren,
            "RaisonSociale" => $merchant->raisonSociale,
            "NombreTransactions" => getNbTransactionsByOneDate($db, $merchant->siren, $date),
            "Devise"=> getDevise($db, $merchant->siren),
            "MontantTotal" => getMontantTotalByOneDate($db, $merchant->siren, $date)
        ));
    }

    // return the response
    $response = array(
        "success" => true,
        "data" => $data
    );

}
else{
    $response = [
        "success" => false,
        "error" => "Vous n'avez pas les droits. en tant que productowner"
    ];

    
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);



?>
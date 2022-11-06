<?php
session_start();
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

function getNbTransactions($db, $numSiren, $date_debut, $date_fin){
    $sql = "SELECT COUNT(*) nombre FROM transaction WHERE numSiren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    if ($date_debut){
        $sql .= " AND dateTransaction >= :date_debut";
        $cond[] = array(":date_debut", $date_debut);
    }
    if ($date_fin){
        $sql .= " AND dateTransaction <= :date_fin";
        $cond[] = array(":date_fin", $date_fin);
    }
    $result = $db->q($sql, $cond);
    return $result[0]->nombre;
}

function getMontantTotal($db, $numSiren, $date_debut, $date_fin){
    $sql = "SELECT SUM(amount) somme FROM transaction WHERE numSiren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    if ($date_debut){
        $sql .= " AND dateTransaction >= :date_debut";
        $cond[] = array(":date_debut", $date_debut);
    }
    if ($date_fin){
        $sql .= " AND dateTransaction <= :date_fin";
        $cond[] = array(":date_fin", $date_fin);
    }
    $result = $db->q($sql, $cond);
    if ($result[0]->somme == null){
        return 0;
    }
    return $result[0]->somme;
}

function getDevise($db, $numSiren){
    // get the currency of the merchant
    $sql = "SELECT currency FROM merchant WHERE siren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->currency;
}

if (isset($_SESSION["id"])){
    $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
    $raisonSociale = isset($_GET["raisonSociale"]) ? $_GET["raisonSociale"] : null;
    $date_debut = isset($_GET["date_debut"]) ? $_GET["date_debut"] : null;
    $date_fin = isset($_GET["date_fin"]) ? $_GET["date_fin"] : null;
    $data = array();



    $sql = "SELECT * FROM transaction";
    $cond = array();
    if ($numSiren){
        $sql .= " WHERE siren = :numSiren";
        array_push($cond, array(":numSiren", $numSiren));
    }
    if ($raisonSociale){
        if ($numSiren){
            $sql .= " AND name LIKE :raisonSociale";
        } else {
            $sql .= " WHERE name LIKE :raisonSociale";
        }
        array_push($cond, array(":raisonSociale", "%".$raisonSociale."%"));
    }
    if ($date_debut){
        if ($numSiren || $raisonSociale){
            $sql .= " AND dateTransaction >= :date_debut";
        } else {
            $sql .= " WHERE dateTransaction >= :date_debut";
        }
        array_push($cond, array(":date_debut", $date_debut));
    }
    if ($date_fin){
        if ($numSiren || $raisonSociale || $date_debut){
            $sql .= " AND dateTransaction <= :date_fin";
        } else {
            $sql .= " WHERE dateTransaction <= :date_fin";
        }
        array_push($cond, array(":date_fin", $date_fin));
    }
    $result = $db->q($sql, $cond);

    $sql2="SELECT * FROM discount WHERE numTransaction = :numTransaction";
    $cond2 = array();
    foreach ($result as $row){
        $cond2 = array(
            array(":numTransaction", $row->numAuthorization)
        );
        $result2 = $db->q($sql2, $cond2);
        $row->discount = $result2;
    }
    foreach($result as $row){
        $data=array(
            "NumSiren" => $row->siren,
            "RaisonSociale" => $row->name,
            "Numero de remise"=>$row->discount->numDiscount,
            "Date Traitement"=>$row->discount->dateDiscount,
            "NombreTransactions" => getNbTransactions($db, $row->siren, $date_debut, $date_fin),
            "Devise"=> getDevise($db, $row->siren),
            "MontantTotal" => getMontantTotal($db, $row->siren, $date_debut, $date_fin),
            "Sens"=>$row->discount->sens
        );
        var_dump($data);
        header('Content-Type: application/json');
        echo json_encode($data);
        return json_encode($data);
        exit();
    }
} else{
    $response = [
        "success" => false,
        "error" => "You are not logged in"
    ];
    header('Content-Type: application/json');
    return json_encode($response);
    exit();
}
?>
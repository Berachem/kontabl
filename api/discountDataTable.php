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


function getNbTransactions($db, $numSiren){
    $sql = "SELECT COUNT(*) nombre FROM transaction WHERE numSiren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->nombre;
}

function getMontantTotal($db, $numSiren){
    $sql = "SELECT SUM(amount) somme FROM transaction WHERE numSiren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    if ($result[0]->somme == null){
        return 0;
    }
    return $result[0]->somme;
}

function getDevise($db, $numSiren){
    // get the currency of the merchant
    $sql = "SELECT currency FROM transaction WHERE numSiren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->currency;
}
if($user_type==null){
    $response = [
        "success" => false,
        "error" => "You are not logged in"
    ];
    header('Content-Type: application/json');
    return json_encode($response);
    exit();
}
else{
    if($_SESSION["id"]=="productowner"){
        $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
        $raisonSociale = isset($_GET["raisonSociale"]) ? $_GET["raisonSociale"] : null;
    }else{
        $numSiren = $_SESSION["numSiren"];
        $raisonSociale = null;
    }
    $date_debut = isset($_GET["date_debut"]) ? $_GET["date_debut"] : null;
    $date_fin = isset($_GET["date_fin"]) ? $_GET["date_fin"] : null;
        $data = array();
        $sql = "SELECT * FROM transaction";
        $cond = array();
        if ($raisonSociale){
            $sql4="SELECT siren FROM merchant WHERE raisonSociale LIKE :raisonSociale";
            $cond4=array(
                array(":raisonSociale", "%".$raisonSociale."%")
            );
            $result4=$db->q($sql4, $cond4);
            if($result4){
                $numSiren=$result4[0]->siren;
            }
        }
        if ($numSiren){
            $sql .= " WHERE numSiren = :numSiren";
            array_push($cond, array(":numSiren", $numSiren));
        }
        $result = $db->q($sql, $cond);
        foreach($result as $row){
            $trans=array();
            $trans["NumSiren"] = $row->numSiren;
            $sql2 = "SELECT raisonSociale FROM merchant WHERE siren = :numSiren";
            $cond2 = array(array(":numSiren", $row->numSiren));
            $result2 = $db->q($sql2, $cond2);
            $trans["RaisonSociale"] = $result2[0]->raisonSociale;
            $sql3="SELECT * FROM discount WHERE numTransaction = :numTransaction";
            $cond3 = array(array(":numTransaction", $row->idTransaction));
            if ($date_debut){
                $sql3 .= " AND dateDiscount >= :date_debut";
                array_push($cond3, array(":date_debut", $date_debut));
            }
            if ($date_fin){
                $sql3 .= " AND dateDiscount <= :date_fin";
                array_push($cond3, array(":date_fin", $date_fin));
            }
            $result3 = $db->q($sql3, $cond3);
            if ($result3){
                $trans["numRemise"]=$result3[0]->numDiscount;
                $trans["dateRemise"]=$result3[0]->dateDiscount;
            }
            else{
                continue;
            }
            $trans["NombreTransactions"] = getNbTransactions($db, $row->numSiren);
            $trans["Devise"] = getDevise($db, $row->numSiren);
            $trans["MontantTotal"] = getMontantTotal($db, $row->numSiren);
            if ($result3){
                $trans["Sens"]=$result3[0]->sens;
            }
            array_push($data, $trans);
        }
        // return the response
    $response = array(
        "success" => true,
        "data" => $data
    );

    header('Content-Type: application/json');
    //echo json_encode($response);
    return json_encode($response);
    exit();
    }
?>
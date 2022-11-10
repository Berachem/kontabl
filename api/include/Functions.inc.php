<?php

function getSirenList() {
    global $db;
    // only if user is logged in and is a product owner
    if (isset($_SESSION['num']) && $_SESSION['type'] == 'productowner') {
        $sql = "SELECT * FROM merchant";
        $result = $db->q($sql);
        $sirenList = [];
        foreach ($result as $merchant) {
            $sirenList[] = $merchant->siren;
        }
        return $sirenList;
    }else{
        // return his own siren
        $sql = "SELECT * FROM merchant WHERE idLogin = :idLogin";
        $cond = array(
            array(":idLogin", $_SESSION['num'])
        );
        $hisSiren = $db->q($sql, $cond);
        return $hisSiren;
    }

}

function getSocialReason(){
    global $db;
    // only if user is logged in and is a product owner
    if (isset($_SESSION['num']) && $_SESSION['type'] == 'productowner') {
        $sql = "SELECT * FROM merchant";
        $result = $db->q($sql);
        $socialReasonList = [];
        foreach ($result as $merchant) {
            $socialReasonList[] = $merchant->raisonSociale;
        }
        return $socialReasonList;
    }else{
        // return his own social reason
        $sql = "SELECT * FROM merchant WHERE numSiren = :numSiren";
        $cond = array(
            array(":numSiren", $_SESSION['num'])
        );
        $hisSocialReason = $db->q($sql, $cond);
        return $hisSocialReason[0]->raisonSociale;
    }
}

function numSirenInDB( $numSiren){
    global $db;
    $sql = "SELECT * FROM merchant WHERE siren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return count($result) > 0;
}


function getDiscounts($numSiren, $raisonSociale, $date_debut, $date_fin, $sens, $numUnpaidFile){
    global $db;
    $sql = "SELECT * FROM transaction";
    $cond = array();
    if ($numSiren){
        $sql .= " WHERE numSiren = :numSiren";
        array_push($cond, array(":numSiren", $numSiren));
    }
    if ($date_debut){
        if ($numSiren){
            $sql .= " AND dateTransaction >= :date_debut";
        } else {
            $sql .= " WHERE dateTransaction >= :date_debut";
        }
        array_push($cond, array(":date_debut", $date_debut));
    }
    if ($date_fin){
        if ($numSiren || $date_debut){
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

    if ($sens){
        $sql2.=" AND sens LIKE :sens";
        array_push($cond2, array(":sens", $sens));
    }

    if ($numUnpaidFile){
        $sql2.=" AND numUnpaidFile = :numUnpaidFile";
        array_push($cond2, array(":numUnpaidFile", $numUnpaidFile));
    }

    $sql3 = "SELECT name FROM merchant WHERE siren = :numSiren";
    $cond3 = array();
    foreach ($result as $row){
        $cond3 = array(
            array(":numSiren", $row->numSiren)
        );
        $result3 = $db->q($sql3, $cond3);
        $row->merchant = $result3;
    }
    foreach($result as $row){
        array_push($data,array(
            "NumSiren" => $row->numSiren,
            "RaisonSociale" => $row->merchant[0]->name,
            "Numero de remise"=>$row->discount[0]->numDiscount,
            "Date Traitement"=>$row->discount[0]->dateDiscount,
            "NombreTransactions" => getNbTransactions($db, $row->numSiren, $date_debut, $date_fin),
            "Devise"=> getDevise($db, $row->numSiren),
            "MontantTotal" => getMontantTotal($db, $row->numSiren, $date_debut, $date_fin),
            "Sens"=>$row->discount[0]->sens
        ));
    }
    return $data;
}



?>
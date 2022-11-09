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



?>
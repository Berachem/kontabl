<?php

function getSirenList() {
    global $db;
    // only if user is logged in and is a product owner
    if (isset($_SESSION['numSiren']) && $_SESSION['type'] == 'productowner') {
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
            array(":idLogin", $_SESSION['numSiren'])
        );
        $hisSiren = $db->q($sql, $cond);
        return $hisSiren;
    }

}

function getSocialReason(){
    global $db;
    // only if user is logged in and is a product owner
    if (isset($_SESSION['numSiren']) && $_SESSION['type'] == 'productowner') {
        $sql = "SELECT * FROM merchant";
        $result = $db->q($sql);
        $socialReasonList = [];
        foreach ($result as $merchant) {
            $socialReasonList[] = $merchant->name;
        }
        return $socialReasonList;
    }else{
        // return his own social reason
        $sql = "SELECT * FROM merchant WHERE idLogin = :idLogin";
        $cond = array(
            array(":idLogin", $_SESSION['numSiren'])
        );
        $hisSocialReason = $db->q($sql, $cond);
        return $hisSocialReason[0]->name;
    }
}



?>
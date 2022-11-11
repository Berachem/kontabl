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
    $sql = "SELECT currency FROM transaction WHERE numSiren = :numSiren";
    $cond = array(
        array(":numSiren", $numSiren)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->currency;
};


/*FONCTION DE CLEMENT qui renvoie une liste de dictionnaire contenants les clés :
    -  NumSiren
    -  RaisonSociale
    - Numero de remise
    - Date Traitement
    - NombreTransactions
    - Devise
    - MontantTotal
    - Sens

En fonction des paramètres donnés
*/
function getDiscounts($numSiren, $raisonSociale, $date_debut, $date_fin, $sens){
    global $db;
    $data = array();
    $sql = "SELECT * FROM transaction";
    $cond = array();
    if ($raisonSociale){
        echo $raisonSociale;
        $sql2= "SELECT siren FROM merchant WHERE raisonSociale LIKE :raisonSociale";
        $cond2 = array(
            array(":raisonSociale","%".$raisonSociale."%")
        );
        $result = $db->q($sql2, $cond2);
        if ($result){
            $numSiren = $result[0]->siren;
        }
        else{
            return $data;
        }
        
    }
    if ($numSiren){
        $sql .= " WHERE numSiren = :numSiren";
        array_push($cond, array(":numSiren", $numSiren));
    }
    $result = $db->q($sql, $cond);
    foreach ($result as $transaction){
        $sql3="SElECT * FROM discount WHERE numTransaction = :numTransaction";
        $cond3 = array(
            array(":numTransaction", $transaction->idTransaction)
        );
        $result3 = $db->q($sql3, $cond3);
        if($result3){
            foreach($result3 as $discount){
                if ($date_debut){
                    if ($date_debut > $discount->dateDiscount){
                        continue;
                    }
                }
                if ($date_fin){
                    if ($date_fin < $discount->dateDiscount){
                        continue;
                    }
                }
                $d=array();
                $d['NumSiren']=$transaction->numSiren;
                $sql4="SELECT raisonSociale FROM merchant WHERE siren = :numSiren";
                $cond4 = array(
                    array(":numSiren", $transaction->numSiren)
                        );
                $result4 = $db->q($sql4, $cond4);
                $d['RaisonSociale']=$result4[0]->raisonSociale;
                $d['NumeroRemise']=$discount->numDiscount;
                $d['DateTraitement']=$discount->dateDiscount;
                $d['NombreTransactions']=getNbTransactions($db, $transaction->numSiren, $date_debut, $date_fin);
                $d['Devise']=getDevise($db, $transaction->numSiren);
                $d['MontantTotal']=getMontantTotal($db, $transaction->numSiren, $date_debut, $date_fin);
                if($sens){
                    if($discount->sens==$sens){
                        $d['Sens']=$discount->sens;
                    }else {
                        continue;
                    }
                }else{
                    $d['Sens']=$discount->sens;
                }
                array_push($data, $d);                    
            }
        }
        else{
            continue;
        }
    }
    return $data;
}



?>
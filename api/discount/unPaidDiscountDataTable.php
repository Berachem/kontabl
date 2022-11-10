<?php 
session_start();


/*
GET:
 - numSiren (optional)
 - raisonSociale (optional)
 - dateValeurDebut (optional)
 - dateValeurFin (optional)
 - numDossierImpayé (optional)

Return a JSON object with the following parameters:
    - success
    - error 
    OR 
    [
        [
            "NumSiren" => "string", ex: "123456789"
            "DateVente" => "string", ex: "2019-01-01"
            "DateRemise" => "string", ex: "2019-02-01"
            "numRemise" => "int", ex: 5
            "NumCarte" => "string", ex: "1234567890123456"
            "Reseau" => "string", ex: "VISA"
            "numDossierImpayé" => "string", ex: "1234567890123456"
            "Devise"=> "string", ex: "EUR"
            "Montant" => "int", ex: - 123 450
            "LibImpayé" => "string", ex: "Impayé"
        ],...
        
    ]
*/


include("../include/Functions.inc.php");


function getDateVente($db, $numRemise){
    $sql = "SELECT dateTransaction FROM transaction,discount WHERE  ";
    $cond = array(
        array(":numRemise", $numRemise)
    );
    $result = $db->q($sql, $cond);
    return $result[0]->dateVente;
}



// IL FAUT REUTILISER LE FICHIER DISCOUNTDATATABLE.
// (tu as un attribut "sens", donc tu pourras filtrer)



if (isset($_SESSION['id'])){
    // filtrer la réponse en gardant que les lignes dont le sens est "impayé"
    $tmpData = getDiscounts($_GET['numSiren'], $_GET['raisonSociale'], $_GET['dateValeurDebut'], $_GET['dateValeurFin'], "-" ,$_GET['numDossierImpayé']);
    
    // renvoie le json avec le numSiren, la dateVente, la dateRemise, le numCarte, le reseau, le numDossierImpayé, la devise, le montant et le libImpayé
    $data = array();
    foreach ($tmpData as $row){
        array_push($data, array(
            "NumSiren" => $row["numSiren"],
            "DateVente" => $row["dateVente"],
            "DateRemise" => $row["dateRemise"],
            "NumCarte" => $row["numCarte"],
            "Reseau" => $row["reseau"],
            "numDossierImpayé" => $row["numDossierImpayé"],
            "Devise"=> $row["devise"],
            "Montant" => $row["montant"],
            "LibImpayé" => $row["libImpayé"]
        ));
    }


   
}






?>
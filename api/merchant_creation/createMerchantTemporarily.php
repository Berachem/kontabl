<?php
/* 
POST 
 - idLogin
 - password`
 - siren
- `siren` ,
- `currency`, 
- `numCarte`,
- `network` ,


add a temporary merchant account

*/

include("include/Functions.inc.php");
session_start();





// check if the user is admin
if (isset($_SESSION['type']) && $_SESSION['type']== "admin"){

    // Check if all the parameters are set
    if (isset($_POST["raisonSociale"]) && isset($_POST["siren"]) && isset($_POST["currency"]) && isset($_POST["numCarte"]) && isset($_POST["network"]) 
    && isset($_POST["idLogin"]) && isset($_POST["password"])){

        // Check if the parameters are not empty
        if (!empty($_POST["raisonSociale"]) && !empty($_POST["siren"]) && !empty($_POST["currency"]) && !empty($_POST["numCarte"]) && !empty($_POST["network"])
        && !empty($_POST["idLogin"]) && !empty($_POST["password"])){

            // Check if the merchant already exists
            $param = array(
                array(':idLogin',$_POST['idLogin'],PDO::PARAM_STR),
            );
            $merchant = $db -> q("SELECT * FROM merchant WHERE idLogin = :idLogin;", $param); 
            if ($merchant){
                $response = [
                    "success" => false,
                    "error" => "Le marchand existe déjà"
                ]; 
            }
            else{
                // Check if the merchant is already in the temporary table
                $param = array(
                    array(':idLogin',$_POST['idLogin'],PDO::PARAM_STR),
                );
                $merchant = $db -> q("SELECT * FROM merchant_temp WHERE idLogin = :idLogin;", $param); 
                if ($merchant){
                    $response = [
                        "success" => false,
                        "error" => "Le marchand a déjà fait une demande"
                    ]; 
                }

                // Check if the siren already exists
                $param = array(
                    array(':siren',$_POST['siren'],PDO::PARAM_STR),
                );
                $merchant = $db -> q("SELECT * FROM merchant WHERE siren = :siren;", $param);

                if ($merchant){
                     // return error
                    $response = [
                        "success" => false,
                        "error" => "Son numéro de SIREN existe déjà dans la base de données"
                    ];
                }
                else{
                    // check if the siren already exists in the temporary table
                    $param = array(
                        array(':siren',$_POST['siren'],PDO::PARAM_STR),
                    );
                    $merchant = $db -> q("SELECT * FROM merchant_temp WHERE siren = :siren;", $param);

                    if ($merchant){
                        // return error
                        $response = [
                            "success" => false,
                            "error" => "Son numéro de SIREN existe déjà dans la table temporaire"
                        ];

                    }

                }

                // if response is not set
                if (!isset($response)){
                    // add the merchant to the temporary table
                    $param = array(
                        array(':raisonSociale',$_POST['raisonSociale'],PDO::PARAM_STR),
                        array(':siren',$_POST['siren'],PDO::PARAM_STR),
                        array(':currency',$_POST['currency'],PDO::PARAM_STR),
                        array(':numCarte',$_POST['numCarte'],PDO::PARAM_STR),
                        array(':network',$_POST['network'],PDO::PARAM_STR),
                        array(':password',$_POST['password'],PDO::PARAM_STR),
                        array(':idLogin',$_POST['idLogin'],PDO::PARAM_STR),
                    );
                    $merchant = $db -> q("INSERT INTO merchant_temp (raisonSociale, siren, currency, numCarte, network, password, idLogin) VALUES (:raisonSociale, :siren, :currency, :numCarte, :network, :password, :idLogin);", $param);

                    // return success
                    $response = [
                        "success" => true,
                        "numSiren" => $_POST['siren']
                    ];
                }


            }

        

}}
}// if the user is not admin
else{
    $response = [
        "success" => false,
        "error" => "Vous n'êtes pas administateur"
    ];
}

// return the response
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>
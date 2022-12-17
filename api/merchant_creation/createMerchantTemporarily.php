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
/*  session_start();

$_SESSION['type']= "admin";
$_POST["raisonSociale"] = "testadd";
$_POST["siren"] = "154685474";
$_POST["currency"] = "EUR";
$_POST["numCarte"] = "1234";
$_POST["network"] = "fr";
$_POST["idLogin"] = "testadd";s
$_POST["password"] = "testadd"; */



// check if the user is admin
if (isset($_SESSION['type']) && $_SESSION['type']== 'admin'){

        // Check if all the parameters are set
        if (isset($_POST["raisonSociale"]) 
        && isset($_POST["siren"]) 
        && isset($_POST["network"]) 
        && isset($_POST["idLogin"]) 
        && isset($_POST["password"])){

            // Check if the parameters are not empty
            try{

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
                        $_POST['numCarte'] = substr($_POST['numCarte'], -4);
                        $param = array(
                            array(':raisonSociale',$_POST['raisonSociale'],PDO::PARAM_STR),
                            array(':siren',$_POST['siren'],PDO::PARAM_STR),
                            array(':network',$_POST['network'],PDO::PARAM_STR),
                            array(':password',$_POST['password'],PDO::PARAM_STR),
                            array(':idLogin',$_POST['idLogin'],PDO::PARAM_STR),
                        );
                        $merchant = $db -> q("INSERT INTO merchant_temp (raisonSociale, siren, network, password, idLogin) VALUES (:raisonSociale, :siren, :network, :password, :idLogin);", $param);

                        // return success
                        $response = [
                            "success" => true,
                            "numSiren" => $_POST['siren']
                        ];
                    }


                }

                

        }catch (Exception $e){

            $response = [
                "success" => false,
                "error" => "Tous les champs doivent être remplis correctement."
            ];
        }


    }else{
        // if the user is not admin
        $response = [
            "success" => false,
            "error" => "Tous les champs doivent être remplis correctement."
        ];
    }

}
if (!isset($response)){
    $response = [
        "success" => false,
        "error" => "Vous n'êtes pas administateur"
    ];
}

// return the response
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
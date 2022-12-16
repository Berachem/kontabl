
<?php


if (isset($_SESSION['type']) && $_SESSION['type'] =="admin" && 
isset($_POST["numSiren"]) && isset($_POST["raisonSociale"]) && isset($_POST["password"]) && isset($_POST["idLogin"])) {
    // insert the merchant in the merchant table
    $param = array(
        array(':raisonSociale', $_POST['raisonSociale'], PDO::PARAM_STR),
        array(':siren', $_POST['numSiren'], PDO::PARAM_STR),
        array(':currency', "EUR", PDO::PARAM_STR),
        array(':numCarte', "none", PDO::PARAM_STR),
        array(':network', "VS", PDO::PARAM_STR),
        array(':password', password_hash($_POST["password"],PASSWORD_BCRYPT ), PDO::PARAM_STR),
        array(':idLogin', $_POST['idLogin'], PDO::PARAM_STR),
    );
    $db->q("INSERT INTO merchant (raisonSociale, siren, currency, numCarte, network, password, idLogin) VALUES (:raisonSociale, :siren, :currency, :numCarte, :network, :password, :idLogin);", $param);


    $response = [
        "success" => true,
        "numSiren" => $_POST['numSiren']
    ];
} else {
    $response = [
        "success" => false,
        "error" => "Opération non autorisée"
    ];

}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);






?>
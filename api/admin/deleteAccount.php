<?php   
/*
GET:
 - numSiren

Return a JSON object with the following parameters:
    - success
    - error 
*/

$_GET["numSiren"] = 124578369;

if (isset($_SESSION["num"]) && ($_SESSION["type"]=="admin")){  
    $cond = array();

    array_push($cond, array(":siren", $_GET["numSiren"]));

    $delete = $db -> q("DELETE FROM `merchant`
    WHERE siren = :siren", $cond);

    // return the response
    $response = array(
        "success" => true,
    );

} else {
    // return the response
    $response = array(
        "success" => false,
        "error" => "not loged like an admin"
    );
}
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
echo("test");




?>
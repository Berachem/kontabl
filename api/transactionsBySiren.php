<?php
/*
takes  GET request with the following parameters:
    - NumSiren (must be here)
    - findOnlyUnpaid   (boolean, optional)
    - dateBefore    (date, optional)
    - dateAfter    (date, optional)
    - amountMin   (float, optional)
    - amountMax  (float, optional)
    - endingFoursCardNumbers (char(4), optional)
    - currency (char(3), optional)


returns a JSON object with the following parameters:
    - success
    - error OR (transaction_details AND invoice_details linked to the transaction)
*/

// get the parameters
if (isset($_GET["NumSiren"])){
    $NumSiren = $_GET["NumSiren"];
    $findOnlyUnpaid = isset($_GET["findOnlyUnpaid"]) ? $_GET["findOnlyUnpaid"] : null;
    $dateBefore = isset($_GET["dateBefore"]) ? $_GET["dateBefore"] : null;
    $dateAfter = isset($_GET["dateAfter"]) ? $_GET["dateAfter"] : null;
    $amountMin = isset($_GET["amountMin"]) ? $_GET["amountMin"] : null;
    $amountMax = isset($_GET["amountMax"]) ? $_GET["amountMax"] : null;
    $endingFoursCardNumbers = isset($_GET["endingFoursCardNumbers"]) ? $_GET["endingFoursCardNumbers"] : null;
    $currency = isset($_GET["currency"]) ? $_GET["currency"] : null;

    // get the transactions
    $sql = "SELECT * FROM transaction WHERE NumSiren = :NumSiren";
    $cond = array(
        array(":NumSiren", $NumSiren)
    );
    if ($dateBefore){
        $sql .= " AND date < :dateBefore";
        $cond[] = array(":dateBefore", $dateBefore);
    }
    if ($dateAfter){
        $sql .= " AND date > :dateAfter";
        $cond[] = array(":dateAfter", $dateAfter);
    }
    if ($amountMin){
        $sql .= " AND amount > :amountMin";
        $cond[] = array(":amountMin", $amountMin);
    }
    if ($amountMax){
        $sql .= " AND amount < :amountMax";
        $cond[] = array(":amountMax", $amountMax);
    }
    if ($endingFoursCardNumbers){
        $sql .= " AND endingFoursCardNumbers = :endingFoursCardNumbers";
        $cond[] = array(":endingFoursCardNumbers", $endingFoursCardNumbers);
    }
    if ($currency){
        $sql .= " AND currency = :currency";
        $cond[] = array(":currency", $currency);
    }
    $transacResult = $db->q($sql, $cond);
    
    // for each transaction, get the invoice linked to the transaction
    $invoices = [];
    foreach ($transacResult as $transaction){
        $sql = "SELECT * FROM remise WHERE idTransaction = :transactionId";
        $cond = array(
            array(":transactionId", $transaction["id"])
        );
        $invoiceResult = $db->q($sql, $cond);
        $invoices[] = $invoiceResult;
    }
    
    if ($findOnlyUnpaid != null){
        
        if ($findOnlyUnpaid){
            // keep only the unpaid transactions
            $sql2 .= " AND sans = '-'";
        }else{
            // keep only the paid transactions
            $sql2 .= " AND sans = '-'";
        }
    }

    
    if ($transacResult) {
        $response = [
            "success" => true,
            "transactions" => $transacResult,
            "invoices" => $invoiceResult
        ];
    } else {
        $response = [
            "success" => false,
            "error" => "no transactions or invoices found corresponding with this ID"
        ];
    }



}
else{
    $response = [
        "success" => false,
        "error" => "missing parameters"
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    return json_encode($response);
    exit();
}






?>
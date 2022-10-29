<?php
/*
takes a GET request with the following parameters:
    - transactionId
returns a JSON object with the following parameters:
    - success
    - error OR (transaction_details AND invoice_details linked to the transaction)
*/

if (isset($_GET['transactionId'])) {
    $transactionId = $_GET['transactionId'];

    // get the transaction details
    $sql = "SELECT * FROM transaction WHERE id = :transactionId";
    $cond = array(
        array(":transactionId", $transactionId)
    );
    $transacResult = $db->q($sql, $cond);

    // get the invoice linked to the transaction
    $sql = "SELECT * FROM remise WHERE idTransaction = :transactionId";
    $cond = array(
        array(":transactionId", $transactionId)
    );
    $invoiceResult = $db->q($sql, $cond);

    if ($transacResult && $invoiceResult) {
        $response = [
            "success" => true,
            "transaction" => $transacResult,
            "invoice" => $invoiceResult

        ];
    } else {
        $response = [
            "success" => false,
            "error" => "no transaction found with this ID"
        ];
    }
    // return the response
    header('Content-Type: application/json');
    echo json_encode($response);
    return json_encode($response);
    exit();

}
?>
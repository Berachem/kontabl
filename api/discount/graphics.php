<?php 



include("transactionsBySiren.php");


$json = file_get_contents($reponse);
$file = json_decode($json, true);


// ------------------- Create data dashboard total amount depending on the date --------------------

if ($file['success'] == true )  {

// get the transactions and the invoices 

$get_transactions = $file["data"][0];
$get_invoices = $file["data"][1];

$date_1 = $_GET['date_1'];
$date_2 = $_GET['date_2'];



// create x_axis and y _axis for the transactions

$x_axis = [];
$y_axis = [];

function getTotalAmount($date) {

    $totalAmount = 0; 
    for ($i = 0; $i < count($get_transactions); $i++){
        if ($get_transactions[$i]["date"] == $date){
            $totalAmount += $get_transactions[$i]["montant"];
        }
    }
    return $totalAmount;
}



// get the amount of trancsactions in function of the month 

for ($i = 0; $i < count($get_transactions); $i++){

    // compare date1 and date2 with the date of the transaction
    if ($get_transactions[$i]["date"] >= $date_1 && $get_transactions[$i]["date"] <= $date_2){

        $get_date = $get_transactions[$i]["date"];
        $x_axis[] = $get_date;
        $y_axis[] = getTotalAmount($get_date);
    }

}

$data_dashboard = array($x_axis, $y_axis);


// check if x_axis and y_axis is not empty and return the data for a dashboard 

    if (empty($x_axis) || empty($y_axis)){
        $response_dashboard = [
            "success" => false,
            "error" => "no transactions found"
        ];
    }else{
        $response = [
            "success" => true,
            "data" => $data_dashboard
        ];

    }

// if "sucess" is false 

else {
    $response = [
        "success" => false,
        "error" => "no transactions found"
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
}

// -------------------------------------------------------------------------------------------------

?>
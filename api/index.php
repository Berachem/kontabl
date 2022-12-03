<?php

// faire tous les imports ici
// permet de ne pas avoir à les faire dans chaque fichier
session_start();

include "include/Connexion.inc.php";

function returnExpiredResponse()
{
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Page expirée. Rafrachissez la page.',
        'needRefresh' => true,
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['_token']) && !isset($_POST['_token'])) {
        returnExpiredResponse();
    }
    $submittedToken = $_POST['_token'] ?? null;
    $_token = $_SESSION['_token'] ?? null;

    if ($_token == null || $submittedToken == null || !hash_equals($_token, $submittedToken)) {
        returnExpiredResponse();
    }
}


if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            include("access/login.php");
            break;
        case 'logout':
            include("access/logout.php");
            break;
        case 'isLoggedIn':
            include("access/isLoggedIn.php");
            break;
        case "treasuryDataTable":
            include("treasury/treasuryDataTable.php");
            break;
        case "discountDataTable":
            include("discount/discountDataTable.php");
            break;
        case "unPaidDiscountDataTable":
            include("discount/unPaidDiscountDataTable.php");
            break;
        case "createMerchantTemporarily":
            include("merchant_creation/createMerchantTemporarily.php");
            break;
        case "acceptMerchantCreation":
            include("merchant_creation/acceptMerchantCreation.php");
            break;
        case "graphics":
            include("discount/graphics.php");
            break;
        case "graphicsLabels":
            include("discount/graphicsLabels.php");
            break;
        case "detailsTransactions":
            include("other/detailsTransactions.php");
            break;
        case "deleteAcount":
            include("admin/deleteAcount.php");
            break;
        case "getAllAcount":
            include("other/getAllAcount.php");
            break;
        case "getAllMerchantTemp":
            include("merchant_creation/getAllMerchantTemp.php");
            break;
        case "csvToXls":
            include("other/csvToXls.php");
            break;
    }
}

<?php

// faire tous les imports ici
// permet de ne pas avoir à les faire dans chaque fichier

include "include/Connexion.inc.php";




if (isset($_GET['action'])){
    switch ($_GET['action']) {
        case 'login':
            // inclure les fichiers qui process l'action demandée
            // retourner un json contenant un clé success et un message (retourner avec les bons headers), avec le code HTTP le plus approprié
            //break;
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
        
        
        
    }
}

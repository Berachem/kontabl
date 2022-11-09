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
            include("login.php");
            break;
        case 'logout':
            include("logout.php");
            break;
        case 'isLoggedIn':
            include("isLoggedIn.php");
            break;
        case 'transactionById':
            if (isset($_GET['transactionId'])) {
                include("transactionById.php");
            } 
            break;
        case 'clientDataTable':
            if (isset($_GET['NumSiren'])) {
                include("clientDataTable.php");
            } 
            break;
        case "pOwnerDataTable":

                include("pOwnerDataTable.php");
            
            break;
        case "discountDataTable":
            include("discountDataTable.php");
            break;
        case "merchantDataTable":
            include("merchantDataTable.php");
            break;
    }
}


?>

<!--
<form action="Login.php" action="insertion_form_BD.php" method="POST">
    <b>indentifiant:</b> <input type="text" name="login" required value="7745511214"/>
    <b>Mot de passe:</b><input type="text" name="password" required value="7745511214"/><br />
    <input type="reset" name="reset" value="Effacez" class = "boutton_formulaire"/> <input type="submit" name="submit" value="Validez" class = "boutton_formulaire"/>
</form> -->


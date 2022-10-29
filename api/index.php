<?php

// faire tous les imports ici
// permet de ne pas avoir à les faire dans chaque fichier

include "include/Connexion.inc.php";


switch ($_GET['action']) {
    case 'login':
        // inclure les fichiers qui process l'action demandée
        // retourner un json contenant un clé success et un message (retourner avec les bons headers), avec le code HTTP le plus approprié
        //break;
        include("login.php");
        break;
}

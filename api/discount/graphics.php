<?php

/*
Renvoie un json avec une liste d'abscisse et une liste d'ordonnées
clés : mois
valeurs : montant total des remises

*/

include("include/Functions.inc.php");
session_start();



if(isset($_SESSION['num'])){
    if ($_SESSION["type"] == "productowner" || $_SESSION["type"] == "admin") {
        $numSiren = isset($_GET["numSiren"]) ? $_GET["numSiren"] : null;
        $raisonSociale = isset($_GET["raisonSociale"]) ? $_GET["raisonSociale"] : null;
    } else {
        $numSiren = $_SESSION['num'];
        $raisonSociale = null;
    }

        $date_debut = isset($_GET["date_debut"]) ? $_GET["date_debut"] : null;
        $date_fin = isset($_GET["date_fin"]) ? $_GET["date_fin"] : null;
        $data = array();

        $data = getDiscounts($numSiren, $raisonSociale, $date_debut, $date_fin, "-", null);
        $montant = array();
        $montant[1] = 0;
        $montant[2] = 0;
        $montant[3] = 0;
        $montant[4] = 0;
        $montant[5] = 0;
        $montant[6] = 0;
        $montant[7] = 0;
        $montant[8] = 0;
        $montant[9] = 0;
        $montant[10] = 0;
        $montant[11] = 0;
        $montant[12] = 0;

        //echo json_encode($data);
        // récupération des données
        foreach ($data as $key => $value) {
            // on récupère les mois dans une date
            $date = new DateTime($value["DateTraitement"]);
            $mois = (int) $date->format("m");
            // cast en int pour pouvoir faire des calculs
            

            // ajoute en tant que clé pour éviter les doublons
            if (isset($montant[$mois])) {
                $montant[$mois] += (int) $value["MontantTotal"];
            } 
        }

        // on trie les données par mois
        ksort($montant);

        // on récupère les clés et les valeurs
        $mois = array_keys($montant);
        $montant = array_values($montant);

        $response = array(
            "success" => true,
            "mois" => $mois,
            "montant" => $montant
        );





} else{
    $response = [
        "success" => false,
        "error" => "Vous n'avez pas les droits.",
        "notLogged" => true
    ];
}

header('Content-Type: application/json ; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);



?>
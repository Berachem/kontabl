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
?>

<!-- test login -->
<form action="Login.php" action="insertion_form_BD.php" method="POST">
    <b>Nom:</b> <input type="text" name="nom" required value="Leroy Merlin Noisy"/>
    <b>Mot de passe:</b><input type="text" name="mdp" required value="2001458436"/><br />
    <input type="reset" name="reset" value="Effacez" class = "boutton_formulaire"/> <input type="submit" name="submit" value="Validez" class = "boutton_formulaire"/>
</form>

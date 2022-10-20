<?php   

    if ((!isset($_POST['nom'])) && (!isset($_POST['mdp']))) {
        exit("Veuillez passer par la page connexion");
    }
    session_start();
    include "include/Connexion.inc.php";
    $nom = $_POST['nom'];
    $mdp = $_POST['mdp'];


    $users = $db -> q("SELECT * FROM commercant WHERE nom = '$nom' AND mdp=$mdp;"); // cas utilisateur
    $admin = $db -> q("SELECT * FROM admin WHERE nom = '$nom' AND mdp=$mdp;"); // cas admin
    $productowner = $db -> q("SELECT * FROM productowner WHERE nom = '$nom' AND mdp=$mdp;"); // cas po

    if ($users) { // cas utilisateur
        $_SESSION['id']= $users[0]->idConnexion;
        $response = [
            "success" => true,
            "id" => $users[0]->idConnexion,
            "type" => 'utilisateur'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        return json_encode($response);
        exit();            
    } else if ($admin) { // cas admin
        $_SESSION['id']= $admin[0]->idAdmin;
        echo 'oui';
        $response = [
            "success" => true,
            "id" => $admin[0]->idAdmin,
            "type" => 'admin'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        return json_encode($response);
        exit();            
    } else if ($productowner) { // cas po
        $_SESSION['id']= $productowner[0]->id;
        $response = [
            "success" => true,
            "id" => $productowner[0]->id,
            "type" => 'product owner'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        return json_encode($response);
        exit();            
    } else { // cas incorrect
        $response = [
            "success" => false,
            "error" => "no user match with your login, password"
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        return json_encode($response);
        exit();            
    }

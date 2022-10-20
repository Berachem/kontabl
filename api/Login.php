<?php   

    if ((!isset($_POST['nom'])) && (!isset($_POST['mdp']))) {
        exit("Veuillez passer par la page connexion");
    }
    session_start();
    include "include/Connexion.inc.php";
    $nom = $_POST['nom'];
    $mdp = $_POST['mdp'];

    // cas utilisateur
    $users = $db -> q("SELECT * FROM commercant WHERE nom = :nom AND mdp= :mdp;", array(
        array(':nom',$nom,PDO::PARAM_STR),
        array(':mdp',$mdp,PDO::PARAM_STR),
        )
    ); 

    // cas admin
    $admin = $db -> q("SELECT * FROM admin WHERE nom = :nom AND mdp= :mdp;", array(
        array(':nom',$nom,PDO::PARAM_STR),
        array(':mdp',$mdp,PDO::PARAM_STR),
        )
    ); 
    // cas Product Owner
    $productowner = $db -> q("SELECT * FROM productowner WHERE nom = :nom AND mdp= :mdp;", array(
        array(':nom',$nom,PDO::PARAM_STR),
        array(':mdp',$mdp,PDO::PARAM_STR),
        )
    ); 

    // si c'est un utilisateur
    if ($users) { 
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
    } 
    // sinon si c'est un admin
    else if ($admin) { 
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
    } 
    // sinon si c'est un Product Owner
    else if ($productowner) { 
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
    } 
    // SINON -> cas incorrect
    else { 
        $response = [
            "success" => false,
            "error" => "no user match with your login, password"
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        return json_encode($response);
        exit();            
    }

<?php   

    if ((!isset($_POST['nom'])) && (!isset($_POST['mdp']))) {
        exit("Veuillez passer par la page connexion");
    }
    session_start();
    $nom = $_POST['nom'];
    $mdp = $_POST['mdp'];
    $param = array(
        array(':nom',$nom,PDO::PARAM_STR),
        array(':mdp',$mdp,PDO::PARAM_STR),
    );
    // cas utilisateur
    $users = $db -> q("SELECT * FROM commercant WHERE nom = :nom AND mdp= :mdp;", $param); 

    // cas admin
    $admin = $db -> q("SELECT * FROM admin WHERE nom = :nom AND mdp= :mdp;", $param
    ); 
    // cas Product Owner
    $productowner = $db -> q("SELECT * FROM productowner WHERE nom = :nom AND mdp= :mdp;", $param); 

    // si c'est un utilisateur
    if ($users) { 
        $_SESSION['id']= $users[0]->idConnexion;
        $response = [
            "success" => true,
            "id" => $users[0]->idConnexion,
            "type" => 'utilisateur'
        ];            
    } 
    // sinon si c'est un admin
    else if ($admin) { 
        $_SESSION['id']= $admin[0]->idAdmin;
        $response = [
            "success" => true,
            "id" => $admin[0]->idAdmin,
            "type" => 'admin'
        ];           
    } 
    // sinon si c'est un Product Owner
    else if ($productowner) { 
        $_SESSION['id']= $productowner[0]->id;
        $response = [
            "success" => true,
            "id" => $productowner[0]->id,
            "type" => 'product owner'
        ];           
    } 
    // SINON -> cas incorrect
    else { 
        $response = [
            "success" => false,
            "error" => "no user match with your login, password"
        ];           
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    return json_encode($response);
    exit();

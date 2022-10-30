<?php   
    // include "include/Connexion.inc.php"; à utiliser si on le test indépendament

    if ((!isset($_POST['login'])) && (!isset($_POST['password']))) {
        exit("Veuillez passer par la page connexion");
    }
    session_start();
    $login = $_POST['login'];
    $password = $_POST['password'];
    $param = array(
        array(':login',$login,PDO::PARAM_STR),
        array(':password',$password,PDO::PARAM_STR),
    );
    // cas utilisateur
    $users = $db -> q("SELECT * FROM merchant WHERE idLogin = :login AND password= :password;", $param); 

    // cas admin
    $admin = $db -> q("SELECT * FROM admin WHERE idAdmin = :login AND password= :password;", $param
    ); 
    // cas Product Owner
    $productowner = $db -> q("SELECT * FROM productowner WHERE idProductowner = :login AND password= :password;", $param); 

    // si c'est un utilisateur
    if ($users) { 
        $_SESSION['id']= $users[0]->idLogin;
        $response = [
            "success" => true,
            "id" => $users[0]->idLogin,
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
        $_SESSION['id']= $productowner[0]->idProductowner;
        $response = [
            "success" => true,
            "id" => $productowner[0]->idProductowner,
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
    exit();

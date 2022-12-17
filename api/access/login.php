<?php
/*
$url_path = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
$data = [
    'secret' => getenv('TURNSTILE_SECRET'),
    'response' => $_POST['turnstileToken'] ?? "",
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

$options = array(
    'http' => array(
        'method' => 'POST',
        'content' => http_build_query($data),
        'header' => "Content-Type: application/x-www-form-urlencoded"
    )
);

$stream = stream_context_create($options);

$body = file_get_contents(
    $url_path,
    false,
    $stream
);

$response = json_decode($body, true);

if ($response['success'] == false) {
    header("Content-Type: application/json");
    echo json_encode([
        "success" => false,
        "error" => "Erreur captcha"
    ]);
    exit;
}

*/
if (!isset($_SESSION["nbtry"])) {
    $_SESSION["nbtry"] = 3;
}

if ((!isset($_POST['login'])) || (!isset($_POST['password']))) {
    $response = [
        "success" => false,
        "error" => "Vous n'avais pas renseigné de login ou de mot de passe"
    ];
} else if ($_SESSION["nbtry"] == 0) {
    $response = [
        "success" => false,
        "error" => "Vous n'avez plus de tentative pour vous connecter"
    ];
} else {
    // session_start();
    $login = $_POST['login'];
    $password = $_POST['password'];
    $param = array(
        array(':login', $login, PDO::PARAM_STR),
    );


    // cas utilisateur
    $users = $db->q("SELECT * FROM merchant WHERE idLogin = :login;", $param);

    // cas admin
    $admin = $db->q(
        "SELECT * FROM admin WHERE idAdmin = :login;",
        $param
    );
    // cas Product Owner
    $productowner = $db->q("SELECT * FROM productowner WHERE idProductowner = :login;", $param);

    // si c'est un utilisateur
    if ($users && password_verify($password, $users[0]->password)) {
        $_SESSION['num'] = $users[0]->siren;
        $_SESSION['type'] = "user";

        $response = [
            "success" => true,
            "id" => $users[0]->idLogin,
            "type" => "user",
            "name" => $users[0]->raisonSociale
        ];
    }
    // sinon si c'est un admin
    else if ($admin && password_verify($password, $admin[0]->password)) {
        $_SESSION['num'] = $admin[0]->idAdmin;
        $_SESSION['type'] = "admin";

        $response = [
            "success" => true,
            "id" => $admin[0]->idAdmin,
            "type" => 'admin',
            "name" => $admin[0]->name
        ];
    }
    // sinon si c'est un Product Owner
    else if ($productowner && password_verify($password, $productowner[0]->password)) {
        $_SESSION['num'] = $productowner[0]->idProductowner;
        $_SESSION['type'] = "productowner";
        $response = [
            "success" => true,
            "id" => $productowner[0]->idProductowner,
            "type" => 'productowner',
            "name" => $productowner[0]->name
        ];
    }
    // SINON -> cas incorrect
    else {
        $_SESSION["nbtry"] -= 1;
        $error = "Identifiant ou mot de passe incorrect";
        if ($_SESSION["nbtry"] == 1) {
            $error = "Identifiant ou mot de passe incorrect, il ne vous reste plus qu'un essaie";
        }
        if ($_SESSION["nbtry"] == 0) {
            $error = "Identifiant ou mot de passe incorrect, il ne vous reste plus d'essaie";
        }
        $response = [
            "success" => false,
            "error" => $error
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);

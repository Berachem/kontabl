<?php
session_start();
session_destroy();
header('Content-Type: application/json');
return json_encode(["success" => true, "message" => "Vous êtes déconnecté"]);
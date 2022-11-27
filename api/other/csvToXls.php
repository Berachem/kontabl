<?php

$csvString = $_POST['csvString'] ?? $_GET['csvString'] ?? null;

if ($csvString === null) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Missing csvString'
    ]);
    exit;
}

require_once './lib/SimpleXLSXGen.php';

$array = array_map(function ($line) {
    return explode(';', $line);
}, explode(PHP_EOL, $csvString));

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($array);
$xlsx->downloadAs('export.xlsx');

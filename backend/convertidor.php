<?php
header("Content-Type: application/json");
echo json_encode(["rate" => 123.45]);

$from = strtoupper($_GET['from'] ?? '');
$to = strtoupper($_GET['to'] ?? '');
$tasas = [];

// Ruta relativa desde este archivo hacia tasas.txt
$archivo = __DIR__ . "/tasas.txt";

if (!file_exists($archivo)) {
    http_response_code(500);
    echo json_encode(["error" => "Archivo de tasas no encontrado."]);
    exit;
}

// Leer tasas desde el archivo
$lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lineas as $linea) {
    list($moneda, $valor) = explode('=', trim($linea));
    $tasas[trim($moneda)] = floatval(trim($valor));
}

if (isset($tasas[$from]) && isset($tasas[$to])) {
    $tasa = $tasas[$to] / $tasas[$from];
    echo json_encode(["rate" => $tasa]);
} else {
    http_response_code(400);
    echo json_encode(["error" => "Moneda no válida o no encontrada."]);
}




?>

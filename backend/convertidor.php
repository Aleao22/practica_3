<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

if (!$from || !$to || $from === $to) {
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

// Ruta al archivo tasas.txt
$archivoTasas = __DIR__ . '/tasas.txt';

if (!file_exists($archivoTasas)) {
    echo json_encode(['error' => 'Archivo de tasas no encontrado']);
    exit;
}

// Leer tasas del archivo
$tasas = [];
$lineas = file($archivoTasas, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lineas as $linea) {
    $linea = trim($linea);
    if ($linea === '' || $linea[0] === '#') continue;

    [$clave, $valor] = explode('=', $linea);
    $tasas[$clave] = floatval($valor);
}

// Buscar tasa directa o inversa
$claveDirecta = "{$from}_{$to}";
$claveInversa = "{$to}_{$from}";

if (isset($tasas[$claveDirecta])) {
    echo json_encode(['rate' => round($tasas[$claveDirecta], 6)]);
    exit;
}

if (isset($tasas[$claveInversa])) {
    $inversa = 1 / $tasas[$claveInversa];
    echo json_encode(['rate' => round($inversa, 6)]);
    exit;
}

// Conversión indirecta: from → USD → to
if ($from !== "USD" && isset($tasas["USD_{$from}"])) {
    $aUSD = 1 / $tasas["USD_{$from}"];
} elseif (isset($tasas["{$from}_USD"])) {
    $aUSD = $tasas["{$from}_USD"];
} else {
    echo json_encode(['error' => "No se puede convertir desde {$from}"]);
    exit;
}

if (isset($tasas["USD_{$to}"])) {
    $deUSD = $tasas["USD_{$to}"];
} elseif (isset($tasas["{$to}_USD"])) {
    $deUSD = 1 / $tasas["{$to}_USD"];
} else {
    echo json_encode(['error' => "No se puede convertir a {$to}"]);
    exit;
}

$tasaFinal = $aUSD * $deUSD;
echo json_encode(['rate' => round($tasaFinal, 6)]);

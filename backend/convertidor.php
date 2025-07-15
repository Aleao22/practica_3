<?php
header('Content-Type: application/json');

// Validación básica
$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

if (!$from || !$to || $from === $to) {
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

// Tasas de cambio fijas
$tasas = [
    "USD_CRC" => 540.50,
    "CRC_USD" => 0.00185,
    "USD_EUR" => 0.91,
    "EUR_USD" => 1.10,
    "CRC_EUR" => 0.0017,
    "EUR_CRC" => 598.20
];

// Buscar la tasa de cambio
$llave = $from . '_' . $to;

if (isset($tasas[$llave])) {
    echo json_encode(['rate' => $tasas[$llave]]);
} else {
    echo json_encode(['error' => 'Tasa no disponible']);
}
?>

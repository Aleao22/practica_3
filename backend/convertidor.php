<?php
header('Content-Type: application/json');

$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

if (!$from || !$to || $from === $to) {
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

$tasas = [
    "USD_CRC" => 540.50,
    "CRC_USD" => 0.00185
];

$key = "{$from}_{$to}";

if (isset($tasas[$key])) {
    echo json_encode(['rate' => $tasas[$key]]);
} else {
    echo json_encode(['error' => 'Tasa no disponible']);
}
?>

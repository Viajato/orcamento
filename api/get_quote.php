<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Arquivo de dados
$dataFile = 'data.json';
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "ID not provided"]);
    exit;
}

if (file_exists($dataFile)) {
    $jsonContent = file_get_contents($dataFile);
    $data = json_decode($jsonContent, true);
    
    $quotes = isset($data['quotes']) ? $data['quotes'] : [];
    
    // Busca o orçamento específico
    foreach ($quotes as $quote) {
        if (isset($quote['id']) && $quote['id'] === $id) {
            echo json_encode($quote);
            exit;
        }
    }
}

http_response_code(404);
echo json_encode(["error" => "Quote not found"]);
?>
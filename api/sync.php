<?php
// Configurações de CORS para permitir acesso do React
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Arquivo onde os dados serão salvos
$dataFile = 'data.json';

// Verifica o método da requisição
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // --- LER DADOS ---
    if (file_exists($dataFile)) {
        echo file_get_contents($dataFile);
    } else {
        // Se o arquivo não existir, retorna estrutura vazia
        echo json_encode([
            "sales" => [],
            "tasks" => [],
            "contracts" => [],
            "quotes" => []
        ]);
    }
} elseif ($method === 'POST') {
    // --- SALVAR DADOS ---
    // Recebe o JSON enviado pelo React
    $input = file_get_contents('php://input');
    
    // Valida se é um JSON válido
    $data = json_decode($input, true);
    
    if ($data) {
        // Salva no arquivo (Lock_EX evita conflito de gravação simultânea)
        if (file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX)) {
            echo json_encode(["status" => "success", "message" => "Dados salvos com sucesso"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Falha ao escrever no arquivo"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "JSON inválido"]);
    }
} else {
    // Método não permitido (apenas GET e POST)
    if ($method !== 'OPTIONS') {
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Método não permitido"]);
    }
}
?>
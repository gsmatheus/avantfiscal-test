<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/Controllers/RoomController.php';

// Aceita tanto DELETE quanto POST para compatibilidade
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$roomId = $_POST['id'] ?? $_GET['id'] ?? null;

if (!$roomId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID da sala é obrigatório']);
    exit;
}

$roomController = new RoomController();
$roomController->delete($roomId); 
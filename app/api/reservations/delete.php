<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/Controllers/ReservationController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$reservationId = $_POST['id'] ?? $_GET['id'] ?? null;

if (!$reservationId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID da reserva é obrigatório']);
    exit;
}

$reservationController = new ReservationController();
$reservationController->delete($reservationId); 
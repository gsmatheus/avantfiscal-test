<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/Controllers/RoomController.php';

$roomController = new RoomController();

if (isset($_GET['id'])) {
    $roomController->getById($_GET['id']);
} elseif (isset($_GET['q'])) {
    $roomController->search();
} else {
    $roomController->getAll();
} 
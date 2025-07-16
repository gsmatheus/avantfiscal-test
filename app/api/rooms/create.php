<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/Controllers/RoomController.php';

$roomController = new RoomController();
$roomController->create(); 
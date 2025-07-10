<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/Controllers/ReservationController.php';

$reservationController = new ReservationController();
$reservationController->create(); 
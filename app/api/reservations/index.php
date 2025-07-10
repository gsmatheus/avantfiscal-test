<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/Controllers/ReservationController.php';

$reservationController = new ReservationController();

if (isset($_GET['room_id'])) {
    $reservationController->getByRoom($_GET['room_id']);
} elseif (isset($_GET['user_id'])) {
    $reservationController->getByUser($_GET['user_id']);
} else {
    $reservationController->getByUser();
} 
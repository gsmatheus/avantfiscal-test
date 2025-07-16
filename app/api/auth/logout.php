<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/Controllers/AuthController.php';

$authController = new AuthController();
$authController->logout(); 
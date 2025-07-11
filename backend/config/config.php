<?php

define('APP_NAME', 'Sistema de Reservas');
define('APP_URL', 'http://localhost:8000');
define('APP_VERSION', '1.0.0');

define('SESSION_NAME', 'reserva_session');
define('SESSION_LIFETIME', 3600);

define('TIMEZONE', 'America/Sao_Paulo');

date_default_timezone_set(TIMEZONE);

session_name(SESSION_NAME);
session_start();

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAuth() {
    if (!isAuthenticated()) {
        redirect('/');
    }
}

function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        redirect('/app\/');
    }
}

function jsonResponse($data, $status = 200) {
    ob_clean();
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
} 
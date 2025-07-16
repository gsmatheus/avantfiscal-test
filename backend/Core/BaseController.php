<?php
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/ExceptionHandler.php';

abstract class BaseController {
    protected function validateRequest($method = 'POST') {
        $allowedMethods = is_array($method) ? $method : [$method];
        
        if (!in_array($_SERVER['REQUEST_METHOD'], $allowedMethods)) {
            Response::error('Método não permitido', 405);
        }
    }
    
    protected function getInput() {
        $input = json_decode(file_get_contents('php://input'), true);
        return $input ?: $_POST;
    }
    
    protected function getQueryParams() {
        return $_GET;
    }
    
    protected function executeWithExceptionHandling($callback) {
        try {
            return $callback();
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }
    
    protected function requireAuth() {
        if (!isAuthenticated()) {
            throw new AuthenticationException();
        }
    }
    
    protected function requireAdmin() {
        $this->requireAuth();
        if (!isAdmin()) {
            throw new AuthorizationException();
        }
    }
    
    protected function getPaginationParams() {
        $page = intval($_GET['page'] ?? 1);
        $limit = intval($_GET['limit'] ?? 20);
        $offset = ($page - 1) * $limit;
        
        return compact('page', 'limit', 'offset');
    }
} 
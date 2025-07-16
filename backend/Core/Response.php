<?php

class Response {
    private static $statusCodes = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        422 => 'Unprocessable Entity',
        500 => 'Internal Server Error'
    ];
    
    public static function success($data = null, $message = null, $statusCode = 200) {
        self::send([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
    
    public static function error($message, $statusCode = 400, $errors = null) {
        self::send([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
    
    public static function validationError($errors, $message = 'Dados inválidos') {
        self::send([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], 422);
    }
    
    public static function unauthorized($message = 'Não autorizado') {
        self::send([
            'success' => false,
            'message' => $message
        ], 401);
    }
    
    public static function forbidden($message = 'Acesso negado') {
        self::send([
            'success' => false,
            'message' => $message
        ], 403);
    }
    
    public static function notFound($message = 'Recurso não encontrado') {
        self::send([
            'success' => false,
            'message' => $message
        ], 404);
    }
    
    private static function send($data, $statusCode) {
        ob_clean();
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
} 
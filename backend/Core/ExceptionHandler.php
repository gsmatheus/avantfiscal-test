<?php
require_once __DIR__ . '/Response.php';

class ExceptionHandler {
    public static function handle($exception) {
        error_log("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
        
        if ($exception instanceof ValidationException) {
            Response::validationError($exception->getErrors(), $exception->getMessage());
        } elseif ($exception instanceof AuthenticationException) {
            Response::unauthorized($exception->getMessage());
        } elseif ($exception instanceof AuthorizationException) {
            Response::forbidden($exception->getMessage());
        } elseif ($exception instanceof NotFoundException) {
            Response::notFound($exception->getMessage());
        } else {
            Response::error('Erro interno do servidor', 500);
        }
    }
}

class ValidationException extends Exception {
    private $errors;
    
    public function __construct($errors, $message = 'Dados inválidos') {
        parent::__construct($message);
        $this->errors = $errors;
    }
    
    public function getErrors() {
        return $this->errors;
    }
}

class AuthenticationException extends Exception {
    public function __construct($message = 'Não autorizado') {
        parent::__construct($message);
    }
}

class AuthorizationException extends Exception {
    public function __construct($message = 'Acesso negado') {
        parent::__construct($message);
    }
}

class NotFoundException extends Exception {
    public function __construct($message = 'Recurso não encontrado') {
        parent::__construct($message);
    }
} 
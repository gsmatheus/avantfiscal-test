<?php
require_once __DIR__ . '/../Core/ExceptionHandler.php';

class RoomValidator {
    public static function validateRoom($name, $location, $capacity, $excludeId = null) {
        $errors = [];
        
        if (empty($name) || strlen(trim($name)) < 2) {
            $errors['name'] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        if (empty($location) || strlen(trim($location)) < 2) {
            $errors['location'] = 'Localização deve ter pelo menos 2 caracteres';
        }
        
        if (!is_numeric($capacity) || $capacity < 1) {
            $errors['capacity'] = 'Capacidade deve ser um número maior que 0';
        } elseif ($capacity > 100) {
            $errors['capacity'] = 'Capacidade máxima é de 100 pessoas';
        }
        
        if (!empty($errors)) {
            throw new ValidationException($errors, 'Dados da sala inválidos');
        }
    }
} 
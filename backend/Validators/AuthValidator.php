<?php
require_once __DIR__ . '/../Core/ExceptionHandler.php';

class AuthValidator {
    public static function validateLogin($email, $password) {
        $errors = [];
        
        if (empty($email)) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Senha é obrigatória';
        }
        
        if (!empty($errors)) {
            throw new ValidationException($errors, 'Dados de login inválidos');
        }
    }
    
    public static function validateRegister($name, $email, $password) {
        $errors = [];
        
        if (empty($name) || strlen(trim($name)) < 2) {
            $errors['name'] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        if (empty($email)) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Senha é obrigatória';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        if (!empty($errors)) {
            throw new ValidationException($errors, 'Dados de cadastro inválidos');
        }
    }
} 
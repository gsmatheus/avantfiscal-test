<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Validators/AuthValidator.php';
require_once __DIR__ . '/../Core/ExceptionHandler.php';

class AuthService {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login($email, $password) {
        AuthValidator::validateLogin($email, $password);
        
        $user = $this->userModel->authenticate($email, $password);
        
        if (!$user) {
            throw new AuthenticationException('Email ou senha inválidos');
        }
        
        $this->createSession($user);
        
        return $user;
    }
    
    public function register($name, $email, $password) {
        AuthValidator::validateRegister($name, $email, $password);
        
        if ($this->userModel->emailExists($email)) {
            throw new ValidationException(['email' => 'Email já cadastrado']);
        }
        
        $userId = $this->userModel->create($name, $email, $password);
        $user = $this->userModel->getById($userId);
        
        $this->createSession($user);
        
        return $user;
    }
    
    public function logout() {
        session_destroy();
    }
    
    public function getCurrentUser() {
        if (!isAuthenticated()) {
            throw new AuthenticationException();
        }
        
        $user = $this->userModel->getById($_SESSION['user_id']);
        
        if (!$user) {
            throw new NotFoundException('Usuário não encontrado');
        }
        
        return $user;
    }
    
    private function createSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['access_level'];
    }
} 
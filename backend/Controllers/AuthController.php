<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Services/AuthService.php';

class AuthController extends BaseController {
    private $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    public function login() {
        $this->executeWithExceptionHandling(function() {
            $this->validateRequest('POST');
            
            $input = $this->getInput();
            $email = trim($input['email'] ?? '');
            $password = $input['password'] ?? '';
            
            $user = $this->authService->login($email, $password);
            
            Response::success($user, 'Login realizado com sucesso');
        });
    }
    
    public function register() {
        $this->executeWithExceptionHandling(function() {
            $this->validateRequest('POST');
            
            $input = $this->getInput();
            $name = trim($input['name'] ?? '');
            $email = trim($input['email'] ?? '');
            $password = $input['password'] ?? '';
            
            $user = $this->authService->register($name, $email, $password);
            
            Response::success($user, 'Usuário criado com sucesso', 201);
        });
    }
    
    public function logout() {
        $this->executeWithExceptionHandling(function() {
            $this->validateRequest('POST');
            
            $this->authService->logout();
            
            Response::success(null, 'Logout realizado com sucesso');
        });
    }
    
    public function profile() {
        $this->executeWithExceptionHandling(function() {
            $this->validateRequest('GET');
            
            $user = $this->authService->getCurrentUser();
            
            Response::success($user, 'Perfil obtido com sucesso');
        });
    }
} 
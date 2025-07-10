<?php
require_once __DIR__ . '/../database/database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($name, $email, $password, $accessLevel = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, password, access_level) VALUES (?, ?, ?, ?)";
        $this->db->query($sql, [$name, $email, $hashedPassword, $accessLevel]);
        
        return $this->db->lastInsertId();
    }
    
    public function authenticate($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->query($sql, [$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        
        return false;
    }
    
    public function getById($id) {
        $sql = "SELECT id, name, email, access_level, created_at FROM users WHERE id = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    public function getByEmail($email) {
        $sql = "SELECT id, name, email, access_level FROM users WHERE email = ?";
        $stmt = $this->db->query($sql, [$email]);
        return $stmt->fetch();
    }
    
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch() !== false;
    }
    
    public function update($id, $data) {
        $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        return $this->db->query($sql, [$data['name'], $data['email'], $id]);
    }
    
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        return $this->db->query($sql, [$hashedPassword, $id]);
    }
    
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT id, name, email, access_level, created_at FROM users ORDER BY name LIMIT ? OFFSET ?";
        $stmt = $this->db->query($sql, [$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
} 
<?php
require_once __DIR__ . '/../database/database.php';

class Reservation {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($roomId, $userId, $startTime, $endTime, $description = null) {
        $sql = "INSERT INTO reservations (room_id, user_id, start_time, end_time, description) VALUES (?, ?, ?, ?, ?)";
        $this->db->query($sql, [$roomId, $userId, $startTime, $endTime, $description]);
        return $this->db->lastInsertId();
    }
    
    public function getByRoomId($roomId) {
        $sql = "SELECT r.*, u.name as user_name, u.email as user_email 
                FROM reservations r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.room_id = ? 
                ORDER BY r.start_time";
        $stmt = $this->db->query($sql, [$roomId]);
        return $stmt->fetchAll();
    }
    
    public function getByUserId($userId) {
        $sql = "SELECT r.*, rm.name as room_name, rm.location as room_location 
                FROM reservations r 
                JOIN rooms rm ON r.room_id = rm.id 
                WHERE r.user_id = ? 
                ORDER BY r.start_time";
        $stmt = $this->db->query($sql, [$userId]);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT r.*, u.name as user_name, u.email as user_email, 
                       rm.name as room_name, rm.location as room_location 
                FROM reservations r 
                JOIN users u ON r.user_id = u.id 
                JOIN rooms rm ON r.room_id = rm.id 
                WHERE r.id = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    public function checkConflict($roomId, $startTime, $endTime, $excludeId = null) {
        $sql = "SELECT id FROM reservations 
                WHERE room_id = ? 
                AND ((start_time < ? AND end_time > ?) OR (start_time < ? AND end_time > ?))";
        $params = [$roomId, $endTime, $startTime, $startTime, $endTime];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch() !== false;
    }
    
    public function delete($id) {
        $sql = "DELETE FROM reservations WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    

    
    public function getUserReservationForRoom($userId, $roomId) {
        $sql = "SELECT * FROM reservations 
                WHERE user_id = ? AND room_id = ? 
                ORDER BY start_time DESC 
                LIMIT 1";
        $stmt = $this->db->query($sql, [$userId, $roomId]);
        return $stmt->fetch();
    }
    
    public function hasConflict($roomId, $startTime, $endTime, $excludeId = null) {
        return $this->checkConflict($roomId, $startTime, $endTime, $excludeId);
    }
    
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT r.*, u.name as user_name, u.email as user_email, 
                       rm.name as room_name, rm.location as room_location 
                FROM reservations r 
                JOIN users u ON r.user_id = u.id 
                JOIN rooms rm ON r.room_id = rm.id 
                ORDER BY r.start_time DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->db->query($sql, [$limit, $offset]);
        return $stmt->fetchAll();
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM reservations";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function update($id, $roomId, $startTime, $endTime, $description = null) {
        $sql = "UPDATE reservations SET room_id = ?, start_time = ?, end_time = ?, description = ? WHERE id = ?";
        return $this->db->query($sql, [$roomId, $startTime, $endTime, $description, $id]);
    }
} 
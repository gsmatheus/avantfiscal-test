<?php
require_once __DIR__ . '/../database/database.php';

class Room {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($name, $location, $capacity) {
        $sql = "INSERT INTO rooms (name, location, capacity) VALUES (?, ?, ?)";
        $this->db->query($sql, [$name, $location, $capacity]);
        return $this->db->lastInsertId();
    }
    
    public function getAll($limit = 50, $offset = 0) {
        $userId = $_SESSION['user_id'] ?? 0;
        
        $sql = "SELECT r.id, r.name, r.capacity, r.location, r.created_at,
                COUNT(DISTINCT res.id) as total_reservations,
                (SELECT COUNT(DISTINCT res2.user_id) FROM reservations res2 WHERE res2.room_id = r.id AND res2.end_time > NOW()) as active_participants,
                (SELECT COUNT(*) FROM reservations res3 WHERE res3.room_id = r.id AND res3.user_id = ? AND res3.end_time > NOW()) as user_has_reservation,
                (SELECT res4.start_time FROM reservations res4 WHERE res4.room_id = r.id AND res4.user_id = ? AND res4.end_time > NOW() ORDER BY res4.start_time ASC LIMIT 1) as user_start_time,
                (SELECT res5.end_time FROM reservations res5 WHERE res5.room_id = r.id AND res5.user_id = ? AND res5.end_time > NOW() ORDER BY res5.start_time ASC LIMIT 1) as user_end_time,
                (SELECT res6.id FROM reservations res6 WHERE res6.room_id = r.id AND res6.user_id = ? AND res6.end_time > NOW() ORDER BY res6.start_time ASC LIMIT 1) as user_reservation_id
                FROM rooms r 
                LEFT JOIN reservations res ON r.id = res.room_id 
                GROUP BY r.id, r.name, r.capacity, r.location, r.created_at
                ORDER BY r.created_at DESC 
                LIMIT ? OFFSET ?";
        $stmt = $this->db->query($sql, [$userId, $userId, $userId, $userId, $limit, $offset]);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $sql = "SELECT r.id, r.name, r.capacity, r.location, r.created_at,
                COUNT(DISTINCT res.id) as total_reservations,
                (SELECT COUNT(DISTINCT res2.user_id) FROM reservations res2 WHERE res2.room_id = r.id AND res2.end_time > NOW()) as active_participants
                FROM rooms r 
                LEFT JOIN reservations res ON r.id = res.room_id 
                WHERE r.id = ?
                GROUP BY r.id, r.name, r.capacity, r.location, r.created_at";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }
    
    public function update($id, $name, $location, $capacity) {
        $sql = "UPDATE rooms SET name = ?, location = ?, capacity = ? WHERE id = ?";
        return $this->db->query($sql, [$name, $location, $capacity, $id]);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM rooms WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
    
    public function search($query, $limit = 50) {
        $searchTerm = "%$query%";
        $userId = $_SESSION['user_id'] ?? 0;
        
        $sql = "SELECT r.id, r.name, r.capacity, r.location, r.created_at,
                COUNT(DISTINCT res.id) as total_reservations,
                (SELECT COUNT(DISTINCT res2.user_id) FROM reservations res2 WHERE res2.room_id = r.id AND res2.end_time > NOW()) as active_participants,
                (SELECT COUNT(*) FROM reservations res3 WHERE res3.room_id = r.id AND res3.user_id = ? AND res3.end_time > NOW()) as user_has_reservation,
                (SELECT res4.start_time FROM reservations res4 WHERE res4.room_id = r.id AND res4.user_id = ? AND res4.end_time > NOW() ORDER BY res4.start_time ASC LIMIT 1) as user_start_time,
                (SELECT res5.end_time FROM reservations res5 WHERE res5.room_id = r.id AND res5.user_id = ? AND res5.end_time > NOW() ORDER BY res5.start_time ASC LIMIT 1) as user_end_time,
                (SELECT res6.id FROM reservations res6 WHERE res6.room_id = r.id AND res6.user_id = ? AND res6.end_time > NOW() ORDER BY res6.start_time ASC LIMIT 1) as user_reservation_id
                FROM rooms r
                LEFT JOIN reservations res ON r.id = res.room_id
                WHERE r.name LIKE ? OR r.location LIKE ?
                GROUP BY r.id, r.name, r.capacity, r.location, r.created_at
                ORDER BY r.name
                LIMIT ?";
        $stmt = $this->db->query($sql, [$userId, $userId, $userId, $userId, $searchTerm, $searchTerm, $limit]);
        return $stmt->fetchAll();
    }
    
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM rooms";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function nameExists($name, $excludeId = null) {
        $sql = "SELECT id FROM rooms WHERE name = ?";
        $params = [$name];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch() !== false;
    }
} 
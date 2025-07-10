<?php
require_once __DIR__ . '/../Models/Reservation.php';
require_once __DIR__ . '/../Models/Room.php';
require_once __DIR__ . '/../Validators/ReservationValidator.php';
require_once __DIR__ . '/../Core/ExceptionHandler.php';

class ReservationService {
    private $reservationModel;
    private $roomModel;
    
    public function __construct() {
        $this->reservationModel = new Reservation();
        $this->roomModel = new Room();
    }
    
    public function createReservation($roomId, $userId, $startTime, $endTime, $description = null) {
        
        $startTime = $this->convertToMySQLFormat($startTime);
        $endTime = $this->convertToMySQLFormat($endTime);
        
        ReservationValidator::validateReservation($roomId, $startTime, $endTime, $description);
        
        $room = $this->roomModel->getById($roomId);
        if (!$room) {
            throw new NotFoundException('Sala não encontrada');
        }
        
        if ($this->reservationModel->hasConflict($roomId, $startTime, $endTime)) {
            throw new ValidationException(['conflict' => 'Sala já reservada neste horário']);
        }
        
        $reservationId = $this->reservationModel->create($roomId, $userId, $startTime, $endTime, $description);
        return $this->reservationModel->getById($reservationId);
    }
    
    public function updateReservation($id, $roomId, $startTime, $endTime, $description = null) {
        
        $startTime = $this->convertToMySQLFormat($startTime);
        $endTime = $this->convertToMySQLFormat($endTime);
        
        ReservationValidator::validateReservation($roomId, $startTime, $endTime, $description);
        
        $reservation = $this->reservationModel->getById($id);
        if (!$reservation) {
            throw new NotFoundException('Reserva não encontrada');
        }
        
        if ($this->reservationModel->hasConflict($roomId, $startTime, $endTime, $id)) {
            throw new ValidationException(['conflict' => 'Sala já reservada neste horário']);
        }
        
        $this->reservationModel->update($id, $roomId, $startTime, $endTime, $description);
        return $this->reservationModel->getById($id);
    }
    
    public function deleteReservation($id, $userId = null) {
        $reservation = $this->reservationModel->getById($id);
        if (!$reservation) {
            throw new NotFoundException('Reserva não encontrada');
        }
        
        if ($userId && $reservation['user_id'] != $userId && !isAdmin()) {
            throw new AuthorizationException('Você só pode excluir suas próprias reservas');
        }
        
        return $this->reservationModel->delete($id);
    }
    
    public function getReservationById($id) {
        $reservation = $this->reservationModel->getById($id);
        if (!$reservation) {
            throw new NotFoundException('Reserva não encontrada');
        }
        
        return $reservation;
    }
    
    public function getReservationsByRoom($roomId) {
        $room = $this->roomModel->getById($roomId);
        if (!$room) {
            throw new NotFoundException('Sala não encontrada');
        }
        
        return $this->reservationModel->getByRoomId($roomId);
    }
    
    public function getReservationsByUser($userId) {
        return $this->reservationModel->getByUserId($userId);
    }
    
    public function getAllReservations($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $reservations = $this->reservationModel->getAll($limit, $offset);
        $total = $this->reservationModel->count();
        
        return [
            'reservations' => $reservations,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
                'per_page' => $limit
            ]
        ];
    }
    
    private function convertToMySQLFormat($datetime) {
        
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $datetime)) {
            return $datetime;
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $datetime)) {
            return str_replace('T', ' ', $datetime) . ':00';
        }
        try {
            $dt = new DateTime($datetime);
            return $dt->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            return $datetime; 
        }
    }
} 
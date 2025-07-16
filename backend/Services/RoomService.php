<?php
require_once __DIR__ . '/../Models/Room.php';
require_once __DIR__ . '/../Validators/RoomValidator.php';
require_once __DIR__ . '/../Core/ExceptionHandler.php';

class RoomService {
    private $roomModel;
    
    public function __construct() {
        $this->roomModel = new Room();
    }
    
    public function createRoom($name, $location, $capacity) {
        RoomValidator::validateRoom($name, $location, $capacity);
        
        if ($this->roomModel->nameExists($name)) {
            throw new ValidationException(['name' => 'Já existe uma sala com este nome']);
        }
        
        $roomId = $this->roomModel->create($name, $location, $capacity);
        return $this->roomModel->getById($roomId);
    }
    
    public function updateRoom($id, $name, $location, $capacity) {
        RoomValidator::validateRoom($name, $location, $capacity, $id);
        
        $room = $this->roomModel->getById($id);
        if (!$room) {
            throw new NotFoundException('Sala não encontrada');
        }
        
        if ($this->roomModel->nameExists($name, $id)) {
            throw new ValidationException(['name' => 'Já existe uma sala com este nome']);
        }
        
        $this->roomModel->update($id, $name, $location, $capacity);
        return $this->roomModel->getById($id);
    }
    
    public function deleteRoom($id) {
        $room = $this->roomModel->getById($id);
        if (!$room) {
            throw new NotFoundException('Sala não encontrada');
        }
        
        return $this->roomModel->delete($id);
    }
    
    public function getRoomById($id) {
        $room = $this->roomModel->getById($id);
        if (!$room) {
            throw new NotFoundException('Sala não encontrada');
        }
        
        return $room;
    }
    
    public function getAllRooms($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        
        $rooms = $this->roomModel->getAll($limit, $offset);
        $total = $this->roomModel->count();
        
        return [
            'rooms' => $rooms,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
                'per_page' => $limit
            ]
        ];
    }
    
    public function searchRooms($query) {
        if (empty($query)) {
            return $this->getAllRooms();
        }
        
        return $this->roomModel->search($query);
    }
} 
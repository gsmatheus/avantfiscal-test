<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Services/RoomService.php';

class RoomController extends BaseController {
    private $roomService;
    
    public function __construct() {
        $this->roomService = new RoomService();
    }
    
    public function create() {
        $this->executeWithExceptionHandling(function() {
            $this->validateRequest('POST');
            $this->requireAdmin();
            
            $input = $this->getInput();
            $name = trim($input['name'] ?? '');
            $location = trim($input['location'] ?? '');
            $capacity = intval($input['capacity'] ?? 0);
            
            $room = $this->roomService->createRoom($name, $location, $capacity);
            
            Response::success($room, 'Sala criada com sucesso', 201);
        });
    }
    
    public function update($id) {
        $this->executeWithExceptionHandling(function() use ($id) {
            $this->validateRequest(['PUT', 'POST']);
            $this->requireAdmin();
            
            $input = $this->getInput();
            $name = trim($input['name'] ?? '');
            $location = trim($input['location'] ?? '');
            $capacity = intval($input['capacity'] ?? 0);
            
            $room = $this->roomService->updateRoom($id, $name, $location, $capacity);
            
            Response::success($room, 'Sala atualizada com sucesso');
        });
    }
    
    public function delete($id) {
        $this->executeWithExceptionHandling(function() use ($id) {
            $this->validateRequest(['DELETE', 'POST']);
            $this->requireAdmin();
            
            $this->roomService->deleteRoom($id);
            
            Response::success(null, 'Sala excluída com sucesso');
        });
    }
    
    public function getById($id) {
        $this->executeWithExceptionHandling(function() use ($id) {
            $this->validateRequest('GET');
            $this->requireAuth();
            
            $room = $this->roomService->getRoomById($id);
            
            Response::success($room, 'Sala obtida com sucesso');
        });
    }
    
    public function getAll() {
        $this->executeWithExceptionHandling(function() {
            $this->validateRequest('GET');
            $this->requireAuth();
            
            $params = $this->getPaginationParams();
            $result = $this->roomService->getAllRooms($params['page'], $params['limit']);
            
            Response::success($result, 'Salas obtidas com sucesso');
        });
    }
    
    public function search() {
        $this->executeWithExceptionHandling(function() {
            $this->validateRequest('GET');
            $this->requireAuth();
            
            $query = trim($_GET['q'] ?? '');
            $rooms = $this->roomService->searchRooms($query);
            
            Response::success($rooms, 'Busca realizada com sucesso');
        });
    }
} 
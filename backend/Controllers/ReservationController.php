<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Services/ReservationService.php';

class ReservationController extends BaseController {
    private $reservationService;
    
    public function __construct() {
        $this->reservationService = new ReservationService();
    }
    
    public function create() {
        $this->executeWithExceptionHandling(function() {
            $this->validateRequest('POST');
            $this->requireAuth();
            
            $input = $this->getInput();
            $roomId = intval($input['room_id'] ?? 0);
            $startTime = trim($input['start_time'] ?? '');
            $endTime = trim($input['end_time'] ?? '');
            $description = trim($input['description'] ?? '');
            
            $reservation = $this->reservationService->createReservation(
                $roomId, 
                $_SESSION['user_id'], 
                $startTime, 
                $endTime, 
                $description
            );
            
            Response::success($reservation, 'Reserva criada com sucesso', 201);
        });
    }
    
    public function update($id) {
        $this->executeWithExceptionHandling(function() use ($id) {
            $this->validateRequest('PUT');
            $this->requireAuth();
            
            $input = $this->getInput();
            $roomId = intval($input['room_id'] ?? 0);
            $startTime = trim($input['start_time'] ?? '');
            $endTime = trim($input['end_time'] ?? '');
            $description = trim($input['description'] ?? '');
            
            $reservation = $this->reservationService->updateReservation($id, $roomId, $startTime, $endTime, $description);
            
            Response::success($reservation, 'Reserva atualizada com sucesso');
        });
    }
    
    public function delete($id) {
        $this->executeWithExceptionHandling(function() use ($id) {
            $this->validateRequest(['DELETE', 'POST']);
            $this->requireAuth();
            
            $this->reservationService->deleteReservation($id, $_SESSION['user_id']);
            
            Response::success(null, 'Reserva excluída com sucesso');
        });
    }
    
    public function getById($id) {
        $this->executeWithExceptionHandling(function() use ($id) {
            $this->validateRequest('GET');
            $this->requireAuth();
            
            $reservation = $this->reservationService->getReservationById($id);
            
            Response::success($reservation, 'Reserva obtida com sucesso');
        });
    }
    
    public function getByRoom($roomId) {
        $this->executeWithExceptionHandling(function() use ($roomId) {
            $this->validateRequest('GET');
            $this->requireAuth();
            
            $reservations = $this->reservationService->getReservationsByRoom($roomId);
            
            Response::success($reservations, 'Reservas da sala obtidas com sucesso');
        });
    }
    
    public function getByUser($userId = null) {
        $this->executeWithExceptionHandling(function() use ($userId) {
            $this->validateRequest('GET');
            $this->requireAuth();
            
            $userId = $userId ?: $_SESSION['user_id'];
            
            if ($userId != $_SESSION['user_id'] && !isAdmin()) {
                throw new AuthorizationException('Você só pode ver suas próprias reservas');
            }
            
            $reservations = $this->reservationService->getReservationsByUser($userId);
            
            Response::success($reservations, 'Reservas do usuário obtidas com sucesso');
        });
    }
    
    public function getAll() {
        $this->executeWithExceptionHandling(function() {
            $this->validateRequest('GET');
            $this->requireAuth();
            
            $params = $this->getPaginationParams();
            $result = $this->reservationService->getAllReservations($params['page'], $params['limit']);
            
            Response::success($result, 'Reservas obtidas com sucesso');
        });
    }
} 
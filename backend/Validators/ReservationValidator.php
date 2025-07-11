<?php
require_once __DIR__ . '/../Core/ExceptionHandler.php';
class ReservationValidator {
    public static function validateReservation($roomId, $startTime, $endTime, $description = null) {
        $errors = [];
        if (empty($roomId) || !is_numeric($roomId)) {
            $errors['room_id'] = 'Sala é obrigatória';
        }
        if (empty($startTime)) {
            $errors['start_time'] = 'Data/hora de início é obrigatória';
        } elseif (!self::isValidDateTime($startTime)) {
            $errors['start_time'] = 'Data/hora de início inválida';
        }
        if (empty($endTime)) {
            $errors['end_time'] = 'Data/hora de fim é obrigatória';
        } elseif (!self::isValidDateTime($endTime)) {
            $errors['end_time'] = 'Data/hora de fim inválida';
        }
        if (!empty($startTime) && !empty($endTime) && self::isValidDateTime($startTime) && self::isValidDateTime($endTime)) {
            $start = new DateTime($startTime);
            $end = new DateTime($endTime);
            $now = new DateTime();
            if ($start <= $now) {
                $errors['start_time'] = 'A data e hora de início não pode ser no passado';
            }
            if ($end <= $start) {
                $errors['end_time'] = 'A data de término deve ser depois da data de início';
            }
            $diffInHours = ($end->getTimestamp() - $start->getTimestamp()) / 3600;
            if ($diffInHours > 8) {
                $errors['duration'] = 'A reserva pode durar no máximo 8 horas';
            }
        }
        if (!empty($description) && strlen($description) > 500) {
            $errors['description'] = 'Descrição não pode ter mais de 500 caracteres';
        }
        if (!empty($errors)) {
            throw new ValidationException($errors, 'Dados da reserva inválidos');
        }
    }
    private static function isValidDateTime($datetime) {
        $d = DateTime::createFromFormat('Y-m-d\TH:i', $datetime);
        if ($d && $d->format('Y-m-d\TH:i') === $datetime) {
            return true;
        }
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        if ($d && $d->format('Y-m-d H:i:s') === $datetime) {
            return true;
        }
        $d = DateTime::createFromFormat('Y-m-d H:i', $datetime);
        return $d && $d->format('Y-m-d H:i') === $datetime;
    }
} 
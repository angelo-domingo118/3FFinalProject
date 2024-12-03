<?php

class TherapistApiController {
    private $db;
    private $availability;

    public function __construct($db) {
        $this->db = $db;
        require_once __DIR__ . '/../../models/Availability.php';
        $this->availability = new Availability($db);
    }

    private function isAdmin() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] === 3;
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function getAvailability($therapistId) {
        error_log("Getting availability for therapist: $therapistId");
        
        if (!$this->isAdmin()) {
            error_log("Unauthorized access attempt");
            $this->sendResponse(['error' => 'Unauthorized'], 401);
        }

        $weekStart = $_GET['week_start'] ?? date('Y-m-d');
        error_log("Week start: $weekStart");
        
        try {
            $availability = $this->availability->getTherapistWeeklyAvailability($therapistId, $weekStart);
            error_log("Retrieved availability: " . print_r($availability, true));
            
            $this->sendResponse([
                'success' => true,
                'availability' => $availability
            ]);
        } catch (Exception $e) {
            error_log("Error getting availability: " . $e->getMessage());
            $this->sendResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function saveAvailability() {
        if (!$this->isAdmin()) {
            $this->sendResponse(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$this->validateAvailabilityData($data)) {
            $this->sendResponse(['error' => 'Invalid data provided'], 400);
        }

        try {
            $success = $this->availability->saveTherapistAvailability(
                $data['therapist_id'],
                $data['day_of_week'],
                $data['start_time'],
                $data['end_time'],
                $data['repeat_weekly'] ?? false
            );

            if ($success) {
                $this->sendResponse([
                    'success' => true,
                    'message' => 'Availability saved successfully'
                ]);
            } else {
                $this->sendResponse(['error' => 'Failed to save availability'], 500);
            }
        } catch (Exception $e) {
            $this->sendResponse(['error' => $e->getMessage()], 500);
        }
    }

    private function validateAvailabilityData($data) {
        $required = ['therapist_id', 'day_of_week', 'start_time', 'end_time'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        // Validate day of week
        $validDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        if (!in_array($data['day_of_week'], $validDays)) {
            return false;
        }

        // Validate time format (HH:mm)
        if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $data['start_time']) ||
            !preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $data['end_time'])) {
            return false;
        }

        // Ensure end time is after start time
        if (strtotime($data['end_time']) <= strtotime($data['start_time'])) {
            return false;
        }

        return true;
    }
} 
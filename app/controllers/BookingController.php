<?php

class BookingController {
    private $pdo;
    private $serviceModel;
    private $userModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        require_once '../app/models/Service.php';
        require_once '../app/models/User.php';
        $this->serviceModel = new Service($pdo);
        $this->userModel = new User($pdo);
    }

    public function index() {
        $serviceId = isset($_GET['service']) ? $_GET['service'] : null;
        $services = $this->serviceModel->getAllServices();
        $selectedService = $serviceId ? $this->serviceModel->getServiceById($serviceId) : null;
        
        require_once '../app/views/booking/index.php';
    }

    public function datetime() {
        if (!isset($_GET['service'])) {
            header('Location: ' . BASE_URL . '/public/booking');
            exit;
        }

        $serviceId = $_GET['service'];
        $service = $this->serviceModel->getServiceById($serviceId);
        $therapists = $this->userModel->getTherapists();
        
        require_once '../app/views/booking/datetime.php';
    }

    public function getAvailableSlots() {
        // Set JSON header first
        header('Content-Type: application/json');
        
        try {
            // Validate inputs
            if (!isset($_GET['date']) || !isset($_GET['service_id'])) {
                echo json_encode(['error' => 'Missing parameters']);
                return;
            }

            $date = $_GET['date'];
            $serviceId = (int)$_GET['service_id'];
            
            // Get service duration
            $service = $this->serviceModel->getServiceById($serviceId);
            if (!$service) {
                echo json_encode(['error' => 'Service not found']);
                return;
            }

            // Calculate end time based on service duration
            $duration = $service['duration'];

            // Query available slots using all named parameters
            $sql = "SELECT a.*, u.full_name as therapist_name,
                    TIME_FORMAT(a.start_time, '%H:%i') as formatted_start_time,
                    TIME_FORMAT(ADDTIME(a.start_time, SEC_TO_TIME(:duration1*60)), '%H:%i') as formatted_end_time 
                    FROM Availability a 
                    JOIN Users u ON a.therapist_id = u.user_id 
                    WHERE a.date = :date 
                    AND a.is_deleted = FALSE 
                    AND u.role_id = (SELECT role_id FROM Roles WHERE role_name = 'therapist')
                    AND NOT EXISTS (
                        SELECT 1 FROM Appointments apt 
                        WHERE apt.therapist_id = a.therapist_id 
                        AND apt.appointment_date = a.date 
                        AND apt.status != 'canceled'
                        AND apt.is_deleted = FALSE
                        AND (
                            (apt.start_time <= a.start_time AND apt.end_time > a.start_time)
                            OR (apt.start_time < ADDTIME(a.start_time, SEC_TO_TIME(:duration2*60)) AND apt.end_time >= ADDTIME(a.start_time, SEC_TO_TIME(:duration3*60)))
                            OR (apt.start_time >= a.start_time AND apt.end_time <= ADDTIME(a.start_time, SEC_TO_TIME(:duration4*60)))
                        )
                    )
                    ORDER BY a.start_time";
                    
            $stmt = $this->pdo->prepare($sql);
            $params = [
                ':date' => $date,
                ':duration1' => $duration,
                ':duration2' => $duration,
                ':duration3' => $duration,
                ':duration4' => $duration
            ];
            $stmt->execute($params);
            $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['slots' => $slots]);
            
        } catch (Exception $e) {
            error_log("Error in getAvailableSlots: " . $e->getMessage());
            echo json_encode(['error' => 'Server error', 'message' => $e->getMessage()]);
        }
        exit; // Make sure nothing else is output
    }

    private function validateTimeSlot($date, $time, $serviceId, $therapistId) {
        try {
            // Get service duration
            $service = $this->serviceModel->getServiceById($serviceId);
            if (!$service) {
                return ['valid' => false, 'message' => 'Service not found'];
            }
            
            $duration = $service['duration'];
            
            // Calculate end time
            $endTime = date('H:i:s', strtotime($time . ' + ' . $duration . ' minutes'));
            
            // Check if the time slot exists and is available
            $sql = "SELECT COUNT(*) as conflict_count 
                    FROM Appointments 
                    WHERE therapist_id = :therapist_id 
                    AND appointment_date = :date 
                    AND status != 'canceled'
                    AND is_deleted = FALSE
                    AND (
                        (start_time <= :start_time AND end_time > :start_time)
                        OR (start_time < :end_time AND end_time >= :end_time)
                        OR (:start_time <= start_time AND :end_time >= end_time)
                    )";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':therapist_id' => $therapistId,
                ':date' => $date,
                ':start_time' => $time,
                ':end_time' => $endTime
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['conflict_count'] > 0) {
                return ['valid' => false, 'message' => 'Selected time slot is no longer available'];
            }
            
            // Check if the selected time is within therapist's availability
            $sql = "SELECT COUNT(*) as available
                    FROM Availability 
                    WHERE therapist_id = :therapist_id 
                    AND date = :date 
                    AND start_time <= :start_time 
                    AND end_time >= :end_time
                    AND is_deleted = FALSE";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':therapist_id' => $therapistId,
                ':date' => $date,
                ':start_time' => $time,
                ':end_time' => $endTime
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['available'] == 0) {
                return ['valid' => false, 'message' => 'Selected time is outside of therapist availability'];
            }
            
            return ['valid' => true, 'end_time' => $endTime];
        } catch (Exception $e) {
            error_log("Error in validateTimeSlot: " . $e->getMessage());
            return ['valid' => false, 'message' => 'Error validating time slot'];
        }
    }

    public function confirm() {
        if (!isset($_GET['service']) || !isset($_GET['date']) || !isset($_GET['time']) || !isset($_GET['therapist'])) {
            header('Location: ' . BASE_URL . '/public/booking');
            exit;
        }

        $serviceId = $_GET['service'];
        $date = $_GET['date'];
        $time = $_GET['time'];
        $therapistId = $_GET['therapist'];

        // Validate the selected time slot
        $validation = $this->validateTimeSlot($date, $time, $serviceId, $therapistId);
        if (!$validation['valid']) {
            $_SESSION['error'] = $validation['message'];
            header('Location: ' . BASE_URL . '/public/booking/datetime?service=' . $serviceId);
            exit;
        }

        $service = $this->serviceModel->getServiceById($serviceId);
        $therapist = $this->userModel->getUserById($therapistId);

        $data = [
            'service' => $service,
            'date' => $date,
            'time' => $time,
            'end_time' => $validation['end_time'],
            'therapist' => $therapist
        ];

        require_once '../app/views/booking/confirm.php';
    }

    public function process() {
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            exit;
        }

        // Get JSON input
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        try {
            // Validate required fields
            if (!isset($data['service_id']) || !isset($data['date']) || 
                !isset($data['time']) || !isset($data['therapist_id'])) {
                throw new Exception('Missing required fields');
            }

            // Start transaction
            $this->pdo->beginTransaction();

            // Get service details for duration
            $service = $this->serviceModel->getServiceById($data['service_id']);
            if (!$service) {
                throw new Exception('Invalid service');
            }

            // Calculate end time
            $startTime = new DateTime($data['time']);
            $endTime = clone $startTime;
            $endTime->add(new DateInterval('PT' . $service['duration'] . 'M'));

            // Insert appointment
            $sql = "INSERT INTO Appointments (
                        user_id, service_id, therapist_id, 
                        appointment_date, start_time, end_time,
                        notes, status, created_at
                    ) VALUES (
                        :user_id, :service_id, :therapist_id,
                        :date, :start_time, :end_time,
                        :notes, 'confirmed', NOW()
                    )";

            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute([
                'user_id' => $_SESSION['user_id'],
                'service_id' => $data['service_id'],
                'therapist_id' => $data['therapist_id'],
                'date' => $data['date'],
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'notes' => $data['notes'] ?? null
            ]);

            if (!$success) {
                throw new Exception('Failed to create appointment');
            }

            $bookingId = $this->pdo->lastInsertId();

            // Commit transaction
            $this->pdo->commit();

            echo json_encode([
                'success' => true,
                'booking_id' => $bookingId,
                'message' => 'Booking created successfully'
            ]);

        } catch (Exception $e) {
            // Rollback transaction on error
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
} 
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

            // Query available slots
            $sql = "SELECT a.*, u.full_name as therapist_name 
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
                            OR (apt.start_time < a.end_time AND apt.end_time >= a.end_time)
                            OR (apt.start_time >= a.start_time AND apt.end_time <= a.end_time)
                        )
                    )
                    ORDER BY a.start_time";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['date' => $date]);
            $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['slots' => $slots]);
            
        } catch (Exception $e) {
            error_log("Error in getAvailableSlots: " . $e->getMessage());
            echo json_encode(['error' => 'Server error', 'message' => $e->getMessage()]);
        }
        exit; // Make sure nothing else is output
    }

    public function confirm() {
        if (!isset($_GET['service']) || !isset($_GET['date']) || !isset($_GET['time']) || !isset($_GET['therapist'])) {
            header('Location: ' . BASE_URL . '/public/booking');
            exit;
        }

        $serviceId = $_GET['service'];
        $selectedDate = $_GET['date'];
        $selectedTime = $_GET['time'];
        $therapistId = $_GET['therapist'];

        // Get service and therapist details
        $service = $this->serviceModel->getServiceById($serviceId);
        $therapist = $this->userModel->getUserById($therapistId);

        if (!$service || !$therapist) {
            header('Location: ' . BASE_URL . '/public/booking');
            exit;
        }

        require_once '../app/views/booking/confirm.php';
    }
} 
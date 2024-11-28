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
        header('Content-Type: application/json');
        
        try {
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

            // Query available slots showing full availability range
            $sql = "SELECT 
                    a.*, 
                    u.full_name as therapist_name,
                    TIME_FORMAT(a.start_time, '%h:%i %p') as formatted_start_time,
                    TIME_FORMAT(a.end_time, '%h:%i %p') as formatted_end_time,
                    :duration as service_duration
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
            $params = [
                ':date' => $date,
                ':duration' => $service['duration']
            ];
            $stmt->execute($params);
            $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['slots' => $slots, 'service' => $service]);
            
        } catch (Exception $e) {
            error_log("Error in getAvailableSlots: " . $e->getMessage());
            echo json_encode(['error' => 'Server error', 'message' => $e->getMessage()]);
        }
        exit;
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
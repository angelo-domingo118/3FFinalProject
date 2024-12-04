<?php

class BookingController {
    private $pdo;
    private $serviceModel;
    private $userModel;
    private $appointmentModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        require_once '../app/models/Service.php';
        require_once '../app/models/User.php';
        require_once '../app/models/Appointment.php';
        $this->serviceModel = new Service($pdo);
        $this->userModel = new User($pdo);
        $this->appointmentModel = new Appointment($pdo);
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
            if (!isset($_GET['date'])) {
                throw new Exception('Date parameter is required');
            }

            $date = $_GET['date'];
            $serviceId = null;
            $appointmentId = null;
            
            // Check if this is for a new booking or rescheduling
            if (isset($_GET['service_id'])) {
                $serviceId = (int)$_GET['service_id'];
                $service = $this->serviceModel->getServiceById($serviceId);
            } elseif (isset($_GET['appointment_id'])) {
                $appointmentId = (int)$_GET['appointment_id'];
                // Get the appointment details to get the service
                $appointment = $this->appointmentModel->getAppointmentById($appointmentId);
                if (!$appointment) {
                    throw new Exception('Appointment not found');
                }
                $service = $this->serviceModel->getServiceById($appointment['service_id']);
            } else {
                throw new Exception('Either service_id or appointment_id is required');
            }
            
            if (!$service) {
                throw new Exception('Service not found');
            }

            // Get all available time slots for the date
            $availableSlots = $this->getTimeSlots($date, $service['duration']);
            
            echo json_encode([
                'success' => true,
                'timeslots' => $availableSlots
            ]);
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function getTimeSlots($date, $duration) {
        // Business hours
        $startTime = '09:00';
        $endTime = '17:00';
        
        $slots = [];
        $currentTime = strtotime($startTime);
        $endTimeStamp = strtotime($endTime);
        
        while ($currentTime <= $endTimeStamp) {
            $timeSlot = date('H:i', $currentTime);
            $slots[] = $timeSlot;
            $currentTime = strtotime('+' . $duration . ' minutes', $currentTime);
        }
        
        return $slots;
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
                        notes, created_at
                    ) VALUES (
                        :user_id, :service_id, :therapist_id,
                        :date, :start_time, :end_time,
                        :notes, NOW()
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

            // Get promo_id if promo code was used
            $promoId = null;
            if (!empty($data['promo_code'])) {
                $sql = "SELECT promo_id FROM Promotions 
                        WHERE promo_code = ? 
                        AND start_date <= CURRENT_DATE 
                        AND end_date >= CURRENT_DATE 
                        AND is_deleted = FALSE";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$data['promo_code']]);
                $promo = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($promo) {
                    $promoId = $promo['promo_id'];
                }
            }

            // Generate transaction ID
            $transactionId = 'TXN' . date('Ymd') . str_pad($bookingId, 6, '0', STR_PAD_LEFT);

            // Insert payment record
            $sql = "INSERT INTO Payments (
                        appointment_id, amount, payment_method,
                        payment_status, original_amount, discount_amount,
                        promo_id, transaction_id, final_amount
                    ) VALUES (
                        :appointment_id, :amount, :payment_method,
                        'unpaid', :original_amount, :discount_amount,
                        :promo_id, :transaction_id, :final_amount
                    )";

            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute([
                'appointment_id' => $bookingId,
                'amount' => $data['final_amount'], 
                'payment_method' => $data['payment_method'],
                'original_amount' => $data['original_amount'],
                'discount_amount' => $data['discount_amount'] ?? 0,
                'promo_id' => $promoId,
                'transaction_id' => $transactionId,
                'final_amount' => $data['final_amount']
            ]);

            if (!$success) {
                throw new Exception('Failed to create payment record');
            }

            // Update availability
            $sql = "UPDATE Availability 
                   SET is_deleted = TRUE 
                   WHERE therapist_id = :therapist_id 
                   AND date = :date 
                   AND start_time <= :start_time 
                   AND end_time >= :end_time";

            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute([
                'therapist_id' => $data['therapist_id'],
                'date' => $data['date'],
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s')
            ]);

            if (!$success) {
                throw new Exception('Failed to update availability');
            }

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

    public function validatePromo() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $promoCode = $data['promo_code'] ?? '';
        
        if (empty($promoCode)) {
            echo json_encode(['success' => false, 'message' => 'Promo code is required']);
            return;
        }

        try {
            // Query the database for the promo code
            $sql = "SELECT * FROM Promotions 
                    WHERE promo_code = ? 
                    AND start_date <= CURRENT_DATE 
                    AND end_date >= CURRENT_DATE 
                    AND is_deleted = FALSE";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$promoCode]);
            $promo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($promo) {
                echo json_encode([
                    'success' => true,
                    'discount_percent' => $promo['discount_percent'],
                    'message' => 'Promo code applied successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid or expired promo code'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error validating promo code'
            ]);
        }
        exit;
    }
} 
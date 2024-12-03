<?php

class AppointmentApiController {
    private $db;
    private $appointment;

    public function __construct($db) {
        $this->db = $db;
        $this->appointment = new Appointment($db);
    }

    public function cancel() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        if (!isset($_POST['appointment_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Appointment ID is required']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $appointmentId = $_POST['appointment_id'];

        try {
            if ($this->appointment->cancelAppointment($appointmentId, $userId)) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Failed to cancel appointment']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getAvailableTherapists() {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Date is required']);
            return;
        }

        try {
            $date = $data['date'];
            
            // Get all therapists and their availability for the selected date
            $sql = "SELECT 
                    t.therapist_id as id,
                    t.name,
                    t.start_time,
                    t.end_time
                FROM Therapists t
                WHERE t.is_active = 1
                AND NOT EXISTS (
                    SELECT 1 FROM Appointments a 
                    WHERE a.therapist_id = t.therapist_id 
                    AND a.appointment_date = ?
                    AND a.status IN ('pending', 'confirmed')
                )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$date]);
            $therapists = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($therapists);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getAvailableSlots() {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['date']) || !isset($data['therapist_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Date and therapist ID are required']);
            return;
        }

        try {
            $date = $data['date'];
            $therapistId = $data['therapist_id'];
            
            // Get therapist's working hours
            $sql = "SELECT start_time, end_time 
                   FROM Therapists 
                   WHERE therapist_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$therapistId]);
            $therapist = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$therapist) {
                throw new Exception('Therapist not found');
            }

            // Get booked appointments for the day
            $sql = "SELECT start_time 
                   FROM Appointments 
                   WHERE therapist_id = ? 
                   AND appointment_date = ? 
                   AND status IN ('pending', 'confirmed')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$therapistId, $date]);
            $bookedSlots = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Generate available time slots
            $slots = [];
            $start = strtotime($therapist['start_time']);
            $end = strtotime($therapist['end_time']);
            $interval = 30 * 60; // 30 minutes in seconds

            for ($time = $start; $time < $end; $time += $interval) {
                $timeStr = date('H:i:s', $time);
                if (!in_array($timeStr, $bookedSlots)) {
                    $slots[] = [
                        'time' => $timeStr,
                        'formatted_time' => date('g:i A', $time)
                    ];
                }
            }

            echo json_encode($slots);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function reschedule() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        $requiredFields = ['appointment_id', 'therapist_id', 'date', 'start_time'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
                return;
            }
        }

        $userId = $_SESSION['user_id'];
        $appointmentId = $data['appointment_id'];
        $therapistId = $data['therapist_id'];
        $newDate = $data['date'];
        $newTime = $data['start_time'];

        try {
            // Get service duration for this appointment
            $sql = "SELECT s.duration 
                   FROM Appointments a 
                   JOIN Services s ON a.service_id = s.service_id 
                   WHERE a.appointment_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$appointmentId]);
            $duration = $stmt->fetchColumn();

            if (!$duration) {
                throw new Exception('Could not determine service duration');
            }

            // Calculate end time
            $startDateTime = new DateTime($newTime);
            $endDateTime = clone $startDateTime;
            $endDateTime->add(new DateInterval('PT' . $duration . 'M'));
            $endTime = $endDateTime->format('H:i:s');

            // Check if the time slot is still available
            $sql = "SELECT 1 FROM Appointments 
                   WHERE therapist_id = ? 
                   AND appointment_date = ? 
                   AND (
                       (start_time <= ? AND end_time > ?) OR
                       (start_time < ? AND end_time >= ?) OR
                       (start_time >= ? AND end_time <= ?)
                   )
                   AND status IN ('pending', 'confirmed')
                   AND appointment_id != ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $therapistId, $newDate,
                $newTime, $newTime,
                $endTime, $endTime,
                $newTime, $endTime,
                $appointmentId
            ]);
            
            if ($stmt->fetchColumn()) {
                http_response_code(400);
                echo json_encode(['error' => 'This time slot is no longer available']);
                return;
            }

            // Update the appointment
            $sql = "UPDATE Appointments 
                   SET appointment_date = ?, 
                       start_time = ?,
                       end_time = ?,
                       therapist_id = ?,
                       updated_at = CURRENT_TIMESTAMP 
                   WHERE appointment_id = ? 
                   AND user_id = ? 
                   AND status IN ('pending', 'confirmed')";
            
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([$newDate, $newTime, $endTime, $therapistId, $appointmentId, $userId])) {
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true]);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'No appointment found or not eligible for rescheduling']);
                }
            } else {
                throw new Exception('Database error while rescheduling');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updateStatus() {
        header('Content-Type: application/json');
        
        // Check if user is admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] !== 3) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized. Admin access required.']);
            return;
        }

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Get appointment ID from URL parameters
        $urlParts = explode('/', $_SERVER['REQUEST_URI']);
        $appointmentId = intval($urlParts[array_search('bookings', $urlParts) + 1]);

        if (!$appointmentId || !isset($data['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Appointment ID and status are required']);
            return;
        }

        // Validate status
        $allowedStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($data['status'], $allowedStatuses)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid status']);
            return;
        }

        try {
            // Update appointment status
            $sql = "UPDATE Appointments 
                   SET status = ?, 
                       updated_at = CURRENT_TIMESTAMP 
                   WHERE appointment_id = ?";
            
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([$data['status'], $appointmentId])) {
                if ($stmt->rowCount() > 0) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Appointment status updated successfully'
                    ]);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Appointment not found']);
                }
            } else {
                throw new Exception('Database error while updating status');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

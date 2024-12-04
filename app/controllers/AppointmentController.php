<?php

class AppointmentController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // API endpoint to get appointment details
    public function getAppointmentDetails() {
        try {
            $appointmentId = $_GET['id'] ?? null;
            
            if (!$appointmentId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Appointment ID is required']);
                return;
            }

            $stmt = $this->db->prepare("
                SELECT 
                    a.*,
                    s.service_name,
                    s.duration,
                    CONCAT(u.first_name, ' ', u.last_name) as therapist_name
                FROM appointments a
                JOIN services s ON a.service_id = s.service_id
                JOIN users u ON a.therapist_id = u.user_id
                WHERE a.appointment_id = ?
            ");
            
            $stmt->execute([$appointmentId]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$appointment) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Appointment not found']);
                return;
            }

            echo json_encode(['success' => true, 'appointment' => $appointment]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to fetch appointment details']);
        }
    }

    // API endpoint to get available therapists
    public function getAvailableTherapists() {
        try {
            $date = $_GET['date'] ?? null;
            $serviceId = $_GET['service_id'] ?? null;

            if (!$date || !$serviceId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Date and service ID are required']);
                return;
            }

            // Get all therapists who can provide this service
            $stmt = $this->db->prepare("
                SELECT DISTINCT 
                    u.user_id,
                    CONCAT(u.first_name, ' ', u.last_name) as full_name,
                    t.start_time,
                    t.end_time
                FROM users u
                JOIN therapist_services ts ON u.user_id = ts.therapist_id
                JOIN therapist_availability t ON u.user_id = t.therapist_id
                WHERE ts.service_id = ?
                AND t.day_of_week = DAYOFWEEK(?)
                AND u.role = 'therapist'
                AND u.status = 'active'
            ");
            
            $stmt->execute([$serviceId, $date]);
            $availableTherapists = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get booked appointments for the date
            $stmt = $this->db->prepare("
                SELECT 
                    therapist_id,
                    start_time,
                    end_time
                FROM appointments
                WHERE appointment_date = ?
                AND status NOT IN ('canceled')
            ");
            
            $stmt->execute([$date]);
            $bookedSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Filter out therapists who are fully booked
            $availableTherapists = array_filter($availableTherapists, function($therapist) use ($bookedSlots) {
                foreach ($bookedSlots as $slot) {
                    if ($slot['therapist_id'] == $therapist['user_id']) {
                        // Check if the therapist's entire day is booked
                        if ($slot['start_time'] <= $therapist['start_time'] && 
                            $slot['end_time'] >= $therapist['end_time']) {
                            return false;
                        }
                    }
                }
                return true;
            });

            echo json_encode([
                'success' => true, 
                'therapists' => array_values($availableTherapists)
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to fetch available therapists']);
        }
    }

    // API endpoint to reschedule appointment
    public function rescheduleAppointment() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['appointment_id']) || !isset($data['new_date']) || 
                !isset($data['new_time']) || !isset($data['therapist_id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                return;
            }

            $appointmentId = $data['appointment_id'];
            $newDate = $data['new_date'];
            $newTime = $data['new_time'];
            $therapistId = $data['therapist_id'];

            // Get appointment details to calculate end time
            $stmt = $this->db->prepare("
                SELECT s.duration
                FROM appointments a
                JOIN services s ON a.service_id = s.service_id
                WHERE a.appointment_id = ?
            ");
            $stmt->execute([$appointmentId]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calculate end time
            $endTime = date('H:i:s', strtotime($newTime . ' + ' . $service['duration'] . ' minutes'));

            // Check for conflicts
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as conflict_count
                FROM appointments
                WHERE therapist_id = ?
                AND appointment_date = ?
                AND status NOT IN ('canceled')
                AND (
                    (start_time <= ? AND end_time > ?)
                    OR (start_time < ? AND end_time >= ?)
                )
                AND appointment_id != ?
            ");
            
            $stmt->execute([
                $therapistId,
                $newDate,
                $newTime,
                $newTime,
                $endTime,
                $endTime,
                $appointmentId
            ]);

            if ($stmt->fetchColumn() > 0) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'Selected time slot is not available']);
                return;
            }

            // Update the appointment
            $stmt = $this->db->prepare("
                UPDATE appointments
                SET 
                    appointment_date = ?,
                    start_time = ?,
                    end_time = ?,
                    therapist_id = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE appointment_id = ?
            ");

            $success = $stmt->execute([
                $newDate,
                $newTime,
                $endTime,
                $therapistId,
                $appointmentId
            ]);

            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Appointment rescheduled successfully']);
            } else {
                throw new Exception('Failed to update appointment');
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to reschedule appointment']);
        }
    }

    // API endpoint to get available time slots
    public function getAvailableTimeSlots() {
        try {
            $date = $_GET['date'] ?? null;
            $appointmentId = $_GET['appointment_id'] ?? null;

            if (!$date || !$appointmentId) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Date and appointment ID are required']);
                return;
            }

            // Get appointment and service details
            $stmt = $this->db->prepare("
                SELECT 
                    a.*,
                    s.duration,
                    t.start_time as therapist_start,
                    t.end_time as therapist_end
                FROM appointments a
                JOIN services s ON a.service_id = s.service_id
                JOIN therapist_availability t ON a.therapist_id = t.therapist_id
                WHERE a.appointment_id = ?
                AND t.day_of_week = DAYOFWEEK(?)
            ");
            $stmt->execute([$appointmentId, $date]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$appointment) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Appointment not found']);
                return;
            }

            // Get booked appointments for the therapist on that date
            $stmt = $this->db->prepare("
                SELECT start_time, end_time
                FROM appointments
                WHERE therapist_id = ?
                AND appointment_date = ?
                AND appointment_id != ?
                AND status NOT IN ('canceled')
            ");
            $stmt->execute([$appointment['therapist_id'], $date, $appointmentId]);
            $bookedSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Generate available time slots
            $availableSlots = $this->generateTimeSlots(
                $appointment['therapist_start'],
                $appointment['therapist_end'],
                $appointment['duration'],
                $bookedSlots
            );

            echo json_encode([
                'success' => true,
                'timeslots' => $availableSlots
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to fetch available time slots']);
        }
    }

    private function generateTimeSlots($startTime, $endTime, $duration, $bookedSlots) {
        $slots = [];
        $interval = 30; // 30-minute intervals
        
        $currentTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        
        while ($currentTime + ($duration * 60) <= $endTime) {
            $slotStart = date('H:i:s', $currentTime);
            $slotEnd = date('H:i:s', $currentTime + ($duration * 60));
            
            $isAvailable = true;
            foreach ($bookedSlots as $booked) {
                if (
                    (strtotime($slotStart) >= strtotime($booked['start_time']) && 
                     strtotime($slotStart) < strtotime($booked['end_time'])) ||
                    (strtotime($slotEnd) > strtotime($booked['start_time']) && 
                     strtotime($slotEnd) <= strtotime($booked['end_time']))
                ) {
                    $isAvailable = false;
                    break;
                }
            }
            
            if ($isAvailable) {
                $slots[] = [
                    'time' => $slotStart,
                    'end_time' => $slotEnd
                ];
            }
            
            $currentTime += $interval * 60;
        }
        
        return $slots;
    }
} 
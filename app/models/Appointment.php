<?php

class Appointment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAppointmentsByUserId($userId, $filters = []) {
        $sql = "SELECT a.*, s.service_name, s.duration, u.full_name as therapist_name,
                (SELECT COUNT(*) FROM Reviews r WHERE r.appointment_id = a.appointment_id) as has_review
                FROM Appointments a
                JOIN Services s ON a.service_id = s.service_id
                LEFT JOIN Users u ON a.therapist_id = u.user_id
                WHERE a.user_id = ? AND a.is_deleted = FALSE";
        
        $params = [$userId];

        if (!empty($filters['status'])) {
            $sql .= " AND a.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(a.appointment_date) = ?";
            $params[] = $filters['date'];
        }

        $sql .= " ORDER BY a.appointment_date DESC, a.start_time DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUpcomingAppointments($userId) {
        $sql = "SELECT a.*, s.service_name, s.duration, u.full_name as therapist_name
                FROM Appointments a
                JOIN Services s ON a.service_id = s.service_id
                LEFT JOIN Users u ON a.therapist_id = u.user_id
                WHERE a.user_id = ? 
                AND a.status IN ('pending', 'confirmed')
                AND a.appointment_date >= CURRENT_DATE
                AND a.is_deleted = FALSE
                ORDER BY a.appointment_date ASC, a.start_time ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // @deprecated Use getUpcomingAppointments() instead
    public function getNextAppointment($userId) {
        $sql = "SELECT a.*, s.service_name 
                FROM Appointments a
                JOIN Services s ON a.service_id = s.service_id
                WHERE a.user_id = ? 
                AND a.status IN ('pending', 'confirmed')
                AND a.appointment_date >= CURRENT_DATE
                AND a.is_deleted = FALSE
                ORDER BY a.appointment_date ASC, a.start_time ASC
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }

    public function cancelAppointment($appointmentId, $userId) {
        $sql = "UPDATE Appointments 
                SET status = 'canceled', 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE appointment_id = ? 
                AND user_id = ? 
                AND status IN ('pending', 'confirmed')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$appointmentId, $userId]);
    }

    public function getAppointmentCounts($userId) {
        $sql = "SELECT 
                SUM(CASE WHEN status IN ('pending', 'confirmed') AND appointment_date >= CURRENT_DATE THEN 1 ELSE 0 END) as upcoming_count,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count
                FROM Appointments 
                WHERE user_id = ? AND is_deleted = FALSE";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'upcoming_count' => (int)$result['upcoming_count'] ?? 0,
            'completed_count' => (int)$result['completed_count'] ?? 0
        ];
    }

    public function getBookingCounts() {
        try {
            $sql = "SELECT 
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
                    COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
                    COUNT(CASE WHEN status = 'canceled' THEN 1 END) as canceled
                    FROM appointments
                    WHERE is_deleted = FALSE";
            
            error_log("Executing booking counts query: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Failed to prepare booking counts query: " . print_r($this->db->errorInfo(), true));
                return [
                    'pending' => 0,
                    'confirmed' => 0,
                    'completed' => 0,
                    'canceled' => 0
                ];
            }
            
            $success = $stmt->execute();
            if (!$success) {
                error_log("Failed to execute booking counts query: " . print_r($stmt->errorInfo(), true));
                return [
                    'pending' => 0,
                    'confirmed' => 0,
                    'completed' => 0,
                    'canceled' => 0
                ];
            }
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Booking counts result: " . print_r($result, true));
            
            return [
                'pending' => (int)($result['pending'] ?? 0),
                'confirmed' => (int)($result['confirmed'] ?? 0),
                'completed' => (int)($result['completed'] ?? 0),
                'canceled' => (int)($result['canceled'] ?? 0)
            ];
            
        } catch (PDOException $e) {
            error_log("Error getting booking counts: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [
                'pending' => 0,
                'confirmed' => 0,
                'completed' => 0,
                'canceled' => 0
            ];
        }
    }

    public function getAllBookings($filters = []) {
        try {
            $sql = "SELECT a.*, s.service_name, 
                           u1.full_name as customer_name,
                           u2.full_name as therapist_name,
                           COALESCE(p.status, 'pending') as payment_status
                    FROM appointments a
                    JOIN services s ON a.service_id = s.service_id
                    JOIN users u1 ON a.user_id = u1.user_id
                    LEFT JOIN users u2 ON a.therapist_id = u2.user_id
                    LEFT JOIN payments p ON a.appointment_id = p.appointment_id
                    WHERE a.is_deleted = FALSE";
            
            $params = [];
            
            // Add filters
            if (!empty($filters['status'])) {
                $sql .= " AND a.status = ?";
                $params[] = $filters['status'];
            }
            
            if (!empty($filters['date'])) {
                $sql .= " AND DATE(a.appointment_date) = ?";
                $params[] = $filters['date'];
            }
            
            if (!empty($filters['therapist'])) {
                $sql .= " AND a.therapist_id = ?";
                $params[] = $filters['therapist'];
            }
            
            $sql .= " ORDER BY a.appointment_date DESC, a.start_time DESC";
            
            if (!empty($filters['limit'])) {
                $sql .= " LIMIT ?";
                $params[] = (int)$filters['limit'];
            }
            
            // Debug log
            error_log("SQL Query: " . $sql);
            error_log("Params: " . print_r($params, true));
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . print_r($this->db->errorInfo(), true));
                return [];
            }
            
            $success = $stmt->execute($params);
            if (!$success) {
                error_log("Execute failed: " . print_r($stmt->errorInfo(), true));
                return [];
            }
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Number of results: " . count($results));
            error_log("Results: " . print_r($results, true));
            
            return $results;
            
        } catch (PDOException $e) {
            error_log("Error getting all bookings: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [];
        }
    }

    public function isTimeSlotAvailable($date, $time, $therapistId, $excludeAppointmentId = null) {
        $sql = "SELECT COUNT(*) as count FROM appointments 
                WHERE appointment_date = ? 
                AND start_time = ? 
                AND therapist_id = ?
                AND status IN ('pending', 'confirmed')";
        $params = [$date, $time, $therapistId];
        
        if ($excludeAppointmentId) {
            $sql .= " AND appointment_id != ?";
            $params[] = $excludeAppointmentId;
        }
        
        $result = $this->db->query($sql, $params);
        return $result[0]['count'] == 0;
    }
} 
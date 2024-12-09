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

    public function belongsToUser($appointmentId, $userId) {
        $sql = "SELECT COUNT(*) FROM Appointments 
                WHERE appointment_id = ? AND user_id = ? AND is_deleted = FALSE";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$appointmentId, $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getBookingCounts() {
        try {
            $sql = "SELECT 
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
                    COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
                    COUNT(CASE WHEN status = 'canceled' THEN 1 END) as canceled
                    FROM Appointments
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
            error_log("getAllBookings called with filters: " . print_r($filters, true));

            $sql = "SELECT 
                    a.*,
                    s.service_name,
                    s.price as service_price,
                    u1.full_name as customer_name,
                    u1.email as customer_email,
                    u1.phone_number as customer_phone,
                    u2.full_name as therapist_name
                FROM Appointments a
                LEFT JOIN Services s ON a.service_id = s.service_id
                LEFT JOIN Users u1 ON a.user_id = u1.user_id
                LEFT JOIN Users u2 ON a.therapist_id = u2.user_id
                WHERE a.is_deleted = FALSE";
            
            $params = [];
            
            // Add filters with debug logging
            if (!empty($filters['status'])) {
                error_log("Applying status filter: " . $filters['status']);
                $sql .= " AND a.status = ?";
                $params[] = $filters['status'];
            }
            
            if (!empty($filters['date'])) {
                error_log("Applying date filter: " . $filters['date']);
                $sql .= " AND DATE(a.appointment_date) = ?";
                $params[] = $filters['date'];
            }
            
            if (!empty($filters['therapist'])) {
                error_log("Applying therapist filter: " . $filters['therapist']);
                $sql .= " AND a.therapist_id = ?";
                $params[] = $filters['therapist'];
            }
            
            // Order by most recent first
            $sql .= " ORDER BY a.created_at DESC";
            
            error_log("Final SQL Query: " . $sql);
            error_log("Query Parameters: " . print_r($params, true));
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Failed to prepare query: " . print_r($this->db->errorInfo(), true));
                return [];
            }
            
            $success = $stmt->execute($params);
            if (!$success) {
                error_log("Failed to execute query: " . print_r($stmt->errorInfo(), true));
                return [];
            }
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Query returned " . count($results) . " results");
            error_log("First result: " . print_r(!empty($results) ? $results[0] : 'No results', true));
            
            return $results;
            
        } catch (PDOException $e) {
            error_log("Database error in getAllBookings: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [];
        }
    }

    public function isTimeSlotAvailable($date, $time, $therapistId, $excludeAppointmentId = null) {
        $sql = "SELECT COUNT(*) as count FROM Appointments 
                WHERE appointment_date = ? 
                AND start_time = ? 
                AND therapist_id = ? 
                AND status != 'canceled'
                AND is_deleted = FALSE";
        $params = [$date, $time, $therapistId];
        
        if ($excludeAppointmentId) {
            $sql .= " AND appointment_id != ?";
            $params[] = $excludeAppointmentId;
        }
        
        $result = $this->db->query($sql, $params);
        return $result[0]['count'] == 0;
    }
}
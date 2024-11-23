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
} 
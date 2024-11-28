<?php

class Availability {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getTherapistWeeklyAvailability($therapistId, $weekStart) {
        $sql = "SELECT 
                    a.*, 
                    CASE WHEN apt.appointment_id IS NOT NULL THEN TRUE ELSE FALSE END as is_booked
                FROM Availability a
                LEFT JOIN Appointments apt ON a.therapist_id = apt.therapist_id 
                    AND a.date = apt.appointment_date 
                    AND (
                        (apt.start_time <= a.start_time AND apt.end_time > a.start_time)
                        OR (apt.start_time < a.end_time AND apt.end_time >= a.end_time)
                        OR (apt.start_time >= a.start_time AND apt.end_time <= a.end_time)
                    )
                    AND apt.status != 'canceled'
                WHERE a.therapist_id = :therapist_id 
                AND a.date BETWEEN :week_start AND DATE_ADD(:week_start, INTERVAL 6 DAY)
                AND a.is_deleted = FALSE
                ORDER BY a.date, a.start_time";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':therapist_id' => $therapistId,
            ':week_start' => $weekStart
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addAvailability($data) {
        $sql = "INSERT INTO Availability (therapist_id, date, start_time, end_time) 
                VALUES (:therapist_id, :date, :start_time, :end_time)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':therapist_id' => $data['therapist_id'],
            ':date' => $data['date'],
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time']
        ]);
    }
} 
<?php

class Availability {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getTherapistWeeklyAvailability($therapistId, $weekStart) {
        try {
            $weekEnd = date('Y-m-d', strtotime($weekStart . ' +7 days'));
            
            error_log("Fetching availability from $weekStart to $weekEnd for therapist $therapistId");
            
            $sql = "SELECT 
                        a.*, 
                        CASE WHEN apt.appointment_id IS NOT NULL THEN 1 ELSE 0 END as is_booked
                    FROM Availability a
                    LEFT JOIN Appointments apt ON 
                        apt.therapist_id = a.therapist_id 
                        AND apt.appointment_date = a.date
                        AND apt.status != 'canceled'
                        AND apt.is_deleted = FALSE
                        AND (
                            (apt.start_time <= a.start_time AND apt.end_time > a.start_time)
                            OR (apt.start_time < a.end_time AND apt.end_time >= a.end_time)
                            OR (apt.start_time >= a.start_time AND apt.end_time <= a.end_time)
                        )
                    WHERE a.therapist_id = :therapist_id 
                    AND a.date >= :week_start
                    AND a.date < :week_end
                    AND a.is_deleted = FALSE
                    ORDER BY a.date, a.start_time";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':therapist_id', $therapistId, PDO::PARAM_INT);
            $stmt->bindParam(':week_start', $weekStart, PDO::PARAM_STR);
            $stmt->bindParam(':week_end', $weekEnd, PDO::PARAM_STR);
            
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Found " . count($result) . " availability slots");
            error_log("Sample slot: " . print_r(!empty($result) ? $result[0] : 'No slots', true));
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("Database error in getTherapistWeeklyAvailability: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        }
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

    public function saveTherapistAvailability($therapistId, $dayOfWeek, $startTime, $endTime, $repeatWeekly = false) {
        try {
            $this->db->beginTransaction();

            // Get the date for the specified day of week
            $date = new DateTime();
            $currentDayOfWeek = $date->format('l');
            $daysToAdd = array_search($dayOfWeek, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']) - 
                        array_search($currentDayOfWeek, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            
            if ($daysToAdd < 0) {
                $daysToAdd += 7;
            }
            
            $date->modify("+{$daysToAdd} days");

            // If repeat weekly, add availability for the next 12 weeks
            $weeks = $repeatWeekly ? 12 : 1;
            
            for ($i = 0; $i < $weeks; $i++) {
                $sql = "INSERT INTO Availability (
                    therapist_id, date, start_time, end_time, created_at, updated_at
                ) VALUES (
                    :therapist_id, :date, :start_time, :end_time, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
                )";

                $stmt = $this->db->prepare($sql);
                $success = $stmt->execute([
                    ':therapist_id' => $therapistId,
                    ':date' => $date->format('Y-m-d'),
                    ':start_time' => $startTime,
                    ':end_time' => $endTime
                ]);

                if (!$success) {
                    throw new Exception("Failed to insert availability for " . $date->format('Y-m-d'));
                }

                // Move to next week
                $date->modify('+7 days');
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error saving therapist availability: " . $e->getMessage());
            return false;
        }
    }
} 
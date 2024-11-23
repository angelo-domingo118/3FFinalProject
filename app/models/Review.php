<?php

class Review {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getReviewsByUserId($userId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT r.*, s.service_name, u.full_name as therapist_name
                FROM Reviews r
                JOIN Appointments a ON r.appointment_id = a.appointment_id
                JOIN Services s ON a.service_id = s.service_id
                LEFT JOIN Users u ON a.therapist_id = u.user_id
                WHERE r.user_id = :user_id AND r.is_deleted = FALSE
                ORDER BY r.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        // Bind parameters with explicit types
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalReviewCount($userId) {
        $sql = "SELECT COUNT(*) FROM Reviews 
                WHERE user_id = :user_id AND is_deleted = FALSE";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function createReview($data) {
        $sql = "INSERT INTO Reviews (appointment_id, user_id, rating, comment) 
                VALUES (:appointment_id, :user_id, :rating, :comment)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':appointment_id', $data['appointment_id'], PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':rating', $data['rating'], PDO::PARAM_INT);
        $stmt->bindValue(':comment', $data['comment'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }
} 
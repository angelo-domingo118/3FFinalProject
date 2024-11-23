<?php

class Promotion {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getActivePromotions() {
        $today = date('Y-m-d');
        $query = "SELECT * FROM Promotions 
                 WHERE start_date <= ? 
                 AND end_date >= ? 
                 AND is_deleted = FALSE 
                 ORDER BY discount_percent DESC";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$today, $today]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
} 
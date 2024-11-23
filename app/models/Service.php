<?php
// app/models/Service.php

class Service {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAllServices() {
        $sql = "SELECT * FROM Services WHERE is_deleted = FALSE ORDER BY popularity DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiceById($serviceId) {
        $sql = "SELECT * FROM Services WHERE service_id = ? AND is_deleted = FALSE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$serviceId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPopularServices($limit = 6) {
        $sql = "SELECT * FROM Services WHERE is_deleted = FALSE AND is_popular = TRUE ORDER BY popularity DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServicesByType($type) {
        $sql = "SELECT * FROM Services WHERE service_type = ? AND is_deleted = FALSE ORDER BY popularity DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilteredServices($filters, $sort) {
        $sql = "SELECT * FROM Services WHERE is_deleted = 0";
        $params = [];

        // Apply filters
        if (!empty($filters['types'])) {
            $placeholders = str_repeat('?,', count($filters['types']) - 1) . '?';
            $sql .= " AND service_type IN ($placeholders)";
            $params = array_merge($params, $filters['types']);
        }

        if (!empty($filters['price_max'])) {
            $sql .= " AND price <= ?";
            $params[] = $filters['price_max'];
        }

        if (!empty($filters['duration'])) {
            $sql .= " AND duration = ?";
            $params[] = $filters['duration'];
        }

        // Apply sorting
        $sql .= match ($sort) {
            'price_asc' => " ORDER BY price ASC",
            'price_desc' => " ORDER BY price DESC",
            'duration' => " ORDER BY duration ASC",
            default => " ORDER BY popularity DESC" // Default sort by popularity
        };

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedServices() {
        $query = "SELECT * FROM Services 
                  WHERE is_popular = TRUE 
                  AND is_deleted = FALSE 
                  ORDER BY popularity DESC 
                  LIMIT 3";
                  
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // Add more methods as needed
} 
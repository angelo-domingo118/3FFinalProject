<?php
// app/models/Service.php

class Service {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllServices() {
        $stmt = $this->pdo->prepare("SELECT * FROM Services");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeaturedServices() {
        $stmt = $this->pdo->prepare("SELECT * FROM Services WHERE is_deleted = 0 LIMIT 6");
        $stmt->execute();
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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add more methods as needed
} 
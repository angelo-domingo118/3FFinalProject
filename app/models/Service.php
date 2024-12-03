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

        // Apply search filter
        if (!empty($filters['search'])) {
            $sql .= " AND (service_name LIKE ? OR description LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Apply other filters
        if (!empty($filters['types']) && is_array($filters['types'])) {
            $placeholders = str_repeat('?,', count($filters['types']) - 1) . '?';
            $sql .= " AND service_type IN ($placeholders)";
            $params = array_merge($params, $filters['types']);
        }

        if (!empty($filters['price_min'])) {
            $sql .= " AND price >= ?";
            $params[] = $filters['price_min'];
        }

        if (!empty($filters['price_max'])) {
            $sql .= " AND price <= ?";
            $params[] = $filters['price_max'];
        }

        if (!empty($filters['duration']) && is_array($filters['duration'])) {
            $placeholders = str_repeat('?,', count($filters['duration']) - 1) . '?';
            $sql .= " AND duration IN ($placeholders)";
            $params = array_merge($params, $filters['duration']);
        }

        // Apply sorting
        $sql .= match ($sort) {
            'price_asc' => " ORDER BY price ASC",
            'price_desc' => " ORDER BY price DESC",
            'duration' => " ORDER BY duration ASC",
            default => " ORDER BY popularity DESC"
        };

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getFilteredServices: " . $e->getMessage());
            return [];
        }
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

    public function getServiceCount() {
        try {
            $sql = "SELECT COUNT(*) as count FROM Services WHERE is_active = TRUE";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            error_log("Error getting service count: " . $e->getMessage());
            return 0;
        }
    }

    public function createService($data) {
        try {
            $sql = "INSERT INTO Services (
                service_name, description, duration, price, 
                service_type, is_popular, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                $data['service_name'],
                $data['description'],
                $data['duration'],
                $data['price'],
                $data['service_type'],
                isset($data['is_popular']) ? 1 : 0
            ]);
            
            if ($success) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating service: " . $e->getMessage());
            return false;
        }
    }

    public function updateService($serviceId, $data) {
        try {
            $sql = "UPDATE Services SET 
                    service_name = ?,
                    description = ?,
                    duration = ?,
                    price = ?,
                    service_type = ?,
                    is_popular = ?,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE service_id = ? AND is_deleted = FALSE";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['service_name'],
                $data['description'],
                $data['duration'],
                $data['price'],
                $data['service_type'],
                isset($data['is_popular']) ? 1 : 0,
                $serviceId
            ]);
        } catch (PDOException $e) {
            error_log("Error updating service: " . $e->getMessage());
            return false;
        }
    }

    public function deleteService($serviceId) {
        try {
            // Soft delete
            $sql = "UPDATE Services SET 
                    is_deleted = TRUE,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE service_id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$serviceId]);
        } catch (PDOException $e) {
            error_log("Error deleting service: " . $e->getMessage());
            return false;
        }
    }

    // Add more methods as needed
} 
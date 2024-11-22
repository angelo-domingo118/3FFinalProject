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

    // Add more methods as needed
} 
<?php
// app/controllers/ServiceController.php

class ServiceController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // Fetch services from the database
        $stmt = $this->pdo->prepare("SELECT * FROM Services");
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once '../app/views/services.php';
    }
} 
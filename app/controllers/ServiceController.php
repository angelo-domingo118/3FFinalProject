<?php
// app/controllers/ServiceController.php

class ServiceController {
    private $pdo;
    private $serviceModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->serviceModel = new Service($pdo);
    }

    public function index() {
        $filters = [
            'types' => $_GET['type'] ?? [],
            'price_max' => $_GET['price_max'] ?? null,
            'duration' => $_GET['duration'] ?? null
        ];
        
        $sort = $_GET['sort'] ?? 'popular';
        
        $services = $this->serviceModel->getFilteredServices($filters, $sort);
        require_once '../app/views/services.php';
    }
} 
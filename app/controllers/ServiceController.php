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
            'types' => isset($_GET['type']) && is_array($_GET['type']) ? $_GET['type'] : [],
            'price_min' => isset($_GET['price_min']) ? (float)$_GET['price_min'] : null,
            'price_max' => isset($_GET['price_max']) ? (float)$_GET['price_max'] : null,
            'duration' => isset($_GET['duration']) && is_array($_GET['duration']) ? $_GET['duration'] : [],
            'search' => isset($_GET['search']) ? trim($_GET['search']) : null
        ];
        
        $sort = $_GET['sort'] ?? 'popular';
        
        $services = $this->serviceModel->getFilteredServices($filters, $sort);
        require_once '../app/views/services.php';
    }
} 
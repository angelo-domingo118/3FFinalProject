<?php
// app/controllers/HomeController.php

class HomeController {
    private $pdo;
    private $serviceModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->serviceModel = new Service($pdo);
    }

    public function index() {
        // Fetch featured services
        $services = $this->serviceModel->getFeaturedServices();
        require_once '../app/views/home.php';
    }
} 
<?php
// app/controllers/HomeController.php

class HomeController {
    private $db;
    private $service;

    public function __construct($db) {
        $this->db = $db;
        require_once '../app/models/Service.php';
        $this->service = new Service($db);
    }

    public function index() {
        // Get featured services
        $featuredServices = $this->service->getFeaturedServices();
        
        // Get all services for the services section
        $allServices = $this->service->getAllServices();

        // Load the home view with the services data
        $data = [
            'featured_services' => $featuredServices,
            'all_services' => $allServices
        ];
        
        // Extract data to make variables available in view
        extract($data);
        
        require_once '../app/views/home.php';
    }
} 
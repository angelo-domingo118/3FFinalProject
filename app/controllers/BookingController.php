<?php

class BookingController {
    private $pdo;
    private $serviceModel;
    private $userModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        require_once '../app/models/Service.php';
        require_once '../app/models/User.php';
        $this->serviceModel = new Service($pdo);
        $this->userModel = new User($pdo);
    }

    public function index($serviceId = null) {
        // Get all services
        $services = $this->serviceModel->getAllServices();
        
        // Get selected service if serviceId is provided
        $selectedService = null;
        if ($serviceId) {
            $selectedService = $this->serviceModel->getServiceById($serviceId);
        }

        // Get all therapists
        $therapists = $this->userModel->getTherapists();

        // Load the booking view
        require_once '../app/views/booking/index.php';
    }

    public function datetime() {
        if (!isset($_GET['service'])) {
            header('Location: ' . BASE_URL . '/public/booking');
            exit;
        }

        $serviceId = $_GET['service'];
        $service = $this->serviceModel->getServiceById($serviceId);
        $therapists = $this->userModel->getTherapists();
        
        require_once '../app/views/booking/datetime.php';
    }
} 
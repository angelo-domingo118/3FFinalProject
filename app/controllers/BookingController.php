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

    public function index() {
        $serviceId = isset($_GET['service']) ? $_GET['service'] : null;
        $services = $this->serviceModel->getAllServices();
        $selectedService = $serviceId ? $this->serviceModel->getServiceById($serviceId) : null;
        
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
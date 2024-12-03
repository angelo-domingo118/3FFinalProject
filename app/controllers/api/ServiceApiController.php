<?php

class ServiceApiController {
    private $db;
    private $service;

    public function __construct($db) {
        $this->db = $db;
        $this->service = new Service($db);
    }

    private function isAdmin() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] === 3;
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function create() {
        if (!$this->isAdmin()) {
            $this->sendResponse(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$this->validateServiceData($data)) {
            $this->sendResponse(['error' => 'Invalid data provided'], 400);
        }

        $serviceId = $this->service->createService($data);
        if ($serviceId) {
            $this->sendResponse([
                'success' => true,
                'message' => 'Service created successfully',
                'service_id' => $serviceId
            ]);
        } else {
            $this->sendResponse(['error' => 'Failed to create service'], 500);
        }
    }

    public function update() {
        if (!$this->isAdmin()) {
            $this->sendResponse(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        // Get service ID from URL
        $urlParts = explode('/', $_SERVER['REQUEST_URI']);
        $serviceId = intval($urlParts[array_search('services', $urlParts) + 1]);

        if (!$serviceId || !$this->validateServiceData($data)) {
            $this->sendResponse(['error' => 'Invalid data provided'], 400);
        }

        if ($this->service->updateService($serviceId, $data)) {
            $this->sendResponse([
                'success' => true,
                'message' => 'Service updated successfully'
            ]);
        } else {
            $this->sendResponse(['error' => 'Failed to update service'], 500);
        }
    }

    public function delete() {
        if (!$this->isAdmin()) {
            $this->sendResponse(['error' => 'Unauthorized'], 401);
        }

        // Get service ID from URL
        $urlParts = explode('/', $_SERVER['REQUEST_URI']);
        $serviceId = intval($urlParts[array_search('services', $urlParts) + 1]);

        if (!$serviceId) {
            $this->sendResponse(['error' => 'Invalid service ID'], 400);
        }

        if ($this->service->deleteService($serviceId)) {
            $this->sendResponse([
                'success' => true,
                'message' => 'Service deleted successfully'
            ]);
        } else {
            $this->sendResponse(['error' => 'Failed to delete service'], 500);
        }
    }

    public function get($serviceId) {
        $service = $this->service->getServiceById($serviceId);
        
        if ($service) {
            $this->sendResponse($service);
        } else {
            $this->sendResponse(['error' => 'Service not found'], 404);
        }
    }

    private function validateServiceData($data) {
        $required = ['service_name', 'description', 'duration', 'price', 'service_type'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        // Validate numeric fields
        if (!is_numeric($data['duration']) || $data['duration'] <= 0) return false;
        if (!is_numeric($data['price']) || $data['price'] <= 0) return false;

        // Validate service type
        $validTypes = ['massage', 'facial', 'body'];
        if (!in_array($data['service_type'], $validTypes)) return false;

        return true;
    }
} 
<?php
// public/index.php

// Prevent errors from being displayed in the output
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffering
ob_start();

session_start();

require_once '../config/config.php';
require_once '../app/config/Database.php';

// Initialize database connection
try {
    $database = new Database();
    $pdo = $database->connect();
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Connection failed. Please try again later.");
}

// Include models - use absolute paths
require_once __DIR__ . '/../app/models/Service.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Appointment.php';
require_once __DIR__ . '/../app/models/Review.php';

// Parse the URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Simple routing logic
switch ($url[0]) {
    case '':
        require_once '../app/controllers/HomeController.php';
        $controller = new HomeController($pdo);
        $controller->index();
        break;
    case 'services':
        require_once '../app/controllers/ServiceController.php';
        $controller = new ServiceController($pdo);
        $controller->index();
        break;
    case 'booking':
        require_once '../app/controllers/BookingController.php';
        $controller = new BookingController($pdo);
        if (isset($url[1])) {
            switch($url[1]) {
                case 'datetime':
                    $controller->datetime();
                    break;
                case 'getAvailableSlots':
                    $controller->getAvailableSlots();
                    break;
                case 'confirm':
                    $controller->confirm();
                    break;
                case 'process':
                    $controller->process();
                    break;
                case 'validate-promo':
                    $controller->validatePromo();
                    break;
                default:
                    $controller->index();
            }
        } else {
            if (isset($_GET['service'])) {
                $controller->index($_GET['service']);
            } else {
                $controller->index();
            }
        }
        break;
    case 'dashboard':
        require_once '../app/controllers/DashboardController.php';
        $controller = new DashboardController($pdo);
        if (isset($url[1])) {
            if ($url[1] === 'profile' && isset($url[2])) {
                if ($url[2] === 'update') {
                    // Handle profile update
                    $controller->updateProfile();
                } else if ($url[2] === 'password') {
                    // Handle password update
                    $controller->updatePassword();
                }
            } else if ($url[1] === 'review' && isset($url[2]) && $url[2] === 'submit') {
                require_once '../app/controllers/api/DashboardApiController.php';
                
                // Set JSON content type header early
                header('Content-Type: application/json');
                
                // Create controller with the existing database connection
                $controller = new DashboardApiController($pdo);
                $controller->submitReview();
                exit;
            } else {
                $method = $url[1];
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    echo "404 Not Found";
                }
            }
        } else {
            $controller->overview();
        }
        break;
    case 'login':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;
    case 'logout':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->logout();
        break;
    case 'register':
        require_once '../app/controllers/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->register();
        break;
    case 'admin':
        require_once '../app/controllers/AdminController.php';
        $database = new Database();
        $pdo = $database->connect();
        $controller = new AdminController($pdo);
        
        if (!isset($url[1])) {
            $controller->dashboard();
        } else {
            switch ($url[1]) {
                case 'get-therapist-availability':
                    $controller->getTherapistAvailability();
                    break;
                case 'therapists':
                    $controller->therapists();
                    break;
                default:
                    $method = $url[1];
                    if (method_exists($controller, $method)) {
                        $controller->$method();
                    } else {
                        header("HTTP/1.0 404 Not Found");
                        include '../app/views/errors/404.php';
                    }
            }
        }
        break;
    case 'api':
        if (isset($url[1])) {
            if ($url[1] === 'appointments') {
                require_once '../app/controllers/api/AppointmentApiController.php';
                $controller = new AppointmentApiController($pdo);
                
                if (isset($url[2])) {
                    $method = $url[2];
                    if (method_exists($controller, $method)) {
                        $controller->$method();
                        exit;
                    }
                }
            } else if ($url[1] === 'bookings' && isset($url[2]) && isset($url[3]) && $url[3] === 'status') {
                require_once '../app/controllers/api/AppointmentApiController.php';
                $controller = new AppointmentApiController($pdo);
                $controller->updateStatus();
                exit;
            } else if ($url[1] === 'services') {
                require_once '../app/controllers/api/ServiceApiController.php';
                $controller = new ServiceApiController($pdo);
                
                // Handle service endpoints
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($url[2])) {
                    $controller->create();
                } else if (isset($url[2])) {
                    $serviceId = $url[2];
                    switch ($_SERVER['REQUEST_METHOD']) {
                        case 'GET':
                            $controller->get($serviceId);
                            break;
                        case 'PUT':
                            $controller->update($serviceId);
                            break;
                        case 'DELETE':
                            $controller->delete($serviceId);
                            break;
                    }
                }
                exit;
            } else if ($url[1] === 'therapists') {
                require_once '../app/controllers/api/TherapistApiController.php';
                $controller = new TherapistApiController($pdo);
                
                if (isset($url[2]) && isset($url[3]) && $url[3] === 'availability') {
                    // Get therapist availability
                    $controller->getAvailability($url[2]);
                } else if ($url[2] === 'availability') {
                    // Save therapist availability
                    $controller->saveAvailability();
                }
                exit;
            } else if ($url[1] === 'dashboard') {
                require_once '../app/controllers/api/DashboardApiController.php';
                $controller = new DashboardApiController($pdo);
                
                if (isset($url[2])) {
                    switch ($url[2]) {
                        case 'submit-review':
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $controller->submitReview();
                            }
                            break;
                        default:
                            http_response_code(404);
                            echo json_encode(['error' => 'API endpoint not found']);
                    }
                }
                exit;
            }
            http_response_code(404);
            echo json_encode(['error' => 'API endpoint not found']);
            exit;
        }
        break;
    default:
        echo "404 Not Found";
        break;
} 
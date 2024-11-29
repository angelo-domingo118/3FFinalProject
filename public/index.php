<?php
// public/index.php

// Prevent errors from being displayed in the output
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffering
ob_start();

session_start();

require_once '../config/config.php';

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
        if (isset($url[1]) && $url[1] === 'appointments') {
            require_once '../app/controllers/api/AppointmentApiController.php';
            $controller = new AppointmentApiController($pdo);
            
            if (isset($url[2])) {
                $method = $url[2];
                if (method_exists($controller, $method)) {
                    $controller->$method();
                    exit;
                }
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
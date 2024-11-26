<?php
// public/index.php

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
            $method = $url[1];
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                echo "404 Not Found";
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
        $controller = new AdminController($pdo);
        
        if (!isset($url[1])) {
            $controller->dashboard();
        } else {
            $method = $url[1];
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                header("HTTP/1.0 404 Not Found");
                include '../app/views/errors/404.php';
            }
        }
        break;
    default:
        echo "404 Not Found";
        break;
} 
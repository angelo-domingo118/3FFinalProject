<?php
// public/index.php

require_once '../config/config.php';

// Include models
require_once '../app/models/Service.php';
require_once '../app/models/User.php';

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
        require_once '../app/controllers/AppointmentController.php';
        $controller = new AppointmentController($pdo);
        $controller->book();
        break;
    case 'dashboard':
        require_once '../app/controllers/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->index();
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
    default:
        echo "404 Not Found";
        break;
} 
<?php
// public/index.php

require_once '../config/config.php';

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
    default:
        echo "404 Not Found";
        break;
} 
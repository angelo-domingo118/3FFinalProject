<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Promotion.php';

class DashboardController {
    private $db;
    private $user;
    private $appointment;
    private $review;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($db);
        $this->appointment = new Appointment($db);
        $this->review = new Review($db);

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/public/login');
            exit;
        }
    }

    public function overview() {
        $userId = $_SESSION['user_id'];
        $promotion = new Promotion($this->db);
        
        // Get counts with default values
        $counts = $this->appointment->getAppointmentCounts($userId) ?? [
            'upcoming_count' => 0,
            'completed_count' => 0
        ];
        
        // Get review count with default value
        $reviewCount = $this->review->getTotalReviewCount($userId) ?? 0;
        
        // Get next appointment
        $nextAppointment = $this->appointment->getNextAppointment($userId);
        
        // Get active promotions
        $activePromotions = $promotion->getActivePromotions();

        $data = [
            'active_page' => 'overview',
            'upcoming_count' => $counts['upcoming_count'] ?? 0,
            'completed_count' => $counts['completed_count'] ?? 0,
            'reviews_count' => $reviewCount,
            'next_appointment' => $nextAppointment,
            'promotions' => $activePromotions
        ];

        extract($data);
        $content = '../app/views/dashboard/overview.php';
        include '../app/views/dashboard/layouts/dashboard.php';
    }

    public function appointments() {
        $userId = $_SESSION['user_id'];
        $filters = [
            'status' => $_GET['status'] ?? null,
            'date' => $_GET['date'] ?? null
        ];
        
        $appointments = $this->appointment->getAppointmentsByUserId($userId, $filters);

        $data = [
            'active_page' => 'appointments',
            'appointments' => $appointments
        ];

        $content = '../app/views/dashboard/appointments.php';
        include '../app/views/dashboard/layouts/dashboard.php';
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        $user = $this->user->getUserById($userId);
        
        // Get appointment counts with default values
        $counts = $this->appointment->getAppointmentCounts($userId) ?? [
            'upcoming_count' => 0,
            'completed_count' => 0
        ];
        
        // Get total appointments (upcoming + completed)
        $totalAppointments = ($counts['upcoming_count'] ?? 0) + ($counts['completed_count'] ?? 0);
        
        // Get review count with default value
        $totalReviews = $this->review->getTotalReviewCount($userId) ?? 0;

        $data = [
            'active_page' => 'profile',
            'user' => $user,
            'total_appointments' => $totalAppointments,
            'total_reviews' => $totalReviews
        ];

        // Make variables available to the view
        extract($data);

        $content = '../app/views/dashboard/profile.php';
        include '../app/views/dashboard/layouts/dashboard.php';
    }

    public function reviews() {
        $userId = $_SESSION['user_id'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        
        $reviews = $this->review->getReviewsByUserId($userId, $page, $perPage);
        $totalReviews = $this->review->getTotalReviewCount($userId);
        $totalPages = ceil($totalReviews / $perPage);

        $data = [
            'active_page' => 'reviews',
            'reviews' => $reviews,
            'current_page' => $page,
            'total_pages' => $totalPages
        ];

        $content = '../app/views/dashboard/reviews.php';
        include '../app/views/dashboard/layouts/dashboard.php';
    }

    public function promotions() {
        $promotion = new Promotion($this->db);
        $activePromotions = $promotion->getActivePromotions();

        $data = [
            'active_page' => 'promotions',
            'promotions' => $activePromotions
        ];

        extract($data);
        $content = '../app/views/dashboard/promotions.php';
        include '../app/views/dashboard/layouts/dashboard.php';
    }
} 
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
        
        // Get appointment counts
        $counts = $this->appointment->getAppointmentCounts($userId);
        $upcoming_count = $counts['upcoming_count'];
        $completed_count = $counts['completed_count'];
        
        // Get upcoming appointments
        $upcoming_appointments = $this->appointment->getUpcomingAppointments($userId);
        
        // Get review count
        $reviews_count = $this->review->getTotalReviewCount($userId);

        // Get active promotions
        $promotion = new Promotion($this->db);
        $promotions = $promotion->getActivePromotions();
        
        $data = [
            'active_page' => 'overview',
            'upcoming_count' => $upcoming_count,
            'completed_count' => $completed_count,
            'reviews_count' => $reviews_count,
            'upcoming_appointments' => $upcoming_appointments,
            'promotions' => $promotions
        ];
        
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

    public function cancelAppointment() {
        // Clear any previous output
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set proper JSON headers
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, must-revalidate');
        header('X-Content-Type-Options: nosniff');
        
        try {
            if (!isset($_POST['appointment_id'])) {
                throw new Exception('Appointment ID is required');
            }

            $userId = $_SESSION['user_id'];
            $appointmentId = $_POST['appointment_id'];

            if ($this->appointment->cancelAppointment($appointmentId, $userId)) {
                die(json_encode(['success' => true]));
            } else {
                throw new Exception('Failed to cancel appointment');
            }
        } catch (Exception $e) {
            http_response_code(400);
            die(json_encode(['error' => $e->getMessage()]));
        }
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/dashboard/profile');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $data = [
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'phone_number' => $_POST['phone_number']
        ];

        try {
            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email format');
            }

            // Check if email is already taken by another user
            $existingUser = $this->user->findByEmail($data['email']);
            if ($existingUser && $existingUser['user_id'] != $userId) {
                throw new Exception('Email is already taken');
            }

            if ($this->user->updateProfile($userId, $data)) {
                $_SESSION['full_name'] = $data['full_name']; // Update session name
                $_SESSION['success'] = 'Profile updated successfully';
            } else {
                throw new Exception('Failed to update profile');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/public/dashboard/profile');
        exit;
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/dashboard/profile');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        try {
            // Get current user data
            $user = $this->user->getUserById($userId);
            if (!$user) {
                throw new Exception('User not found');
            }

            // Verify current password
            if (!password_verify($currentPassword, $user['password'])) {
                throw new Exception('Current password is incorrect');
            }

            // Validate new password
            if (strlen($newPassword) < 6) {
                throw new Exception('New password must be at least 6 characters long');
            }

            // Check if new passwords match
            if ($newPassword !== $confirmPassword) {
                throw new Exception('New passwords do not match');
            }

            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            if ($this->user->updatePassword($userId, $hashedPassword)) {
                $_SESSION['success'] = 'Password updated successfully';
            } else {
                throw new Exception('Failed to update password');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/public/dashboard/profile');
        exit;
    }
} 
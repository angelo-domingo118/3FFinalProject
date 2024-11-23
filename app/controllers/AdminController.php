<?php

require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/User.php';

class AdminController {
    private $db;
    private $appointment;
    private $service;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->appointment = new Appointment($db);
        $this->service = new Service($db);
        $this->user = new User($db);

        // Check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
            header('Location: ' . BASE_URL . '/public/login');
            exit;
        }

        // Set default active page
        $this->data['active_page'] = '';
    }

    public function dashboard() {
        // Get summary statistics
        $booking_counts = $this->appointment->getBookingCounts();
        
        $data = [
            'active_page' => 'dashboard',
            'booking_counts' => $booking_counts ?? [],
            'recent_bookings' => $this->appointment->getAllBookings(['limit' => 5]) ?? []
        ];

        $content = __DIR__ . '/../views/admin/dashboard.php';
        include __DIR__ . '/../views/admin/layouts/admin_dashboard.php';
    }

    public function bookings() {
        // Get booking counts
        $counts = $this->appointment->getBookingCounts();
        
        // Get bookings with filters
        $filters = [
            'status' => $_GET['status'] ?? null,
            'date' => $_GET['date'] ?? null,
            'therapist' => $_GET['therapist'] ?? null
        ];
        
        $data = [
            'active_page' => 'bookings',
            'pending_count' => $counts['pending'] ?? 0,
            'confirmed_count' => $counts['confirmed'] ?? 0,
            'completed_count' => $counts['completed'] ?? 0,
            'cancelled_count' => $counts['canceled'] ?? 0,
            'bookings' => $this->appointment->getAllBookings($filters) ?? []
        ];

        $content = '../app/views/admin/bookings.php';
        include '../app/views/admin/layouts/admin_dashboard.php';
    }

    public function services() {
        $services = $this->service->getAllServices();
        
        $data = [
            'active_page' => 'services',
            'services' => $services
        ];

        $content = '../app/views/admin/services.php';
        include '../app/views/admin/layouts/admin_dashboard.php';
    }

    public function therapists() {
        $therapists = $this->user->getTherapists();
        
        $data = [
            'active_page' => 'therapists',
            'therapists' => $therapists
        ];

        $content = '../app/views/admin/therapists.php';
        include '../app/views/admin/layouts/admin_dashboard.php';
    }

    public function schedule() {
        $data = [
            'active_page' => 'schedule',
            'therapists' => $this->user->getTherapists(),
            'appointments' => $this->appointment->getAllBookings(['status' => 'confirmed'])
        ];

        $content = '../app/views/admin/schedule.php';
        include '../app/views/admin/layouts/admin_dashboard.php';
    }

    public function payments() {
        $data = [
            'active_page' => 'payments'
        ];

        $content = '../app/views/admin/payments.php';
        include '../app/views/admin/layouts/admin_dashboard.php';
    }

    public function reports() {
        $data = [
            'active_page' => 'reports'
        ];

        $content = '../app/views/admin/reports.php';
        include '../app/views/admin/layouts/admin_dashboard.php';
    }
} 
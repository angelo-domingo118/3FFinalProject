<?php

require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/BookingHelper.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Availability.php';

class AdminController {
    private $db;
    private $appointment;
    private $service;
    private $user;
    private $availability;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->appointment = new Appointment($this->db);
        $this->service = new Service($this->db);
        $this->user = new User($this->db);
        $this->availability = new Availability($this->db);

        // Check if user is logged in and is admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
            header('Location: ' . BASE_URL . '/login');
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
        try {
            // Get booking counts
            error_log("Starting to fetch booking counts...");
            $counts = $this->appointment->getBookingCounts();
            error_log("Booking counts: " . print_r($counts, true));
            
            // Get all therapists for the filter dropdown
            error_log("Starting to fetch therapists...");
            $therapists = $this->user->getTherapists();
            error_log("Therapists: " . print_r($therapists, true));
            
            // Get bookings with filters
            $filters = [
                'status' => $_GET['status'] ?? null,
                'date' => $_GET['date'] ?? null,
                'therapist' => $_GET['therapist'] ?? null
            ];
            error_log("Applied filters: " . print_r($filters, true));
            
            // Get bookings
            error_log("Starting to fetch all bookings...");
            $bookings = $this->appointment->getAllBookings($filters);
            error_log("Retrieved bookings count: " . count($bookings));
            error_log("First booking: " . print_r(!empty($bookings) ? $bookings[0] : 'No bookings', true));
            
            $data = [
                'active_page' => 'bookings',
                'pending_count' => $counts['pending'] ?? 0,
                'confirmed_count' => $counts['confirmed'] ?? 0,
                'completed_count' => $counts['completed'] ?? 0,
                'cancelled_count' => $counts['canceled'] ?? 0,
                'bookings' => $bookings,
                'therapists' => $therapists ?? []
            ];
            error_log("View data prepared: " . print_r($data, true));

            $content = __DIR__ . '/../views/admin/bookings.php';
            include __DIR__ . '/../views/admin/layouts/admin_dashboard.php';
            
        } catch (PDOException $e) {
            error_log("Database error in bookings method: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            error_log("Stack trace: " . $e->getTraceAsString());
            $data = [
                'active_page' => 'bookings',
                'error' => 'A database error occurred while loading bookings.'
            ];
            $content = __DIR__ . '/../views/admin/bookings.php';
            include __DIR__ . '/../views/admin/layouts/admin_dashboard.php';
        } catch (Exception $e) {
            error_log("General error in bookings method: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $data = [
                'active_page' => 'bookings',
                'error' => 'An error occurred while loading bookings.'
            ];
            $content = __DIR__ . '/../views/admin/bookings.php';
            include __DIR__ . '/../views/admin/layouts/admin_dashboard.php';
        }
    }

    public function services() {
        $services = $this->service->getAllServices();
        
        $data = [
            'active_page' => 'services',
            'services' => $services
        ];

        $content = __DIR__ . '/../views/admin/services.php';
        extract($data);
        include __DIR__ . '/../views/admin/layouts/admin_dashboard.php';
    }

    public function therapists() {
        $therapists = $this->user->getTherapists();
        
        $data = [
            'active_page' => 'therapists',
            'therapists' => $therapists
        ];

        extract($data);
        
        $content = __DIR__ . '/../views/admin/therapists.php';
        include __DIR__ . '/../views/admin/layouts/admin_dashboard.php';
    }

    public function payments() {
        $data = [
            'active_page' => 'payments'
        ];

        $content = __DIR__ . '/../views/admin/payments.php';
        include __DIR__ . '/../views/admin/layouts/admin_dashboard.php';
    }

    public function reports() {
        $data = [
            'active_page' => 'reports'
        ];

        $content = __DIR__ . '/../views/admin/reports.php';
        include __DIR__ . '/../views/admin/layouts/admin_dashboard.php';
    }

    public function getTherapistAvailability() {
        error_log("getTherapistAvailability called");
        error_log("GET params: " . print_r($_GET, true));
        
        if (!isset($_GET['therapist_id']) || !isset($_GET['week_start'])) {
            error_log('Missing parameters in getTherapistAvailability');
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }

        $therapistId = $_GET['therapist_id'];
        $weekStart = $_GET['week_start'];
        
        try {
            error_log("Fetching availability for therapist $therapistId from $weekStart");
            
            // Get availability for the specified week
            $availability = $this->availability->getTherapistWeeklyAvailability(
                $therapistId, 
                $weekStart
            );
            
            error_log('Availability data: ' . print_r($availability, true));
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'availability' => $availability
            ]);
        } catch (Exception $e) {
            error_log('Error in getTherapistAvailability: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Failed to load availability',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
} 
<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ReportsController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function index()
    {
        // Check if user is logged in and is admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /cit17-final-project/public/login');
            exit;
        }

        $data = [
            'title' => 'Reports',
            'content' => 'admin/reports',
            'bookingStats' => $this->getBookingStats(),
            'earningsStats' => $this->getEarningsStats(),
            'customerStats' => $this->getCustomerStats()
        ];

        $this->view('admin/layouts/admin_dashboard', $data);
    }

    private function getBookingStats()
    {
        $query = "SELECT 
                    DATE_FORMAT(booking_date, '%Y-%m') as month,
                    COUNT(*) as total_bookings,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_bookings
                FROM bookings 
                WHERE booking_date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
                ORDER BY month";
        
        return $this->db->query($query)->fetchAll();
    }

    private function getEarningsStats()
    {
        $query = "SELECT 
                    DATE_FORMAT(payment_date, '%Y-%m') as month,
                    SUM(amount) as total_earnings
                FROM payments 
                WHERE payment_date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
                ORDER BY month";
        
        return $this->db->query($query)->fetchAll();
    }

    private function getCustomerStats()
    {
        $query = "SELECT 
                    service_id,
                    COUNT(*) as booking_count,
                    AVG(rating) as avg_rating
                FROM bookings 
                WHERE rating IS NOT NULL
                GROUP BY service_id
                ORDER BY booking_count DESC
                LIMIT 5";
        
        return $this->db->query($query)->fetchAll();
    }
}

<?php

namespace App\Controllers\Api;

use App\Models\Appointment;
use App\Core\Controller;

class AppointmentsController extends Controller {
    public function reschedule() {
        if (!$this->isLoggedIn()) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            return;
        }

        $appointmentId = $_POST['appointment_id'] ?? null;
        $newDate = $_POST['new_date'] ?? null;
        $newTime = $_POST['new_time'] ?? null;
        $reason = $_POST['reason'] ?? null;

        if (!$appointmentId || !$newDate || !$newTime || !$reason) {
            $this->jsonResponse(['success' => false, 'message' => 'Missing required fields'], 400);
            return;
        }

        $appointment = new Appointment();
        $currentAppointment = $appointment->findById($appointmentId);

        if (!$currentAppointment) {
            $this->jsonResponse(['success' => false, 'message' => 'Appointment not found'], 404);
            return;
        }

        // Check if user owns this appointment
        if ($currentAppointment['user_id'] !== $_SESSION['user_id']) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
            return;
        }

        // Check if appointment can be rescheduled
        if (!in_array($currentAppointment['status'], ['pending', 'confirmed'])) {
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Only pending or confirmed appointments can be rescheduled'
            ], 400);
            return;
        }

        // Check if new date is in the future
        if (strtotime($newDate . ' ' . $newTime) <= time()) {
            $this->jsonResponse([
                'success' => false, 
                'message' => 'New appointment time must be in the future'
            ], 400);
            return;
        }

        // Check if the time slot is available
        if (!$appointment->isTimeSlotAvailable($newDate, $newTime, $currentAppointment['therapist_id'], $appointmentId)) {
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Selected time slot is not available'
            ], 400);
            return;
        }

        // Update appointment
        $success = $appointment->update($appointmentId, [
            'appointment_date' => $newDate,
            'start_time' => $newTime,
            'reschedule_reason' => $reason,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($success) {
            // TODO: Send notification email about rescheduling
            $this->jsonResponse(['success' => true, 'message' => 'Appointment rescheduled successfully']);
        } else {
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Error rescheduling appointment'
            ], 500);
        }
    }
}

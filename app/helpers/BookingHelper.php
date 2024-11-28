<?php

function getStatusBadgeClass($status) {
    switch (strtolower($status)) {
        case 'pending':
            return 'warning';
        case 'confirmed':
            return 'primary';
        case 'completed':
            return 'success';
        case 'cancelled':
        case 'canceled':
            return 'danger';
        default:
            return 'secondary';
    }
}

function getPaymentStatusBadgeClass($status) {
    switch (strtolower($status)) {
        case 'paid':
            return 'success';
        case 'pending':
            return 'warning';
        case 'refunded':
            return 'info';
        case 'failed':
            return 'danger';
        default:
            return 'secondary';
    }
}

function getStatusLabel($status) {
    return ucfirst(strtolower($status));
}

function getPaymentStatusLabel($status) {
    return ucfirst(strtolower($status));
}

function getActionButtons($booking) {
    $buttons = [];
    
    switch (strtolower($booking['status'])) {
        case 'pending':
            $buttons[] = [
                'label' => 'Confirm',
                'icon' => 'check-circle',
                'class' => 'success',
                'action' => "confirmBooking({$booking['appointment_id']})"
            ];
            $buttons[] = [
                'label' => 'Cancel',
                'icon' => 'x-circle',
                'class' => 'danger',
                'action' => "cancelBooking({$booking['appointment_id']})"
            ];
            break;
            
        case 'confirmed':
            $buttons[] = [
                'label' => 'Complete',
                'icon' => 'check-all',
                'class' => 'success',
                'action' => "completeBooking({$booking['appointment_id']})"
            ];
            $buttons[] = [
                'label' => 'Cancel',
                'icon' => 'x-circle',
                'class' => 'danger',
                'action' => "cancelBooking({$booking['appointment_id']})"
            ];
            break;
            
        case 'completed':
            $buttons[] = [
                'label' => 'View Details',
                'icon' => 'eye',
                'class' => 'primary',
                'action' => "viewBooking({$booking['appointment_id']})"
            ];
            break;
            
        case 'cancelled':
        case 'canceled':
            $buttons[] = [
                'label' => 'View Details',
                'icon' => 'eye',
                'class' => 'primary',
                'action' => "viewBooking({$booking['appointment_id']})"
            ];
            break;
    }
    
    return $buttons;
}

<?php
// Debug session data
echo "<!-- Session Debug: ";
var_dump($_SESSION);
echo " -->";

// Update the status text display
$statusClasses = [
    'pending' => 'text-warning',
    'confirmed' => 'text-primary',
    'completed' => 'text-success',
    'canceled' => 'text-danger'
];

$statusBadges = [
    'pending' => '<span class="badge bg-warning">Pending</span>',
    'confirmed' => '<span class="badge bg-primary">Confirmed</span>',
    'completed' => '<span class="badge bg-success">Completed</span>',
    'canceled' => '<span class="badge bg-danger">Canceled</span>'
];
?>
// ... rest of the file 
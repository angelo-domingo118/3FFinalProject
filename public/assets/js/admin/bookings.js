// Function to filter by status (used by status cards)
function filterByStatus(status) {
    window.location.href = `${window.location.pathname}?status=${status}`;
}

// Function to apply filters from the filter modal
function applyFilters() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    
    // Build query string
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        if (value) { // Only add non-empty values
            params.append(key, value);
        }
    }
    
    // Redirect with filters
    window.location.href = `${window.location.pathname}?${params.toString()}`;
}

// Function to view booking details
function viewBooking(id) {
    // TODO: Implement view booking details
    const url = `${BASE_URL}/public/admin/bookings/${id}`;
    window.location.href = url;
}

// Function to confirm booking
function confirmBooking(id) {
    if (confirm('Are you sure you want to confirm this booking?')) {
        updateBookingStatus(id, 'confirmed');
    }
}

// Function to complete booking
function completeBooking(id) {
    if (confirm('Are you sure you want to mark this booking as completed?')) {
        updateBookingStatus(id, 'completed');
    }
}

// Function to cancel booking
function cancelBooking(id) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        updateBookingStatus(id, 'cancelled');
    }
}

// Function to update booking status
async function updateBookingStatus(id, status) {
    try {
        const response = await fetch(`${BASE_URL}/public/api/bookings/${id}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status })
        });

        if (!response.ok) {
            throw new Error('Failed to update booking status');
        }

        // Reload the page to show updated status
        window.location.reload();
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update booking status. Please try again.');
    }
}

// Function to export bookings
function exportBookings() {
    const params = new URLSearchParams(window.location.search);
    const url = `${BASE_URL}/public/admin/bookings/export?${params.toString()}`;
    window.location.href = url;
}

// Set selected values in filter form based on URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const form = document.getElementById('filterForm');
    
    if (form) {
        // Set values for each filter
        form.querySelector('[name="status"]').value = urlParams.get('status') || '';
        form.querySelector('[name="date"]').value = urlParams.get('date') || '';
        form.querySelector('[name="therapist"]').value = urlParams.get('therapist') || '';
    }
});

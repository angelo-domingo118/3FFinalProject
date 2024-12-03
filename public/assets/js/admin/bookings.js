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
        updateBookingStatus(id, 'canceled');
    }
}

// Function to update booking status
async function updateBookingStatus(id, status) {
    try {
        console.log('Updating booking status:', { id, status }); // Debug log
        
        const response = await fetch(`/cit17-final-project/public/api/bookings/${id}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: status })
        });

        console.log('Response status:', response.status); // Debug log

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Error data:', errorData); // Debug log
            throw new Error(errorData.message || 'Failed to update booking status');
        }

        const result = await response.json();
        console.log('Success:', result); // Debug log

        // Show success message
        alert('Booking status updated successfully!');
        
        // Reload the page to show updated status
        window.location.reload();
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update booking status: ' + error.message);
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

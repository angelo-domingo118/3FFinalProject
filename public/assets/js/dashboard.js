// Toggle sidebar
document.addEventListener('DOMContentLoaded', function() {
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            content.classList.toggle('active');
        });
    }

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            const isClickInside = sidebar.contains(event.target) || 
                                sidebarCollapse.contains(event.target);
            
            if (!isClickInside && !sidebar.classList.contains('active')) {
                sidebar.classList.add('active');
                content.classList.add('active');
            }
        }
    });
});

// Handle appointment cancellation
function cancelAppointment(appointmentId) {
    if (!confirm('Are you sure you want to cancel this appointment?')) {
        return;
    }

    fetch(`${BASE_URL}/public/api/dashboard/cancel-appointment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ appointment_id: appointmentId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the page to show updated status
            window.location.reload();
        } else {
            alert(data.error || 'Failed to cancel appointment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while canceling the appointment');
    });
}

// Handle review modal and submission
let reviewModal;
document.addEventListener('DOMContentLoaded', function() {
    reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
});

function leaveReview(appointmentId) {
    document.getElementById('reviewAppointmentId').value = appointmentId;
    reviewModal.show();
}

function submitReview() {
    const form = document.getElementById('reviewForm');
    const formData = new FormData(form);
    
    // Validate rating
    if (!formData.get('rating')) {
        alert('Please select a rating');
        return;
    }

    fetch(`${BASE_URL}/public/api/dashboard/submit-review`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            reviewModal.hide();
            // Refresh the page to show the new review
            window.location.reload();
        } else {
            alert(data.error || 'Failed to submit review');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the review');
    });
}

// Star rating interaction
document.addEventListener('DOMContentLoaded', function() {
    const ratingContainer = document.querySelector('.rating');
    if (ratingContainer) {
        const stars = ratingContainer.querySelectorAll('label');
        
        stars.forEach((star, index) => {
            star.addEventListener('mouseover', function() {
                stars.forEach((s, i) => {
                    s.querySelector('i').classList.toggle('text-warning', i <= index);
                });
            });
        });

        ratingContainer.addEventListener('mouseleave', function() {
            const selectedRating = ratingContainer.querySelector('input:checked');
            stars.forEach((star, index) => {
                star.querySelector('i').classList.toggle('text-warning', 
                    selectedRating && index < selectedRating.value);
            });
        });
    }
}); 
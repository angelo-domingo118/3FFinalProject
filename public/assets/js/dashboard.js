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

    // Initialize review modal if it exists
    const reviewModalElement = document.getElementById('reviewModal');
    if (reviewModalElement) {
        window.reviewModal = new bootstrap.Modal(reviewModalElement);
    }
});

// Handle appointment cancellation
function cancelAppointment(appointmentId) {
    if (!confirm('Are you sure you want to cancel this appointment?')) {
        return;
    }

    if (typeof window.BASE_URL === 'undefined') {
        console.error('BASE_URL is not defined');
        alert('Configuration error. Please contact support.');
        return;
    }

    // Create FormData object
    const formData = new FormData();
    formData.append('appointment_id', appointmentId);

    fetch(`${window.BASE_URL}/public/api/appointments/cancel`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.error || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Refresh the page to show updated status
            window.location.reload();
        } else {
            throw new Error(data.error || 'Failed to cancel appointment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while canceling the appointment: ' + error.message);
    });
}

// Handle review modal and submission
function leaveReview(appointmentId) {
    document.getElementById('reviewAppointmentId').value = appointmentId;
    if (window.reviewModal) {
        window.reviewModal.show();
    } else {
        console.error('Review modal not initialized');
        alert('Error: Could not open review form. Please try refreshing the page.');
    }
}

function submitReview() {
    const form = document.getElementById('reviewForm');
    const formData = new FormData(form);
    
    // Basic validation
    const rating = formData.get('rating');
    const comment = formData.get('comment');
    const appointmentId = formData.get('appointment_id');

    if (!rating) {
        alert('Please select a rating');
        return;
    }

    if (!comment || comment.trim() === '') {
        alert('Please enter your review comment');
        return;
    }

    if (!appointmentId) {
        alert('Invalid appointment. Please try again.');
        return;
    }

    // Disable submit button and show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';
    }

    // Submit the review
    fetch(`${window.BASE_URL}/public/submit_review.php`, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.error || `Server error (${response.status})`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close modal if it exists
            const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
            if (modal) {
                modal.hide();
            }
            
            alert('Thank you for your review!');
            window.location.reload();
        } else {
            throw new Error(data.error || 'Failed to submit review');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while submitting the review');
    })
    .finally(() => {
        // Reset button state
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Review';
        }
    });
}

// Initialize calendar for rescheduling
function initRescheduleCalendar(appointmentId) {
    // Initialize datepicker with enhanced options
    const datepicker = new Datepicker(document.getElementById(`datepicker-${appointmentId}`), {
        minDate: new Date(),
        maxDate: new Date().setMonth(new Date().getMonth() + 2),
        format: 'yyyy-mm-dd',
        autohide: true,
        todayHighlight: true,
        weekStart: 1,
        daysOfWeekDisabled: [0], // Disable Sundays
        beforeShowDay: function(date) {
            // You can add additional date validation here if needed
            return true;
        }
    });

    // Add date selection event listener
    datepicker.element.addEventListener('changeDate', function(e) {
        const selectedDate = e.detail.date;
        if (selectedDate) {
            const formattedDate = formatDate(selectedDate);
            loadTherapistsForReschedule(appointmentId, formattedDate);
        }
    });
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Load available therapists for the selected date
function loadTherapistsForReschedule(appointmentId, selectedDate) {
    const url = `${window.BASE_URL}/public/booking/getAvailableSlots?date=${selectedDate}&service_id=${appointmentId}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const therapistList = document.getElementById(`therapist-list-${appointmentId}`);
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            const slots = data.slots || [];
            if (slots.length === 0) {
                therapistList.innerHTML = `
                    <div class="text-center p-4">
                        <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                        <p class="text-muted mb-0">No available therapists for the selected date</p>
                    </div>
                `;
                return;
            }
            
            const therapistCardsHtml = slots.map(slot => `
                <div class="therapist-card" data-therapist-id="${slot.therapist_id}" data-date="${slot.date}" data-start-time="${slot.start_time}" data-end-time="${slot.end_time}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-person me-2"></i>${slot.therapist_name}
                        </div>
                        <div class="text-end">
                            <small class="d-block text-dark">Available from</small>
                            <span class="fw-medium">${formatTime(slot.start_time)} to ${formatTime(slot.end_time)}</span>
                        </div>
                    </div>
                </div>
            `).join('');
            
            therapistList.innerHTML = therapistCardsHtml;
            
            // Add event listener to each therapist card
            const therapistCards = therapistList.querySelectorAll('.therapist-card');
            therapistCards.forEach(card => {
                card.addEventListener('click', function() {
                    const therapistId = this.getAttribute('data-therapist-id');
                    const name = this.querySelector('.fw-medium').textContent;
                    const startTime = this.getAttribute('data-start-time');
                    const endTime = this.getAttribute('data-end-time');
                    selectTherapistForReschedule(therapistId, name, startTime, endTime, appointmentId);
                });
            });
        })
        .catch(error => {
            console.error('Error loading therapists:', error);
            const therapistList = document.getElementById(`therapist-list-${appointmentId}`);
            therapistList.innerHTML = `
                <div class="text-center p-4">
                    <i class="bi bi-exclamation-circle display-4 text-danger mb-3"></i>
                    <p class="text-danger mb-0">Error loading therapists: ${error.message}</p>
                </div>
            `;
        });
}

// Helper functions for time manipulation
function formatTime(timeStr) {
    return new Date('2000-01-01T' + timeStr).toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
}

function addMinutes(time, minutes) {
    const date = new Date('2000-01-01T' + time);
    date.setMinutes(date.getMinutes() + parseInt(minutes));
    return date.toTimeString().slice(0, 5);
}

function subtractMinutes(time, minutes) {
    const date = new Date('2000-01-01T' + time);
    date.setMinutes(date.getMinutes() - parseInt(minutes));
    return date.toTimeString().slice(0, 5);
}

function isTimeWithinRange(endTime, startTime, maxEndTime) {
    const end = new Date('2000-01-01T' + endTime);
    const start = new Date('2000-01-01T' + startTime);
    const max = new Date('2000-01-01T' + maxEndTime);
    return end <= max && start >= start;
}

function selectTherapistForReschedule(therapistId, name, startTime, endTime, appointmentId) {
    // Remove active class from all therapist cards in this modal
    const modal = document.getElementById(`rescheduleModal${appointmentId}`);
    const allCards = modal.querySelectorAll('.therapist-card');
    allCards.forEach(card => card.classList.remove('active'));

    // Add active class to selected therapist
    const selectedCard = modal.querySelector(`.therapist-card[data-therapist-id="${therapistId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('active');
    }

    // Enable and update time input
    const timeInput = document.getElementById(`time-select-${appointmentId}`);
    timeInput.disabled = false;
    
    // Convert times to 24-hour format for input
    const startHour = startTime.split(':')[0].padStart(2, '0');
    const endHour = endTime.split(':')[0].padStart(2, '0');
    timeInput.min = `${startHour}:00`;
    timeInput.max = `${endHour}:00`;

    // Update available hours text
    const hoursText = document.getElementById(`therapist-hours-${appointmentId}`);
    if (hoursText) {
        hoursText.innerHTML = `<i class="bi bi-info-circle me-1"></i>Available from ${formatTime(startTime)} to ${formatTime(endTime)}`;
    }

    // Clear any previous time selection
    timeInput.value = '';
    updateEndTime(appointmentId, '');
}

function submitReschedule(appointmentId) {
    const modal = document.getElementById(`rescheduleModal${appointmentId}`);
    const timeInput = document.getElementById(`time-select-${appointmentId}`);
    const selectedTherapist = modal.querySelector('.therapist-card.active');
    
    if (!selectedTherapist) {
        alert('Please select a therapist first.');
        return;
    }

    if (!timeInput.value) {
        alert('Please select a time slot.');
        return;
    }

    // Get therapist's working hours
    const startTime = selectedTherapist.getAttribute('data-start-time');
    const endTime = selectedTherapist.getAttribute('data-end-time');
    
    // Validate selected time is within therapist's working hours
    const selectedTime = timeInput.value + ':00';
    if (selectedTime < startTime || selectedTime >= endTime) {
        alert('Please select a time within the therapist\'s working hours.');
        return;
    }
    
    const data = {
        appointment_id: parseInt(appointmentId),
        therapist_id: parseInt(selectedTherapist.getAttribute('data-therapist-id')),
        date: selectedTherapist.getAttribute('data-date'),
        start_time: selectedTime
    };

    // Validate all required fields
    if (!data.appointment_id || !data.therapist_id || !data.date || !data.start_time) {
        console.error('Missing required fields:', data);
        alert('Please ensure all fields are filled out properly.');
        return;
    }

    console.log('Submitting reschedule data:', data);
    
    fetch(`${window.BASE_URL}/public/api/appointments/reschedule`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.error || 'Failed to reschedule appointment');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.error) {
            throw new Error(result.error);
        }
        
        // Show success message
        alert('Appointment rescheduled successfully!');
        
        // Close modal and refresh page
        bootstrap.Modal.getInstance(modal).hide();
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error rescheduling appointment: ' + error.message);
    });
}

// Helper function to update end time display
function updateEndTime(appointmentId, startTime) {
    const endTimeDisplay = document.getElementById(`end-time-${appointmentId}`);
    if (!startTime) {
        endTimeDisplay.textContent = '--:--';
        return;
    }

    const duration = parseInt(document.getElementById(`service-duration-${appointmentId}`).textContent);
    const endTime = addMinutes(startTime, duration);
    endTimeDisplay.textContent = formatTime(endTime);
}

// Handle reschedule button click
function rescheduleAppointment(appointmentId) {
    const modal = new bootstrap.Modal(document.getElementById(`rescheduleModal${appointmentId}`));
    modal.show();
    
    // Initialize calendar after modal is shown
    modal._element.addEventListener('shown.bs.modal', function() {
        initRescheduleCalendar(appointmentId);
    });
}

// Initialize datepickers when modals are shown
document.addEventListener('DOMContentLoaded', function() {
    const rescheduleModals = document.querySelectorAll('[id^="rescheduleModal"]');
    rescheduleModals.forEach(modal => {
        const appointmentId = modal.id.replace('rescheduleModal', '');
        modal.addEventListener('shown.bs.modal', function() {
            // Removed the call to initializeRescheduleDatepicker
        });
    });
});

// Star rating interaction
document.addEventListener('DOMContentLoaded', function() {
    const ratingContainer = document.querySelector('.rating');
    if (ratingContainer) {
        const stars = ratingContainer.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.dataset.value;
                ratingInput.value = value;
                
                stars.forEach(s => {
                    if (s.dataset.value <= value) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
    }
});
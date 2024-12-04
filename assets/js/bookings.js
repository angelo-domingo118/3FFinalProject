// Function to load available time slots
async function loadAvailableTimeSlots(date, appointmentId) {
    console.log('Loading time slots for:', { appointmentId, date });
    try {
        const response = await fetch(`/cit17-final-project/public/booking/getAvailableSlots?date=${date}&appointment_id=${appointmentId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load time slots');
        }

        const data = await response.json();
        console.log('Time slots response:', data);

        if (data.success) {
            displayTimeSlots(data.timeslots);
        } else {
            throw new Error(data.message || 'Failed to load time slots');
        }
    } catch (error) {
        console.error('Error loading time slots:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Failed to load time slots'
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up event listeners');
    
    // Initialize date picker min date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('newDate').min = today;

    let currentAppointment = null;

    // Handle opening the reschedule modal
    window.handleReschedule = async function(appointmentId) {
        console.log('Opening reschedule modal for appointment:', appointmentId);
        try {
            // Fetch current appointment details
            const response = await fetch(`/cit17-final-project/public/api/appointment-details?id=${appointmentId}`);
            if (!response.ok) {
                throw new Error('Failed to load appointment details');
            }
            
            const data = await response.json();
            if (data.success) {
                currentAppointment = data.appointment;
                displayCurrentAppointment(currentAppointment);
                document.getElementById('appointmentId').value = appointmentId;
                
                // Clear previous selections
                document.getElementById('newDate').value = '';
                document.getElementById('newTime').innerHTML = '<option value="">Select time</option>';
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
                modal.show();
            } else {
                throw new Error(data.message || 'Failed to load appointment details');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Failed to load appointment details'
            });
        }
    };

    // Display current appointment info
    function displayCurrentAppointment(appointment) {
        const infoHtml = `
            <div class="d-flex align-items-center">
                <div>
                    <strong>${appointment.service_name}</strong><br>
                    <span class="text-muted">
                        ${formatTime(appointment.start_time)} - ${formatTime(appointment.end_time)} · 
                        ${formatDate(appointment.appointment_date)}
                    </span>
                </div>
                <span class="badge bg-primary ms-auto">Active</span>
            </div>
        `;
        document.getElementById('currentAppointmentInfo').innerHTML = infoHtml;
    }

    // Handle date change
    document.getElementById('newDate').addEventListener('change', function() {
        const selectedDate = this.value;
        if (selectedDate && currentAppointment) {
            loadAvailableTimeSlots(selectedDate, currentAppointment.appointment_id);
        }
    });

    // Display available time slots
    function displayTimeSlots(timeslots) {
        const timeSelect = document.getElementById('newTime');
        timeSelect.innerHTML = '<option value="">Select time</option>';
        
        if (!timeslots || timeslots.length === 0) {
            timeSelect.innerHTML += '<option value="" disabled>No available time slots</option>';
            return;
        }
        
        timeslots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot.time;
            option.textContent = formatTime(slot.time);
            timeSelect.appendChild(option);
        });
    }

    // Handle form submission
    document.getElementById('confirmReschedule').addEventListener('click', async function() {
        if (!validateReschedule()) return;

        const appointmentId = document.getElementById('appointmentId').value;
        const newDate = document.getElementById('newDate').value;
        const newTime = document.getElementById('newTime').value;

        try {
            const response = await fetch('/cit17-final-project/public/api/reschedule-appointment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    appointment_id: appointmentId,
                    new_date: newDate,
                    new_time: newTime
                })
            });

            if (!response.ok) {
                throw new Error('Failed to reschedule appointment');
            }

            const result = await response.json();
            
            if (result.success) {
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('rescheduleModal'));
                modal.hide();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Appointment rescheduled successfully.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Reload the page to show updated appointments
                    window.location.reload();
                });
            } else {
                throw new Error(result.message || 'Failed to reschedule appointment');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'An unexpected error occurred'
            });
        }
    });

    // Validation function
    function validateReschedule() {
        if (!document.getElementById('newDate').value) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please select a date'
            });
            return false;
        }

        if (!document.getElementById('newTime').value) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Please select a time'
            });
            return false;
        }

        return true;
    }

    // Utility functions
    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    function formatTime(timeString) {
        return new Date(`2000-01-01T${timeString}`).toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }
}); 
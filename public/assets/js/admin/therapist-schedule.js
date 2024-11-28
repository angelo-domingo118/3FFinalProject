document.addEventListener('DOMContentLoaded', function() {
    loadTherapistSchedule();
    initializeWeekNavigation();
    initializeAvailabilityForm();
});

let currentWeekStart = getStartOfWeek(new Date());

function getStartOfWeek(date) {
    const d = new Date(date);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(d.setDate(diff));
}

function initializeWeekNavigation() {
    document.getElementById('prevWeek').addEventListener('click', () => {
        currentWeekStart.setDate(currentWeekStart.getDate() - 7);
        loadTherapistSchedule();
    });

    document.getElementById('nextWeek').addEventListener('click', () => {
        currentWeekStart.setDate(currentWeekStart.getDate() + 7);
        loadTherapistSchedule();
    });

    document.getElementById('currentWeek').addEventListener('click', () => {
        currentWeekStart = getStartOfWeek(new Date());
        loadTherapistSchedule();
    });

    document.getElementById('therapistFilter').addEventListener('change', loadTherapistSchedule);
}

function loadTherapistSchedule() {
    const therapistId = document.getElementById('therapistFilter').value;
    if (!therapistId) return;

    fetch(`/api/therapist-schedule?therapist_id=${therapistId}&week_start=${currentWeekStart.toISOString()}`)
        .then(response => response.json())
        .then(data => {
            clearSchedule();
            displaySchedule(data);
        })
        .catch(error => {
            console.error('Error loading schedule:', error);
            showAlert('Error loading schedule. Please try again.', 'danger');
        });
}

function clearSchedule() {
    document.querySelectorAll('.availability-slot').forEach(slot => {
        slot.className = 'availability-slot';
    });
}

function displaySchedule(scheduleData) {
    scheduleData.forEach(slot => {
        const selector = `[data-day="${slot.day}"][data-time="${slot.start_time}"]`;
        const element = document.querySelector(selector);
        if (element) {
            element.classList.add(slot.is_booked ? 'booked' : 'available');
            element.setAttribute('data-schedule-id', slot.id);
        }
    });
}

function initializeAvailabilityForm() {
    const form = document.getElementById('availabilityForm');
    
    // Validate end time is after start time
    form.querySelector('[name="end_time"]').addEventListener('change', function() {
        const startTime = form.querySelector('[name="start_time"]').value;
        const endTime = this.value;
        
        if (endTime <= startTime) {
            showAlert('End time must be after start time', 'warning');
            this.value = '';
        }
    });
}

function saveAvailability() {
    const form = document.getElementById('availabilityForm');
    const formData = new FormData(form);
    
    fetch('/api/therapist-schedule', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Availability saved successfully', 'success');
            loadTherapistSchedule();
            $('#addAvailabilityModal').modal('hide');
        } else {
            showAlert(data.message || 'Error saving availability', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error saving availability. Please try again.', 'danger');
    });
}

function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

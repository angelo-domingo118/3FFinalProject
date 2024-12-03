document.addEventListener('DOMContentLoaded', function() {
    loadTherapistSchedule();
    initializeWeekNavigation();
    initializeAvailabilityForm();
});

let currentWeekStart = new Date();
currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1); // Set to Monday

// Function to format date as YYYY-MM-DD
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

// Function to load therapist schedule
async function loadTherapistSchedule() {
    const therapistId = document.getElementById('therapistFilter').value;
    if (!therapistId) {
        clearCalendar();
        return;
    }

    try {
        const weekStart = formatDate(currentWeekStart);
        console.log('Loading availability for week starting:', weekStart);
        
        const response = await fetch(`${BASE_URL}/api/therapists/${therapistId}/availability?week_start=${weekStart}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.log('Response:', response);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Data:', data);
        
        if (data.success) {
            updateCalendar(data.availability || []);
        } else {
            throw new Error(data.error || 'Failed to load availability');
        }
    } catch (error) {
        console.error('Error loading availability:', error);
        showAlert('Error loading schedule: ' + error.message, 'danger');
    }
}

// Function to update calendar
function updateCalendar(availability) {
    clearCalendar();
    
    // Update date headers
    const dateHeaders = document.querySelectorAll('thead th:not(:first-child)');
    let currentDate = new Date(currentWeekStart);
    
    dateHeaders.forEach(header => {
        const dayName = header.textContent.trim();
        const dateStr = formatDate(currentDate);
        header.innerHTML = `${dayName}<br><small>${dateStr}</small>`;
        currentDate.setDate(currentDate.getDate() + 1);
    });

    // Update availability slots
    availability.forEach(slot => {
        const dayCell = document.querySelector(`td[data-day="${slot.day_of_week}"][data-time="${slot.start_time}"]`);
        if (dayCell) {
            const slotDiv = dayCell.querySelector('.availability-slot');
            slotDiv.classList.add(slot.is_booked ? 'booked' : 'available');
            slotDiv.title = slot.is_booked ? 'Booked' : 'Available';
        }
    });
}

// Function to clear calendar
function clearCalendar() {
    document.querySelectorAll('.availability-slot').forEach(slot => {
        slot.className = 'availability-slot';
        slot.title = '';
    });
}

// Function to save availability
async function saveAvailability() {
    const form = document.getElementById('availabilityForm');
    const formData = new FormData(form);
    
    const data = {
        therapist_id: formData.get('therapist_id'),
        day_of_week: formData.get('day_of_week'),
        start_time: formData.get('start_time'),
        end_time: formData.get('end_time'),
        repeat_weekly: formData.get('repeat_weekly') === 'on'
    };

    try {
        const response = await fetch(`${BASE_URL}/api/therapists/availability`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            showAlert('Availability saved successfully!', 'success');
            const modal = document.getElementById('addAvailabilityModal');
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            bootstrapModal.hide();
            loadTherapistSchedule();
        } else {
            throw new Error(result.error || 'Failed to save availability');
        }
    } catch (error) {
        console.error('Error saving availability:', error);
        showAlert('Failed to save availability: ' + error.message, 'danger');
    }
}

// Function to show alert
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    if (container) {
        const firstChild = container.firstChild;
        if (firstChild) {
            container.insertBefore(alertDiv, firstChild);
        } else {
            container.appendChild(alertDiv);
        }
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

// Navigation functions
function previousWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    loadTherapistSchedule();
}

function nextWeek() {
    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    loadTherapistSchedule();
}

function resetToCurrentWeek() {
    currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1);
    loadTherapistSchedule();
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Set initial week start date
    currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1); // Set to Monday
    
    // Add event listeners
    const therapistFilter = document.getElementById('therapistFilter');
    if (therapistFilter) {
        therapistFilter.addEventListener('change', loadTherapistSchedule);
    }
    
    // Week navigation
    const prevWeekBtn = document.getElementById('prevWeek');
    const nextWeekBtn = document.getElementById('nextWeek');
    const currentWeekBtn = document.getElementById('currentWeek');
    
    if (prevWeekBtn) prevWeekBtn.addEventListener('click', previousWeek);
    if (nextWeekBtn) nextWeekBtn.addEventListener('click', nextWeek);
    if (currentWeekBtn) currentWeekBtn.addEventListener('click', resetToCurrentWeek);
    
    // Load initial data if therapist is selected
    if (therapistFilter && therapistFilter.value) {
        loadTherapistSchedule();
    }
});

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

document.addEventListener('DOMContentLoaded', function() {
    const therapistSelect = document.getElementById('therapistFilter');
    let currentWeekStart = getStartOfWeek(new Date());

    // Initialize calendar
    updateCalendarDates();
    if (therapistSelect.value) {
        loadTherapistAvailability();
    }

    // Event listeners
    therapistSelect.addEventListener('change', loadTherapistAvailability);
    document.getElementById('prevWeek').addEventListener('click', () => navigateWeek(-1));
    document.getElementById('nextWeek').addEventListener('click', () => navigateWeek(1));
    document.getElementById('currentWeek').addEventListener('click', () => {
        currentWeekStart = getStartOfWeek(new Date());
        updateCalendarDates();
        loadTherapistAvailability();
    });

    function getStartOfWeek(date) {
        const d = new Date(date);
        const day = d.getDay();
        const diff = d.getDate() - day + (day === 0 ? -6 : 1);
        return new Date(d.setDate(diff));
    }

    function navigateWeek(direction) {
        currentWeekStart.setDate(currentWeekStart.getDate() + (direction * 7));
        updateCalendarDates();
        loadTherapistAvailability();
    }

    function updateCalendarDates() {
        const headers = document.querySelectorAll('thead th:not(:first-child)');
        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        headers.forEach((header, index) => {
            const date = new Date(currentWeekStart);
            date.setDate(date.getDate() + index);
            const formattedDate = formatDate(date);
            const dayName = days[index];
            header.textContent = `${dayName} ${date.getDate()}/${date.getMonth() + 1}`;
            
            // Update data-date attributes for the cells in this column
            const columnCells = document.querySelectorAll(`.availability-slot[data-day="${dayName}"]`);
            columnCells.forEach(cell => {
                cell.dataset.date = formattedDate;
            });
        });
        
        debugCalendarCells();
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function getDayName(date) {
        return date.toLocaleDateString('en-US', { weekday: 'long' });
    }

    function clearAvailability() {
        document.querySelectorAll('.availability-slot').forEach(slot => {
            slot.className = 'availability-slot';
            slot.style.backgroundColor = ''; // Clear background color
            slot.style.cursor = 'default';
            slot.title = '';
            // Remove any tooltips
            const tooltip = slot.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    }

    function loadTherapistAvailability() {
        const therapistId = therapistSelect.value;
        if (!therapistId) return;

        // Clear existing availability
        clearAvailability();

        // Show loading state
        document.body.style.cursor = 'wait';

        const url = `${BASE_URL}/admin/get-therapist-availability?therapist_id=${therapistId}&week_start=${formatDate(currentWeekStart)}`;
        console.log('Fetching URL:', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            if (data.availability && Array.isArray(data.availability)) {
                console.log('Processing availability slots:', data.availability.length);
                
                if (data.availability.length === 0) {
                    console.log('No availability data for this week');
                    return;
                }
                
                data.availability.forEach(slot => {
                    console.log('Processing slot:', slot);
                    
                    // Get start and end hours
                    const startHour = parseInt(slot.start_time.split(':')[0]);
                    const endHour = parseInt(slot.end_time.split(':')[0]);
                    
                    // Mark each hour in the range as available
                    for (let hour = startHour; hour < endHour; hour++) {
                        const formattedTime = `${hour.toString().padStart(2, '0')}:00`;
                        console.log(`Looking for slot: date=${slot.date}, time=${formattedTime}`);
                        
                        const cell = document.querySelector(
                            `.availability-slot[data-date="${slot.date}"][data-time="${formattedTime}"]`
                        );
                        
                        if (cell) {
                            console.log('Found cell:', cell);
                            cell.classList.add('available');
                            cell.style.backgroundColor = '#28a745'; // Green for available
                            cell.style.cursor = 'pointer';
                            cell.title = 'Available';
                        } else {
                            console.log('Cell not found for:', slot.date, formattedTime);
                        }
                    }
                });
            } else {
                console.log('No availability data or invalid format');
                clearAvailability(); // Ensure cells are cleared if no data
            }
        })
        .catch(error => {
            console.error('Error loading availability:', error);
            alert(`Error loading therapist availability: ${error.message}`);
            clearAvailability(); // Clear cells on error
        })
        .finally(() => {
            document.body.style.cursor = 'default';
        });
    }

    function debugCalendarCells() {
        const cells = document.querySelectorAll('.availability-slot');
        console.log('Total calendar cells:', cells.length);
        cells.forEach(cell => {
            console.log('Cell:', {
                date: cell.dataset.date,
                time: cell.dataset.time,
                className: cell.className
            });
        });
    }

    async function fetchTherapistAvailability(therapistId, weekStart) {
        console.group('Fetching Therapist Availability');
        console.log(`Therapist ID: ${therapistId}`);
        console.log(`Week Start: ${weekStart}`);
        
        const url = `${BASE_URL}/admin/get-therapist-availability?therapist_id=${therapistId}&week_start=${weekStart}`;
        console.log('Request URL:', url);
        
        try {
            const response = await fetch(url);
            console.log('Response Status:', response.status);
            
            const data = await response.json();
            console.log('Raw Response Data:', data);
            
            if (data.success && data.availability) {
                console.log('Number of availability slots:', data.availability.length);
                
                console.group('Availability Slots');
                data.availability.forEach((slot, index) => {
                    console.log(`Slot ${index + 1}:`, {
                        date: slot.date,
                        start_time: slot.start_time,
                        end_time: slot.end_time,
                        status: slot.status
                    });
                });
                console.groupEnd();
                
                // Debug calendar cells before update
                console.group('Calendar Cells Before Update');
                const allCells = document.querySelectorAll('.time-slot');
                console.log('Total calendar cells:', allCells.length);
                allCells.forEach((cell, index) => {
                    console.log(`Cell ${index + 1}:`, {
                        date: cell.dataset.date,
                        time: cell.dataset.time,
                        className: cell.className
                    });
                });
                console.groupEnd();
                
                // Update calendar UI
                updateCalendarWithAvailability(data.availability);
                
            } else {
                console.error('Failed to get availability data:', data);
            }
        } catch (error) {
            console.error('Error fetching availability:', error);
        }
        console.groupEnd();
    }

    // Add this function to debug calendar structure
    function debugCalendarStructure() {
        console.group('Calendar Structure Debug');
        
        const calendar = document.querySelector('.schedule-calendar');
        if (!calendar) {
            console.error('Calendar table not found!');
            console.groupEnd();
            return;
        }
        
        const rows = calendar.querySelectorAll('tr');
        console.log('Total rows:', rows.length);
        
        rows.forEach((row, rowIndex) => {
            const cells = row.querySelectorAll('td');
            console.log(`Row ${rowIndex}:`, {
                totalCells: cells.length,
                cells: Array.from(cells).map(cell => ({
                    date: cell.dataset.date,
                    time: cell.dataset.time,
                    className: cell.className
                }))
            });
        });
        
        console.groupEnd();
    }

    // Add styles to the document
    const styles = document.createElement('style');
    styles.textContent = `
        .time-slot {
            padding: 10px;
            text-align: center;
            border: 1px solid #dee2e6;
            cursor: default;
            transition: all 0.2s ease;
        }
        
        .time-slot.available {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        
        .time-slot.available:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        
        .time-slot.booked {
            background-color: #dc3545;
            color: white;
            cursor: not-allowed;
        }
        
        .schedule-calendar {
            border-collapse: collapse;
            width: 100%;
        }
        
        .schedule-calendar th {
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        
        .schedule-calendar td:first-child {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    `;
    document.head.appendChild(styles);

    // Call this function after the calendar is loaded
    document.addEventListener('DOMContentLoaded', () => {
        debugCalendarStructure();
    });

    // Add Availability Form Handling
    function validateAvailabilityForm() {
        console.log('Validating form...');
        
        const form = document.getElementById('availabilityForm');
        const therapistId = form.querySelector('[name="therapist_id"]').value;
        const date = form.querySelector('[name="date"]').value;
        const startTime = form.querySelector('[name="start_time"]').value;
        const endTime = form.querySelector('[name="end_time"]').value;

        console.log('Form values:', {
            therapistId,
            date,
            startTime,
            endTime
        });

        if (!therapistId) {
            alert('Please select a therapist');
            return false;
        }

        if (!date) {
            alert('Please select a date');
            return false;
        }

        if (!startTime || !endTime) {
            alert('Please select both start and end times');
            return false;
        }

        const startHour = parseInt(startTime.split(':')[0]);
        const endHour = parseInt(endTime.split(':')[0]);

        if (endHour <= startHour) {
            alert('End time must be after start time');
            return false;
        }

        // Check if selected date is not in the past
        const selectedDate = new Date(date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            alert('Cannot add availability for past dates');
            return false;
        }

        console.log('Form validation passed');
        return true;
    }

    // Make saveAvailability globally accessible
    window.saveAvailability = function() {
        console.log('Save Availability function called');
        
        if (!validateAvailabilityForm()) {
            console.log('Form validation failed');
            return;
        }

        const form = document.getElementById('availabilityForm');
        const formData = new FormData(form);
        
        // Log form data
        console.log('Form Data:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        const repeatWeekly = form.querySelector('[name="repeat_weekly"]').checked;
        console.log('Repeat Weekly:', repeatWeekly);

        // If repeat weekly is checked, calculate future dates
        if (repeatWeekly) {
            const baseDate = new Date(formData.get('date'));
            const dates = [];
            
            // Add availability for the next 12 weeks
            for (let i = 0; i < 12; i++) {
                const futureDate = new Date(baseDate);
                futureDate.setDate(baseDate.getDate() + (i * 7));
                dates.push(formatDate(futureDate));
            }
            formData.append('dates', JSON.stringify(dates));
            console.log('Generated dates:', dates);
        }

        // Show loading state
        const saveButton = document.getElementById('saveAvailabilityBtn');
        const originalText = saveButton.textContent;
        saveButton.textContent = 'Saving...';
        saveButton.disabled = true;

        console.log('Sending request to:', `${BASE_URL}/admin/save-therapist-availability`);

        fetch(`${BASE_URL}/admin/save-therapist-availability`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse response as JSON:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        })
        .then(data => {
            console.log('Server response:', data);
            
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addAvailabilityModal'));
                modal.hide();

                // Reset form
                form.reset();

                // Refresh calendar
                loadTherapistAvailability();

                // Show success message
                alert('Availability saved successfully');
            } else {
                throw new Error(data.error || 'Failed to save availability');
            }
        })
        .catch(error => {
            console.error('Error saving availability:', error);
            console.error('Error details:', {
                message: error.message,
                stack: error.stack
            });
            alert(`Error saving availability: ${error.message}`);
        })
        .finally(() => {
            // Reset button state
            saveButton.textContent = originalText;
            saveButton.disabled = false;
        });
    };

    // Also expose validateAvailabilityForm for use by saveAvailability
    window.validateAvailabilityForm = function() {
        console.log('Validating form...');
        
        const form = document.getElementById('availabilityForm');
        const therapistId = form.querySelector('[name="therapist_id"]').value;
        const date = form.querySelector('[name="date"]').value;
        const startTime = form.querySelector('[name="start_time"]').value;
        const endTime = form.querySelector('[name="end_time"]').value;

        console.log('Form values:', {
            therapistId,
            date,
            startTime,
            endTime
        });

        if (!therapistId) {
            alert('Please select a therapist');
            return false;
        }

        if (!date) {
            alert('Please select a date');
            return false;
        }

        if (!startTime || !endTime) {
            alert('Please select both start and end times');
            return false;
        }

        const startHour = parseInt(startTime.split(':')[0]);
        const endHour = parseInt(endTime.split(':')[0]);

        if (endHour <= startHour) {
            alert('End time must be after start time');
            return false;
        }

        // Check if selected date is not in the past
        const selectedDate = new Date(date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            alert('Cannot add availability for past dates');
            return false;
        }

        console.log('Form validation passed');
        return true;
    };

    // Add this at the top of your file to ensure BASE_URL is defined
    if (typeof BASE_URL === 'undefined') {
        console.error('BASE_URL is not defined. Make sure it is set in your PHP template.');
    }

    // Add form reset when modal is closed
    document.getElementById('addAvailabilityModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('availabilityForm').reset();
    });

    // Add time validation on time select change
    document.querySelectorAll('#availabilityForm select[name="start_time"], #availabilityForm select[name="end_time"]')
    .forEach(select => {
        select.addEventListener('change', function() {
            const form = this.closest('form');
            const startTime = form.querySelector('[name="start_time"]').value;
            const endTime = form.querySelector('[name="end_time"]').value;

            if (startTime && endTime) {
                const startHour = parseInt(startTime.split(':')[0]);
                const endHour = parseInt(endTime.split(':')[0]);

                if (endHour <= startHour) {
                    alert('End time must be after start time');
                    this.value = ''; // Reset the changed select
                }
            }
        });
    });

    // Initialize datepicker for the date input
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('#availabilityForm [name="date"]');
        if (dateInput) {
            // Set min date to today
            const today = new Date();
            dateInput.min = formatDate(today);
            
            // Set max date to 3 months from now
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 3);
            dateInput.max = formatDate(maxDate);
        }
    });
}); 
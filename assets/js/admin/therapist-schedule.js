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
}); 
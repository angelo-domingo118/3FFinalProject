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
        const cells = document.querySelectorAll('.availability-slot');
        
        headers.forEach((header, index) => {
            const date = new Date(currentWeekStart);
            date.setDate(date.getDate() + index);
            const formattedDate = formatDate(date);
            header.textContent = `${getDayName(date)} ${date.getDate()}/${date.getMonth() + 1}`;
            
            // Update data-date attributes for the cells in this column
            const columnCells = document.querySelectorAll(`.availability-slot[data-day="${header.textContent.split(' ')[0]}"]`);
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

    function loadTherapistAvailability() {
        const therapistId = therapistSelect.value;
        if (!therapistId) return;

        // Clear existing availability
        document.querySelectorAll('.availability-slot').forEach(slot => {
            slot.className = 'availability-slot';
            slot.title = ''; // Clear existing titles
        });

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
                
                data.availability.forEach(slot => {
                    console.log('Processing slot:', slot);
                    const slotDate = new Date(slot.date);
                    const formattedDate = formatDate(slotDate);
                    console.log(`Looking for slot: date=${formattedDate}, time=${slot.start_time}`);
                    
                    const cell = document.querySelector(
                        `.availability-slot[data-date="${formattedDate}"][data-time="${slot.start_time}"]`
                    );
                    
                    if (cell) {
                        console.log('Found cell:', cell);
                        cell.classList.add(slot.is_booked ? 'booked' : 'available');
                        cell.title = slot.is_booked ? 'Booked' : 'Available';
                    } else {
                        console.log('Cell not found for:', formattedDate, slot.start_time);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading availability:', error);
            alert(`Error loading therapist availability: ${error.message}`);
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

    function updateCalendarWithAvailability(availability) {
        console.group('Updating Calendar UI');
        
        // Clear existing availability indicators
        const existingSlots = document.querySelectorAll('.time-slot');
        console.log('Clearing existing slots:', existingSlots.length);
        
        existingSlots.forEach(slot => {
            slot.className = 'time-slot';
            // Remove existing tooltips
            const tooltip = slot.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
        
        // Process each availability slot
        availability.forEach((slot, index) => {
            console.group(`Processing Slot ${index + 1}`);
            console.log('Slot data:', slot);
            
            const timeSlots = slot.start_time.split(':')[0];
            const selector = `[data-date="${slot.date}"][data-time="${timeSlots}:00"]`;
            console.log('Looking for cell with selector:', selector);
            
            const cell = document.querySelector(selector);
            if (cell) {
                console.log('Found cell:', {
                    date: cell.dataset.date,
                    time: cell.dataset.time,
                    currentClass: cell.className
                });
                
                // Update cell class
                cell.className = `time-slot ${slot.status}`;
                
                // Add tooltip
                const tooltip = document.createElement('span');
                tooltip.className = 'tooltip';
                tooltip.textContent = `${slot.status.charAt(0).toUpperCase() + slot.status.slice(1)}`;
                cell.appendChild(tooltip);
                
                console.log('Updated cell:', {
                    newClass: cell.className,
                    hasTooltip: cell.querySelector('.tooltip') !== null
                });
            } else {
                console.warn('No matching cell found for:', {
                    date: slot.date,
                    time: `${timeSlots}:00`
                });
            }
            console.groupEnd();
        });
        
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

    // Call this function after the calendar is loaded
    document.addEventListener('DOMContentLoaded', () => {
        debugCalendarStructure();
    });
}); 
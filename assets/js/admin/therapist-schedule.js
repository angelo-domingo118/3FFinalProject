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
    }

    function getDayName(date) {
        return date.toLocaleDateString('en-US', { weekday: 'short' });
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function loadTherapistAvailability() {
        const therapistId = therapistSelect.value;
        if (!therapistId) return;

        // Clear existing availability
        document.querySelectorAll('.availability-slot').forEach(slot => {
            slot.className = 'availability-slot';
        });

        // Show loading state
        document.body.style.cursor = 'wait';

        const url = `${BASE_URL}/admin/get-therapist-availability?therapist_id=${therapistId}&week_start=${formatDate(currentWeekStart)}`;
        console.log('Fetching URL:', url);

        fetch(url)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.error) {
                    throw new Error(data.error);
                }
                
                if (data.availability && Array.isArray(data.availability)) {
                    data.availability.forEach(slot => {
                        const slotDate = new Date(slot.date);
                        const dayName = getDayName(slotDate);
                        const cell = document.querySelector(
                            `.availability-slot[data-date="${slot.date}"][data-time="${slot.start_time}"]`
                        );
                        if (cell) {
                            cell.classList.add(slot.is_booked ? 'booked' : 'available');
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
}); 
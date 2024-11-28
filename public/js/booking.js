document.addEventListener('DOMContentLoaded', function() {
    const confirmBookingBtn = document.getElementById('confirmBookingBtn');
    const termsCheckbox = document.getElementById('terms');

    if (confirmBookingBtn) {
        confirmBookingBtn.addEventListener('click', function() {
            // Check if terms are accepted
            if (!termsCheckbox.checked) {
                alert('Please accept the terms and conditions to proceed.');
                return;
            }

            // Get the notes
            const notes = document.getElementById('notes').value;

            // Here you would typically make an AJAX call to save the booking
            // For now, we'll just show the confirmation dialog
            Swal.fire({
                title: 'Booking Confirmed!',
                text: 'Would you like to book another service?',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, book another service',
                cancelButtonText: 'No, go to dashboard'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to services page
                    window.location.href = BASE_URL + '/public/services';
                } else {
                    // Redirect to user dashboard
                    window.location.href = BASE_URL + '/public/dashboard';
                }
            });
        });
    }
});

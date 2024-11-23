// Add service filtering functionality
document.querySelectorAll('.service-filters button').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('.service-filters button').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Add active class to clicked button
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        
        // Show/hide services based on filter
        document.querySelectorAll('.service-card').forEach(card => {
            if (filter === 'all' || card.classList.contains(`service-type-${filter}`)) {
                card.closest('.col-md-6').style.display = '';
            } else {
                card.closest('.col-md-6').style.display = 'none';
            }
        });
    });
}); 
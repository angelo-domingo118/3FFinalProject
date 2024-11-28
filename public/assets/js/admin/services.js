// Search functionality
document.getElementById('serviceSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const serviceName = row.querySelector('h6').textContent.toLowerCase();
        const serviceDesc = row.querySelector('small').textContent.toLowerCase();
        const shouldShow = serviceName.includes(searchTerm) || serviceDesc.includes(searchTerm);
        row.style.display = shouldShow ? '' : 'none';
    });
});

// Filter by service type
document.getElementById('serviceTypeFilter').addEventListener('change', function(e) {
    const filterType = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const serviceType = row.querySelector('.badge').textContent.toLowerCase();
        row.style.display = !filterType || serviceType === filterType ? '' : 'none';
    });
});

// Edit service
function editService(serviceId) {
    // Fetch service details
    fetch(`${BASE_URL}/public/api/services/${serviceId}`)
        .then(response => response.json())
        .then(service => {
            const form = document.getElementById('editServiceForm');
            form.querySelector('[name="service_id"]').value = service.service_id;
            form.querySelector('[name="service_name"]').value = service.service_name;
            form.querySelector('[name="description"]').value = service.description;
            form.querySelector('[name="duration"]').value = service.duration;
            form.querySelector('[name="price"]').value = service.price;
            form.querySelector('[name="service_type"]').value = service.service_type;
            form.querySelector('[name="is_popular"]').checked = service.is_popular;
            
            // Show modal
            new bootstrap.Modal(document.getElementById('editServiceModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load service details');
        });
}

// Save new service
function saveService() {
    const form = document.getElementById('addServiceForm');
    const formData = new FormData(form);
    
    fetch(`${BASE_URL}/public/api/services`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        } else {
            alert(result.message || 'Failed to add service');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add service');
    });
}

// Update existing service
function updateService() {
    const form = document.getElementById('editServiceForm');
    const formData = new FormData(form);
    const serviceId = formData.get('service_id');
    
    fetch(`${BASE_URL}/public/api/services/${serviceId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        } else {
            alert(result.message || 'Failed to update service');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update service');
    });
}

// Delete service
function deleteService(serviceId) {
    if (!confirm('Are you sure you want to delete this service?')) {
        return;
    }
    
    fetch(`${BASE_URL}/public/api/services/${serviceId}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        } else {
            alert(result.message || 'Failed to delete service');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete service');
    });
}

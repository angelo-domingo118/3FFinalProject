// Function to save a new service
async function saveService() {
    console.log('saveService called');
    const form = document.getElementById('addServiceForm');
    const formData = new FormData(form);
    
    const data = {
        service_name: formData.get('service_name'),
        description: formData.get('description'),
        duration: parseInt(formData.get('duration')),
        price: parseFloat(formData.get('price')),
        service_type: formData.get('service_type'),
        is_popular: formData.get('is_popular') === 'on'
    };

    console.log('Service data:', data);

    try {
        const response = await fetch('/cit17-final-project/public/api/services', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });

        console.log('Response status:', response.status);
        const result = await response.json();
        console.log('Response data:', result);

        if (response.ok) {
            alert('Service created successfully!');
            window.location.reload();
        } else {
            alert(result.error || 'Failed to create service');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to create service. Please try again.');
    }
}

// Function to edit a service
async function editService(serviceId) {
    console.log('editService called with ID:', serviceId);
    try {
        const response = await fetch(`/cit17-final-project/public/api/services/${serviceId}`);
        console.log('Response status:', response.status);
        const service = await response.json();
        console.log('Service data:', service);

        if (response.ok) {
            const form = document.getElementById('editServiceForm');
            form.querySelector('[name="service_id"]').value = service.service_id;
            form.querySelector('[name="service_name"]').value = service.service_name;
            form.querySelector('[name="description"]').value = service.description;
            form.querySelector('[name="duration"]').value = service.duration;
            form.querySelector('[name="price"]').value = service.price;
            form.querySelector('[name="service_type"]').value = service.service_type;
            form.querySelector('[name="is_popular"]').checked = service.is_popular;

            // Show the edit modal
            new bootstrap.Modal(document.getElementById('editServiceModal')).show();
        } else {
            alert(service.error || 'Failed to load service details');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load service details. Please try again.');
    }
}

// Function to update a service
async function updateService() {
    console.log('updateService called');
    const form = document.getElementById('editServiceForm');
    const formData = new FormData(form);
    const serviceId = formData.get('service_id');

    const data = {
        service_name: formData.get('service_name'),
        description: formData.get('description'),
        duration: parseInt(formData.get('duration')),
        price: parseFloat(formData.get('price')),
        service_type: formData.get('service_type'),
        is_popular: formData.get('is_popular') === 'on'
    };

    console.log('Update data:', data);

    try {
        const response = await fetch(`/cit17-final-project/public/api/services/${serviceId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });

        console.log('Response status:', response.status);
        const result = await response.json();
        console.log('Response data:', result);

        if (response.ok) {
            alert('Service updated successfully!');
            window.location.reload();
        } else {
            alert(result.error || 'Failed to update service');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update service. Please try again.');
    }
}

// Function to delete a service
async function deleteService(serviceId) {
    console.log('deleteService called with ID:', serviceId);
    if (!confirm('Are you sure you want to delete this service?')) {
        return;
    }

    try {
        const response = await fetch(`/cit17-final-project/public/api/services/${serviceId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        console.log('Response status:', response.status);
        const result = await response.json();
        console.log('Response data:', result);

        if (response.ok) {
            alert('Service deleted successfully!');
            window.location.reload();
        } else {
            alert(result.error || 'Failed to delete service');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete service. Please try again.');
    }
}

// Function to filter services
function filterServices() {
    console.log('filterServices called');
    const searchInput = document.getElementById('serviceSearch').value.toLowerCase().trim();
    const typeFilter = document.getElementById('serviceTypeFilter').value.toLowerCase().trim();
    const rows = document.querySelectorAll('tbody tr');

    console.log('Search term:', searchInput);
    console.log('Type filter:', typeFilter);

    rows.forEach(row => {
        const serviceName = row.querySelector('h6').textContent.toLowerCase().trim();
        const serviceType = row.querySelector('.badge').textContent.toLowerCase().trim();
        
        console.log('Row service type:', serviceType, 'Selected filter:', typeFilter);
        
        const matchesSearch = !searchInput || serviceName.includes(searchInput);
        const matchesType = !typeFilter || serviceType === typeFilter;

        console.log(`Service: ${serviceName} - Type: ${serviceType}`);
        console.log('Matches - Search:', matchesSearch, 'Type:', matchesType);
        
        row.style.display = (matchesSearch && matchesType) ? '' : 'none';
    });
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up event listeners');
    
    // Add listeners for search and filter
    const searchInput = document.getElementById('serviceSearch');
    const typeFilter = document.getElementById('serviceTypeFilter');
    
    if (searchInput) {
        console.log('Search input found');
        searchInput.addEventListener('input', filterServices);
    }
    
    if (typeFilter) {
        console.log('Type filter found');
        typeFilter.addEventListener('change', filterServices);
    }

    // Log initial values
    if (typeFilter) {
        console.log('Available filter options:', Array.from(typeFilter.options).map(opt => opt.value));
    }
});

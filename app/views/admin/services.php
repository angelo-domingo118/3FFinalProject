<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Manage Services</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
            <i class="bi bi-plus-circle me-2"></i>Add Service
        </button>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="serviceSearch" class="form-control border-0 bg-light" placeholder="Search services...">
                            </div>
                        </div>
                        <div class="col-auto">
                            <select id="serviceTypeFilter" class="form-select border-0 bg-light">
                                <option value="">All Types</option>
                                <option value="massage">Massage</option>
                                <option value="facial">Facial</option>
                                <option value="body">Body</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0" style="width: 40%">Service</th>
                                    <th class="border-0" style="width: 15%">Type</th>
                                    <th class="border-0" style="width: 12%">Duration</th>
                                    <th class="border-0" style="width: 15%">Price</th>
                                    <th class="border-0" style="width: 8%">Popular</th>
                                    <th class="border-0 text-end" style="width: 10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $service): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="service-icon me-3">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <?php
                                                    $icon = '';
                                                    switch($service['service_type']) {
                                                        case 'massage':
                                                            $icon = '<i class="bi bi-hand-index-thumb fs-5"></i>';
                                                            break;
                                                        case 'facial':
                                                            $icon = '<i class="bi bi-emoji-smile fs-5"></i>';
                                                            break;
                                                        case 'body':
                                                            $icon = '<i class="bi bi-person-arms-up fs-5"></i>';
                                                            break;
                                                    }
                                                    echo $icon;
                                                    ?>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($service['service_name']); ?></h6>
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 300px;"><?php echo htmlspecialchars($service['description']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $service['service_type'] === 'massage' ? 'info' : ($service['service_type'] === 'facial' ? 'success' : 'warning'); ?> text-nowrap">
                                            <?php echo $service['service_type']; ?>
                                        </span>
                                    </td>
                                    <td class="text-nowrap"><?php echo $service['duration']; ?> mins</td>
                                    <td class="text-nowrap">₱<?php echo number_format($service['price'], 2); ?></td>
                                    <td class="text-center">
                                        <?php if ($service['is_popular']): ?>
                                            <i class="bi bi-star-fill text-warning" title="Popular Service"></i>
                                        <?php else: ?>
                                            <i class="bi bi-star text-secondary" title="Regular Service"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editService(<?php echo $service['service_id']; ?>)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteService(<?php echo $service['service_id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addServiceForm">
                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text" class="form-control" name="service_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Duration (mins)</label>
                            <input type="number" class="form-control" name="duration" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Price (₱)</label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Type</label>
                        <select class="form-select" name="service_type" required>
                            <option value="massage">Massage</option>
                            <option value="facial">Facial</option>
                            <option value="body">Body</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="is_popular" id="isPopular">
                        <label class="form-check-label" for="isPopular">Mark as Popular Service</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveService()">Save Service</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editServiceForm">
                    <input type="hidden" name="service_id">
                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text" class="form-control" name="service_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Duration (mins)</label>
                            <input type="number" class="form-control" name="duration" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Price (₱)</label>
                            <input type="number" step="0.01" class="form-control" name="price" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Type</label>
                        <select class="form-select" name="service_type" required>
                            <option value="massage">Massage</option>
                            <option value="facial">Facial</option>
                            <option value="body">Body</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="is_popular" id="editIsPopular">
                        <label class="form-check-label" for="editIsPopular">Mark as Popular Service</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateService()">Update Service</button>
            </div>
        </div>
    </div>
</div>

<!-- Add your custom scripts -->
<script src="/cit17-final-project/public/assets/js/admin/services.js"></script>
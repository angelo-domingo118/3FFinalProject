<?php
// Display error message if exists
if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Display success message if exists -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Left Column: Personal Information & Password -->
        <div class="col-lg-8">
            <!-- Personal Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Personal Information</h5>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2">Customer Profile</span>
                    </div>
                    
                    <form id="profile-form" action="<?php echo BASE_URL; ?>/public/dashboard/profile/update" method="POST" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                <div class="invalid-feedback">Please enter your full name</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                <div class="invalid-feedback">Please enter a valid email address</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                       value="<?php echo htmlspecialchars($user['phone_number']); ?>" 
                                       pattern="[0-9]{11}" required>
                                <div class="invalid-feedback">Please enter a valid 11-digit phone number</div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Change Password</h5>
                        <span class="badge bg-warning-subtle text-warning px-3 py-2">Security Settings</span>
                    </div>
                    
                    <form id="password-form" action="<?php echo BASE_URL; ?>/public/dashboard/profile/password" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <!-- Password Strength Meter -->
                                <div class="mt-2">
                                    <div class="progress" style="height: 5px;">
                                        <div id="password-strength-meter" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">Password Strength:</small>
                                        <small id="password-strength-text" class="text-muted">Not set</small>
                                    </div>
                                </div>
                                <ul id="password-feedback" class="small text-muted mt-2"></ul>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div id="confirm-password-feedback" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning" disabled>
                                <i class="bi bi-shield-lock me-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Account Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Account Summary</h5>
                    <div class="text-center mb-4">
                        <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3">
                            <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                        </div>
                        <h6 class="mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h6>
                        <p class="text-muted mb-0">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                    </div>
                    <hr>
                    <div class="account-stats">
                        <div class="row g-0">
                            <div class="col-6 border-end">
                                <div class="p-3 text-center">
                                    <div class="display-6 mb-1"><?php echo $total_appointments; ?></div>
                                    <p class="text-muted mb-0">Appointments</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 text-center">
                                    <div class="display-6 mb-1"><?php echo $total_reviews; ?></div>
                                    <p class="text-muted mb-0">Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Security Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Account Security</h5>
                    <div class="security-items">
                        <div class="d-flex align-items-center mb-3">
                            <div class="security-icon bg-success-subtle text-success rounded-circle me-3">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Two-Factor Authentication</h6>
                                <p class="text-muted mb-0">Not enabled</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="security-icon bg-info-subtle text-info rounded-circle me-3">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Last Password Change</h6>
                                <p class="text-muted mb-0"><?php echo date('F d, Y', strtotime($user['updated_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-placeholder {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
}

.security-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.display-6 {
    font-size: 2rem;
    font-weight: 600;
}
</style>

<!-- Include profile.js -->
<script src="<?php echo BASE_URL; ?>/public/assets/js/profile.js"></script>
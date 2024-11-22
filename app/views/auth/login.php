<?php
// Remove the header include
// <?php include '../app/views/templates/header.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SereneBook - Your Path to Wellness. Book spa and wellness services online.">
    <title>Login - SereneBook™</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>/public/assets/css/styles.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .login-panel {
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #fff;
            position: relative;
        }

        .login-brand {
            position: absolute;
            top: 2rem;
            left: 2.5rem;
            margin-bottom: 3rem;
        }

        .login-brand a {
            color: #0d6efd;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .login-brand a:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .login-form-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 420px;
            margin: 0 auto;
            width: 100%;
            margin-top: -2rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #6c757d;
            font-size: 1rem;
        }

        .input-group {
            border: 1px solid #dee2e6;
            border-radius: 50px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .input-group-text {
            background: transparent;
            border: none;
            padding-left: 1.5rem;
        }

        .form-control {
            border: none;
            padding: 0.75rem 0.75rem 0.75rem 0;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .welcome-panel {
            background: linear-gradient(135deg, 
                rgba(0, 123, 255, 0.95), 
                rgba(0, 98, 204, 0.9)), 
                url('<?php echo BASE_URL; ?>/public/assets/images/spa-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 2rem;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .welcome-panel::-webkit-scrollbar {
            width: 6px;
        }

        .welcome-panel::-webkit-scrollbar-track {
            background: transparent;
        }

        .welcome-panel::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            border: transparent;
        }

        .welcome-panel::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .brand-section {
            position: relative;
            z-index: 2;
            padding: 0.5rem 0;
            margin-bottom: 1rem;
        }

        .brand-link {
            color: white;
            text-decoration: none;
            font-size: 1.25rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .welcome-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 2;
            max-width: 600px;
            margin: 0 auto;
            padding-top: 1rem;
            margin-top: -2rem;
        }

        .welcome-header {
            margin-bottom: 1.5rem;
        }

        .welcome-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .welcome-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.875rem;
            width: 100%;
            margin-top: 1rem;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.25rem;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .feature-item:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.15);
        }

        .feature-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .feature-item h5 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .feature-item p {
            font-size: 0.875rem;
            margin-bottom: 0;
            opacity: 0.8;
        }

        /* Responsive adjustments */
        @media (max-height: 800px) {
            .welcome-content {
                margin-top: -1.5rem;
            }

            .welcome-header {
                margin-bottom: 1rem;
            }

            .feature-item {
                padding: 1rem;
            }

            .features-grid {
                gap: 0.75rem;
            }
        }

        .input-group-text {
            background: transparent;
            border-right: none;
        }

        .form-control {
            border-left: none;
            padding-left: 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        .btn-primary {
            padding: 0.8rem;
            font-weight: 500;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.3);
        }

        .feature-item {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.15);
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .floating-element {
            animation: float 6s ease-in-out infinite;
        }

        .container-fluid {
            padding: 0;
            margin: 0;
            overflow: hidden;
        }

        .row {
            margin: 0;
            height: 100vh;
        }

        .login-panel .mb-5 {
            margin-bottom: 2rem !important;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Login Panel (Left) -->
        <div class="col-lg-5">
            <div class="login-panel">
                <div class="login-brand">
                    <a href="<?php echo BASE_URL; ?>/public">
                        <i class="bi bi-heart-pulse-fill me-2"></i>
                        SereneBook™
                    </a>
                </div>

                <div class="login-form-container">
                    <div class="form-header">
                        <h2>Welcome Back</h2>
                        <p>Please enter your credentials to continue</p>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php 
                                echo htmlspecialchars($_SESSION['error']);
                                unset($_SESSION['error']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>/public/login" method="POST" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="email" class="form-label">Email address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" 
                                       required placeholder="Enter your email">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password" 
                                       required placeholder="Enter your password">
                                <button class="btn btn-link text-muted px-3" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="<?php echo BASE_URL; ?>/public/forgot-password" class="text-primary text-decoration-none">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-4 py-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Log In
                        </button>

                        <p class="text-center mb-0">
                            Don't have an account? 
                            <a href="<?php echo BASE_URL; ?>/public/register" class="text-primary text-decoration-none fw-medium">
                                Sign up
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Welcome Panel (Right) -->
        <div class="col-lg-7">
            <div class="welcome-panel">
                <div class="welcome-content">
                    <div class="welcome-header">
                        <i class="bi bi-peace display-4 mb-2"></i>
                        <h1>Your Path to Wellness</h1>
                        <p>Experience tranquility and rejuvenation with our premium wellness services</p>
                    </div>

                    <div class="features-grid">
                        <div class="feature-item">
                            <i class="bi bi-calendar-check feature-icon"></i>
                            <h5>Easy Booking</h5>
                            <p>Schedule appointments in minutes</p>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-award feature-icon"></i>
                            <h5>Expert Therapists</h5>
                            <p>Certified wellness experts</p>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-heart feature-icon"></i>
                            <h5>Premium Service</h5>
                            <p>Tailored wellness treatments</p>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-shield-check feature-icon"></i>
                            <h5>Safe & Secure</h5>
                            <p>Your privacy is our priority</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
</script>

</body>
</html> 
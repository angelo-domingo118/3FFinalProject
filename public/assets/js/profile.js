// Password validation rules
const passwordRules = {
    minLength: 8,
    requireUppercase: true,
    requireLowercase: true,
    requireNumber: true,
    requireSpecial: true
};

// Password strength meter
function checkPasswordStrength(password) {
    let strength = 0;
    let feedback = [];

    // Check length
    if (password.length >= passwordRules.minLength) {
        strength += 25;
    } else {
        feedback.push(`Password must be at least ${passwordRules.minLength} characters long`);
    }

    // Check for uppercase letters
    if (passwordRules.requireUppercase && /[A-Z]/.test(password)) {
        strength += 25;
    } else {
        feedback.push('Include at least one uppercase letter');
    }

    // Check for lowercase letters
    if (passwordRules.requireLowercase && /[a-z]/.test(password)) {
        strength += 25;
    } else {
        feedback.push('Include at least one lowercase letter');
    }

    // Check for numbers
    if (passwordRules.requireNumber && /\d/.test(password)) {
        strength += 12.5;
    } else {
        feedback.push('Include at least one number');
    }

    // Check for special characters
    if (passwordRules.requireSpecial && /[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        strength += 12.5;
    } else {
        feedback.push('Include at least one special character');
    }

    return {
        strength: strength,
        feedback: feedback
    };
}

// Update password strength meter
function updatePasswordStrength(password) {
    const result = checkPasswordStrength(password);
    const strengthMeter = document.getElementById('password-strength-meter');
    const strengthText = document.getElementById('password-strength-text');
    const feedbackList = document.getElementById('password-feedback');

    // Update strength meter
    strengthMeter.style.width = `${result.strength}%`;
    strengthMeter.className = 'progress-bar';
    
    if (result.strength <= 25) {
        strengthMeter.classList.add('bg-danger');
        strengthText.textContent = 'Weak';
    } else if (result.strength <= 50) {
        strengthMeter.classList.add('bg-warning');
        strengthText.textContent = 'Fair';
    } else if (result.strength <= 75) {
        strengthMeter.classList.add('bg-info');
        strengthText.textContent = 'Good';
    } else {
        strengthMeter.classList.add('bg-success');
        strengthText.textContent = 'Strong';
    }

    // Update feedback list
    feedbackList.innerHTML = result.feedback.map(item => `<li>${item}</li>`).join('');
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Password change form validation
    const passwordForm = document.getElementById('password-form');
    if (passwordForm) {
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const submitButton = passwordForm.querySelector('button[type="submit"]');

        // Add password strength meter listener
        newPasswordInput.addEventListener('input', function() {
            updatePasswordStrength(this.value);
            validatePasswords();
        });

        // Add confirm password listener
        confirmPasswordInput.addEventListener('input', validatePasswords);

        function validatePasswords() {
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const strengthResult = checkPasswordStrength(newPassword);

            // Check if password meets minimum requirements
            const isValid = strengthResult.strength >= 75 && newPassword === confirmPassword;
            submitButton.disabled = !isValid;

            // Update confirm password feedback
            const confirmFeedback = document.getElementById('confirm-password-feedback');
            if (confirmPassword && newPassword !== confirmPassword) {
                confirmFeedback.textContent = 'Passwords do not match';
                confirmFeedback.style.display = 'block';
            } else {
                confirmFeedback.style.display = 'none';
            }
        }
    }

    // Profile update form validation
    const profileForm = document.getElementById('profile-form');
    if (profileForm) {
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone_number');
        const submitButton = profileForm.querySelector('button[type="submit"]');

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function validatePhone(phone) {
            return /^[0-9]{11}$/.test(phone);
        }

        function validateProfileForm() {
            const isEmailValid = validateEmail(emailInput.value);
            const isPhoneValid = validatePhone(phoneInput.value);

            emailInput.classList.toggle('is-invalid', !isEmailValid);
            phoneInput.classList.toggle('is-invalid', !isPhoneValid);

            submitButton.disabled = !(isEmailValid && isPhoneValid);
        }

        emailInput.addEventListener('input', validateProfileForm);
        phoneInput.addEventListener('input', validateProfileForm);
    }
});

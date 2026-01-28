document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');

    // Initialize features


    if (loginForm) {
        setupRealTimeValidation(loginForm);
        loginForm.addEventListener('submit', function (e) {
            if (!validateForm(loginForm, 'login')) {
                e.preventDefault();
                shakeForm(loginForm);
            }
        });
    }

    if (signupForm) {
        setupRealTimeValidation(signupForm);
        signupForm.addEventListener('submit', function (e) {
            if (!validateForm(signupForm, 'signup')) {
                e.preventDefault();
                shakeForm(signupForm);
            }
        });
    }

    function setupRealTimeValidation(form) {
        const inputs = form.querySelectorAll('input:not([type="hidden"])');
        inputs.forEach(input => {
            // Validate on blur (when leaving the field)
            input.addEventListener('blur', () => {
                if (input.value.trim() !== '') {
                    validateField(input);
                }
            });

            // Clear error on input (when typing)
            input.addEventListener('input', () => {
                clearFieldError(input);
            });
        });
    }

    function validateForm(form, type) {
        let isValid = true;

        // Validate all fields
        const inputs = form.querySelectorAll('input:not([type="hidden"])');
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    function validateField(input) {
        const valid = checkFieldLogic(input);
        if (!valid.isValid) {
            showError(input, valid.message);
            return false;
        } else {
            clearFieldError(input);
            return true;
        }
    }

    function checkFieldLogic(input) {
        const id = input.id;
        const value = input.value.trim();
        const type = input.type;

        // Required Check
        if (value === '') {
            return { isValid: false, message: 'This field is required' };
        }

        // Email Validation
        if (type === 'email' || id === 'email') {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!re.test(value)) {
                return { isValid: false, message: 'Please enter a valid email address' };
            }
        }

        // Limit First/Last Name Validation to Signup Page
        if (id === 'first_name' || id === 'last_name') {
            if (value.length < 2) {
                return { isValid: false, message: 'Must be at least 2 characters' };
            }
        }

        // Password Validation
        if (id === 'password' || type === 'password') {
            if (value.length < 6) {
                return { isValid: false, message: 'Password must be at least 6 characters' };
            }
        }

        return { isValid: true };
    }

    function showError(input, message) {
        const group = input.closest('.auth-input-group');
        let error = group.querySelector('.error-message');

        if (!error) {
            error = document.createElement('div');
            error.className = 'error-message';
            // Icon could be added via CSS or here
            error.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> ${message}`;
            group.appendChild(error);
        } else {
            error.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> ${message}`;
        }

        input.classList.add('error');
    }

    function clearFieldError(input) {
        const group = input.closest('.auth-input-group');
        const error = group.querySelector('.error-message');
        if (error) error.remove();
        input.classList.remove('error');
    }

    function shakeForm(form) {
        form.classList.add('shake');
        form.addEventListener('animationend', () => {
            form.classList.remove('shake');
        }, { once: true });
    }

});

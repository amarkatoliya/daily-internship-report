document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            if (!validateLoginForm()) {
                e.preventDefault();
            }
        });
    }

    if (signupForm) {
        signupForm.addEventListener('submit', function (e) {
            if (!validateSignupForm()) {
                e.preventDefault();
            }
        });
    }

    function validateLoginForm() {
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        let isValid = true;

        clearErrors(loginForm);

        if (!validateEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }

        if (password.value.length < 6) {
            showError(password, 'Password must be at least 6 characters');
            isValid = false;
        }

        return isValid;
    }

    function validateSignupForm() {
        const firstName = document.getElementById('first_name');
        const lastName = document.getElementById('last_name');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        let isValid = true;

        clearErrors(signupForm);

        if (firstName.value.trim() === '') {
            showError(firstName, 'First name is required');
            isValid = false;
        }

        if (lastName.value.trim() === '') {
            showError(lastName, 'Last name is required');
            isValid = false;
        }

        if (!validateEmail(email.value)) {
            showError(email, 'Please enter a valid email address');
            isValid = false;
        }

        if (password.value.length < 6) {
            showError(password, 'Password must be at least 6 characters');
            isValid = false;
        }

        return isValid;
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function showError(input, message) {
        const group = input.closest('.auth-input-group');
        const error = document.createElement('div');
        error.className = 'error-message';
        error.style.color = 'var(--color-danger)';
        error.style.fontSize = '0.8rem';
        error.style.marginTop = '0.25rem';
        error.textContent = message;
        group.appendChild(error);
        input.style.borderColor = 'var(--color-danger)';
    }

    function clearErrors(form) {
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.auth-input').forEach(el => el.style.borderColor = '');
    }
});

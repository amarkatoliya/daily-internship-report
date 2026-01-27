// Current state storage
let currentShipping = 500;
let currentPaymentCharge = 0;

function updateShipping(cost, name) {
    currentShipping = cost;

    // Update active state on labels
    const shippingRadios = document.getElementsByName('shipping_method');
    shippingRadios.forEach(radio => {
        const card = radio.closest('.payment-method-card');
        if (radio.checked) {
            card.classList.add('active');
        } else {
            card.classList.remove('active');
        }
    });

    const shippingDisplay = document.querySelector('.order-summary__row span:nth-child(2)'); // This is brittle, better ID
    // Let's find by text content or add IDs to checkout.php
    updateSummaryTotals();
}

function togglePaymentForms() {
    const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethodInput) return;

    const paymentMethod = paymentMethodInput.value;
    const cardForm = document.getElementById('card-form');
    const upiForm = document.getElementById('upi-form');
    const codNotice = document.getElementById('cod-notice');

    // Update active state on labels
    document.querySelectorAll('input[name="payment_method"]').forEach(input => {
        input.closest('.payment-method-card').classList.remove('active');
    });
    paymentMethodInput.closest('.payment-method-card').classList.add('active');

    // Hide all forms
    if (cardForm) cardForm.style.display = 'none';
    if (upiForm) upiForm.style.display = 'none';
    if (codNotice) codNotice.style.display = 'none';

    // Remove animation classes
    if (cardForm) cardForm.classList.remove('animate-fade-in-up');
    if (upiForm) upiForm.classList.remove('animate-fade-in-up');
    if (codNotice) codNotice.classList.remove('animate-fade-in-up');

    // Remove required attributes
    if (cardForm) cardForm.querySelectorAll('input').forEach(input => input.required = false);
    if (upiForm) upiForm.querySelectorAll('input').forEach(input => input.required = false);

    // Show selected form and set required attributes
    if (paymentMethod === 'card' && cardForm) {
        cardForm.style.display = 'block';
        setTimeout(() => cardForm.classList.add('animate-fade-in-up'), 10);
        cardForm.querySelectorAll('input').forEach(input => input.required = true);
        currentPaymentCharge = 0;
    } else if (paymentMethod === 'upi' && upiForm) {
        upiForm.style.display = 'block';
        setTimeout(() => upiForm.classList.add('animate-fade-in-up'), 10);
        upiForm.querySelectorAll('input').forEach(input => input.required = true);
        currentPaymentCharge = 0;
    } else if (paymentMethod === 'cod' && codNotice) {
        codNotice.style.display = 'block';
        setTimeout(() => codNotice.classList.add('animate-fade-in-up'), 10);
        currentPaymentCharge = 50; // COD charge
    }

    updateSummaryTotals();
}

function updateSummaryTotals() {
    const checkoutContainer = document.getElementById('checkout-container');
    const subtotal = parseInt(checkoutContainer.dataset.subtotal);
    const totalQuantity = parseInt(checkoutContainer.dataset.totalQuantity || 1);

    // Elements
    const shippingRow = document.getElementById('summary-shipping');
    const extraChargesRow = document.getElementById('extra-charges-row');
    const extraChargesAmount = document.getElementById('extra-charges-amount');
    const finalTotalDisplay = document.getElementById('final-total');
    const placeOrderButton = document.getElementById('place-order-button');

    // Calculations
    const calculatedShipping = currentShipping * totalQuantity;
    const newTotal = subtotal + calculatedShipping + currentPaymentCharge;

    // Updates
    if (shippingRow) shippingRow.textContent = '₹' + calculatedShipping.toLocaleString();

    if (extraChargesRow && extraChargesAmount) {
        if (currentPaymentCharge > 0) {
            extraChargesRow.style.display = 'flex';
            extraChargesAmount.textContent = '₹' + currentPaymentCharge.toLocaleString();
        } else {
            extraChargesRow.style.display = 'none';
        }
    }

    if (finalTotalDisplay) finalTotalDisplay.textContent = '₹' + newTotal.toLocaleString();
    if (placeOrderButton) placeOrderButton.innerHTML = 'Place Order - ₹' + newTotal.toLocaleString();
}

// Format card number with spaces
function formatCardNumber(input) {
    let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formatted = '';
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
            formatted += ' ';
        }
        formatted += value[i];
    }
    input.value = formatted;
}

// Format expiry date
function formatExpiryDate(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    togglePaymentForms();

    // Address Validation
    const checkoutForm = document.querySelector('form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            if (!validateAddressFields()) {
                e.preventDefault();
            }
        });
    }

    function validateAddressFields() {
        const requiredFields = ['email', 'first_name', 'last_name', 'address', 'city', 'state', 'zip'];
        let isValid = true;

        requiredFields.forEach(fieldName => {
            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input) {
                clearFieldError(input);
                if (input.value.trim() === '') {
                    showFieldError(input, `${fieldName.replace('_', ' ')} is required`);
                    isValid = false;
                }
            }
        });

        // Also validate current payment method fields
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        if (paymentMethod === 'card') {
            const cardFields = ['cardholder_name', 'card_number', 'expiry_date', 'cvv'];
            cardFields.forEach(fieldName => {
                const input = document.querySelector(`[name="${fieldName}"]`);
                if (input && input.value.trim() === '') {
                    showFieldError(input, 'This field is required');
                    isValid = false;
                }
            });
        } else if (paymentMethod === 'upi') {
            const upiInput = document.querySelector('[name="upi_id"]');
            if (upiInput && upiInput.value.trim() === '') {
                showFieldError(upiInput, 'UPI ID is required');
                isValid = false;
            }
        }

        return isValid;
    }

    function showFieldError(input, message) {
        input.style.borderColor = 'var(--color-danger)';
        const group = input.closest('.form__group');
        if (group) {
            let error = group.querySelector('.field-error');
            if (!error) {
                error = document.createElement('div');
                error.className = 'field-error';
                error.style.color = 'var(--color-danger)';
                error.style.fontSize = '0.75rem';
                error.style.marginTop = '0.25rem';
                group.appendChild(error);
            }
            error.textContent = message;
        }
    }

    function clearFieldError(input) {
        input.style.borderColor = '';
        const group = input.closest('.form__group');
        if (group) {
            const error = group.querySelector('.field-error');
            if (error) error.remove();
        }
    }

    // Add event listeners for formatting
    const cardNumberInput = document.querySelector('input[name="card_number"]');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function () {
            formatCardNumber(this);
        });
    }

    const expiryDateInput = document.querySelector('input[name="expiry_date"]');
    if (expiryDateInput) {
        expiryDateInput.addEventListener('input', function () {
            formatExpiryDate(this);
        });
    }
});

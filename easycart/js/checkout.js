// Current state storage
let currentShipping = 500;
let currentPaymentCharge = 0;
let shippingUpdateTimeout = null; // For debouncing

/**
 * Calculate shipping cost based on method and subtotal
 * @param {string} method - Shipping method: 'standard', 'express', 'white_glove', 'freight'
 * @param {number} subtotal - Cart subtotal amount
 * @returns {number} Calculated shipping cost
 */
function calculateShipping(method, subtotal) {
    switch (method) {
        case 'standard':
            // Flat ₹40
            return 40;

        case 'express':
            // MIN of ₹80 or 10% of subtotal
            return Math.min(80, subtotal * 0.10);

        case 'white_glove':
            // MIN of ₹150 or 5% of subtotal
            return Math.min(150, subtotal * 0.05);

        case 'freight':
            // MAX of 3% of subtotal or ₹200
            return Math.max(subtotal * 0.03, 200);

        default:
            return 40; // Default to standard
    }
}

/**
 * Calculate 18% tax on (Subtotal + Shipping)
 * @param {number} subtotal - Cart subtotal amount
 * @param {number} shipping - Shipping cost
 * @returns {number} Calculated tax amount
 */
function calculateTax(subtotal, shipping) {
    const taxRate = 0.18; // 18%
    return (subtotal + shipping) * taxRate;
}

/**
 * Update shipping method and recalculate totals via AJAX
 * @param {string} method - Shipping method: 'standard', 'express', 'white_glove', 'freight'
 * @param {string} name - Display name of the shipping method
 */
function updateShipping(method, name) {
    const checkoutContainer = document.getElementById('checkout-container');
    const subtotal = parseFloat(checkoutContainer.dataset.subtotal);

    // Update active state on labels immediately for better UX
    const shippingRadios = document.getElementsByName('shipping_method');
    shippingRadios.forEach(radio => {
        const card = radio.closest('.payment-method-card');
        if (radio.checked) {
            card.classList.add('active');
        } else {
            card.classList.remove('active');
        }
    });

    // Debounce: Clear previous timeout
    if (shippingUpdateTimeout) {
        clearTimeout(shippingUpdateTimeout);
    }

    // Show loading state
    const shippingRow = document.getElementById('summary-shipping');
    const taxRow = document.getElementById('summary-tax');
    const finalTotalDisplay = document.getElementById('final-total');

    shippingRow.innerHTML = '<span style="opacity: 0.5;">Calculating...</span>';
    taxRow.innerHTML = '<span style="opacity: 0.5;">Calculating...</span>';
    finalTotalDisplay.innerHTML = '<span style="opacity: 0.5;">Calculating...</span>';

    // Debounce: Wait 300ms after last click
    shippingUpdateTimeout = setTimeout(() => {
        // Make AJAX request to calculate shipping
        fetch('update_ship_ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                shipping_method: method,
                subtotal: subtotal
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update current shipping cost
                    currentShipping = data.shipping;

                    // Update shipping options based on allowed methods
                    updateShippingOptions(data);

                    // Update the summary display
                    updateSummaryTotals();

                    // Show notification if method was changed due to restrictions
                    if (data.method_changed) {
                        let reason = '';
                        if (data.has_freight_item) {
                            reason = 'Your cart contains Freight items.';
                        } else if (data.subtotal_threshold_met) {
                            reason = 'Your order total is ₹300 or more.';
                        }
                        showNotification(`${reason} Only premium shipping options are available.`, 'info');
                    }
                } else {
                    console.error('Shipping calculation failed:', data.error);
                    showNotification('Unable to calculate shipping. Using estimated cost.', 'warning');
                    // Fallback to local calculation
                    currentShipping = calculateShipping(method, subtotal);
                    updateSummaryTotals();
                }
            })
            .catch(error => {
                console.error('Error calculating shipping:', error);
                showNotification('Network error. Using estimated shipping cost.', 'error');
                // Fallback to local calculation
                currentShipping = calculateShipping(method, subtotal);
                updateSummaryTotals();
            });
    }, 300);
}

/**
 * Update shipping options UI based on allowed methods
 * @param {Object} data - Response data from AJAX containing allowed_methods
 */
function updateShippingOptions(data) {
    const allowedMethods = data.allowed_methods || [];
    const currentMethod = data.current_method;

    const shippingRadios = document.getElementsByName('shipping_method');

    shippingRadios.forEach(radio => {
        const methodValue = radio.value;
        const card = radio.closest('.payment-method-card');
        const isAllowed = allowedMethods.includes(methodValue);

        if (isAllowed) {
            // Enable this option
            radio.disabled = false;
            card.classList.remove('disabled');
            card.style.opacity = '1';
            card.style.cursor = 'pointer';
            card.style.pointerEvents = 'auto';

            // Check if this is the current method
            if (methodValue === currentMethod) {
                radio.checked = true;
                card.classList.add('active');
            }
        } else {
            // Disable this option
            radio.disabled = true;
            radio.checked = false;
            card.classList.remove('active');
            card.classList.add('disabled');
            card.style.opacity = '0.5';
            card.style.cursor = 'not-allowed';
            card.style.pointerEvents = 'none';
        }
    });
}

/**
 * Initialize shipping restrictions on page load
 * @param {number} subtotal - Cart subtotal amount
 */
function initializeShippingRestrictions(subtotal) {
    // Get the currently selected shipping method
    const selectedRadio = document.querySelector('input[name="shipping_method"]:checked');
    const selectedMethod = selectedRadio ? selectedRadio.value : 'standard';

    // Trigger shipping update to get restrictions
    fetch('update_ship_ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            shipping_method: selectedMethod,
            subtotal: subtotal
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentShipping = data.shipping;
                updateShippingOptions(data);
                updateSummaryTotals();
            }
        })
        .catch(error => {
            console.error('Error initializing shipping restrictions:', error);
        });
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
    const subtotal = parseFloat(checkoutContainer.dataset.subtotal);

    // Elements
    const shippingRow = document.getElementById('summary-shipping');
    const taxRow = document.getElementById('summary-tax');
    const extraChargesRow = document.getElementById('extra-charges-row');
    const extraChargesAmount = document.getElementById('extra-charges-amount');
    const finalTotalDisplay = document.getElementById('final-total');
    const placeOrderButton = document.getElementById('place-order-button');

    // Calculations - shipping is per order, not per item
    const calculatedShipping = currentShipping;
    const calculatedTax = calculateTax(subtotal, calculatedShipping);
    const newTotal = subtotal + calculatedShipping + calculatedTax + currentPaymentCharge;

    // Updates
    if (shippingRow) shippingRow.textContent = '₹' + calculatedShipping.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    if (taxRow) taxRow.textContent = '₹' + calculatedTax.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    if (extraChargesRow && extraChargesAmount) {
        if (currentPaymentCharge > 0) {
            extraChargesRow.style.display = 'flex';
            extraChargesAmount.textContent = '₹' + currentPaymentCharge.toLocaleString();
        } else {
            extraChargesRow.style.display = 'none';
        }
    }

    if (finalTotalDisplay) finalTotalDisplay.textContent = '₹' + newTotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (placeOrderButton) placeOrderButton.innerHTML = 'Place Order - ₹' + newTotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
    // Initialize shipping cost with default method (standard)
    const checkoutContainer = document.getElementById('checkout-container');
    if (checkoutContainer) {
        const subtotal = parseInt(checkoutContainer.dataset.subtotal);
        currentShipping = calculateShipping('standard', subtotal);

        // Check shipping restrictions on page load
        initializeShippingRestrictions(subtotal);
    }

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

/**
 * Show notification message to user
 * @param {string} message - Message to display
 * @param {string} type - Notification type: 'success', 'error', 'warning', 'info'
 */
function showNotification(message, type = 'info') {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.checkout-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `checkout-notification checkout-notification--${type}`;
    notification.innerHTML = `
        <div class="checkout-notification__content">
            <span class="checkout-notification__icon">${getNotificationIcon(type)}</span>
            <span class="checkout-notification__message">${message}</span>
        </div>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto-remove after 4 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

function getNotificationIcon(type) {
    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
    };
    return icons[type] || icons.info;
}

function getNotificationColor(type) {
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    return colors[type] || colors.info;
}


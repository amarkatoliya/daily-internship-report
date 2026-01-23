function togglePaymentForms() {
    const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethodInput) return;

    const paymentMethod = paymentMethodInput.value;
    const cardForm = document.getElementById('card-form');
    const upiForm = document.getElementById('upi-form');
    const codNotice = document.getElementById('cod-notice');
    const extraChargesRow = document.getElementById('extra-charges-row');
    const extraChargesAmount = document.getElementById('extra-charges-amount');
    const finalTotal = document.getElementById('final-total');
    const placeOrderButton = document.getElementById('place-order-button');

    // Get values from data attributes
    const checkoutContainer = document.getElementById('checkout-container');
    const subtotal = parseInt(checkoutContainer.dataset.subtotal);
    const shipping = parseInt(checkoutContainer.dataset.shipping);

    // Update active state on labels
    document.querySelectorAll('.payment-method-card').forEach(label => {
        label.classList.remove('active');
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
        updateExtraCharges(0);
    } else if (paymentMethod === 'upi' && upiForm) {
        upiForm.style.display = 'block';
        setTimeout(() => upiForm.classList.add('animate-fade-in-up'), 10);
        upiForm.querySelectorAll('input').forEach(input => input.required = true);
        updateExtraCharges(0);
    } else if (paymentMethod === 'cod' && codNotice) {
        codNotice.style.display = 'block';
        setTimeout(() => codNotice.classList.add('animate-fade-in-up'), 10);
        updateExtraCharges(50); // COD charge
    }

    function updateExtraCharges(charges) {
        const newTotal = subtotal + shipping + charges;

        if (extraChargesRow && extraChargesAmount) {
            if (charges > 0) {
                extraChargesRow.style.display = 'flex';
                extraChargesRow.classList.add('animate-fade-in-up');
                extraChargesAmount.textContent = '₹' + charges.toLocaleString();
            } else {
                extraChargesRow.style.display = 'none';
                extraChargesRow.classList.remove('animate-fade-in-up');
            }
        }

        if (finalTotal) finalTotal.textContent = '₹' + newTotal.toLocaleString();
        if (placeOrderButton) placeOrderButton.innerHTML = 'Place Order - ₹' + newTotal.toLocaleString();
    }
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

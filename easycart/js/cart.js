// Cart quantity validation
function validateQuantity(input) {
    const value = parseInt(input.value);
    if (isNaN(value) || value < 1) {
        input.value = 1;
    } else if (value > 99) {
        input.value = 99;
    }
}

// Add event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    const quantityInputs = document.querySelectorAll('input[name="quantity"]');
    quantityInputs.forEach(input => {
        input.addEventListener('input', function () {
            validateQuantity(this);
        });
        input.addEventListener('blur', function () {
            validateQuantity(this);
        });
    });
});

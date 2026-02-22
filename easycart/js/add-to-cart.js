document.addEventListener('DOMContentLoaded', function () {
    // Select all "Add to Cart" forms
    const addToCartForms = document.querySelectorAll('form[action="add_to_cart"]');

    addToCartForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            const productId = form.querySelector('input[name="product_id"]').value;

            // Visual feedback - Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> adding...';

            const formData = new FormData(form);

            fetch('add_to_cart', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart badge
                        updateHeaderCartBadge(data.cartCount);

                        // Success feedback
                        showToast(data.message);
                        submitBtn.classList.add('btn--success');
                        submitBtn.innerHTML = '✓ Added';

                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalContent;
                            submitBtn.classList.remove('btn--success');
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                    showToast('Failed to add product', 'error');
                });
        });
    });

    function updateHeaderCartBadge(count) {
        let badge = document.querySelector('.cart-badge');
        const cartLink = document.querySelector('.nav__link--cart') || document.querySelector('a[href="cart"]');

        if (!badge && cartLink) {
            badge = document.createElement('span');
            badge.className = 'cart-badge';
            cartLink.style.position = 'relative';
            cartLink.appendChild(badge);
        }

        if (badge) {
            badge.textContent = count;
            badge.classList.add('badge-pop');
            setTimeout(() => badge.classList.remove('badge-pop'), 300);
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        // Simple animation
        setTimeout(() => toast.classList.add('toast--visible'), 10);

        setTimeout(() => {
            toast.classList.remove('toast--visible');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});

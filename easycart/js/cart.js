// Cart quantity validation (deprecated for AJAX)
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
    const cartTableBody = document.getElementById('cart-table-body');
    if (!cartTableBody) return;

    // Handle quantity and remove via delegation
    cartTableBody.addEventListener('click', function (e) {
        let target = e.target;

        // Check if button or its child (like SVG) was clicked
        const qtyBtn = target.closest('button[name="update_quantity"]');
        const removeBtn = target.closest('.remove-item-btn');

        if (qtyBtn) {
            e.preventDefault();
            const form = qtyBtn.closest('form');
            const productId = form.querySelector('[name="product_id"]').value;
            const delta = qtyBtn.title === 'Increase quantity' ? 1 : -1;
            updateCartItem(productId, delta);
        }

        if (removeBtn) {
            e.preventDefault();
            const form = removeBtn.closest('form');
            const productId = form.querySelector('[name="product_id"]').value;
            if (confirm('Remove this item from cart?')) {
                removeCartItem(productId);
            }
        }
    });

    async function updateCartItem(productId, delta) {
        const formData = new FormData();
        formData.append('action', 'update');
        formData.append('product_id', productId);
        formData.append('delta', delta);

        try {
            const response = await fetch('update_cart_ajax.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                if (data.isRemoved) {
                    handleRowRemoval(productId, data);
                } else {
                    updateUI(productId, data);
                }
            }
        } catch (error) {
            console.error('Error updating cart:', error);
        }
    }

    async function removeCartItem(productId) {
        const formData = new FormData();
        formData.append('action', 'remove');
        formData.append('product_id', productId);

        try {
            const response = await fetch('update_cart_ajax.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                handleRowRemoval(productId, data);
            }
        } catch (error) {
            console.error('Error removing item:', error);
        }
    }

    function handleRowRemoval(productId, data) {
        const row = document.getElementById(`product-row-${productId}`);
        if (!row) return;

        row.style.transition = 'all 0.3s ease';
        row.style.opacity = '0';
        row.style.transform = 'translateX(20px)';
        setTimeout(() => {
            row.remove();
            updateTotals(data);
            updateBadge(data.cartCount);
            if (data.cartCount === 0) {
                location.reload(); // Refresh to show empty cart state
            }
        }, 300);
    }

    function updateUI(productId, data) {
        const row = document.getElementById(`product-row-${productId}`);
        const quantityInput = row.querySelector('[name="quantity"]');
        const itemTotal = document.getElementById(`item-total-${productId}`);
        const decrementBtn = row.querySelector('button[title="Decrease quantity"]');

        quantityInput.value = data.newQuantity;
        itemTotal.textContent = data.itemTotal;

        // Handle decrement button state
        if (decrementBtn) {
            if (data.newQuantity <= 1) {
                decrementBtn.disabled = true;
                decrementBtn.style.opacity = '0.5';
                decrementBtn.style.cursor = 'not-allowed';
            } else {
                decrementBtn.disabled = false;
                decrementBtn.style.opacity = '1';
                decrementBtn.style.cursor = 'pointer';
            }
        }

        updateTotals(data);
        updateBadge(data.cartCount);
    }

    function updateTotals(data) {
        const subtotalEl = document.getElementById('cart-subtotal');
        const shippingEl = document.getElementById('cart-shipping-display');
        const totalEl = document.getElementById('cart-total');
        const totalQtyEl = document.getElementById('total-quantity-display');

        if (subtotalEl) subtotalEl.textContent = data.subtotal;
        if (shippingEl) shippingEl.textContent = data.shipping;
        if (totalEl) totalEl.textContent = data.total;
        if (totalQtyEl) totalQtyEl.textContent = data.totalQuantity;
    }

    function updateBadge(count) {
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
            } else {
                badge.remove();
            }
        }
    }
});

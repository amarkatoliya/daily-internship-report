/**
 * Product Gallery Functionality
 * Handles image switching on the product detail page
 */

/**
 * Switches the main product image and updates active thumbnail state
 * @param {HTMLElement} element - The clicked thumbnail element
 * @param {string} imageUrl - The URL of the image to switch to
 */
function switchProductImage(element, imageUrl) {
    const mainImage = document.getElementById('mainProductImage');
    if (!mainImage) return;

    // Update main image with a slight fade effect
    mainImage.style.opacity = '0';

    setTimeout(() => {
        mainImage.src = imageUrl;
        mainImage.style.opacity = '1';
    }, 150);

    // Update active state of thumbnails
    const thumbnails = document.querySelectorAll('.product-gallery__thumb');
    thumbnails.forEach(thumb => thumb.classList.remove('active'));
    element.classList.add('active');
}

// Add CSS transitions if they don't exist
document.addEventListener('DOMContentLoaded', () => {
    const mainImage = document.getElementById('mainProductImage');
    if (mainImage) {
        mainImage.style.transition = 'opacity 0.2s ease-in-out';
    }
});

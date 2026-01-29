<?php
/**
 * Shared Shipping Logic
 * Single source of truth for shipping calculations
 * Used by: checkout.php, update_ship_ajax.php
 */

/**
 * Calculate shipping cost based on method and subtotal
 * @param string $method Shipping method: 'standard', 'express', 'white_glove', 'freight'
 * @param float $subtotal Cart subtotal amount
 * @return float Calculated shipping cost
 */
function calculateShippingCost($method, $subtotal)
{
    switch ($method) {
        case 'standard':
            // Flat ₹40
            return 40;

        case 'express':
            // MIN of ₹80 or 10% of subtotal
            return min(80, $subtotal * 0.10);

        case 'white_glove':
            // MIN of ₹150 or 5% of subtotal
            return min(150, $subtotal * 0.05);

        case 'freight':
            // MAX of 3% of subtotal or ₹200
            return max($subtotal * 0.03, 200);

        default:
            return 40; // Default to standard
    }
}

/**
 * Calculate tax on subtotal + shipping
 * @param float $subtotal Cart subtotal amount
 * @param float $shipping Shipping cost
 * @return float Calculated tax amount (18%)
 */
function calculateTax($subtotal, $shipping)
{
    $tax_rate = 0.18; // 18%
    return ($subtotal + $shipping) * $tax_rate;
}

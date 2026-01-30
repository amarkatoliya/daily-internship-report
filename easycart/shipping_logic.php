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

/**
 * Determine allowed shipping methods based on cart contents and subtotal
 * Business Rules:
 * - IF cart has Freight item OR subtotal >= ₹300: Only white_glove and freight allowed
 * - ELSE: Only standard and express allowed
 * 
 * @param array $cartItems - Array of cart items with product details
 * @param float $subtotal - Cart subtotal amount
 * @return array ['allowed_methods' => array, 'default_method' => string, 'has_freight_item' => bool, 'subtotal_threshold_met' => bool]
 */
function getAllowedShippingMethods($cartItems, $subtotal)
{
    // Check if any cart item has Freight shipping type
    $hasFreightItem = false;
    foreach ($cartItems as $item) {
        if (
            isset($item['product']['shipping_type']) &&
            strtolower($item['product']['shipping_type']) === 'freight'
        ) {
            $hasFreightItem = true;
            break;
        }
    }

    // Apply shipping rules
    if ($hasFreightItem || $subtotal >= 300) {
        // High-value or Freight items: Only premium shipping
        return [
            'allowed_methods' => ['white_glove', 'freight'],
            'default_method' => 'freight',
            'has_freight_item' => $hasFreightItem,
            'subtotal_threshold_met' => $subtotal >= 300
        ];
    } else {
        // Standard items under ₹300: Only standard shipping
        return [
            'allowed_methods' => ['standard', 'express'],
            'default_method' => 'standard',
            'has_freight_item' => false,
            'subtotal_threshold_met' => false
        ];
    }
}

/**
 * Validate if a shipping method is allowed for the given cart
 * @param string $method - Shipping method to validate
 * @param array $cartItems - Array of cart items with product details
 * @param float $subtotal - Cart subtotal amount
 * @return bool True if method is allowed, false otherwise
 */
function isShippingMethodAllowed($method, $cartItems, $subtotal)
{
    $allowedData = getAllowedShippingMethods($cartItems, $subtotal);
    return in_array($method, $allowedData['allowed_methods']);
}

<?php
session_start();
header('Content-Type: application/json');

// Include data layer and shared shipping logic
require_once 'data.php';
require_once 'shipping_logic.php';

// Check if this is an AJAX request
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$shippingMethod = $input['shipping_method'] ?? 'standard';
$subtotal = floatval($input['subtotal'] ?? 0);

// Persist selection to session
$_SESSION['selected_shipping'] = $shippingMethod;

// Validate subtotal
if ($subtotal <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid subtotal amount']);
    exit;
}

// Calculate shipping using shared logic
$shipping = calculateShippingCost($shippingMethod, $subtotal);

// Calculate tax using shared logic
$tax = calculateTax($subtotal, $shipping);

// Get extra charges (if COD is selected, it's handled separately in payment method)
$extraCharges = 0;

// Calculate total
$total = $subtotal + $shipping + $tax + $extraCharges;

// Return response
echo json_encode([
    'success' => true,
    'shipping' => $shipping,
    'tax' => $tax,
    'total' => $total,
    'formatted' => [
        'shipping' => formatPrice($shipping),
        'tax' => formatPrice($tax),
        'total' => formatPrice($total)
    ]
]);

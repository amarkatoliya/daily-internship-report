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

// Get cart items for analysis
$cartItems = [];
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cartItem) {
        $product = getProductById($cartItem['id']);
        if ($product) {
            $cartItems[] = [
                'product' => $product,
                'quantity' => $cartItem['quantity']
            ];
        }
    }
}

// Get allowed shipping methods based on cart contents and subtotal
$allowedData = getAllowedShippingMethods($cartItems, $subtotal);

// Check if current shipping method is allowed
$isCurrentMethodAllowed = in_array($shippingMethod, $allowedData['allowed_methods']);

// If current method is not allowed, switch to default
if (!$isCurrentMethodAllowed) {
    $shippingMethod = $allowedData['default_method'];
    $_SESSION['selected_shipping'] = $shippingMethod;
    $shipping = calculateShippingCost($shippingMethod, $subtotal);
    $tax = calculateTax($subtotal, $shipping);
}

// Get extra charges (if COD is selected, it's handled separately in payment method)
$extraCharges = 0;

// Calculate total
$total = $subtotal + $shipping + $tax + $extraCharges;

// Return response with allowed methods
echo json_encode([
    'success' => true,
    'shipping' => $shipping,
    'tax' => $tax,
    'total' => $total,
    'allowed_methods' => $allowedData['allowed_methods'],
    'default_method' => $allowedData['default_method'],
    'current_method' => $shippingMethod,
    'has_freight_item' => $allowedData['has_freight_item'],
    'subtotal_threshold_met' => $allowedData['subtotal_threshold_met'],
    'method_changed' => !$isCurrentMethodAllowed,
    'formatted' => [
        'shipping' => formatPrice($shipping),
        'tax' => formatPrice($tax),
        'total' => formatPrice($total)
    ]
]);

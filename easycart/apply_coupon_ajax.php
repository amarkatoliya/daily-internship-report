<?php
// Start session to access cart data and store coupon
session_start();

// Set JSON header
header('Content-Type: application/json');

// Define valid coupons with their discount percentages
$validCoupons = [
    'SAVE5' => 5,
    'SAVE10' => 10,
    'SAVE15' => 15,
    'SAVE20' => 20
];

// Get the coupon code from POST request
$couponCode = isset($_POST['coupon_code']) ? trim($_POST['coupon_code']) : '';

// Get subtotal from POST request
$subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;

// Validate that coupon code is provided
if (empty($couponCode)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a coupon code'
    ]);
    exit;
}

// Validate that subtotal is provided
if ($subtotal <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid cart subtotal'
    ]);
    exit;
}

// Check if coupon is valid (case-sensitive)
if (!array_key_exists($couponCode, $validCoupons)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid coupon code. Please try again.'
    ]);
    exit;
}

// Get discount percentage
$discountPercentage = $validCoupons[$couponCode];

// Calculate discount amount (percentage of subtotal)
$discountAmount = ($subtotal * $discountPercentage) / 100;

// Store coupon information in session
$_SESSION['applied_coupon'] = [
    'code' => $couponCode,
    'percentage' => $discountPercentage,
    'amount' => $discountAmount
];

// Return success response
echo json_encode([
    'success' => true,
    'coupon_code' => $couponCode,
    'discount_percentage' => $discountPercentage,
    'discount_amount' => $discountAmount,
    'message' => "Coupon '{$couponCode}' applied successfully! You saved {$discountPercentage}%"
]);

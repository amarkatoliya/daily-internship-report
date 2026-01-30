<?php
// Start session to clear coupon
session_start();

// Set JSON header
header('Content-Type: application/json');

// Clear coupon from session
if (isset($_SESSION['applied_coupon'])) {
    unset($_SESSION['applied_coupon']);
}

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Coupon removed successfully'
]);

<?php
session_start();
require_once 'Database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Both email and password are required.";
        header("Location: login");
        exit();
    }

    // Query database for user by email
    $stmt = $pdo->prepare("SELECT entity_id, firstname, lastname, email, password_hash FROM customer_entity WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Verify password
    if ($user && password_verify($password, $user['password_hash'])) {
        // Remove password hash from session data for security
        unset($user['password_hash']);

        // Map DB column names to session keys (for backward compatibility)
        $sessionUser = [
            'id' => $user['entity_id'],
            'first_name' => $user['firstname'],
            'last_name' => $user['lastname'],
            'email' => $user['email']
        ];

        // Success: Store user in session
        $_SESSION['user'] = $sessionUser;
        $_SESSION['success'] = "Welcome back, " . $sessionUser['first_name'] . "!";

        // Redirect to homepage
        header("Location: index");
        exit();
    } else {
        // Failure: Show error
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login");
        exit();
    }
} else {
    header("Location: login");
    exit();
}

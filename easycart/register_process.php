<?php
session_start();
require_once 'Database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: signup");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: signup");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters.";
        header("Location: signup");
        exit();
    }

    // Check if email already exists in database
    $stmt = $pdo->prepare("SELECT COUNT(*) AS email_exists FROM customer_entity WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetch();

    if ($result['email_exists'] > 0) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: signup");
        exit();
    }

    // Hash password and insert user into database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO customer_entity (firstname, lastname, email, password_hash) 
        VALUES (:firstname, :lastname, :email, :password_hash)
    ");

    $success = $stmt->execute([
        'firstname' => $first_name,
        'lastname' => $last_name,
        'email' => $email,
        'password_hash' => $hashedPassword
    ]);

    if ($success) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login");
        exit();
    } else {
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: signup");
        exit();
    }
} else {
    header("Location: signup");
    exit();
}

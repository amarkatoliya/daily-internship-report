<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: signup.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: signup.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters.";
        header("Location: signup.php");
        exit();
    }

    $usersFile = 'data/users.json';
    $users = [];

    // Read existing users
    if (file_exists($usersFile)) {
        $jsonContent = file_get_contents($usersFile);
        $users = json_decode($jsonContent, true) ?: [];
    }

    // Check if email already exists
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $_SESSION['error'] = "Email already registered.";
            header("Location: signup.php");
            exit();
        }
    }

    // Hash password and add user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $newUser = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'password' => $hashedPassword,
        'created_at' => date('Y-m-d H:i:s')
    ];

    $users[] = $newUser;

    // Save back to JSON
    if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT))) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "An error occurred. Please try again.";
        header("Location: signup.php");
        exit();
    }
} else {
    header("Location: signup.php");
    exit();
}

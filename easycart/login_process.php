<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Both email and password are required.";
        header("Location: login");
        exit();
    }

    $usersFile = 'data/users.json';
    $users = [];

    // Read existing users
    if (file_exists($usersFile)) {
        $jsonContent = file_get_contents($usersFile);
        $users = json_decode($jsonContent, true) ?: [];
    }

    // Find user and verify password
    $foundUser = null;
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            if (password_verify($password, $user['password'])) {
                $foundUser = $user;
                break;
            }
        }
    }

    if ($foundUser) {
        // Remove password from session data for security
        unset($foundUser['password']);

        // Success: Store user in session
        $_SESSION['user'] = $foundUser;
        $_SESSION['success'] = "Welcome back, " . $foundUser['first_name'] . "!";

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

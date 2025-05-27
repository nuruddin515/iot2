<?php
session_start();
require 'config.php';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Please fill in all fields.");
        exit();
    }

    try {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Password correct — set session and redirect
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $email;

            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid credentials
            header("Location: login.php?error=Invalid email or password.");
            exit();
        }

    } catch (PDOException $e) {
        header("Location: login.php?error=Login failed: " . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If not a POST request, redirect to login
    header("Location: login.php");
    exit();
}
?>

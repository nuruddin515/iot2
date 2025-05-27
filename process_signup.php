<?php
session_start();
require 'config.php';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        header("Location: signup.php?error=Please fill in all fields.");
        exit();
    }

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            header("Location: signup.php?error=Email already registered.");
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $stmt->execute([
            'email' => $email,
            'password' => $hashedPassword
        ]);

        // Redirect with success message
        header("Location: signup.php?success=1");
        exit();

    } catch (PDOException $e) {
        header("Location: signup.php?error=Registration failed: " . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If not a POST request, redirect to signup
    header("Location: signup.php");
    exit();
}
?>

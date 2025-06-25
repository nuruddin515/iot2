<?php
// Use config to connect to DB
require 'config.php';

// Set header to prevent caching
header('Content-Type: text/plain');
header('Cache-Control: no-cache, must-revalidate');

// Get token from POST request
$token = trim($_POST['token'] ?? '');

// Return early if token is empty
if (empty($token)) {
    echo "invalid";
    exit;
}

try {
    // Prepare and execute query
    $stmt = $pdo->prepare("SELECT id FROM token WHERE token = :token AND remarks = 'unused' LIMIT 1");
    $stmt->execute(['token' => $token]);

    // Check if token exists
    if ($stmt->fetch()) {
        echo "valid";
    } else {
        echo "invalid";
    }

} catch (PDOException $e) {
    // Log error in production, don't show to user
    error_log("Token check failed: " . $e->getMessage());
    echo "invalid";
}
?>

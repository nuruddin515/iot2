<?php
require 'config.php';

try {
    // Simple query to test the connection
    $stmt = $pdo->query("SELECT version()");

    $row = $stmt->fetch();
    echo "<h2>✅ Database connection successful!</h2>";
    echo "<p>PostgreSQL Version: " . $row['version'] . "</p>";
} catch (PDOException $e) {
    echo "<h2>❌ Database connection failed:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('HTTP/1.1 403 Forbidden');
    echo "<h1>403 Forbidden</h1>";
    echo "<p>Access denied.</p>";
    echo "<p>Redirecting you back to the main site...</p>";
    echo '<script>setTimeout(function(){ window.location.href = "https://omnischool.rf.gd"; }, 3000);</script>';
    exit;
}

// Database credentials
$host = 'sql109.infinityfree.com';
$db   = 'if0_38817814_omnischool';
$user = 'if0_38817814';
$pass = 'OMNISoftware25';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

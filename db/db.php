<?php
// db.php
$host = 'sql109.infinityfree.com';
$user = 'if0_38817814';
$password = 'OMNISoftware25';
$dbname = 'if0_38817814_omnischool';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

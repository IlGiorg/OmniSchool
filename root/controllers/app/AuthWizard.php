<?php
session_start();
require_once '../controllers/includes/db.php'; // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /logon.php");
    exit();
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    die("Missing credentials");
}

// Fetch user
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $password === $user['password']) {

    // Store session
    $_SESSION['userid'] = $user['ID'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // Role-based redirect
    switch ($user['role']) {
        case 'teacher':
            header("Location: /teacher/dashboard.php");
            break;

        case 'student':
            header("Location: /student/dashboard.php");
            break;

        case 'admin':
            header("Location: /admin/dashboard.php");
            break;

        case 'parent':
            header("Location: /parent/dashboard.php");
            break;

        default:
            session_destroy();
            die("Unknown role");
    }

    exit();

} else {
    // Invalid login
    echo "<script>
        alert('Invalid username or password');
        window.location.href = 'logon.php';
    </script>";
}
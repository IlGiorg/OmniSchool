<?php
session_start();
header('Content-Type: application/json');

// Handle login POST (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    require_once '../db/db.php';

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $dbpassword,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $stmt = $pdo->prepare(
            "SELECT * FROM students WHERE Username = ? AND Password = ?"
        );
        $stmt->execute([$username, $password]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            $_SESSION['username'] = $student['Username'];
            echo json_encode(["success" => true]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Invalid username or password."
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Database error."
        ]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ebd78f, #ff7dc4);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #1976d2;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #1976d2;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #125ea2;
        }

        #errorMessage {
            text-align: center;
            color: red;
            margin-top: 12px;
            display: none;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h1>Student Login</h1>

    <form id="studentLoginForm">
        <input type="text" id="username" placeholder="Username" required>
        <input type="password" id="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div id="errorMessage"></div>
</div>

<script>
document.getElementById("studentLoginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const errorBox = document.getElementById("errorMessage");
    errorBox.style.display = "none";

    fetch("studentlogin.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "same-origin",
        body: JSON.stringify({
            username: document.getElementById("username").value,
            password: document.getElementById("password").value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = "students/student_home.html";
        } else {
            errorBox.textContent = data.message || "Login failed.";
            errorBox.style.display = "block";
        }
    })
    .catch(() => {
        errorBox.textContent = "Something went wrong. Please try again.";
        errorBox.style.display = "block";
    });
});
</script>

</body>
</html>

<?php
session_start();

// If already logged in, redirect
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'teacher':
            header("Location: /teacher/dashboard.php");
            exit();
        case 'student':
            header("Location: /student/dashboard.php");
            exit();
        case 'admin':
            header("Location: /admin/dashboard.php");
            exit();
        case 'parent':
            header("Location: /parent/dashboard.php");
            exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../controllers/app/AuthWand.php';
    login(); // call your controller function
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Omnischool Login</title>
    <link rel="icon" href="/assets/img/brand/omnischool/favicon.ico">
    <style>
        body {
            font-family: Arial;
            background: #f5f7fb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 300px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        img.logo {
            width: 120px;
            margin-bottom: 15px;
        }
        .roles img {
            width: 40px;
            margin: 5px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <img src="/assets/img/brand/omnischool/omnischool.png" class="logo">

    <h2>Login</h2>
    <p style="font-size: 12px; color: gray;">
        Now all in one place. Enter your credentials to login
    </p>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <div class="roles">
        <img src="/assets/img/brand/omnischool/omnistudent.png">
        <img src="/assets/img/brand/omnischool/omniteacher.png">
        <img src="/assets/img/brand/omnischool/omniparent.png">
    </div>
</div>

</body>
</html>
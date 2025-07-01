<?php
session_start();

if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>401 Unauthorized</title>
        <meta http-equiv='refresh' content='0;url=/technical/error/401-6.html'>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8d7da;
                color: #721c24;
                text-align: center;
                padding-top: 100px;
            }
        </style>
    </head>
    <body>
        <h1>401 Unauthorized</h1>
        <p>You are not authorized to view this page. Redirecting...</p>
    </body>
    </html>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Panel</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .dashboard {
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-weight: 600;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        button {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fafafa;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        button:hover {
            background-color: #e6e6e6;
        }

        .logout {
            background-color: #e74c3c;
            color: white;
            border: none;
        }

        .logout:hover {
            background-color: #c0392b;
        }

        .admin-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .admin-link a {
            color: #555;
            text-decoration: none;
        }

        .admin-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="dashboard">
    <h1>Admin Panel</h1>

    <div class="button-group">
    <button onclick="openWindow('students/createstudent.html')">Create Student</button>
    <button onclick="openWindow('students/editstudent.php')">Edit Student</button>
    <button onclick="openWindow('grades/index.html')">Create Teacher</button>
    <button onclick="openWindow('homework/homework.html')">Edit Teacher</button>
    <button onclick="openWindow('sqlexecute.php')">SQL Executor</button>
    <br>
    <button class="logout" onclick="window.location.href='/logout.php'">Logout</button>
</div>

<script>
function openWindow(url) {
    window.open(url, '_blank', 'width=1000,height=800,toolbar=no,menubar=no,location=no,status=no,scrollbars=yes,resizable=yes');
}
</script>



</div>
</body>
</html>

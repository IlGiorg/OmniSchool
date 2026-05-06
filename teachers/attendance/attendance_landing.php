<?php
session_start();

if (!isset($_SESSION['username'])) {
     header("Location: /");
     exit;
 }

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db/db.php';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Fetch all classes
    $stmt = $pdo->query("SELECT ClassID, Year, Form FROM classes ORDER BY Year, Form");
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Landing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 40px;
        }
        h2 {
            color: #0056b3;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        label, select, button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            font-size: 16px;
        }
        select, button {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #0056b3;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #003f8a;
        }
    </style>
</head>
<body>

<h2>Select Class for Attendance</h2>

<form method="GET" action="take_attendance.php">
    <label for="class">Class:</label>
    <select name="class" id="class" required>
        <option value="" disabled selected>Select a class</option>
        <?php foreach ($classes as $row): 
            $classID = htmlspecialchars($row['ClassID']);
            $year = htmlspecialchars($row['Year']);
            $form = htmlspecialchars($row['Form']);
        ?>
            <option value="<?= $classID ?>">Year <?= $year ?> <?= $form ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Take Attendance</button>
</form>

</body>
</html>

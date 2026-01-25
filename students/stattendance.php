<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo "<p>Unauthorized. Please log in.</p>";
    exit;
}

$attendanceRecords = [];
$studentName = "";

try {
    $pdo = new PDO("mysql:host=127.0.0.1:3307;dbname=omnischool;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $username = $_SESSION['username'];

    // Get student info
    $stmt = $pdo->prepare("SELECT ID, First_name FROM students WHERE username = ?");
    $stmt->execute([$username]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo "<p>Student not found.</p>";
        exit;
    }

    $studentName = $student['First_name'];

    // Get attendance records
    $stmt = $pdo->prepare("
        SELECT a.Date, a.Status, t.username AS Marked_By
        FROM attendance a
        JOIN teachers t ON a.Recorded_By = t.TeachID
        WHERE a.StudentID = ?
        ORDER BY a.Date DESC
    ");
    $stmt->execute([$student['ID']]);
    $attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<pre>" . $e->getMessage() . "</pre>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Attendance</title>
    <style>
        :root {
            --primary-color: #388e3c;
            --bg-color: #f1f8e9;
            --card-bg: #ffffff;
            --border-color: #c8e6c9;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }

        h1, h2 {
            text-align: center;
            color: var(--primary-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }

        th {
            background-color: var(--primary-color);
            color: #fff;
        }

        tr:hover {
            background-color: #e8f5e9;
        }

        button {
            display: block;
            margin: 30px auto 0 auto;
            padding: 12px 24px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1b5e20;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Hello, <?= htmlspecialchars($studentName) ?></h1>
    <h2>Your Attendance Records</h2>
    <table>
        <thead>
        <tr>
            <th>Date</th>
            <th>Status</th>
            <th>Marked By</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($attendanceRecords) === 0): ?>
            <tr><td colspan="3">No attendance records found.</td></tr>
        <?php else: ?>
            <?php foreach ($attendanceRecords as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Date']) ?></td>
                    <td><?= htmlspecialchars($row['Status']) ?></td>
                    <td><?= htmlspecialchars($row['Marked_By']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <button onclick="window.close()">Close</button>
</div>
</body>
</html>

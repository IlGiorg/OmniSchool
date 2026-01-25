<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: /");
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1:3307;dbname=omnischool;charset=utf8mb4",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Get parent children IDs
    $stmt = $pdo->prepare("SELECT Child1, Child2, Child3 FROM parents WHERE Username = ?");
    $stmt->execute([$_SESSION['username']]);
    $parent = $stmt->fetch(PDO::FETCH_ASSOC);

    $children = array_filter([
        ['id' => $parent['Child1'], 'key' => 'Child1'],
        ['id' => $parent['Child2'], 'key' => 'Child2'],
        ['id' => $parent['Child3'], 'key' => 'Child3']
    ]);

    $childData = [];
    if ($children) {
        $ids = array_column($children, 'id');
        $in = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $pdo->prepare("
            SELECT ID, First_name, CONCAT(c.Year, ' ', c.Form) AS Class 
            FROM students s
            JOIN classes c ON s.ClassID = c.ClassID
            WHERE s.ID IN ($in)
        ");
        $stmt->execute($ids);
        $childData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $childData = array_combine(array_column($childData, 'ID'), $childData);
    }

    // Get selected child ID from GET, default to first child
    $selectedChildID = $_GET['ID'] ?? ($children[0]['id'] ?? null);

    $childDetails = null;
    $conduct = $grades = $attendance = [];

    if ($selectedChildID && isset($childData[$selectedChildID])) {
        $childDetails = $childData[$selectedChildID];

        // Fetch conduct
        $stmt = $pdo->prepare("SELECT Consequence_Type, Reason, Date_Assigned FROM conduct WHERE Student_ID = ?");
        $stmt->execute([$selectedChildID]);
        $conduct = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch grades
        $stmt = $pdo->prepare("SELECT Grade, Assignment, Assessment_Date FROM grades WHERE Student_ID = ?");
        $stmt->execute([$selectedChildID]);
        $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch attendance
        $stmt = $pdo->prepare("SELECT Date, Status FROM attendance WHERE StudentID = ?");
        $stmt->execute([$selectedChildID]);
        $attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Parent Dashboard</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f8;
        margin: 0;
        display: flex;
        flex-direction: column;
    }
    header {
        background: #fff;
        padding: 20px 40px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
    }
    header img {
        height: 120px;
    }
    .dashboard {
        display: flex;
        flex: 1;
        width: 95%;
        margin: 20px auto;
    }
    .left-menu, .right-sidebar {
        width: 250px;
        background: #fff;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
    .left-menu h3, .right-sidebar h3 {
        background: #333;
        color: #fff;
        padding: 10px;
        margin: -15px -15px 15px -15px;
        border-radius: 8px 8px 0 0;
        text-align: center;
    }
    .left-menu a, .right-sidebar a {
        display: block;
        padding: 10px;
        margin-bottom: 10px;
        text-decoration: none;
        color: #000;
        background: #f9f9f9;
        border-radius: 5px;
        transition: background 0.2s;
    }
    .left-menu a:hover, .right-sidebar a:hover {
        background: #e0e0e0;
    }
    .main-content {
        flex: 1;
        margin: 0 20px;
        background: #fff;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
    .section {
        margin-bottom: 30px;
    }
    .section h4 {
        margin-bottom: 10px;
        color: #0056b3;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    th {
        background: #f0f0f0;
    }
</style>
</head>
<body>

<header>
    <img src="/img/omnischool.png" alt="OmniSchool Logo">
</header>

<div class="dashboard">
    <!-- Left menu -->
    <div class="left-menu">
        <h3>Options</h3>
        <a href="?ID=<?= $selectedChildID ?>&section=conduct">Conduct</a>
        <a href="?ID=<?= $selectedChildID ?>&section=grades">Grades</a>
        <a href="?ID=<?= $selectedChildID ?>&section=attendance">Attendance</a>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <h2><?= htmlspecialchars($childDetails['First_name'] ?? 'Select a child') ?> - <?= htmlspecialchars($childDetails['Class'] ?? '') ?></h2>

        <!-- Conduct -->
        <?php if (!empty($conduct)): ?>
            <div class="section">
                <h4>Conduct</h4>
                <table>
                    <tr>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Date</th>
                    </tr>
                    <?php foreach ($conduct as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['Consequence_Type']) ?></td>
                            <td><?= htmlspecialchars($c['Reason']) ?></td>
                            <td><?= htmlspecialchars($c['Date_Assigned']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>

        <!-- Grades -->
        <?php if (!empty($grades)): ?>
            <div class="section">
                <h4>Grades</h4>
                <table>
                    <tr>
                        <th>Assignment</th>
                        <th>Grade</th>
                        <th>Date</th>
                    </tr>
                    <?php foreach ($grades as $g): ?>
                        <tr>
                            <td><?= htmlspecialchars($g['Assignment']) ?></td>
                            <td><?= htmlspecialchars($g['Grade']) ?></td>
                            <td><?= htmlspecialchars($g['Assessment_Date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>

        <!-- Attendance -->
        <?php if (!empty($attendance)): ?>
            <div class="section">
                <h4>Attendance</h4>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($attendance as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['Date']) ?></td>
                            <td><?= htmlspecialchars($a['Status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right sidebar: children -->
    <div class="right-sidebar">
        <h3>My Children</h3>
        <?php if (empty($childData)): ?>
            <div>No children linked</div>
        <?php else: ?>
            <?php foreach ($children as $childRef): 
                $id = $childRef['id'];
                if (!isset($childData[$id])) continue;
                $child = $childData[$id];
            ?>
                <a href="?ID=<?= $child['ID'] ?>">
                    👤 <?= htmlspecialchars($child['First_name']) ?><br>
                    <span>Class: <?= htmlspecialchars($child['Class']) ?></span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

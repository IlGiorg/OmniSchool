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

        // Fetch student info along with their full class (Year + Form)
        $stmt = $pdo->prepare("
            SELECT ID, First_name, CONCAT(c.Year, ' ', c.Form) AS Class 
            FROM students s
            JOIN classes c ON s.ClassID = c.ClassID
            WHERE s.ID IN ($in)
        ");
        $stmt->execute($ids);
        $childData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Map childData by ID for easy linking
        $childData = array_combine(array_column($childData, 'ID'), $childData);
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Children</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
        }
        header {
            background: #fff;
            padding: 20px 40px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        header img {
            height: 120px; /* Bigger logo */
        }
        .container {
            width: 300px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .container h3 {
            background: #333;
            color: #fff;
            padding: 10px;
            margin: -20px -20px 20px -20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .child {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            background: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: #000;
            display: block;
        }
        .child:hover {
            background: #e0e0e0;
        }
        .child span {
            color: #555;
            font-size: 14px;
        }
        .child .status {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <img src="/img/omnischool.png" alt="OmniSchool Logo">
</header>

<div class="container">
    <h3>My Children</h3>

    <?php if (empty($childData)): ?>
        <div class="child">No children linked</div>
    <?php else: ?>
        <?php foreach ($children as $childRef): 
            $id = $childRef['id'];
            if (!isset($childData[$id])) continue;
            $child = $childData[$id];
        ?>
            <a class="child" href="/parents/authok/child.php?ID=<?= $child['ID'] ?>">
                👤 <?= htmlspecialchars($child['First_name']) ?><br>
                <span>Class: <?= htmlspecialchars($child['Class']) ?></span><br>
                <span class="status">Current</span>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>

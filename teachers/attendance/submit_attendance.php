<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- PDO DB connection ---
$pdo = new PDO(
    "mysql:host=127.0.0.1;port=3307;dbname=omnischool;charset=utf8mb4",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["status"], $_POST["classID"], $_POST["teacher_username"])
) {
    $classID = (int)$_POST["classID"];
    $statuses = $_POST["status"];
    $teacherUsername = trim($_POST["teacher_username"]);

    // --- Validate teacher ---
    $teacherStmt = $pdo->prepare(
        "SELECT TeachID FROM teachers WHERE Username = ?"
    );
    $teacherStmt->execute([$teacherUsername]);
    $teacherRow = $teacherStmt->fetch(PDO::FETCH_ASSOC);

    if (!$teacherRow) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Invalid Teacher</title>
            <style>
                body {
                    font-family: 'Segoe UI', sans-serif;
                    background-color: #ffebee;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .message-box {
                    background-color: #ffffff;
                    padding: 30px;
                    border-radius: 12px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    text-align: center;
                }
                .message-box h2 { color: #d32f2f; }
            </style>
        </head>
        <body>
            <div class="message-box">
                <h2>❌ Wrong Username</h2>
                <p>Please check the teacher username and try again.</p>
                <p><a href="javascript:history.back();">Go Back</a></p>
            </div>
        </body>
        </html>
        <?php
        exit;
    }

    $teacherID = (int)$teacherRow["TeachID"];

    // --- Insert attendance ---
    $insertStmt = $pdo->prepare(
        "INSERT INTO attendance (StudentID, Status, Date, ClassID, Recorded_By)
         VALUES (?, ?, CURDATE(), ?, ?)"
    );

    foreach ($statuses as $studentID => $status) {
        $insertStmt->execute([
            (int)$studentID,
            $status,
            $classID,
            $teacherID
        ]);
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Attendance Submitted</title>
        <meta http-equiv="refresh" content="3;url=attendance_landing.php">
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background-color: #e8f5e9;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .message-box {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                text-align: center;
            }
            .message-box h2 { color: #2e7d32; }
        </style>
    </head>
    <body>
        <div class="message-box">
            <h2>✅ Attendance Submitted</h2>
            <p>Redirecting you back to class selection...</p>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "Invalid submission.";
}
?>

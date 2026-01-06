<?php
// --- Begin DB connection ---
$host = "sql109.infinityfree.com";
$dbname = "if0_38817814_omnischool";
$user = "if0_38817814";
$pass = "OMNISoftware25";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
// --- End DB connection ---

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["status"], $_POST["classID"], $_POST["teacher_username"])) {
    $classID = (int)$_POST["classID"];
    $statuses = $_POST["status"];
    $teacherUsername = trim($_POST["teacher_username"]);

    // Check if teacher username exists
    $teacherStmt = $conn->prepare("SELECT TeachID FROM teachers WHERE Username = ?");
    $teacherStmt->bind_param("s", $teacherUsername);
    $teacherStmt->execute();
    $teacherResult = $teacherStmt->get_result();

    if ($teacherResult->num_rows === 0) {
        // Invalid teacher username
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
                .message-box h2 {
                    color: #d32f2f;
                }
                .message-box p {
                    color: #444;
                }
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

    $teacherRow = $teacherResult->fetch_assoc();
    $teacherID = (int)$teacherRow["TeachID"];

    // Insert attendance
    $stmt = $conn->prepare("INSERT INTO attendance (StudentID, Status, Date, ClassID, Recorded_By) VALUES (?, ?, CURDATE(), ?, ?)");

    foreach ($statuses as $studentID => $status) {
        $studentID = (int)$studentID;
        $status = $conn->real_escape_string($status);
        $stmt->bind_param("isii", $studentID, $status, $classID, $teacherID);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
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
            .message-box h2 {
                color: #2e7d32;
            }
            .message-box p {
                color: #444;
            }
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

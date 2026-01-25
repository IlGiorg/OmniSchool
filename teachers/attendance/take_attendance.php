<?php
// --- DB Connection ---
// DB config
$host = '127.0.0.1:3307';
$db = 'omnischool'; // Replace XXX
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$classID = isset($_GET['class']) ? (int)$_GET['class'] : 0;
if ($classID <= 0) die("Invalid class ID.");

$stmt = $conn->prepare("SELECT ID, First_name, Last_Name FROM students WHERE ClassID = ?");
$stmt->bind_param("i", $classID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) die("No students found for this class.");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Take Attendance</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f8fc;
            margin: 0;
            padding: 30px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: auto;
        }
        h2 {
            color: #1976d2;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #e3f2fd;
            padding: 10px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .status-options label {
            margin-right: 10px;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s;
        }
        .status-options input[type="radio"] {
            display: none;
        }
        .status-options label.present     { background-color: #e8f5e9; color: #388e3c; }
        .status-options label.absent      { background-color: #ffebee; color: #d32f2f; }
        .status-options label.late        { background-color: #fff8e1; color: #f57c00; }
        .status-options label.excused     { background-color: #e7daf7; color: #000000; }

        .status-options input:checked + label {
            box-shadow: 0 0 0 6px #0002;
        }

        .teacher-box {
            margin-top: 30px;
            text-align: center;
        }
        .teacher-box input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 250px;
        }

        .submit-btn {
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #1976d2;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            margin-left: auto;
            margin-right: auto;
            font-size: 16px;
        }

        .submit-btn:hover {
            background-color: #1565c0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Take Attendance</h2>
        <form method="POST" action="submit_attendance.php">
            <input type="hidden" name="classID" value="<?= htmlspecialchars($classID) ?>">
            <table>
                <tr>
                    <th>Student Name</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): 
                    $studentId = htmlspecialchars($row['ID']);
                    $name = htmlspecialchars($row['First_name'] . ' ' . $row['Last_Name']);
                ?>
                <tr>
                    <td><?= $name ?></td>
                    <td class="status-options">
                        <input type="radio" id="present_<?= $studentId ?>" name="status[<?= $studentId ?>]" value="Present" required>
                        <label class="present" for="present_<?= $studentId ?>">Present</label>

                        <input type="radio" id="absent_<?= $studentId ?>" name="status[<?= $studentId ?>]" value="Absent">
                        <label class="absent" for="absent_<?= $studentId ?>">Absent</label>

                        <input type="radio" id="late_<?= $studentId ?>" name="status[<?= $studentId ?>]" value="Late">
                        <label class="late" for="late_<?= $studentId ?>">Late</label>

                        <input type="radio" id="excused_<?= $studentId ?>" name="status[<?= $studentId ?>]" value="Out of School">
                        <label class="excused" for="excused_<?= $studentId ?>">Out of School</label>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Teacher Username Field -->
            <div class="teacher-box">
                <label for="teacher_username"><strong>Enter your username:</strong></label><br>
                <input type="text" id="teacher_username" name="teacher_username" required>
            </div>

            <button class="submit-btn" type="submit">Submit Attendance</button>
        </form>
    </div>
</body>
</html>

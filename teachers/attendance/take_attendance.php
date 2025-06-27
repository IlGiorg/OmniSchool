<?php
// --- Begin integrated DB connection ---
$host = "sql109.infinityfree.com";
$dbname = "if0_38817814_omnischool";
$user = "if0_38817814";
$pass = "OMNISoftware25";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// --- End DB connection ---

$classID = isset($_GET['class']) ? (int)$_GET['class'] : 0;
if ($classID <= 0) {
    die("Invalid class ID.");
}

$sql = "SELECT ID, First_name, Last_Name FROM students WHERE ClassID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $classID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No students found for this class.");
}

echo "<form method='POST' action='submit_attendance.php'>";
echo "<input type='hidden' name='classID' value='" . htmlspecialchars($classID) . "'>";
echo "<table border='1'>";
echo "<tr><th>Name</th><th>Status</th></tr>";

while ($row = $result->fetch_assoc()) {
    $studentId = htmlspecialchars($row['ID']);
    $firstName = htmlspecialchars($row['First_name']);
    $lastName = htmlspecialchars($row['Last_Name']);

    echo "<tr>";
    echo "<td>$firstName $lastName</td>";
    echo "<td>
        <label><input type='radio' name='status[$studentId]' value='Present' required> Present</label>
        <label><input type='radio' name='status[$studentId]' value='Absent'> Absent</label>
        <label><input type='radio' name='status[$studentId]' value='Late'> Late</label>
        <label><input type='radio' name='status[$studentId]' value='Excused'> Excused</label>
    </td>";
    echo "</tr>";
}

echo "</table>";
echo "<button type='submit'>Submit Attendance</button>";
echo "</form>";

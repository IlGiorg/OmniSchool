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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$result = mysqli_query($conn, "SELECT * FROM classes");
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head><title>Attendance Landing</title></head>';
echo '<body>';
echo '<h2>Select Class for Attendance</h2>';
echo '<form method="GET" action="take_attendance.php">';
echo '<label for="class">Class:</label>';
echo '<select name="class" id="class">';

while ($row = mysqli_fetch_assoc($result)) {
    $classID = htmlspecialchars($row['ClassID']);
    $year = htmlspecialchars($row['Year']);
    $form = htmlspecialchars($row['Form']);
    echo "<option value=\"$classID\">Year $year$form</option>";
}

echo '</select>';
echo '<button type="submit">Take Attendance</button>';
echo '</form>';
echo '</body>';
echo '</html>';
?>

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



$classID = $_POST['classID'];
$teacherID = 1; // replace with session ID
$date = date('Y-m-d');

foreach ($_POST['status'] as $studentID => $status) {
  // Check if already recorded
  $check = mysqli_query($conn, "SELECT * FROM attendance WHERE Student_ID=$studentID AND Date='$date'");
  if (mysqli_num_rows($check) == 0) {
    $stmt = $conn->prepare("INSERT INTO attendance (Student_ID, ClassID, Date, Status, Recorded_By) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $studentID, $classID, $date, $status, $teacherID);
    $stmt->execute();
  }
}

echo "Attendance recorded! <a href='attendance_landing.html'>Back</a>";
?>

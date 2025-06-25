<?php
include '../../db.php';

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

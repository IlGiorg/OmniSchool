<?php
include '../../db.php'; // DB connection
$classID = $_GET['class'];
$teacherID = 1; // replace with logged-in teacher ID
$today = date('Y-m-d');

// Get students in class
$result = mysqli_query($conn, "SELECT * FROM students WHERE ClassID = $classID");

echo "<form method='POST' action='submit_attendance.php'>";
echo "<input type='hidden' name='classID' value='$classID'>";
echo "<table border='1'><tr><th>Name</th><th>Status</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>";
  echo "<td>{$row['First_name']} {$row['Last_Name']}</td>";
  echo "<td>
    <input type='radio' name='status[{$row['ID']}]' value='Present' required> Present
    <input type='radio' name='status[{$row['ID']}]' value='Absent'> Absent
    <input type='radio' name='status[{$row['ID']}]' value='Late'> Late
    <input type='radio' name='status[{$row['ID']}]' value='Excused'> Excused
  </td>";
  echo "</tr>";
}
echo "</table>";
echo "<button type='submit'>Submit Attendance</button>";
echo "</form>";
?>

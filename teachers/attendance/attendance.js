document.getElementById('loadStudents').addEventListener('click', function() {
    const classId = document.getElementById('classSelect').value;

    fetch('students.json')
        .then(response => response.json())
        .then(students => {
            const studentListDiv = document.getElementById('studentList');
            studentListDiv.innerHTML = ''; // Clear previous list
            students.filter(s => s.classId == classId).forEach(student => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <label>${student.name} ${student.surname}</label>
                    <select id="attendance-${student.username}">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                    </select>
                `;
                studentListDiv.appendChild(div);
            });
        });
});

document.getElementById('submitAttendance').addEventListener('click', function() {
    const classId = document.getElementById('classSelect').value;
    const attendanceRecords = [];

    const students = document.querySelectorAll('[id^="attendance-"]');
    students.forEach(student => {
        const username = student.id.split('-')[1];
        const status = student.value;
        attendanceRecords.push({ username, status });
    });

    const attendanceData = {
        classId,
        date: new Date().toISOString().split('T')[0],
        attendance: attendanceRecords
    };

    fetch('attendance.json', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(attendanceData)
    }).then(() => {
        alert('Attendance submitted successfully!');
    });
});
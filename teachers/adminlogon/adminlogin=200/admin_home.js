document.getElementById('createTeacherButton').addEventListener('click', function() {
    const username = document.getElementById('teacherUsername').value;
    const password = document.getElementById('teacherPassword').value;

    fetch('teachers.json')
        .then(response => response.json())
        .then(teachers => {
            // Check if the username already exists
            const existingTeacher = teachers.find(t => t.username === username);
            if (existingTeacher) {
                document.getElementById('message').innerText = 'Username already exists!';
                return;
            }

            teachers.push({ username, password });
            // Update the teachers.json file (this would typically be done on the server)
            fetch('teachers.json', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(teachers)
            }).then(() => {
                document.getElementById('message').innerText = 'Teacher account created successfully!';
                // Clear input fields
                document.getElementById('teacherUsername').value = '';
                document.getElementById('teacherPassword').value = '';
            });
        });
});

document.getElementById('createStudentButton').addEventListener('click', function() {
    const name = document.getElementById('studentName').value;
    const surname = document.getElementById('studentSurname').value;
    const username = document.getElementById('studentUsername').value;
    const password = document.getElementById('studentPassword').value;

    fetch('students.json')
        .then(response => response.json())
        .then(students => {
            // Check if the username already exists
            const existingStudent = students.find(s => s.username === username);
            if (existingStudent) {
                document.getElementById('message').innerText = 'Username already exists!';
                return;
            }

            const newStudent = {
                name,
                surname,
                username,
                password,
                homework: [],
                classId: 1, // Default class ID, can be modified as needed
                consequences: []
            };
            students.push(newStudent);
            // Update the students.json file (this would typically be done on the server)
            fetch('students.json', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(students)
            }).then(() => {
                document.getElementById('message').innerText = 'Student account created successfully!';
                // Clear input fields
                document.getElementById('studentName').value = '';
                document.getElementById('studentSurname').value = '';
                document.getElementById('studentUsername').value = '';
                document.getElementById('studentPassword').value = '';
            });
        });
});
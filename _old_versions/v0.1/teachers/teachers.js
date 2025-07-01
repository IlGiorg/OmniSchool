document.getElementById('teacherLoginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    fetch('teachers.json')
        .then(response => response.json())
        .then(teachers => {
            const teacher = teachers.find(t => t.username === username && t.password === password);
            if (teacher) {
                localStorage.setItem('currentTeacher', JSON.stringify(teacher));
                window.location.href = 'teacher_home.html';
            } else {
                document.getElementById('errorMessage').innerText = 'Invalid username or password';
            }
        });
});
document.getElementById("teacherLoginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    fetch("/teacher_login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        credentials: "include", // Important for PHP session
        body: JSON.stringify({ username, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = "/teachers/teacher_home.html";
        } else {
            document.getElementById("errorMessage").innerText = data.message;
        }
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById("errorMessage").innerText = "Something went wrong.";
    });
});

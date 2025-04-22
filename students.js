document.getElementById("studentLoginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    fetch("/student_login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ username, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem("username", username); // ✅ Only set if login worked
            window.location.href = "/students/student_home.html"; // ✅ Only redirect on success
        } else {
            document.getElementById("errorMessage").innerText = data.message;
        }
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById("errorMessage").innerText = "700: Something went wrong.";
    });
});

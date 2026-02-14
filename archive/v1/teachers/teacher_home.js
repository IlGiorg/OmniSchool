fetch("/get_teacher_data.php", {
    credentials: "include"
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        document.getElementById("welcomeMessage").innerText = `Welcome, ${data.username}`;
    } else {
        window.location.href = "/teacher_login.html"; // redirect if not logged in
    }
});

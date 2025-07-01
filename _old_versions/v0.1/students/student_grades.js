// Simulating a "logged-in" user — in a real app, use a session
const loggedInUsername = localStorage.getItem("username") || "testuser";

fetch("/php/get_student_grades.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ username: loggedInUsername })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        document.getElementById("greeting").textContent = "Hello, " + data.name;

        const tableBody = document.querySelector("#consequenceTable tbody");
        data.grades.forEach(row => {
            const tr = document.createElement("tr");
            tr.innerHTML = `<td>${row.Grade}</td><td>${row.Assignment}</td><td>${row.Assessment_Date}</td>`;
            tableBody.appendChild(tr);
        });
    } else {
        document.body.innerHTML = `<p>Error: ${data.message}</p>`;
    }
});

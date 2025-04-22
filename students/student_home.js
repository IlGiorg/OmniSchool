// Simulating a "logged-in" user — in a real app, use a session
const loggedInUsername = localStorage.getItem("username") || "testuser";

fetch("/get_student_data.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ username: loggedInUsername })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        document.getElementById("greeting").textContent = "Hello, " + data.name;

        const tableBody = document.querySelector("#consequenceTable tbody");
        data.consequences.forEach(row => {
            const tr = document.createElement("tr");
            tr.innerHTML = `<td>${row.Consequence_Type}</td><td>${row.Reason}</td><td>${row.Date_Assigned}</td>`;
            tableBody.appendChild(tr);
        });
    } else {
        document.body.innerHTML = `<p>Error: ${data.message}</p>`;
    }
});

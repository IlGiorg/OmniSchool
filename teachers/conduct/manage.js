document.getElementById("consequenceForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const data = {
        studentId: document.getElementById("studentId").value,
        type: document.getElementById("type").value,
        reason: document.getElementById("reason").value
    };

    fetch("add_consequence.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        credentials: "include",
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        alert(result.message);
        loadRecords();
    });
});
document.getElementById("studentSearch").addEventListener("input", function () {
    const query = this.value;
    if (query.length < 2) return;

    fetch("../../search_students.php?q=" + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            const resultsList = document.getElementById("searchResults");
            resultsList.innerHTML = "";

            data.forEach(student => {
                const li = document.createElement("li");
                li.textContent = `${student.First_name} ${student.Last_Name} (ID: ${student.ID})`;
                li.style.cursor = "pointer";
                li.onclick = () => {
                    document.getElementById("studentId").value = student.ID;
                    document.getElementById("studentSearch").value = `${student.First_name} ${student.Last_Name}`;
                    resultsList.innerHTML = "";
                };
                resultsList.appendChild(li);
            });
        });
});

function loadRecords() {
    fetch("get_consequences.php", { credentials: "include" })
    .then(res => res.json())
    .then(data => {
        const tbody = document.querySelector("#recordsTable tbody");
        tbody.innerHTML = "";
        data.records.forEach(row => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${row.ID}</td>
                <td>${row.Student_ID}</td>
                <td>${row.Consequence_Type}</td>
                <td>${row.Reason}</td>
                <td>${row.Date_Assigned}</td>
            `;
            tbody.appendChild(tr);
        });
    });
}

window.onload = loadRecords;

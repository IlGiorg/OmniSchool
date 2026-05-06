        document.getElementById("addstudent").addEventListener("submit", function (e) {
            e.preventDefault();


            const data = {
                First_Name: document.getElementById("stname").value,
                Last_Name: document.getElementById("stsurname").value,
                Username: document.getElementById("studer").value,
                Password: document.getElementById("stpsw").value,
                Academic_House: document.getElementById("house").value,
                DOB: document.getElementById("dob").value,
            };

            fetch("/teachers/adminlogon/adminlogin=200/add_student.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                credentials: "include",
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .catch(err => {
                alert("Error adding consequence.");
                console.error(err);
            });
        });

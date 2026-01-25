<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmniSchool</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 80px;
        }
        .logo {
            max-width: 400px;
        }
        .buttons {
            margin-top: 50px;
        }
        .button-img {
            width: 200px;
            margin: 25px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .button-img:hover {
            transform: scale(1.07);
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="img/omnischool.png" alt="OmniSchool Logo" class="logo">
        <div class="buttons">
            <a href="students.html">
                <img src="img/omnistudent.png" alt="Students" class="button-img">
            </a>
            <a href="teacher_login.html">
                <img src="img/omniteacher.png" alt="Teachers" class="button-img">
            </a>
            <a href="parents/logon.php">
                <img src="img/omniparent.png" alt="Parents" class="button-img">
            </a>
        </div>
    </div>
</body>
</html>

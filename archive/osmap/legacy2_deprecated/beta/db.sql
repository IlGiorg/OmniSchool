CREATE TABLE Students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    surname VARCHAR(255),
    DOB DATE,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    academic_house VARCHAR(255),
    form VARCHAR(255),
    conduct TEXT
);

CREATE TABLE Teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE Homework (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class VARCHAR(255),
    assignment TEXT,
    due_date DATE
);

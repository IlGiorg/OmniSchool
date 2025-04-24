-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--

--OmniSchool Base Setup.
-- Teast credentials included
--Last updated: dd/mm/yyyy 24/04/2025 12:19:40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `OSMAP`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(3) NOT NULL,
  `Username` varchar(25) NOT NULL,
  `Password` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `Username`, `Password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `Classes`
--

CREATE TABLE `classes` (
  `ClassID` int(11) NOT NULL,
  `Year` int(2) NOT NULL,
  `Form` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Classes`
--

INSERT INTO `classes` (`ClassID`, `Year`, `Form`) VALUES
(1, 7, 'A'),
(2, 7, 'B'),
(3, 8, 'A'),
(4, 8, 'B'),
(5, 9, 'A'),
(6, 9, 'B'),
(7, 10, 'A'),
(8, 10, 'B'),
(9, 11, 'A'),
(10, 11, 'B'),
(11, 12, 'A'),
(12, 12, 'B'),
(13, 13, 'A'),
(14, 13, 'B');

-- --------------------------------------------------------

--
-- Table structure for table `conduct`
--

CREATE TABLE `conduct` (
  `ID` int(11) NOT NULL,
  `Student_ID` int(11) NOT NULL,
  `Consequence_Type` enum('HP','L0','L1','L2','L3','L4') NOT NULL,
  `Reason` varchar(255) DEFAULT NULL,
  `Date_Assigned` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



--
-- Table structure for table `Grades`
--

CREATE TABLE `grades` (
  `ID` int(11) NOT NULL,
  `Student_ID` int(11) NOT NULL,
  `Grade` enum('0','1','2','3','4','5','6','7','8','9','10') NOT NULL,
  `Assignment` varchar(255) DEFAULT NULL,
  `Assessment_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `ID` int(4) NOT NULL,
  `First_name` varchar(25) NOT NULL,
  `Last_Name` varchar(25) NOT NULL,
  `Username` varchar(25) NOT NULL,
  `Password` varchar(15) NOT NULL,
  `Academic_House` varchar(15) NOT NULL,
  `DOB` date NOT NULL,
  `ClassID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`ID`, `First_name`, `Last_Name`, `Username`, `Password`, `Academic_House`, `DOB`, `ClassID`) VALUES
(2, 'Paul', 'Smith', 'PSmith', '123456', 'Red', '2010-09-11', NULL),
(1, 'First', 'Last', 'testuser', 'testpass', 'Blue', '2025-04-02', NULL),
(3, 'Anthony', 'Green', 'AGreen', '654321', 'Yellow', '2013-07-03', NULL),
(4, 'Mathilda', 'White', 'MWhite', '0', 'Green', '2009-21-07', NULL);
-- --------------------------------------------------------

--
-- Table structure for table `Teachers`
--

CREATE TABLE `teachers` (
  `TeachID` int(11) NOT NULL,
  `Username` varchar(11) NOT NULL,
  `Password` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Teachers`
--

INSERT INTO `teachers` (`TeachID`, `Username`, `Password`) VALUES
(1, 'testteach', 'testpass');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `Classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`ClassID`);

--
-- Indexes for table `conduct`
--
ALTER TABLE `consequences`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Student_ID` (`Student_ID`);

--
-- Indexes for table `Grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_username` (`Username`),
  ADD KEY `fk_class` (`ClassID`);

--
-- Indexes for table `Teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`TeachID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Classes`
--
ALTER TABLE `classes`
  MODIFY `ClassID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `conduct`
--
ALTER TABLE `conduct`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Grades`
--
ALTER TABLE `grades`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Teachers`
--
ALTER TABLE `teachers`
  MODIFY `TeachID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conduct`
--
ALTER TABLE `conduct`
  ADD CONSTRAINT `consequences_ibfk_1` FOREIGN KEY (`Student_ID`) REFERENCES `students` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_class` FOREIGN KEY (`ClassID`) REFERENCES `classes` (`ClassID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

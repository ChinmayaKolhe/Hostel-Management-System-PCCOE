-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Apr 08, 2025 at 03:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hostel`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`` PROCEDURE `RegisterStudent` (IN `fname` VARCHAR(50), IN `lname` VARCHAR(50), IN `dob` DATE, IN `email` VARCHAR(100), IN `phone` VARCHAR(15), IN `course` VARCHAR(100))   BEGIN
  INSERT INTO student (First_Name, Last_Name, DOB, Email, Phone, Course)
  VALUES (fname, lname, dob, email, phone, course);
END$$

--
-- Functions
--
CREATE DEFINER=`` FUNCTION `GetTotalDue` (`studentId` INT) RETURNS DECIMAL(10,2) DETERMINISTIC BEGIN
  DECLARE due DECIMAL(10,2);
  SELECT SUM(Remaining_Amount) INTO due FROM fees WHERE Student_ID = studentId;
  RETURN IFNULL(due, 0.00);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_ID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Reg_Date` datetime DEFAULT current_timestamp(),
  `Upd_Date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_ID`, `Username`, `Email`, `Password`, `Reg_Date`, `Upd_Date`) VALUES
(1, 'admin1', 'admin1@gmail.com', '$2y$10$.Gf0uCEb2nSEBe8B5CfI4OoygmnbtGnuFqeORP1dBielFx8ka7LdK', '2025-04-08 16:43:37', NULL),
(2, 'Rudra Kolhe', 'rudrakolhe@gmail.com', '$2y$10$6NYXCmgL3STpQtJSh5OMaO3dTXc1nSMUp/R0zv6mjKKxZVlw0tBpi', '2025-04-08 16:45:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `Log_ID` int(11) NOT NULL,
  `Admin_ID` int(11) DEFAULT NULL,
  `IP_Address` varchar(50) DEFAULT NULL,
  `Login_Time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `City_ID` int(11) NOT NULL,
  `City_Name` varchar(100) NOT NULL,
  `State_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`City_ID`, `City_Name`, `State_ID`) VALUES
(1, 'Mumbai', 1),
(2, 'Pune', 1),
(3, 'Nagpur', 1),
(4, 'Nashik', 1),
(5, 'Aurangabad', 1),
(6, 'Solapur', 1),
(7, 'Amravati', 1),
(8, 'Kolhapur', 1),
(9, 'Latur', 1),
(10, 'Jalgaon', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Emp_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Aadhar_No` varchar(12) NOT NULL,
  `Service` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `Fees_ID` int(11) NOT NULL,
  `Student_ID` int(11) DEFAULT NULL,
  `Room_ID` int(11) DEFAULT NULL,
  `Total_Fees` decimal(10,2) NOT NULL,
  `Paid_Amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Remaining_Amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `Payment_Status` enum('Paid','Pending','Partial') DEFAULT 'Pending',
  `Mess_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`Fees_ID`, `Student_ID`, `Room_ID`, `Total_Fees`, `Paid_Amount`, `Remaining_Amount`, `Payment_Status`, `Mess_ID`) VALUES
(1, 2, NULL, 4000.00, 0.00, 0.00, 'Paid', NULL),
(2, 2, NULL, 3000.00, 0.00, 0.00, 'Paid', NULL),
(3, 3, NULL, 4000.00, 0.00, 0.00, 'Paid', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mess`
--

CREATE TABLE `mess` (
  `Mess_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Options` enum('Veg','Non-Veg','Both') DEFAULT NULL,
  `Food_Status` enum('Active','Inactive') DEFAULT NULL,
  `Mess_Fee` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mess`
--

INSERT INTO `mess` (`Mess_ID`, `Name`, `Options`, `Food_Status`, `Mess_Fee`) VALUES
(1, 'Girls hostel 1', 'Veg', 'Active', 4000.00),
(2, 'Girls hostel 2', 'Both', 'Active', 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `Transaction_ID` int(11) NOT NULL,
  `Fees_ID` int(11) DEFAULT NULL,
  `Student_ID` int(11) DEFAULT NULL,
  `Payment_Amount` decimal(10,2) NOT NULL,
  `Payment_Date` datetime DEFAULT current_timestamp(),
  `Payment_Method` enum('Cash','Credit Card','UPI','Net Banking') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `after_payment_insert` AFTER INSERT ON `payments` FOR EACH ROW BEGIN
  UPDATE fees
  SET 
    Paid_Amount = Paid_Amount + NEW.Payment_Amount,
    Remaining_Amount = Total_Fees - (Paid_Amount + NEW.Payment_Amount),
    Payment_Status = CASE
      WHEN Total_Fees = Paid_Amount + NEW.Payment_Amount THEN 'Paid'
      WHEN Paid_Amount + NEW.Payment_Amount = 0 THEN 'Pending'
      ELSE 'Partial'
    END
  WHERE Fees_ID = NEW.Fees_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `Room_ID` int(11) NOT NULL,
  `Seater` int(11) NOT NULL,
  `Room_No` varchar(10) NOT NULL,
  `Fees` decimal(10,2) NOT NULL,
  `Posting_Date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`Room_ID`, `Seater`, `Room_No`, `Fees`, `Posting_Date`) VALUES
(1, 4, '101', 4000.00, '2025-04-08 00:00:00'),
(2, 3, '102', 5000.00, '2025-04-08 00:00:00'),
(3, 5, '103', 2500.00, '2025-04-08 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `State_ID` int(11) NOT NULL,
  `State_Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`State_ID`, `State_Name`) VALUES
(1, 'Maharashtra'),
(2, 'Gujarat'),
(3, 'Karnataka'),
(4, 'Tamil Nadu'),
(5, 'Delhi'),
(6, 'Uttar Pradesh'),
(7, 'Madhya Pradesh'),
(8, 'West Bengal'),
(9, 'Rajasthan'),
(10, 'Punjab');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `Student_ID` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `DOB` date NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(15) NOT NULL,
  `Course` varchar(100) NOT NULL,
  `Guardian_Name` varchar(100) DEFAULT NULL,
  `Guardian_Contact` varchar(15) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `City_ID` int(11) DEFAULT NULL,
  `State_ID` int(11) DEFAULT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`Student_ID`, `First_Name`, `Middle_Name`, `Last_Name`, `Gender`, `DOB`, `Email`, `Phone`, `Course`, `Guardian_Name`, `Guardian_Contact`, `Address`, `City_ID`, `State_ID`, `Password`) VALUES
(2, 'Shreya', 'Mahesh ', 'Bhole', 'Female', '2006-06-14', 'shreya@gmail.com', '6789567845', 'B.Tech Computer Engineering', 'Sagar ', 'Bharambe', '606, ved vihar,mauli park,ravet', 2, 1, '$2y$10$T.P7hOKtvykum3YTpz0Rbu8Da6FtQZuzSMbf5GOWFz5C1iXGlwhZ2'),
(3, 'Yamini', 'Mahesh', 'Bhole', 'Female', '2009-07-16', 'yamini@gmail.com', '8909789878', 'B.Tech Electronics and Telecommunication', 'Sagar ', 'Patil', '22,Gurudwara chowk, akurdi,pune', 2, 1, '$2y$10$jzLA/E4x/KPnj0HLl4qAg.iy/s.F1cf5HFJF3UpvXFb6Vy/kCjzVa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`);

--
-- Indexes for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`Log_ID`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`City_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Emp_ID`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`Fees_ID`);

--
-- Indexes for table `mess`
--
ALTER TABLE `mess`
  ADD PRIMARY KEY (`Mess_ID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`Transaction_ID`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`Room_ID`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`State_ID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`Student_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `Log_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `City_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `Emp_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `Fees_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mess`
--
ALTER TABLE `mess`
  MODIFY `Mess_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `Transaction_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `Room_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `State_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `Student_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

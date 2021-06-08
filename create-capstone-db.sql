-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2021 at 02:25 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatboxmessages`
--

CREATE TABLE `chatboxmessages` (
  `CreatedDateAndTime` datetime NOT NULL,
  `Message` blob NOT NULL,
  `employeeID` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chatboxmessages`
--

INSERT INTO `chatboxmessages` (`CreatedDateAndTime`, `Message`, `employeeID`) VALUES
('2021-06-01 00:28:19', 0x44616e6b6965446f6f646c65, 1),
('2021-06-01 00:28:26', 0x597570707070, 1),
('2021-06-01 00:28:35', 0x49747320776f726b696e67212121, 1),
('2021-06-01 00:29:33', 0x4d656d657373, 1);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `Username` text NOT NULL,
  `password` text NOT NULL,
  `employeeID` int(255) NOT NULL,
  `Supervisor` tinyint(1) NOT NULL,
  `Instructor` tinyint(1) NOT NULL,
  `Lifeguard` tinyint(1) NOT NULL,
  `Headguard` tinyint(1) NOT NULL,
  `Firstname` text NOT NULL,
  `Lastname` text NOT NULL,
  `NewNotfications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Availability` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`Username`, `password`, `employeeID`, `Supervisor`, `Instructor`, `Lifeguard`, `Headguard`, `Firstname`, `Lastname`, `NewNotfications`, `Availability`) VALUES
('Norman.Potts', 'roflPlz', 1, 1, 0, 0, 0, 'Norman', 'Potts', NULL, '{\"Mondays\":{\"A1\":true,\"A2\":true,\"A3\":true,\"A4\":true,\"A5\":true,\"A6\":true,\"A7\":true,\"ANOT\":false,\"Anytime\":true},\"Tuesday\":{\"A1\":true,\"A2\":true,\"A3\":true,\"A4\":true,\"A5\":true,\"A6\":true,\"A7\":true,\"ANOT\":false,\"Anytime\":true},\"Wednesday\":{\"A1\":true,\"A2\":true,\"A3\":true,\"A4\":true,\"A5\":true,\"A6\":true,\"A7\":true,\"ANOT\":false,\"Anytime\":true},\"Thrusday\":{\"A1\":false,\"A2\":false,\"A3\":false,\"A4\":false,\"A5\":false,\"A6\":false,\"A7\":false,\"ANOT\":true,\"Anytime\":false},\"Friday\":{\"A1\":false,\"A2\":false,\"A3\":false,\"A4\":false,\"A5\":false,\"A6\":false,\"A7\":false,\"ANOT\":true,\"Anytime\":false},\"Saturday\":{\"A1\":true,\"A2\":true,\"A3\":true,\"A4\":true,\"A5\":\" \",\"A6\":\" \",\"A7\":\" \",\"ANOT\":false,\"Anytime\":true},\"Sundays\":{\"A1\":false,\"A2\":false,\"A3\":false,\"A4\":false,\"A5\":false,\"A6\":false,\"A7\":false,\"ANOT\":true,\"Anytime\":false},\"Notes\":\"Yeet Can work anytime \"}'),
('Dank.Memes', 'password', 2, 0, 0, 1, 0, 'Dank', 'Memes', NULL, 'Nothing yet');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `employeeID` int(255) NOT NULL,
  `type` int(255) NOT NULL,
  `message` blob NOT NULL,
  `readOrUnread` tinyint(1) NOT NULL,
  `CreatedDateAndTime` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`employeeID`, `type`, `message`, `readOrUnread`, `CreatedDateAndTime`) VALUES
(2, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30352d33312066726f6d2030303a30303a303020746f2030303a33303a3030206173204c6966656775617264696e672e, 0, '2021-05-31'),
(3, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30352d33312066726f6d2030303a30303a303020746f2030303a33303a303020617320496e7374727563746f72696e672e, 0, '2021-05-31'),
(1, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30352d33312066726f6d2030303a30303a303020746f2030303a33303a303020617320486561644775617264696e672e, 1, '2021-05-31'),
(1, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30352d33312066726f6d2030353a33303a303020746f2031313a30303a30302061732053757065727669736f72696e672e, 1, '2021-05-31'),
(3, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30352d33312066726f6d2030333a30303a303020746f2030393a30303a3030206173204c6966656775617264696e672e, 0, '2021-05-31'),
(1, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30372d30352066726f6d2030333a30303a303020746f2030383a33303a3030206173204c6966656775617264696e672e, 1, '2021-05-31'),
(3, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30362d30352066726f6d2031373a33303a303020746f2032313a33303a303020617320496e7374727563746f72696e672e, 0, '2021-06-05'),
(3, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30362d30352066726f6d2031313a33303a303020746f2031343a33303a3030206173204c6966656775617264696e672e, 0, '2021-06-05'),
(2, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30362d30352066726f6d2031313a33303a303020746f2031343a33303a3030206173204c6966656775617264696e672e, 0, '2021-06-05'),
(3, 5, 0x596f7520776572652061737369676e6564206120536869667420666f7220323032312d30362d30352066726f6d2031353a30303a303020746f2031363a30303a3030206173204c6966656775617264696e672e, 0, '2021-06-05');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `ShiftID` int(255) NOT NULL,
  `DefaultOwnerEmployeeID` int(255) NOT NULL,
  `CurrentOwnerEmployeeID` int(255) NOT NULL,
  `date` date NOT NULL,
  `startTime` mediumtext NOT NULL,
  `endTime` mediumtext NOT NULL,
  `Position` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`ShiftID`, `DefaultOwnerEmployeeID`, `CurrentOwnerEmployeeID`, `date`, `startTime`, `endTime`, `Position`) VALUES
(2, 0, 0, '2021-05-31', '00:00:00', '00:30:00', '2'),
(3, 1, 1, '2021-05-31', '00:00:00', '00:30:00', '3'),
(4, 1, 1, '2021-05-31', '05:30:00', '11:00:00', '4'),
(5, 0, 0, '2021-05-31', '03:00:00', '09:00:00', '1'),
(6, 1, 1, '2021-07-05', '03:00:00', '08:30:00', '1'),
(7, 0, 0, '2021-06-05', '17:30:00', '21:30:00', '2'),
(8, 0, 0, '2021-06-05', '11:30:00', '14:30:00', '1'),
(9, 2, 2, '2021-06-05', '11:30:00', '14:30:00', '1'),
(10, 0, 0, '2021-06-05', '15:00:00', '16:00:00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `subslips`
--

CREATE TABLE `subslips` (
  `CreatorID` int(255) NOT NULL,
  `TakerID` int(255) DEFAULT NULL,
  `ShiftDate` date NOT NULL,
  `startTime` varchar(255) NOT NULL,
  `endTime` varchar(255) NOT NULL,
  `Position` int(5) NOT NULL,
  `Reason` text NOT NULL,
  `TakenTrueorFalse` tinyint(1) NOT NULL,
  `TakenDateAndTime` text DEFAULT NULL,
  `ShiftID` int(255) DEFAULT NULL,
  `completed` tinyint(1) NOT NULL,
  `subslipID` int(11) NOT NULL,
  `CreatedDateAndTime` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD UNIQUE KEY `employeeID` (`employeeID`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`ShiftID`),
  ADD UNIQUE KEY `ShiftID` (`ShiftID`);

--
-- Indexes for table `subslips`
--
ALTER TABLE `subslips`
  ADD PRIMARY KEY (`subslipID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employeeID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `ShiftID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

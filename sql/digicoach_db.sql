-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 27, 2025 at 05:23 AM
-- Server version: 5.7.40-log
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digicoach_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `status` enum('Present','Absent') DEFAULT 'Absent',
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `trainer_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `status`, `date`, `trainer_id`, `room_id`) VALUES
(1, 1, 'Present', '2025-10-23 07:00:52', 1, 3),
(2, 1, 'Present', '2025-10-23 10:56:02', 1, NULL),
(3, 4, 'Absent', '2025-10-23 10:56:02', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

DROP TABLE IF EXISTS `fees`;
CREATE TABLE IF NOT EXISTS `fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `course_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `student_id`, `payment_date`, `amount`, `course_name`) VALUES
(1, 2, '2025-10-14', '2000.00', 'html'),
(2, 1, '2025-10-24', '10000.00', 'html');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(100) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `course_name`, `trainer_id`, `created_at`) VALUES
(1, 'CSS', 1, '2025-10-15 06:05:07'),
(2, 'React', 2, '2025-10-15 07:22:52');

-- --------------------------------------------------------

--
-- Table structure for table `room_students`
--

DROP TABLE IF EXISTS `room_students`;
CREATE TABLE IF NOT EXISTS `room_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `course` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `name`, `email`, `password`, `course`) VALUES
(1, 'surya', 'surya@gmail.com', '12345', 'html'),
(2, 'abi', 'abi@gmail.com', '', 'html'),
(3, 'marish', 'marish@gmail.com', '', 'html'),
(4, 'bharath', 'bharath@gmail.com', '', 'html'),
(5, 'aj', 'aj@gmail.com', '', 'html');

-- --------------------------------------------------------

--
-- Table structure for table `submission`
--

DROP TABLE IF EXISTS `submission`;
CREATE TABLE IF NOT EXISTS `submission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `due_date` date NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `room_id` int(11) DEFAULT NULL,
  KEY `assigned_to` (`assigned_to`),
  KEY `fk_task_room` (`room_id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `title`, `description`, `due_date`, `assigned_to`, `status`, `room_id`) VALUES
(7, 'react', 'asdfghj', '2025-10-29', NULL, 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tasks_assigned`
--

DROP TABLE IF EXISTS `tasks_assigned`;
CREATE TABLE IF NOT EXISTS `tasks_assigned` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_title` varchar(255) NOT NULL,
  `task_desc` text NOT NULL,
  `due_date` date NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tasks_assigned`
--

INSERT INTO `tasks_assigned` (`id`, `task_title`, `task_desc`, `due_date`, `trainer_id`, `student_id`, `created_at`, `status`) VALUES
(1, 'react', 'hgfds', '2025-10-23', 2, 4, '2025-10-17 07:36:14', 'Pending'),
(2, 'html', 'oiuhgfc', '2025-10-15', 2, 1, '2025-10-17 07:36:44', 'Pending'),
(3, 'html', 'oiuhgfc', '2025-10-15', 2, 2, '2025-10-17 07:36:44', 'Pending'),
(4, 'html', 'oiuhgfc', '2025-10-15', 2, 3, '2025-10-17 07:36:44', 'Pending'),
(5, 'html', 'oiuhgfc', '2025-10-15', 2, 4, '2025-10-17 07:36:44', 'Pending'),
(6, 'html', 'oiuhgfc', '2025-10-15', 2, 5, '2025-10-17 07:36:44', 'Pending'),
(7, 'react', 'fix', '2025-11-05', 2, 1, '2025-10-17 10:22:08', 'Pending'),
(8, 'react', 'fix', '2025-11-05', 2, 2, '2025-10-17 10:22:08', 'Pending'),
(9, 'react', 'fix', '2025-11-05', 2, 3, '2025-10-17 10:22:08', 'Pending'),
(10, 'react', 'fix', '2025-11-05', 2, 4, '2025-10-17 10:22:08', 'Pending'),
(11, 'react', 'fix', '2025-11-05', 2, 5, '2025-10-17 10:22:08', 'Pending'),
(12, 'react', 'asdfgh', '2025-10-30', 1, 1, '2025-10-23 10:59:55', 'Pending'),
(13, 'react', 'asdfgh', '2025-10-30', 1, 4, '2025-10-23 10:59:55', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `task_submissions`
--

DROP TABLE IF EXISTS `task_submissions`;
CREATE TABLE IF NOT EXISTS `task_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `task_title` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Submitted',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task_submissions`
--

INSERT INTO `task_submissions` (`id`, `student_id`, `task_title`, `file_path`, `submitted_at`, `status`) VALUES
(1, 1, 'html', '../uploads/1760697739_testmysql.php', '2025-10-17 16:12:19', 'Submitted'),
(2, 1, 'react', '../uploads/1760698092_testmysql.php', '2025-10-17 16:18:12', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `trainer`
--

DROP TABLE IF EXISTS `trainer`;
CREATE TABLE IF NOT EXISTS `trainer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trainer`
--

INSERT INTO `trainer` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'subisha', 'subisha@gmail.com', '12345', 'trainer'),
(2, 'John Doe', 'suryapcs91@gmail.com', '12345', 'trainer'),
(3, 'subisha', 'test@gmail.com', '12345', 'trainer');

-- --------------------------------------------------------

--
-- Table structure for table `training_rooms`
--

DROP TABLE IF EXISTS `training_rooms`;
CREATE TABLE IF NOT EXISTS `training_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(100) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `student1_id` int(11) DEFAULT NULL,
  `student2_id` int(11) DEFAULT NULL,
  `student3_id` int(11) DEFAULT NULL,
  `student4_id` int(11) DEFAULT NULL,
  `student5_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `training_rooms`
--

INSERT INTO `training_rooms` (`id`, `course_name`, `trainer_id`, `student1_id`, `student2_id`, `student3_id`, `student4_id`, `student5_id`, `created_at`) VALUES
(1, 'JavaScript', 2, 1, 2, 3, 4, 5, '2025-10-17 05:37:09'),
(2, 'Angular', 2, 1, 2, 3, 4, 5, '2025-10-17 07:03:56'),
(3, 'Angular', 2, 2, 3, 4, NULL, NULL, '2025-10-23 06:47:53'),
(4, 'Angular', 1, 1, 4, NULL, NULL, NULL, '2025-10-23 06:48:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` enum('admin','trainer','student') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin@gmail.com', '12345', 'admin');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk_task_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `student` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

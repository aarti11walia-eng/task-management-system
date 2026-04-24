-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2026 at 08:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `priority` enum('Low','Medium','High') DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `category`) VALUES
(3, 2, 'Complete Project Documentation', 'Write complete documentation including system architecture, features, and screenshots', 'Pending', 'High', '2026-04-30', 'study'),
(14, 1, 'doc', '', 'Pending', 'Low', '0000-00-00', ''),
(15, 1, 'copy', '', 'Pending', 'Low', '0000-00-00', ''),
(16, 3, 'eeeee', 'frfrgggrggrrgrgrgrgrgrgg', 'Completed', 'Low', '2026-04-21', 'ddddd'),
(17, 3, 'eeeee', 'frfrgggrggrrgrgrgrgrgrgg', 'Pending', 'Medium', '2026-04-21', 'ddddd'),
(18, 3, 'eeeee', 'frfrgggrggrrgrgrgrgrgrgg', 'Pending', 'Medium', '2026-04-21', 'ddddd'),
(19, 10, 'complete frontend', '', 'Completed', 'Low', '0000-00-00', ''),
(20, 10, 'complete backend', '\r\n\r\n\r\n\r\n', 'Completed', 'Medium', '2026-06-05', ''),
(21, 10, 'complete backend', '\r\n\r\n\r\nbackend should include phpmyadmin\r\n', 'Pending', 'Medium', '2026-06-05', 'work');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'aarti', 'aarti11@gmail.com', '$2y$10$2.XzFL3450AY7cRH4Sb87.QiDZxeFaZSp.wy/PXaG3KeKuiweG9ne', 'user'),
(2, 'prachi', 'prachi@gmail.com', '$2y$10$lgbI75NUHAMoSSFY5zDXle7aPG25NE877I1BZtwCbO/R1MsTHAcVy', 'user'),
(3, 'shubham', 'koundal@gmail.com', '$2y$10$zAk7TOUFhN8HS/eoEil1/uZuhXlsNhvYfx44JA3OB7jEEVWSpDe..', 'user'),
(6, 'Admin', 'admin@gmail.com', '$2y$10$vacu3rVPQplqd7Ut77v5gemCXu5aEB6oVsYL0qlAYgPLjcxlEvfEe', 'admin'),
(10, 'kartik', 'kar@gmail.com', '$2y$10$QYa61qAMKnL9USdZPan09OInbRGEbUTpYsexZhSWurOKg8dWp4gm.', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

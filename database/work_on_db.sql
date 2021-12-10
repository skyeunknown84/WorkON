-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2021 at 02:10 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `work_on_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `group_list`
--

CREATE TABLE `group_list` (
  `id` int(30) NOT NULL,
  `project_id` int(30) NOT NULL,
  `group_name` text NOT NULL,
  `member` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `member_id` int(30) NOT NULL,
  `user_ids` text NOT NULL,
  `date_created` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `project_list`
--

CREATE TABLE `project_list` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `manager_id` int(30) NOT NULL,
  `user_ids` text NOT NULL,
  `project_url` longtext NOT NULL,
  `project_time_sheet` longtext NOT NULL,
  `project_files` longtext NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_list`
--

INSERT INTO `project_list` (`id`, `name`, `description`, `status`, `start_date`, `end_date`, `manager_id`, `user_ids`, `project_url`, `project_time_sheet`, `project_files`, `date_created`) VALUES
(21, 'Project One', '																Proj One														', 1, '2021-12-10', '2021-12-17', 17, '18,20', 'https://docs.google.com/spreadsheets/u/0/', 'https://docs.google.com/spreadsheets/u/0/', '', '2021-12-10 05:54:59'),
(22, 'Project Two', '								Proj Two							', 1, '2021-12-10', '2021-12-17', 16, '21,19', 'https://docs.google.com/spreadsheets/u/0/', 'https://docs.google.com/spreadsheets/u/0/', '', '2021-12-10 05:58:48'),
(23, 'Project Three', '								Proj Three							', 1, '2021-12-10', '2021-12-24', 16, '18,21,22', 'https://docs.google.com/spreadsheets/u/0/', 'https://docs.google.com/spreadsheets/u/0/', '', '2021-12-10 06:14:10'),
(24, 'Project Four', 'Proj Four', 1, '2021-12-10', '2021-12-31', 16, '18,20,21,19,22', 'https://docs.google.com/spreadsheets/u/0/', 'https://docs.google.com/spreadsheets/u/0/', '', '2021-12-10 06:51:29'),
(25, 'Project Five', 'Project Five', 1, '2021-12-10', '2022-01-07', 17, '18,20,21,19,22', 'https://docs.google.com/spreadsheets/u/0/', 'https://docs.google.com/spreadsheets/u/0/', '', '2021-12-10 07:11:07');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'WorkON', 'info@workon.com', '+6397701234567', '1111 New Build Avenue Drive Street Working Hub City, Philippines', 'work-on-logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `task_list`
--

CREATE TABLE `task_list` (
  `id` int(30) NOT NULL,
  `project_id` int(30) NOT NULL,
  `task` varchar(200) NOT NULL,
  `task_owner` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_uploaded` longtext NOT NULL,
  `task_url` longtext NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task_list`
--

INSERT INTO `task_list` (`id`, `project_id`, `task`, `task_owner`, `description`, `file_uploaded`, `task_url`, `status`, `date_created`) VALUES
(21, 25, 'Task 1', 'Ellie Pal', 'Task No. 1', '', '', 1, '2021-12-10 07:28:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1 = admin, 2 = staff',
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `type`, `avatar`, `date_created`) VALUES
(1, 'Administrator', ' ', 'admin@workon.com', '0192023a7bbd73250516f069df18b500', 1, '1639091160_144119.jpg', '2021-12-10 05:16:23'),
(16, 'Jane', 'Smith', 'janesmith@workon.com', '9172a26354c4073ced007b01b3ac5b1f', 2, '1639085100_PM_1.PNG', '2021-12-10 05:25:32'),
(17, 'Jon', 'Smith', 'jonsmith@workon.com', '13188d067cb373f775e0c452c42bc177', 2, '1639085220_PM.PNG', '2021-12-10 05:27:32'),
(18, 'Adam', 'Doe', 'adamdoe@workon.com', '51e4b4360b146d70f1c8f457be19497e', 3, '1639085580_EMP_1.jfif', '2021-12-10 05:33:05'),
(19, 'Ethan', 'End', 'ethanend@workon.com', '423f2413ede9d1207cc40377c64b4c2f', 3, '1639085640_EMP_2.jpg', '2021-12-10 05:34:24'),
(20, 'Alice', 'Doe', 'alicedoe@workon.com', '0c71b853e16aa031265abd08f94024e7', 3, '1639085820_EMP_3.jfif', '2021-12-10 05:37:55'),
(21, 'Ellie', 'Pal', 'elliepal@workon.com', '4c8a8df57b4f393623abbf4eb916720d', 3, '1639085940_EMP_4.jpg', '2021-12-10 05:39:00'),
(22, 'Sage', 'Del', 'sagedel@workon.com', '73a7ac02d64efa7c3b177c8581a2ad53', 3, '1639086000_EMP_5.jpg', '2021-12-10 05:40:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_productivity`
--

CREATE TABLE `user_productivity` (
  `id` int(30) NOT NULL,
  `project_id` int(30) NOT NULL,
  `task_id` int(30) NOT NULL,
  `comment` text NOT NULL,
  `subject` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `user_id` int(30) NOT NULL,
  `file_uploaded` longtext NOT NULL,
  `url_productivity` longtext NOT NULL,
  `status` tinyint(4) NOT NULL,
  `time_rendered` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_productivity`
--

INSERT INTO `user_productivity` (`id`, `project_id`, `task_id`, `comment`, `subject`, `date`, `start_time`, `end_time`, `user_id`, `file_uploaded`, `url_productivity`, `status`, `time_rendered`, `date_created`) VALUES
(23, 25, 21, '&lt;ul&gt;&lt;li&gt;Add more icons for each buttons&lt;/li&gt;&lt;li&gt;Add more details to design&lt;/li&gt;&lt;/ul&gt;', 'For Task 1 - Initial Update', '0000-00-00', '08:58:00', '09:58:00', 1, '', 'https://docs.google.com/spreadsheets/u/0/', 0, 1, '2021-12-10 08:59:34'),
(24, 25, 21, '&lt;ul&gt;&lt;li&gt;Apply URL Links&lt;/li&gt;&lt;li&gt;Responsiveness of UI&lt;/li&gt;&lt;/ul&gt;', 'For Task 1 - Final Update', '2021-12-10', '00:00:00', '02:00:00', 1, '', 'https://docs.google.com/spreadsheets/u/0/', 0, 2, '2021-12-10 09:01:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `group_list`
--
ALTER TABLE `group_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_list`
--
ALTER TABLE `project_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_list`
--
ALTER TABLE `task_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_productivity`
--
ALTER TABLE `user_productivity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `group_list`
--
ALTER TABLE `group_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_list`
--
ALTER TABLE `project_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `task_list`
--
ALTER TABLE `task_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_productivity`
--
ALTER TABLE `user_productivity`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

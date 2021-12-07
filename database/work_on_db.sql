-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2021 at 12:50 AM
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
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_list`
--

INSERT INTO `project_list` (`id`, `name`, `description`, `status`, `start_date`, `end_date`, `manager_id`, `user_ids`, `date_created`) VALUES
(9, 'Christmas Party', '																														List anything activities and needed stuff for the Annual Christmas Party!																									', 1, '2021-12-02', '2021-12-25', 9, '6', '2021-12-02 19:13:42'),
(10, 'Christmas Movies', '												&lt;p&gt;List All Holidays Movie Marathons (2019-2021)&lt;/p&gt;										', 1, '2021-12-04', '2021-12-04', 9, '10,12,11,6', '2021-12-02 22:10:15'),
(11, 'Work-On: A Task Management System', 'Create A Baby Tasks', 1, '2021-12-07', '2021-12-09', 9, '10,12,11', '2021-12-07 05:33:45');

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
  `status` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task_list`
--

INSERT INTO `task_list` (`id`, `project_id`, `task`, `task_owner`, `description`, `file_uploaded`, `status`, `date_created`) VALUES
(10, 9, 'Christmas Gifts', 'Adam', '&lt;ul&gt;&lt;li&gt;Monito -&amp;gt; Monita&lt;/li&gt;&lt;li&gt;Monita -&amp;gt; Monito&lt;/li&gt;&lt;/ul&gt;', '', 1, '2021-12-02 19:48:26'),
(11, 10, 'Christmas Party', 'Ethan', '							', '', 1, '2021-12-02 23:29:06'),
(12, 9, 'Christmas Dinner', 'Ellie', '							', '', 1, '2021-12-02 23:43:39'),
(13, 11, 'Make A Responsive UI in Login and Register via Bootstrap', 'Adam', 'Tasd', '', 1, '2021-12-07 05:37:02'),
(14, 10, 'Birthday Party', 'Ellie', '&lt;p&gt;Daddy&amp;#x2019;s Bday&lt;/p&gt;&lt;p&gt;Auntie&amp;#x2019;s Bday&lt;/p&gt;', '', 1, '2021-12-08 06:10:46');

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
(1, 'Administrator', '', 'admin@workon.com', '0192023a7bbd73250516f069df18b500', 1, '1638257340_rocky-sea-bg.PNG', '2020-11-26 10:57:04'),
(6, 'Nancy', 'Drew', 'nancydrew@workon.com', 'f4f0d1165fbf5ddc5fbf4c214af9f2b2', 3, '1636958700_Screenshot (1).png', '2021-11-15 14:45:39'),
(8, 'Jen', 'Del', 'jendel@workon.com', '0ed3d2694dbb0fb3a8e75b5b5ac80cf6', 2, '1636965480_IMG_20180825_134618.jpg', '2021-11-15 16:38:52'),
(9, 'bea', 'gal', 'beagal@workon.com', '371b3ac948d5928e52206be791de78f3', 2, '1638429540_Ellie-7th-Months.jpg', '2021-12-02 15:19:03'),
(10, 'Adam', 'Dom', 'adam@workon.com', '3e7b522b9756d2578d3a86d8f366be6e', 3, '1638444480_IMG_20200909_162649.jpg', '2021-12-02 19:28:04'),
(11, 'Ethan', 'end', 'ethan@wotkon.com', '95b82c0376b58d81b9a7283be2ead91c', 3, '1638444600_Ethan-1st-BDay.png', '2021-12-02 19:30:12'),
(12, 'Ellie', 'Pal', 'ellie@workon.com', 'ce33397f0e2fdb6e5c7e83da88bf823f', 3, '1638444660_Ellie-7th-Months.jpg', '2021-12-02 19:31:13');

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
  `status` tinyint(4) NOT NULL,
  `time_rendered` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `task_list`
--
ALTER TABLE `task_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_productivity`
--
ALTER TABLE `user_productivity`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

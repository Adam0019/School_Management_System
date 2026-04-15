-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Apr 15, 2026 at 05:06 PM
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
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_tbl`
--

CREATE TABLE `category_tbl` (
  `cat_id` int(20) NOT NULL,
  `cat_name` varchar(300) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_tbl`
--

INSERT INTO `category_tbl` (`cat_id`, `cat_name`, `created_at`, `updated_at`) VALUES
(1, 'Under Graduate (UG)', '2026-03-16 16:43:55', '2026-03-16 16:43:55');

-- --------------------------------------------------------

--
-- Table structure for table `class_tbl`
--

CREATE TABLE `class_tbl` (
  `c_id` int(200) NOT NULL,
  `c_name` varchar(200) NOT NULL,
  `sub_one` varchar(300) DEFAULT NULL,
  `sub_id` int(20) NOT NULL,
  `sub_two` varchar(300) DEFAULT NULL,
  `sub_three` varchar(300) DEFAULT NULL,
  `sub_four` varchar(300) DEFAULT NULL,
  `sub_five` varchar(300) DEFAULT NULL,
  `sub_six` varchar(300) DEFAULT NULL,
  `sub_seven` varchar(300) DEFAULT NULL,
  `sub_eight` varchar(300) DEFAULT NULL,
  `sub_nine` varchar(300) DEFAULT NULL,
  `section_a` varchar(20) DEFAULT NULL,
  `section_b` varchar(20) DEFAULT NULL,
  `section_c` varchar(20) DEFAULT NULL,
  `section_d` varchar(20) DEFAULT NULL,
  `c_img` varchar(300) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_tbl`
--

INSERT INTO `class_tbl` (`c_id`, `c_name`, `sub_one`, `sub_id`, `sub_two`, `sub_three`, `sub_four`, `sub_five`, `sub_six`, `sub_seven`, `sub_eight`, `sub_nine`, `section_a`, `section_b`, `section_c`, `section_d`, `c_img`, `created_at`, `updated_at`) VALUES
(1, 'Class Five', '1', 1, '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'class_1773846895_1459c066.jpeg', '2026-03-18 15:14:55', '2026-03-23 09:32:09'),
(2, 'Class Six', '1', 1, '3', '4', '5', '9', '6', '8', '7', '2', 'Section A', 'Section B', 'Section C', 'Section D', 'class_1774259908_1ad1eadb.jpeg', '2026-03-23 09:58:28', '2026-03-23 09:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `doc_tbl`
--

CREATE TABLE `doc_tbl` (
  `doc_id` int(20) NOT NULL,
  `doc_title` varchar(300) NOT NULL,
  `doc_t_id` int(20) NOT NULL,
  `author` varchar(300) NOT NULL,
  `doc_cat_id` int(20) NOT NULL,
  `cat_name` varchar(300) NOT NULL,
  `sub_cat_id` int(20) NOT NULL,
  `sub_cat_name` varchar(300) NOT NULL,
  `dop` date NOT NULL,
  `doc_about` varchar(300) NOT NULL,
  `doc_img` varchar(300) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doc_tbl`
--

INSERT INTO `doc_tbl` (`doc_id`, `doc_title`, `doc_t_id`, `author`, `doc_cat_id`, `cat_name`, `sub_cat_id`, `sub_cat_name`, `dop`, `doc_about`, `doc_img`, `created_at`, `updated_at`) VALUES
(1, 'Results of the mid-term exams (class - 7 to 8)', 5, '', 1, '', 1, '', '2026-03-20', '<p>yrkdtyddtufjfj</p>', 'article_default.jpg', '2026-03-21 12:18:10', '2026-03-21 12:18:10');

-- --------------------------------------------------------

--
-- Table structure for table `otp_tbl`
--

CREATE TABLE `otp_tbl` (
  `otp_id` int(20) NOT NULL,
  `u_id` int(20) DEFAULT NULL,
  `t_id` int(20) DEFAULT NULL,
  `otp_sts` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token` int(6) NOT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_tbl`
--

INSERT INTO `otp_tbl` (`otp_id`, `u_id`, `t_id`, `otp_sts`, `verification_token`, `created_at`, `expires_at`) VALUES
(72, 17, NULL, 1, 966113, '2026-04-15 20:26:50', '2026-04-15 20:27:11');

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `s_id` int(200) NOT NULL,
  `s_name` varchar(200) NOT NULL,
  `s_gender` varchar(20) NOT NULL,
  `s_dob` date NOT NULL,
  `s_g_name` varchar(200) NOT NULL,
  `s_g_type` varchar(20) NOT NULL,
  `c_id` int(20) NOT NULL,
  `s_class` varchar(20) NOT NULL,
  `s_roll` varchar(20) NOT NULL,
  `s_section` varchar(25) NOT NULL,
  `s_address` varchar(200) NOT NULL,
  `s_img` varchar(250) NOT NULL,
  `s_phone` varchar(20) NOT NULL,
  `s_institute` varchar(200) NOT NULL,
  `s_registration` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `s_status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`s_id`, `s_name`, `s_gender`, `s_dob`, `s_g_name`, `s_g_type`, `c_id`, `s_class`, `s_roll`, `s_section`, `s_address`, `s_img`, `s_phone`, `s_institute`, `s_registration`, `username`, `password`, `s_status`, `created_at`, `updated_at`) VALUES
(3, 'Shankhadeep Pathak', 'Male', '2004-05-23', 'Pinakibhusan Pathak', 'Father', 1, 'Class Five', '68', 'A', 'jfAo', '1774259481_571315083_1378719526952072_3014442542158112577_n.jpg', '9654682758', '', '', 'Class Five_68', '$2y$10$JcdXOFuHtBehBFVN8TzDz.Ox5PzH31/0IPHXGlM.MA4vCWPKiImtC', NULL, '2026-03-23 09:51:21', '2026-03-23 09:51:21'),
(4, 'Aniruddha Mallick', 'Male', '2002-08-24', 'Gautam Mallick', 'Father', 2, 'Class Six', '64', 'Section B', 'hivdhixi', 'male_default.jpeg', '9654682758', '', '', 'Class Six_64', '$2y$10$2FivLDufO9aV2tpzV/Y6Bu0iQg0/aM6jNkfvDfjb5suWJ796QX192', NULL, '2026-03-23 09:59:36', '2026-03-23 09:59:36'),
(5, 'Amit Sharma', 'Male', '2012-05-14', 'Rajesh Sharma', 'Father', 1, 'Five', '1', 'A', 'Kolkata, WB', '', '9876543210', '', '', '', '', NULL, '2026-03-31 19:19:36', '2026-03-31 19:19:36'),
(6, 'Priya Das', 'Female', '2013-08-21', 'Sunita Das', 'Mother', 1, 'Five', '2', 'A', 'Durgapur, WB', '', '9123456780', '', '', '', '', NULL, '2026-03-31 19:19:36', '2026-03-31 19:19:36'),
(7, 'Rahul Roy', 'Male', '2011-12-02', 'Sanjay Roy', 'Father', 2, 'Six', '3', 'B', 'Asansol, WB', '', '9988776655', '', '', '', '', NULL, '2026-03-31 19:19:36', '2026-03-31 19:19:36'),
(8, 'Sneha Sen', 'Female', '2012-03-18', 'Anita Sen', 'Mother', 2, 'Six', '4', 'B', 'Bardhaman, WB', '', '9090909090', '', '', '', '', NULL, '2026-03-31 19:19:36', '2026-03-31 19:19:36'),
(9, 'Arjun Gupta', 'Male', '2013-07-09', 'Manoj Gupta', 'Father', 1, 'Five', '5', 'C', 'Howrah, WB', '', '9345678901', '', '', '', '', NULL, '2026-03-31 19:19:36', '2026-03-31 19:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `subject_tbl`
--

CREATE TABLE `subject_tbl` (
  `sub_id` int(200) NOT NULL,
  `sub_name` varchar(200) NOT NULL,
  `sub_about` varchar(300) NOT NULL,
  `sub_img` varchar(300) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_tbl`
--

INSERT INTO `subject_tbl` (`sub_id`, `sub_name`, `sub_about`, `sub_img`, `created_at`, `updated_at`) VALUES
(1, 'History', 'History is the study of past events, people, and civilizations.\r\nIt helps us understand how societies developed over time and how the past influences the present and future.', '1773846229_history.jpg', '2026-03-18 15:03:49', '2026-03-18 15:03:49'),
(2, 'Chemistry', 'Chemistry is the study of matter, its properties, and how substances react and change.\r\nIt helps us understand the composition of materials and the chemical processes that occur in everyday life.', 'subject_2_1776263055.jpg', '2026-03-18 15:04:17', '2026-04-15 14:24:15'),
(3, 'Maths', 'Mathematics (Maths) is the study of numbers, shapes, patterns, and logical reasoning.\r\nIt helps us understand and solve problems related to quantity, structure, space, and change in everyday life and science.', '1773846284_math.jpg', '2026-03-18 15:04:44', '2026-03-18 15:04:44'),
(4, 'Physics', 'Physics is the study of matter, energy, force, and motion in the universe.\r\nIt explains how and why objects move and interact, from tiny atoms to large planets.', '1773846421_physics.jpg', '2026-03-18 15:07:01', '2026-03-18 15:07:01'),
(5, 'Chemistry 2', 'Chemistry 2 is the study of matter, its properties, and how substances react and change.\r\nIt helps us understand the composition of materials and the chemical processes that occur in everyday life.', '1773846507_chemistry.jpg', '2026-03-18 15:08:27', '2026-03-18 15:08:27'),
(6, 'History 2', 'History is the study of past events, people, and civilizations.\r\nIt helps us understand how societies developed over time and how the past influences the present and future.', '1773846532_history.jpg', '2026-03-18 15:08:52', '2026-03-18 15:08:52'),
(7, 'Physics 2', 'Physics is the study of matter, energy, force, and motion in the universe.\r\nIt explains how and why objects move and interact, from tiny atoms to large planets.', '1773846572_physics.jpg', '2026-03-18 15:09:32', '2026-03-18 15:09:32'),
(8, 'Maths 2', 'Mathematics (Maths) is the study of numbers, shapes, patterns, and logical reasoning.\r\nIt helps us understand and solve problems related to quantity, structure, space, and change in everyday life and science.', '1773846605_math.jpg', '2026-03-18 15:10:05', '2026-03-18 15:10:05'),
(9, 'Chemistry 3', 'Chemistry is the study of matter, its properties, and how substances react and change.\r\nIt helps us understand the composition of materials and the chemical processes that occur in everyday life.', '1773846645_chemistry.jpg', '2026-03-18 15:10:45', '2026-03-18 15:10:45');

-- --------------------------------------------------------

--
-- Table structure for table `sub_category_tbl`
--

CREATE TABLE `sub_category_tbl` (
  `sub_cat_id` int(20) NOT NULL,
  `sub_cat_name` varchar(300) NOT NULL,
  `cat_id` int(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_category_tbl`
--

INSERT INTO `sub_category_tbl` (`sub_cat_id`, `sub_cat_name`, `cat_id`, `created_at`, `updated_at`) VALUES
(1, 'Marksheet', 1, '2026-03-16 16:44:11', '2026-03-16 16:44:11');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_tbl`
--

CREATE TABLE `teacher_tbl` (
  `t_id` int(200) NOT NULL,
  `t_name` varchar(200) NOT NULL,
  `t_email` varchar(200) NOT NULL,
  `t_phone` varchar(200) NOT NULL,
  `t_img` varchar(250) NOT NULL,
  `t_about` int(11) NOT NULL,
  `t_address` varchar(200) NOT NULL,
  `t_c_id` int(20) NOT NULL,
  `t_class` varchar(20) NOT NULL,
  `t_sub_id` int(20) NOT NULL,
  `t_subject_main` varchar(200) NOT NULL,
  `t_subject_sec_1` varchar(300) NOT NULL,
  `t_subject_sec_2` varchar(300) NOT NULL,
  `t_gender` varchar(20) NOT NULL,
  `t_dob` date NOT NULL,
  `t_institute` varchar(200) NOT NULL,
  `t_key` int(6) NOT NULL,
  `t_role` varchar(20) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_tbl`
--

INSERT INTO `teacher_tbl` (`t_id`, `t_name`, `t_email`, `t_phone`, `t_img`, `t_about`, `t_address`, `t_c_id`, `t_class`, `t_sub_id`, `t_subject_main`, `t_subject_sec_1`, `t_subject_sec_2`, `t_gender`, `t_dob`, `t_institute`, `t_key`, `t_role`, `username`, `password`, `created_at`, `updated_at`) VALUES
(5, 'Kaustab Sadhu', 'testsms047@gmail.com', '8789857850', '1773990263_download (4).jpg', 0, 'rdtfdjfhkgjhkj', 1, '1', 4, '4', '2', '3', 'Male', '2001-03-23', '', 274824, 'Class Teacher', 'testsms047@gmail.com', '$2y$10$QallykZTyYL5M2yw8pmKMufJ4CfkrMQazSdjQWl5DXL8OMgiAde8m', '2026-03-20 07:04:24', '2026-03-20 07:04:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `u_id` int(200) NOT NULL,
  `u_name` varchar(200) NOT NULL,
  `u_email` varchar(200) NOT NULL,
  `u_key` int(6) NOT NULL,
  `u_img` varchar(200) NOT NULL,
  `u_about` varchar(300) NOT NULL,
  `u_address` varchar(250) NOT NULL,
  `u_phone` bigint(20) NOT NULL,
  `u_gender` varchar(10) DEFAULT NULL,
  `username` varchar(450) NOT NULL,
  `password` varchar(450) NOT NULL,
  `role` varchar(450) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`u_id`, `u_name`, `u_email`, `u_key`, `u_img`, `u_about`, `u_address`, `u_phone`, `u_gender`, `username`, `password`, `role`, `created_at`, `update_at`) VALUES
(1, 'Vasudev Shree Krishna', 'testsms047@gmail.com', 759515, '', '', '', 0, NULL, 'testsms047@gmail.com', 'test0001', 'admin', '2025-05-13 13:44:45', '2026-02-01 15:41:54'),
(17, 'Vasudev Shree Krishna', 'aniruddhajeshu02@gmail.com', 611345, '1773053650_Screenshot 2025-12-10 010758.png', 'GOD', 'Baikunth Dham ', 9609870713, 'Male', 'aniruddhajeshu02@gmail.com', '$2y$10$uKRD4ejZEnRJWNpfU//KAufp6ZIChwuu6wMIsX.poNZ767ftRkJJG', 'Admin', '2025-12-22 15:08:58', '2026-04-15 14:56:13'),
(18, 'Amina Khatun', 'aminakhatunabdul@gmail.com', 754500, '', '', '', 0, NULL, 'aminakhatunabdul@gmail.com', '$2y$10$YNaGyQW.frVaB2Yu8poJPucBM36TDpVE2wUjbw.mOPEpU.BTEHV3q', '', '2026-01-06 08:15:34', '2026-01-06 08:15:34'),
(20, 'Anirudhha Mallick', 'aniruddhamallick2021@gmail.com', 426718, '', '', '', 0, NULL, 'aniruddhamallick2021@gmail.com', '$2y$10$PV/cUCZthH3S2VVRKQfXbeRmBNB3qLJ4/Tw4PWEbZEpU6vyUeD./q', '', '2026-01-13 17:08:57', '2026-02-05 07:38:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_tbl`
--
ALTER TABLE `category_tbl`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `class_tbl`
--
ALTER TABLE `class_tbl`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `sub_id` (`sub_id`);

--
-- Indexes for table `doc_tbl`
--
ALTER TABLE `doc_tbl`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `doc_t_id` (`doc_t_id`),
  ADD KEY `doc_cat_id` (`doc_cat_id`),
  ADD KEY `sub_cat_id` (`sub_cat_id`);

--
-- Indexes for table `otp_tbl`
--
ALTER TABLE `otp_tbl`
  ADD PRIMARY KEY (`otp_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `t_id` (`t_id`);

--
-- Indexes for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD PRIMARY KEY (`s_id`),
  ADD KEY `c_id` (`c_id`);

--
-- Indexes for table `subject_tbl`
--
ALTER TABLE `subject_tbl`
  ADD PRIMARY KEY (`sub_id`);

--
-- Indexes for table `sub_category_tbl`
--
ALTER TABLE `sub_category_tbl`
  ADD PRIMARY KEY (`sub_cat_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `teacher_tbl`
--
ALTER TABLE `teacher_tbl`
  ADD PRIMARY KEY (`t_id`),
  ADD KEY `t_c_id` (`t_c_id`),
  ADD KEY `t_sub_id` (`t_sub_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_tbl`
--
ALTER TABLE `category_tbl`
  MODIFY `cat_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `class_tbl`
--
ALTER TABLE `class_tbl`
  MODIFY `c_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doc_tbl`
--
ALTER TABLE `doc_tbl`
  MODIFY `doc_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `otp_tbl`
--
ALTER TABLE `otp_tbl`
  MODIFY `otp_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `student_tbl`
--
ALTER TABLE `student_tbl`
  MODIFY `s_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subject_tbl`
--
ALTER TABLE `subject_tbl`
  MODIFY `sub_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sub_category_tbl`
--
ALTER TABLE `sub_category_tbl`
  MODIFY `sub_cat_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teacher_tbl`
--
ALTER TABLE `teacher_tbl`
  MODIFY `t_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `u_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_tbl`
--
ALTER TABLE `class_tbl`
  ADD CONSTRAINT `sub_id` FOREIGN KEY (`sub_id`) REFERENCES `subject_tbl` (`sub_id`) ON DELETE CASCADE;

--
-- Constraints for table `doc_tbl`
--
ALTER TABLE `doc_tbl`
  ADD CONSTRAINT `doc_cat_id` FOREIGN KEY (`doc_cat_id`) REFERENCES `category_tbl` (`cat_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doc_t_id` FOREIGN KEY (`doc_t_id`) REFERENCES `teacher_tbl` (`t_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sub_cat_id` FOREIGN KEY (`sub_cat_id`) REFERENCES `sub_category_tbl` (`sub_cat_id`) ON DELETE CASCADE;

--
-- Constraints for table `otp_tbl`
--
ALTER TABLE `otp_tbl`
  ADD CONSTRAINT `t_id` FOREIGN KEY (`t_id`) REFERENCES `teacher_tbl` (`t_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `u_id` FOREIGN KEY (`u_id`) REFERENCES `user_tbl` (`u_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD CONSTRAINT `c_id` FOREIGN KEY (`c_id`) REFERENCES `class_tbl` (`c_id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_category_tbl`
--
ALTER TABLE `sub_category_tbl`
  ADD CONSTRAINT `cat_id` FOREIGN KEY (`cat_id`) REFERENCES `category_tbl` (`cat_id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_tbl`
--
ALTER TABLE `teacher_tbl`
  ADD CONSTRAINT `t_c_id` FOREIGN KEY (`t_c_id`) REFERENCES `class_tbl` (`c_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_sub_id` FOREIGN KEY (`t_sub_id`) REFERENCES `subject_tbl` (`sub_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

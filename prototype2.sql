-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2026 at 05:58 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prototype`
--

-- --------------------------------------------------------

--
-- Table structure for table `las_scores`
--

CREATE TABLE `las_scores` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `las_1` varchar(2) DEFAULT '',
  `las_2` varchar(2) DEFAULT '',
  `las_3` varchar(2) DEFAULT '',
  `las_4` varchar(2) DEFAULT '',
  `las_5` varchar(2) DEFAULT '',
  `las_6` varchar(2) DEFAULT '',
  `las_7` varchar(2) DEFAULT '',
  `las_8` varchar(2) DEFAULT '',
  `las_9` varchar(2) DEFAULT '',
  `las_10` varchar(2) DEFAULT '',
  `las_11` varchar(2) DEFAULT '',
  `las_12` varchar(2) DEFAULT '',
  `las_13` varchar(2) DEFAULT '',
  `las_14` varchar(2) DEFAULT '',
  `las_15` varchar(2) DEFAULT '',
  `las_16` varchar(2) DEFAULT '',
  `las_17` varchar(2) DEFAULT '',
  `las_18` varchar(2) DEFAULT '',
  `las_19` varchar(2) DEFAULT '',
  `las_20` varchar(2) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `las_scores`
--

INSERT INTO `las_scores` (`id`, `student_id`, `subject_id`, `teacher_id`, `las_1`, `las_2`, `las_3`, `las_4`, `las_5`, `las_6`, `las_7`, `las_8`, `las_9`, `las_10`, `las_11`, `las_12`, `las_13`, `las_14`, `las_15`, `las_16`, `las_17`, `las_18`, `las_19`, `las_20`) VALUES
(1, 10, 52, 2, '/', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(2, 11, 52, 2, '', '/', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(3, 10, 3, 2, '/', '', '/', '/', '/', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(4, 11, 3, 2, '', '', '', '/', '', '', '', '', '', '/', '', '', '', '/', '', '', '', '', '', ''),
(9, 30, 3, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(13, 16, 3, 2, '', '/', '/', '', '/', '', '', '', '/', '', '', '', '', '', '', '', '', '', '', ''),
(14, 17, 3, 2, '', '', '/', '', '', '', '/', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(15, 0, 1, 2, '/', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(25, 56, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(26, 60, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(27, 51, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(28, 52, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(29, 59, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(30, 55, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(31, 53, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(32, 54, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(33, 10, 1, 2, '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/', '/'),
(34, 57, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(35, 58, 1, 2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `strand_id` int(11) DEFAULT NULL,
  `section_name` varchar(50) NOT NULL,
  `grade_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `strand_id`, `section_name`, `grade_level`) VALUES
(1, NULL, 'simplicity', 11),
(2, NULL, 'stewardship', 11),
(3, NULL, 'integrity', 11),
(4, NULL, 'competence', 11),
(5, NULL, 'courage', 11),
(6, NULL, 'compassion', 11),
(7, NULL, 'diligence', 11),
(8, NULL, 'family spirit', 11),
(9, NULL, 'humility', 11),
(10, NULL, 'honesty', 11),
(11, NULL, 'wisdom', 11),
(12, NULL, 'justice', 11),
(13, NULL, 'kind', 11),
(14, NULL, 'excellence', 11),
(15, NULL, 'grit', 11),
(16, NULL, 'resilience', 11),
(17, NULL, 'cambodia', 12),
(18, NULL, 'philippines', 12),
(19, 2, 'indonesia', 12),
(20, NULL, 'vietnam', 12);

-- --------------------------------------------------------

--
-- Table structure for table `strands`
--

CREATE TABLE `strands` (
  `id` int(11) NOT NULL,
  `strand_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `strands`
--

INSERT INTO `strands` (`id`, `strand_name`) VALUES
(1, 'TVL-HE'),
(2, 'TVL-ICT'),
(3, 'STEM'),
(4, 'HUMSS'),
(5, 'ABM');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `type` enum('minor','major') NOT NULL DEFAULT 'minor',
  `semester` int(11) NOT NULL DEFAULT 1,
  `strand_id` int(11) DEFAULT NULL,
  `grade_level` int(11) DEFAULT 11
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `type`, `semester`, `strand_id`, `grade_level`) VALUES
(1, 'Introduction To The Philosophy Of Human Persons', 'minor', 1, NULL, 12),
(2, 'Physical Science', 'minor', 1, NULL, 12),
(3, 'Filipino sa Piling Larang', 'minor', 1, NULL, 12),
(4, '21st Century Literature from the Philippines and the World', 'minor', 1, NULL, 12),
(5, 'English for Academic and Professional Purposes', 'minor', 1, NULL, 12),
(6, 'Research in Daily Life 2', 'minor', 1, NULL, 12),
(7, 'Database Management Systems', 'major', 1, 2, 12),
(8, 'Object-Oriented Programming', 'major', 1, 2, 12),
(9, 'PE and Health 4', 'minor', 2, NULL, 12),
(10, 'Media Information Literacy', 'minor', 2, NULL, 12),
(11, 'Entrepreneurship', 'minor', 2, NULL, 12),
(12, 'Research Project', 'minor', 2, NULL, 12),
(13, 'Introduction to MySQL', 'major', 2, 2, 12),
(14, 'PHP Programming', 'major', 2, 2, 12);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teacher_subjects`
--

INSERT INTO `teacher_subjects` (`id`, `teacher_id`, `subject_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 49),
(4, 1, 50),
(5, 2, 3),
(6, 2, 4),
(7, 2, 51),
(8, 2, 52),
(9, 3, 5),
(10, 3, 6),
(11, 3, 53),
(12, 3, 54),
(13, 4, 7),
(14, 4, 8),
(15, 4, 55),
(16, 4, 56),
(17, 5, 9),
(18, 5, 10),
(19, 5, 57),
(20, 5, 58),
(21, 6, 11),
(22, 6, 12),
(23, 6, 59),
(24, 6, 60),
(25, 7, 13),
(26, 7, 14),
(27, 7, 61),
(28, 7, 62),
(29, 8, 15),
(30, 8, 16),
(31, 8, 63),
(32, 8, 64);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `student_id_number` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('teacher','user') NOT NULL DEFAULT 'user',
  `section_id` int(11) DEFAULT NULL,
  `profile_pix` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_id_number`, `username`, `email`, `password_hash`, `role`, `section_id`, `profile_pix`) VALUES
(1, NULL, '123', '123@gmail.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', NULL, 'default.png'),
(2, NULL, 'teacher_smith', 'smith@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'teacher', NULL, 'default.png'),
(3, NULL, 'teacher_jones', 'jones@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'teacher', NULL, 'default.png'),
(4, NULL, 'teacher_clark', 'clark@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'teacher', NULL, 'default.png'),
(5, NULL, 'teacher_brown', 'brown@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'teacher', NULL, 'default.png'),
(6, NULL, 'teacher_davis', 'davis@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'teacher', NULL, 'default.png'),
(7, NULL, 'teacher_miller', 'miller@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'teacher', NULL, 'default.png'),
(8, NULL, 'teacher_wilson', 'wilson@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'teacher', NULL, 'default.png'),
(9, NULL, 'teacher_taylor', 'taylor@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'teacher', NULL, 'default.png'),
(10, NULL, 'student_01', 's01@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 19, 'user_10_1770161990.jpg'),
(11, NULL, 'student_02', 's02@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 1, 'user_11_1770162332.jpg'),
(12, NULL, 'student_03', 's03@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 2, 'default.png'),
(13, NULL, 'student_04', 's04@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 2, 'default.png'),
(14, NULL, 'student_05', 's05@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 3, 'default.png'),
(15, NULL, 'student_06', 's06@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 3, 'default.png'),
(16, NULL, 'student_07', 's07@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 4, 'default.png'),
(17, NULL, 'student_08', 's08@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 4, 'default.png'),
(18, NULL, 'student_09', 's09@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 5, 'default.png'),
(19, NULL, 'student_10', 's10@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 5, 'default.png'),
(20, NULL, 'student_11', 's11@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 6, 'default.png'),
(21, NULL, 'student_12', 's12@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 6, 'default.png'),
(22, NULL, 'student_13', 's13@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 7, 'default.png'),
(23, NULL, 'student_14', 's14@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 7, 'default.png'),
(24, NULL, 'student_15', 's15@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 8, 'default.png'),
(25, NULL, 'student_16', 's16@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 8, 'default.png'),
(26, NULL, 'student_17', 's17@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 9, 'default.png'),
(27, NULL, 'student_18', 's18@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 9, 'default.png'),
(28, NULL, 'student_19', 's19@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 10, 'default.png'),
(29, NULL, 'student_20', 's20@school.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 10, 'default.png'),
(30, NULL, 'test', 'test@gmail.com', '$2y$10$52Fr93FuCA29XSrCBnVz9uFGkIfN/Es1Sew2kTI7q1t5I43DBdvPK', 'user', 1, 'default.png'),
(51, NULL, 'delacruz_j', 'j.delacruz@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png'),
(52, NULL, 'garcia_m', 'm.garcia@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png'),
(53, NULL, 'reyes_a', 'a.reyes@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png'),
(54, NULL, 'santos_l', 'l.santos@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png'),
(55, NULL, 'mendoza_p', 'p.mendoza@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png'),
(56, NULL, 'bautista_r', 'r.bautista@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png'),
(57, NULL, 'torres_k', 'k.torres@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png'),
(58, NULL, 'villanueva_s', 's.villanueva@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png'),
(59, NULL, 'lim_e', 'e.lim@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png'),
(60, NULL, 'castillo_d', 'd.castillo@example.com', '$2y$10$8sA2N5Sx/1zMQv2yrTDAaOFlbGWECrrgB68axL.hBb78NhQdyAqWm', 'user', 19, 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `las_scores`
--
ALTER TABLE `las_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_subject` (`student_id`,`subject_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `strand_id` (`strand_id`);

--
-- Indexes for table `strands`
--
ALTER TABLE `strands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `strand_id` (`strand_id`);

--
-- Indexes for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `section_id` (`section_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `las_scores`
--
ALTER TABLE `las_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `strands`
--
ALTER TABLE `strands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `teacher_subjects`
--
ALTER TABLE `teacher_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

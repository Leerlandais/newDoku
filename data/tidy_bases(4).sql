-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 13, 2026 at 09:42 PM
-- Server version: 10.11.19-MariaDB
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tidy_bases`
--

-- --------------------------------------------------------

--
-- Table structure for table `doku_connection_logs`
--

CREATE TABLE `doku_connection_logs` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `log_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `log_ip` varchar(512) NOT NULL,
  `log_page_visited` smallint(5) UNSIGNED NOT NULL,
  `log_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doku_dokus`
--

CREATE TABLE `doku_dokus` (
  `doku_id` smallint(5) UNSIGNED NOT NULL,
  `doku_title` varchar(1024) NOT NULL,
  `doku_url` varchar(1024) NOT NULL,
  `doku_setter_id` smallint(6) NOT NULL,
  `doku_date` datetime NOT NULL,
  `doku_duration` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doku_dokus`
--

INSERT INTO `doku_dokus` (`doku_id`, `doku_title`, `doku_url`, `doku_setter_id`, `doku_date`, `doku_duration`) VALUES
(1, 'The Tulip', 'https://crackingthecryptic.com/sudoku?id=2004', 1, '2026-09-06 08:58:54', 30),
(2, 'The Ant(i) Farm', 'https://sudokupad.app/3yyqe1we8y', 2, '2026-09-06 09:31:09', 36),
(3, 'Six Rules, Four Clues', 'https://crackingthecryptic.com/sudoku?id=2057', 3, '2026-09-06 09:37:19', 45),
(4, 'Sir Brian', 'https://crackingthecryptic.com/sudoku?id=2097', 4, '2026-09-06 15:24:53', 35),
(5, 'Double Counting', 'https://sudokupad.app/james-sinclair/double-counting', 5, '2026-09-07 05:10:28', 23),
(6, 'Big Bang', 'https://crackingthecryptic.com/sudoku?id=2101', 1, '2026-09-07 06:47:28', 18),
(7, 'Kropki DotS', 'https://crackingthecryptic.com/sudoku?id=2099', 6, '2026-09-07 11:26:35', 22),
(8, 'Weird Bug', 'https://crackingthecryptic.com/sudoku?id=2102', 7, '2026-09-07 12:07:41', 38),
(9, 'Ontario', 'https://sudokupad.app/james-sinclair/ontario', 5, '2026-09-07 12:46:21', 35),
(10, 'Rail Slide', 'https://sudokupad.app/james-sinclair/rail-slide', 5, '2026-09-07 13:15:37', 25),
(11, 'Pressure Packed (alt)', 'https://sudokupad.app/james-sinclair/pressure-packed-alt', 5, '2026-09-07 13:44:26', 14),
(12, 'Pressure Packed', 'https://sudokupad.app/james-sinclair/pressure-packed', 5, '2026-09-07 14:22:41', 36),
(13, 'True Blue', 'https://crackingthecryptic.com/sudoku?id=2111', 8, '2026-09-08 05:27:01', 40),
(14, 'A Night in the Entropics', 'A Night in the Entropics', 9, '2026-09-08 11:24:06', 30),
(15, 'Echoed Whispers', 'https://sudokupad.app/w2zxwu6v9g', 10, '2026-09-08 12:45:09', 25),
(16, 'Sudoku is A Bouquet Of Complements', 'https://crackingthecryptic.com/sudoku?id=3322', 11, '2026-09-08 15:57:35', 35),
(17, 'Snackdoku Title Puzzle', 'https://crackingthecryptic.com/sudoku?id=2910', 12, '2026-09-08 17:55:53', 13),
(18, 'Give Me an X, Give Me a V', 'https://crackingthecryptic.com/sudoku?id=1626', 13, '2026-09-09 04:34:25', 15),
(19, 'Search and Surprise', 'https://sudokupad.app/sandra-and-nala/search-and-surprise', 14, '2026-09-10 05:04:28', 25),
(20, 'This Puzzle May Floor You', 'https://crackingthecryptic.com/sudoku?id=793', 15, '2026-09-11 05:01:41', 14),
(21, 'Floater', 'https://sudokupad.app/james-sinclair/floater', 5, '2026-09-11 05:39:16', 17),
(22, 'Arrow with a Twist', 'https://crackingthecryptic.com/sudoku?id=978', 9, '2026-09-11 10:30:08', 8),
(23, 'Right Angles, Wrong Quads', 'https://sudokupad.app/mwz7l2lvoi', 16, '2026-09-12 04:58:28', 25),
(24, 'XV Sudoku', 'https://sudokupad.app/2J2fRGP4Tn', 17, '2026-09-12 06:57:03', 18),
(25, 'Clueless WPC', 'https://crackingthecryptic.com/sudoku?id=1148', 18, '2026-09-12 07:29:08', 24),
(26, 'XV Sudoku', 'https://crackingthecryptic.com/sudoku?id=677', 19, '2026-09-12 08:08:14', 13),
(27, 'Incluso', 'https://sudokupad.app/james-sinclair/incluso', 5, '2026-09-12 08:56:48', 30),
(28, 'Regional Conflict', 'https://crackingthecryptic.com/sudoku?id=2156', 20, '2026-09-12 11:32:25', 35),
(29, 'WSM 1C', 'https://crackingthecryptic.com/sudoku?id=633', 21, '2026-09-12 14:40:19', 12),
(30, 'Electric Explosion', 'https://crackingthecryptic.com/sudoku?id=1806', 16, '2026-09-13 06:17:55', 14),
(31, 'Dimitrescu', 'https://sudokupad.app/james-sinclair/dimitrescu', 5, '2026-09-13 07:02:36', 31),
(32, 'Question Everything', 'https://crackingthecryptic.com/sudoku?id=1091', 5, '2026-09-13 12:05:37', 14),
(33, 'Thermo Killer', 'https://sudokupad.app/hnHfBN97Fh', 22, '2026-09-13 12:59:13', 40),
(34, 'Divinity Through The Fog', 'https://sudokupad.app/8d3hli9g3k', 10, '2026-09-13 20:20:25', 50);

-- --------------------------------------------------------

--
-- Table structure for table `doku_has_variant`
--

CREATE TABLE `doku_has_variant` (
  `doku_id` smallint(6) NOT NULL,
  `doku_variant` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doku_has_variant`
--

INSERT INTO `doku_has_variant` (`doku_id`, `doku_variant`) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 2),
(3, 4),
(3, 5),
(3, 7),
(3, 8),
(4, 1),
(4, 9),
(4, 10),
(5, 4),
(5, 11),
(6, 4),
(7, 1),
(7, 10),
(7, 12),
(8, 9),
(8, 10),
(9, 2),
(10, 9),
(10, 11),
(11, 4),
(11, 5),
(12, 4),
(12, 5),
(13, 7),
(13, 10),
(14, 13),
(14, 14),
(14, 15),
(15, 1),
(15, 17),
(15, 18),
(16, 1),
(16, 2),
(16, 3),
(16, 9),
(16, 12),
(16, 16),
(16, 18),
(17, 10),
(17, 12),
(18, 19),
(19, 5),
(19, 20),
(20, 4),
(21, 9),
(21, 10),
(21, 11),
(22, 4),
(23, 3),
(23, 21),
(24, 19),
(25, 5),
(26, 19),
(27, 2),
(27, 7),
(28, 7),
(28, 18),
(29, 5),
(30, 1),
(31, 2),
(31, 3),
(31, 9),
(31, 22),
(32, 2),
(32, 5),
(32, 19),
(33, 2),
(33, 5),
(34, 2),
(34, 3),
(34, 18);

-- --------------------------------------------------------

--
-- Table structure for table `doku_setters`
--

CREATE TABLE `doku_setters` (
  `setter_id` smallint(5) UNSIGNED NOT NULL,
  `setter_name` varchar(256) NOT NULL,
  `setter_count` smallint(6) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doku_setters`
--

INSERT INTO `doku_setters` (`setter_id`, `setter_name`, `setter_count`) VALUES
(1, 'Aad van de Wetering', 0),
(2, 'HDev', 0),
(3, '99%Sneaky', 0),
(4, 'Olima', 0),
(5, 'James Sinclair', 0),
(6, 'Agarwal', 0),
(7, 'Jay Dyer', 0),
(8, 'James Kopp', 0),
(9, 'Thomas Prestopnik', 0),
(10, 'Meggen033', 0),
(11, 'RockyRoer', 0),
(12, 'Panthera', 0),
(13, 'Andy Petersen', 0),
(14, 'Sandra &amp; Nala', 0),
(15, 'Thomas Snyder', 0),
(16, 'Scojo', 0),
(17, 'Richard Stolk', 0),
(18, 'Ashish Kumar', 0),
(19, 'Akash Doulani', 0),
(20, 'Chilly', 0),
(21, 'Christoph Seeliger', 0),
(22, 'Tyrgannus', 0);

-- --------------------------------------------------------

--
-- Table structure for table `doku_users`
--

CREATE TABLE `doku_users` (
  `doku_user_id` tinyint(3) UNSIGNED NOT NULL,
  `doku_username` varchar(64) NOT NULL,
  `doku_password` varchar(256) NOT NULL,
  `doku_user_roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`doku_user_roles`)),
  `doku_email` varchar(256) NOT NULL,
  `doku_user_created` datetime NOT NULL,
  `doku_user_active` tinyint(1) NOT NULL DEFAULT 1,
  `doku_user_ip` varchar(512) DEFAULT NULL,
  `doku_user_status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `doku_user_intro` varchar(4096) DEFAULT NULL,
  `doku_last_login` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doku_users`
--

INSERT INTO `doku_users` (`doku_user_id`, `doku_username`, `doku_password`, `doku_user_roles`, `doku_email`, `doku_user_created`, `doku_user_active`, `doku_user_ip`, `doku_user_status`, `doku_user_intro`, `doku_last_login`) VALUES
(1, 'leeDoku', '$2y$12$hqzB02gcWDQbFzNqZHQoHuI4is/GqpB5FgzZ6U3So8pR6Gocj6lmS', '[\"ROLE_SUPER\", \"ROLE_ADMIN\", \"ROLE_USER\"]', 'lee@leerlandais.com', '2026-09-01 16:44:44', 1, '', 255, NULL, '2026-09-01 19:19:48');

-- --------------------------------------------------------

--
-- Table structure for table `doku_variants`
--

CREATE TABLE `doku_variants` (
  `variant_id` tinyint(3) UNSIGNED NOT NULL,
  `variant_name` varchar(256) NOT NULL,
  `variant_rules` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doku_variants`
--

INSERT INTO `doku_variants` (`variant_id`, `variant_name`, `variant_rules`) VALUES
(1, 'German Whisper', 'Adjacent digits must have a minimum difference of 5'),
(2, 'Killer Cage', 'Digits in a cage cannot repeat and sum to the small number in the top left corner of its uppermost rown'),
(3, 'Special', 'Rare, see puzzle for info'),
(4, 'Arrow', 'Digits along arrows sum to the digit in the attached circle.'),
(5, 'Thermometer', 'Digits along a thermometer must increase from bulb to the tips'),
(6, 'Slow Thermo', 'Digits along a Slow thermometer must never decrease, moving from bulb to the tips'),
(7, 'Region Sum', 'Box borders divide the line into segments with equal sums'),
(8, 'Anti-Knight', 'Cells separated by a knight&#039;s move, in chess, cannot contain the same digit'),
(9, 'Renban', 'Digits form a set of non-repeating, consecutive digits in any order'),
(10, 'Kropki Black', 'Ratio 1:2'),
(11, 'Quadruple Circles', 'Digits inside the circles appear in the four surrounding cells'),
(12, 'Kropki White', 'A white dot joins cells with consecutive digits'),
(13, 'Anti-King', 'Cells separated by a chess King&#039;s move cannot contain the same digit'),
(14, 'Anti-Queen', 'Cells separated by a chess Queen&#039;s move cannot contain the same digit'),
(15, 'Entropic', 'Any three consecutive digits along a peach line must contain a digit from the set [1,2,3], a digit from [4,5,6], and a digit from [789]'),
(16, 'Modular', 'Any three consecutive digits along a teal line must contain a digit from the set [1,4,7], a digit from [2,5,8], and a digit from [3,6,9]'),
(17, 'Palindrome', 'Digits equidistant from the center are the same (i.e. line reads the same forwards and backwards)'),
(18, 'Fog', 'The grid is covered in for. Entering digits may clear cells'),
(19, 'X/V', 'Cells separated by an X sum to 10. Cells separated by a V sum to 5'),
(20, 'Numbered Rooms', 'Clues outside the grid indicate the digit which has to be placed in the Nth cell, where N is the first digit'),
(21, 'Zipper', 'Pairs of digits equidistant from the center sum to the digit in the center of the line'),
(22, 'Diagonal', 'Digits cannot repeat along the diagonal line');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doku_connection_logs`
--
ALTER TABLE `doku_connection_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `doku_dokus`
--
ALTER TABLE `doku_dokus`
  ADD PRIMARY KEY (`doku_id`);

--
-- Indexes for table `doku_has_variant`
--
ALTER TABLE `doku_has_variant`
  ADD PRIMARY KEY (`doku_id`,`doku_variant`);

--
-- Indexes for table `doku_setters`
--
ALTER TABLE `doku_setters`
  ADD PRIMARY KEY (`setter_id`),
  ADD UNIQUE KEY `setter_name` (`setter_name`);

--
-- Indexes for table `doku_users`
--
ALTER TABLE `doku_users`
  ADD PRIMARY KEY (`doku_user_id`);

--
-- Indexes for table `doku_variants`
--
ALTER TABLE `doku_variants`
  ADD PRIMARY KEY (`variant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doku_connection_logs`
--
ALTER TABLE `doku_connection_logs`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doku_dokus`
--
ALTER TABLE `doku_dokus`
  MODIFY `doku_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `doku_setters`
--
ALTER TABLE `doku_setters`
  MODIFY `setter_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `doku_users`
--
ALTER TABLE `doku_users`
  MODIFY `doku_user_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doku_variants`
--
ALTER TABLE `doku_variants`
  MODIFY `variant_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

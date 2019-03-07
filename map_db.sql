-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2019 at 02:16 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `map_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `link`
--

CREATE TABLE `link` (
  `id` int(11) NOT NULL,
  `map_id` int(11) NOT NULL,
  `point_start` int(11) DEFAULT NULL,
  `point_end` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `value` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `link`
--

INSERT INTO `link` (`id`, `map_id`, `point_start`, `point_end`, `type`, `value`) VALUES
(3054, 250, 1, 4, 'blocked', 4),
(3055, 250, 1, 2, 'straight', 2),
(3056, 250, 2, 5, 'to_start', 5),
(3057, 250, 2, 3, 'blocked', 4),
(3058, 250, 3, 6, 'to_start', 1),
(3059, 250, 4, 7, 'to_end', 2),
(3060, 250, 4, 5, 'to_end', 1),
(3061, 250, 5, 8, 'straight', 2),
(3062, 250, 5, 6, 'straight', 4),
(3063, 250, 6, 9, 'to_start', 5),
(3064, 250, 7, 10, 'straight', 3),
(3065, 250, 7, 8, 'straight', 1),
(3066, 250, 10, 11, 'straight', 2),
(3067, 250, 8, 11, 'straight', 3),
(3068, 250, 8, 9, 'straight', 3),
(3069, 250, 11, 12, 'blocked', 5),
(3070, 250, 9, 12, 'straight', 5),
(3071, 251, 1, 4, 'to_end', 5),
(3072, 251, 1, 2, 'to_start', 1),
(3073, 251, 2, 5, 'straight', 2),
(3074, 251, 2, 3, 'to_start', 3),
(3075, 251, 3, 6, 'straight', 2),
(3076, 251, 4, 7, 'straight', 5),
(3077, 251, 4, 5, 'blocked', 3),
(3078, 251, 5, 8, 'straight', 4),
(3079, 251, 5, 6, 'to_start', 5),
(3080, 251, 6, 9, 'to_start', 5),
(3081, 251, 7, 10, 'straight', 3),
(3082, 251, 7, 8, 'blocked', 3),
(3083, 251, 8, 11, 'straight', 1),
(3084, 251, 8, 9, 'to_end', 3),
(3085, 251, 9, 12, 'straight', 5),
(3086, 251, 10, 13, 'straight', 2),
(3087, 251, 10, 11, 'straight', 4),
(3088, 251, 11, 14, 'straight', 3),
(3089, 251, 11, 12, 'straight', 5),
(3090, 251, 12, 15, 'blocked', 1),
(3091, 251, 13, 16, 'to_end', 1),
(3092, 251, 13, 14, 'blocked', 3),
(3093, 251, 16, 17, 'straight', 3),
(3094, 251, 14, 17, 'straight', 1),
(3095, 251, 14, 15, 'to_end', 4),
(3096, 251, 17, 18, 'to_end', 5),
(3097, 251, 15, 18, 'blocked', 5);

-- --------------------------------------------------------

--
-- Table structure for table `map`
--

CREATE TABLE `map` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `registration_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `map`
--

INSERT INTO `map` (`id`, `name`, `registration_dt`, `width`, `height`) VALUES
(250, 'test1', '2019-03-07 13:13:43', 3, 2),
(251, 'test2', '2019-03-07 13:14:24', 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `point`
--

CREATE TABLE `point` (
  `id` int(11) NOT NULL,
  `map_id` int(11) NOT NULL,
  `x_coordinates` int(11) DEFAULT NULL,
  `y_coordinates` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `point`
--

INSERT INTO `point` (`id`, `map_id`, `x_coordinates`, `y_coordinates`) VALUES
(1, 250, 100, 100),
(1, 251, 100, 100),
(2, 250, 100, 200),
(2, 251, 100, 200),
(3, 250, 100, 300),
(3, 251, 100, 300),
(4, 250, 200, 100),
(4, 251, 200, 100),
(5, 250, 200, 200),
(5, 251, 200, 200),
(6, 250, 200, 300),
(6, 251, 200, 300),
(7, 250, 300, 100),
(7, 251, 300, 100),
(8, 250, 300, 200),
(8, 251, 300, 200),
(9, 250, 300, 300),
(9, 251, 300, 300),
(10, 250, 400, 100),
(10, 251, 400, 100),
(11, 250, 400, 200),
(11, 251, 400, 200),
(12, 250, 400, 300),
(12, 251, 400, 300),
(13, 251, 500, 100),
(14, 251, 500, 200),
(15, 251, 500, 300),
(16, 251, 600, 100),
(17, 251, 600, 200),
(18, 251, 600, 300);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `map_id` (`map_id`);

--
-- Indexes for table `map`
--
ALTER TABLE `map`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point`
--
ALTER TABLE `point`
  ADD PRIMARY KEY (`id`,`map_id`) USING BTREE,
  ADD KEY `map_id` (`map_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `link`
--
ALTER TABLE `link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3098;

--
-- AUTO_INCREMENT for table `map`
--
ALTER TABLE `map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2019 at 02:20 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `worldcup`
--

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `id` bigint(64) UNSIGNED NOT NULL,
  `letter` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `match`
--

CREATE TABLE `match` (
  `id` varchar(255) NOT NULL,
  `stadion_id` varchar(255) NOT NULL,
  `status_id` varchar(255) NOT NULL,
  `time_id` varchar(255) NOT NULL,
  `stage_id` varchar(255) NOT NULL,
  `winner_team_id` bigint(64) UNSIGNED DEFAULT NULL,
  `attendance` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `last_event_update_at` datetime DEFAULT NULL,
  `last_score_update_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `match_official`
--

CREATE TABLE `match_official` (
  `official_id` varchar(255) NOT NULL,
  `match_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `match_team_event`
--

CREATE TABLE `match_team_event` (
  `id` bigint(64) UNSIGNED NOT NULL,
  `match_id` varchar(255) NOT NULL,
  `team_id` bigint(64) UNSIGNED NOT NULL,
  `player_id` varchar(255) NOT NULL,
  `type_of_event_id` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `match_team_list`
--

CREATE TABLE `match_team_list` (
  `match_id` varchar(255) NOT NULL,
  `player_id` varchar(255) NOT NULL,
  `player_position_id` varchar(255) NOT NULL,
  `is_captain` tinyint(1) NOT NULL,
  `is_starter` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `match_team_statistic`
--

CREATE TABLE `match_team_statistic` (
  `match_id` varchar(255) NOT NULL,
  `team_id` bigint(64) UNSIGNED NOT NULL,
  `tactic_id` varchar(255) NOT NULL,
  `is_home` tinyint(1) NOT NULL,
  `goals` int(32) UNSIGNED NOT NULL,
  `penalties` int(32) UNSIGNED NOT NULL,
  `attempts_on_goal` int(32) UNSIGNED NOT NULL,
  `on_target` int(32) UNSIGNED NOT NULL,
  `off_target` int(32) UNSIGNED NOT NULL,
  `blocked` int(32) UNSIGNED NOT NULL,
  `woodwork` int(32) UNSIGNED NOT NULL,
  `corners` int(32) UNSIGNED NOT NULL,
  `offsides` int(32) UNSIGNED NOT NULL,
  `ball_possession` int(32) UNSIGNED NOT NULL,
  `pass_accuracy` int(32) UNSIGNED NOT NULL,
  `num_passes` int(32) UNSIGNED NOT NULL,
  `passes_completed` int(32) UNSIGNED NOT NULL,
  `distance_covered` int(32) UNSIGNED NOT NULL,
  `balls_recovered` int(32) UNSIGNED NOT NULL,
  `tackles` int(32) UNSIGNED NOT NULL,
  `clearances` int(32) UNSIGNED NOT NULL,
  `yellow_cards` int(32) UNSIGNED NOT NULL,
  `red_cards` int(32) UNSIGNED NOT NULL,
  `fouls_committed` int(32) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `match_weather`
--

CREATE TABLE `match_weather` (
  `match_id` varchar(255) NOT NULL,
  `weather_description_id` varchar(255) NOT NULL,
  `humidity` varchar(255) NOT NULL,
  `temp_celsius` varchar(255) NOT NULL,
  `temp_farenheit` varchar(255) NOT NULL,
  `wind_speed` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `official`
--

CREATE TABLE `official` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `id` varchar(255) NOT NULL,
  `team_id` bigint(64) UNSIGNED NOT NULL,
  `shirt_number` int(32) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `player_position`
--

CREATE TABLE `player_position` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stadion`
--

CREATE TABLE `stadion` (
  `id` varchar(255) NOT NULL,
  `city_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stage`
--

CREATE TABLE `stage` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tactic`
--

CREATE TABLE `tactic` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` bigint(64) UNSIGNED NOT NULL,
  `group_id` bigint(64) UNSIGNED NOT NULL,
  `country` varchar(255) NOT NULL,
  `alternate_name` varchar(255) DEFAULT NULL,
  `fifa_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `time`
--

CREATE TABLE `time` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `type_of_event`
--

CREATE TABLE `type_of_event` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `weather_description`
--

CREATE TABLE `weather_description` (
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `letter` (`letter`);

--
-- Indexes for table `match`
--
ALTER TABLE `match`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stadion_id` (`stadion_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `time_id` (`time_id`),
  ADD KEY `winner_team_id` (`winner_team_id`),
  ADD KEY `stage_id` (`stage_id`);

--
-- Indexes for table `match_official`
--
ALTER TABLE `match_official`
  ADD PRIMARY KEY (`official_id`,`match_id`),
  ADD KEY `match_id` (`match_id`),
  ADD KEY `official_id` (`official_id`);

--
-- Indexes for table `match_team_event`
--
ALTER TABLE `match_team_event`
  ADD PRIMARY KEY (`id`,`match_id`,`team_id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `type_of_event_id` (`type_of_event_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `match_id` (`match_id`) USING BTREE;

--
-- Indexes for table `match_team_list`
--
ALTER TABLE `match_team_list`
  ADD PRIMARY KEY (`match_id`,`player_id`) USING BTREE,
  ADD KEY `player_id` (`player_id`),
  ADD KEY `player_position_id` (`player_position_id`) USING BTREE,
  ADD KEY `match_id` (`match_id`) USING BTREE;

--
-- Indexes for table `match_team_statistic`
--
ALTER TABLE `match_team_statistic`
  ADD PRIMARY KEY (`match_id`,`team_id`) USING BTREE,
  ADD KEY `match_id` (`match_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `tactic_id` (`tactic_id`);

--
-- Indexes for table `match_weather`
--
ALTER TABLE `match_weather`
  ADD PRIMARY KEY (`match_id`) USING BTREE,
  ADD KEY `weather_description_id` (`weather_description_id`);

--
-- Indexes for table `official`
--
ALTER TABLE `official`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `player_position`
--
ALTER TABLE `player_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stadion`
--
ALTER TABLE `stadion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tactic`
--
ALTER TABLE `tactic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `country` (`country`),
  ADD UNIQUE KEY `fifa_code` (`fifa_code`),
  ADD KEY `group_id` (`group_id`) USING BTREE;

--
-- Indexes for table `time`
--
ALTER TABLE `time`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type_of_event`
--
ALTER TABLE `type_of_event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weather_description`
--
ALTER TABLE `weather_description`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `match`
--
ALTER TABLE `match`
  ADD CONSTRAINT `match_ibfk_1` FOREIGN KEY (`stadion_id`) REFERENCES `stadion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_ibfk_2` FOREIGN KEY (`stage_id`) REFERENCES `stage` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_ibfk_4` FOREIGN KEY (`time_id`) REFERENCES `time` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_ibfk_5` FOREIGN KEY (`winner_team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `match_official`
--
ALTER TABLE `match_official`
  ADD CONSTRAINT `match_official_ibfk_1` FOREIGN KEY (`official_id`) REFERENCES `official` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_official_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `match_team_event`
--
ALTER TABLE `match_team_event`
  ADD CONSTRAINT `match_team_event_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_team_event_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_team_event_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_team_event_ibfk_4` FOREIGN KEY (`type_of_event_id`) REFERENCES `type_of_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `match_team_list`
--
ALTER TABLE `match_team_list`
  ADD CONSTRAINT `match_team_list_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_team_list_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_team_list_ibfk_3` FOREIGN KEY (`player_position_id`) REFERENCES `player_position` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `match_team_statistic`
--
ALTER TABLE `match_team_statistic`
  ADD CONSTRAINT `match_team_statistic_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_team_statistic_ibfk_2` FOREIGN KEY (`tactic_id`) REFERENCES `tactic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_team_statistic_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `match_weather`
--
ALTER TABLE `match_weather`
  ADD CONSTRAINT `match_weather_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `match` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `match_weather_ibfk_2` FOREIGN KEY (`weather_description_id`) REFERENCES `weather_description` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stadion`
--
ALTER TABLE `stadion`
  ADD CONSTRAINT `stadion_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2019 at 04:41 PM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maxusint`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `upazilla` varchar(50) DEFAULT NULL,
  `up` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_balance` double DEFAULT '0',
  `status` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `username`, `email`, `mobile`, `password`, `district`, `upazilla`, `up`, `create_date`, `total_balance`, `status`) VALUES
(1, 'Maxus int', 'maxus', 'maxusint@gmail.com', '01828800184', '123', 'Dhaka', 'Dhaka', 'Dhaka', '2019-01-01 08:02:25', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `agent_id` int(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `club_id` int(6) DEFAULT NULL,
  `district` varchar(50) NOT NULL,
  `upazilla` varchar(50) DEFAULT NULL,
  `up` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_balance` double DEFAULT '0',
  `status` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agent_id`, `name`, `username`, `email`, `mobile`, `password`, `club_id`, `district`, `upazilla`, `up`, `create_date`, `total_balance`, `status`) VALUES
(6, 'Md Mainuddin', 'mainuddin', 'mainuddinm5@gmail.com', '01822800728', '123', 13, 'Dhaka', 'Dhaka', 'Dhaka', '2018-12-26 07:26:56', 0, 1),
(7, 'Md Mainuddin', 'mainuddinm5', 'mainuddinm55@gamil.com', '01822800727', '123', 13, 'Dhaka', '', '', '2018-12-26 07:48:19', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bet`
--

CREATE TABLE `bet` (
  `bet_id` int(6) NOT NULL,
  `question` varchar(100) NOT NULL,
  `started_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `match_id` int(6) DEFAULT NULL,
  `bet_mode` int(6) DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  `result` varchar(30) NOT NULL DEFAULT 'In play',
  `right_answer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bet`
--

INSERT INTO `bet` (`bet_id`, `question`, `started_date`, `match_id`, `bet_mode`, `status`, `result`, `right_answer`) VALUES
(36, 'Who won the toss?', '0000-00-00 00:00:00', 23, 3, 1, 'Finish', 14),
(37, 'Who won the match?', '2018-07-24 00:07:23', 23, 3, 1, 'Finish', 16),
(38, 'Who won toss?', '0000-00-00 00:00:00', 24, 3, 1, 'In play', 0);

-- --------------------------------------------------------

--
-- Stand-in structure for view `bet_history`
-- (See below for the actual view)
--
CREATE TABLE `bet_history` (
`Date` timestamp
,`Details` varchar(100)
,`Amount` float
,`Rate` float
,`Return Amount` double
,`Status` varchar(30)
);

-- --------------------------------------------------------

--
-- Table structure for table `bet_mode`
--

CREATE TABLE `bet_mode` (
  `mode_id` int(6) NOT NULL,
  `mode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bet_mode`
--

INSERT INTO `bet_mode` (`mode_id`, `mode`) VALUES
(1, 'Trade'),
(2, 'Advanced'),
(3, 'Both');

-- --------------------------------------------------------

--
-- Table structure for table `bet_rate`
--

CREATE TABLE `bet_rate` (
  `id` int(6) NOT NULL,
  `bet_id` int(6) NOT NULL,
  `options` varchar(50) NOT NULL,
  `rate` float NOT NULL,
  `user_type_id` int(6) DEFAULT NULL,
  `bet_mode_id` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bet_rate`
--

INSERT INTO `bet_rate` (`id`, `bet_id`, `options`, `rate`, `user_type_id`, `bet_mode_id`) VALUES
(14, 36, 'Bangladesh', 1.5, 4, 3),
(15, 36, 'Windies', 1.6, 4, 3),
(16, 37, 'Bangladesh', 1.8, 4, 3),
(17, 37, 'Windies', 2, 4, 3),
(18, 38, 'India', 1.5, 4, 3),
(19, 38, 'Australia', 1.6, 4, 3);

-- --------------------------------------------------------

--
-- Stand-in structure for view `bet_rate_view`
-- (See below for the actual view)
--
CREATE TABLE `bet_rate_view` (
`question` varchar(100)
,`bet_date` timestamp
,`mode` varchar(50)
,`user_type` varchar(40)
,`options` varchar(50)
,`rate` float
,`status` int(1)
,`result` varchar(30)
);

-- --------------------------------------------------------

--
-- Table structure for table `club`
--

CREATE TABLE `club` (
  `club_id` int(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `upazilla` varchar(50) DEFAULT NULL,
  `up` varchar(50) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_balance` float DEFAULT '0',
  `status` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `club`
--

INSERT INTO `club` (`club_id`, `name`, `username`, `email`, `mobile`, `password`, `district`, `upazilla`, `up`, `create_date`, `total_balance`, `status`) VALUES
(13, 'Md Mainuddin', 'mainuddinm55', 'mainuddinm55@gmail.com', '01822800727', '123', 'Dhaka', '', '', '2018-12-26 07:25:59', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE `commission` (
  `comm_id` int(6) NOT NULL,
  `comm_rate` float DEFAULT NULL,
  `amount` float NOT NULL,
  `username` varchar(100) NOT NULL,
  `from_user_id` int(6) DEFAULT NULL,
  `bet_id` int(6) DEFAULT NULL,
  `comm_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_seen` int(1) DEFAULT '0',
  `purpose` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(6) NOT NULL,
  `team1` varchar(50) NOT NULL,
  `team2` varchar(50) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `match_type` varchar(30) NOT NULL,
  `match_format` varchar(30) DEFAULT NULL,
  `status` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `team1`, `team2`, `date_time`, `match_type`, `match_format`, `status`) VALUES
(23, 'Bangladesh', 'Windies', '2018-12-25 12:00:00', 'Cricket', 'T20', 1),
(24, 'India', 'Australia', '2018-12-25 12:00:00', 'Cricket', 'ODI', 1);

-- --------------------------------------------------------

--
-- Table structure for table `profit_shared_user`
--

CREATE TABLE `profit_shared_user` (
  `user_id` int(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `district` varchar(100) NOT NULL,
  `upazilla` varchar(50) DEFAULT NULL,
  `up` varchar(50) DEFAULT NULL,
  `shared_percent` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profit_shared_user`
--

INSERT INTO `profit_shared_user` (`user_id`, `name`, `username`, `email`, `mobile`, `district`, `upazilla`, `up`, `shared_percent`) VALUES
(1, 'Md Mainuddin', 'mainuddinm55', 'mainuddinm55@gmail.com', '01822800727', 'Gazipur', 'Gazipur Sadar', 'Kashimpur', 20),
(5, 'mainuddin', 'mainuddin567', 'mainuddinm@gmail.com', '64521561', 'dhaka', 'dhaka', 'dhaka', 20),
(6, 'Mainuddin', 'mainuddin120', 'mainuddin54@gmail.com', '01822800725', 'Gazipur', NULL, NULL, NULL),
(8, 'kamal', 'kamal3', 'kamal@gmail.com', '12345678', 'Dhaka', 'Savar', 'Cantonment', 32),
(9, 'badon', 'badon', 'badon@gmail.com', '1654654', 'dhaka', 'dhaka', 'dhaka', 50);

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `rank_id` int(6) NOT NULL,
  `rank_name` varchar(50) NOT NULL,
  `assert_need` int(6) DEFAULT NULL,
  `bonus` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rank`
--

INSERT INTO `rank` (`rank_id`, `rank_name`, `assert_need`, `bonus`) VALUES
(1, 'General Member', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `security_pin`
--

CREATE TABLE `security_pin` (
  `id` int(6) NOT NULL,
  `pin` varchar(30) NOT NULL,
  `user_type_id` int(6) DEFAULT NULL,
  `used` int(1) DEFAULT '0',
  `validity` int(6) DEFAULT '180'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `security_pin`
--

INSERT INTO `security_pin` (`id`, `pin`, `user_type_id`, `used`, `validity`) VALUES
(1, '102011', 1, 1, 180),
(2, '102012', 2, 1, 180);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(6) NOT NULL,
  `from_username` varchar(100) NOT NULL,
  `to_username` varchar(100) NOT NULL,
  `amount` double NOT NULL,
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT 'Request send',
  `trans_type` varchar(50) NOT NULL,
  `trans_charge` varchar(50) DEFAULT NULL,
  `from_user_seen` int(1) DEFAULT '0',
  `to_user_seen` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `from_username`, `to_username`, `amount`, `trans_date`, `status`, `trans_type`, `trans_charge`, `from_user_seen`, `to_user_seen`) VALUES
(2, 'mainuddin', 'kamal', 500, '2018-12-18 09:42:04', 'Success', 'withdraw', '1', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `agent_id` int(6) DEFAULT NULL,
  `district` varchar(50) NOT NULL,
  `upazilla` varchar(50) DEFAULT NULL,
  `up` varchar(50) DEFAULT NULL,
  `type_id` int(6) DEFAULT NULL,
  `pin_id` int(6) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `validity_date` datetime DEFAULT NULL,
  `trade_balance` float DEFAULT NULL,
  `advanced_balance` float DEFAULT '0',
  `status` int(1) DEFAULT '1',
  `rank_id` int(6) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `username`, `email`, `mobile`, `password`, `reference`, `agent_id`, `district`, `upazilla`, `up`, `type_id`, `pin_id`, `create_date`, `validity_date`, `trade_balance`, `advanced_balance`, `status`, `rank_id`) VALUES
(13, 'Kamal', 'kamal', 'kamal@gmail.com', '01833513131', '123', 'mainuddin', 6, 'Dhaka', '', '', 1, 1, '2019-01-01 12:28:46', NULL, 30, 0, 1, 1),
(15, 'Robi', 'robi', 'robi@gmail.com', '0185230120', '123', 'mainuddin', 6, 'Dhaka', '', '', 2, 2, '2019-01-01 12:38:52', NULL, 100, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_bet`
--

CREATE TABLE `user_bet` (
  `user_id` int(6) DEFAULT NULL,
  `bet_id` int(6) DEFAULT NULL,
  `bet_option_id` int(6) DEFAULT NULL,
  `bet_rate` float NOT NULL,
  `bet_amount` float NOT NULL,
  `bet_return_amount` double NOT NULL,
  `bet_mode_id` int(6) DEFAULT NULL,
  `bet_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `result` varchar(30) DEFAULT 'Pending',
  `is_seen` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_bet`
--

INSERT INTO `user_bet` (`user_id`, `bet_id`, `bet_option_id`, `bet_rate`, `bet_amount`, `bet_return_amount`, `bet_mode_id`, `bet_date`, `result`, `is_seen`) VALUES
(1, 1, 1, 1.8, 500, 900, 1, '2018-12-13 12:20:02', 'Pending', 1),
(2, 2, 6, 1.5, 200, 300, NULL, '2018-12-19 11:12:46', 'Pending', 1),
(2, 2, 6, 1.5, 200, 300, 1, '2018-12-19 11:13:24', 'Pending', 1),
(2, 3, 6, 1.5, 200, 300, 1, '2018-12-19 11:55:58', 'Pending', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(6) NOT NULL,
  `user_type` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `user_type`) VALUES
(1, 'Royal'),
(2, 'Classic'),
(3, 'Premium'),
(4, 'All');

-- --------------------------------------------------------

--
-- Structure for view `bet_history`
--
DROP TABLE IF EXISTS `bet_history`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bet_history`  AS  select `user_bet`.`bet_date` AS `Date`,`bet`.`question` AS `Details`,`user_bet`.`bet_amount` AS `Amount`,`user_bet`.`bet_rate` AS `Rate`,`user_bet`.`bet_return_amount` AS `Return Amount`,`user_bet`.`result` AS `Status` from (`user_bet` join `bet`) where (`user_bet`.`bet_id` = `bet`.`bet_id`) ;

-- --------------------------------------------------------

--
-- Structure for view `bet_rate_view`
--
DROP TABLE IF EXISTS `bet_rate_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bet_rate_view`  AS  select `bet`.`question` AS `question`,`bet`.`started_date` AS `bet_date`,`bet_mode`.`mode` AS `mode`,`user_type`.`user_type` AS `user_type`,`bet_rate`.`options` AS `options`,`bet_rate`.`rate` AS `rate`,`bet`.`status` AS `status`,`bet`.`result` AS `result` from (((`bet` join `bet_mode`) join `bet_rate`) join `user_type`) where ((`bet`.`bet_id` = `bet_rate`.`bet_id`) and (`bet`.`bet_mode` = `bet_mode`.`mode_id`) and (`bet_rate`.`user_type_id` = `user_type`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`agent_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD KEY `agent_club_id_fk` (`club_id`);

--
-- Indexes for table `bet`
--
ALTER TABLE `bet`
  ADD PRIMARY KEY (`bet_id`),
  ADD KEY `bet_bet_mode_id_fk` (`bet_mode`);

--
-- Indexes for table `bet_mode`
--
ALTER TABLE `bet_mode`
  ADD PRIMARY KEY (`mode_id`);

--
-- Indexes for table `bet_rate`
--
ALTER TABLE `bet_rate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bet_rate_bet_id_fk` (`bet_id`),
  ADD KEY `bet_rate_user_type_id_fk` (`user_type_id`),
  ADD KEY `bet_rate_bet_mode_id_fk` (`bet_mode_id`);

--
-- Indexes for table `club`
--
ALTER TABLE `club`
  ADD PRIMARY KEY (`club_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- Indexes for table `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`comm_id`),
  ADD KEY `comm_from_user_id_fk` (`from_user_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profit_shared_user`
--
ALTER TABLE `profit_shared_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`rank_id`);

--
-- Indexes for table `security_pin`
--
ALTER TABLE `security_pin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pin` (`pin`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD KEY `user_agent_id_fk` (`agent_id`),
  ADD KEY `user_type_id_fk` (`type_id`),
  ADD KEY `user_rank_id_fk` (`rank_id`),
  ADD KEY `user_pin_id_fk` (`pin_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `agent_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bet`
--
ALTER TABLE `bet`
  MODIFY `bet_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `bet_mode`
--
ALTER TABLE `bet_mode`
  MODIFY `mode_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bet_rate`
--
ALTER TABLE `bet_rate`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `club`
--
ALTER TABLE `club`
  MODIFY `club_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `comm_id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `profit_shared_user`
--
ALTER TABLE `profit_shared_user`
  MODIFY `user_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rank`
--
ALTER TABLE `rank`
  MODIFY `rank_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `security_pin`
--
ALTER TABLE `security_pin`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `agent_club_id_fk` FOREIGN KEY (`club_id`) REFERENCES `club` (`club_id`);

--
-- Constraints for table `bet`
--
ALTER TABLE `bet`
  ADD CONSTRAINT `bet_bet_mode_id_fk` FOREIGN KEY (`bet_mode`) REFERENCES `bet_mode` (`mode_id`);

--
-- Constraints for table `bet_rate`
--
ALTER TABLE `bet_rate`
  ADD CONSTRAINT `bet_rate_bet_id_fk` FOREIGN KEY (`bet_id`) REFERENCES `bet` (`bet_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bet_rate_bet_mode_id_fk` FOREIGN KEY (`bet_mode_id`) REFERENCES `bet_mode` (`mode_id`),
  ADD CONSTRAINT `bet_rate_user_type_id_fk` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`);

--
-- Constraints for table `commission`
--
ALTER TABLE `commission`
  ADD CONSTRAINT `comm_from_user_id_fk` FOREIGN KEY (`from_user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_agent_id_fk` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`),
  ADD CONSTRAINT `user_pin_id_fk` FOREIGN KEY (`pin_id`) REFERENCES `security_pin` (`id`),
  ADD CONSTRAINT `user_rank_id_fk` FOREIGN KEY (`rank_id`) REFERENCES `rank` (`rank_id`),
  ADD CONSTRAINT `user_type_id_fk` FOREIGN KEY (`type_id`) REFERENCES `user_type` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

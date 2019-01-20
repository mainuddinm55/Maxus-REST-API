-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2019 at 07:49 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

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
  `status_update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `right_answer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bet`
--

INSERT INTO `bet` (`bet_id`, `question`, `started_date`, `match_id`, `bet_mode`, `status`, `result`, `status_update_date`, `right_answer`) VALUES
(1, 'Who won the match?', '0000-00-00 00:00:00', 1, 1, 1, 'In play', '0000-00-00 00:00:00', 0);

--
-- Triggers `bet`
--
DELIMITER $$
CREATE TRIGGER `update_user_bet_status` AFTER UPDATE ON `bet` FOR EACH ROW BEGIN
IF NEW.result = 'Finish' THEN
UPDATE user_bet SET result = 'Won', is_seen =0 WHERE bet_option_id = NEW.right_answer AND bet_id = OLD.bet_id;
UPDATE user_bet SET result = 'Loss' , is_seen =0 WHERE bet_option_id != NEW.right_answer AND bet_id = OLD.bet_id;
ELSEIF NEW.result = 'Cancel' THEN
UPDATE user_bet SET result = 'Cancel', is_seen =0 WHERE bet_id = OLD.bet_id;
END IF;
END
$$
DELIMITER ;

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
(2, 'Advanced');

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
(1, 1, 'Comilla', 1.6, 5, 1),
(2, 1, 'Rajshahi', 1.8, 5, 1),
(3, 1, 'Comilla', 1.8, 4, 1),
(4, 1, 'Rajshahi', 1.5, 4, 1);

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

--
-- Triggers `commission`
--
DELIMITER $$
CREATE TRIGGER `send_notification_after_commission` AFTER INSERT ON `commission` FOR EACH ROW BEGIN
INSERT INTO notification (notification.username, notification.body, notification.type, notification.type_id) VALUES  (NEW.username, Concat("Got ", NEW.amount,"$ ",lower(NEW.purpose)), "Commission",New.comm_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(6) NOT NULL,
  `team1` varchar(50) NOT NULL,
  `team2` varchar(50) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tournament` varchar(200) NOT NULL,
  `match_type` varchar(30) NOT NULL,
  `match_format` varchar(30) DEFAULT NULL,
  `status` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `team1`, `team2`, `date_time`, `tournament`, `match_type`, `match_format`, `status`) VALUES
(1, 'Comilla', 'Rakshahi', '2019-01-21 07:30:00', 'Bangladesh Premier League -2019', 'Cricket', 'T20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `body` varchar(300) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `notice_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(9, 'badon', 'badon', 'badon@gmail.com', '1654654', 'dhaka', 'dhaka', 'dhaka', 50),
(10, 'Md Mainuddin', 'mainuddinm5', 'mainuddin@gmail.com', '0185689666', 'Gazipur', 'Sadar', 'Kashimpur', 20);

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
(1, 'General Member', 0, 0),
(2, 'Associate', 10, 50),
(3, 'Sr. Associate', 100, 100),
(4, 'Bronze', 500, 150),
(5, 'Silver', 1200, 200),
(6, 'Gold', 6000, 350),
(7, 'P.D', 15000, 600),
(8, 'A.M', 30000, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `security_pin`
--

CREATE TABLE `security_pin` (
  `id` int(6) NOT NULL,
  `pin` varchar(30) NOT NULL,
  `user_type_id` int(6) DEFAULT NULL,
  `used` int(1) DEFAULT '0',
  `validity` int(6) DEFAULT '180',
  `owner_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `security_pin`
--

INSERT INTO `security_pin` (`id`, `pin`, `user_type_id`, `used`, `validity`, `owner_id`) VALUES
(1, '102011', 1, 1, 180, 0),
(2, '102012', 2, 1, 180, 0),
(3, '102013', 4, 1, 180, 0),
(4, '102100', 1, 1, 180, 0),
(6, '102122', 1, 1, 180, 0),
(7, '122012', 1, 0, 180, 1);

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
  `status` varchar(20) DEFAULT 'Request Send',
  `status_update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `trans_type` varchar(50) NOT NULL,
  `trans_charge` varchar(50) DEFAULT NULL,
  `from_user_seen` int(1) DEFAULT '0',
  `to_user_seen` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `transaction`
--
DELIMITER $$
CREATE TRIGGER `transaction_notification` AFTER UPDATE ON `transaction` FOR EACH ROW BEGIN
IF NEW.status = 'Success' THEN
INSERT INTO notification (notification.username, notification.body, notification.type, notification.type_id) VALUES(New.from_username, CONCAT(NEW.trans_type," ",NEW.amount,"$ from ",NEW.to_username," ",lower(NEW.status)),'Transaction',NEW.id);
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(6) NOT NULL,
  `name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(40) NOT NULL,
  `club_id` int(6) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `agent_id` int(6) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `upazilla` varchar(50) DEFAULT NULL,
  `up` varchar(50) DEFAULT NULL,
  `pin` int(6) DEFAULT NULL,
  `total_balance` float DEFAULT '0',
  `status` varchar(20) DEFAULT 'Active',
  `rank_id` int(6) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type_id` int(6) DEFAULT NULL,
  `ref_count` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `email`, `mobile`, `password`, `club_id`, `reference`, `agent_id`, `district`, `upazilla`, `up`, `pin`, `total_balance`, `status`, `rank_id`, `create_date`, `type_id`, `ref_count`) VALUES
(1, 'Shahriar', 'maxusint', 'maxusint@gmail.com', '01723307372', '123', NULL, NULL, NULL, 'Dhaka', 'Uttara', 'Uttara', NULL, 29870, 'Active', NULL, '2019-01-05 07:11:03', 1, 0),
(2, 'Md Mainuddin', 'mainuddin', 'mainuddin@gmail.com', '01759108032', '123', NULL, NULL, NULL, 'Dhaka', '', '', NULL, 11000.2, 'Active', NULL, '2019-01-05 07:15:42', 2, 0),
(3, 'Kamal', 'kamal', 'kamal@gmail.com', '01833513131', '123', 2, NULL, NULL, 'Dhaka', '', '', NULL, 9700.2, 'Active', NULL, '2019-01-05 07:20:13', 3, 0),
(6, 'Robi', 'robi', 'robi@gmail.com', '01856556699', '123', NULL, 'mainuddin', 3, 'Dhaka', 'Dhaka', 'Dhaka', 1, 250.85, 'Active', 1, '2019-01-05 07:46:49', 4, 2),
(7, 'Hanif', 'hanif', 'hanif@gmail.com', '0185635321', '123', NULL, 'mainuddin', 3, 'Dhaka', '', '', 3, 480, 'Active', 1, '2019-01-05 08:32:53', 4, 0),
(8, 'Dadon', 'dadon', 'dadon@gmail.com', '01569759633', '123', NULL, 'kamal', 3, 'Dhaka', '', '', NULL, 0, 'Active', 1, '2019-01-05 08:43:48', 6, 0),
(9, 'Riaz', 'riaz', 'riaz@gmail.com', '01856556691', '123', NULL, 'mainuddin', 3, 'Dhaka', 'Dhaka', 'Dhaka', NULL, 17, 'Active', 1, '2019-01-06 07:33:42', 6, 0),
(52, 'Samir', 'samir', 'samir@gmail.com', '0185696365', '123', NULL, 'robi', 3, 'Dhaka', '', '', NULL, 66, 'Active', 1, '2019-01-13 12:27:27', 6, 0),
(53, 'Sarif', 'sarif', 'sarif@gmail.com', '0185696364', '123', NULL, 'robi', 6, 'Dhaka', '', '', NULL, 0, 'Active', 1, '2019-01-13 12:28:28', 6, 0),
(54, 'Md Shawon', 'shawon', 'shawon@gmail.com', '01523678969', '123', 2, NULL, NULL, 'Dhaka', 'Motijeel', 'Road  - 3', NULL, 0, 'Active', NULL, '2019-01-17 08:47:04', 3, 0),
(55, 'Shuashanto', 'sushan', 'sushan@gmail.com', '0186659658', '123', 2, NULL, NULL, 'Dhaka', 'Mirpur', '', NULL, 0, 'Active', NULL, '2019-01-17 10:16:28', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_bet`
--

CREATE TABLE `user_bet` (
  `user_id` int(6) NOT NULL,
  `bet_id` int(6) NOT NULL,
  `bet_option_id` int(6) DEFAULT NULL,
  `bet_rate` float NOT NULL,
  `bet_amount` float NOT NULL,
  `bet_return_amount` double NOT NULL,
  `bet_mode_id` int(6) DEFAULT NULL,
  `bet_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `result` varchar(30) DEFAULT 'Pending',
  `result_update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `is_seen` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `user_bet`
--
DELIMITER $$
CREATE TRIGGER `balance_transfer_after_bet_finish` AFTER UPDATE ON `user_bet` FOR EACH ROW BEGIN
IF NEW.result = 'Won' THEN
UPDATE users SET users.total_balance = users.total_balance + NEW.bet_return_amount WHERE users.user_id = NEW.user_id;

INSERT INTO notification(notification.username, notification.body, notification.type, notification.type_id) VALUES ((SELECT users.username FROM users WHERE users.user_id = New.user_id),'Congratulations, You won the bet', 'Bet Result', NEW.bet_id);

ELSEIF NEW.result = 'Cancel' THEN
UPDATE users SET users.total_balance = users.total_balance + NEW.bet_amount WHERE users.user_id = NEW.user_id;

INSERT INTO notification(notification.username, notification.body, notification.type, notification.type_id) VALUES ((SELECT users.username FROM users WHERE users.user_id = New.user_id),'Sorry, The bet was canceled', 'Bet Result', NEW.bet_id);

ELSEIF NEW.result = 'Loss' THEN
INSERT INTO notification(notification.username, notification.body, notification.type, notification.type_id) VALUES ((SELECT users.username FROM users WHERE users.user_id = New.user_id),'Opps, You loss the bet', 'Bet Result', NEW.bet_id);
END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `send_notification_after_finish_bet` BEFORE UPDATE ON `user_bet` FOR EACH ROW BEGIN
IF NEW.result = 'Won' THEN
INSERT INTO notification(notification.username, notification.body, notification.type, notification.type_id) VALUES ((SELECT users.username FROM users WHERE users.user_id = New.user_id),'Congratulations you won the bet', 'Bet Result', NEW.bet_id);
END IF;
END
$$
DELIMITER ;

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
(1, 'Admin'),
(2, 'Club'),
(3, 'Agent'),
(4, 'Royal'),
(5, 'Classic'),
(6, 'Premium');

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
-- Indexes for table `notification`
--
ALTER TABLE `notification`
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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- Indexes for table `user_bet`
--
ALTER TABLE `user_bet`
  ADD PRIMARY KEY (`user_id`,`bet_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bet`
--
ALTER TABLE `bet`
  MODIFY `bet_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bet_mode`
--
ALTER TABLE `bet_mode`
  MODIFY `mode_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bet_rate`
--
ALTER TABLE `bet_rate`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `comm_id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profit_shared_user`
--
ALTER TABLE `profit_shared_user`
  MODIFY `user_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rank`
--
ALTER TABLE `rank`
  MODIFY `rank_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `security_pin`
--
ALTER TABLE `security_pin`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

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
  ADD CONSTRAINT `comm_from_user_id_fk` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Host: mysql.claremontbooks.com
-- Generation Time: May 15, 2014 at 05:05 AM
-- Server version: 5.1.56
-- PHP Version: 5.3.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zonkey`
--

-- --------------------------------------------------------

--
-- Table structure for table `banned_customers`
--

DROP TABLE IF EXISTS `banned_customers`;
CREATE TABLE IF NOT EXISTS `banned_customers` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `notes` text,
  `bannedbystaff` varchar(64) NOT NULL,
  `dateunban` date NOT NULL,
  PRIMARY KEY (`bid`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `banned_customers`
--


-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `customerid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` varchar(64) NOT NULL,
  `school` varchar(64) NOT NULL,
  PRIMARY KEY (`customerid`),
  UNIQUE KEY `sid` (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerid`, `sid`, `name`, `email`, `phone`, `school`) VALUES
(1, 333, 'aaa', '', '', ''),
(6, 40114398, 'bruce', '', '', 'hmc'),
(7, 40156787, 'Angela', '', '', 'HMC'),
(8, 40114399, 'Yan', '', '', 'HMC'),
(9, 4444, '', '', '', 'HMC');

-- --------------------------------------------------------

--
-- Table structure for table `equipmentdata`
--

DROP TABLE IF EXISTS `equipmentdata`;
CREATE TABLE IF NOT EXISTS `equipmentdata` (
  `equipmentid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `qtyleft` int(11) DEFAULT '999',
  `notes` varchar(256) DEFAULT NULL,
  `ownerid` int(11) DEFAULT '0',
  PRIMARY KEY (`equipmentid`),
  KEY `ownerid` (`ownerid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `equipmentdata`
--

INSERT INTO `equipmentdata` (`equipmentid`, `name`, `qtyleft`, `notes`, `ownerid`) VALUES
(2, 'Ping pong paddle', 0, 'peeling stickers...', 0),
(3, 'billiards supply', 985, '3 sets', 0),
(4, 'basketballs', 997, 'different brands', 0),
(8, 'Volleyball', 85, 'blue and green', 0),
(9, 'socks', 50, 'for zombies', 0),
(11, 'balls', 90, 'what balls', 0);

-- --------------------------------------------------------

--
-- Table structure for table `equipmentrentals`
--

DROP TABLE IF EXISTS `equipmentrentals`;
CREATE TABLE IF NOT EXISTS `equipmentrentals` (
  `rentid` int(11) NOT NULL AUTO_INCREMENT,
  `equipmentid` int(11) NOT NULL,
  `sname` varchar(64) NOT NULL,
  `sid` int(11) NOT NULL,
  `dateout` date NOT NULL,
  `datein` date DEFAULT NULL,
  `school` varchar(64) NOT NULL,
  `timeout` datetime NOT NULL,
  `timein` datetime NOT NULL,
  `notes` text NOT NULL,
  `pid` int(11) DEFAULT NULL,
  PRIMARY KEY (`rentid`),
  KEY `sid` (`sid`),
  KEY `equipmentid` (`equipmentid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `equipmentrentals`
--

INSERT INTO `equipmentrentals` (`rentid`, `equipmentid`, `sname`, `sid`, `dateout`, `datein`, `school`, `timeout`, `timein`, `notes`, `pid`) VALUES
(2, 3, 'aa', 333, '2014-05-15', NULL, 'asdf', '2014-05-15 12:31:51', '0000-00-00 00:00:00', '', NULL),
(6, 3, 'bruce', 40114398, '2014-05-15', NULL, 'hmc', '2014-05-15 00:00:00', '0000-00-00 00:00:00', '', NULL),
(7, 3, 'Bruce Yan', 40114398, '2014-05-15', NULL, 'HNC', '2014-05-15 01:21:56', '0000-00-00 00:00:00', '', NULL),
(8, 3, 'Bruce Yan', 40114398, '2014-05-15', NULL, 'HMC', '2014-05-15 01:22:32', '0000-00-00 00:00:00', '', NULL),
(9, 3, '2', 40114398, '2014-05-15', NULL, '1', '2014-05-15 01:23:01', '0000-00-00 00:00:00', '', NULL),
(10, 4, '1', 40114398, '2014-05-15', NULL, 'G', '2014-05-15 01:24:54', '0000-00-00 00:00:00', '', NULL),
(11, 4, '1', 40114398, '2014-05-15', NULL, 'HNC', '2014-05-15 01:27:57', '0000-00-00 00:00:00', '', NULL),
(12, 3, 'Angela', 40156787, '2014-05-15', '2014-05-15', 'HMC', '2014-05-15 01:28:59', '2014-05-15 01:30:30', 'done', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `found`
--

DROP TABLE IF EXISTS `found`;
CREATE TABLE IF NOT EXISTS `found` (
  `itemid` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(256) NOT NULL,
  `datefound` date NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `found`
--

INSERT INTO `found` (`itemid`, `item`, `datefound`) VALUES
(1, 'Frisbee', '2014-05-12');

-- --------------------------------------------------------

--
-- Table structure for table `lost`
--

DROP TABLE IF EXISTS `lost`;
CREATE TABLE IF NOT EXISTS `lost` (
  `itemid` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(256) NOT NULL,
  `datelost` date NOT NULL,
  `description` varchar(256) NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `lost`
--

INSERT INTO `lost` (`itemid`, `item`, `datelost`, `description`) VALUES
(1, 'water bottle', '2014-05-11', 'Bruce @ 213-555-0005');

-- --------------------------------------------------------

--
-- Table structure for table `lostandfound`
--

DROP TABLE IF EXISTS `lostandfound`;
CREATE TABLE IF NOT EXISTS `lostandfound` (
  `itemid` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(256) NOT NULL,
  `datefound` date NOT NULL,
  `returnedto` varchar(256) DEFAULT NULL,
  `datereturn` date DEFAULT NULL,
  `notes` text,
  `category` varchar(16) NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=125 ;

--
-- Dumping data for table `lostandfound`
--

INSERT INTO `lostandfound` (`itemid`, `item`, `datefound`, `returnedto`, `datereturn`, `notes`, `category`) VALUES
(4, 'diet coke', '2014-05-15', 'me', '2014-05-15', 'coke', 'Lost'),
(120, 'water bottle (purple)', '2014-05-14', 'reported by angela', '2014-05-15', 'actually it''s stolen out', 'Found'),
(121, 'apple pie', '2014-05-13', 'ivan', '2014-05-15', 'it''s been eaten', 'Lost'),
(122, 'slices of pie', '2014-05-14', 'lac staff', '2014-05-15', 'eaten by lac staff', 'Found'),
(123, 'sdfasdfads', '0000-00-00', '', '2014-05-15', '', 'Lost'),
(124, 'asdfa', '0000-00-00', '', '2014-05-15', '', 'Found');

-- --------------------------------------------------------

--
-- Table structure for table `mudderbikedata`
--

DROP TABLE IF EXISTS `mudderbikedata`;
CREATE TABLE IF NOT EXISTS `mudderbikedata` (
  `bikeid` int(11) NOT NULL AUTO_INCREMENT,
  `available` varchar(4) NOT NULL DEFAULT 'Yes',
  `notes` varchar(256) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `dateofdeath` date DEFAULT NULL,
  PRIMARY KEY (`bikeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `mudderbikedata`
--

INSERT INTO `mudderbikedata` (`bikeid`, `available`, `notes`, `dateofbirth`, `dateofdeath`) VALUES
(1, '0', NULL, NULL, NULL),
(2, '0', NULL, NULL, NULL),
(3, '0', NULL, NULL, NULL),
(4, '0', 'Doesn''t show up in current database', NULL, NULL),
(5, '0', NULL, NULL, NULL),
(6, '0', NULL, NULL, NULL),
(7, '0', NULL, NULL, NULL),
(8, '0', NULL, NULL, NULL),
(9, '0', NULL, NULL, NULL),
(10, '1', 'Doesn''t show up in current database	', NULL, NULL),
(11, '1', NULL, NULL, NULL),
(12, '0', NULL, NULL, NULL),
(13, '1', 'Doesn''t show up in current database	', NULL, NULL),
(14, '1', NULL, NULL, NULL),
(15, '1', NULL, NULL, NULL),
(16, '1', NULL, NULL, NULL),
(17, '1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mudderbikerentals`
--

DROP TABLE IF EXISTS `mudderbikerentals`;
CREATE TABLE IF NOT EXISTS `mudderbikerentals` (
  `rentid` int(11) NOT NULL AUTO_INCREMENT,
  `bikeid` int(11) NOT NULL,
  `sname` varchar(64) NOT NULL,
  `sid` int(11) NOT NULL,
  `waiver` varchar(4) NOT NULL,
  `dateout` date NOT NULL,
  `keyreturnedto` varchar(64) DEFAULT NULL,
  `datein` date DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  `latedays` int(11) DEFAULT NULL,
  `paidcollectedby` varchar(64) DEFAULT NULL,
  `notes` text,
  `pid` int(11) DEFAULT NULL,
  PRIMARY KEY (`rentid`),
  KEY `sid` (`sid`),
  KEY `bikeid` (`bikeid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `mudderbikerentals`
--

INSERT INTO `mudderbikerentals` (`rentid`, `bikeid`, `sname`, `sid`, `waiver`, `dateout`, `keyreturnedto`, `datein`, `status`, `latedays`, `paidcollectedby`, `notes`, `pid`) VALUES
(3, 4, '333', 333, 'a', '2014-05-15', NULL, NULL, '', NULL, NULL, '', NULL),
(5, 9, 'number 9, bruce', 40114398, 'Y', '2014-05-15', NULL, NULL, '', NULL, NULL, '', NULL),
(6, 10, 'Yan', 40114399, 'N', '2014-05-15', 'Ivan', '0000-00-00', 'Returned', 0, '', '', NULL),
(7, 11, '', 4444, '', '2014-05-15', '', '0000-00-00', 'Returned', 0, '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

DROP TABLE IF EXISTS `owners`;
CREATE TABLE IF NOT EXISTS `owners` (
  `name` varchar(256) NOT NULL,
  `ownerid` int(11) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `contactName` varchar(256) DEFAULT NULL,
  `contactNum` varchar(64) DEFAULT NULL,
  `contactEmail` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`ownerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`name`, `ownerid`, `description`, `contactName`, `contactNum`, `contactEmail`) VALUES
('LAC Public', 0, 'Public equipments that allow LAC users to check out.', 'Ivan Wong', NULL, 'iwong@hmc.edu'),
('LAC Private', 1, 'Private equipments for internal use only', 'Ivan Wong', NULL, 'iwong@hmc.edu');

-- --------------------------------------------------------

--
-- Table structure for table `payment_history_bike`
--

DROP TABLE IF EXISTS `payment_history_bike`;
CREATE TABLE IF NOT EXISTS `payment_history_bike` (
  `pid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `amount` float NOT NULL,
  `staff` varchar(64) NOT NULL,
  `rentid` int(11) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `rentid` (`rentid`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_history_bike`
--


-- --------------------------------------------------------

--
-- Table structure for table `payment_history_equip`
--

DROP TABLE IF EXISTS `payment_history_equip`;
CREATE TABLE IF NOT EXISTS `payment_history_equip` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `amount` float NOT NULL,
  `staff` varchar(64) NOT NULL,
  `rentid` int(11) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `sid` (`sid`),
  KEY `rentid` (`rentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `payment_history_equip`
--


-- --------------------------------------------------------

--
-- Table structure for table `roomrentals`
--

DROP TABLE IF EXISTS `roomrentals`;
CREATE TABLE IF NOT EXISTS `roomrentals` (
  `rentid` int(11) NOT NULL AUTO_INCREMENT,
  `roomid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `checkout` date NOT NULL,
  `timeout` datetime NOT NULL,
  `checkin` date NOT NULL,
  `timein` datetime NOT NULL,
  `notes` text NOT NULL,
  `status` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`rentid`),
  KEY `roomid` (`roomid`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `roomrentals`
--

INSERT INTO `roomrentals` (`rentid`, `roomid`, `sid`, `checkout`, `timeout`, `checkin`, `timein`, `notes`, `status`) VALUES
(1, 1, 40114399, '2014-05-15', '2014-05-15 04:03:47', '2014-05-16', '2014-05-16 04:06:34', '', ''),
(2, 1, 40114398, '2014-05-01', '2014-05-01 23:00:00', '2014-06-01', '2014-06-01 12:00:00', 'Test test test', NULL),
(3, 2, 40156787, '2014-05-15', '2014-05-15 04:42:56', '2014-05-16', '2014-05-16 04:43:00', '1', ''),
(4, 1, 40114398, '2014-05-01', '2014-05-01 23:00:00', '0000-00-00', '0000-00-00 00:00:00', 'yay', ''),
(5, 1, 40114398, '2014-05-01', '2014-05-01 23:00:00', '2014-06-01', '2014-06-01 12:00:00', 'actually it''s stolen out', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roomsdata`
--

DROP TABLE IF EXISTS `roomsdata`;
CREATE TABLE IF NOT EXISTS `roomsdata` (
  `roomid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`roomid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roomsdata`
--

INSERT INTO `roomsdata` (`roomid`, `name`) VALUES
(1, 'Riggs Room'),
(2, 'Baker Room');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(1) NOT NULL DEFAULT '9',
  `name` varchar(256) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(256) NOT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `school` varchar(64) NOT NULL,
  `profile` text,
  PRIMARY KEY (`uid`),
  KEY `level` (`level`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `level`, `name`, `email`, `password`, `phone`, `school`, `profile`) VALUES
(1, 1, 'Bruce Yan', 'byan@hmc.edu', 'e203fe127f487098df65a701831606b724f974a073db7aa8d0f88ac02d133824', '310-907-6543', 'HMC', 'Perosnal Profile Info here'),
(2, 1, 'Angela Zhou', 'azhou@hmc.edu', 'e203fe127f487098df65a701831606b724f974a073db7aa8d0f88ac02d133824', '909-767-1190', 'HMC', 'Perosnal Profile Info here');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banned_customers`
--
ALTER TABLE `banned_customers`
  ADD CONSTRAINT `banned_customers_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `customers` (`sid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `equipmentdata`
--
ALTER TABLE `equipmentdata`
  ADD CONSTRAINT `equipmentdata_ibfk_2` FOREIGN KEY (`ownerid`) REFERENCES `owners` (`ownerid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `equipmentrentals`
--
ALTER TABLE `equipmentrentals`
  ADD CONSTRAINT `equipmentrentals_ibfk_4` FOREIGN KEY (`sid`) REFERENCES `customers` (`sid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `equipmentrentals_ibfk_1` FOREIGN KEY (`equipmentid`) REFERENCES `equipmentdata` (`equipmentid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `equipmentrentals_ibfk_3` FOREIGN KEY (`pid`) REFERENCES `payment_history_equip` (`pid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `mudderbikerentals`
--
ALTER TABLE `mudderbikerentals`
  ADD CONSTRAINT `mudderbikerentals_ibfk_1` FOREIGN KEY (`bikeid`) REFERENCES `mudderbikedata` (`bikeid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `mudderbikerentals_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `customers` (`sid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `payment_history_bike`
--
ALTER TABLE `payment_history_bike`
  ADD CONSTRAINT `payment_history_bike_ibfk_2` FOREIGN KEY (`rentid`) REFERENCES `mudderbikerentals` (`rentid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `payment_history_bike_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `customers` (`sid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `payment_history_equip`
--
ALTER TABLE `payment_history_equip`
  ADD CONSTRAINT `payment_history_equip_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `customers` (`sid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `payment_history_equip_ibfk_1` FOREIGN KEY (`rentid`) REFERENCES `equipmentrentals` (`rentid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `roomrentals`
--
ALTER TABLE `roomrentals`
  ADD CONSTRAINT `roomrentals_ibfk_1` FOREIGN KEY (`roomid`) REFERENCES `roomsdata` (`roomid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `roomrentals_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `customers` (`sid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

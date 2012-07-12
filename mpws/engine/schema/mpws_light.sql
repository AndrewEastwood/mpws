-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 10, 2012 at 02:57 AM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mpws_light`
--

-- --------------------------------------------------------

--
-- Table structure for table `mpws_uploads`
--

CREATE TABLE IF NOT EXISTS `mpws_uploads` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Path` text NOT NULL,
  `Description` text,
  `DateCreated` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mpws_users`
--

CREATE TABLE IF NOT EXISTS `mpws_users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Password` text NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `IsOnline` tinyint(1) NOT NULL,
  `Permisions` text NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mpws_users`
--

INSERT INTO `mpws_users` (`Id`, `Name`, `Password`, `Active`, `IsOnline`, `Permisions`, `DateLastAccess`, `DateCreated`) VALUES
(1, 'TestUser', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 0, 'Toolbox:*:all;\r\nWriter:*:all;', '2012-06-26 00:00:00', '2012-06-26 00:00:00'),
(2, 'olololo', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'test3', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 1, '', '2012-07-09 21:06:33', '2012-06-25 23:56:20');

-- --------------------------------------------------------

--
-- Table structure for table `writer_documents`
--

CREATE TABLE IF NOT EXISTS `writer_documents` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Price` double NOT NULL,
  `Discount` double NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `writer_documents`
--

INSERT INTO `writer_documents` (`ID`, `Name`, `Price`, `Discount`) VALUES
(2, 'jh fghjgjhj ghj g', 0, 0),
(3, 'v fgh gf hdfh hg', 0, 0),
(4, 'fghfgh fgh fgh ', 0, 0),
(5, 'fhf hfgh fh fgh g', 0, 0),
(6, 'gh fghfgh fgh fgh ', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `writer_invoices`
--

CREATE TABLE IF NOT EXISTS `writer_invoices` (
  `ID` int(11) NOT NULL,
  `invoice_id` bigint(20) NOT NULL,
  `sid` int(11) NOT NULL,
  `key` varchar(32) NOT NULL,
  `order_number` bigint(20) NOT NULL,
  `total` double NOT NULL,
  `merchant_order_id` varchar(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `writer_messages`
--

CREATE TABLE IF NOT EXISTS `writer_messages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` text NOT NULL,
  `Message` text NOT NULL,
  `Owner` enum('WEBMASTER','WRITER','STUDENT') NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `WriterID` int(11) DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ParentMessageID` int(11) DEFAULT NULL,
  `IsUnread` tinyint(1) NOT NULL DEFAULT '1',
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `writer_messages`
--

INSERT INTO `writer_messages` (`ID`, `Subject`, `Message`, `Owner`, `StudentID`, `WriterID`, `OrderID`, `ParentMessageID`, `IsUnread`, `DateCreated`) VALUES
(1, 'gfhfgh fgh  ', 'jgjghjghjfgj ghj j fgj ghj g', 'WEBMASTER', NULL, 5, NULL, NULL, 1, '2012-07-02 20:21:30'),
(2, 'olololo lo lolo lolo', 'lolololol ol ol o lo lol o', 'WEBMASTER', NULL, 1, NULL, NULL, 1, '2012-07-02 21:27:31'),
(3, 'gdfgdfg fdg dfg df ', 'gdfgsfgsdffg sdf gsdfg dfg', 'WEBMASTER', 1, NULL, NULL, NULL, 1, '2012-07-02 21:29:24'),
(4, 'h dh gh dfhhhf h', 'hh gh h fgh fgh fgfg hhfg hhfgh fg hdgh fgh gh fgh f', 'WEBMASTER', 1, NULL, 1, NULL, 0, '2012-07-02 21:34:14'),
(5, 'gfdf gdfh gh', 'gh fgh fgh fhhg ', 'WEBMASTER', 1, NULL, NULL, NULL, 1, '2012-07-02 21:36:02'),
(6, 'the next msg ololo', 'testing messaging feature', 'WEBMASTER', NULL, NULL, 1, NULL, 0, '2012-07-03 20:30:20'),
(7, 'hg hfghh fgh j ', 'hfjh fj f fj fj fjj fhjfghfh ', 'WEBMASTER', NULL, NULL, 2, NULL, 0, '2012-07-03 20:37:50'),
(8, 'test test test test test ', 'test test test test test test test test test test test test test test test test test test test test test test test test test test test ', 'WEBMASTER', NULL, NULL, 1, NULL, 1, '2012-07-03 23:54:31'),
(9, 'some test message', 'some test messagesome test messagesome test messagesome test message', 'WEBMASTER', NULL, NULL, 1, NULL, 1, '2012-07-06 13:17:17'),
(10, 'test test test test test test ', 'test test test test test test test test test test test test test test test test test test test test test test test test ', 'WEBMASTER', NULL, NULL, 4, NULL, 1, '2012-07-06 15:29:46'),
(11, 'initial comment', 'I have start writing this essay', 'WEBMASTER', NULL, NULL, 2, NULL, 1, '2012-07-06 22:11:46'),
(12, 'demo demo ', 'demo demo demo demo demo ', 'WEBMASTER', NULL, NULL, 3, NULL, 0, '2012-07-09 08:00:09');

-- --------------------------------------------------------

--
-- Table structure for table `writer_orders`
--

CREATE TABLE IF NOT EXISTS `writer_orders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` text NOT NULL,
  `Description` text NOT NULL,
  `Status` enum('NEW','IN PROGRESS','PENDING','CLOSED') NOT NULL,
  `ClosedStatus` enum('DONE','REJECTED','UNCOMPLETED') NOT NULL DEFAULT 'UNCOMPLETED',
  `PriceID` int(11) NOT NULL,
  `Level` enum('High School','College','University') NOT NULL DEFAULT 'High School',
  `Format` enum('MLA','APA','Chicago','Turabian') NOT NULL DEFAULT 'MLA',
  `Pages` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `WriterID` int(11) NOT NULL,
  `Price` double NOT NULL,
  `Paid` double NOT NULL,
  `Discount` double NOT NULL,
  `Refund` tinyint(1) NOT NULL,
  `OrderToken` varchar(32) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateDeadline` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `OrderToken` (`OrderToken`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `writer_orders`
--

INSERT INTO `writer_orders` (`ID`, `Title`, `Description`, `Status`, `ClosedStatus`, `PriceID`, `Level`, `Format`, `Pages`, `StudentID`, `WriterID`, `Price`, `Paid`, `Discount`, `Refund`, `OrderToken`, `DateCreated`, `DateDeadline`) VALUES
(1, 'test', 'jfhj ghj hj ghj gh j', 'NEW', 'UNCOMPLETED', 3, 'High School', 'MLA', 7, 2, 0, 87.85, 29.28, 0, 0, '7adec28d15c7ec3ba15184f258760029', '2012-07-09 23:45:18', '2012-07-16 23:45:18');

-- --------------------------------------------------------

--
-- Table structure for table `writer_prices`
--

CREATE TABLE IF NOT EXISTS `writer_prices` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Price` double NOT NULL,
  `Hours` double NOT NULL,
  `Weeks` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `writer_prices`
--

INSERT INTO `writer_prices` (`ID`, `Name`, `Price`, `Hours`, `Weeks`) VALUES
(1, 'Flash - in 8 - hours $39.55/page ', 39.55, 8, 0),
(2, 'Save money - two weeks - $10.55/page', 10.55, 0, 2),
(3, 'Economical - one week - $12.55/page', 12.55, 0, 1),
(4, 'Regular - in 96 hours - $15.55/page', 15.55, 96, 0),
(5, 'Fast - in 48 hours - 18.55/page ', 18.55, 48, 0),
(6, 'Emergency - in 24 - hours $24.55/page ', 24.55, 24, 0),
(7, 'Rush - in 12 - hours $34.55/page ', 34.55, 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `writer_students`
--

CREATE TABLE IF NOT EXISTS `writer_students` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Login` text NOT NULL,
  `Password` text NOT NULL,
  `Email` text,
  `Phone` text,
  `UserToken` varchar(32) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `IsTemporary` tinyint(1) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UserToken` (`UserToken`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `writer_students`
--

INSERT INTO `writer_students` (`ID`, `Name`, `Login`, `Password`, `Email`, `Phone`, `UserToken`, `Active`, `IsTemporary`, `DateCreated`, `DateLastAccess`) VALUES
(1, 'eu_u3yrv9lnnv', 'eu_u3yrv9lnnv', '600e56e7e72c532dec1b7df509023a2c', 'test@test.com', NULL, '36d103d7bd2451e6e5d2f481a300575c', 0, 1, '2012-07-09 23:41:57', '2012-07-09 23:41:57'),
(2, 'eu_nz0e8z5gka', 'eu_nz0e8z5gka', '4ad1051f1ef2ae42499d3824509896ed', 'test@test.com', NULL, '26c4329f623961ffd812c2e7a9b25d3a', 0, 1, '2012-07-09 23:45:18', '2012-07-09 23:45:18');

-- --------------------------------------------------------

--
-- Table structure for table `writer_subjects`
--

CREATE TABLE IF NOT EXISTS `writer_subjects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `writer_subjects`
--

INSERT INTO `writer_subjects` (`ID`, `Name`) VALUES
(1, 'Art Subject toooo looooong'),
(2, 'Art Subject'),
(4, 'olololololo'),
(5, 'hhfhhgh dh dfh '),
(6, 'dh hfgh dgh fgh h '),
(7, 'hfgh dfh fh df hf'),
(8, 'dghdgh g hfg hgh'),
(9, 'hfg hfhf hfh f hfh');

-- --------------------------------------------------------

--
-- Table structure for table `writer_writers`
--

CREATE TABLE IF NOT EXISTS `writer_writers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  `Login` text NOT NULL,
  `Password` text NOT NULL,
  `Subjects` text,
  `CardNumber` text NOT NULL,
  `CardType` enum('VISA','MC','Disc','AmEx','Diners') NOT NULL DEFAULT 'VISA',
  `University` text,
  `Email` text,
  `IM` text,
  `Phone` text,
  `Active` tinyint(1) NOT NULL,
  `IsOnline` tinyint(1) NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `writer_writers`
--

INSERT INTO `writer_writers` (`ID`, `Name`, `Login`, `Password`, `Subjects`, `CardNumber`, `CardType`, `University`, `Email`, `IM`, `Phone`, `Active`, `IsOnline`, `DateLastAccess`, `DateCreated`) VALUES
(5, 'Test User 1', 'writer1', 'fe01ce2a7fbac8fafaed7c982a04e229', 's1 s2 s3', '1234-1234-1234', 'VISA', 'demo', 'my@mail.com', 'wwww', 'eeee', 1, 0, '2012-06-30 00:04:05', '2012-06-30 00:04:05'),
(6, 'Test User Second', 'testuser', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '', 'VISA', '', 'my@mail.com', '', '', 1, 0, '2012-06-30 15:39:13', '2012-06-30 15:39:13'),
(7, 'Test User Third', 'testuser', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '', 'VISA', '', 'my@mail.com', '', '', 1, 0, '2012-06-30 15:40:05', '2012-06-30 15:40:05'),
(8, 'Test User 4', 'testuser1', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '', 'VISA', '', 'soulcor@gmail.com', '', '', 1, 0, '2012-06-30 19:29:25', '2012-06-30 19:29:25'),
(9, 'Test User 5', 'testuser', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '', 'VISA', '', 'soulcor@gmail.com', '', '', 1, 0, '2012-06-30 19:30:56', '2012-06-30 19:30:56'),
(10, 'Test User 6', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', '', 'soulcor@gmail.com', '', '', 0, 0, '2012-06-30 19:37:08', '2012-06-30 19:37:08'),
(11, 'Test User 7', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', '', 'soulcor@gmail.com', '', '', 1, 0, '2012-06-30 20:33:01', '2012-06-30 20:33:01'),
(12, 'Test User 8', 'writer1', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '1234-1234-1234', 'VISA', 'demo', 'soulcor@gmail.com', '', '', 1, 0, '2012-06-30 20:39:23', '2012-06-30 20:39:23'),
(13, 'Test User 9', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', '', 'soulcor@gmail.com', '', '', 0, 0, '2012-06-30 21:11:38', '2012-06-30 21:11:38'),
(14, 'Test User 10', 'writer1', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '1234-1234-1234', 'VISA', 'demo', 'soulcor@gmail.com', '', '', 1, 0, '2012-06-30 21:37:33', '2012-06-30 21:37:33'),
(15, 'Test User 11', 'testuser2', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-06-30 22:42:56', '2012-06-30 22:42:56'),
(16, 'Test User 12', 'testuser2', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-06-30 22:42:57', '2012-06-30 22:42:57'),
(17, 'Test User 13', 'testuser2', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-06-30 22:42:59', '2012-06-30 22:42:59'),
(18, 'Test User 14', 'testuser2', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-06-30 22:43:25', '2012-06-30 22:43:25'),
(19, 'Test User 15', 'testuser2', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-06-30 22:43:34', '2012-06-30 22:43:34'),
(20, 'Test User 16', 'testuser2', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-06-30 22:43:36', '2012-06-30 22:43:36'),
(21, 'Test User 17', 'testuser2', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-06-30 22:43:37', '2012-06-30 22:43:37'),
(22, 'Test User 18', 'testuser2', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-06-30 22:43:37', '2012-06-30 22:43:37'),
(23, 'Test User Nineteen', 'writer1', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '1234-1234-1234', 'VISA', 'demo3', 'soulcor@gmail.com', '', '', 0, 0, '2012-06-30 23:32:55', '2012-06-30 23:32:55'),
(24, 'Test User', 'writer1', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '1234-1234-1234', 'VISA', 'demo', 'soulcor@gmail.com', '', '', 0, 0, '2012-07-01 08:13:51', '2012-07-01 08:13:51'),
(25, 'Test User Name', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', '', 'soulcor@gmail.com', '', '', 0, 0, '2012-07-01 08:15:41', '2012-07-01 08:15:41'),
(26, 'Test User', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', '', 'soulcor@gmail.com', '', '', 0, 0, '2012-07-01 08:19:28', '2012-07-01 08:19:28'),
(27, 'Test User Q', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '1234-1234-1234', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-07-01 08:59:26', '2012-07-01 08:59:26'),
(28, 'Test User W', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-07-01 08:59:50', '2012-07-01 08:59:50'),
(32, 'AAAAA AAAA', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', '', 'my@mail.com', '', '', 0, 0, '2012-07-01 23:01:37', '2012-07-01 23:01:37'),
(33, 'QQQ QQQQQ', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', '', 'my@mail.com', '', '', 0, 0, '2012-07-01 23:19:56', '2012-07-01 23:19:56'),
(34, 'QWE QASWE', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', 'demo', 'my@mail.com', '', '', 0, 0, '2012-07-01 23:20:15', '2012-07-01 23:20:15'),
(35, 'VVVV VVVVV', 'writer1', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '', 'VISA', '', 'my@mail.com', '', '', 0, 0, '2012-07-01 23:20:56', '2012-07-01 23:20:56'),
(36, 'Test User Name', 'writer1', '4a15e51376377794e4daddd092cb680a', '', '', 'VISA', '', 'my@mail.com', '', '', 0, 0, '2012-07-01 23:21:16', '2012-07-01 23:21:16'),
(37, 'EEEEE WWWW', 'writer1', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '1234-1234-1234', 'VISA', 'demo', 'my@mail.com', '', '', 1, 0, '2012-07-01 23:21:47', '2012-07-01 23:21:47'),
(38, 'EEWQWQ QQ', 'writer1', '4a15e51376377794e4daddd092cb680a', 's1 s2 s3', '1234-1234-1234', 'VISA', 'demo', 'my@mail.com', 'wwww', 'eeee', 1, 0, '2012-07-01 23:22:10', '2012-07-01 23:22:10'),
(39, 'test user name second', 'testuser', 'de9dad5fadef773a3d51a48ac68c42b3', '', '', 'VISA', '', 'f.andriy@gmail.com', '', '', 1, 0, '2012-07-02 08:17:46', '2012-07-02 08:17:46');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

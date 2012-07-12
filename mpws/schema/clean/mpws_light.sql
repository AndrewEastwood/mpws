-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 12, 2012 at 09:53 AM
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
(3, 'test3', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 1, '', '2012-07-12 06:34:30', '2012-06-25 23:56:20');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

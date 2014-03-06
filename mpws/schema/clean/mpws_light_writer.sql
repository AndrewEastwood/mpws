-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 06, 2014 at 12:53 AM
-- Server version: 5.5.35
-- PHP Version: 5.3.10-1ubuntu3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mpws_light`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllShopCategoryBrands`(IN catid INT)
BEGIN
  SELECT o.ID,
         o.Name
  FROM   shop_products AS p
         LEFT JOIN shop_origins AS o
                ON p.OriginID = o.ID
  WHERE  p.Status = 'ACTIVE'
         AND o.Status = 'ACTIVE'
         AND p.CategoryID = catid
  GROUP  BY o.Name; 
-- SELECT o.ID, o.Name FROM shop_products AS `p` LEFT JOIN shop_origins AS `o` ON p.OriginID = o.ID WHERE p.Enabled = 1 AND o.Enabled = 1 AND p.CategoryID = catid GROUP BY o.Name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllShopCategorySubCategories`(IN catid INT)
BEGIN
  SELECT c.ID,
         c.Name
  FROM   shop_products AS p
         LEFT JOIN shop_categories AS c
                ON p.CategoryID = c.ID
  WHERE  p.Status = 'ACTIVE'
         AND c.Status = 'ACTIVE'
         AND c.ParentID = catid
  GROUP  BY c.Name; 
-- SELECT c.ID, c.ParentID, c.Name FROM shop_categories AS `c` WHERE c.ParentID = catid AND c.Enabled = 1 GROUP BY c.Name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryBrands`(IN catid INT)
BEGIN
SELECT o.ID, o.Name FROM shop_products AS `p` LEFT JOIN shop_origins AS `o` ON p.OriginID = o.ID WHERE p.CategoryID = catid GROUP BY o.Name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryLocation`(IN catid INT)
BEGIN
SELECT T2.ID, T2.Name
FROM (
    SELECT
        @r AS _id,
        (SELECT @r := ParentID FROM shop_categories WHERE ID = _id) AS ParentID,
        @l := @l + 1 AS lvl
    FROM
        (SELECT @r := catid, @l := 0) vars,
        shop_categories h
    WHERE @r <> 0) T1
JOIN shop_categories T2
ON T1._id = T2.ID
ORDER BY T1.lvl DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryPriceEdges`(IN catid INT)
BEGIN
SELECT MAX( p.Price ) AS 'PriceMax' , MIN( p.price ) AS 'PriceMin' FROM shop_products AS  `p` WHERE p.CategoryID = catid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategorySubCategories`(IN catid INT)
BEGIN
SELECT
  c.ID, c.ParentID, c.Name,
  (SELECT count(*) FROM shop_products AS `p` WHERE p.CategoryID = c.ID AND p.Status = 'ACTIVE') AS `ProductCount`
FROM shop_categories AS `c` WHERE c.ParentID = catid AND c.Status = 'ACTIVE' GROUP BY c.Name;
END$$

DELIMITER ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `writer_documents`
--

INSERT INTO `writer_documents` (`ID`, `Name`, `Price`, `Discount`) VALUES
(7, 'Essay Simple', 0, 0),
(8, 'Essay Smart', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `writer_invoices`
--

CREATE TABLE IF NOT EXISTS `writer_invoices` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `inv_type` enum('PAYMENT','REFUND') NOT NULL,
  `invoice_id` bigint(20) NOT NULL,
  `sid` int(11) NOT NULL,
  `key` varchar(32) NOT NULL,
  `order_number` bigint(20) NOT NULL,
  `total` double NOT NULL,
  `merchant_order_id` varchar(32) NOT NULL,
  `credit_card_processed` varchar(1) NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `writer_invoices`
--

INSERT INTO `writer_invoices` (`ID`, `inv_type`, `invoice_id`, `sid`, `key`, `order_number`, `total`, `merchant_order_id`, `credit_card_processed`, `email`, `phone`) VALUES
(3, 'PAYMENT', 4766310026, 1799160, '454DDCCBDADC5C822127E724FBF79D54', 4766310017, 139.95, 'cefbef67d8480b8f827630a5f41e437c', 'Y', 'andrew.ivaskevych@gmail.com', '123-456-7890 '),
(4, 'PAYMENT', 4767332839, 1799160, '5804558C3EBC8C6FB7D8CD08C4AD450F', 4767332830, 39.55, 'c9198e0546fbab932edc1d22ffb69429', 'Y', 'soulcor@gmail.com', '123-456-7890 '),
(5, 'PAYMENT', 4767332839, 1799160, '5804558C3EBC8C6FB7D8CD08C4AD450F', 4767332830, 39.55, 'c9198e0546fbab932edc1d22ffb69429', 'Y', 'soulcor@gmail.com', '123-456-7890 '),
(6, 'PAYMENT', 4767430597, 1799160, '5804558C3EBC8C6FB7D8CD08C4AD450F', 4767430588, 39.55, 'ef2418b7c3741b07552cf094eb8eaaa6', 'Y', 'soulcor@gmail.com', '123-456-7890 ');

-- --------------------------------------------------------

--
-- Table structure for table `writer_messages`
--

CREATE TABLE IF NOT EXISTS `writer_messages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Subject` text NOT NULL,
  `Message` text NOT NULL,
  `Owner` enum('WEBMASTER','WRITER','STUDENT','SYSTEM') NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `WriterID` int(11) DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ParentMessageID` int(11) DEFAULT NULL,
  `IsUnread` tinyint(1) NOT NULL DEFAULT '1',
  `IsPublic` tinyint(1) NOT NULL DEFAULT '0',
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=95 ;

--
-- Dumping data for table `writer_messages`
--

INSERT INTO `writer_messages` (`ID`, `Subject`, `Message`, `Owner`, `StudentID`, `WriterID`, `OrderID`, `ParentMessageID`, `IsUnread`, `IsPublic`, `DateCreated`) VALUES
(4, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:09:36'),
(5, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:10:02'),
(6, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:11:51'),
(7, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:13:47'),
(8, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:21:20'),
(9, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:21:32'),
(10, 'Order Was Unassigned.', 'Sent to Task Queue', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:33:00'),
(11, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:40:48'),
(12, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:42:18'),
(13, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:42:31'),
(14, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:44:24'),
(15, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:45:57'),
(16, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 22:46:07'),
(17, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:46:54'),
(18, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:47:28'),
(19, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:47:44'),
(20, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:48:00'),
(21, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:49:02'),
(22, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:50:11'),
(23, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:51:19'),
(24, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:51:52'),
(25, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:52:20'),
(26, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:52:30'),
(27, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:53:21'),
(28, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:53:33'),
(29, 'Order Is Rejected By Writer', 'Assign this order to another writer.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 22:54:14'),
(30, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 23:09:46'),
(31, 'Writer Started This Order', 'Public Status: IN PROGRESS', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 23:11:17'),
(32, 'Resolution Document Is Modified', 'Document Link: hfghfghgfhg', 'SYSTEM', 1, NULL, 3, NULL, 1, 0, '2012-07-26 23:15:15'),
(33, 'Need Approval', 'Waiting for approval.', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 23:15:20'),
(34, 'Order Was Approved To Review.', '<div>PublicStatus: PENDING</div>\n<div>InternalStatus: APPROVED</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 23:18:47'),
(35, 'Order Was Updated.', '<div>PublicStatus: IN PROGRESS</div>\n<div>InternalStatus: PENDING</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 23:20:47'),
(36, 'Order Was Approved To Review.', '<div>PublicStatus: PENDING</div>\n<div>InternalStatus: APPROVED</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-26 23:21:30'),
(37, 'Buyer Wants To Rework', 'Ask owner for more details to rework.', 'SYSTEM', 5, NULL, 3, NULL, 1, 0, '2012-07-26 23:24:31'),
(38, 'Writer Started This Order', 'Public Status: IN PROGRESS', 'SYSTEM', NULL, 1, 3, NULL, 1, 0, '2012-07-26 23:28:28'),
(39, 'test student', 'test student test student test student test student', 'STUDENT', 5, NULL, 3, NULL, 1, 1, '2012-07-26 23:31:27'),
(40, 'wrietr answer', 'wrietr answerwrietr answerwrietr answerwrietr answerwrietr answer', 'WRITER', NULL, 1, 3, NULL, 1, 1, '2012-07-26 23:34:39'),
(41, 'wrietr answer', 'wrietr answerwrietr answerwrietr answerwrietr answerwrietr answer', 'WRITER', NULL, 1, 3, NULL, 0, 1, '2012-07-26 23:59:27'),
(42, 'Buyer Wants Refund', 'You must clarify the reason of refund action.', 'SYSTEM', 5, NULL, 3, NULL, 1, 0, '2012-07-27 00:27:21'),
(43, 'Order Was Updated.', '<div>PublicStatus: TO REFUND</div>\n<div>InternalStatus: CLOSED</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-27 00:37:54'),
(44, 'Order Was Updated.', '<div>PublicStatus: IN PROGRESS</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-27 00:43:10'),
(45, 'Buyer Wants Refund', 'You must clarify the reason of refund action.', 'SYSTEM', 5, NULL, 3, NULL, 1, 0, '2012-07-27 00:43:16'),
(46, 'Order Is Reopened', 'Owner has reopened this order.', 'SYSTEM', 5, NULL, 3, NULL, 1, 0, '2012-07-27 00:46:55'),
(47, 'Order Was Updated.', '<div>PublicStatus: TO REFUND</div>\n<div>InternalStatus: CLOSED</div>\n<div>Credits: 35</div>\n', 'SYSTEM', NULL, NULL, 3, NULL, 1, 0, '2012-07-27 00:49:50'),
(48, 'Sources were changed.', '1<br>3', 'SYSTEM', 6, NULL, 4, NULL, 1, 0, '2012-07-27 16:06:16'),
(49, 'Sources were changed.', 'All sources were removed.', 'SYSTEM', 6, NULL, 4, NULL, 1, 0, '2012-07-27 16:07:58'),
(50, 'Sources were changed.', 'demo 2', 'SYSTEM', 6, NULL, 4, NULL, 1, 0, '2012-07-27 16:08:08'),
(51, 'Writer Started This Order', 'Public Status: IN PROGRESS', 'SYSTEM', NULL, 1, 4, NULL, 1, 0, '2012-07-27 16:22:37'),
(52, 'Order Is Reopened', 'Owner has reopened this order.', 'SYSTEM', 6, NULL, 3, NULL, 1, 0, '2012-07-27 16:27:45'),
(53, 'Order Is Closed', 'Owner has closed this order.', 'SYSTEM', 6, NULL, 3, NULL, 1, 0, '2012-07-27 16:30:17'),
(54, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 8, NULL, 1, 0, '2012-07-28 02:28:26'),
(55, 'Date Deadline is changed', '2012-07-28 03:30:00 => 2012-07-29 18:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 18:58:03'),
(56, 'Date Deadline is changed', '2012-07-29 18:00:00 => 2012-07-29 18:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:00:40'),
(57, 'Date Deadline is changed', '2012-07-29 22:00:00 => 2012-07-29 14:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:00:58'),
(58, 'Date Deadline is changed', '2012-07-29 18:00:00 => 2012-07-29 18:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:01:07'),
(59, 'Date Deadline is changed', '2012-07-29 22:00:00 => 2012-07-29 19:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:06:11'),
(60, 'Date Deadline is changed', '2012-07-29 23:00:00 => 2012-07-29 20:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:06:40'),
(61, 'Date Deadline is changed', '2012-07-30 00:00:00 => 2012-07-29 14:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:06:59'),
(62, 'Date Deadline is changed', '2012-07-29 18:00:00 => 2012-07-29 16:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 19:15:58'),
(63, 'Date Deadline is changed', '2012-07-29 20:00:00 => 2012-07-29 17:00:00', 'SYSTEM', 1, 1, 8, NULL, 1, 0, '2012-07-29 20:15:44'),
(64, 'Writer Started This Order', 'Public Status: IN PROGRESS', 'SYSTEM', NULL, 1, 8, NULL, 1, 0, '2012-07-29 20:27:11'),
(65, 'Order Is Closed', 'Owner has closed this order.', 'SYSTEM', 1, NULL, 8, NULL, 1, 0, '2012-07-29 20:37:11'),
(66, 'Order Is Reopened', 'Owner has reopened this order.', 'SYSTEM', 1, NULL, 8, NULL, 1, 0, '2012-07-29 21:46:04'),
(67, 'demo demo demo', 'demo demo demodemo demo demodemo demo demodemo demo demodemo demo demodemo demo demodemo demo demo', 'WEBMASTER', NULL, NULL, 8, NULL, 1, 0, '2012-07-29 22:47:39'),
(68, 'test', 'test', 'STUDENT', 1, NULL, 8, NULL, 0, 1, '2012-08-11 12:49:05'),
(69, 'test', 'test', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 12:51:06'),
(70, 'demo 3', 'demo 3 .. demo 3 .. demo 3 .. demo 3 .. demo 3 .. demo 3 .. ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 12:56:08'),
(71, 'test >> test >> test >> test >> test >> ', 'test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 13:05:26'),
(72, 'test >> test >> test >> test >> test >> ', 'test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 13:10:41'),
(73, 'test >> test >> test >> test >> test >> ', 'test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 13:12:42'),
(74, 'test >> test >> test >> test >> test >> ', 'test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> test >> ', 'STUDENT', 1, NULL, 8, NULL, 1, 1, '2012-08-11 13:14:48'),
(75, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=1">DEMO WRITER</a>', 'SYSTEM', NULL, NULL, 10, NULL, 1, 0, '2012-08-20 23:22:15'),
(76, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=4">DEMO WRITER LOCAL</a>', 'SYSTEM', NULL, NULL, 10, NULL, 1, 0, '2012-08-20 23:22:45'),
(77, 'Order Was Updated.', '<div>PublicStatus: IN PROGRESS</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 10</div>\n', 'SYSTEM', NULL, NULL, 13, NULL, 1, 0, '2012-08-22 00:12:38'),
(78, 'Order Was Updated.', '<div>PublicStatus: IN PROGRESS</div>\n<div>InternalStatus: PENDING</div>\n<div>Credits: 10</div>\n', 'SYSTEM', NULL, NULL, 13, NULL, 1, 0, '2012-08-22 00:12:53'),
(79, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 21:00:00 => 2012-08-30 12:00:00<br>Writer Local Time: <br>2012-08-31 08:00:00 => 2012-08-30 23:00:00', 'SYSTEM', 1, 1, 10, NULL, 1, 0, '2012-08-22 01:45:20'),
(80, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 22:00:00 => 2012-08-30 13:00:00<br>Writer Local Time: <br>2012-08-31 09:00:00 => 2012-08-31 00:00:00', 'SYSTEM', 1, 1, 10, NULL, 1, 0, '2012-08-22 01:46:42'),
(81, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 23:00:00 => 2012-08-30 14:00:00<br>Writer Local Time: <br>2012-08-31 10:00:00 => 2012-08-31 01:00:00', 'SYSTEM', 1, 1, 10, NULL, 1, 0, '2012-08-22 01:46:54'),
(82, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 20:00:00 => 2012-08-30 12:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 01:56:17'),
(83, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=4">DEMO WRITER LOCAL</a>', 'SYSTEM', NULL, NULL, 13, NULL, 1, 0, '2012-08-22 01:56:27'),
(84, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 17:00:00 => 2012-08-30 16:00:00<br>Writer Local Time: <br>2012-08-30 18:00:00 => 2012-08-30 22:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:03:07'),
(85, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 21:00:00 => 2012-08-30 05:00:00<br>Writer Local Time: <br>2012-08-30 22:00:00 => 2012-08-30 11:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:04:17'),
(86, 'Date Deadline is changed', 'Owner Local Time: <br>1346306400 => 1346284800<br>Writer Local Time: <br>1346310000 => 1346306400', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:23:50'),
(87, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 08:00:00 => 2012-08-30 12:00:00<br>Writer Local Time: <br>2012-08-30 09:00:00 => 2012-08-30 18:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:25:17'),
(88, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 17:00:00 => 2012-08-30 13:00:00<br>Writer Local Time: <br>2012-08-30 18:00:00 => 2012-08-30 19:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:26:00'),
(89, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 18:00:00 => 2012-08-30 14:00:00<br>Writer Local Time: <br>2012-08-30 19:00:00 => 2012-08-30 20:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:28:26'),
(90, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 14:00:00 => 2012-08-30 15:00:00<br>Writer Local Time: <br>2012-08-30 20:00:00 => 2012-08-30 21:00:00', 'SYSTEM', 1, 1, 13, NULL, 1, 0, '2012-08-22 02:29:07'),
(91, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 14:00:00 => 2012-08-30 15:00:00', 'SYSTEM', 13, 13, 18, NULL, 1, 0, '2012-08-22 22:29:40'),
(92, 'Date Deadline is changed', 'Owner Local Time: <br>2012-08-30 15:00:00 => 2012-08-30 16:00:00', 'SYSTEM', 13, 13, 18, NULL, 1, 0, '2012-08-22 22:30:52'),
(93, 'Order Was Assigned To Writer.', 'Assigned To: <a href="writer.html?display=writers&action=details&oid=4">DEMO WRITER LOCAL</a>', 'SYSTEM', NULL, NULL, 1, NULL, 1, 0, '2012-08-25 18:12:11'),
(94, 'Order Was Updated.', '<div>PublicStatus: NEW</div>\n<div>InternalStatus: OPEN</div>\n<div>Credits: 10</div>\n', 'SYSTEM', NULL, NULL, 4, NULL, 1, 0, '2012-08-25 19:52:39');

-- --------------------------------------------------------

--
-- Table structure for table `writer_orders`
--

CREATE TABLE IF NOT EXISTS `writer_orders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` text NOT NULL,
  `Description` text NOT NULL,
  `ResolutionDocumentLink` text,
  `PublicStatus` enum('NEW','IN PROGRESS','PENDING','CLOSED','REWORK','REOPEN','TO REFUND') NOT NULL,
  `InternalStatus` enum('CLOSED','REJECTED','OPEN','APPROVED','PENDING') NOT NULL DEFAULT 'OPEN',
  `ReworkCount` int(11) NOT NULL,
  `DocumentID` int(11) NOT NULL,
  `SubjectID` int(11) NOT NULL,
  `PriceID` int(11) NOT NULL,
  `Level` enum('High School','College','University') NOT NULL DEFAULT 'High School',
  `Format` enum('MLA','APA','Chicago','Turabian') NOT NULL DEFAULT 'MLA',
  `Pages` int(11) NOT NULL,
  `UseSources` int(11) NOT NULL DEFAULT '0',
  `StudentID` int(11) NOT NULL,
  `WriterID` int(11) NOT NULL,
  `Price` double NOT NULL,
  `Credits` double NOT NULL,
  `Discount` double NOT NULL,
  `RefundToken` varchar(32) DEFAULT NULL,
  `OrderToken` varchar(32) NOT NULL,
  `TimeZone` varchar(50) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateDeadline` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `OrderToken` (`OrderToken`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `writer_orders`
--

INSERT INTO `writer_orders` (`ID`, `Title`, `Description`, `ResolutionDocumentLink`, `PublicStatus`, `InternalStatus`, `ReworkCount`, `DocumentID`, `SubjectID`, `PriceID`, `Level`, `Format`, `Pages`, `UseSources`, `StudentID`, `WriterID`, `Price`, `Credits`, `Discount`, `RefundToken`, `OrderToken`, `TimeZone`, `DateCreated`, `DateDeadline`) VALUES
(1, 'xxxgfdgdg dfg', '', NULL, 'NEW', 'OPEN', 0, 7, 10, 1, 'High School', 'MLA', 1, 0, 1, 0, 39.55, 10, 0, '', 'f03a4a9c48b90e54b99f84014dcdb787', 'Kwajalein', '2012-08-26 22:51:09', '2012-08-29 12:00:00');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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
(7, 'Rush - in 12 - hours $34.55/page ', 34.55, 12, 0),
(8, 'demo price', 0.01, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `writer_sale`
--

CREATE TABLE IF NOT EXISTS `writer_sale` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` text NOT NULL,
  `Description` text,
  `Sample` text,
  `Pages` double NOT NULL,
  `Price` double NOT NULL,
  `DocumentURL` text,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `writer_sale`
--

INSERT INTO `writer_sale` (`ID`, `Title`, `Description`, `Sample`, `Pages`, `Price`, `DocumentURL`, `DateCreated`) VALUES
(1, 'demo 1', '', '<h1>demo de<span style="text-decoration: underline;">mo dem<strong>o</strong></span> demo demo</h1>\r\n<p>sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt; sample txt &gt;&gt;&nbsp;</p>\r\n<ol>\r\n<li>some item</li>\r\n<li>some item</li>\r\n<li>some item</li>\r\n<li>some item</li>\r\n<li>some item</li>\r\n<li>some ite</li>\r\n</ol>\r\n<h1 style="text-align: left;"><br></h1><h1 style="text-align: left;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<img src="https://www.google.com.ua/logos/classicplus.png" alt="" align="none" style="font-size: 12px; font-weight: normal; text-align: center; "><br></h1><h1 style="text-align: left;"><br></h1><h1 style="text-align: left;"><br></h1><h1 style="text-align: left;"><img src="../static/wysiwyg/tiny_mce/themes/advanced/skins/default/img/buttons.png" alt="" width="94" height="78"></h1><div><br></div><div style="text-align: center;"><br></div><p style="text-align: center;"><br></p><p style="text-align: center;"><br></p>\r\n<p style="padding-left: 30px;">TROLOLOLO TROLOLOLO TROLOLOLO TROLOLOLO&nbsp;</p>\r\n<p style="padding-left: 30px;">&nbsp;</p>\r\n<p style="padding-left: 30px;">A TUT MAE BUTU SHE BILSHE TEXTU!!!! :)))) TROLOLOLO!!!! OTAk&nbsp;</p>', 1, 2.55, '', '0000-00-00 00:00:00'),
(3, 'XXXXXXX', 'xxxxxxx', '<p>demo&nbsp;demo&nbsp;demo&nbsp;demo&nbsp;demo&nbsp;demo&nbsp;</p>', 99, 99.99, 'http://essay-about.mpws.com/mpws/writer.html?display=sale&action=create', '0000-00-00 00:00:00'),
(4, 'ZZZZZ', 'zzzzzzz', '<p><strong>SECOND DEMO</strong></p>', 3, 12.99, 'http://essay-about.mpws.com/mpws/writer.html?display=sale&action=create', '2012-07-25 22:02:11'),
(5, 'demo sale 1', 'demo deo demo', '<p>demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;demo sale 1&nbsp;</p>', 2, 0, 'http://google.com', '2012-07-26 14:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `writer_sales`
--

CREATE TABLE IF NOT EXISTS `writer_sales` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SaleID` int(11) NOT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `IsActive` tinyint(1) NOT NULL DEFAULT '1',
  `SalesToken` varchar(32) DEFAULT NULL,
  `RefundToken` varchar(32) DEFAULT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `writer_sales`
--

INSERT INTO `writer_sales` (`ID`, `SaleID`, `StudentID`, `IsActive`, `SalesToken`, `RefundToken`, `DateCreated`) VALUES
(1, 1, NULL, 1, '45d7c03dcf78945415001c8a7caf9ca6', NULL, '2012-08-12 13:41:29'),
(2, 1, NULL, 1, '5a007b45213347bcc00d4c38cafd66c4', NULL, '2012-08-12 13:42:12'),
(3, 3, NULL, 1, 'c2e2d1dcb06da2ffede409e1f46e1d84', NULL, '2012-08-12 13:48:19'),
(4, 3, NULL, 1, '44e9d81056dc2aa709e97d0cadbf32d1', NULL, '2012-08-12 13:49:52'),
(5, 3, NULL, 1, '780059e35995533eee246c0c4faea86c', NULL, '2012-08-12 13:51:41'),
(6, 3, NULL, 1, 'ed743e1423c195c29b8123a257e7f0b8', NULL, '2012-08-12 13:53:47'),
(7, 1, NULL, 1, '2b7c72fbbcad068db81785a06dd3ff79', NULL, '2012-08-12 13:54:05'),
(8, 4, NULL, 1, '8e40a3f19d34e280ff2eb45e4d2564cb', NULL, '2012-08-15 22:33:57'),
(9, 4, NULL, 1, '2b6d84c2434d7986e2cf4128bfd2279f', NULL, '2012-08-15 22:37:24'),
(12, 4, NULL, 1, '91c402b9c86f1024e2acb29f2e65ad6a', NULL, '2012-08-26 00:44:32'),
(13, 3, NULL, 1, '02dc4bf1c057b8b9d3fee1b2892d2fa3', NULL, '2012-08-26 00:44:33'),
(14, 1, NULL, 1, 'e2097526ac48e7ab97a9690c75a96d01', NULL, '2012-08-26 00:44:35'),
(15, 4, NULL, 1, '1511c6a3f9763fcaecca648bd49fff24', NULL, '2012-08-26 00:45:01'),
(16, 3, NULL, 1, '400ea371dbe5304d4995ad042243f3bc', NULL, '2012-08-26 00:45:02'),
(17, 1, NULL, 1, '8b0a18d40dcb0627709948ae8735f8f2', NULL, '2012-08-26 00:45:03'),
(18, 4, NULL, 1, '935f5fa2f2f1ec96f25d92f8029a0bd5', NULL, '2012-08-26 00:45:51'),
(19, 3, NULL, 1, '4c25c37f3daf9e07308fce81d7d268b8', NULL, '2012-08-26 00:45:53'),
(20, 1, NULL, 1, '89b0ebc56b34d5a9da97899cd7d9768c', NULL, '2012-08-26 00:45:54'),
(21, 4, NULL, 1, '7cf6eca5b695611572e10086220873e5', NULL, '2012-08-26 00:47:47'),
(22, 3, NULL, 1, '8e9e94ea3a16fe64bb3fc3559907b151', NULL, '2012-08-26 00:47:48'),
(23, 1, NULL, 1, 'ac444eb0649ec6aa29c06715bcc2da6f', NULL, '2012-08-26 00:47:50'),
(24, 4, NULL, 1, '0d3f2dc06ce00adf54f122aed5c087e4', NULL, '2012-08-26 01:15:14'),
(25, 3, NULL, 1, '2d51bf48cfa5b9e29a0d124a81ed873a', NULL, '2012-08-26 01:15:15'),
(26, 1, NULL, 1, '0960900a36ef633bded466222805563d', NULL, '2012-08-26 01:15:16');

-- --------------------------------------------------------

--
-- Table structure for table `writer_sources`
--

CREATE TABLE IF NOT EXISTS `writer_sources` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderToken` varchar(32) NOT NULL,
  `SourceURL` text NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `IM` text NOT NULL,
  `Phone` text,
  `UserToken` varchar(32) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `IsOnline` tinyint(1) NOT NULL,
  `Billing_FirstName` text,
  `Billing_LastName` text,
  `Billing_Email` text,
  `Billing_Phone` text,
  `Billing_Address` text,
  `Billing_City` text,
  `Billing_State` text,
  `Billing_PostalCode` text,
  `Billing_Country` text,
  `IsTemporary` tinyint(1) NOT NULL,
  `ModifiedBy` enum('SYSTEM','USER') NOT NULL DEFAULT 'SYSTEM',
  `TimeZone` varchar(50) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UserToken` (`UserToken`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `writer_subjects`
--

CREATE TABLE IF NOT EXISTS `writer_subjects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `writer_subjects`
--

INSERT INTO `writer_subjects` (`ID`, `Name`) VALUES
(10, 'Subject One'),
(11, 'Subject Two');

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
  `ModifiedBy` enum('SYSTEM','USER') NOT NULL DEFAULT 'SYSTEM',
  `TimeZone` varchar(50) NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `writer_writers`
--

INSERT INTO `writer_writers` (`ID`, `Name`, `Login`, `Password`, `Subjects`, `CardNumber`, `CardType`, `University`, `Email`, `IM`, `Phone`, `Active`, `IsOnline`, `ModifiedBy`, `TimeZone`, `DateLastAccess`, `DateCreated`) VALUES
(1, 'DEMO WRITER', 'test', '098f6bcd4621d373cade4e832627b4f6', 'tes test testtt', '0000-0000-0000-0000', 'VISA', 'harvard', 'soulcor@gmail.com', 'skype.name.1', '123-123-1234', 1, 1, 'USER', 'Pacific/Tahiti', '2012-08-28 23:09:16', '2012-07-23 01:30:40'),
(4, 'DEMO WRITER LOCAL', 'writer1', '098f6bcd4621d373cade4e832627b4f6', '', '', 'VISA', '', 'my@mail.com', '', '', 1, 1, 'SYSTEM', 'Europe/London', '2012-08-25 19:15:23', '2012-08-09 22:32:54'),
(5, 'jfjhfjgjgfjfgjg', 'dfdsfsdfsdf', '552d0d8f3bea399c22d9ffc40f68d560', '', '', 'VISA', '', 'my@mail.com', '', '', 1, 0, 'SYSTEM', '', '2012-08-09 22:48:17', '2012-08-09 22:48:17');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

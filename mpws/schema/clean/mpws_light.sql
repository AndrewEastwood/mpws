-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 24, 2013 at 03:19 AM
-- Server version: 5.5.32
-- PHP Version: 5.3.10-1ubuntu3.8

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
-- Table structure for table `editor_content`
--

CREATE TABLE IF NOT EXISTS `editor_content` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Property` varchar(100) NOT NULL,
  `Value` text NOT NULL,
  `PageOwner` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `mpws_accounts`
--

CREATE TABLE IF NOT EXISTS `mpws_accounts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(200) COLLATE utf8_bin NOT NULL,
  `LastName` varchar(200) COLLATE utf8_bin NOT NULL,
  `EMail` varchar(100) COLLATE utf8_bin NOT NULL,
  `Telephone` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `FAX` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `IM` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `Password` varchar(50) COLLATE utf8_bin NOT NULL,
  `Delivery_Address1` varchar(200) COLLATE utf8_bin NOT NULL,
  `Delivery_Address2` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Delivery_City` varchar(300) COLLATE utf8_bin NOT NULL,
  `Delivery_PostCode` varchar(100) COLLATE utf8_bin NOT NULL,
  `Delivery_State` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Delivery_Country` varchar(200) COLLATE utf8_bin NOT NULL,
  `Delivery_Region` varchar(200) COLLATE utf8_bin NOT NULL,
  `Delivery_Company` varchar(300) COLLATE utf8_bin DEFAULT NULL,
  `Enabled` tinyint(1) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `EMail` (`EMail`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `mpws_customer`
--

CREATE TABLE IF NOT EXISTS `mpws_customer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` text COLLATE utf8_bin NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  `HomePage` varchar(200) COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `mpws_jobs`
--

CREATE TABLE IF NOT EXISTS `mpws_jobs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `Description` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `Action` text CHARACTER SET latin1 NOT NULL,
  `Schedule` datetime NOT NULL,
  `LastError` text CHARACTER SET latin1,
  `DateUpdated` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `mpws_subscripers`
--

CREATE TABLE IF NOT EXISTS `mpws_subscripers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AccountID` int(11) NOT NULL,
  `ContentType` enum('NEWSLETTER','OFFERS') COLLATE utf8_bin NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `AccountID` (`AccountID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `mpws_uploads`
--

CREATE TABLE IF NOT EXISTS `mpws_uploads` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Path` text CHARACTER SET latin1 NOT NULL,
  `Owner` text CHARACTER SET latin1 NOT NULL,
  `Description` text CHARACTER SET latin1,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `mpws_users`
--

CREATE TABLE IF NOT EXISTS `mpws_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `Password` varchar(32) CHARACTER SET latin1 NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `IsOnline` tinyint(1) NOT NULL,
  `Permisions` text CHARACTER SET latin1 NOT NULL,
  `Role` enum('SUPERADMIN','READER','REPORTER') CHARACTER SET latin1 NOT NULL DEFAULT 'READER',
  `DateLastAccess` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `reporting_all`
--

CREATE TABLE IF NOT EXISTS `reporting_all` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `ExternalKey` text NOT NULL,
  `DataPath` varchar(150) NOT NULL,
  `ReportDataUrl` varchar(250) DEFAULT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `reviews_reviews`
--

CREATE TABLE IF NOT EXISTS `reviews_reviews` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OriginIP` varchar(20) NOT NULL,
  `OriginDomain` varchar(50) NOT NULL,
  `OwnerID` int(11) NOT NULL,
  `ReviewerID` int(11) NOT NULL,
  `Nickname` text NOT NULL,
  `WouldRecommend` tinyint(1) NOT NULL,
  `Title` text NOT NULL,
  `ReviewText` text NOT NULL,
  `Rating` double NOT NULL,
  `Pros` text,
  `Cons` text,
  `ModerationStatus` enum('SUBMITTED','APPROVED','REJECTED') NOT NULL DEFAULT 'SUBMITTED',
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_categories`
--

CREATE TABLE IF NOT EXISTS `shop_categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RootID` int(11) NOT NULL,
  `ParentID` int(11) DEFAULT NULL,
  `CustomerID` int(11) NOT NULL,
  `SchemaID` int(11) NOT NULL,
  `Name` varchar(100) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `SchemaID` (`SchemaID`),
  KEY `RootID` (`RootID`,`ParentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_commands`
--

CREATE TABLE IF NOT EXISTS `shop_commands` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ExternalKey` text CHARACTER SET latin1 NOT NULL,
  `Name` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Description` text CHARACTER SET latin1 NOT NULL,
  `Action` text CHARACTER SET latin1 NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_currency`
--

CREATE TABLE IF NOT EXISTS `shop_currency` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `IsMain` tinyint(1) NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  `Currency` varchar(10) COLLATE utf8_bin NOT NULL,
  `Rate` decimal(10,2) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `ID_2` (`ID`),
  KEY `Currency` (`Currency`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_origins`
--

CREATE TABLE IF NOT EXISTS `shop_origins` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` int(11) NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  `HomePage` varchar(200) COLLATE utf8_bin NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_productAttributes`
--

CREATE TABLE IF NOT EXISTS `shop_productAttributes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Attribute` enum('IMAGE','LABEL','OTHER','ISBN','MANUFACTURER','EXPIRE','TAGS') COLLATE utf8_bin NOT NULL,
  `Value` text COLLATE utf8_bin,
  PRIMARY KEY (`ID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_productPrices`
--

CREATE TABLE IF NOT EXISTS `shop_productPrices` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_products`
--

CREATE TABLE IF NOT EXISTS `shop_products` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `OriginID` int(11) NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `ExternalKey` text COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin,
  `Specifications` text COLLATE utf8_bin,
  `Model` text COLLATE utf8_bin,
  `SKU` text COLLATE utf8_bin,
  `Price` decimal(10,2) NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `OriginID` (`OriginID`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='shop products' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_relations`
--

CREATE TABLE IF NOT EXISTS `shop_relations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductA_ID` int(11) NOT NULL,
  `ProductB_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `ProductB_ID` (`ProductB_ID`),
  KEY `ProductA_ID` (`ProductA_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `shop_specifications`
--

CREATE TABLE IF NOT EXISTS `shop_specifications` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) COLLATE utf8_bin NOT NULL,
  `Fields` text COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `test2`
--
CREATE TABLE IF NOT EXISTS `test2` (
`pNAME` varchar(200)
,`cName` varchar(100)
);
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

-- --------------------------------------------------------

--
-- Structure for view `test2`
--
DROP TABLE IF EXISTS `test2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `test2` AS select `p`.`Name` AS `pNAME`,`c`.`Name` AS `cName` from (`shop_products` `p` left join `shop_categories` `c` on((`p`.`CategoryID` = `c`.`ID`)));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mpws_accounts`
--
ALTER TABLE `mpws_accounts`
  ADD CONSTRAINT `mpws_accounts_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mpws_subscripers`
--
ALTER TABLE `mpws_subscripers`
  ADD CONSTRAINT `mpws_subscripers_ibfk_2` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shop_categories`
--
ALTER TABLE `shop_categories`
  ADD CONSTRAINT `shop_categories_ibfk_1` FOREIGN KEY (`SchemaID`) REFERENCES `shop_specifications` (`ID`);

--
-- Constraints for table `shop_productAttributes`
--
ALTER TABLE `shop_productAttributes`
  ADD CONSTRAINT `shop_productAttributes_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `shop_productPrices`
--
ALTER TABLE `shop_productPrices`
  ADD CONSTRAINT `shop_productPrices_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`);

--
-- Constraints for table `shop_products`
--
ALTER TABLE `shop_products`
  ADD CONSTRAINT `shop_products_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `shop_categories` (`ID`),
  ADD CONSTRAINT `shop_products_ibfk_2` FOREIGN KEY (`OriginID`) REFERENCES `shop_origins` (`ID`);

--
-- Constraints for table `shop_relations`
--
ALTER TABLE `shop_relations`
  ADD CONSTRAINT `shop_relations_ibfk_1` FOREIGN KEY (`ProductA_ID`) REFERENCES `shop_products` (`ID`),
  ADD CONSTRAINT `shop_relations_ibfk_2` FOREIGN KEY (`ProductB_ID`) REFERENCES `shop_products` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
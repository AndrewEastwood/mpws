-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: mpws_light
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `mpws_light`
--

/*!40000 DROP DATABASE IF EXISTS `mpws_light`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mpws_light` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;

USE `mpws_light`;

--
-- Table structure for table `mpws_accountAddresses`
--

DROP TABLE IF EXISTS `mpws_accountAddresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mpws_accountAddresses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `AccountID` int(11) DEFAULT NULL,
  `Address` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `POBox` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Country` varchar(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `City` varchar(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `AccountID` (`AccountID`),
  KEY `AccountID_2` (`AccountID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `mpws_accountAddresses_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mpws_accountAddresses_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_accountAddresses`
--

LOCK TABLES `mpws_accountAddresses` WRITE;
/*!40000 ALTER TABLE `mpws_accountAddresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `mpws_accountAddresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mpws_accounts`
--

DROP TABLE IF EXISTS `mpws_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mpws_accounts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `IsOnline` tinyint(1) NOT NULL DEFAULT '0',
  `FirstName` varchar(200) COLLATE utf8_bin NOT NULL,
  `LastName` varchar(200) COLLATE utf8_bin NOT NULL,
  `EMail` varchar(100) COLLATE utf8_bin NOT NULL,
  `Phone` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `Password` varchar(50) COLLATE utf8_bin NOT NULL,
  `ValidationString` varchar(400) COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED','TEMP') COLLATE utf8_bin NOT NULL DEFAULT 'TEMP',
  `DateLastAccess` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `EMail` (`EMail`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `mpws_accounts_ibfk_4` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_accounts`
--

LOCK TABLES `mpws_accounts` WRITE;
/*!40000 ALTER TABLE `mpws_accounts` DISABLE KEYS */;
INSERT INTO `mpws_accounts` VALUES (1,1,1,'Super','Admin','admin@mpws.ua','(000) 000-00-00','b2cff1386ea9cb5744731ac8e0d299dd','8de111e04ec15fc171c7723caa5342e2','ACTIVE','2014-11-18 11:02:37','2014-11-18 11:02:37','2014-11-18 11:02:37');
/*!40000 ALTER TABLE `mpws_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mpws_customer`
--

DROP TABLE IF EXISTS `mpws_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mpws_customer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ExternalKey` varchar(100) COLLATE utf8_bin NOT NULL,
  `Name` text COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `HomePage` varchar(200) COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_customer`
--

LOCK TABLES `mpws_customer` WRITE;
/*!40000 ALTER TABLE `mpws_customer` DISABLE KEYS */;
INSERT INTO `mpws_customer` VALUES (1,'pb_com_ua','pb_com_ua','ACTIVE','','2014-11-18 11:02:37','2014-11-18 11:02:37');
/*!40000 ALTER TABLE `mpws_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mpws_jobs`
--

DROP TABLE IF EXISTS `mpws_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mpws_jobs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) COLLATE utf8_bin NOT NULL,
  `Description` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `Action` text COLLATE utf8_bin NOT NULL,
  `Schedule` datetime NOT NULL,
  `LastError` text COLLATE utf8_bin,
  `DateUpdated` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `mpws_jobs_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_jobs`
--

LOCK TABLES `mpws_jobs` WRITE;
/*!40000 ALTER TABLE `mpws_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `mpws_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mpws_permissions`
--

DROP TABLE IF EXISTS `mpws_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mpws_permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `AccountID` int(11) NOT NULL,
  `CanAdmin` tinyint(1) NOT NULL,
  `CanCreate` tinyint(1) NOT NULL,
  `CanEdit` tinyint(1) NOT NULL,
  `CanUpload` tinyint(1) NOT NULL DEFAULT '0',
  `CanViewReports` tinyint(1) NOT NULL,
  `CanAddUsers` tinyint(1) NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `AccountID` (`AccountID`),
  CONSTRAINT `mpws_permissions_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_permissions`
--

LOCK TABLES `mpws_permissions` WRITE;
/*!40000 ALTER TABLE `mpws_permissions` DISABLE KEYS */;
INSERT INTO `mpws_permissions` VALUES (1,1,1,1,1,0,1,0,'2014-11-18 11:02:37','2014-11-18 11:02:37');
/*!40000 ALTER TABLE `mpws_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mpws_subscribers`
--

DROP TABLE IF EXISTS `mpws_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mpws_subscribers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `AccountID` int(11) NOT NULL,
  `ContentType` enum('NEWSLETTER','OFFERS') COLLATE utf8_bin NOT NULL,
  `Enabled` tinyint(1) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `AccountID` (`AccountID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `mpws_subscribers_ibfk_2` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mpws_subscribers_ibfk_3` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_subscribers`
--

LOCK TABLES `mpws_subscribers` WRITE;
/*!40000 ALTER TABLE `mpws_subscribers` DISABLE KEYS */;
/*!40000 ALTER TABLE `mpws_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mpws_tasks`
--

DROP TABLE IF EXISTS `mpws_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mpws_tasks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Hash` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `Group` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Name` varchar(100) CHARACTER SET utf16 COLLATE utf16_bin NOT NULL,
  `PrcPath` varchar(300) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Params` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `PID` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Result` varchar(10000) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Scheduled` tinyint(1) NOT NULL DEFAULT '0',
  `IsRunning` tinyint(1) NOT NULL DEFAULT '0',
  `Complete` tinyint(1) NOT NULL DEFAULT '0',
  `ManualCancel` tinyint(1) NOT NULL DEFAULT '0',
  `DateCreated` datetime NOT NULL,
  UNIQUE KEY `ID_2` (`ID`),
  KEY `ID` (`ID`),
  KEY `CustomerID` (`CustomerID`),
  KEY `Hash` (`Hash`),
  CONSTRAINT `mpws_tasks_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_tasks`
--

LOCK TABLES `mpws_tasks` WRITE;
/*!40000 ALTER TABLE `mpws_tasks` DISABLE KEYS */;
INSERT INTO `mpws_tasks` VALUES (1,'63a5a304c0ea7acaf658b34e025b3fed',1,'shop','importProductFeed','','','',NULL,0,0,0,0,'2014-11-18 11:21:24'),(2,'7d8ce2da031762131742ef88646f5da4',1,'shop','importProductFeed','','import_20141118_112336','','[\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440\\u0438 \\u0434\\u043b\\u044f \\u0434\\u0443\\u0445\\u043e\\u0432\\u0438\\u0445 \\u0448\\u0430\\u0444\\n\",\"[SUCCESS] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440\\u0438 \\u0434\\u043b\\u044f \\u0434\\u0443\\u0445\\u043e\\u0432\\u0438\\u0445 \\u0448\\u0430\\u0444\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436 \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043e\\u0432\\u043e\\u0447\\u0456\\u0432 \\\"\\u043f\\u043e-\\u043a\\u043e\\u0440\\u0435\\u0439\\u0441\\u044c\\u043a\\u0438\\\"\\n\",\"processing product \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0440\\u0443\\u043a\\u0442\\u043e\\u0432\\u0438\\u0445 \\u043d\\u0430\\u043f\\u043e\\u0457\\u0432 \\u0437 \\u043c\'\\u044f\\u043a\\u043e\\u0442\\u0442\\u044e\\n\",\"processing product \\u0414\\u0432\\u043e\\u0441\\u0442\\u043e\\u0440\\u043e\\u043d\\u043d\\u0456\\u0439 \\u0434\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436-\\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 (\\u0441\\u0435\\u0440\\u0435\\u0434\\u043d\\u044f)\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 \\u0434\\u043b\\u044f \\u0433\\u0440\\u0443\\u0431\\u043e\\u0433\\u043e \\u043d\\u0430\\u0442\\u0438\\u0440\\u0430\\u043d\\u043d\\u044f\\n\",\"processing product \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0456\\u0433\\u0443\\u0440\\u043d\\u0438\\u0445 \\u0432\\u0438\\u0440\\u043e\\u0431\\u0456\\u0432 \\u0437 \\u0442\\u0456\\u0441\\u0442\\u0430\\n\",\"processing product \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\\n\",\"[SUCCESS] \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\\n\",\"processing product \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\\n\",\"[SUCCESS] \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\\n\",\"processing product \\u041f\\u0440\\u0438\\u043b\\u0430\\u0434\\u0434\\u044f \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043f\\u0440\\u043e\\u0434\\u0443\\u043a\\u0442\\u0456\\u0432 \\u043a\\u0443\\u0431\\u0438\\u043a\\u0430\\u043c\\u0438\\n\",\"[SUCCESS] \\u041f\\u0440\\u0438\\u043b\\u0430\\u0434\\u0434\\u044f \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043f\\u0440\\u043e\\u0434\\u0443\\u043a\\u0442\\u0456\\u0432 \\u043a\\u0443\\u0431\\u0438\\u043a\\u0430\\u043c\\u0438\\n\",\"processing product \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\\n\",\"[SUCCESS] \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\\n\",\"processing product \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\\n\",\"[SUCCESS] \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 \\u0434\\u043b\\u044f \\u0433\\u0440\\u0443\\u0431\\u043e\\u0433\\u043e \\u043d\\u0430\\u0442\\u0438\\u0440\\u0430\\u043d\\u043d\\u044f\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430, \\u0433\\u0440\\u0443\\u0431\\u0430, \\u0437 \\u043d\\u0435\\u0440\\u0436\\u0430\\u0432\\u0456\\u044e\\u0447\\u043e\\u0457 \\u0441\\u0442\\u0430\\u043b\\u0456\\n\",\"[SUCCESS] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430, \\u0433\\u0440\\u0443\\u0431\\u0430, \\u0437 \\u043d\\u0435\\u0440\\u0436\\u0430\\u0432\\u0456\\u044e\\u0447\\u043e\\u0457 \\u0441\\u0442\\u0430\\u043b\\u0456\\n\",\"processing product \\u041d\\u0430\\u0431\\u0456\\u0440 \\u0444\\u043e\\u0440\\u043c\\u0443\\u0432\\u0430\\u043b\\u044c\\u043d\\u0438\\u0445 \\u0434\\u0438\\u0441\\u043a\\u0456\\u0432\\n\",\"[SUCCESS] \\u041d\\u0430\\u0431\\u0456\\u0440 \\u0444\\u043e\\u0440\\u043c\\u0443\\u0432\\u0430\\u043b\\u044c\\u043d\\u0438\\u0445 \\u0434\\u0438\\u0441\\u043a\\u0456\\u0432\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436 \\u0437 \\u043d\\u0435\\u0440\\u0436\\u0430\\u0432\\u0456\\u044e\\u0447\\u043e\\u0457 \\u0441\\u0442\\u0430\\u043b\\u0456 \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043a\\u0430\\u0440\\u0442\\u043e\\u043f\\u043b\\u0456 \\u0444\\u0440\\u0456\\n\",\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"[SUCCESS] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"[SUCCESS] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"[SUCCESS] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\"]',0,0,1,0,'2014-11-18 11:23:36'),(3,'0d426f6bf13ef0e6b5929b832901e812',1,'shop','importProductFeed','','import_20141118_114332','',NULL,0,0,0,0,'2014-11-18 11:43:32'),(4,'dc0afba996032afc8a0beb98f08bf766',1,'shop','importProductFeed','','import_20141118_114416','','[\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440\\u0438 \\u0434\\u043b\\u044f \\u0434\\u0443\\u0445\\u043e\\u0432\\u0438\\u0445 \\u0448\\u0430\\u0444\\n\",\"[SUCCESS] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440\\u0438 \\u0434\\u043b\\u044f \\u0434\\u0443\\u0445\\u043e\\u0432\\u0438\\u0445 \\u0448\\u0430\\u0444\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436 \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043e\\u0432\\u043e\\u0447\\u0456\\u0432 \\\"\\u043f\\u043e-\\u043a\\u043e\\u0440\\u0435\\u0439\\u0441\\u044c\\u043a\\u0438\\\"\\n\",\"[ERROR] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436 \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043e\\u0432\\u043e\\u0447\\u0456\\u0432 \\\"\\u043f\\u043e-\\u043a\\u043e\\u0440\\u0435\\u0439\\u0441\\u044c\\u043a\\u0438\\\"\\n\",\"processing product \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0440\\u0443\\u043a\\u0442\\u043e\\u0432\\u0438\\u0445 \\u043d\\u0430\\u043f\\u043e\\u0457\\u0432 \\u0437 \\u043c\'\\u044f\\u043a\\u043e\\u0442\\u0442\\u044e\\n\",\"[ERROR] \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0440\\u0443\\u043a\\u0442\\u043e\\u0432\\u0438\\u0445 \\u043d\\u0430\\u043f\\u043e\\u0457\\u0432 \\u0437 \\u043c\'\\u044f\\u043a\\u043e\\u0442\\u0442\\u044e\\n\",\"processing product \\u0414\\u0432\\u043e\\u0441\\u0442\\u043e\\u0440\\u043e\\u043d\\u043d\\u0456\\u0439 \\u0434\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436-\\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 (\\u0441\\u0435\\u0440\\u0435\\u0434\\u043d\\u044f)\\n\",\"[ERROR] \\u0414\\u0432\\u043e\\u0441\\u0442\\u043e\\u0440\\u043e\\u043d\\u043d\\u0456\\u0439 \\u0434\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436-\\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 (\\u0441\\u0435\\u0440\\u0435\\u0434\\u043d\\u044f)\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 \\u0434\\u043b\\u044f \\u0433\\u0440\\u0443\\u0431\\u043e\\u0433\\u043e \\u043d\\u0430\\u0442\\u0438\\u0440\\u0430\\u043d\\u043d\\u044f\\n\",\"[ERROR] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 \\u0434\\u043b\\u044f \\u0433\\u0440\\u0443\\u0431\\u043e\\u0433\\u043e \\u043d\\u0430\\u0442\\u0438\\u0440\\u0430\\u043d\\u043d\\u044f\\n\",\"processing product \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0456\\u0433\\u0443\\u0440\\u043d\\u0438\\u0445 \\u0432\\u0438\\u0440\\u043e\\u0431\\u0456\\u0432 \\u0437 \\u0442\\u0456\\u0441\\u0442\\u0430\\n\",\"[ERROR] \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0456\\u0433\\u0443\\u0440\\u043d\\u0438\\u0445 \\u0432\\u0438\\u0440\\u043e\\u0431\\u0456\\u0432 \\u0437 \\u0442\\u0456\\u0441\\u0442\\u0430\\n\",\"processing product \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\\n\",\"[SUCCESS] \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\\n\",\"processing product \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\\n\",\"[ERROR] \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\\n\",\"processing product \\u041f\\u0440\\u0438\\u043b\\u0430\\u0434\\u0434\\u044f \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043f\\u0440\\u043e\\u0434\\u0443\\u043a\\u0442\\u0456\\u0432 \\u043a\\u0443\\u0431\\u0438\\u043a\\u0430\\u043c\\u0438\\n\",\"[SUCCESS] \\u041f\\u0440\\u0438\\u043b\\u0430\\u0434\\u0434\\u044f \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043f\\u0440\\u043e\\u0434\\u0443\\u043a\\u0442\\u0456\\u0432 \\u043a\\u0443\\u0431\\u0438\\u043a\\u0430\\u043c\\u0438\\n\",\"processing product \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\\n\",\"[SUCCESS] \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\\n\",\"processing product \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\\n\",\"[SUCCESS] \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 \\u0434\\u043b\\u044f \\u0433\\u0440\\u0443\\u0431\\u043e\\u0433\\u043e \\u043d\\u0430\\u0442\\u0438\\u0440\\u0430\\u043d\\u043d\\u044f\\n\",\"[ERROR] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 \\u0434\\u043b\\u044f \\u0433\\u0440\\u0443\\u0431\\u043e\\u0433\\u043e \\u043d\\u0430\\u0442\\u0438\\u0440\\u0430\\u043d\\u043d\\u044f\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430, \\u0433\\u0440\\u0443\\u0431\\u0430, \\u0437 \\u043d\\u0435\\u0440\\u0436\\u0430\\u0432\\u0456\\u044e\\u0447\\u043e\\u0457 \\u0441\\u0442\\u0430\\u043b\\u0456\\n\",\"[SUCCESS] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430, \\u0433\\u0440\\u0443\\u0431\\u0430, \\u0437 \\u043d\\u0435\\u0440\\u0436\\u0430\\u0432\\u0456\\u044e\\u0447\\u043e\\u0457 \\u0441\\u0442\\u0430\\u043b\\u0456\\n\",\"processing product \\u041d\\u0430\\u0431\\u0456\\u0440 \\u0444\\u043e\\u0440\\u043c\\u0443\\u0432\\u0430\\u043b\\u044c\\u043d\\u0438\\u0445 \\u0434\\u0438\\u0441\\u043a\\u0456\\u0432\\n\",\"[SUCCESS] \\u041d\\u0430\\u0431\\u0456\\u0440 \\u0444\\u043e\\u0440\\u043c\\u0443\\u0432\\u0430\\u043b\\u044c\\u043d\\u0438\\u0445 \\u0434\\u0438\\u0441\\u043a\\u0456\\u0432\\n\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436 \\u0437 \\u043d\\u0435\\u0440\\u0436\\u0430\\u0432\\u0456\\u044e\\u0447\\u043e\\u0457 \\u0441\\u0442\\u0430\\u043b\\u0456 \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043a\\u0430\\u0440\\u0442\\u043e\\u043f\\u043b\\u0456 \\u0444\\u0440\\u0456\\n\",\"[ERROR] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436 \\u0437 \\u043d\\u0435\\u0440\\u0436\\u0430\\u0432\\u0456\\u044e\\u0447\\u043e\\u0457 \\u0441\\u0442\\u0430\\u043b\\u0456 \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043a\\u0430\\u0440\\u0442\\u043e\\u043f\\u043b\\u0456 \\u0444\\u0440\\u0456\\n\",\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"[SUCCESS] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"[SUCCESS] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"[SUCCESS] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\",\"[SUCCESS] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440 \\u0434\\u043b\\u044f \\u043c\\u044f\\u0441\\u043e\\u0440\\u0443\\u0431\\u043a\\u0438\\n\"]',0,1,0,0,'2014-11-18 11:44:16');
/*!40000 ALTER TABLE `mpws_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mpws_uploads`
--

DROP TABLE IF EXISTS `mpws_uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mpws_uploads` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Path` text CHARACTER SET latin1 NOT NULL,
  `Owner` text CHARACTER SET latin1 NOT NULL,
  `Description` text CHARACTER SET latin1,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `mpws_uploads_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_uploads`
--

LOCK TABLES `mpws_uploads` WRITE;
/*!40000 ALTER TABLE `mpws_uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `mpws_uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_boughts`
--

DROP TABLE IF EXISTS `shop_boughts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_boughts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `SellingPrice` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `IsPromo` tinyint(1) NOT NULL DEFAULT '0',
  `DateCreated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `OrderID` (`OrderID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_boughts_ibfk_5` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_boughts_ibfk_6` FOREIGN KEY (`OrderID`) REFERENCES `shop_orders` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_boughts_ibfk_7` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_boughts`
--

LOCK TABLES `shop_boughts` WRITE;
/*!40000 ALTER TABLE `shop_boughts` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_boughts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_categories`
--

DROP TABLE IF EXISTS `shop_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ParentID` int(11) DEFAULT NULL,
  `CustomerID` int(11) NOT NULL,
  `ExternalKey` varchar(50) COLLATE utf8_bin NOT NULL,
  `Name` varchar(100) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ParentID` (`ParentID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_categories_ibfk_5` FOREIGN KEY (`ParentID`) REFERENCES `shop_categories` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `shop_categories_ibfk_6` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_categories`
--

LOCK TABLES `shop_categories` WRITE;
/*!40000 ALTER TABLE `shop_categories` DISABLE KEYS */;
INSERT INTO `shop_categories` VALUES (3,NULL,1,'аксесуари','Аксесуари','','ACTIVE','2014-11-18 12:13:29','2014-11-18 12:13:29'),(4,NULL,1,'noname','noname','','ACTIVE','2014-11-18 12:13:35','2014-11-18 12:13:35');
/*!40000 ALTER TABLE `shop_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_commands`
--

DROP TABLE IF EXISTS `shop_commands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_commands` (
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
  KEY `Name` (`Name`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_commands_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_commands`
--

LOCK TABLES `shop_commands` WRITE;
/*!40000 ALTER TABLE `shop_commands` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_commands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_currency`
--

DROP TABLE IF EXISTS `shop_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_currency` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `IsMain` tinyint(1) NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `Currency` varchar(10) COLLATE utf8_bin NOT NULL,
  `Rate` decimal(10,2) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateLastAccess` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `ID_2` (`ID`),
  KEY `Currency` (`Currency`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_currency_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_currency`
--

LOCK TABLES `shop_currency` WRITE;
/*!40000 ALTER TABLE `shop_currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_customerProductRequests`
--

DROP TABLE IF EXISTS `shop_customerProductRequests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_customerProductRequests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `AccountID` int(11) DEFAULT NULL,
  `OptOut` text NOT NULL,
  `Email` text NOT NULL,
  `IsTemporary` tinyint(1) NOT NULL DEFAULT '1',
  `AutoClose` tinyint(1) NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`,`AccountID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_customerProductRequests`
--

LOCK TABLES `shop_customerProductRequests` WRITE;
/*!40000 ALTER TABLE `shop_customerProductRequests` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_customerProductRequests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_deliveryAgencies`
--

DROP TABLE IF EXISTS `shop_deliveryAgencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_deliveryAgencies` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `HomePage` varchar(300) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Status` enum('ACTIVE','DISABLED','REMOVED') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_deliveryAgencies`
--

LOCK TABLES `shop_deliveryAgencies` WRITE;
/*!40000 ALTER TABLE `shop_deliveryAgencies` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_deliveryAgencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_favlists`
--

DROP TABLE IF EXISTS `shop_favlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_favlists` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `AccountID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`,`AccountID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_favlists`
--

LOCK TABLES `shop_favlists` WRITE;
/*!40000 ALTER TABLE `shop_favlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_favlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_favproducts`
--

DROP TABLE IF EXISTS `shop_favproducts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_favproducts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ListID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`,`ListID`,`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='contains all products related to particular list';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_favproducts`
--

LOCK TABLES `shop_favproducts` WRITE;
/*!40000 ALTER TABLE `shop_favproducts` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_favproducts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_features`
--

DROP TABLE IF EXISTS `shop_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_features` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `FieldName` varchar(200) COLLATE utf8_bin NOT NULL,
  `GroupName` varchar(100) COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_features_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_features`
--

LOCK TABLES `shop_features` WRITE;
/*!40000 ALTER TABLE `shop_features` DISABLE KEYS */;
INSERT INTO `shop_features` VALUES (1,1,'12міс','Гарантія','2014-11-18 11:23:50','2014-11-18 11:23:50');
/*!40000 ALTER TABLE `shop_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_offers`
--

DROP TABLE IF EXISTS `shop_offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_offers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Type` enum('SHOP_CLEARANCE','SHOP_NEW','SHOP_HOTOFFER','SHOP_BESTSELLER','SHOP_LIMITED') COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateActive` datetime NOT NULL,
  `DateInactive` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_offers_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_offers`
--

LOCK TABLES `shop_offers` WRITE;
/*!40000 ALTER TABLE `shop_offers` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_offers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_orders`
--

DROP TABLE IF EXISTS `shop_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_orders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `AccountID` int(11) DEFAULT NULL,
  `AccountAddressesID` int(11) NOT NULL,
  `DeliveryID` int(11) DEFAULT NULL,
  `Warehouse` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `Comment` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `InternalComment` varchar(300) COLLATE utf8_bin DEFAULT NULL,
  `Status` enum('ACTIVE','LOGISTIC_DELIVERING','CUSTOMER_CANCELED','LOGISTIC_DELIVERED','SHOP_CLOSED','SHOP_REFUNDED','NEW') COLLATE utf8_bin NOT NULL DEFAULT 'NEW',
  `Hash` varchar(100) COLLATE utf8_bin NOT NULL,
  `PromoID` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `AccountID` (`AccountID`),
  KEY `Hash` (`Hash`),
  KEY `CustomerID` (`CustomerID`),
  KEY `AccountAddressesID` (`AccountAddressesID`),
  KEY `DeliveryID` (`DeliveryID`),
  CONSTRAINT `shop_orders_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_orders_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_orders_ibfk_3` FOREIGN KEY (`AccountAddressesID`) REFERENCES `mpws_accountAddresses` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_orders_ibfk_4` FOREIGN KEY (`DeliveryID`) REFERENCES `shop_deliveryAgencies` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_orders`
--

LOCK TABLES `shop_orders` WRITE;
/*!40000 ALTER TABLE `shop_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_origins`
--

DROP TABLE IF EXISTS `shop_origins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_origins` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ExternalKey` varchar(50) COLLATE utf8_bin NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin,
  `HomePage` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_origins_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_origins`
--

LOCK TABLES `shop_origins` WRITE;
/*!40000 ALTER TABLE `shop_origins` DISABLE KEYS */;
INSERT INTO `shop_origins` VALUES (46,1,'bosch','BOSCH','',NULL,'ACTIVE','2014-11-18 12:13:29','2014-11-18 12:13:29'),(47,1,'zelmer','ZELMER','',NULL,'ACTIVE','2014-11-18 12:13:35','2014-11-18 12:13:35'),(48,1,'braun','BRAUN','',NULL,'ACTIVE','2014-11-18 12:54:52','2014-11-18 12:54:52'),(49,1,'philips','Philips','',NULL,'ACTIVE','2014-11-18 12:54:54','2014-11-18 12:54:54'),(50,1,'philips','PHILIPS','',NULL,'ACTIVE','2014-11-18 12:54:55','2014-11-18 12:54:55'),(51,1,'siemens','SIEMENS','',NULL,'ACTIVE','2014-11-18 12:54:55','2014-11-18 12:54:55'),(52,1,'tefal','TEFAL','',NULL,'ACTIVE','2014-11-18 12:54:55','2014-11-18 12:54:55'),(53,1,'ariston','ARISTON','',NULL,'ACTIVE','2014-11-18 12:54:56','2014-11-18 12:54:56'),(54,1,'electrolux','ELECTROLUX','',NULL,'ACTIVE','2014-11-18 12:55:02','2014-11-18 12:55:02'),(55,1,'whirlpool','WHIRLPOOL','',NULL,'ACTIVE','2014-11-18 12:55:06','2014-11-18 12:55:06'),(56,1,'kaltmann','KALTMANN','',NULL,'ACTIVE','2014-11-18 12:55:16','2014-11-18 12:55:16'),(57,1,'lavazza','LAVAZZA','',NULL,'ACTIVE','2014-11-18 12:55:29','2014-11-18 12:55:29'),(58,1,'saeco','SAECO','',NULL,'ACTIVE','2014-11-18 12:55:31','2014-11-18 12:55:31'),(59,1,'kenwood','KENWOOD','',NULL,'ACTIVE','2014-11-18 12:55:40','2014-11-18 12:55:40'),(60,1,'elecrtolux','ELECRTOLUX','',NULL,'ACTIVE','2014-11-18 12:55:57','2014-11-18 12:55:57'),(61,1,'rowenta','ROWENTA','',NULL,'ACTIVE','2014-11-18 12:56:08','2014-11-18 12:56:08'),(62,1,'mpm','MPM','',NULL,'ACTIVE','2014-11-18 12:56:18','2014-11-18 12:56:18'),(63,1,'ravanson','RAVANSON','',NULL,'ACTIVE','2014-11-18 12:56:19','2014-11-18 12:56:19'),(64,1,'finish','FINISH','',NULL,'ACTIVE','2014-11-18 12:56:20','2014-11-18 12:56:20'),(65,1,'panasonic','PANASONIC','',NULL,'ACTIVE','2014-11-18 12:56:33','2014-11-18 12:56:33');
/*!40000 ALTER TABLE `shop_origins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_productAttributes`
--

DROP TABLE IF EXISTS `shop_productAttributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_productAttributes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Attribute` enum('IMAGE','ISBN','EXPIRE','TAGS','VIDEO','WARRANTY') COLLATE utf8_bin NOT NULL,
  `Value` text COLLATE utf8_bin,
  PRIMARY KEY (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_productAttributes_ibfk_3` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_productAttributes_ibfk_4` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productAttributes`
--

LOCK TABLES `shop_productAttributes` WRITE;
/*!40000 ALTER TABLE `shop_productAttributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_productAttributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_productFeatures`
--

DROP TABLE IF EXISTS `shop_productFeatures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_productFeatures` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `FeatureID` int(11) NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `CustomerID` (`CustomerID`,`ProductID`,`FeatureID`),
  KEY `ProductID` (`ProductID`),
  KEY `SpecFieldID` (`FeatureID`),
  CONSTRAINT `shop_productFeatures_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_productFeatures_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_productFeatures_ibfk_3` FOREIGN KEY (`FeatureID`) REFERENCES `shop_features` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productFeatures`
--

LOCK TABLES `shop_productFeatures` WRITE;
/*!40000 ALTER TABLE `shop_productFeatures` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_productFeatures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_productPrices`
--

DROP TABLE IF EXISTS `shop_productPrices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_productPrices` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_productPrices_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_productPrices_ibfk_3` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productPrices`
--

LOCK TABLES `shop_productPrices` WRITE;
/*!40000 ALTER TABLE `shop_productPrices` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_productPrices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_products`
--

DROP TABLE IF EXISTS `shop_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_products` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `OriginID` int(11) NOT NULL,
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
  `ExternalKey` varchar(50) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin,
  `Model` text COLLATE utf8_bin,
  `SKU` text COLLATE utf8_bin,
  `Price` decimal(10,2) NOT NULL,
  `IsPromo` tinyint(1) NOT NULL DEFAULT '0',
  `Status` enum('ACTIVE','ARCHIVED','DISCOUNT','DEFECT','WAITING','PREORDER') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `OriginID` (`OriginID`),
  KEY `CategoryID` (`CategoryID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_products_ibfk_3` FOREIGN KEY (`CategoryID`) REFERENCES `shop_categories` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `shop_products_ibfk_4` FOREIGN KEY (`OriginID`) REFERENCES `shop_origins` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `shop_products_ibfk_5` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='shop products';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_products`
--

LOCK TABLES `shop_products` WRITE;
/*!40000 ALTER TABLE `shop_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_products` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=root@localhost*/ /*!50003 TRIGGER `backupProductPrice` BEFORE UPDATE ON `shop_products`
 FOR EACH ROW IF NEW.Price != OLD.Price THEN
    INSERT INTO shop_productPrices SET CustomerID = NEW.CustomerID, ProductID = NEW.ID, Price = OLD.Price, DateCreated = NOW();
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `shop_promo`
--

DROP TABLE IF EXISTS `shop_promo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_promo` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Code` varchar(50) NOT NULL,
  `DateStart` datetime NOT NULL,
  `DateExpire` datetime NOT NULL,
  `Discount` decimal(10,0) NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Code` (`Code`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_promo_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_promo`
--

LOCK TABLES `shop_promo` WRITE;
/*!40000 ALTER TABLE `shop_promo` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_promo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_relations`
--

DROP TABLE IF EXISTS `shop_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_relations` (
  `CustomerID` int(11) NOT NULL,
  `ProductA_ID` int(11) NOT NULL,
  `ProductB_ID` int(11) NOT NULL,
  KEY `ProductB_ID` (`ProductB_ID`),
  KEY `ProductA_ID` (`ProductA_ID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_relations_ibfk_3` FOREIGN KEY (`ProductA_ID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_relations_ibfk_4` FOREIGN KEY (`ProductB_ID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_relations_ibfk_5` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_relations`
--

LOCK TABLES `shop_relations` WRITE;
/*!40000 ALTER TABLE `shop_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_settings`
--

DROP TABLE IF EXISTS `shop_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Property` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Label` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Value` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Status` enum('ACTIVE','DISABLED','REMOVED') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `Type` enum('ADDRESS','ALERTS','EXCHANAGERATES','OPENHOURS','FORMORDER','WEBSITE','MISC','PRODUCT') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'MISC',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Property` (`Property`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_settings_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_settings`
--

LOCK TABLES `shop_settings` WRITE;
/*!40000 ALTER TABLE `shop_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'mpws_light'
--
/*!50003 DROP PROCEDURE IF EXISTS `getShopCatalogBrands` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCatalogBrands`(IN `cat_ids` VARCHAR(500))
BEGIN
  SELECT o.ID,
         o.Name
  FROM   shop_products AS p
         LEFT JOIN shop_origins AS o
                ON p.OriginID = o.ID
  WHERE FIND_IN_SET (p.CategoryID, cat_ids)
  GROUP  BY o.Name;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getShopCatalogLocation` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCatalogLocation`(IN `catid` INT)
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getShopCatalogPriceEdges` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCatalogPriceEdges`(IN `cat_ids` VARCHAR(100))
BEGIN
SELECT MAX( p.Price ) AS 'PriceMax' , MIN( p.price ) AS 'PriceMin' FROM shop_products AS `p` WHERE FIND_IN_SET (p.CategoryID, cat_ids);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-18 15:12:36

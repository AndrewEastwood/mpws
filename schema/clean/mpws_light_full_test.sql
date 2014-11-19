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
  `Hash` varchar(32) COLLATE utf8_bin NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `Group` varchar(100) COLLATE utf8_bin NOT NULL,
  `Name` varchar(100) CHARACTER SET utf16 COLLATE utf16_bin NOT NULL,
  `PrcPath` varchar(300) COLLATE utf8_bin DEFAULT NULL,
  `Params` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  `PID` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `Result` varchar(10000) COLLATE utf8_bin DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_tasks`
--

LOCK TABLES `mpws_tasks` WRITE;
/*!40000 ALTER TABLE `mpws_tasks` DISABLE KEYS */;
INSERT INTO `mpws_tasks` VALUES (1,'e9e804c9c7fe095448bd64fc75dc204f',1,'shop','importProductFeed','','import_20141119_012453','','[\"processing product \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440\\u0438 \\u0434\\u043b\\u044f \\u0434\\u0443\\u0445\\u043e\\u0432\\u0438\\u0445 \\u0448\\u0430\\u0444\",\"[FAILED] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440\\u0438 \\u0434\\u043b\\u044f \\u0434\\u0443\\u0445\\u043e\\u0432\\u0438\\u0445 \\u0448\\u0430\\u0444\",\"[ERROR] \\u0410\\u043a\\u0441\\u0435\\u0441\\u0443\\u0430\\u0440\\u0438 \\u0434\\u043b\\u044f \\u0434\\u0443\\u0445\\u043e\\u0432\\u0438\\u0445 \\u0448\\u0430\\u0444\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436 \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043e\\u0432\\u043e\\u0447\\u0456\\u0432 \\\"\\u043f\\u043e-\\u043a\\u043e\\u0440\\u0435\\u0439\\u0441\\u044c\\u043a\\u0438\\\"\",\"[FAILED] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436 \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043e\\u0432\\u043e\\u0447\\u0456\\u0432 \\\"\\u043f\\u043e-\\u043a\\u043e\\u0440\\u0435\\u0439\\u0441\\u044c\\u043a\\u0438\\\"\",\"[ERROR] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436 \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043e\\u0432\\u043e\\u0447\\u0456\\u0432 \\\"\\u043f\\u043e-\\u043a\\u043e\\u0440\\u0435\\u0439\\u0441\\u044c\\u043a\\u0438\\\"\",\"processing product \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0440\\u0443\\u043a\\u0442\\u043e\\u0432\\u0438\\u0445 \\u043d\\u0430\\u043f\\u043e\\u0457\\u0432 \\u0437 \\u043c\'\\u044f\\u043a\\u043e\\u0442\\u0442\\u044e\",\"[FAILED] \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0440\\u0443\\u043a\\u0442\\u043e\\u0432\\u0438\\u0445 \\u043d\\u0430\\u043f\\u043e\\u0457\\u0432 \\u0437 \\u043c\'\\u044f\\u043a\\u043e\\u0442\\u0442\\u044e\",\"[ERROR] \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0440\\u0443\\u043a\\u0442\\u043e\\u0432\\u0438\\u0445 \\u043d\\u0430\\u043f\\u043e\\u0457\\u0432 \\u0437 \\u043c\'\\u044f\\u043a\\u043e\\u0442\\u0442\\u044e\",\"processing product \\u0414\\u0432\\u043e\\u0441\\u0442\\u043e\\u0440\\u043e\\u043d\\u043d\\u0456\\u0439 \\u0434\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436-\\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 (\\u0441\\u0435\\u0440\\u0435\\u0434\\u043d\\u044f)\",\"[FAILED] \\u0414\\u0432\\u043e\\u0441\\u0442\\u043e\\u0440\\u043e\\u043d\\u043d\\u0456\\u0439 \\u0434\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436-\\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 (\\u0441\\u0435\\u0440\\u0435\\u0434\\u043d\\u044f)\",\"[ERROR] \\u0414\\u0432\\u043e\\u0441\\u0442\\u043e\\u0440\\u043e\\u043d\\u043d\\u0456\\u0439 \\u0434\\u0438\\u0441\\u043a\\u043e\\u0432\\u0438\\u0439 \\u043d\\u0456\\u0436-\\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 (\\u0441\\u0435\\u0440\\u0435\\u0434\\u043d\\u044f)\",\"processing product \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 \\u0434\\u043b\\u044f \\u0433\\u0440\\u0443\\u0431\\u043e\\u0433\\u043e \\u043d\\u0430\\u0442\\u0438\\u0440\\u0430\\u043d\\u043d\\u044f\",\"[FAILED] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 \\u0434\\u043b\\u044f \\u0433\\u0440\\u0443\\u0431\\u043e\\u0433\\u043e \\u043d\\u0430\\u0442\\u0438\\u0440\\u0430\\u043d\\u043d\\u044f\",\"[ERROR] \\u0414\\u0438\\u0441\\u043a\\u043e\\u0432\\u0430 \\u0442\\u0435\\u0440\\u0442\\u043a\\u0430 \\u0434\\u043b\\u044f \\u0433\\u0440\\u0443\\u0431\\u043e\\u0433\\u043e \\u043d\\u0430\\u0442\\u0438\\u0440\\u0430\\u043d\\u043d\\u044f\",\"processing product \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0456\\u0433\\u0443\\u0440\\u043d\\u0438\\u0445 \\u0432\\u0438\\u0440\\u043e\\u0431\\u0456\\u0432 \\u0437 \\u0442\\u0456\\u0441\\u0442\\u0430\",\"[FAILED] \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0456\\u0433\\u0443\\u0440\\u043d\\u0438\\u0445 \\u0432\\u0438\\u0440\\u043e\\u0431\\u0456\\u0432 \\u0437 \\u0442\\u0456\\u0441\\u0442\\u0430\",\"[ERROR] \\u041d\\u0430\\u0441\\u0430\\u0434\\u043a\\u0430 \\u0437 \\u043b\\u0438\\u0442\\u043e\\u0433\\u043e \\u0430\\u043b\\u044e\\u043c\\u0456\\u043d\\u0456\\u044e \\u0434\\u043b\\u044f \\u043f\\u0440\\u0438\\u0433\\u043e\\u0442\\u0443\\u0432\\u0430\\u043d\\u043d\\u044f \\u0444\\u0456\\u0433\\u0443\\u0440\\u043d\\u0438\\u0445 \\u0432\\u0438\\u0440\\u043e\\u0431\\u0456\\u0432 \\u0437 \\u0442\\u0456\\u0441\\u0442\\u0430\",\"processing product \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\",\"[FAILED] \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\",\"[ERROR] \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\",\"processing product \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\",\"[FAILED] \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\",\"[ERROR] \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\",\"processing product \\u041f\\u0440\\u0438\\u043b\\u0430\\u0434\\u0434\\u044f \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043f\\u0440\\u043e\\u0434\\u0443\\u043a\\u0442\\u0456\\u0432 \\u043a\\u0443\\u0431\\u0438\\u043a\\u0430\\u043c\\u0438\",\"[FAILED] \\u041f\\u0440\\u0438\\u043b\\u0430\\u0434\\u0434\\u044f \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043f\\u0440\\u043e\\u0434\\u0443\\u043a\\u0442\\u0456\\u0432 \\u043a\\u0443\\u0431\\u0438\\u043a\\u0430\\u043c\\u0438\",\"[ERROR] \\u041f\\u0440\\u0438\\u043b\\u0430\\u0434\\u0434\\u044f \\u0434\\u043b\\u044f \\u043d\\u0430\\u0440\\u0456\\u0437\\u0430\\u043d\\u043d\\u044f \\u043f\\u0440\\u043e\\u0434\\u0443\\u043a\\u0442\\u0456\\u0432 \\u043a\\u0443\\u0431\\u0438\\u043a\\u0430\\u043c\\u0438\",\"processing product \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\",\"[FAILED] \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\",\"[ERROR] \\u041c\\u043b\\u0438\\u043d\\u043e\\u043a \\u0434\\u043b\\u044f \\u0437\\u0435\\u0440\\u043d\\u043e\\u0432\\u0438\\u0445\",\"processing product \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\",\"[FAILED] \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\",\"[ERROR] \\u0426\\u0438\\u0442\\u0440\\u0443\\u0441-\\u043f\\u0440\\u0435\\u0441\"]',0,1,0,0,'2014-11-19 01:24:53'),(2,'cb9a14c7322215813e7f5104e74482f5',1,'shop','importProductFeed','','import_20141119_013550','',NULL,0,1,0,0,'2014-11-19 01:35:50');
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
  `Name` varchar(200) COLLATE utf8_bin NOT NULL,
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
  `Name` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
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
  `Warehouse` varchar(200) COLLATE utf8_bin DEFAULT NULL,
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
  `HomePage` varchar(300) COLLATE utf8_bin DEFAULT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_origins_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_origins`
--

LOCK TABLES `shop_origins` WRITE;
/*!40000 ALTER TABLE `shop_origins` DISABLE KEYS */;
INSERT INTO `shop_origins` VALUES (46,1,'bosch','BOSCH','',NULL,'ACTIVE','2014-11-18 12:13:29','2014-11-18 12:13:29'),(47,1,'zelmer','ZELMER','',NULL,'ACTIVE','2014-11-18 12:13:35','2014-11-18 12:13:35'),(48,1,'braun','BRAUN','',NULL,'ACTIVE','2014-11-18 12:54:52','2014-11-18 12:54:52'),(49,1,'philips','Philips','',NULL,'ACTIVE','2014-11-18 12:54:54','2014-11-18 12:54:54'),(50,1,'philips','PHILIPS','',NULL,'ACTIVE','2014-11-18 12:54:55','2014-11-18 12:54:55'),(51,1,'siemens','SIEMENS','',NULL,'ACTIVE','2014-11-18 12:54:55','2014-11-18 12:54:55'),(52,1,'tefal','TEFAL','',NULL,'ACTIVE','2014-11-18 12:54:55','2014-11-18 12:54:55'),(53,1,'ariston','ARISTON','',NULL,'ACTIVE','2014-11-18 12:54:56','2014-11-18 12:54:56'),(54,1,'electrolux','ELECTROLUX','',NULL,'ACTIVE','2014-11-18 12:55:02','2014-11-18 12:55:02'),(55,1,'whirlpool','WHIRLPOOL','',NULL,'ACTIVE','2014-11-18 12:55:06','2014-11-18 12:55:06'),(56,1,'kaltmann','KALTMANN','',NULL,'ACTIVE','2014-11-18 12:55:16','2014-11-18 12:55:16'),(57,1,'lavazza','LAVAZZA','',NULL,'ACTIVE','2014-11-18 12:55:29','2014-11-18 12:55:29'),(58,1,'saeco','SAECO','',NULL,'ACTIVE','2014-11-18 12:55:31','2014-11-18 12:55:31'),(59,1,'kenwood','KENWOOD','',NULL,'ACTIVE','2014-11-18 12:55:40','2014-11-18 12:55:40'),(60,1,'elecrtolux','ELECRTOLUX','',NULL,'ACTIVE','2014-11-18 12:55:57','2014-11-18 12:55:57'),(61,1,'rowenta','ROWENTA','',NULL,'ACTIVE','2014-11-18 12:56:08','2014-11-18 12:56:08'),(62,1,'mpm','MPM','',NULL,'ACTIVE','2014-11-18 12:56:18','2014-11-18 12:56:18'),(63,1,'ravanson','RAVANSON','',NULL,'ACTIVE','2014-11-18 12:56:19','2014-11-18 12:56:19'),(64,1,'finish','FINISH','',NULL,'ACTIVE','2014-11-18 12:56:20','2014-11-18 12:56:20'),(65,1,'panasonic','PANASONIC','',NULL,'ACTIVE','2014-11-18 12:56:33','2014-11-18 12:56:33'),(66,1,'moulinex','MOULINEX','',NULL,'ACTIVE','2014-11-19 03:05:30','2014-11-19 03:05:30');
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
) ENGINE=InnoDB AUTO_INCREMENT=1326 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productAttributes`
--

LOCK TABLES `shop_productAttributes` WRITE;
/*!40000 ALTER TABLE `shop_productAttributes` DISABLE KEYS */;
INSERT INTO `shop_productAttributes` VALUES (1026,1,42,'IMAGE','421416358731546beb4bca8cd.jpg'),(1027,1,43,'IMAGE','431416358732546beb4c6be68.jpg'),(1028,1,44,'IMAGE','441416358733546beb4d11680.jpg'),(1029,1,49,'IMAGE','491416358733546beb4dabb0e.jpg'),(1031,1,46,'IMAGE','461416358744546beb58f18e6.jpg'),(1034,1,50,'IMAGE','501416358746546beb5ab67bf.jpg'),(1035,1,47,'IMAGE','471416358747546beb5b49a50.jpg'),(1037,1,45,'IMAGE','451416358748546beb5c92904.jpg'),(1038,1,51,'IMAGE','511416358749546beb5d69858.jpg'),(1039,1,52,'IMAGE','521416358755546beb63108e2.jpg'),(1040,1,53,'IMAGE','531416358755546beb63d1cd2.jpg'),(1052,1,54,'IMAGE','541416358764546beb6c0671b.jpg'),(1055,1,55,'IMAGE','551416358770546beb72e7ed8.jpg'),(1072,1,56,'IMAGE','561416358781546beb7dab57c.jpg'),(1078,1,57,'IMAGE','571416358786546beb82c835e.jpg'),(1079,1,58,'IMAGE','581416358787546beb8362af9.jpg'),(1080,1,59,'IMAGE','591416358787546beb83f26d5.jpg'),(1081,1,60,'IMAGE','601416358793546beb898e6e6.jpg'),(1083,1,61,'IMAGE','611416358794546beb8ad88c0.jpg'),(1088,1,62,'IMAGE','621416358803546beb9360215.jpg'),(1094,1,63,'IMAGE','631416358807546beb9717df5.jpg'),(1100,1,64,'IMAGE','641416358814546beb9e44880.jpg'),(1107,1,65,'IMAGE','651416358829546bebad37e6a.jpg'),(1109,1,66,'IMAGE','661416358830546bebae63fdb.jpg'),(1112,1,67,'IMAGE','671416358837546bebb531037.jpg'),(1117,1,68,'IMAGE','681416358850546bebc2cf86b.jpg'),(1118,1,69,'IMAGE','691416358851546bebc36d532.jpg'),(1122,1,70,'IMAGE','701416358854546bebc638cb4.jpg'),(1123,1,71,'IMAGE','711416358854546bebc6cde92.jpg'),(1124,1,72,'IMAGE','721416358855546bebc76953b.jpg'),(1127,1,73,'IMAGE','731416358857546bebc99a4c1.jpg'),(1128,1,74,'IMAGE','741416358858546bebca3be5c.jpg'),(1132,1,75,'IMAGE','751416358865546bebd1c6d99.jpg'),(1133,1,76,'IMAGE','761416358866546bebd25f4d0.jpg'),(1135,1,77,'IMAGE','771416358872546bebd8eaa80.jpg'),(1139,1,78,'IMAGE','781416358876546bebdc62dd5.jpg'),(1150,1,79,'IMAGE','791416358883546bebe3da707.jpg'),(1151,1,80,'IMAGE','801416358884546bebe4788bb.jpg'),(1157,1,81,'IMAGE','811416358898546bebf286551.jpg'),(1161,1,82,'IMAGE','821416358902546bebf62a6d7.jpg'),(1162,1,83,'IMAGE','831416358902546bebf6bf2b4.jpg'),(1163,1,84,'IMAGE','841416358903546bebf7a6d16.jpg'),(1165,1,85,'IMAGE','851416358904546bebf8f24c6.jpg'),(1182,1,86,'IMAGE','861416358932546bec1446dd3.jpg'),(1183,1,87,'IMAGE','871416358935546bec170db9d.jpg'),(1184,1,88,'IMAGE','881416358940546bec1ca7ebe.jpg'),(1185,1,89,'IMAGE','891416358942546bec1e26bf1.jpg'),(1197,1,90,'IMAGE','901416358957546bec2d65fec.jpg'),(1199,1,91,'IMAGE','911416358963546bec339fcc1.jpg'),(1212,1,92,'IMAGE','921416358988546bec4c7b08a.jpg'),(1213,1,93,'IMAGE','931416358989546bec4d77be3.jpg'),(1215,1,94,'IMAGE','941416358992546bec501ae22.jpg'),(1218,1,95,'IMAGE','951416359001546bec590ed25.jpg'),(1219,1,96,'IMAGE','961416359001546bec59c8bb5.jpg'),(1220,1,97,'IMAGE','971416359002546bec5ae9bfe.jpg'),(1221,1,98,'IMAGE','981416359004546bec5c4c77e.jpg'),(1223,1,99,'IMAGE','991416359005546bec5da7cd0.jpg'),(1224,1,100,'IMAGE','1001416359006546bec5e420c5.jpg'),(1226,1,101,'IMAGE','1011416359007546bec5f61b42.jpg'),(1227,1,102,'IMAGE','1021416359008546bec60029e8.jpg'),(1230,1,103,'IMAGE','1031416359009546bec61bbf5a.jpg'),(1231,1,104,'IMAGE','1041416359010546bec62b864f.jpg'),(1243,1,105,'IMAGE','1051416359020546bec6c6a46a.jpg'),(1248,1,106,'IMAGE','1061416359025546bec718de6e.jpg'),(1250,1,107,'IMAGE','1071416359027546bec734d891.jpg'),(1254,1,108,'IMAGE','1081416359035546bec7bc0c55.jpg'),(1258,1,109,'IMAGE','1091416359045546bec856f626.jpg'),(1259,1,110,'IMAGE','1101416359046546bec8683277.jpg'),(1266,1,111,'IMAGE','1111416359054546bec8e6ca63.jpg'),(1268,1,112,'IMAGE','1121416359056546bec9072c21.jpg'),(1269,1,113,'IMAGE','1131416359057546bec9190e94.jpg'),(1270,1,114,'IMAGE','1141416359058546bec9276f18.jpg'),(1271,1,115,'IMAGE','1151416359059546bec937ee84.jpg'),(1272,1,116,'IMAGE','1161416359060546bec9466223.jpg'),(1274,1,117,'IMAGE','1171416359062546bec96dacdf.jpg'),(1275,1,118,'IMAGE','1181416359063546bec979abd3.jpg'),(1282,1,119,'IMAGE','1191416359082546becaa39947.jpg'),(1283,1,120,'IMAGE','1201416359082546becaad5f24.jpg'),(1287,1,121,'IMAGE','1211416359086546becae7ebbe.jpg'),(1289,1,122,'IMAGE','1221416359088546becb086431.jpg'),(1298,1,123,'IMAGE','1231416359095546becb745386.jpg'),(1305,1,124,'IMAGE','1241416359111546becc75f218.jpg'),(1306,1,125,'IMAGE','1251416359112546becc80885c.jpg'),(1307,1,126,'IMAGE','1261416359112546becc89d0f8.jpg'),(1308,1,48,'IMAGE','481416359113546becc958690.jpg'),(1316,1,127,'IMAGE','1271416359124546becd442d11.jpg'),(1318,1,128,'IMAGE','1281416359125546becd5969ed.jpg'),(1321,1,129,'IMAGE','1291416359127546becd7986ce.jpg'),(1323,1,130,'IMAGE','1301416359129546becd919047.jpg'),(1324,1,131,'IMAGE','1311416359129546becd9b106e.jpg'),(1325,1,132,'IMAGE','1321416359130546becda8b8fa.jpg');
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
) ENGINE=InnoDB AUTO_INCREMENT=1326 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productFeatures`
--

LOCK TABLES `shop_productFeatures` WRITE;
/*!40000 ALTER TABLE `shop_productFeatures` DISABLE KEYS */;
INSERT INTO `shop_productFeatures` VALUES (1026,1,42,1,'2014-11-19 02:58:51','2014-11-19 02:58:51'),(1027,1,43,1,'2014-11-19 02:58:52','2014-11-19 02:58:52'),(1028,1,44,1,'2014-11-19 02:58:53','2014-11-19 02:58:53'),(1029,1,49,1,'2014-11-19 02:58:53','2014-11-19 02:58:53'),(1031,1,46,1,'2014-11-19 02:59:04','2014-11-19 02:59:04'),(1034,1,50,1,'2014-11-19 02:59:06','2014-11-19 02:59:06'),(1035,1,47,1,'2014-11-19 02:59:07','2014-11-19 02:59:07'),(1037,1,45,1,'2014-11-19 02:59:08','2014-11-19 02:59:08'),(1038,1,51,1,'2014-11-19 02:59:09','2014-11-19 02:59:09'),(1039,1,52,1,'2014-11-19 02:59:15','2014-11-19 02:59:15'),(1040,1,53,1,'2014-11-19 02:59:15','2014-11-19 02:59:15'),(1052,1,54,1,'2014-11-19 02:59:24','2014-11-19 02:59:24'),(1055,1,55,1,'2014-11-19 02:59:30','2014-11-19 02:59:30'),(1072,1,56,1,'2014-11-19 02:59:41','2014-11-19 02:59:41'),(1078,1,57,1,'2014-11-19 02:59:46','2014-11-19 02:59:46'),(1079,1,58,1,'2014-11-19 02:59:47','2014-11-19 02:59:47'),(1080,1,59,1,'2014-11-19 02:59:47','2014-11-19 02:59:47'),(1081,1,60,1,'2014-11-19 02:59:53','2014-11-19 02:59:53'),(1083,1,61,1,'2014-11-19 02:59:54','2014-11-19 02:59:54'),(1088,1,62,1,'2014-11-19 03:00:03','2014-11-19 03:00:03'),(1094,1,63,1,'2014-11-19 03:00:07','2014-11-19 03:00:07'),(1100,1,64,1,'2014-11-19 03:00:14','2014-11-19 03:00:14'),(1107,1,65,1,'2014-11-19 03:00:29','2014-11-19 03:00:29'),(1109,1,66,1,'2014-11-19 03:00:30','2014-11-19 03:00:30'),(1112,1,67,1,'2014-11-19 03:00:37','2014-11-19 03:00:37'),(1117,1,68,1,'2014-11-19 03:00:50','2014-11-19 03:00:50'),(1118,1,69,1,'2014-11-19 03:00:51','2014-11-19 03:00:51'),(1122,1,70,1,'2014-11-19 03:00:54','2014-11-19 03:00:54'),(1123,1,71,1,'2014-11-19 03:00:54','2014-11-19 03:00:54'),(1124,1,72,1,'2014-11-19 03:00:55','2014-11-19 03:00:55'),(1127,1,73,1,'2014-11-19 03:00:57','2014-11-19 03:00:57'),(1128,1,74,1,'2014-11-19 03:00:58','2014-11-19 03:00:58'),(1132,1,75,1,'2014-11-19 03:01:05','2014-11-19 03:01:05'),(1133,1,76,1,'2014-11-19 03:01:06','2014-11-19 03:01:06'),(1135,1,77,1,'2014-11-19 03:01:12','2014-11-19 03:01:12'),(1139,1,78,1,'2014-11-19 03:01:16','2014-11-19 03:01:16'),(1150,1,79,1,'2014-11-19 03:01:23','2014-11-19 03:01:23'),(1151,1,80,1,'2014-11-19 03:01:24','2014-11-19 03:01:24'),(1157,1,81,1,'2014-11-19 03:01:38','2014-11-19 03:01:38'),(1161,1,82,1,'2014-11-19 03:01:42','2014-11-19 03:01:42'),(1162,1,83,1,'2014-11-19 03:01:42','2014-11-19 03:01:42'),(1163,1,84,1,'2014-11-19 03:01:43','2014-11-19 03:01:43'),(1165,1,85,1,'2014-11-19 03:01:44','2014-11-19 03:01:44'),(1182,1,86,1,'2014-11-19 03:02:12','2014-11-19 03:02:12'),(1183,1,87,1,'2014-11-19 03:02:15','2014-11-19 03:02:15'),(1184,1,88,1,'2014-11-19 03:02:20','2014-11-19 03:02:20'),(1185,1,89,1,'2014-11-19 03:02:22','2014-11-19 03:02:22'),(1197,1,90,1,'2014-11-19 03:02:37','2014-11-19 03:02:37'),(1199,1,91,1,'2014-11-19 03:02:43','2014-11-19 03:02:43'),(1212,1,92,1,'2014-11-19 03:03:08','2014-11-19 03:03:08'),(1213,1,93,1,'2014-11-19 03:03:09','2014-11-19 03:03:09'),(1215,1,94,1,'2014-11-19 03:03:12','2014-11-19 03:03:12'),(1218,1,95,1,'2014-11-19 03:03:21','2014-11-19 03:03:21'),(1219,1,96,1,'2014-11-19 03:03:21','2014-11-19 03:03:21'),(1220,1,97,1,'2014-11-19 03:03:22','2014-11-19 03:03:22'),(1221,1,98,1,'2014-11-19 03:03:24','2014-11-19 03:03:24'),(1223,1,99,1,'2014-11-19 03:03:25','2014-11-19 03:03:25'),(1224,1,100,1,'2014-11-19 03:03:26','2014-11-19 03:03:26'),(1226,1,101,1,'2014-11-19 03:03:27','2014-11-19 03:03:27'),(1227,1,102,1,'2014-11-19 03:03:28','2014-11-19 03:03:28'),(1230,1,103,1,'2014-11-19 03:03:29','2014-11-19 03:03:29'),(1231,1,104,1,'2014-11-19 03:03:30','2014-11-19 03:03:30'),(1243,1,105,1,'2014-11-19 03:03:40','2014-11-19 03:03:40'),(1248,1,106,1,'2014-11-19 03:03:45','2014-11-19 03:03:45'),(1250,1,107,1,'2014-11-19 03:03:47','2014-11-19 03:03:47'),(1254,1,108,1,'2014-11-19 03:03:55','2014-11-19 03:03:55'),(1258,1,109,1,'2014-11-19 03:04:05','2014-11-19 03:04:05'),(1259,1,110,1,'2014-11-19 03:04:06','2014-11-19 03:04:06'),(1266,1,111,1,'2014-11-19 03:04:14','2014-11-19 03:04:14'),(1268,1,112,1,'2014-11-19 03:04:16','2014-11-19 03:04:16'),(1269,1,113,1,'2014-11-19 03:04:17','2014-11-19 03:04:17'),(1270,1,114,1,'2014-11-19 03:04:18','2014-11-19 03:04:18'),(1271,1,115,1,'2014-11-19 03:04:19','2014-11-19 03:04:19'),(1272,1,116,1,'2014-11-19 03:04:20','2014-11-19 03:04:20'),(1274,1,117,1,'2014-11-19 03:04:22','2014-11-19 03:04:22'),(1275,1,118,1,'2014-11-19 03:04:23','2014-11-19 03:04:23'),(1282,1,119,1,'2014-11-19 03:04:42','2014-11-19 03:04:42'),(1283,1,120,1,'2014-11-19 03:04:42','2014-11-19 03:04:42'),(1287,1,121,1,'2014-11-19 03:04:46','2014-11-19 03:04:46'),(1289,1,122,1,'2014-11-19 03:04:48','2014-11-19 03:04:48'),(1298,1,123,1,'2014-11-19 03:04:55','2014-11-19 03:04:55'),(1305,1,124,1,'2014-11-19 03:05:11','2014-11-19 03:05:11'),(1306,1,125,1,'2014-11-19 03:05:12','2014-11-19 03:05:12'),(1307,1,126,1,'2014-11-19 03:05:12','2014-11-19 03:05:12'),(1308,1,48,1,'2014-11-19 03:05:13','2014-11-19 03:05:13'),(1316,1,127,1,'2014-11-19 03:05:24','2014-11-19 03:05:24'),(1318,1,128,1,'2014-11-19 03:05:25','2014-11-19 03:05:25'),(1321,1,129,1,'2014-11-19 03:05:27','2014-11-19 03:05:27'),(1323,1,130,1,'2014-11-19 03:05:29','2014-11-19 03:05:29'),(1324,1,131,1,'2014-11-19 03:05:29','2014-11-19 03:05:29'),(1325,1,132,1,'2014-11-19 03:05:30','2014-11-19 03:05:30');
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
) ENGINE=InnoDB AUTO_INCREMENT=993 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productPrices`
--

LOCK TABLES `shop_productPrices` WRITE;
/*!40000 ALTER TABLE `shop_productPrices` DISABLE KEYS */;
INSERT INTO `shop_productPrices` VALUES (1,1,48,17.99,'2014-11-19 01:36:28'),(2,1,48,55.00,'2014-11-19 01:49:34'),(3,1,47,171.00,'2014-11-19 01:49:41'),(4,1,48,17.99,'2014-11-19 01:49:42'),(5,1,47,25.00,'2014-11-19 01:53:42'),(6,1,48,55.00,'2014-11-19 01:53:42'),(7,1,47,171.00,'2014-11-19 01:53:54'),(8,1,48,17.99,'2014-11-19 01:53:55'),(9,1,47,25.00,'2014-11-19 01:56:50'),(10,1,48,55.00,'2014-11-19 01:56:50'),(11,1,47,171.00,'2014-11-19 01:56:52'),(12,1,48,17.99,'2014-11-19 01:56:53'),(13,1,47,25.00,'2014-11-19 01:57:56'),(14,1,48,55.00,'2014-11-19 01:57:57'),(15,1,47,171.00,'2014-11-19 01:57:58'),(16,1,48,17.99,'2014-11-19 01:58:04'),(17,1,47,25.00,'2014-11-19 02:19:20'),(18,1,48,55.00,'2014-11-19 02:19:20'),(19,1,47,171.00,'2014-11-19 02:19:22'),(20,1,48,17.99,'2014-11-19 02:19:22'),(21,1,45,4.00,'2014-11-19 02:19:23'),(22,1,54,4.43,'2014-11-19 02:19:27'),(23,1,54,2.53,'2014-11-19 02:19:28'),(24,1,54,4.43,'2014-11-19 02:19:29'),(25,1,54,3.77,'2014-11-19 02:19:35'),(26,1,54,4.64,'2014-11-19 02:19:35'),(27,1,54,4.58,'2014-11-19 02:19:36'),(28,1,54,4.07,'2014-11-19 02:19:37'),(29,1,54,21.00,'2014-11-19 02:19:38'),(30,1,54,22.36,'2014-11-19 02:19:38'),(31,1,55,8.44,'2014-11-19 02:19:40'),(32,1,55,2.22,'2014-11-19 02:19:41'),(33,1,56,24.26,'2014-11-19 02:19:42'),(34,1,56,77.10,'2014-11-19 02:19:42'),(35,1,56,90.43,'2014-11-19 02:19:43'),(36,1,56,106.69,'2014-11-19 02:19:43'),(37,1,56,105.06,'2014-11-19 02:19:44'),(38,1,56,127.18,'2014-11-19 02:19:45'),(39,1,56,53.95,'2014-11-19 02:19:45'),(40,1,56,66.00,'2014-11-19 02:19:46'),(41,1,56,34.10,'2014-11-19 02:19:46'),(42,1,56,48.00,'2014-11-19 02:19:47'),(43,1,56,45.58,'2014-11-19 02:19:48'),(44,1,56,60.00,'2014-11-19 02:19:49'),(45,1,56,52.00,'2014-11-19 02:19:54'),(46,1,56,64.00,'2014-11-19 02:19:55'),(47,1,56,35.50,'2014-11-19 02:19:56'),(48,1,56,75.00,'2014-11-19 02:19:57'),(49,1,57,54.64,'2014-11-19 02:20:05'),(50,1,57,45.00,'2014-11-19 02:20:05'),(51,1,57,69.00,'2014-11-19 02:20:06'),(52,1,57,72.15,'2014-11-19 02:20:06'),(53,1,57,77.00,'2014-11-19 02:20:07'),(54,1,61,34.80,'2014-11-19 02:20:13'),(55,1,62,153.14,'2014-11-19 02:20:14'),(56,1,62,138.88,'2014-11-19 02:20:15'),(57,1,62,165.14,'2014-11-19 02:20:16'),(58,1,62,177.14,'2014-11-19 02:20:16'),(59,1,63,242.50,'2014-11-19 02:20:24'),(60,1,63,207.82,'2014-11-19 02:20:30'),(61,1,63,345.00,'2014-11-19 02:20:31'),(62,1,63,355.80,'2014-11-19 02:20:31'),(63,1,64,133.13,'2014-11-19 02:20:33'),(64,1,64,171.81,'2014-11-19 02:20:35'),(65,1,64,202.49,'2014-11-19 02:20:36'),(66,1,64,178.48,'2014-11-19 02:20:37'),(67,1,64,305.19,'2014-11-19 02:20:37'),(68,1,65,238.50,'2014-11-19 02:20:39'),(69,1,65,273.18,'2014-11-19 02:20:40'),(70,1,65,102.45,'2014-11-19 02:20:41'),(71,1,65,126.46,'2014-11-19 02:20:52'),(72,1,65,199.82,'2014-11-19 02:20:53'),(73,1,67,370.55,'2014-11-19 02:21:05'),(74,1,67,319.87,'2014-11-19 02:21:11'),(75,1,68,250.51,'2014-11-19 02:21:12'),(76,1,68,249.17,'2014-11-19 02:21:14'),(77,1,68,323.87,'2014-11-19 02:21:15'),(78,1,68,291.86,'2014-11-19 02:21:15'),(79,1,70,24.85,'2014-11-19 02:21:28'),(80,1,70,49.20,'2014-11-19 02:21:29'),(81,1,70,50.00,'2014-11-19 02:21:29'),(82,1,73,85.20,'2014-11-19 02:21:32'),(83,1,73,79.00,'2014-11-19 02:21:33'),(84,1,75,38.40,'2014-11-19 02:21:45'),(85,1,75,60.00,'2014-11-19 02:21:46'),(86,1,75,43.00,'2014-11-19 02:21:46'),(87,1,78,342.37,'2014-11-19 02:21:49'),(88,1,78,346.71,'2014-11-19 02:21:50'),(89,1,78,379.00,'2014-11-19 02:21:51'),(90,1,79,301.02,'2014-11-19 02:21:52'),(91,1,79,238.30,'2014-11-19 02:21:53'),(92,1,79,254.34,'2014-11-19 02:21:54'),(93,1,79,331.70,'2014-11-19 02:21:54'),(94,1,79,365.05,'2014-11-19 02:21:55'),(95,1,79,429.07,'2014-11-19 02:21:56'),(96,1,79,389.05,'2014-11-19 02:21:56'),(97,1,79,423.73,'2014-11-19 02:21:57'),(98,1,79,301.02,'2014-11-19 02:21:58'),(99,1,79,342.37,'2014-11-19 02:21:59'),(100,1,81,206.32,'2014-11-19 02:22:02'),(101,1,81,200.98,'2014-11-19 02:22:03'),(102,1,81,293.02,'2014-11-19 02:22:03'),(103,1,81,219.66,'2014-11-19 02:22:04'),(104,1,81,208.99,'2014-11-19 02:22:15'),(105,1,82,16.20,'2014-11-19 02:22:18'),(106,1,82,18.90,'2014-11-19 02:22:18'),(107,1,85,21.80,'2014-11-19 02:22:31'),(108,1,86,139.00,'2014-11-19 02:22:33'),(109,1,86,152.90,'2014-11-19 02:22:33'),(110,1,86,119.90,'2014-11-19 02:22:34'),(111,1,86,96.95,'2014-11-19 02:22:35'),(112,1,86,81.65,'2014-11-19 02:22:36'),(113,1,86,132.25,'2014-11-19 02:22:36'),(114,1,86,139.40,'2014-11-19 02:22:37'),(115,1,86,181.00,'2014-11-19 02:22:38'),(116,1,86,198.95,'2014-11-19 02:22:38'),(117,1,86,177.60,'2014-11-19 02:22:39'),(118,1,86,167.24,'2014-11-19 02:22:40'),(119,1,86,220.00,'2014-11-19 02:22:40'),(120,1,86,225.00,'2014-11-19 02:22:41'),(121,1,86,263.64,'2014-11-19 02:22:42'),(122,1,86,209.74,'2014-11-19 02:22:43'),(123,1,86,225.50,'2014-11-19 02:22:44'),(124,1,90,38.00,'2014-11-19 02:22:47'),(125,1,90,58.00,'2014-11-19 02:22:48'),(126,1,90,66.70,'2014-11-19 02:22:49'),(127,1,90,41.11,'2014-11-19 02:22:50'),(128,1,90,68.33,'2014-11-19 02:22:56'),(129,1,90,28.80,'2014-11-19 02:22:56'),(130,1,90,27.52,'2014-11-19 02:23:02'),(131,1,90,47.08,'2014-11-19 02:23:03'),(132,1,90,47.00,'2014-11-19 02:23:03'),(133,1,90,44.96,'2014-11-19 02:23:04'),(134,1,90,43.00,'2014-11-19 02:23:10'),(135,1,91,51.00,'2014-11-19 02:23:11'),(136,1,92,46.66,'2014-11-19 02:23:13'),(137,1,92,70.15,'2014-11-19 02:23:13'),(138,1,92,70.40,'2014-11-19 02:23:15'),(139,1,92,87.31,'2014-11-19 02:23:15'),(140,1,92,112.00,'2014-11-19 02:23:16'),(141,1,92,86.25,'2014-11-19 02:23:17'),(142,1,92,108.90,'2014-11-19 02:23:17'),(143,1,92,82.80,'2014-11-19 02:23:18'),(144,1,92,85.96,'2014-11-19 02:23:24'),(145,1,92,73.00,'2014-11-19 02:23:24'),(146,1,92,110.00,'2014-11-19 02:23:25'),(147,1,94,43.11,'2014-11-19 02:23:32'),(148,1,95,103.40,'2014-11-19 02:23:33'),(149,1,95,113.30,'2014-11-19 02:23:34'),(150,1,99,415.00,'2014-11-19 02:23:48'),(151,1,101,406.23,'2014-11-19 02:23:50'),(152,1,103,364.97,'2014-11-19 02:24:02'),(153,1,103,535.70,'2014-11-19 02:24:02'),(154,1,105,30.28,'2014-11-19 02:24:04'),(155,1,105,49.35,'2014-11-19 02:24:10'),(156,1,105,55.00,'2014-11-19 02:24:11'),(157,1,105,57.43,'2014-11-19 02:24:13'),(158,1,105,60.11,'2014-11-19 02:24:14'),(159,1,105,67.85,'2014-11-19 02:24:15'),(160,1,105,64.65,'2014-11-19 02:24:16'),(161,1,105,70.80,'2014-11-19 02:24:21'),(162,1,105,79.35,'2014-11-19 02:24:22'),(163,1,105,96.50,'2014-11-19 02:24:23'),(164,1,105,102.35,'2014-11-19 02:24:24'),(165,1,106,57.60,'2014-11-19 02:24:25'),(166,1,106,31.78,'2014-11-19 02:24:31'),(167,1,106,54.60,'2014-11-19 02:24:32'),(168,1,106,64.00,'2014-11-19 02:24:37'),(169,1,107,69.00,'2014-11-19 02:24:44'),(170,1,108,22.00,'2014-11-19 02:24:50'),(171,1,108,24.00,'2014-11-19 02:24:51'),(172,1,108,30.00,'2014-11-19 02:24:51'),(173,1,109,36.00,'2014-11-19 02:24:53'),(174,1,109,60.45,'2014-11-19 02:24:54'),(175,1,109,62.10,'2014-11-19 02:24:55'),(176,1,45,7.00,'2014-11-19 02:31:04'),(177,1,47,25.00,'2014-11-19 02:31:15'),(178,1,48,55.00,'2014-11-19 02:31:16'),(179,1,47,171.00,'2014-11-19 02:31:17'),(180,1,48,17.99,'2014-11-19 02:31:23'),(181,1,45,4.00,'2014-11-19 02:31:24'),(182,1,54,30.00,'2014-11-19 02:31:36'),(183,1,54,4.43,'2014-11-19 02:31:37'),(184,1,54,2.53,'2014-11-19 02:31:38'),(185,1,54,4.43,'2014-11-19 02:31:39'),(186,1,54,3.77,'2014-11-19 02:31:46'),(187,1,54,4.64,'2014-11-19 02:31:46'),(188,1,54,4.58,'2014-11-19 02:31:52'),(189,1,54,4.07,'2014-11-19 02:31:53'),(190,1,54,21.00,'2014-11-19 02:31:54'),(191,1,54,22.36,'2014-11-19 02:31:55'),(192,1,55,2.94,'2014-11-19 02:31:55'),(193,1,55,8.44,'2014-11-19 02:31:56'),(194,1,55,2.22,'2014-11-19 02:31:57'),(195,1,56,86.00,'2014-11-19 02:31:58'),(196,1,56,24.26,'2014-11-19 02:31:58'),(197,1,56,77.10,'2014-11-19 02:31:59'),(198,1,56,90.43,'2014-11-19 02:32:00'),(199,1,56,106.69,'2014-11-19 02:32:00'),(200,1,56,105.06,'2014-11-19 02:32:01'),(201,1,56,127.18,'2014-11-19 02:32:02'),(202,1,56,53.95,'2014-11-19 02:32:03'),(203,1,56,66.00,'2014-11-19 02:32:03'),(204,1,56,34.10,'2014-11-19 02:32:14'),(205,1,56,48.00,'2014-11-19 02:32:14'),(206,1,56,45.58,'2014-11-19 02:32:15'),(207,1,56,60.00,'2014-11-19 02:32:16'),(208,1,56,52.00,'2014-11-19 02:32:16'),(209,1,56,64.00,'2014-11-19 02:32:17'),(210,1,56,35.50,'2014-11-19 02:32:17'),(211,1,56,75.00,'2014-11-19 02:32:18'),(212,1,57,96.66,'2014-11-19 02:32:19'),(213,1,57,54.64,'2014-11-19 02:32:20'),(214,1,57,45.00,'2014-11-19 02:32:20'),(215,1,57,69.00,'2014-11-19 02:32:21'),(216,1,57,72.15,'2014-11-19 02:32:21'),(217,1,57,77.00,'2014-11-19 02:32:22'),(218,1,61,72.45,'2014-11-19 02:32:30'),(219,1,61,34.80,'2014-11-19 02:32:34'),(220,1,62,229.16,'2014-11-19 02:32:35'),(221,1,62,153.14,'2014-11-19 02:32:35'),(222,1,62,138.88,'2014-11-19 02:32:36'),(223,1,62,165.14,'2014-11-19 02:32:36'),(224,1,62,177.14,'2014-11-19 02:32:37'),(225,1,63,500.20,'2014-11-19 02:32:38'),(226,1,63,242.50,'2014-11-19 02:32:39'),(227,1,63,207.82,'2014-11-19 02:32:40'),(228,1,63,345.00,'2014-11-19 02:32:40'),(229,1,63,355.80,'2014-11-19 02:32:41'),(230,1,64,322.53,'2014-11-19 02:32:47'),(231,1,64,133.13,'2014-11-19 02:32:48'),(232,1,64,171.81,'2014-11-19 02:32:49'),(233,1,64,202.49,'2014-11-19 02:32:50'),(234,1,64,178.48,'2014-11-19 02:32:51'),(235,1,64,305.19,'2014-11-19 02:32:51'),(236,1,65,234.50,'2014-11-19 02:32:52'),(237,1,65,238.50,'2014-11-19 02:32:53'),(238,1,65,273.18,'2014-11-19 02:32:54'),(239,1,65,102.45,'2014-11-19 02:32:54'),(240,1,65,126.46,'2014-11-19 02:32:55'),(241,1,65,199.82,'2014-11-19 02:32:56'),(242,1,67,337.21,'2014-11-19 02:33:08'),(243,1,67,370.55,'2014-11-19 02:33:09'),(244,1,67,319.87,'2014-11-19 02:33:15'),(245,1,68,188.00,'2014-11-19 02:33:15'),(246,1,68,250.51,'2014-11-19 02:33:21'),(247,1,68,249.17,'2014-11-19 02:33:21'),(248,1,68,323.87,'2014-11-19 02:33:22'),(249,1,68,291.86,'2014-11-19 02:33:23'),(250,1,70,40.80,'2014-11-19 02:33:24'),(251,1,70,24.85,'2014-11-19 02:33:25'),(252,1,70,49.20,'2014-11-19 02:33:26'),(253,1,70,50.00,'2014-11-19 02:33:26'),(254,1,73,104.65,'2014-11-19 02:33:28'),(255,1,73,85.20,'2014-11-19 02:33:34'),(256,1,73,79.00,'2014-11-19 02:33:35'),(257,1,75,51.00,'2014-11-19 02:33:46'),(258,1,75,38.40,'2014-11-19 02:33:47'),(259,1,75,60.00,'2014-11-19 02:33:52'),(260,1,75,43.00,'2014-11-19 02:33:53'),(261,1,78,231.50,'2014-11-19 02:33:55'),(262,1,78,342.37,'2014-11-19 02:33:56'),(263,1,78,346.71,'2014-11-19 02:33:57'),(264,1,78,379.00,'2014-11-19 02:33:57'),(265,1,79,457.08,'2014-11-19 02:33:59'),(266,1,79,301.02,'2014-11-19 02:34:00'),(267,1,79,238.30,'2014-11-19 02:34:00'),(268,1,79,254.34,'2014-11-19 02:34:06'),(269,1,79,331.70,'2014-11-19 02:34:06'),(270,1,79,365.05,'2014-11-19 02:34:07'),(271,1,79,429.07,'2014-11-19 02:34:08'),(272,1,79,389.05,'2014-11-19 02:34:08'),(273,1,79,423.73,'2014-11-19 02:34:14'),(274,1,79,301.02,'2014-11-19 02:34:20'),(275,1,79,342.37,'2014-11-19 02:34:20'),(276,1,81,333.03,'2014-11-19 02:34:22'),(277,1,81,206.32,'2014-11-19 02:34:22'),(278,1,81,200.98,'2014-11-19 02:34:23'),(279,1,81,293.02,'2014-11-19 02:34:34'),(280,1,81,219.66,'2014-11-19 02:34:34'),(281,1,81,208.99,'2014-11-19 02:34:35'),(282,1,82,20.25,'2014-11-19 02:34:35'),(283,1,82,16.20,'2014-11-19 02:34:37'),(284,1,82,18.90,'2014-11-19 02:34:39'),(285,1,85,24.00,'2014-11-19 02:34:41'),(286,1,85,21.80,'2014-11-19 02:34:43'),(287,1,86,223.00,'2014-11-19 02:34:44'),(288,1,86,139.00,'2014-11-19 02:34:45'),(289,1,86,152.90,'2014-11-19 02:34:46'),(290,1,86,119.90,'2014-11-19 02:34:47'),(291,1,86,96.95,'2014-11-19 02:34:48'),(292,1,86,81.65,'2014-11-19 02:34:49'),(293,1,86,132.25,'2014-11-19 02:34:49'),(294,1,86,139.40,'2014-11-19 02:34:50'),(295,1,86,181.00,'2014-11-19 02:34:52'),(296,1,86,198.95,'2014-11-19 02:34:52'),(297,1,86,177.60,'2014-11-19 02:34:53'),(298,1,86,167.24,'2014-11-19 02:34:54'),(299,1,86,220.00,'2014-11-19 02:35:00'),(300,1,86,225.00,'2014-11-19 02:35:01'),(301,1,86,263.64,'2014-11-19 02:35:01'),(302,1,86,209.74,'2014-11-19 02:35:02'),(303,1,86,225.50,'2014-11-19 02:35:02'),(304,1,90,63.84,'2014-11-19 02:35:05'),(305,1,90,38.00,'2014-11-19 02:35:06'),(306,1,90,58.00,'2014-11-19 02:35:06'),(307,1,90,66.70,'2014-11-19 02:35:07'),(308,1,90,41.11,'2014-11-19 02:35:08'),(309,1,90,68.33,'2014-11-19 02:35:08'),(310,1,90,28.80,'2014-11-19 02:35:09'),(311,1,90,27.52,'2014-11-19 02:35:10'),(312,1,90,47.08,'2014-11-19 02:35:10'),(313,1,90,47.00,'2014-11-19 02:35:11'),(314,1,90,44.96,'2014-11-19 02:35:12'),(315,1,90,43.00,'2014-11-19 02:35:18'),(316,1,91,72.00,'2014-11-19 02:35:18'),(317,1,91,51.00,'2014-11-19 02:35:20'),(318,1,92,98.90,'2014-11-19 02:35:21'),(319,1,92,46.66,'2014-11-19 02:35:22'),(320,1,92,70.15,'2014-11-19 02:35:27'),(321,1,92,70.40,'2014-11-19 02:35:29'),(322,1,92,87.31,'2014-11-19 02:35:30'),(323,1,92,112.00,'2014-11-19 02:35:30'),(324,1,92,86.25,'2014-11-19 02:35:31'),(325,1,92,108.90,'2014-11-19 02:35:31'),(326,1,92,82.80,'2014-11-19 02:35:32'),(327,1,92,85.96,'2014-11-19 02:35:33'),(328,1,92,73.00,'2014-11-19 02:35:39'),(329,1,92,110.00,'2014-11-19 02:35:39'),(330,1,94,31.78,'2014-11-19 02:35:45'),(331,1,94,43.11,'2014-11-19 02:35:46'),(332,1,95,121.00,'2014-11-19 02:35:47'),(333,1,95,103.40,'2014-11-19 02:35:47'),(334,1,95,113.30,'2014-11-19 02:35:48'),(335,1,99,371.46,'2014-11-19 02:35:51'),(336,1,99,415.00,'2014-11-19 02:35:57'),(337,1,101,453.18,'2014-11-19 02:35:58'),(338,1,101,406.23,'2014-11-19 02:35:59'),(339,1,103,427.57,'2014-11-19 02:36:00'),(340,1,103,364.97,'2014-11-19 02:36:00'),(341,1,103,535.70,'2014-11-19 02:36:01'),(342,1,105,89.58,'2014-11-19 02:36:02'),(343,1,105,30.28,'2014-11-19 02:36:03'),(344,1,105,49.35,'2014-11-19 02:36:08'),(345,1,105,55.00,'2014-11-19 02:36:09'),(346,1,105,57.43,'2014-11-19 02:36:14'),(347,1,105,60.11,'2014-11-19 02:36:15'),(348,1,105,67.85,'2014-11-19 02:36:16'),(349,1,105,64.65,'2014-11-19 02:36:16'),(350,1,105,70.80,'2014-11-19 02:36:17'),(351,1,105,79.35,'2014-11-19 02:36:18'),(352,1,105,96.50,'2014-11-19 02:36:18'),(353,1,105,102.35,'2014-11-19 02:36:19'),(354,1,106,72.00,'2014-11-19 02:36:20'),(355,1,106,57.60,'2014-11-19 02:36:21'),(356,1,106,31.78,'2014-11-19 02:36:26'),(357,1,106,54.60,'2014-11-19 02:36:27'),(358,1,106,64.00,'2014-11-19 02:36:28'),(359,1,107,115.00,'2014-11-19 02:36:28'),(360,1,107,69.00,'2014-11-19 02:36:29'),(361,1,108,50.00,'2014-11-19 02:36:30'),(362,1,108,22.00,'2014-11-19 02:36:30'),(363,1,108,24.00,'2014-11-19 02:36:31'),(364,1,108,30.00,'2014-11-19 02:36:32'),(365,1,109,135.70,'2014-11-19 02:36:32'),(366,1,109,36.00,'2014-11-19 02:36:33'),(367,1,109,60.45,'2014-11-19 02:36:34'),(368,1,109,62.10,'2014-11-19 02:36:34'),(369,1,45,7.00,'2014-11-19 02:44:23'),(370,1,47,25.00,'2014-11-19 02:44:24'),(371,1,48,55.00,'2014-11-19 02:44:25'),(372,1,47,171.00,'2014-11-19 02:44:26'),(373,1,48,17.99,'2014-11-19 02:44:32'),(374,1,45,4.00,'2014-11-19 02:44:33'),(375,1,54,30.00,'2014-11-19 02:44:35'),(376,1,54,4.43,'2014-11-19 02:44:36'),(377,1,54,2.53,'2014-11-19 02:44:37'),(378,1,54,4.43,'2014-11-19 02:44:37'),(379,1,54,3.77,'2014-11-19 02:44:39'),(380,1,54,4.64,'2014-11-19 02:44:39'),(381,1,54,4.58,'2014-11-19 02:44:40'),(382,1,54,4.07,'2014-11-19 02:44:51'),(383,1,54,21.00,'2014-11-19 02:44:52'),(384,1,54,22.36,'2014-11-19 02:44:52'),(385,1,55,2.94,'2014-11-19 02:44:53'),(386,1,55,8.44,'2014-11-19 02:44:59'),(387,1,55,2.22,'2014-11-19 02:45:00'),(388,1,56,86.00,'2014-11-19 02:45:01'),(389,1,56,24.26,'2014-11-19 02:45:02'),(390,1,56,77.10,'2014-11-19 02:45:02'),(391,1,56,90.43,'2014-11-19 02:45:04'),(392,1,56,106.69,'2014-11-19 02:45:10'),(393,1,56,105.06,'2014-11-19 02:45:10'),(394,1,56,127.18,'2014-11-19 02:45:11'),(395,1,56,53.95,'2014-11-19 02:45:11'),(396,1,56,66.00,'2014-11-19 02:45:12'),(397,1,56,34.10,'2014-11-19 02:45:23'),(398,1,56,48.00,'2014-11-19 02:45:23'),(399,1,56,45.58,'2014-11-19 02:45:34'),(400,1,56,60.00,'2014-11-19 02:45:34'),(401,1,56,52.00,'2014-11-19 02:45:35'),(402,1,56,64.00,'2014-11-19 02:45:36'),(403,1,56,35.50,'2014-11-19 02:45:36'),(404,1,56,75.00,'2014-11-19 02:45:37'),(405,1,57,96.66,'2014-11-19 02:45:37'),(406,1,57,54.64,'2014-11-19 02:45:38'),(407,1,57,45.00,'2014-11-19 02:45:38'),(408,1,57,69.00,'2014-11-19 02:45:39'),(409,1,57,72.15,'2014-11-19 02:45:40'),(410,1,57,77.00,'2014-11-19 02:45:40'),(411,1,61,72.45,'2014-11-19 02:45:43'),(412,1,61,34.80,'2014-11-19 02:45:43'),(413,1,62,229.16,'2014-11-19 02:45:44'),(414,1,62,153.14,'2014-11-19 02:45:46'),(415,1,62,138.88,'2014-11-19 02:45:46'),(416,1,62,165.14,'2014-11-19 02:45:47'),(417,1,62,177.14,'2014-11-19 02:45:47'),(418,1,63,500.20,'2014-11-19 02:45:48'),(419,1,63,242.50,'2014-11-19 02:45:49'),(420,1,63,207.82,'2014-11-19 02:45:50'),(421,1,63,345.00,'2014-11-19 02:45:51'),(422,1,63,355.80,'2014-11-19 02:45:51'),(423,1,64,322.53,'2014-11-19 02:45:52'),(424,1,64,133.13,'2014-11-19 02:45:53'),(425,1,64,171.81,'2014-11-19 02:45:53'),(426,1,64,202.49,'2014-11-19 02:45:54'),(427,1,64,178.48,'2014-11-19 02:45:54'),(428,1,64,305.19,'2014-11-19 02:45:55'),(429,1,65,234.50,'2014-11-19 02:45:56'),(430,1,65,238.50,'2014-11-19 02:46:02'),(431,1,65,273.18,'2014-11-19 02:46:03'),(432,1,65,102.45,'2014-11-19 02:46:04'),(433,1,65,126.46,'2014-11-19 02:46:06'),(434,1,65,199.82,'2014-11-19 02:46:06'),(435,1,67,337.21,'2014-11-19 02:46:08'),(436,1,67,370.55,'2014-11-19 02:46:09'),(437,1,67,319.87,'2014-11-19 02:46:15'),(438,1,68,188.00,'2014-11-19 02:46:20'),(439,1,68,250.51,'2014-11-19 02:46:21'),(440,1,68,249.17,'2014-11-19 02:46:22'),(441,1,68,323.87,'2014-11-19 02:46:23'),(442,1,68,291.86,'2014-11-19 02:46:23'),(443,1,70,40.80,'2014-11-19 02:46:30'),(444,1,70,24.85,'2014-11-19 02:46:30'),(445,1,70,49.20,'2014-11-19 02:46:31'),(446,1,70,50.00,'2014-11-19 02:46:32'),(447,1,73,104.65,'2014-11-19 02:46:39'),(448,1,73,85.20,'2014-11-19 02:46:39'),(449,1,73,79.00,'2014-11-19 02:46:40'),(450,1,75,51.00,'2014-11-19 02:46:41'),(451,1,75,38.40,'2014-11-19 02:46:52'),(452,1,75,60.00,'2014-11-19 02:46:53'),(453,1,75,43.00,'2014-11-19 02:46:54'),(454,1,78,231.50,'2014-11-19 02:46:56'),(455,1,78,342.37,'2014-11-19 02:46:57'),(456,1,78,346.71,'2014-11-19 02:46:57'),(457,1,78,379.00,'2014-11-19 02:46:58'),(458,1,79,457.08,'2014-11-19 02:47:03'),(459,1,79,301.02,'2014-11-19 02:47:05'),(460,1,79,238.30,'2014-11-19 02:47:05'),(461,1,79,254.34,'2014-11-19 02:47:11'),(462,1,79,331.70,'2014-11-19 02:47:11'),(463,1,79,365.05,'2014-11-19 02:47:12'),(464,1,79,429.07,'2014-11-19 02:47:13'),(465,1,79,389.05,'2014-11-19 02:47:15'),(466,1,79,423.73,'2014-11-19 02:47:15'),(467,1,79,301.02,'2014-11-19 02:47:16'),(468,1,79,342.37,'2014-11-19 02:47:17'),(469,1,81,333.03,'2014-11-19 02:47:18'),(470,1,81,206.32,'2014-11-19 02:47:19'),(471,1,81,200.98,'2014-11-19 02:47:20'),(472,1,81,293.02,'2014-11-19 02:47:20'),(473,1,81,219.66,'2014-11-19 02:47:21'),(474,1,81,208.99,'2014-11-19 02:47:21'),(475,1,82,20.25,'2014-11-19 02:47:22'),(476,1,82,16.20,'2014-11-19 02:47:23'),(477,1,82,18.90,'2014-11-19 02:47:24'),(478,1,85,24.00,'2014-11-19 02:47:26'),(479,1,85,21.80,'2014-11-19 02:47:27'),(480,1,86,223.00,'2014-11-19 02:47:28'),(481,1,86,139.00,'2014-11-19 02:47:28'),(482,1,86,152.90,'2014-11-19 02:47:29'),(483,1,86,119.90,'2014-11-19 02:47:30'),(484,1,86,96.95,'2014-11-19 02:47:31'),(485,1,86,81.65,'2014-11-19 02:47:41'),(486,1,86,132.25,'2014-11-19 02:47:42'),(487,1,86,139.40,'2014-11-19 02:47:43'),(488,1,86,181.00,'2014-11-19 02:47:43'),(489,1,86,198.95,'2014-11-19 02:47:44'),(490,1,86,177.60,'2014-11-19 02:47:45'),(491,1,86,167.24,'2014-11-19 02:47:46'),(492,1,86,220.00,'2014-11-19 02:47:46'),(493,1,86,225.00,'2014-11-19 02:47:47'),(494,1,86,263.64,'2014-11-19 02:47:48'),(495,1,86,209.74,'2014-11-19 02:47:48'),(496,1,86,225.50,'2014-11-19 02:47:49'),(497,1,90,63.84,'2014-11-19 02:47:57'),(498,1,90,38.00,'2014-11-19 02:47:58'),(499,1,90,58.00,'2014-11-19 02:47:58'),(500,1,90,66.70,'2014-11-19 02:47:59'),(501,1,90,41.11,'2014-11-19 02:48:00'),(502,1,90,68.33,'2014-11-19 02:48:01'),(503,1,90,28.80,'2014-11-19 02:48:02'),(504,1,90,27.52,'2014-11-19 02:48:02'),(505,1,90,47.08,'2014-11-19 02:48:03'),(506,1,90,47.00,'2014-11-19 02:48:04'),(507,1,90,44.96,'2014-11-19 02:48:05'),(508,1,90,43.00,'2014-11-19 02:48:05'),(509,1,91,72.00,'2014-11-19 02:48:06'),(510,1,91,51.00,'2014-11-19 02:48:07'),(511,1,92,98.90,'2014-11-19 02:48:07'),(512,1,92,46.66,'2014-11-19 02:48:08'),(513,1,92,70.15,'2014-11-19 02:48:10'),(514,1,92,70.40,'2014-11-19 02:48:16'),(515,1,92,87.31,'2014-11-19 02:48:17'),(516,1,92,112.00,'2014-11-19 02:48:18'),(517,1,92,86.25,'2014-11-19 02:48:18'),(518,1,92,108.90,'2014-11-19 02:48:19'),(519,1,92,82.80,'2014-11-19 02:48:20'),(520,1,92,85.96,'2014-11-19 02:48:20'),(521,1,92,73.00,'2014-11-19 02:48:31'),(522,1,92,110.00,'2014-11-19 02:48:32'),(523,1,94,31.78,'2014-11-19 02:48:33'),(524,1,94,43.11,'2014-11-19 02:48:34'),(525,1,95,121.00,'2014-11-19 02:48:34'),(526,1,95,103.40,'2014-11-19 02:48:35'),(527,1,95,113.30,'2014-11-19 02:48:41'),(528,1,99,371.46,'2014-11-19 02:48:44'),(529,1,99,415.00,'2014-11-19 02:48:45'),(530,1,101,453.18,'2014-11-19 02:48:47'),(531,1,101,406.23,'2014-11-19 02:48:47'),(532,1,103,427.57,'2014-11-19 02:48:49'),(533,1,103,364.97,'2014-11-19 02:48:49'),(534,1,103,535.70,'2014-11-19 02:48:50'),(535,1,105,89.58,'2014-11-19 02:48:51'),(536,1,105,30.28,'2014-11-19 02:48:52'),(537,1,105,49.35,'2014-11-19 02:48:53'),(538,1,105,55.00,'2014-11-19 02:48:53'),(539,1,105,57.43,'2014-11-19 02:48:54'),(540,1,105,60.11,'2014-11-19 02:48:55'),(541,1,105,67.85,'2014-11-19 02:48:56'),(542,1,105,64.65,'2014-11-19 02:48:56'),(543,1,105,70.80,'2014-11-19 02:48:57'),(544,1,105,79.35,'2014-11-19 02:48:58'),(545,1,105,96.50,'2014-11-19 02:48:58'),(546,1,105,102.35,'2014-11-19 02:48:59'),(547,1,106,72.00,'2014-11-19 02:49:00'),(548,1,106,57.60,'2014-11-19 02:49:01'),(549,1,106,31.78,'2014-11-19 02:49:01'),(550,1,106,54.60,'2014-11-19 02:49:02'),(551,1,106,64.00,'2014-11-19 02:49:03'),(552,1,107,115.00,'2014-11-19 02:49:04'),(553,1,107,69.00,'2014-11-19 02:49:05'),(554,1,108,50.00,'2014-11-19 02:49:06'),(555,1,108,22.00,'2014-11-19 02:49:07'),(556,1,108,24.00,'2014-11-19 02:49:07'),(557,1,108,30.00,'2014-11-19 02:49:09'),(558,1,109,135.70,'2014-11-19 02:49:09'),(559,1,109,36.00,'2014-11-19 02:49:10'),(560,1,109,60.45,'2014-11-19 02:49:10'),(561,1,109,62.10,'2014-11-19 02:49:11'),(562,1,111,44.00,'2014-11-19 02:49:13'),(563,1,111,39.00,'2014-11-19 02:49:14'),(564,1,45,7.00,'2014-11-19 02:53:28'),(565,1,47,25.00,'2014-11-19 02:53:29'),(566,1,48,55.00,'2014-11-19 02:53:30'),(567,1,47,171.00,'2014-11-19 02:53:31'),(568,1,48,17.99,'2014-11-19 02:53:32'),(569,1,45,4.00,'2014-11-19 02:53:38'),(570,1,54,30.00,'2014-11-19 02:53:42'),(571,1,54,4.43,'2014-11-19 02:53:42'),(572,1,54,2.53,'2014-11-19 02:53:43'),(573,1,54,4.43,'2014-11-19 02:53:44'),(574,1,54,3.77,'2014-11-19 02:53:45'),(575,1,54,4.64,'2014-11-19 02:53:51'),(576,1,54,4.58,'2014-11-19 02:53:51'),(577,1,54,4.07,'2014-11-19 02:53:52'),(578,1,54,21.00,'2014-11-19 02:53:53'),(579,1,54,22.36,'2014-11-19 02:53:54'),(580,1,55,2.94,'2014-11-19 02:53:55'),(581,1,55,8.44,'2014-11-19 02:53:55'),(582,1,55,2.22,'2014-11-19 02:53:56'),(583,1,56,86.00,'2014-11-19 02:53:56'),(584,1,56,24.26,'2014-11-19 02:53:57'),(585,1,56,77.10,'2014-11-19 02:53:58'),(586,1,56,90.43,'2014-11-19 02:53:59'),(587,1,56,106.69,'2014-11-19 02:53:59'),(588,1,56,105.06,'2014-11-19 02:54:00'),(589,1,56,127.18,'2014-11-19 02:54:01'),(590,1,56,53.95,'2014-11-19 02:54:02'),(591,1,56,66.00,'2014-11-19 02:54:02'),(592,1,56,34.10,'2014-11-19 02:54:03'),(593,1,56,48.00,'2014-11-19 02:54:03'),(594,1,56,45.58,'2014-11-19 02:54:04'),(595,1,56,60.00,'2014-11-19 02:54:05'),(596,1,56,52.00,'2014-11-19 02:54:05'),(597,1,56,64.00,'2014-11-19 02:54:06'),(598,1,56,35.50,'2014-11-19 02:54:06'),(599,1,56,75.00,'2014-11-19 02:54:07'),(600,1,57,96.66,'2014-11-19 02:54:08'),(601,1,57,54.64,'2014-11-19 02:54:08'),(602,1,57,45.00,'2014-11-19 02:54:09'),(603,1,57,69.00,'2014-11-19 02:54:10'),(604,1,57,72.15,'2014-11-19 02:54:11'),(605,1,57,77.00,'2014-11-19 02:54:22'),(606,1,61,72.45,'2014-11-19 02:54:29'),(607,1,61,34.80,'2014-11-19 02:54:30'),(608,1,62,229.16,'2014-11-19 02:54:31'),(609,1,62,153.14,'2014-11-19 02:54:32'),(610,1,62,138.88,'2014-11-19 02:54:33'),(611,1,62,165.14,'2014-11-19 02:54:33'),(612,1,62,177.14,'2014-11-19 02:54:34'),(613,1,63,500.20,'2014-11-19 02:54:35'),(614,1,63,242.50,'2014-11-19 02:54:42'),(615,1,63,207.82,'2014-11-19 02:54:42'),(616,1,63,345.00,'2014-11-19 02:54:43'),(617,1,63,355.80,'2014-11-19 02:54:44'),(618,1,64,322.53,'2014-11-19 02:54:44'),(619,1,64,133.13,'2014-11-19 02:54:45'),(620,1,64,171.81,'2014-11-19 02:54:46'),(621,1,64,202.49,'2014-11-19 02:54:47'),(622,1,64,178.48,'2014-11-19 02:54:47'),(623,1,64,305.19,'2014-11-19 02:54:48'),(624,1,65,234.50,'2014-11-19 02:54:49'),(625,1,65,238.50,'2014-11-19 02:54:50'),(626,1,65,273.18,'2014-11-19 02:54:56'),(627,1,65,102.45,'2014-11-19 02:54:57'),(628,1,65,126.46,'2014-11-19 02:54:58'),(629,1,65,199.82,'2014-11-19 02:54:58'),(630,1,67,337.21,'2014-11-19 02:55:00'),(631,1,67,370.55,'2014-11-19 02:55:01'),(632,1,67,319.87,'2014-11-19 02:55:02'),(633,1,68,188.00,'2014-11-19 02:55:02'),(634,1,68,250.51,'2014-11-19 02:55:08'),(635,1,68,249.17,'2014-11-19 02:55:09'),(636,1,68,323.87,'2014-11-19 02:55:09'),(637,1,68,291.86,'2014-11-19 02:55:10'),(638,1,70,40.80,'2014-11-19 02:55:11'),(639,1,70,24.85,'2014-11-19 02:55:12'),(640,1,70,49.20,'2014-11-19 02:55:13'),(641,1,70,50.00,'2014-11-19 02:55:13'),(642,1,73,104.65,'2014-11-19 02:55:15'),(643,1,73,85.20,'2014-11-19 02:55:16'),(644,1,73,79.00,'2014-11-19 02:55:17'),(645,1,75,51.00,'2014-11-19 02:55:18'),(646,1,75,38.40,'2014-11-19 02:55:18'),(647,1,75,60.00,'2014-11-19 02:55:19'),(648,1,75,43.00,'2014-11-19 02:55:20'),(649,1,78,231.50,'2014-11-19 02:55:27'),(650,1,78,342.37,'2014-11-19 02:55:28'),(651,1,78,346.71,'2014-11-19 02:55:29'),(652,1,78,379.00,'2014-11-19 02:55:29'),(653,1,79,457.08,'2014-11-19 02:55:30'),(654,1,79,301.02,'2014-11-19 02:55:31'),(655,1,79,238.30,'2014-11-19 02:55:32'),(656,1,79,254.34,'2014-11-19 02:55:33'),(657,1,79,331.70,'2014-11-19 02:55:33'),(658,1,79,365.05,'2014-11-19 02:55:34'),(659,1,79,429.07,'2014-11-19 02:55:35'),(660,1,79,389.05,'2014-11-19 02:55:35'),(661,1,79,423.73,'2014-11-19 02:55:36'),(662,1,79,301.02,'2014-11-19 02:55:36'),(663,1,79,342.37,'2014-11-19 02:55:37'),(664,1,81,333.03,'2014-11-19 02:55:39'),(665,1,81,206.32,'2014-11-19 02:55:39'),(666,1,81,200.98,'2014-11-19 02:55:40'),(667,1,81,293.02,'2014-11-19 02:55:41'),(668,1,81,219.66,'2014-11-19 02:55:41'),(669,1,81,208.99,'2014-11-19 02:55:42'),(670,1,82,20.25,'2014-11-19 02:55:42'),(671,1,82,16.20,'2014-11-19 02:55:44'),(672,1,82,18.90,'2014-11-19 02:55:45'),(673,1,85,24.00,'2014-11-19 02:55:47'),(674,1,85,21.80,'2014-11-19 02:55:47'),(675,1,86,223.00,'2014-11-19 02:55:48'),(676,1,86,139.00,'2014-11-19 02:55:49'),(677,1,86,152.90,'2014-11-19 02:55:49'),(678,1,86,119.90,'2014-11-19 02:55:50'),(679,1,86,96.95,'2014-11-19 02:55:51'),(680,1,86,81.65,'2014-11-19 02:55:51'),(681,1,86,132.25,'2014-11-19 02:55:52'),(682,1,86,139.40,'2014-11-19 02:55:53'),(683,1,86,181.00,'2014-11-19 02:55:58'),(684,1,86,198.95,'2014-11-19 02:55:59'),(685,1,86,177.60,'2014-11-19 02:55:59'),(686,1,86,167.24,'2014-11-19 02:56:00'),(687,1,86,220.00,'2014-11-19 02:56:01'),(688,1,86,225.00,'2014-11-19 02:56:02'),(689,1,86,263.64,'2014-11-19 02:56:03'),(690,1,86,209.74,'2014-11-19 02:56:03'),(691,1,86,225.50,'2014-11-19 02:56:04'),(692,1,90,63.84,'2014-11-19 02:56:07'),(693,1,90,38.00,'2014-11-19 02:56:07'),(694,1,90,58.00,'2014-11-19 02:56:08'),(695,1,90,66.70,'2014-11-19 02:56:09'),(696,1,90,41.11,'2014-11-19 02:56:09'),(697,1,90,68.33,'2014-11-19 02:56:20'),(698,1,90,28.80,'2014-11-19 02:56:21'),(699,1,90,27.52,'2014-11-19 02:56:21'),(700,1,90,47.08,'2014-11-19 02:56:27'),(701,1,90,47.00,'2014-11-19 02:56:28'),(702,1,90,44.96,'2014-11-19 02:56:28'),(703,1,90,43.00,'2014-11-19 02:56:29'),(704,1,91,72.00,'2014-11-19 02:56:29'),(705,1,91,51.00,'2014-11-19 02:56:30'),(706,1,92,98.90,'2014-11-19 02:56:31'),(707,1,92,46.66,'2014-11-19 02:56:31'),(708,1,92,70.15,'2014-11-19 02:56:32'),(709,1,92,70.40,'2014-11-19 02:56:33'),(710,1,92,87.31,'2014-11-19 02:56:34'),(711,1,92,112.00,'2014-11-19 02:56:34'),(712,1,92,86.25,'2014-11-19 02:56:35'),(713,1,92,108.90,'2014-11-19 02:56:36'),(714,1,92,82.80,'2014-11-19 02:56:36'),(715,1,92,85.96,'2014-11-19 02:56:37'),(716,1,92,73.00,'2014-11-19 02:56:38'),(717,1,92,110.00,'2014-11-19 02:56:38'),(718,1,94,31.78,'2014-11-19 02:56:40'),(719,1,94,43.11,'2014-11-19 02:56:41'),(720,1,95,121.00,'2014-11-19 02:56:46'),(721,1,95,103.40,'2014-11-19 02:56:47'),(722,1,95,113.30,'2014-11-19 02:56:47'),(723,1,99,371.46,'2014-11-19 02:56:50'),(724,1,99,415.00,'2014-11-19 02:56:50'),(725,1,101,453.18,'2014-11-19 02:56:51'),(726,1,101,406.23,'2014-11-19 02:56:52'),(727,1,103,427.57,'2014-11-19 02:56:53'),(728,1,103,364.97,'2014-11-19 02:56:54'),(729,1,103,535.70,'2014-11-19 02:56:54'),(730,1,105,89.58,'2014-11-19 02:56:56'),(731,1,105,30.28,'2014-11-19 02:56:57'),(732,1,105,49.35,'2014-11-19 02:56:58'),(733,1,105,55.00,'2014-11-19 02:56:59'),(734,1,105,57.43,'2014-11-19 02:56:59'),(735,1,105,60.11,'2014-11-19 02:57:00'),(736,1,105,67.85,'2014-11-19 02:57:00'),(737,1,105,64.65,'2014-11-19 02:57:01'),(738,1,105,70.80,'2014-11-19 02:57:02'),(739,1,105,79.35,'2014-11-19 02:57:03'),(740,1,105,96.50,'2014-11-19 02:57:03'),(741,1,105,102.35,'2014-11-19 02:57:04'),(742,1,106,72.00,'2014-11-19 02:57:09'),(743,1,106,57.60,'2014-11-19 02:57:10'),(744,1,106,31.78,'2014-11-19 02:57:11'),(745,1,106,54.60,'2014-11-19 02:57:11'),(746,1,106,64.00,'2014-11-19 02:57:12'),(747,1,107,115.00,'2014-11-19 02:57:13'),(748,1,107,69.00,'2014-11-19 02:57:14'),(749,1,108,50.00,'2014-11-19 02:57:14'),(750,1,108,22.00,'2014-11-19 02:57:16'),(751,1,108,24.00,'2014-11-19 02:57:17'),(752,1,108,30.00,'2014-11-19 02:57:18'),(753,1,109,135.70,'2014-11-19 02:57:18'),(754,1,109,36.00,'2014-11-19 02:57:19'),(755,1,109,60.45,'2014-11-19 02:57:19'),(756,1,109,62.10,'2014-11-19 02:57:20'),(757,1,111,44.00,'2014-11-19 02:57:37'),(758,1,111,39.00,'2014-11-19 02:57:38'),(759,1,111,44.00,'2014-11-19 02:57:39'),(760,1,45,7.00,'2014-11-19 02:58:54'),(761,1,47,25.00,'2014-11-19 02:59:05'),(762,1,48,55.00,'2014-11-19 02:59:06'),(763,1,47,171.00,'2014-11-19 02:59:07'),(764,1,48,17.99,'2014-11-19 02:59:07'),(765,1,45,4.00,'2014-11-19 02:59:08'),(766,1,54,30.00,'2014-11-19 02:59:16'),(767,1,54,4.43,'2014-11-19 02:59:17'),(768,1,54,2.53,'2014-11-19 02:59:17'),(769,1,54,4.43,'2014-11-19 02:59:18'),(770,1,54,3.77,'2014-11-19 02:59:19'),(771,1,54,4.64,'2014-11-19 02:59:20'),(772,1,54,4.58,'2014-11-19 02:59:21'),(773,1,54,4.07,'2014-11-19 02:59:22'),(774,1,54,21.00,'2014-11-19 02:59:23'),(775,1,54,22.36,'2014-11-19 02:59:24'),(776,1,55,2.94,'2014-11-19 02:59:24'),(777,1,55,8.44,'2014-11-19 02:59:25'),(778,1,55,2.22,'2014-11-19 02:59:30'),(779,1,56,86.00,'2014-11-19 02:59:31'),(780,1,56,24.26,'2014-11-19 02:59:32'),(781,1,56,77.10,'2014-11-19 02:59:32'),(782,1,56,90.43,'2014-11-19 02:59:33'),(783,1,56,106.69,'2014-11-19 02:59:34'),(784,1,56,105.06,'2014-11-19 02:59:34'),(785,1,56,127.18,'2014-11-19 02:59:35'),(786,1,56,53.95,'2014-11-19 02:59:36'),(787,1,56,66.00,'2014-11-19 02:59:36'),(788,1,56,34.10,'2014-11-19 02:59:37'),(789,1,56,48.00,'2014-11-19 02:59:37'),(790,1,56,45.58,'2014-11-19 02:59:38'),(791,1,56,60.00,'2014-11-19 02:59:39'),(792,1,56,52.00,'2014-11-19 02:59:39'),(793,1,56,64.00,'2014-11-19 02:59:40'),(794,1,56,35.50,'2014-11-19 02:59:40'),(795,1,56,75.00,'2014-11-19 02:59:41'),(796,1,57,96.66,'2014-11-19 02:59:42'),(797,1,57,54.64,'2014-11-19 02:59:44'),(798,1,57,45.00,'2014-11-19 02:59:45'),(799,1,57,69.00,'2014-11-19 02:59:45'),(800,1,57,72.15,'2014-11-19 02:59:46'),(801,1,57,77.00,'2014-11-19 02:59:46'),(802,1,61,72.45,'2014-11-19 02:59:54'),(803,1,61,34.80,'2014-11-19 02:59:54'),(804,1,62,229.16,'2014-11-19 03:00:00'),(805,1,62,153.14,'2014-11-19 03:00:01'),(806,1,62,138.88,'2014-11-19 03:00:02'),(807,1,62,165.14,'2014-11-19 03:00:02'),(808,1,62,177.14,'2014-11-19 03:00:03'),(809,1,63,500.20,'2014-11-19 03:00:04'),(810,1,63,242.50,'2014-11-19 03:00:05'),(811,1,63,207.82,'2014-11-19 03:00:05'),(812,1,63,345.00,'2014-11-19 03:00:06'),(813,1,63,355.80,'2014-11-19 03:00:07'),(814,1,64,322.53,'2014-11-19 03:00:08'),(815,1,64,133.13,'2014-11-19 03:00:09'),(816,1,64,171.81,'2014-11-19 03:00:09'),(817,1,64,202.49,'2014-11-19 03:00:10'),(818,1,64,178.48,'2014-11-19 03:00:11'),(819,1,64,305.19,'2014-11-19 03:00:14'),(820,1,65,234.50,'2014-11-19 03:00:14'),(821,1,65,238.50,'2014-11-19 03:00:16'),(822,1,65,273.18,'2014-11-19 03:00:17'),(823,1,65,102.45,'2014-11-19 03:00:22'),(824,1,65,126.46,'2014-11-19 03:00:28'),(825,1,65,199.82,'2014-11-19 03:00:29'),(826,1,67,337.21,'2014-11-19 03:00:35'),(827,1,67,370.55,'2014-11-19 03:00:36'),(828,1,67,319.87,'2014-11-19 03:00:37'),(829,1,68,188.00,'2014-11-19 03:00:38'),(830,1,68,250.51,'2014-11-19 03:00:48'),(831,1,68,249.17,'2014-11-19 03:00:49'),(832,1,68,323.87,'2014-11-19 03:00:50'),(833,1,68,291.86,'2014-11-19 03:00:50'),(834,1,70,40.80,'2014-11-19 03:00:52'),(835,1,70,24.85,'2014-11-19 03:00:52'),(836,1,70,49.20,'2014-11-19 03:00:53'),(837,1,70,50.00,'2014-11-19 03:00:54'),(838,1,73,104.65,'2014-11-19 03:00:56'),(839,1,73,85.20,'2014-11-19 03:00:57'),(840,1,73,79.00,'2014-11-19 03:00:57'),(841,1,75,51.00,'2014-11-19 03:00:58'),(842,1,75,38.40,'2014-11-19 03:00:59'),(843,1,75,60.00,'2014-11-19 03:01:00'),(844,1,75,43.00,'2014-11-19 03:01:05'),(845,1,78,231.50,'2014-11-19 03:01:13'),(846,1,78,342.37,'2014-11-19 03:01:14'),(847,1,78,346.71,'2014-11-19 03:01:15'),(848,1,78,379.00,'2014-11-19 03:01:16'),(849,1,79,457.08,'2014-11-19 03:01:17'),(850,1,79,301.02,'2014-11-19 03:01:17'),(851,1,79,238.30,'2014-11-19 03:01:18'),(852,1,79,254.34,'2014-11-19 03:01:19'),(853,1,79,331.70,'2014-11-19 03:01:19'),(854,1,79,365.05,'2014-11-19 03:01:20'),(855,1,79,429.07,'2014-11-19 03:01:21'),(856,1,79,389.05,'2014-11-19 03:01:21'),(857,1,79,423.73,'2014-11-19 03:01:22'),(858,1,79,301.02,'2014-11-19 03:01:23'),(859,1,79,342.37,'2014-11-19 03:01:23'),(860,1,81,333.03,'2014-11-19 03:01:30'),(861,1,81,206.32,'2014-11-19 03:01:30'),(862,1,81,200.98,'2014-11-19 03:01:31'),(863,1,81,293.02,'2014-11-19 03:01:36'),(864,1,81,219.66,'2014-11-19 03:01:37'),(865,1,81,208.99,'2014-11-19 03:01:38'),(866,1,82,20.25,'2014-11-19 03:01:39'),(867,1,82,16.20,'2014-11-19 03:01:40'),(868,1,82,18.90,'2014-11-19 03:01:42'),(869,1,85,24.00,'2014-11-19 03:01:44'),(870,1,85,21.80,'2014-11-19 03:01:44'),(871,1,86,223.00,'2014-11-19 03:01:45'),(872,1,86,139.00,'2014-11-19 03:01:47'),(873,1,86,152.90,'2014-11-19 03:01:47'),(874,1,86,119.90,'2014-11-19 03:01:48'),(875,1,86,96.95,'2014-11-19 03:01:54'),(876,1,86,81.65,'2014-11-19 03:01:55'),(877,1,86,132.25,'2014-11-19 03:01:56'),(878,1,86,139.40,'2014-11-19 03:01:57'),(879,1,86,181.00,'2014-11-19 03:01:58'),(880,1,86,198.95,'2014-11-19 03:01:59'),(881,1,86,177.60,'2014-11-19 03:02:05'),(882,1,86,167.24,'2014-11-19 03:02:07'),(883,1,86,220.00,'2014-11-19 03:02:07'),(884,1,86,225.00,'2014-11-19 03:02:08'),(885,1,86,263.64,'2014-11-19 03:02:09'),(886,1,86,209.74,'2014-11-19 03:02:10'),(887,1,86,225.50,'2014-11-19 03:02:12'),(888,1,90,63.84,'2014-11-19 03:02:23'),(889,1,90,38.00,'2014-11-19 03:02:24'),(890,1,90,58.00,'2014-11-19 03:02:24'),(891,1,90,66.70,'2014-11-19 03:02:25'),(892,1,90,41.11,'2014-11-19 03:02:26'),(893,1,90,68.33,'2014-11-19 03:02:27'),(894,1,90,28.80,'2014-11-19 03:02:28'),(895,1,90,27.52,'2014-11-19 03:02:29'),(896,1,90,47.08,'2014-11-19 03:02:30'),(897,1,90,47.00,'2014-11-19 03:02:31'),(898,1,90,44.96,'2014-11-19 03:02:36'),(899,1,90,43.00,'2014-11-19 03:02:37'),(900,1,91,72.00,'2014-11-19 03:02:43'),(901,1,91,51.00,'2014-11-19 03:02:43'),(902,1,92,98.90,'2014-11-19 03:02:44'),(903,1,92,46.66,'2014-11-19 03:02:45'),(904,1,92,70.15,'2014-11-19 03:02:46'),(905,1,92,70.40,'2014-11-19 03:02:58'),(906,1,92,87.31,'2014-11-19 03:02:59'),(907,1,92,112.00,'2014-11-19 03:03:00'),(908,1,92,86.25,'2014-11-19 03:03:02'),(909,1,92,108.90,'2014-11-19 03:03:03'),(910,1,92,82.80,'2014-11-19 03:03:04'),(911,1,92,85.96,'2014-11-19 03:03:05'),(912,1,92,73.00,'2014-11-19 03:03:07'),(913,1,92,110.00,'2014-11-19 03:03:08'),(914,1,94,31.78,'2014-11-19 03:03:11'),(915,1,94,43.11,'2014-11-19 03:03:12'),(916,1,95,121.00,'2014-11-19 03:03:17'),(917,1,95,103.40,'2014-11-19 03:03:20'),(918,1,95,113.30,'2014-11-19 03:03:21'),(919,1,99,371.46,'2014-11-19 03:03:25'),(920,1,99,415.00,'2014-11-19 03:03:25'),(921,1,101,453.18,'2014-11-19 03:03:26'),(922,1,101,406.23,'2014-11-19 03:03:27'),(923,1,103,427.57,'2014-11-19 03:03:28'),(924,1,103,364.97,'2014-11-19 03:03:29'),(925,1,103,535.70,'2014-11-19 03:03:29'),(926,1,105,89.58,'2014-11-19 03:03:31'),(927,1,105,30.28,'2014-11-19 03:03:32'),(928,1,105,49.35,'2014-11-19 03:03:33'),(929,1,105,55.00,'2014-11-19 03:03:34'),(930,1,105,57.43,'2014-11-19 03:03:34'),(931,1,105,60.11,'2014-11-19 03:03:35'),(932,1,105,67.85,'2014-11-19 03:03:36'),(933,1,105,64.65,'2014-11-19 03:03:36'),(934,1,105,70.80,'2014-11-19 03:03:37'),(935,1,105,79.35,'2014-11-19 03:03:38'),(936,1,105,96.50,'2014-11-19 03:03:39'),(937,1,105,102.35,'2014-11-19 03:03:40'),(938,1,106,72.00,'2014-11-19 03:03:41'),(939,1,106,57.60,'2014-11-19 03:03:42'),(940,1,106,31.78,'2014-11-19 03:03:43'),(941,1,106,54.60,'2014-11-19 03:03:44'),(942,1,106,64.00,'2014-11-19 03:03:45'),(943,1,107,115.00,'2014-11-19 03:03:46'),(944,1,107,69.00,'2014-11-19 03:03:47'),(945,1,108,50.00,'2014-11-19 03:03:53'),(946,1,108,22.00,'2014-11-19 03:03:54'),(947,1,108,24.00,'2014-11-19 03:03:55'),(948,1,108,30.00,'2014-11-19 03:03:55'),(949,1,109,135.70,'2014-11-19 03:03:56'),(950,1,109,36.00,'2014-11-19 03:03:57'),(951,1,109,60.45,'2014-11-19 03:03:58'),(952,1,109,62.10,'2014-11-19 03:04:05'),(953,1,111,42.00,'2014-11-19 03:04:07'),(954,1,111,44.00,'2014-11-19 03:04:07'),(955,1,111,39.00,'2014-11-19 03:04:09'),(956,1,111,44.00,'2014-11-19 03:04:11'),(957,1,111,42.00,'2014-11-19 03:04:13'),(958,1,111,62.00,'2014-11-19 03:04:14'),(959,1,112,78.53,'2014-11-19 03:04:16'),(960,1,117,9.50,'2014-11-19 03:04:22'),(961,1,119,70.40,'2014-11-19 03:04:30'),(962,1,119,31.20,'2014-11-19 03:04:36'),(963,1,119,30.00,'2014-11-19 03:04:38'),(964,1,119,32.40,'2014-11-19 03:04:42'),(965,1,121,28.05,'2014-11-19 03:04:44'),(966,1,121,46.61,'2014-11-19 03:04:45'),(967,1,122,26.00,'2014-11-19 03:04:48'),(968,1,123,15.60,'2014-11-19 03:04:49'),(969,1,123,18.00,'2014-11-19 03:04:50'),(970,1,123,19.83,'2014-11-19 03:04:51'),(971,1,123,20.00,'2014-11-19 03:04:52'),(972,1,123,33.60,'2014-11-19 03:04:53'),(973,1,123,36.00,'2014-11-19 03:04:54'),(974,1,123,38.40,'2014-11-19 03:04:54'),(975,1,123,40.80,'2014-11-19 03:04:55'),(976,1,124,18.43,'2014-11-19 03:05:07'),(977,1,124,26.62,'2014-11-19 03:05:08'),(978,1,124,31.00,'2014-11-19 03:05:09'),(979,1,124,33.00,'2014-11-19 03:05:09'),(980,1,124,42.00,'2014-11-19 03:05:11'),(981,1,48,55.00,'2014-11-19 03:05:13'),(982,1,127,28.00,'2014-11-19 03:05:14'),(983,1,127,27.60,'2014-11-19 03:05:15'),(984,1,127,30.21,'2014-11-19 03:05:15'),(985,1,127,36.65,'2014-11-19 03:05:17'),(986,1,127,50.90,'2014-11-19 03:05:17'),(987,1,127,26.00,'2014-11-19 03:05:23'),(988,1,127,42.00,'2014-11-19 03:05:24'),(989,1,128,36.12,'2014-11-19 03:05:25'),(990,1,129,33.16,'2014-11-19 03:05:26'),(991,1,129,28.00,'2014-11-19 03:05:27'),(992,1,130,75.00,'2014-11-19 03:05:29');
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
  `Name` varchar(300) COLLATE utf8_bin NOT NULL,
  `ExternalKey` varchar(50) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin,
  `Model` text COLLATE utf8_bin,
  `SKU` text COLLATE utf8_bin,
  `Price` decimal(10,2) NOT NULL DEFAULT '0.00',
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
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='shop products';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_products`
--

LOCK TABLES `shop_products` WRITE;
/*!40000 ALTER TABLE `shop_products` DISABLE KEYS */;
INSERT INTO `shop_products` VALUES (42,1,3,46,'Аксесуари для духових шаф','-hez-338250','','HEZ 338250',NULL,213.00,0,'ACTIVE','2014-11-19 01:36:03','2014-11-19 02:58:51'),(43,1,3,46,'Дисковий ніж для нарізання овочів \"по-корейськи\"','-muz45ag1','Для шатківниці кухонних комбайнів серій MUM4, MUM5, MCM55 та MCM6','MUZ45AG1',NULL,100.00,0,'ACTIVE','2014-11-19 01:36:09','2014-11-19 02:58:52'),(44,1,3,46,'Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю','-muz45fv1','Для м\'ясорубки кухонних комбайнів серій MUM4 та MUM5','MUZ45FV1',NULL,100.00,0,'ACTIVE','2014-11-19 01:36:10','2014-11-19 02:58:53'),(45,1,3,46,'Дискова тертка для грубого натирання','-muz8rs1','Для шатківниці кухонних комбайнів серій MUM8, MUM4, MUM5, MCM55 та MCM6','MUZ8RS1',NULL,7.00,0,'ACTIVE','2014-11-19 01:36:17','2014-11-19 02:59:08'),(46,1,3,46,'Насадка з литого алюмінію для приготування фігурних виробів з тіста','-muz45sv1','Для м\'ясорубки кухонних комбайнів серій MUM4 та MUM5','MUZ45SV1',NULL,56.00,0,'ACTIVE','2014-11-19 01:36:19','2014-11-19 02:59:04'),(47,1,3,46,'Млинок для зернових','-muz5gm1','Для кухонних комбайнів Bosch серії MUM 5.','MUZ5GM1',NULL,25.00,0,'ACTIVE','2014-11-19 01:36:19','2014-11-19 02:59:07'),(48,1,4,46,'Цитрус-прес','-mcp-3500','','MCP 3500',NULL,25.32,0,'ACTIVE','2014-11-19 01:36:26','2014-11-19 03:05:13'),(49,1,3,46,'Двосторонній дисковий ніж-тертка (середня)','-muz45kp1','Для шатківниці кухонних комбайнів серій MUM4, MUM5, MCM55 та MCM6','MUZ45KP1',NULL,36.00,0,'ACTIVE','2014-11-19 01:53:40','2014-11-19 02:58:53'),(50,1,3,46,'Приладдя для нарізання продуктів кубиками','-muz5cc1','Для кухонних комбайнів серії MUM5…','MUZ5CC1',NULL,355.00,0,'ACTIVE','2014-11-19 01:53:43','2014-11-19 02:59:06'),(51,1,3,46,'Дискова тертка, груба, з нержавіючої сталі','-mcz4rs1','Для комбайнів серії МСМ4…','MCZ4RS1',NULL,65.88,0,'ACTIVE','2014-11-19 02:19:24','2014-11-19 02:59:09'),(52,1,3,46,'Набір формувальних дисків','-muz45ls1','Для м\'ясорубки кухонних комбайнів серій MUM4 та MUM5','MUZ45LS1',NULL,77.99,0,'ACTIVE','2014-11-19 02:19:25','2014-11-19 02:59:15'),(53,1,3,46,'Дисковий ніж з нержавіючої сталі для нарізання картоплі фрі','-muz45ps1','Для шатківниці кухонних комбайнів серій MUM4, MUM5, MCM55 та MCM6','MUZ45PS1',NULL,55.00,0,'ACTIVE','2014-11-19 02:19:25','2014-11-19 02:59:15'),(54,1,4,47,'Аксесуар для мясорубки','-a986-7000','','A986.7000',NULL,30.00,0,'ACTIVE','2014-11-19 02:19:26','2014-11-19 02:59:24'),(55,1,4,47,'Аксесуар для пилососа','-919-0088','','919.0088',NULL,2.94,0,'ACTIVE','2014-11-19 02:19:39','2014-11-19 02:59:30'),(56,1,4,46,'Блендер','-msm-7800','','MSM 7800',NULL,86.00,0,'ACTIVE','2014-11-19 02:19:41','2014-11-19 02:59:41'),(57,1,4,48,'Блендер','-mq-775','','MQ 775',NULL,96.66,0,'ACTIVE','2014-11-19 02:19:57','2014-11-19 02:59:46'),(58,1,4,49,'Блендер','-hr-1372-90','','HR 1372/90',NULL,70.15,0,'ACTIVE','2014-11-19 02:20:08','2014-11-19 02:59:47'),(59,1,4,50,'Блендер','-hr-1377-90','','HR 1377/90',NULL,92.29,0,'ACTIVE','2014-11-19 02:20:09','2014-11-19 02:59:47'),(60,1,4,51,'Блендер','-mq-67170','','MQ 67170',NULL,66.00,0,'ACTIVE','2014-11-19 02:20:09','2014-11-19 02:59:53'),(61,1,4,52,'Бутербродниця','-sw-6058','','SW 6058',NULL,72.45,0,'ACTIVE','2014-11-19 02:20:10','2014-11-19 02:59:54'),(62,1,4,53,'Варильна поверхня газова','-pc-750-t-an-r-ha','','PC 750 T (AN) R/HA',NULL,229.16,0,'PREORDER','2014-11-19 02:20:14','2014-11-19 03:00:03'),(63,1,4,46,'Варильна поверхня газова','-prp-626b70e','','PRP 626B70E',NULL,500.20,0,'PREORDER','2014-11-19 02:20:22','2014-11-19 03:00:07'),(64,1,4,54,'Варильна поверхня газова','-egt-6345-yok','','EGT 6345 YOK',NULL,322.53,0,'PREORDER','2014-11-19 02:20:32','2014-11-19 03:00:14'),(65,1,4,55,'Варильна поверхня газова','-akt-475-wh','','AKT 475 WH',NULL,234.50,0,'PREORDER','2014-11-19 02:20:38','2014-11-19 03:00:29'),(66,1,4,53,'Варильна поверхня електрична','-kro-642-dz','','KRO 642 DZ',NULL,219.83,0,'PREORDER','2014-11-19 02:21:04','2014-11-19 03:00:30'),(67,1,4,46,'Варильна поверхня електрична','-pkn-651f17e','','PKN 651F17E',NULL,337.21,0,'PREORDER','2014-11-19 02:21:05','2014-11-19 03:00:37'),(68,1,4,54,'Варильна поверхня електрична','-ehf-16240-xk','','EHF 16240 XK',NULL,188.00,0,'PREORDER','2014-11-19 02:21:12','2014-11-19 03:00:50'),(69,1,4,55,'Варильна поверхня електрична','-akt-801-ne','','AKT 801 NE',NULL,198.49,0,'PREORDER','2014-11-19 02:21:16','2014-11-19 03:00:51'),(70,1,4,46,'Випрямляч для волосся','-phs-9590','','PHS 9590',NULL,40.80,0,'ACTIVE','2014-11-19 02:21:17','2014-11-19 03:00:54'),(71,1,4,56,'Генератор','-k-ap-2500','','K-AP 2500',NULL,250.00,0,'PREORDER','2014-11-19 02:21:30','2014-11-19 03:00:54'),(72,1,4,46,'Гриль','-tfb-3302v','','TFB 3302V',NULL,59.00,0,'ACTIVE','2014-11-19 02:21:31','2014-11-19 03:00:55'),(73,1,4,49,'Гриль','-hd-4469-90','','HD 4469/90',NULL,104.65,0,'ACTIVE','2014-11-19 02:21:31','2014-11-19 03:00:57'),(74,1,4,52,'Гриль','-gc-2050','','GC 2050',NULL,74.75,0,'ACTIVE','2014-11-19 02:21:43','2014-11-19 03:00:58'),(75,1,4,47,'Гриль','-ge1000','','GE1000',NULL,51.00,0,'ACTIVE','2014-11-19 02:21:44','2014-11-19 03:01:05'),(76,1,4,46,'Дошка для прасування','-tdn-1700p','','TDN 1700P',NULL,219.41,0,'ACTIVE','2014-11-19 02:21:47','2014-11-19 03:01:06'),(77,1,4,53,'Духова шафа електрична','-ft-850-1-ow','','FT 850.1 OW',NULL,287.68,0,'PREORDER','2014-11-19 02:21:48','2014-11-19 03:01:12'),(78,1,4,46,'Духова шафа електрична','-hbn-231eo','','HBN 231EO',NULL,231.50,0,'PREORDER','2014-11-19 02:21:49','2014-11-19 03:01:16'),(79,1,4,54,'Духова шафа електрична','-eob-5851-aox','','EOB 5851 AOX',NULL,457.08,0,'PREORDER','2014-11-19 02:21:52','2014-11-19 03:01:23'),(80,1,4,51,'Духова шафа електрична','-hb-23gb540','','HB 23GB540',NULL,297.02,0,'PREORDER','2014-11-19 02:21:59','2014-11-19 03:01:24'),(81,1,4,55,'Духова шафа електрична','-akp-468-ix','','AKP 468 IX',NULL,333.03,0,'PREORDER','2014-11-19 02:22:00','2014-11-19 03:01:38'),(82,1,4,57,'Кава в зернах','-qualita-oro-1-kg-','','QUALITA ORO 1 KG.',NULL,20.25,0,'ACTIVE','2014-11-19 02:22:15','2014-11-19 03:01:42'),(83,1,4,46,'Кавоварка','-tca-5309','','TCA 5309',NULL,384.00,0,'ACTIVE','2014-11-19 02:22:19','2014-11-19 03:01:42'),(84,1,4,58,'Кавоварка','-hd-8743-19-xsmall','','HD 8743/19 XSMALL',NULL,295.35,0,'ACTIVE','2014-11-19 02:22:20','2014-11-19 03:01:43'),(85,1,4,46,'Кавомолка','-mkm-6003','','MKM 6003',NULL,24.00,0,'ACTIVE','2014-11-19 02:22:25','2014-11-19 03:01:44'),(86,1,4,46,'Кухонний комбайн','-mum-54920','','MUM 54920',NULL,223.00,0,'ACTIVE','2014-11-19 02:22:32','2014-11-19 03:02:12'),(87,1,4,59,'Кухонний комбайн','-kmc-010','','KMC 010',NULL,635.00,0,'ACTIVE','2014-11-19 02:22:44','2014-11-19 03:02:15'),(88,1,4,50,'Кухонний комбайн','-hr-7774','','HR 7774',NULL,111.00,0,'ACTIVE','2014-11-19 02:22:45','2014-11-19 03:02:20'),(89,1,4,51,'Кухонний комбайн','-mk-55300','','MK 55300',NULL,132.25,0,'ACTIVE','2014-11-19 02:22:46','2014-11-19 03:02:22'),(90,1,4,46,'Міксер','-mfq-4070','','MFQ 4070',NULL,63.84,0,'ACTIVE','2014-11-19 02:22:46','2014-11-19 03:02:37'),(91,1,4,48,'Міксер','-m-1050','','M 1050',NULL,72.00,0,'ACTIVE','2014-11-19 02:23:10','2014-11-19 03:02:43'),(92,1,4,47,'Мясорубка','-mm-1200-89','','MM 1200.89',NULL,98.90,0,'ACTIVE','2014-11-19 02:23:12','2014-11-19 03:03:08'),(93,1,4,50,'Пароварка','-hd-9115-00','','HD 9115/00',NULL,43.11,0,'ACTIVE','2014-11-19 02:23:26','2014-11-19 03:03:09'),(94,1,4,52,'Пароварка','-vc-1401','','VC 1401',NULL,31.78,0,'ACTIVE','2014-11-19 02:23:31','2014-11-19 03:03:12'),(95,1,4,54,'Пилосос','-zsc-6940','','ZSC 6940',NULL,121.00,0,'ACTIVE','2014-11-19 02:23:33','2014-11-19 03:03:21'),(96,1,4,50,'Пилосос','-fc-9170','','FC 9170',NULL,143.16,0,'ACTIVE','2014-11-19 02:23:35','2014-11-19 03:03:21'),(97,1,4,47,'Пилосос','-01z014-st','','01Z014 ST',NULL,82.50,0,'ACTIVE','2014-11-19 02:23:40','2014-11-19 03:03:22'),(98,1,4,47,'Пилосос миючий','-919-0-st','','919.0 ST',NULL,165.60,0,'ACTIVE','2014-11-19 02:23:41','2014-11-19 03:03:24'),(99,1,4,46,'Посудомийка вбудована','-spv-43m10eu','','SPV 43M10EU',NULL,371.46,0,'PREORDER','2014-11-19 02:23:42','2014-11-19 03:03:25'),(100,1,4,54,'Посудомийка вбудована','-esl-6301-lo','','ESL 6301 LO',NULL,339.45,0,'PREORDER','2014-11-19 02:23:48','2014-11-19 03:03:26'),(101,1,4,46,'Пральна машина','-wlo24240pl','','WLO24240PL',NULL,453.18,0,'ACTIVE','2014-11-19 02:23:49','2014-11-19 03:03:27'),(102,1,4,60,'Пральна машина','-ews11254edu','','EWS11254EDU',NULL,393.42,0,'ACTIVE','2014-11-19 02:24:00','2014-11-19 03:03:28'),(103,1,4,54,'Пральна машина','-ews1264edw','','EWS1264EDW',NULL,427.57,0,'ACTIVE','2014-11-19 02:24:01','2014-11-19 03:03:29'),(104,1,4,51,'Пральна машина','-ws10o261pl','','WS10O261PL',NULL,377.77,0,'ACTIVE','2014-11-19 02:24:03','2014-11-19 03:03:30'),(105,1,4,46,'Праска','-tdi-902431-e','','TDI 902431 E',NULL,89.58,0,'ACTIVE','2014-11-19 02:24:04','2014-11-19 03:03:40'),(106,1,4,48,'Праска','-ts-775tp','','TS 775TP',NULL,72.00,0,'ACTIVE','2014-11-19 02:24:24','2014-11-19 03:03:45'),(107,1,4,61,'Праска','-dw-9240','','DW 9240',NULL,115.00,0,'ACTIVE','2014-11-19 02:24:43','2014-11-19 03:03:47'),(108,1,4,47,'Праска','-28z025','','28Z025',NULL,50.00,0,'ACTIVE','2014-11-19 02:24:49','2014-11-19 03:03:55'),(109,1,4,46,'Скиборізка','-mas-9101-n','','MAS 9101 N',NULL,135.70,0,'ACTIVE','2014-11-19 02:24:52','2014-11-19 03:04:05'),(110,1,4,51,'Скиборізка','-ms-65500','','MS 65500',NULL,82.80,0,'ACTIVE','2014-11-19 02:24:56','2014-11-19 03:04:06'),(111,1,4,47,'Скиборізка','-493-6-silver','','493.6 Silver',NULL,88.00,0,'ACTIVE','2014-11-19 02:36:36','2014-11-19 03:04:14'),(112,1,4,46,'Соковитискач','-mes3000','','MES3000',NULL,100.00,0,'ACTIVE','2014-11-19 03:04:15','2014-11-19 03:04:16'),(113,1,4,47,'Соковитискач','-476',NULL,'476',NULL,112.20,0,'ACTIVE','2014-11-19 03:04:17','2014-11-19 03:04:17'),(114,1,4,46,'Стайлер','-phc-9590',NULL,'PHC 9590',NULL,31.65,0,'ACTIVE','2014-11-19 03:04:18','2014-11-19 03:04:18'),(115,1,4,62,'Сушка для овочів і фруктів','-msg01',NULL,'MSG01',NULL,27.60,0,'ACTIVE','2014-11-19 03:04:19','2014-11-19 03:04:19'),(116,1,4,63,'Сушка для овочів і фруктів','-sd-2010',NULL,'SD-2010',NULL,30.21,0,'ACTIVE','2014-11-19 03:04:20','2014-11-19 03:04:20'),(117,1,4,51,'Таблетки для кавоварок','-tz-60002','','TZ 60002',NULL,11.88,0,'ACTIVE','2014-11-19 03:04:21','2014-11-19 03:04:22'),(118,1,4,64,'Таблетки для посудомийних машин','-quantum-20',NULL,'QUANTUM 20',NULL,4.00,0,'ACTIVE','2014-11-19 03:04:23','2014-11-19 03:04:23'),(119,1,4,46,'Тостер','-tat-6313','','TAT 6313',NULL,43.20,0,'ACTIVE','2014-11-19 03:04:24','2014-11-19 03:04:42'),(120,1,4,54,'Тостер','-eat-7100r',NULL,'EAT 7100R',NULL,54.40,0,'ACTIVE','2014-11-19 03:04:42','2014-11-19 03:04:42'),(121,1,4,51,'Тостер','-tt-86104','','TT 86104',NULL,67.86,0,'ACTIVE','2014-11-19 03:04:43','2014-11-19 03:04:46'),(122,1,4,47,'Тостер','-27z012-symbio-line','','27Z012 symbio line',NULL,30.80,0,'ACTIVE','2014-11-19 03:04:47','2014-11-19 03:04:48'),(123,1,4,46,'Фен','-phd-9940','','PHD 9940',NULL,44.40,0,'ACTIVE','2014-11-19 03:04:49','2014-11-19 03:04:55'),(124,1,4,46,'Фен-щітка','-pha-5363','','PHA 5363',NULL,36.00,0,'ACTIVE','2014-11-19 03:05:05','2014-11-19 03:05:11'),(125,1,4,52,'Фритюрниця','-gh-8060',NULL,'GH 8060',NULL,200.00,0,'ACTIVE','2014-11-19 03:05:12','2014-11-19 03:05:12'),(126,1,4,65,'Хлібопічка','-sd-2500-wxe',NULL,'SD-2500 WXE',NULL,125.00,0,'ACTIVE','2014-11-19 03:05:12','2014-11-19 03:05:12'),(127,1,4,46,'Чайник електричний','-twk-8611','','TWK 8611',NULL,73.50,0,'ACTIVE','2014-11-19 03:05:14','2014-11-19 03:05:24'),(128,1,4,48,'Чайник електричний','-wk-300-white','','WK 300 WHITE',NULL,38.00,0,'ACTIVE','2014-11-19 03:05:24','2014-11-19 03:05:25'),(129,1,4,54,'Чайник електричний','-eewa-5210','','EEWA 5210',NULL,39.00,0,'ACTIVE','2014-11-19 03:05:26','2014-11-19 03:05:27'),(130,1,4,51,'Чайник електричний','-tw-86104','','TW 86104',NULL,71.30,0,'ACTIVE','2014-11-19 03:05:28','2014-11-19 03:05:29'),(131,1,4,47,'Чайник електричний','-ck-1004-ix',NULL,'CK 1004 IX',NULL,52.80,0,'ACTIVE','2014-11-19 03:05:29','2014-11-19 03:05:29'),(132,1,4,66,'Шатківниця','-dj-756',NULL,'DJ 756',NULL,43.00,0,'ACTIVE','2014-11-19 03:05:30','2014-11-19 03:05:30');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `backupProductPrice` BEFORE UPDATE ON `shop_products`
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

-- Dump completed on 2014-11-19  3:16:42

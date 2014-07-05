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

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mpws_light` /*!40100 DEFAULT CHARACTER SET latin1 */;

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
  `Address` varchar(500) NOT NULL,
  `POBox` varchar(50) NOT NULL,
  `Country` varchar(300) NOT NULL,
  `City` varchar(300) NOT NULL,
  `Status` enum('ACTIVE','REMOVED') NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `AccountID` (`AccountID`),
  KEY `AccountID_2` (`AccountID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `mpws_accountAddresses_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mpws_accountAddresses_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_accountAddresses`
--

LOCK TABLES `mpws_accountAddresses` WRITE;
/*!40000 ALTER TABLE `mpws_accountAddresses` DISABLE KEYS */;
INSERT INTO `mpws_accountAddresses` VALUES (5,1,79,'wwwww','fsdfsdf','rererererer','fdsfsdfsdf','REMOVED','2014-03-02 20:26:25','2014-03-02 22:37:12'),(19,1,79,'fsdfsdf','345345345','fsdfsdf','uuuuuuu','REMOVED','2014-03-02 22:47:47','2014-03-02 22:55:48'),(20,1,79,'dsadfasdasdfdfdsfdsf','fffff','ffff','dasdasdasdasd','REMOVED','2014-03-02 23:02:38','2014-03-03 00:40:52'),(21,1,79,'setsertser','tsetest','dfsdfsdfsd','fsdfsdfsdfsd','REMOVED','2014-03-03 00:40:27','2014-03-03 00:40:53'),(22,1,79,'Horodotska 123','79001','Ukraine','Lviv','REMOVED','2014-03-03 00:41:05','2014-03-10 00:40:42'),(23,1,79,'xxxxxx','xxxxxx','xxxxxx','xxxxxx','REMOVED','2014-03-03 01:00:12','2014-03-03 15:04:04'),(24,1,79,'Lvivska 34','57841','Ukraine','Kyiv','REMOVED','2014-03-03 19:10:39','2014-03-10 00:41:54'),(25,1,79,'Kyivska 3','78451','Ukraine','Rivne','ACTIVE','2014-03-04 02:22:33','2014-03-04 02:25:13'),(26,1,NULL,'demo','120012','Ukraine','Lviv','ACTIVE','2014-03-04 02:43:40','2014-03-04 02:43:40'),(27,1,NULL,'demo','120012','Ukraine','Lviv','ACTIVE','2014-03-04 02:43:46','2014-03-04 02:43:46'),(28,1,NULL,'demo','120012','Ukraine','Lviv','ACTIVE','2014-03-04 02:46:43','2014-03-04 02:46:43'),(29,1,NULL,'test','test','test','estsetsetset','ACTIVE','2014-03-05 22:52:14','2014-03-05 22:52:14'),(30,1,79,'Lvivska','78045','Ukraine','Kyiv','ACTIVE','2014-03-10 00:44:02','2014-03-10 00:44:02'),(31,1,79,'Zhutomyrska','79451','Ukraine','Dubno','ACTIVE','2014-03-10 00:44:58','2014-03-10 00:44:58'),(32,1,82,'','','','','ACTIVE','2014-04-13 17:03:11','2014-04-13 17:03:11'),(33,1,83,'','','','','ACTIVE','2014-04-13 17:03:15','2014-04-13 17:03:15'),(34,1,84,'','','','','ACTIVE','2014-04-13 17:03:22','2014-04-13 17:03:22'),(35,1,85,'','','','','ACTIVE','2014-04-13 17:06:02','2014-04-13 17:06:02'),(36,1,86,'','','','','ACTIVE','2014-04-13 17:09:31','2014-04-13 17:09:31'),(37,1,87,'','','','','ACTIVE','2014-04-13 17:10:51','2014-04-13 17:10:51'),(38,1,88,'','','','','ACTIVE','2014-04-19 21:19:49','2014-04-19 21:19:49'),(39,1,89,'','','','','ACTIVE','2014-04-19 21:22:04','2014-04-19 21:22:04'),(40,1,90,'groiv upa 12','11111','Ukraine','Lviv','ACTIVE','2014-04-19 21:38:08','2014-04-19 21:38:08');
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
  `PermissionID` int(11) DEFAULT NULL,
  `IsTemporary` tinyint(1) NOT NULL DEFAULT '1',
  `IsOnline` tinyint(1) NOT NULL DEFAULT '0',
  `FirstName` varchar(200) COLLATE utf8_bin NOT NULL,
  `LastName` varchar(200) COLLATE utf8_bin NOT NULL,
  `EMail` varchar(100) COLLATE utf8_bin NOT NULL,
  `Phone` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `Password` varchar(50) COLLATE utf8_bin NOT NULL,
  `ValidationString` varchar(400) COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateLastAccess` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `EMail` (`EMail`),
  KEY `CustomerID` (`CustomerID`),
  KEY `PermisionsID` (`PermissionID`),
  CONSTRAINT `mpws_accounts_ibfk_4` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `mpws_accounts_ibfk_5` FOREIGN KEY (`PermissionID`) REFERENCES `mpws_permissions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_accounts`
--

LOCK TABLES `mpws_accounts` WRITE;
/*!40000 ALTER TABLE `mpws_accounts` DISABLE KEYS */;
INSERT INTO `mpws_accounts` VALUES (77,1,1,1,0,'James','Griffin','test@test.com',NULL,'24d04aa3d61423fb9dae48ac4d7567d5','72e6d9fe68147c4feb0cf7b035be9e05','ACTIVE','0000-00-00 00:00:00','2014-03-01 01:14:14','2014-03-01 01:14:14'),(78,1,1,0,0,'James','demo2','test@demo2.com',NULL,'24d04aa3d61423fb9dae48ac4d7567d5','c261191eb31433b98994f58e57567a67','ACTIVE','0000-00-00 00:00:00','2014-03-01 01:16:39','2014-03-01 01:16:39'),(79,1,1,0,0,'Test','Demo','demo@demo.com5','097-56-56-201','24d04aa3d61423fb9dae48ac4d7567d5','b74c7e4ec4dc62728ee5a2195a8605b2','ACTIVE','0000-00-00 00:00:00','2014-03-01 14:38:46','2014-03-09 23:52:24'),(80,1,1,1,0,'tset','','tset','ttset','4a123a551c46b3a7a2e1b6b76e7d69c9','f5046014417bd9c1098e0f29bd5abf59','ACTIVE','0000-00-00 00:00:00','2014-03-01 14:45:03','2014-03-01 14:45:03'),(81,1,1,1,0,'','','','','4a123a551c46b3a7a2e1b6b76e7d69c9','23fcad34643fa6b3c4d9765778498f21','ACTIVE','0000-00-00 00:00:00','2014-03-01 14:45:14','2014-03-01 14:45:14'),(82,1,1,1,0,'','','','','4a123a551c46b3a7a2e1b6b76e7d69c9','79e4c5826a3eb7159495df60739ea8e4','ACTIVE','0000-00-00 00:00:00','2014-04-13 17:03:11','2014-04-13 17:03:11'),(83,1,1,1,0,'','','','','4a123a551c46b3a7a2e1b6b76e7d69c9','96b43a2b8b7a4345e39ff036d1fb1411','ACTIVE','0000-00-00 00:00:00','2014-04-13 17:03:15','2014-04-13 17:03:15'),(84,1,1,1,0,'','','','','4a123a551c46b3a7a2e1b6b76e7d69c9','a6db90adbd1ceba703b935900b63676c','ACTIVE','0000-00-00 00:00:00','2014-04-13 17:03:22','2014-04-13 17:03:22'),(85,1,1,1,0,'','','','','4a123a551c46b3a7a2e1b6b76e7d69c9','930b420144d08e6655a9742c4e27f7aa','ACTIVE','0000-00-00 00:00:00','2014-04-13 17:06:02','2014-04-13 17:06:02'),(86,1,1,1,0,'','','','','4a123a551c46b3a7a2e1b6b76e7d69c9','d8a652f26fbd5fde44eafb83366c6060','ACTIVE','0000-00-00 00:00:00','2014-04-13 17:09:30','2014-04-13 17:09:31'),(87,1,1,1,0,'','','','','4a123a551c46b3a7a2e1b6b76e7d69c9','731090a3ff07cd56600d19fef1ee968b','ACTIVE','0000-00-00 00:00:00','2014-04-13 17:10:51','2014-04-13 17:10:51'),(88,1,1,1,0,'','','','','4a123a551c46b3a7a2e1b6b76e7d69c9','b47beeb45a7da18c266e60f432fe75e6','ACTIVE','0000-00-00 00:00:00','2014-04-19 21:19:49','2014-04-19 21:19:49'),(89,1,1,1,0,'','','','','4a123a551c46b3a7a2e1b6b76e7d69c9','455399988248744bef4fb19d28392f94','ACTIVE','0000-00-00 00:00:00','2014-04-19 21:22:04','2014-04-19 21:22:04'),(90,1,1,1,0,'test','','test@test.com','123-123-1234','4a123a551c46b3a7a2e1b6b76e7d69c9','0ccb16b9476e2423e0ff74e56750d5b2','ACTIVE','0000-00-00 00:00:00','2014-04-19 21:38:08','2014-04-19 21:38:08'),(91,1,1,0,0,'test','','admin@pb.com.ua','123-123-1234','4a123a551c46b3a7a2e1b6b76e7d69c9','0ccb16b9476e2423e0ff74e56750d5b2','ACTIVE','0000-00-00 00:00:00','2014-04-19 21:38:08','2014-04-19 21:38:08');
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
INSERT INTO `mpws_customer` VALUES (0,'toolbox','toolbox','ACTIVE','','2013-09-03 00:00:00','2013-09-03 00:00:00'),(1,'pb_com_ua','Pobutteh','ACTIVE','','2013-09-03 00:00:00','2013-09-03 00:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_jobs`
--

LOCK TABLES `mpws_jobs` WRITE;
/*!40000 ALTER TABLE `mpws_jobs` DISABLE KEYS */;
INSERT INTO `mpws_jobs` VALUES (6,0,'SI Team Render Weekly Report','SI Team Render Weekly Report','http://toolbox.mpws.com/api.js?caller=reporting&fn=Render&p=token=656c88543646e400eb581f6921b83238&realm=plugin&oid=1&name=SI Team Render Weekly Report&custom=script%3Dweekly%26schedule%3D%2A%2F30+%2F1+%2A+%2A+%2A+%2A','0000-00-00 00:00:00',NULL,'0000-00-00 00:00:00','2013-05-16 22:50:23'),(7,0,'GenerateSitemap','GenerateSitemap','http://toolbox.mpws.com/api.js?caller=shop&fn=ShopActionTrigger&p=token=656c88543646e400eb581f6921b83238&realm=plugin&oid=5&name=GenerateSitemap&custom=schedule%3D%2A+%2F1+%2A+%2A+%2A+%2A','0000-00-00 00:00:00',NULL,'0000-00-00 00:00:00','2013-08-18 20:14:13');
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
  `isAdmin` tinyint(1) NOT NULL,
  `CanCreate` tinyint(1) NOT NULL,
  `CanEdit` tinyint(1) NOT NULL,
  `CanView` tinyint(1) NOT NULL,
  `CanAddUsers` tinyint(1) NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_permissions`
--

LOCK TABLES `mpws_permissions` WRITE;
/*!40000 ALTER TABLE `mpws_permissions` DISABLE KEYS */;
INSERT INTO `mpws_permissions` VALUES (1,1,1,1,1,1,'2014-05-24 00:00:00','2014-05-24 00:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_uploads`
--

LOCK TABLES `mpws_uploads` WRITE;
/*!40000 ALTER TABLE `mpws_uploads` DISABLE KEYS */;
INSERT INTO `mpws_uploads` VALUES (3,0,'/var/www/mpws/rc_1.0/data/uploads/2012-08-26/f03a4a9c48b90e54b99f84014dcdb787/e-card.PNG','f03a4a9c48b90e54b99f84014dcdb787',NULL,'2012-08-26 22:55:36');
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
  `ProductPrice` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `OrderID` (`OrderID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_boughts_ibfk_5` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_boughts_ibfk_6` FOREIGN KEY (`OrderID`) REFERENCES `shop_orders` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_boughts_ibfk_7` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_boughts`
--

LOCK TABLES `shop_boughts` WRITE;
/*!40000 ALTER TABLE `shop_boughts` DISABLE KEYS */;
INSERT INTO `shop_boughts` VALUES (20,1,5,19,17.00,1),(21,1,5,20,17.00,1),(22,1,3,20,213.00,1),(23,1,3,21,213.00,1),(24,1,4,21,100.00,10),(25,1,4,24,100.00,7),(26,1,4,27,100.00,1),(27,1,3,28,213.00,4),(28,1,4,28,100.00,5),(29,1,5,29,17.00,1),(30,1,4,29,100.00,1),(31,1,4,33,100.00,8),(32,1,10,34,171.00,10),(33,1,11,34,37.00,7),(34,1,3,35,213.00,1),(35,1,3,36,213.00,1),(36,1,3,37,213.00,3);
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
  `RootID` int(11) DEFAULT NULL,
  `ParentID` int(11) DEFAULT NULL,
  `CustomerID` int(11) NOT NULL,
  `ExternalKey` varchar(50) COLLATE utf8_bin NOT NULL,
  `Name` varchar(100) COLLATE utf8_bin NOT NULL,
  `Description` text COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `RootID` (`RootID`),
  KEY `ParentID` (`ParentID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_categories_ibfk_4` FOREIGN KEY (`RootID`) REFERENCES `shop_categories` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `shop_categories_ibfk_5` FOREIGN KEY (`ParentID`) REFERENCES `shop_categories` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `shop_categories_ibfk_6` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_categories`
--

LOCK TABLES `shop_categories` WRITE;
/*!40000 ALTER TABLE `shop_categories` DISABLE KEYS */;
INSERT INTO `shop_categories` VALUES (1,NULL,NULL,1,'','Побутова техніка','Побутова техніка','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07'),(2,1,1,1,'','Дошка прасувальні','Дошка прасувальні','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07'),(3,NULL,NULL,1,'','Мийка високого тиску','Мийка високого тиску','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07'),(4,1,1,1,'','Посуд','Посуд','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07'),(5,NULL,NULL,1,'','Професійна техніка','Професійна техніка','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07'),(6,NULL,NULL,1,'','ТВ, відео, аудіо, фото','ТВ, відео, аудіо, фото','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07'),(7,6,6,1,'','Телевізори','Відео обладнання','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07'),(12,6,7,1,'lct_televizoru','LCD телевізори','LCD телевізори','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(13,1,1,1,'kt','Кліматична техніка','Кліматична техніка','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(14,1,1,1,'kt','Крупна побутова техніка','Крупна побутова техніка','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(15,1,1,1,'kt','Дрібна побутова техніка','Дрібна побутова техніка','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(16,1,1,1,'kt','Догляд за будинком','Догляд за будинком','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(17,6,6,1,'kt','Аудіо','Аудіо','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(18,6,6,1,'kt','Відео','Відео','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(19,6,6,1,'kt','Фото','Фото','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(20,6,6,1,'kt','Ігрові приставки','Ігрові приставки','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(21,NULL,NULL,1,'kt','Авто товари','Авто товари','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(22,21,21,1,'kt','Автоелектроніка','Автоелектроніка','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(23,21,21,1,'kt','Авто звук','Авто звук','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(24,21,23,1,'kt','Автомагнітоли','Автомагнітоли','ACTIVE','0000-00-00 00:00:00','0000-00-00 00:00:00'),(25,21,23,1,'','Аксесуари до автозвуку','Аксесуари до автозвуку','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07'),(26,21,21,1,'','АвтоОптика (Світло)','АвтоОптика (Світло)','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07'),(27,21,26,1,'','Габаритні вогні','Габаритні вогні','ACTIVE','2013-08-27 02:26:07','2013-08-27 02:26:07');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_commands`
--

LOCK TABLES `shop_commands` WRITE;
/*!40000 ALTER TABLE `shop_commands` DISABLE KEYS */;
INSERT INTO `shop_commands` VALUES (4,0,'import-descriptions','import descriptions','import descriptions','import descriptions','2013-08-18 19:23:10','2013-08-18 19:23:10','0000-00-00 00:00:00'),(5,0,'generate-sitemap','generate sitemap','generate sitemap','generate sitemap','2013-08-18 20:09:25','2013-08-18 20:09:25','0000-00-00 00:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_currency`
--

LOCK TABLES `shop_currency` WRITE;
/*!40000 ALTER TABLE `shop_currency` DISABLE KEYS */;
INSERT INTO `shop_currency` VALUES (1,0,0,'ACTIVE','EUR',10.70,'0000-00-00 00:00:00','2013-08-15 00:36:46','0000-00-00 00:00:00'),(2,0,0,'ACTIVE','USD',8.10,'0000-00-00 00:00:00','2013-08-15 00:36:53','0000-00-00 00:00:00'),(3,0,1,'ACTIVE','UAH',1.00,'2013-08-15 00:37:14','2013-08-15 00:37:14','0000-00-00 00:00:00'),(4,0,0,'ACTIVE','PLN',2.52,'2013-08-17 01:30:40','2013-08-17 01:30:40','0000-00-00 00:00:00');
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
  `Shipping` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Warehouse` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `Comment` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  `Status` enum('ACTIVE','SHOP_REVIEWING','SHOP_PACKAGE','LOGISTIC_DELIVERING','CUSTOMER_POSTPONE','CUSTOMER_CANCELED','CUSTOMER_CHANGED','SHOP_WAITING_CUSTOMER_APPROVAL','CUSTOMER_APPROVED','LOGISTIC_DELIVERED','SHOP_CLOSED','CUSTOMER_REOPENED','CUSTOMER_CLOSED','CUSTOMER_WAITNG_REFUND','SHOP_REFUNDED','REMOVED','NEW') COLLATE utf8_bin NOT NULL DEFAULT 'NEW',
  `Hash` varchar(100) COLLATE utf8_bin NOT NULL,
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `AccountID` (`AccountID`),
  KEY `Hash` (`Hash`),
  KEY `CustomerID` (`CustomerID`),
  KEY `AccountAddressesID` (`AccountAddressesID`),
  CONSTRAINT `shop_orders_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_orders_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_orders_ibfk_3` FOREIGN KEY (`AccountAddressesID`) REFERENCES `mpws_accountAddresses` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_orders`
--

LOCK TABLES `shop_orders` WRITE;
/*!40000 ALTER TABLE `shop_orders` DISABLE KEYS */;
INSERT INTO `shop_orders` VALUES (19,1,79,26,'company_gunsel','test','test','SHOP_CLOSED','feceb946892d6553534402fe85d34b0f','2014-03-01 14:38:46','2014-04-08 17:53:49'),(20,1,79,26,'company_novaposhta','tset','setset','SHOP_CLOSED','4a45f03d9c345814c22c3ba14977040f','2014-03-01 14:45:03','2014-04-19 00:52:02'),(21,1,79,26,'self','','','SHOP_CLOSED','bf62eae73bb93385458205350b41f5c8','2014-03-01 14:45:15','2014-04-13 03:54:55'),(22,1,79,26,'self','12','call','SHOP_CLOSED','539c1281990325f8870ee236920231ca','2014-03-04 02:43:40','2014-04-13 03:54:57'),(23,1,79,27,'self','12','call','SHOP_CLOSED','594b40d6834fe6b7c1988e022f0e5833','2014-03-04 02:43:46','2014-04-13 15:57:44'),(24,1,79,28,'self','12','call','SHOP_CLOSED','3bcff429fdb62932c9ff7636461b74e7','2014-03-04 02:46:43','2014-04-13 03:54:53'),(25,1,79,22,'self','12','444','SHOP_CLOSED','91bac37fd7056ede8da053fae4164d71','2014-03-04 02:52:48','2014-04-13 15:57:46'),(26,1,79,22,'self','12','444','SHOP_CLOSED','91a92b3864b2053b89d9214aa56f5f0d','2014-03-04 02:53:27','2014-04-13 15:57:43'),(27,1,79,24,'','','dedededede','SHOP_CLOSED','1206292bb1b863c76096e2e37d73232b','2014-03-04 03:02:31','2014-04-13 15:57:40'),(28,1,79,29,'company_novaposhta','434','testsetsetse','SHOP_CLOSED','cc9ee90f5fa3df9a1c2fa14f563f2483','2014-03-05 22:52:14','2014-03-05 22:52:14'),(29,1,82,32,'','','','SHOP_CLOSED','03c7a045da0ae470685c544780492c1c','2014-04-13 17:03:11','2014-04-19 00:49:20'),(30,1,83,33,'','','','SHOP_CLOSED','9df174a33ba3d3a1619c6b695e9ca281','2014-04-13 17:03:15','2014-04-13 17:11:50'),(31,1,84,34,'','','','SHOP_CLOSED','a8e0f7d81543ead5b12e5ffeb275bc48','2014-04-13 17:03:22','2014-04-22 11:09:13'),(32,1,85,35,'','','','LOGISTIC_DELIVERING','55fc5935ee3ce60518721cbc8e0f4d29','2014-04-13 17:06:02','2014-04-22 11:09:10'),(33,1,86,36,'','','','ACTIVE','73a335d5d536deed7a75e9d636170703','2014-04-13 17:09:31','2014-04-22 11:09:06'),(34,1,87,37,'','','','SHOP_CLOSED','3585cf7e98ea9a09659b1bf9d8c526fa','2014-04-13 17:10:51','2014-04-22 11:22:06'),(35,1,88,38,'','','','SHOP_CLOSED','5aa1b216b1e49f6cda12c0b6d4650f15','2014-04-19 21:19:49','2014-04-22 11:22:03'),(36,1,89,39,'','','','SHOP_CLOSED','4b8fa00674f3edbdfe490644c63a51d1','2014-04-19 21:22:04','2014-04-22 11:22:01'),(37,1,90,40,'company_gunsel','12','ffff','ACTIVE','af3fdec761065fe66fbec0ace3af59cf','2014-04-19 21:38:08','2014-05-10 20:33:48'),(38,1,90,40,'company_gunsel','13','ffff','NEW','af3fdec761065fe66fbec0ace3af59cf','2014-04-22 21:38:08','2014-05-10 20:39:30');
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
  `Description` text COLLATE utf8_bin NOT NULL,
  `HomePage` varchar(200) COLLATE utf8_bin NOT NULL,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_origins_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_origins`
--

LOCK TABLES `shop_origins` WRITE;
/*!40000 ALTER TABLE `shop_origins` DISABLE KEYS */;
INSERT INTO `shop_origins` VALUES (1,1,'','SONY','SONY','http://www.sony.com','ACTIVE','2013-08-27 02:26:41','2013-08-27 02:26:41'),(2,1,'','DELL','DELL','http://www.sony.com','ACTIVE','2013-08-27 02:26:41','2013-08-27 02:26:41'),(3,1,'hp','HP','HP','http://www.sony.com','ACTIVE','2013-08-27 02:26:41','2014-05-18 23:10:59'),(4,1,'samsung','Samsung','Samsung','http://www.sony.com','ACTIVE','2013-08-27 02:26:41','2014-05-22 22:02:21'),(5,1,'lg','LG','LG','http://www.sony.com','ACTIVE','2013-08-27 02:26:41','2014-05-22 22:02:16'),(6,1,'toshibagfdg','Toshibagfdg','Toshiba','http://www.sony.com','ACTIVE','2013-08-27 02:26:41','2014-05-22 03:55:59'),(7,1,'sharp','SHARP','SHARP','http://www.sony.com','ACTIVE','2013-08-27 02:26:41','2014-05-19 10:19:54'),(8,1,'','Apple','Apple','http://www.sony.com','ACTIVE','2013-08-27 02:26:41','2013-08-27 02:26:41'),(9,1,'dex','DEX','DEX','www.dex.com','ACTIVE','2014-05-18 23:19:01','2014-05-19 09:13:02'),(10,1,'microsoft','Microsoft','Microsoft','www.microsoft.com','ACTIVE','2014-05-18 23:28:13','2014-05-18 23:28:23'),(11,1,'','WEST','WEST','west.com','ACTIVE','2014-05-22 03:23:18','2014-05-22 03:23:18'),(12,1,'bosch','BOSCH','BOSCH','BOSCH.com','ACTIVE','2014-05-22 03:24:44','2014-05-22 03:24:44');
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
  `Attribute` enum('IMAGE','LABEL','OTHER','ISBN','MANUFACTURER','EXPIRE','TAGS') COLLATE utf8_bin NOT NULL,
  `Value` text COLLATE utf8_bin,
  `Status` enum('ACTIVE','REMOVED') COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_productAttributes_ibfk_3` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_productAttributes_ibfk_4` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productAttributes`
--

LOCK TABLES `shop_productAttributes` WRITE;
/*!40000 ALTER TABLE `shop_productAttributes` DISABLE KEYS */;
INSERT INTO `shop_productAttributes` VALUES (1,1,4,'LABEL','test','ACTIVE'),(2,1,4,'TAGS','wash device','ACTIVE'),(3,1,5,'TAGS','light bulb','ACTIVE'),(4,1,5,'LABEL','smth elese','ACTIVE'),(5,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/03-EKOCYCLE-Beats-headphones.jpg','ACTIVE'),(6,1,5,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/3dnWhDVRASLCMuQg.jpg','ACTIVE'),(7,1,6,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/302835-apple-iphone-5-sprint.jpg','ACTIVE'),(8,1,7,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/1362020208.jpg','ACTIVE'),(9,1,3,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(10,1,9,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/1362020208.jpg','ACTIVE'),(11,1,10,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/1362020208.jpg','ACTIVE'),(12,1,11,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/1362020208.jpg','ACTIVE'),(13,1,12,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/dryer2.jpg','ACTIVE'),(14,1,6,'LABEL','smth elese','ACTIVE'),(15,1,7,'LABEL','smth elese','ACTIVE'),(16,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/episkevi-laptop.jpg','REMOVED'),(17,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(18,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(19,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(20,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(21,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(22,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(23,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/lenovo-c540-all-in-one-desktop-pc-100021362-large.jpg','ACTIVE');
/*!40000 ALTER TABLE `shop_productAttributes` ENABLE KEYS */;
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
  CONSTRAINT `shop_productPrices_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productPrices`
--

LOCK TABLES `shop_productPrices` WRITE;
/*!40000 ALTER TABLE `shop_productPrices` DISABLE KEYS */;
INSERT INTO `shop_productPrices` VALUES (1,1,4,8.25,'2013-10-01 00:00:00'),(2,1,4,11.25,'2013-10-02 00:00:00'),(3,1,4,5.50,'2013-10-03 00:00:00'),(4,1,4,2.45,'2013-10-04 00:00:00'),(5,1,4,5.45,'2013-10-05 00:00:00'),(6,1,4,9.45,'2013-10-06 00:00:00'),(7,1,4,10.95,'2013-10-07 00:00:00'),(8,1,4,14.25,'2013-10-08 00:00:00'),(9,1,4,13.25,'2013-10-09 00:00:00'),(10,1,3,12.25,'2013-10-10 00:00:00'),(11,1,4,10.25,'2013-10-11 00:00:00'),(12,1,3,1.25,'2013-10-12 00:00:00'),(13,1,3,19.25,'2013-10-13 00:00:00'),(14,1,4,7.00,'2013-10-14 00:00:00'),(15,1,4,4.00,'2013-10-15 00:00:00'),(16,1,4,2.00,'2013-10-16 00:00:00'),(17,1,4,1.50,'2013-10-17 00:00:00'),(18,1,4,11.50,'2013-10-18 00:00:00');
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
  `Specifications` text COLLATE utf8_bin,
  `Model` text COLLATE utf8_bin,
  `SKU` text COLLATE utf8_bin,
  `Price` decimal(10,2) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='shop products';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_products`
--

LOCK TABLES `shop_products` WRITE;
/*!40000 ALTER TABLE `shop_products` DISABLE KEYS */;
INSERT INTO `shop_products` VALUES (3,1,1,1,'TES 1','tes1','test test 33','test test 33','test test 33','test test 33',213.00,'ACTIVE','2014-06-09 00:00:00','2013-09-30 12:21:56'),(4,1,1,5,'LCD S32DV','lcds32dv','LCD S32DV Description','','S32DV','S32DV11111',100.00,'DISCOUNT','2013-08-27 02:28:56','2013-08-27 02:28:56'),(5,1,1,2,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',17.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(6,1,1,1,'I AM HIDDEN PRODUCT','test2','test test','test test','test test','test test',36.00,'ARCHIVED','0000-00-00 00:00:00','2013-09-30 12:21:56'),(7,1,4,1,'Ложки','logku','Опис тут','','L100','ALLL1200100',46.25,'ACTIVE','2013-11-14 02:28:56','2013-08-27 02:28:56'),(8,1,16,7,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',56.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(9,1,15,1,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',71.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(10,1,13,8,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',171.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(11,1,23,2,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',37.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(12,1,3,3,'AAA S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',17.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(13,1,1,1,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',355.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(14,1,27,3,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',68.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(15,1,1,3,'CCC S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',85.00,'DISCOUNT','2013-08-27 02:28:56','2013-08-27 02:28:56'),(16,1,1,1,'EEE S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',554.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(17,1,15,6,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',7.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(18,1,1,1,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',65.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(19,1,16,6,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',7.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(20,1,1,1,'BBB S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',55.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(21,1,14,8,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',45.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(22,1,14,1,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',65.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(23,1,14,1,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',83.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(24,1,1,7,'GGG S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',7.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(25,1,23,1,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',7.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(26,1,21,8,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',7.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(27,1,1,1,'LCD S48DV','lcds48dv','LCD S48DV Description','','S48DV','S48DV222222',7.00,'ACTIVE','2013-08-27 02:28:56','2013-08-27 02:28:56'),(28,1,7,2,'','fsdfsdfs','','','',NULL,0.00,'ACTIVE','2014-04-20 00:00:00','2014-04-20 00:00:00'),(29,1,7,2,'','fsdfsdfs','','','',NULL,0.00,'ACTIVE','2014-04-20 00:00:00','2014-04-20 00:00:00');
/*!40000 ALTER TABLE `shop_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_relations`
--

DROP TABLE IF EXISTS `shop_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_relations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductA_ID` int(11) NOT NULL,
  `ProductB_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
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
-- Table structure for table `shop_specCategoryGroups`
--

DROP TABLE IF EXISTS `shop_specCategoryGroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_specCategoryGroups` (
  `CategoryID` int(11) NOT NULL,
  `SpecFieldID` int(11) NOT NULL,
  KEY `CategoryID` (`CategoryID`,`SpecFieldID`),
  KEY `SpecFieldID` (`SpecFieldID`),
  CONSTRAINT `shop_specCategoryGroups_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `shop_categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_specCategoryGroups_ibfk_2` FOREIGN KEY (`SpecFieldID`) REFERENCES `shop_specFields` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_specCategoryGroups`
--

LOCK TABLES `shop_specCategoryGroups` WRITE;
/*!40000 ALTER TABLE `shop_specCategoryGroups` DISABLE KEYS */;
INSERT INTO `shop_specCategoryGroups` VALUES (1,1),(1,2),(1,3),(1,4);
/*!40000 ALTER TABLE `shop_specCategoryGroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_specFields`
--

DROP TABLE IF EXISTS `shop_specFields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_specFields` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `FieldName` varchar(200) COLLATE utf8_bin NOT NULL,
  `DefaultValue` tinyint(1) DEFAULT '0',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Field` (`FieldName`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_specFields_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_specFields`
--

LOCK TABLES `shop_specFields` WRITE;
/*!40000 ALTER TABLE `shop_specFields` DISABLE KEYS */;
INSERT INTO `shop_specFields` VALUES (1,1,'LED',0,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(2,1,'16:9',0,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(3,1,'UltraFlat',0,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(4,1,'HD Support',0,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(5,1,'Wi-Fi',0,'2014-06-11 00:00:00','2014-06-11 00:00:00');
/*!40000 ALTER TABLE `shop_specFields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_specProductValues`
--

DROP TABLE IF EXISTS `shop_specProductValues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_specProductValues` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `SpecFieldID` int(11) NOT NULL,
  `Value` tinyint(1) NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `CustomerID` (`CustomerID`,`ProductID`,`SpecFieldID`),
  KEY `ProductID` (`ProductID`),
  KEY `SpecFieldID` (`SpecFieldID`),
  CONSTRAINT `shop_specProductValues_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`),
  CONSTRAINT `shop_specProductValues_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`),
  CONSTRAINT `shop_specProductValues_ibfk_3` FOREIGN KEY (`SpecFieldID`) REFERENCES `shop_specFields` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_specProductValues`
--

LOCK TABLES `shop_specProductValues` WRITE;
/*!40000 ALTER TABLE `shop_specProductValues` DISABLE KEYS */;
INSERT INTO `shop_specProductValues` VALUES (1,1,3,1,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(2,1,4,2,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(3,1,4,3,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(4,1,4,2,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(5,1,6,2,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(6,1,7,2,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(7,1,7,3,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(8,1,8,3,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(9,1,10,1,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(10,1,16,3,1,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(11,1,17,4,1,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(12,1,18,4,1,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(13,1,19,4,1,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(14,1,20,4,1,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(15,1,21,4,1,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(16,1,27,3,1,'2014-06-21 00:00:00','2014-06-21 00:00:00');
/*!40000 ALTER TABLE `shop_specProductValues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'mpws_light'
--
/*!50003 DROP PROCEDURE IF EXISTS `getAllShopCategoryBrands` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getAllShopCategorySubCategories` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getCategorySpecs` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getCategorySpecs`(IN `CategoryID` INT)
    READS SQL DATA
SELECT shop_specFields.ID, shop_specFields.FieldName as `Specs`
FROM  `shop_specCategoryGroups` LEFT JOIN shop_specFields ON shop_specCategoryGroups.SpecFieldID = shop_specFields.ID
WHERE shop_specCategoryGroups.CategoryID = CategoryID GROUP BY shop_specCategoryGroups.SpecFieldID ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getFieldOptions` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getFieldOptions`(IN `_tbl` VARCHAR(30), IN `_fld` VARCHAR(30))
BEGIN
	set @tbl = _tbl;
	set @fld = _fld;
	set @s = CONCAT('SHOW FIELDS FROM ', @tbl, ' WHERE FIELD = "', @fld, '"');
    PREPARE sqlstmt_state FROM @s;
    EXECUTE sqlstmt_state;
    DEALLOCATE PREPARE sqlstmt_state;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getProductSpecs` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getProductSpecs`(IN `ProductID` INT)
    READS SQL DATA
SELECT shop_specProductValues.Value, shop_specFields.FieldName FROM `shop_specProductValues` LEFT JOIN shop_specFields ON shop_specProductValues.SpecFieldID = shop_specFields.ID
WHERE shop_specProductValues.ProductID = ProductID ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getShopCategoryLocation` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
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
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getShopCategoryPriceEdges` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryPriceEdges`(IN catid INT)
BEGIN
SELECT MAX( p.Price ) AS 'PriceMax' , MIN( p.price ) AS 'PriceMin' FROM shop_products AS  `p` WHERE p.CategoryID = catid;
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

-- Dump completed on 2014-07-06  1:18:45

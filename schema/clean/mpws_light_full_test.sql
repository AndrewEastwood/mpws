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
  `Result` mediumtext COLLATE utf8_bin,
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_tasks`
--

LOCK TABLES `mpws_tasks` WRITE;
/*!40000 ALTER TABLE `mpws_tasks` DISABLE KEYS */;
INSERT INTO `mpws_tasks` VALUES (3,'9fec57e0a7f62eb2e359da9919606860',1,'shop','importProductFeed','','import_20141119_020927','','Array\n(\n    [total] => 0\n    [productsAdded] => 300\n    [productsUpdated] => 0\n    [productsInvalid] => 0\n    [success] => 1\n    [results] => Array\n        (\n            [0] => processing product Аксесуари для духових шаф\n            [1] => [INFO] new product created\n            [2] => [SUCCESS] Аксесуари для духових шаф\n            [3] => processing product Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [4] => [INFO] new product created\n            [5] => [SUCCESS] Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [6] => processing product Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [7] => [INFO] new product created\n            [8] => [SUCCESS] Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [9] => processing product Двосторонній дисковий ніж-тертка (середня)\n            [10] => [INFO] new product created\n            [11] => [SUCCESS] Двосторонній дисковий ніж-тертка (середня)\n            [12] => processing product Дискова тертка для грубого натирання\n            [13] => [INFO] new product created\n            [14] => [SUCCESS] Дискова тертка для грубого натирання\n            [15] => processing product Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [16] => [INFO] new product created\n            [17] => [SUCCESS] Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [18] => processing product Млинок для зернових\n            [19] => [INFO] new product created\n            [20] => [SUCCESS] Млинок для зернових\n            [21] => processing product Цитрус-прес\n            [22] => [INFO] new product created\n            [23] => [SUCCESS] Цитрус-прес\n            [24] => processing product Приладдя для нарізання продуктів кубиками\n            [25] => [INFO] new product created\n            [26] => [SUCCESS] Приладдя для нарізання продуктів кубиками\n            [27] => processing product Млинок для зернових\n            [28] => [INFO] new product created\n            [29] => [SUCCESS] Млинок для зернових\n            [30] => processing product Цитрус-прес\n            [31] => [INFO] new product created\n            [32] => [SUCCESS] Цитрус-прес\n            [33] => processing product Дискова тертка для грубого натирання\n            [34] => [INFO] new product created\n            [35] => [SUCCESS] Дискова тертка для грубого натирання\n            [36] => processing product Дискова тертка, груба, з нержавіючої сталі\n            [37] => [INFO] new product created\n            [38] => [SUCCESS] Дискова тертка, груба, з нержавіючої сталі\n            [39] => processing product Набір формувальних дисків\n            [40] => [INFO] new product created\n            [41] => [SUCCESS] Набір формувальних дисків\n            [42] => processing product Дисковий ніж з нержавіючої сталі для нарізання картоплі фрі\n            [43] => [INFO] new product created\n            [44] => [SUCCESS] Дисковий ніж з нержавіючої сталі для нарізання картоплі фрі\n            [45] => processing product Аксесуар для мясорубки\n            [46] => [INFO] new product created\n            [47] => [SUCCESS] Аксесуар для мясорубки\n            [48] => processing product Аксесуар для мясорубки\n            [49] => [INFO] new product created\n            [50] => [SUCCESS] Аксесуар для мясорубки\n            [51] => processing product Аксесуар для мясорубки\n            [52] => [INFO] new product created\n            [53] => [SUCCESS] Аксесуар для мясорубки\n            [54] => processing product Аксесуар для мясорубки\n            [55] => [INFO] new product created\n            [56] => [SUCCESS] Аксесуар для мясорубки\n            [57] => processing product Аксесуар для мясорубки\n            [58] => [INFO] new product created\n            [59] => [SUCCESS] Аксесуар для мясорубки\n            [60] => processing product Аксесуар для мясорубки\n            [61] => [INFO] new product created\n            [62] => [SUCCESS] Аксесуар для мясорубки\n            [63] => processing product Аксесуар для мясорубки\n            [64] => [INFO] new product created\n            [65] => [SUCCESS] Аксесуар для мясорубки\n            [66] => processing product Аксесуар для мясорубки\n            [67] => [INFO] new product created\n            [68] => [SUCCESS] Аксесуар для мясорубки\n            [69] => processing product Аксесуар для мясорубки\n            [70] => [INFO] new product created\n            [71] => [SUCCESS] Аксесуар для мясорубки\n            [72] => processing product Аксесуар для мясорубки\n            [73] => [INFO] new product created\n            [74] => [SUCCESS] Аксесуар для мясорубки\n            [75] => processing product Аксесуар для мясорубки\n            [76] => [INFO] new product created\n            [77] => [SUCCESS] Аксесуар для мясорубки\n            [78] => processing product Аксесуар для мясорубки\n            [79] => [INFO] new product created\n            [80] => [SUCCESS] Аксесуар для мясорубки\n            [81] => processing product Аксесуар для пилососа\n            [82] => [INFO] new product created\n            [83] => [SUCCESS] Аксесуар для пилососа\n            [84] => processing product Аксесуар для пилососа\n            [85] => [INFO] new product created\n            [86] => [SUCCESS] Аксесуар для пилососа\n            [87] => processing product Аксесуар для пилососа\n            [88] => [INFO] new product created\n            [89] => [SUCCESS] Аксесуар для пилососа\n            [90] => processing product Блендер\n            [91] => [INFO] new product created\n            [92] => [SUCCESS] Блендер\n            [93] => processing product Блендер\n            [94] => [INFO] new product created\n            [95] => [SUCCESS] Блендер\n            [96] => processing product Блендер\n            [97] => [INFO] new product created\n            [98] => [SUCCESS] Блендер\n            [99] => processing product Блендер\n            [100] => [INFO] new product created\n            [101] => [SUCCESS] Блендер\n            [102] => processing product Блендер\n            [103] => [INFO] new product created\n            [104] => [SUCCESS] Блендер\n            [105] => processing product Блендер\n            [106] => [INFO] new product created\n            [107] => [SUCCESS] Блендер\n            [108] => processing product Блендер\n            [109] => [INFO] new product created\n            [110] => [SUCCESS] Блендер\n            [111] => processing product Блендер\n            [112] => [INFO] new product created\n            [113] => [SUCCESS] Блендер\n            [114] => processing product Блендер\n            [115] => [INFO] new product created\n            [116] => [SUCCESS] Блендер\n            [117] => processing product Блендер\n            [118] => [INFO] new product created\n            [119] => [SUCCESS] Блендер\n            [120] => processing product Блендер\n            [121] => [INFO] new product created\n            [122] => [SUCCESS] Блендер\n            [123] => processing product Блендер\n            [124] => [INFO] new product created\n            [125] => [SUCCESS] Блендер\n            [126] => processing product Блендер\n            [127] => [INFO] new product created\n            [128] => [SUCCESS] Блендер\n            [129] => processing product Блендер\n            [130] => [INFO] new product created\n            [131] => [SUCCESS] Блендер\n            [132] => processing product Блендер\n            [133] => [INFO] new product created\n            [134] => [SUCCESS] Блендер\n            [135] => processing product Блендер\n            [136] => [INFO] new product created\n            [137] => [SUCCESS] Блендер\n            [138] => processing product Блендер\n            [139] => [INFO] new product created\n            [140] => [SUCCESS] Блендер\n            [141] => processing product Блендер\n            [142] => [INFO] new product created\n            [143] => [SUCCESS] Блендер\n            [144] => processing product Блендер\n            [145] => [INFO] new product created\n            [146] => [SUCCESS] Блендер\n            [147] => processing product Блендер\n            [148] => [INFO] new product created\n            [149] => [SUCCESS] Блендер\n            [150] => processing product Блендер\n            [151] => [INFO] new product created\n            [152] => [SUCCESS] Блендер\n            [153] => processing product Блендер\n            [154] => [INFO] new product created\n            [155] => [SUCCESS] Блендер\n            [156] => processing product Блендер\n            [157] => [INFO] new product created\n            [158] => [SUCCESS] Блендер\n            [159] => processing product Блендер\n            [160] => [INFO] new product created\n            [161] => [SUCCESS] Блендер\n            [162] => processing product Блендер\n            [163] => [INFO] new product created\n            [164] => [SUCCESS] Блендер\n            [165] => processing product Блендер\n            [166] => [INFO] new product created\n            [167] => [SUCCESS] Блендер\n            [168] => processing product Бутербродниця\n            [169] => [INFO] new product created\n            [170] => [SUCCESS] Бутербродниця\n            [171] => processing product Бутербродниця\n            [172] => [INFO] new product created\n            [173] => [SUCCESS] Бутербродниця\n            [174] => processing product Варильна поверхня газова\n            [175] => [INFO] new product created\n            [176] => [SUCCESS] Варильна поверхня газова\n            [177] => processing product Варильна поверхня газова\n            [178] => [INFO] new product created\n            [179] => [SUCCESS] Варильна поверхня газова\n            [180] => processing product Варильна поверхня газова\n            [181] => [INFO] new product created\n            [182] => [SUCCESS] Варильна поверхня газова\n            [183] => processing product Варильна поверхня газова\n            [184] => [INFO] new product created\n            [185] => [SUCCESS] Варильна поверхня газова\n            [186] => processing product Варильна поверхня газова\n            [187] => [INFO] new product created\n            [188] => [SUCCESS] Варильна поверхня газова\n            [189] => processing product Варильна поверхня газова\n            [190] => [INFO] new product created\n            [191] => [SUCCESS] Варильна поверхня газова\n            [192] => processing product Варильна поверхня газова\n            [193] => [INFO] new product created\n            [194] => [SUCCESS] Варильна поверхня газова\n            [195] => processing product Варильна поверхня газова\n            [196] => [INFO] new product created\n            [197] => [SUCCESS] Варильна поверхня газова\n            [198] => processing product Варильна поверхня газова\n            [199] => [INFO] new product created\n            [200] => [SUCCESS] Варильна поверхня газова\n            [201] => processing product Варильна поверхня газова\n            [202] => [INFO] new product created\n            [203] => [SUCCESS] Варильна поверхня газова\n            [204] => processing product Варильна поверхня газова\n            [205] => [INFO] new product created\n            [206] => [SUCCESS] Варильна поверхня газова\n            [207] => processing product Варильна поверхня газова\n            [208] => [INFO] new product created\n            [209] => [SUCCESS] Варильна поверхня газова\n            [210] => processing product Варильна поверхня газова\n            [211] => [INFO] new product created\n            [212] => [SUCCESS] Варильна поверхня газова\n            [213] => processing product Варильна поверхня газова\n            [214] => [INFO] new product created\n            [215] => [SUCCESS] Варильна поверхня газова\n            [216] => processing product Варильна поверхня газова\n            [217] => [INFO] new product created\n            [218] => [SUCCESS] Варильна поверхня газова\n            [219] => processing product Варильна поверхня газова\n            [220] => [INFO] new product created\n            [221] => [SUCCESS] Варильна поверхня газова\n            [222] => processing product Варильна поверхня газова\n            [223] => [INFO] new product created\n            [224] => [SUCCESS] Варильна поверхня газова\n            [225] => processing product Варильна поверхня газова\n            [226] => [INFO] new product created\n            [227] => [SUCCESS] Варильна поверхня газова\n            [228] => processing product Варильна поверхня газова\n            [229] => [INFO] new product created\n            [230] => [SUCCESS] Варильна поверхня газова\n            [231] => processing product Варильна поверхня газова\n            [232] => [INFO] new product created\n            [233] => [SUCCESS] Варильна поверхня газова\n            [234] => processing product Варильна поверхня газова\n            [235] => [INFO] new product created\n            [236] => [SUCCESS] Варильна поверхня газова\n            [237] => processing product Варильна поверхня газова\n            [238] => [INFO] new product created\n            [239] => [SUCCESS] Варильна поверхня газова\n            [240] => processing product Варильна поверхня газова\n            [241] => [INFO] new product created\n            [242] => [SUCCESS] Варильна поверхня газова\n            [243] => processing product Варильна поверхня газова\n            [244] => [INFO] new product created\n            [245] => [SUCCESS] Варильна поверхня газова\n            [246] => processing product Варильна поверхня електрична\n            [247] => [INFO] new product created\n            [248] => [SUCCESS] Варильна поверхня електрична\n            [249] => processing product Варильна поверхня електрична\n            [250] => [INFO] new product created\n            [251] => [SUCCESS] Варильна поверхня електрична\n            [252] => processing product Варильна поверхня електрична\n            [253] => [INFO] new product created\n            [254] => [SUCCESS] Варильна поверхня електрична\n            [255] => processing product Варильна поверхня електрична\n            [256] => [INFO] new product created\n            [257] => [SUCCESS] Варильна поверхня електрична\n            [258] => processing product Варильна поверхня електрична\n            [259] => [INFO] new product created\n            [260] => [SUCCESS] Варильна поверхня електрична\n            [261] => processing product Варильна поверхня електрична\n            [262] => [INFO] new product created\n            [263] => [SUCCESS] Варильна поверхня електрична\n            [264] => processing product Варильна поверхня електрична\n            [265] => [INFO] new product created\n            [266] => [SUCCESS] Варильна поверхня електрична\n            [267] => processing product Варильна поверхня електрична\n            [268] => [INFO] new product created\n            [269] => [SUCCESS] Варильна поверхня електрична\n            [270] => processing product Варильна поверхня електрична\n            [271] => [INFO] new product created\n            [272] => [SUCCESS] Варильна поверхня електрична\n            [273] => processing product Варильна поверхня електрична\n            [274] => [INFO] new product created\n            [275] => [SUCCESS] Варильна поверхня електрична\n            [276] => processing product Варильна поверхня електрична\n            [277] => [INFO] new product created\n            [278] => [SUCCESS] Варильна поверхня електрична\n            [279] => processing product Випрямляч для волосся\n            [280] => [INFO] new product created\n            [281] => [SUCCESS] Випрямляч для волосся\n            [282] => processing product Випрямляч для волосся\n            [283] => [INFO] new product created\n            [284] => [SUCCESS] Випрямляч для волосся\n            [285] => processing product Випрямляч для волосся\n            [286] => [INFO] new product created\n            [287] => [SUCCESS] Випрямляч для волосся\n            [288] => processing product Випрямляч для волосся\n            [289] => [INFO] new product created\n            [290] => [SUCCESS] Випрямляч для волосся\n            [291] => processing product Генератор\n            [292] => [INFO] new product created\n            [293] => [SUCCESS] Генератор\n            [294] => processing product Гриль\n            [295] => [INFO] new product created\n            [296] => [SUCCESS] Гриль\n            [297] => processing product Гриль\n            [298] => [INFO] new product created\n            [299] => [SUCCESS] Гриль\n            [300] => processing product Гриль\n            [301] => [INFO] new product created\n            [302] => [SUCCESS] Гриль\n            [303] => processing product Гриль\n            [304] => [INFO] new product created\n            [305] => [SUCCESS] Гриль\n            [306] => processing product Гриль\n            [307] => [INFO] new product created\n            [308] => [SUCCESS] Гриль\n            [309] => processing product Гриль\n            [310] => [INFO] new product created\n            [311] => [SUCCESS] Гриль\n            [312] => processing product Гриль\n            [313] => [INFO] new product created\n            [314] => [SUCCESS] Гриль\n            [315] => processing product Гриль\n            [316] => [INFO] new product created\n            [317] => [SUCCESS] Гриль\n            [318] => processing product Гриль\n            [319] => [INFO] new product created\n            [320] => [SUCCESS] Гриль\n            [321] => processing product Дошка для прасування\n            [322] => [INFO] new product created\n            [323] => [SUCCESS] Дошка для прасування\n            [324] => processing product Духова шафа електрична\n            [325] => [INFO] new product created\n            [326] => [SUCCESS] Духова шафа електрична\n            [327] => processing product Духова шафа електрична\n            [328] => [INFO] new product created\n            [329] => [SUCCESS] Духова шафа електрична\n            [330] => processing product Духова шафа електрична\n            [331] => [INFO] new product created\n            [332] => [SUCCESS] Духова шафа електрична\n            [333] => processing product Духова шафа електрична\n            [334] => [INFO] new product created\n            [335] => [SUCCESS] Духова шафа електрична\n            [336] => processing product Духова шафа електрична\n            [337] => [INFO] new product created\n            [338] => [SUCCESS] Духова шафа електрична\n            [339] => processing product Духова шафа електрична\n            [340] => [INFO] new product created\n            [341] => [SUCCESS] Духова шафа електрична\n            [342] => processing product Духова шафа електрична\n            [343] => [INFO] new product created\n            [344] => [SUCCESS] Духова шафа електрична\n            [345] => processing product Духова шафа електрична\n            [346] => [INFO] new product created\n            [347] => [SUCCESS] Духова шафа електрична\n            [348] => processing product Духова шафа електрична\n            [349] => [INFO] new product created\n            [350] => [SUCCESS] Духова шафа електрична\n            [351] => processing product Духова шафа електрична\n            [352] => [INFO] new product created\n            [353] => [SUCCESS] Духова шафа електрична\n            [354] => processing product Духова шафа електрична\n            [355] => [INFO] new product created\n            [356] => [SUCCESS] Духова шафа електрична\n            [357] => processing product Духова шафа електрична\n            [358] => [INFO] new product created\n            [359] => [SUCCESS] Духова шафа електрична\n            [360] => processing product Духова шафа електрична\n            [361] => [INFO] new product created\n            [362] => [SUCCESS] Духова шафа електрична\n            [363] => processing product Духова шафа електрична\n            [364] => [INFO] new product created\n            [365] => [SUCCESS] Духова шафа електрична\n            [366] => processing product Духова шафа електрична\n            [367] => [INFO] new product created\n            [368] => [SUCCESS] Духова шафа електрична\n            [369] => processing product Духова шафа електрична\n            [370] => [INFO] new product created\n            [371] => [SUCCESS] Духова шафа електрична\n            [372] => processing product Духова шафа електрична\n            [373] => [INFO] new product created\n            [374] => [SUCCESS] Духова шафа електрична\n            [375] => processing product Духова шафа електрична\n            [376] => [INFO] new product created\n            [377] => [SUCCESS] Духова шафа електрична\n            [378] => processing product Духова шафа електрична\n            [379] => [INFO] new product created\n            [380] => [SUCCESS] Духова шафа електрична\n            [381] => processing product Духова шафа електрична\n            [382] => [INFO] new product created\n            [383] => [SUCCESS] Духова шафа електрична\n            [384] => processing product Духова шафа електрична\n            [385] => [INFO] new product created\n            [386] => [SUCCESS] Духова шафа електрична\n            [387] => processing product Духова шафа електрична\n            [388] => [INFO] new product created\n            [389] => [SUCCESS] Духова шафа електрична\n            [390] => processing product Духова шафа електрична\n            [391] => [INFO] new product created\n            [392] => [SUCCESS] Духова шафа електрична\n            [393] => processing product Духова шафа електрична\n            [394] => [INFO] new product created\n            [395] => [SUCCESS] Духова шафа електрична\n            [396] => processing product Кава в зернах\n            [397] => [INFO] new product created\n            [398] => [SUCCESS] Кава в зернах\n            [399] => processing product Кава в зернах\n            [400] => [INFO] new product created\n            [401] => [SUCCESS] Кава в зернах\n            [402] => processing product Кава в зернах\n            [403] => [INFO] new product created\n            [404] => [SUCCESS] Кава в зернах\n            [405] => processing product Кава в зернах\n            [406] => [INFO] new product created\n            [407] => [SUCCESS] Кава в зернах\n            [408] => processing product Кавоварка\n            [409] => [INFO] new product created\n            [410] => [SUCCESS] Кавоварка\n            [411] => processing product Кавоварка\n            [412] => [INFO] new product created\n            [413] => [SUCCESS] Кавоварка\n            [414] => processing product Кавомолка\n            [415] => [INFO] new product created\n            [416] => [SUCCESS] Кавомолка\n            [417] => processing product Кавомолка\n            [418] => [INFO] new product created\n            [419] => [SUCCESS] Кавомолка\n            [420] => processing product Кухонний комбайн\n            [421] => [INFO] new product created\n            [422] => [SUCCESS] Кухонний комбайн\n            [423] => processing product Кухонний комбайн\n            [424] => [INFO] new product created\n            [425] => [SUCCESS] Кухонний комбайн\n            [426] => processing product Кухонний комбайн\n            [427] => [INFO] new product created\n            [428] => [SUCCESS] Кухонний комбайн\n            [429] => processing product Кухонний комбайн\n            [430] => [INFO] new product created\n            [431] => [SUCCESS] Кухонний комбайн\n            [432] => processing product Кухонний комбайн\n            [433] => [INFO] new product created\n            [434] => [SUCCESS] Кухонний комбайн\n            [435] => processing product Кухонний комбайн\n            [436] => [INFO] new product created\n            [437] => [SUCCESS] Кухонний комбайн\n            [438] => processing product Кухонний комбайн\n            [439] => [INFO] new product created\n            [440] => [SUCCESS] Кухонний комбайн\n            [441] => processing product Кухонний комбайн\n            [442] => [INFO] new product created\n            [443] => [SUCCESS] Кухонний комбайн\n            [444] => processing product Кухонний комбайн\n            [445] => [INFO] new product created\n            [446] => [SUCCESS] Кухонний комбайн\n            [447] => processing product Кухонний комбайн\n            [448] => [INFO] new product created\n            [449] => [SUCCESS] Кухонний комбайн\n            [450] => processing product Кухонний комбайн\n            [451] => [INFO] new product created\n            [452] => [SUCCESS] Кухонний комбайн\n            [453] => processing product Кухонний комбайн\n            [454] => [INFO] new product created\n            [455] => [SUCCESS] Кухонний комбайн\n            [456] => processing product Кухонний комбайн\n            [457] => [INFO] new product created\n            [458] => [SUCCESS] Кухонний комбайн\n            [459] => processing product Кухонний комбайн\n            [460] => [INFO] new product created\n            [461] => [SUCCESS] Кухонний комбайн\n            [462] => processing product Кухонний комбайн\n            [463] => [INFO] new product created\n            [464] => [SUCCESS] Кухонний комбайн\n            [465] => processing product Кухонний комбайн\n            [466] => [INFO] new product created\n            [467] => [SUCCESS] Кухонний комбайн\n            [468] => processing product Кухонний комбайн\n            [469] => [INFO] new product created\n            [470] => [SUCCESS] Кухонний комбайн\n            [471] => processing product Кухонний комбайн\n            [472] => [INFO] new product created\n            [473] => [SUCCESS] Кухонний комбайн\n            [474] => processing product Кухонний комбайн\n            [475] => [INFO] new product created\n            [476] => [SUCCESS] Кухонний комбайн\n            [477] => processing product Кухонний комбайн\n            [478] => [INFO] new product created\n            [479] => [SUCCESS] Кухонний комбайн\n            [480] => processing product Міксер\n            [481] => [INFO] new product created\n            [482] => [SUCCESS] Міксер\n            [483] => processing product Міксер\n            [484] => [INFO] new product created\n            [485] => [SUCCESS] Міксер\n            [486] => processing product Міксер\n            [487] => [INFO] new product created\n            [488] => [SUCCESS] Міксер\n            [489] => processing product Міксер\n            [490] => [INFO] new product created\n            [491] => [SUCCESS] Міксер\n            [492] => processing product Міксер\n            [493] => [INFO] new product created\n            [494] => [SUCCESS] Міксер\n            [495] => processing product Міксер\n            [496] => [INFO] new product created\n            [497] => [SUCCESS] Міксер\n            [498] => processing product Міксер\n            [499] => [INFO] new product created\n            [500] => [SUCCESS] Міксер\n            [501] => processing product Міксер\n            [502] => [INFO] new product created\n            [503] => [SUCCESS] Міксер\n            [504] => processing product Міксер\n            [505] => [INFO] new product created\n            [506] => [SUCCESS] Міксер\n            [507] => processing product Міксер\n            [508] => [INFO] new product created\n            [509] => [SUCCESS] Міксер\n            [510] => processing product Міксер\n            [511] => [INFO] new product created\n            [512] => [SUCCESS] Міксер\n            [513] => processing product Міксер\n            [514] => [INFO] new product created\n            [515] => [SUCCESS] Міксер\n            [516] => processing product Міксер\n            [517] => [INFO] new product created\n            [518] => [SUCCESS] Міксер\n            [519] => processing product Міксер\n            [520] => [INFO] new product created\n            [521] => [SUCCESS] Міксер\n            [522] => processing product Мясорубка\n            [523] => [INFO] new product created\n            [524] => [SUCCESS] Мясорубка\n            [525] => processing product Мясорубка\n            [526] => [INFO] new product created\n            [527] => [SUCCESS] Мясорубка\n            [528] => processing product Мясорубка\n            [529] => [INFO] new product created\n            [530] => [SUCCESS] Мясорубка\n            [531] => processing product Мясорубка\n            [532] => [INFO] new product created\n            [533] => [SUCCESS] Мясорубка\n            [534] => processing product Мясорубка\n            [535] => [INFO] new product created\n            [536] => [SUCCESS] Мясорубка\n            [537] => processing product Мясорубка\n            [538] => [INFO] new product created\n            [539] => [SUCCESS] Мясорубка\n            [540] => processing product Мясорубка\n            [541] => [INFO] new product created\n            [542] => [SUCCESS] Мясорубка\n            [543] => processing product Мясорубка\n            [544] => [INFO] new product created\n            [545] => [SUCCESS] Мясорубка\n            [546] => processing product Мясорубка\n            [547] => [INFO] new product created\n            [548] => [SUCCESS] Мясорубка\n            [549] => processing product Мясорубка\n            [550] => [INFO] new product created\n            [551] => [SUCCESS] Мясорубка\n            [552] => processing product Мясорубка\n            [553] => [INFO] new product created\n            [554] => [SUCCESS] Мясорубка\n            [555] => processing product Мясорубка\n            [556] => [INFO] new product created\n            [557] => [SUCCESS] Мясорубка\n            [558] => processing product Мясорубка\n            [559] => [INFO] new product created\n            [560] => [SUCCESS] Мясорубка\n            [561] => processing product Пароварка\n            [562] => [INFO] new product created\n            [563] => [SUCCESS] Пароварка\n            [564] => processing product Пароварка\n            [565] => [INFO] new product created\n            [566] => [SUCCESS] Пароварка\n            [567] => processing product Пароварка\n            [568] => [INFO] new product created\n            [569] => [SUCCESS] Пароварка\n            [570] => processing product Пилосос\n            [571] => [INFO] new product created\n            [572] => [SUCCESS] Пилосос\n            [573] => processing product Пилосос\n            [574] => [INFO] new product created\n            [575] => [SUCCESS] Пилосос\n            [576] => processing product Пилосос\n            [577] => [INFO] new product created\n            [578] => [SUCCESS] Пилосос\n            [579] => processing product Пилосос\n            [580] => [INFO] new product created\n            [581] => [SUCCESS] Пилосос\n            [582] => processing product Пилосос\n            [583] => [INFO] new product created\n            [584] => [SUCCESS] Пилосос\n            [585] => processing product Пилосос миючий\n            [586] => [INFO] new product created\n            [587] => [SUCCESS] Пилосос миючий\n            [588] => processing product Посудомийка вбудована\n            [589] => [INFO] new product created\n            [590] => [SUCCESS] Посудомийка вбудована\n            [591] => processing product Посудомийка вбудована\n            [592] => [INFO] new product created\n            [593] => [SUCCESS] Посудомийка вбудована\n            [594] => processing product Посудомийка вбудована\n            [595] => [INFO] new product created\n            [596] => [SUCCESS] Посудомийка вбудована\n            [597] => processing product Пральна машина\n            [598] => [INFO] new product created\n            [599] => [SUCCESS] Пральна машина\n            [600] => processing product Пральна машина\n            [601] => [INFO] new product created\n            [602] => [SUCCESS] Пральна машина\n            [603] => processing product Пральна машина\n            [604] => [INFO] new product created\n            [605] => [SUCCESS] Пральна машина\n            [606] => processing product Пральна машина\n            [607] => [INFO] new product created\n            [608] => [SUCCESS] Пральна машина\n            [609] => processing product Пральна машина\n            [610] => [INFO] new product created\n            [611] => [SUCCESS] Пральна машина\n            [612] => processing product Пральна машина\n            [613] => [INFO] new product created\n            [614] => [SUCCESS] Пральна машина\n            [615] => processing product Пральна машина\n            [616] => [INFO] new product created\n            [617] => [SUCCESS] Пральна машина\n            [618] => processing product Праска\n            [619] => [INFO] new product created\n            [620] => [SUCCESS] Праска\n            [621] => processing product Праска\n            [622] => [INFO] new product created\n            [623] => [SUCCESS] Праска\n            [624] => processing product Праска\n            [625] => [INFO] new product created\n            [626] => [SUCCESS] Праска\n            [627] => processing product Праска\n            [628] => [INFO] new product created\n            [629] => [SUCCESS] Праска\n            [630] => processing product Праска\n            [631] => [INFO] new product created\n            [632] => [SUCCESS] Праска\n            [633] => processing product Праска\n            [634] => [INFO] new product created\n            [635] => [SUCCESS] Праска\n            [636] => processing product Праска\n            [637] => [INFO] new product created\n            [638] => [SUCCESS] Праска\n            [639] => processing product Праска\n            [640] => [INFO] new product created\n            [641] => [SUCCESS] Праска\n            [642] => processing product Праска\n            [643] => [INFO] new product created\n            [644] => [SUCCESS] Праска\n            [645] => processing product Праска\n            [646] => [INFO] new product created\n            [647] => [SUCCESS] Праска\n            [648] => processing product Праска\n            [649] => [INFO] new product created\n            [650] => [SUCCESS] Праска\n            [651] => processing product Праска\n            [652] => [INFO] new product created\n            [653] => [SUCCESS] Праска\n            [654] => processing product Праска\n            [655] => [INFO] new product created\n            [656] => [SUCCESS] Праска\n            [657] => processing product Праска\n            [658] => [INFO] new product created\n            [659] => [SUCCESS] Праска\n            [660] => processing product Праска\n            [661] => [INFO] new product created\n            [662] => [SUCCESS] Праска\n            [663] => processing product Праска\n            [664] => [INFO] new product created\n            [665] => [SUCCESS] Праска\n            [666] => processing product Праска\n            [667] => [INFO] new product created\n            [668] => [SUCCESS] Праска\n            [669] => processing product Праска\n            [670] => [INFO] new product created\n            [671] => [SUCCESS] Праска\n            [672] => processing product Праска\n            [673] => [INFO] new product created\n            [674] => [SUCCESS] Праска\n            [675] => processing product Праска\n            [676] => [INFO] new product created\n            [677] => [SUCCESS] Праска\n            [678] => processing product Праска\n            [679] => [INFO] new product created\n            [680] => [SUCCESS] Праска\n            [681] => processing product Праска\n            [682] => [INFO] new product created\n            [683] => [SUCCESS] Праска\n            [684] => processing product Праска\n            [685] => [INFO] new product created\n            [686] => [SUCCESS] Праска\n            [687] => processing product Скиборізка\n            [688] => [INFO] new product created\n            [689] => [SUCCESS] Скиборізка\n            [690] => processing product Скиборізка\n            [691] => [INFO] new product created\n            [692] => [SUCCESS] Скиборізка\n            [693] => processing product Скиборізка\n            [694] => [INFO] new product created\n            [695] => [SUCCESS] Скиборізка\n            [696] => processing product Скиборізка\n            [697] => [INFO] new product created\n            [698] => [SUCCESS] Скиборізка\n            [699] => processing product Скиборізка\n            [700] => [INFO] new product created\n            [701] => [SUCCESS] Скиборізка\n            [702] => processing product Скиборізка\n            [703] => [INFO] new product created\n            [704] => [SUCCESS] Скиборізка\n            [705] => processing product Скиборізка\n            [706] => [INFO] new product created\n            [707] => [SUCCESS] Скиборізка\n            [708] => processing product Скиборізка\n            [709] => [INFO] new product created\n            [710] => [SUCCESS] Скиборізка\n            [711] => processing product Скиборізка\n            [712] => [INFO] new product created\n            [713] => [SUCCESS] Скиборізка\n            [714] => processing product Скиборізка\n            [715] => [INFO] new product created\n            [716] => [SUCCESS] Скиборізка\n            [717] => processing product Скиборізка\n            [718] => [INFO] new product created\n            [719] => [SUCCESS] Скиборізка\n            [720] => processing product Скиборізка\n            [721] => [INFO] new product created\n            [722] => [SUCCESS] Скиборізка\n            [723] => processing product Соковитискач\n            [724] => [INFO] new product created\n            [725] => [SUCCESS] Соковитискач\n            [726] => processing product Соковитискач\n            [727] => [INFO] new product created\n            [728] => [SUCCESS] Соковитискач\n            [729] => processing product Соковитискач\n            [730] => [INFO] new product created\n            [731] => [SUCCESS] Соковитискач\n            [732] => processing product Стайлер\n            [733] => [INFO] new product created\n            [734] => [SUCCESS] Стайлер\n            [735] => processing product Сушка для овочів і фруктів\n            [736] => [INFO] new product created\n            [737] => [SUCCESS] Сушка для овочів і фруктів\n            [738] => processing product Сушка для овочів і фруктів\n            [739] => [INFO] new product created\n            [740] => [SUCCESS] Сушка для овочів і фруктів\n            [741] => processing product Таблетки для кавоварок\n            [742] => [INFO] new product created\n            [743] => [SUCCESS] Таблетки для кавоварок\n            [744] => processing product Таблетки для кавоварок\n            [745] => [INFO] new product created\n            [746] => [SUCCESS] Таблетки для кавоварок\n            [747] => processing product Таблетки для посудомийних машин\n            [748] => [INFO] new product created\n            [749] => [SUCCESS] Таблетки для посудомийних машин\n            [750] => processing product Тостер\n            [751] => [INFO] new product created\n            [752] => [SUCCESS] Тостер\n            [753] => processing product Тостер\n            [754] => [INFO] new product created\n            [755] => [SUCCESS] Тостер\n            [756] => processing product Тостер\n            [757] => [INFO] new product created\n            [758] => [SUCCESS] Тостер\n            [759] => processing product Тостер\n            [760] => [INFO] new product created\n            [761] => [SUCCESS] Тостер\n            [762] => processing product Тостер\n            [763] => [INFO] new product created\n            [764] => [SUCCESS] Тостер\n            [765] => processing product Тостер\n            [766] => [INFO] new product created\n            [767] => [SUCCESS] Тостер\n            [768] => processing product Тостер\n            [769] => [INFO] new product created\n            [770] => [SUCCESS] Тостер\n            [771] => processing product Тостер\n            [772] => [INFO] new product created\n            [773] => [SUCCESS] Тостер\n            [774] => processing product Тостер\n            [775] => [INFO] new product created\n            [776] => [SUCCESS] Тостер\n            [777] => processing product Тостер\n            [778] => [INFO] new product created\n            [779] => [SUCCESS] Тостер\n            [780] => processing product Тостер\n            [781] => [INFO] new product created\n            [782] => [SUCCESS] Тостер\n            [783] => processing product Тостер\n            [784] => [INFO] new product created\n            [785] => [SUCCESS] Тостер\n            [786] => processing product Тостер\n            [787] => [INFO] new product created\n            [788] => [SUCCESS] Тостер\n            [789] => processing product Тостер\n            [790] => [INFO] new product created\n            [791] => [SUCCESS] Тостер\n            [792] => processing product Фен\n            [793] => [INFO] new product created\n            [794] => [SUCCESS] Фен\n            [795] => processing product Фен\n            [796] => [INFO] new product created\n            [797] => [SUCCESS] Фен\n            [798] => processing product Фен\n            [799] => [INFO] new product created\n            [800] => [SUCCESS] Фен\n            [801] => processing product Фен\n            [802] => [INFO] new product created\n            [803] => [SUCCESS] Фен\n            [804] => processing product Фен\n            [805] => [INFO] new product created\n            [806] => [SUCCESS] Фен\n            [807] => processing product Фен\n            [808] => [INFO] new product created\n            [809] => [SUCCESS] Фен\n            [810] => processing product Фен\n            [811] => [INFO] new product created\n            [812] => [SUCCESS] Фен\n            [813] => processing product Фен\n            [814] => [INFO] new product created\n            [815] => [SUCCESS] Фен\n            [816] => processing product Фен\n            [817] => [INFO] new product created\n            [818] => [SUCCESS] Фен\n            [819] => processing product Фен-щітка\n            [820] => [INFO] new product created\n            [821] => [SUCCESS] Фен-щітка\n            [822] => processing product Фен-щітка\n            [823] => [INFO] new product created\n            [824] => [SUCCESS] Фен-щітка\n            [825] => processing product Фен-щітка\n            [826] => [INFO] new product created\n            [827] => [SUCCESS] Фен-щітка\n            [828] => processing product Фен-щітка\n            [829] => [INFO] new product created\n            [830] => [SUCCESS] Фен-щітка\n            [831] => processing product Фен-щітка\n            [832] => [INFO] new product created\n            [833] => [SUCCESS] Фен-щітка\n            [834] => processing product Фен-щітка\n            [835] => [INFO] new product created\n            [836] => [SUCCESS] Фен-щітка\n            [837] => processing product Фен-щітка\n            [838] => [INFO] new product created\n            [839] => [SUCCESS] Фен-щітка\n            [840] => processing product Фритюрниця\n            [841] => [INFO] new product created\n            [842] => [SUCCESS] Фритюрниця\n            [843] => processing product Хлібопічка\n            [844] => [INFO] new product created\n            [845] => [SUCCESS] Хлібопічка\n            [846] => processing product Цитрус-прес\n            [847] => [INFO] new product created\n            [848] => [SUCCESS] Цитрус-прес\n            [849] => processing product Чайник електричний\n            [850] => [INFO] new product created\n            [851] => [SUCCESS] Чайник електричний\n            [852] => processing product Чайник електричний\n            [853] => [INFO] new product created\n            [854] => [SUCCESS] Чайник електричний\n            [855] => processing product Чайник електричний\n            [856] => [INFO] new product created\n            [857] => [SUCCESS] Чайник електричний\n            [858] => processing product Чайник електричний\n            [859] => [INFO] new product created\n            [860] => [SUCCESS] Чайник електричний\n            [861] => processing product Чайник електричний\n            [862] => [INFO] new product created\n            [863] => [SUCCESS] Чайник електричний\n            [864] => processing product Чайник електричний\n            [865] => [INFO] new product created\n            [866] => [SUCCESS] Чайник електричний\n            [867] => processing product Чайник електричний\n            [868] => [INFO] new product created\n            [869] => [SUCCESS] Чайник електричний\n            [870] => processing product Чайник електричний\n            [871] => [INFO] new product created\n            [872] => [SUCCESS] Чайник електричний\n            [873] => processing product Чайник електричний\n            [874] => [INFO] new product created\n            [875] => [SUCCESS] Чайник електричний\n            [876] => processing product Чайник електричний\n            [877] => [INFO] new product created\n            [878] => [SUCCESS] Чайник електричний\n            [879] => processing product Чайник електричний\n            [880] => [INFO] new product created\n            [881] => [SUCCESS] Чайник електричний\n            [882] => processing product Чайник електричний\n            [883] => [INFO] new product created\n            [884] => [SUCCESS] Чайник електричний\n            [885] => processing product Чайник електричний\n            [886] => [INFO] new product created\n            [887] => [SUCCESS] Чайник електричний\n            [888] => processing product Чайник електричний\n            [889] => [INFO] new product created\n            [890] => [SUCCESS] Чайник електричний\n            [891] => processing product Чайник електричний\n            [892] => [INFO] new product created\n            [893] => [SUCCESS] Чайник електричний\n            [894] => processing product Чайник електричний\n            [895] => [INFO] new product created\n            [896] => [SUCCESS] Чайник електричний\n            [897] => processing product Шатківниця\n            [898] => [INFO] new product created\n            [899] => [SUCCESS] Шатківниця\n        )\n\n)\n',0,0,1,0,'2014-11-19 02:09:27'),(5,'e8fa1537bab62d8b62097ee37b2b41d3',1,'shop','importProductFeed','','import_20141119_033844','','Array\n(\n    [total] => 0\n    [productsAdded] => 64\n    [productsUpdated] => 0\n    [productsInvalid] => 0\n    [success] => 1\n    [results] => Array\n        (\n            [0] => processing product Аксесуари для духових шаф\n            [1] => [INFO] new product created\n            [2] => [SUCCESS] Аксесуари для духових шаф\n            [3] => processing product Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [4] => [INFO] new product created\n            [5] => [SUCCESS] Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [6] => processing product Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [7] => [INFO] new product created\n            [8] => [SUCCESS] Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [9] => processing product Двосторонній дисковий ніж-тертка (середня)\n            [10] => [INFO] new product created\n            [11] => [SUCCESS] Двосторонній дисковий ніж-тертка (середня)\n            [12] => processing product Дискова тертка для грубого натирання\n            [13] => [INFO] new product created\n            [14] => [SUCCESS] Дискова тертка для грубого натирання\n            [15] => processing product Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [16] => [INFO] new product created\n            [17] => [SUCCESS] Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [18] => processing product Млинок для зернових\n            [19] => [INFO] new product created\n            [20] => [SUCCESS] Млинок для зернових\n            [21] => processing product Цитрус-прес\n            [22] => [INFO] new product created\n            [23] => [SUCCESS] Цитрус-прес\n            [24] => processing product Приладдя для нарізання продуктів кубиками\n            [25] => [INFO] new product created\n            [26] => [SUCCESS] Приладдя для нарізання продуктів кубиками\n            [27] => processing product Млинок для зернових\n            [28] => [INFO] new product created\n            [29] => [SUCCESS] Млинок для зернових\n            [30] => processing product Цитрус-прес\n            [31] => [INFO] new product created\n            [32] => [SUCCESS] Цитрус-прес\n            [33] => processing product Дискова тертка для грубого натирання\n            [34] => [INFO] new product created\n            [35] => [SUCCESS] Дискова тертка для грубого натирання\n            [36] => processing product Дискова тертка, груба, з нержавіючої сталі\n            [37] => [INFO] new product created\n            [38] => [SUCCESS] Дискова тертка, груба, з нержавіючої сталі\n            [39] => processing product Набір формувальних дисків\n            [40] => [INFO] new product created\n            [41] => [SUCCESS] Набір формувальних дисків\n            [42] => processing product Дисковий ніж з нержавіючої сталі для нарізання картоплі фрі\n            [43] => [INFO] new product created\n            [44] => [SUCCESS] Дисковий ніж з нержавіючої сталі для нарізання картоплі фрі\n            [45] => processing product Аксесуар для мясорубки\n            [46] => [INFO] new product created\n            [47] => [SUCCESS] Аксесуар для мясорубки\n            [48] => processing product Аксесуар для мясорубки\n            [49] => [INFO] new product created\n            [50] => [SUCCESS] Аксесуар для мясорубки\n            [51] => processing product Аксесуар для мясорубки\n            [52] => [INFO] new product created\n            [53] => [SUCCESS] Аксесуар для мясорубки\n            [54] => processing product Аксесуар для мясорубки\n            [55] => [INFO] new product created\n            [56] => [SUCCESS] Аксесуар для мясорубки\n            [57] => processing product Аксесуар для мясорубки\n            [58] => [INFO] new product created\n            [59] => [SUCCESS] Аксесуар для мясорубки\n            [60] => processing product Аксесуар для мясорубки\n            [61] => [INFO] new product created\n            [62] => [SUCCESS] Аксесуар для мясорубки\n            [63] => processing product Аксесуар для мясорубки\n            [64] => [INFO] new product created\n            [65] => [SUCCESS] Аксесуар для мясорубки\n            [66] => processing product Аксесуар для мясорубки\n            [67] => [INFO] new product created\n            [68] => [SUCCESS] Аксесуар для мясорубки\n            [69] => processing product Аксесуар для мясорубки\n            [70] => [INFO] new product created\n            [71] => [SUCCESS] Аксесуар для мясорубки\n            [72] => processing product Аксесуар для мясорубки\n            [73] => [INFO] new product created\n            [74] => [SUCCESS] Аксесуар для мясорубки\n            [75] => processing product Аксесуар для мясорубки\n            [76] => [INFO] new product created\n            [77] => [SUCCESS] Аксесуар для мясорубки\n            [78] => processing product Аксесуар для мясорубки\n            [79] => [INFO] new product created\n            [80] => [SUCCESS] Аксесуар для мясорубки\n            [81] => processing product Аксесуар для пилососа\n            [82] => [INFO] new product created\n            [83] => [SUCCESS] Аксесуар для пилососа\n            [84] => processing product Аксесуар для пилососа\n            [85] => [INFO] new product created\n            [86] => [SUCCESS] Аксесуар для пилососа\n            [87] => processing product Аксесуар для пилососа\n            [88] => [INFO] new product created\n            [89] => [SUCCESS] Аксесуар для пилососа\n            [90] => processing product Блендер\n            [91] => [INFO] new product created\n            [92] => [SUCCESS] Блендер\n            [93] => processing product Блендер\n            [94] => [INFO] new product created\n            [95] => [SUCCESS] Блендер\n            [96] => processing product Блендер\n            [97] => [INFO] new product created\n            [98] => [SUCCESS] Блендер\n            [99] => processing product Блендер\n            [100] => [INFO] new product created\n            [101] => [SUCCESS] Блендер\n            [102] => processing product Блендер\n            [103] => [INFO] new product created\n            [104] => [SUCCESS] Блендер\n            [105] => processing product Блендер\n            [106] => [INFO] new product created\n            [107] => [SUCCESS] Блендер\n            [108] => processing product Блендер\n            [109] => [INFO] new product created\n            [110] => [SUCCESS] Блендер\n            [111] => processing product Блендер\n            [112] => [INFO] new product created\n            [113] => [SUCCESS] Блендер\n            [114] => processing product Блендер\n            [115] => [INFO] new product created\n            [116] => [SUCCESS] Блендер\n            [117] => processing product Блендер\n            [118] => [INFO] new product created\n            [119] => [SUCCESS] Блендер\n            [120] => processing product Блендер\n            [121] => [INFO] new product created\n            [122] => [SUCCESS] Блендер\n            [123] => processing product Блендер\n            [124] => [INFO] new product created\n            [125] => [SUCCESS] Блендер\n            [126] => processing product Блендер\n            [127] => [INFO] new product created\n            [128] => [SUCCESS] Блендер\n            [129] => processing product Блендер\n            [130] => [INFO] new product created\n            [131] => [SUCCESS] Блендер\n            [132] => processing product Блендер\n            [133] => [INFO] new product created\n            [134] => [SUCCESS] Блендер\n            [135] => processing product Блендер\n            [136] => [INFO] new product created\n            [137] => [SUCCESS] Блендер\n            [138] => processing product Блендер\n            [139] => [INFO] new product created\n            [140] => [SUCCESS] Блендер\n            [141] => processing product Блендер\n            [142] => [INFO] new product created\n            [143] => [SUCCESS] Блендер\n            [144] => processing product Блендер\n            [145] => [INFO] new product created\n            [146] => [SUCCESS] Блендер\n            [147] => processing product Блендер\n            [148] => [INFO] new product created\n            [149] => [SUCCESS] Блендер\n            [150] => processing product Блендер\n            [151] => [INFO] new product created\n            [152] => [SUCCESS] Блендер\n            [153] => processing product Блендер\n            [154] => [INFO] new product created\n            [155] => [SUCCESS] Блендер\n            [156] => processing product Блендер\n            [157] => [INFO] new product created\n            [158] => [SUCCESS] Блендер\n            [159] => processing product Блендер\n            [160] => [INFO] new product created\n            [161] => [SUCCESS] Блендер\n            [162] => processing product Блендер\n            [163] => [INFO] new product created\n            [164] => [SUCCESS] Блендер\n            [165] => processing product Блендер\n            [166] => [INFO] new product created\n            [167] => [SUCCESS] Блендер\n            [168] => processing product Бутербродниця\n            [169] => [INFO] new product created\n            [170] => [SUCCESS] Бутербродниця\n            [171] => processing product Бутербродниця\n            [172] => [INFO] new product created\n            [173] => [SUCCESS] Бутербродниця\n            [174] => processing product Варильна поверхня газова\n            [175] => [INFO] new product created\n            [176] => [SUCCESS] Варильна поверхня газова\n            [177] => processing product Варильна поверхня газова\n            [178] => [INFO] new product created\n            [179] => [SUCCESS] Варильна поверхня газова\n            [180] => processing product Варильна поверхня газова\n            [181] => [INFO] new product created\n            [182] => [SUCCESS] Варильна поверхня газова\n            [183] => processing product Варильна поверхня газова\n            [184] => [INFO] new product created\n            [185] => [SUCCESS] Варильна поверхня газова\n            [186] => processing product Варильна поверхня газова\n            [187] => [INFO] new product created\n            [188] => [SUCCESS] Варильна поверхня газова\n            [189] => processing product Варильна поверхня газова\n            [190] => [INFO] new product created\n            [191] => [SUCCESS] Варильна поверхня газова\n        )\n\n)\n',0,0,1,0,'2014-11-19 03:38:44'),(6,'8bef0b60de0e62e0cd9e573171e6390e',1,'shop','importProductFeed','','import_20141119_034356','','Array\n(\n    [total] => 0\n    [productsAdded] => 9\n    [productsUpdated] => 0\n    [productsInvalid] => 0\n    [success] => 1\n    [results] => Array\n        (\n            [0] => processing product Аксесуари для духових шаф\n            [1] => [INFO] new product created\n            [2] => [SUCCESS] Аксесуари для духових шаф\n            [3] => processing product Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [4] => [INFO] new product created\n            [5] => [SUCCESS] Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [6] => processing product Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [7] => [INFO] new product created\n            [8] => [SUCCESS] Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [9] => processing product Двосторонній дисковий ніж-тертка (середня)\n            [10] => [INFO] new product created\n            [11] => [SUCCESS] Двосторонній дисковий ніж-тертка (середня)\n            [12] => processing product Дискова тертка для грубого натирання\n            [13] => [INFO] new product created\n            [14] => [SUCCESS] Дискова тертка для грубого натирання\n            [15] => processing product Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [16] => [INFO] new product created\n            [17] => [SUCCESS] Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [18] => processing product Млинок для зернових\n            [19] => [INFO] new product created\n            [20] => [SUCCESS] Млинок для зернових\n            [21] => processing product Цитрус-прес\n            [22] => [INFO] new product created\n            [23] => [SUCCESS] Цитрус-прес\n            [24] => processing product Приладдя для нарізання продуктів кубиками\n            [25] => [INFO] new product created\n            [26] => [SUCCESS] Приладдя для нарізання продуктів кубиками\n        )\n\n)\n',0,0,0,0,'2014-11-19 03:43:56'),(7,'1374917f614ecf20645a502c4beb78cf',1,'shop','importProductFeed','','import_20141119_040109','','Array\n(\n    [total] => 0\n    [productsAdded] => 9\n    [productsUpdated] => 0\n    [productsInvalid] => 0\n    [success] => 1\n    [results] => Array\n        (\n            [0] => processing product Аксесуари для духових шаф\n            [1] => [INFO] new product created\n            [2] => [SUCCESS] Аксесуари для духових шаф\n            [3] => processing product Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [4] => [INFO] new product created\n            [5] => [SUCCESS] Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [6] => processing product Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [7] => [INFO] new product created\n            [8] => [SUCCESS] Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [9] => processing product Двосторонній дисковий ніж-тертка (середня)\n            [10] => [INFO] new product created\n            [11] => [SUCCESS] Двосторонній дисковий ніж-тертка (середня)\n            [12] => processing product Дискова тертка для грубого натирання\n            [13] => [INFO] new product created\n            [14] => [SUCCESS] Дискова тертка для грубого натирання\n            [15] => processing product Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [16] => [INFO] new product created\n            [17] => [SUCCESS] Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [18] => processing product Млинок для зернових\n            [19] => [INFO] new product created\n            [20] => [SUCCESS] Млинок для зернових\n            [21] => processing product Цитрус-прес\n            [22] => [INFO] new product created\n            [23] => [SUCCESS] Цитрус-прес\n            [24] => processing product Приладдя для нарізання продуктів кубиками\n            [25] => [INFO] new product created\n            [26] => [SUCCESS] Приладдя для нарізання продуктів кубиками\n        )\n\n)\n',0,0,0,0,'2014-11-19 04:01:09'),(8,'ba23280a5169b1a234311fb041140a35',1,'shop','importProductFeed','','import_20141119_040214','','Array\n(\n    [total] => 0\n    [productsAdded] => 0\n    [productsUpdated] => 9\n    [productsInvalid] => 0\n    [success] => 1\n    [results] => Array\n        (\n            [0] => processing product Аксесуари для духових шаф\n            [1] => [INFO] updating existent product 392\n            [2] => [SUCCESS] Аксесуари для духових шаф\n            [3] => processing product Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [4] => [INFO] updating existent product 393\n            [5] => [SUCCESS] Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [6] => processing product Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [7] => [INFO] updating existent product 394\n            [8] => [SUCCESS] Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [9] => processing product Двосторонній дисковий ніж-тертка (середня)\n            [10] => [INFO] updating existent product 395\n            [11] => [SUCCESS] Двосторонній дисковий ніж-тертка (середня)\n            [12] => processing product Дискова тертка для грубого натирання\n            [13] => [INFO] updating existent product 396\n            [14] => [SUCCESS] Дискова тертка для грубого натирання\n            [15] => processing product Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [16] => [INFO] updating existent product 397\n            [17] => [SUCCESS] Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [18] => processing product Млинок для зернових\n            [19] => [INFO] updating existent product 398\n            [20] => [SUCCESS] Млинок для зернових\n            [21] => processing product Цитрус-прес\n            [22] => [INFO] updating existent product 399\n            [23] => [SUCCESS] Цитрус-прес\n            [24] => processing product Приладдя для нарізання продуктів кубиками\n            [25] => [INFO] updating existent product 400\n            [26] => [SUCCESS] Приладдя для нарізання продуктів кубиками\n        )\n\n)\n',0,0,0,0,'2014-11-19 04:02:14'),(9,'9f3d5c43888af802dd9eb06bfb515c29',1,'shop','importProductFeed','','import_20141119_041949','',NULL,0,0,0,0,'2014-11-19 04:19:49'),(10,'a5d8a5c0b36792656e163853f549e0c0',1,'shop','importProductFeed','','import_20141119_042827','','Array\n(\n    [total] => 0\n    [productsAdded] => 0\n    [productsUpdated] => 9\n    [productsInvalid] => 0\n    [success] => 1\n    [results] => Array\n        (\n            [0] => processing product Аксесуари для духових шаф\n            [1] => [INFO] updating existent product 392\n            [2] => [SUCCESS] Аксесуари для духових шаф\n            [3] => processing product Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [4] => [INFO] updating existent product 393\n            [5] => [SUCCESS] Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [6] => processing product Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [7] => [INFO] updating existent product 394\n            [8] => [SUCCESS] Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [9] => processing product Двосторонній дисковий ніж-тертка (середня)\n            [10] => [INFO] updating existent product 395\n            [11] => [SUCCESS] Двосторонній дисковий ніж-тертка (середня)\n            [12] => processing product Дискова тертка для грубого натирання\n            [13] => [INFO] updating existent product 396\n            [14] => [SUCCESS] Дискова тертка для грубого натирання\n            [15] => processing product Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [16] => [INFO] updating existent product 397\n            [17] => [SUCCESS] Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [18] => processing product Млинок для зернових\n            [19] => [INFO] updating existent product 398\n            [20] => [SUCCESS] Млинок для зернових\n            [21] => processing product Цитрус-прес\n            [22] => [INFO] updating existent product 399\n            [23] => [SUCCESS] Цитрус-прес\n            [24] => processing product Приладдя для нарізання продуктів кубиками\n            [25] => [INFO] updating existent product 400\n            [26] => [SUCCESS] Приладдя для нарізання продуктів кубиками\n        )\n\n)\n',0,0,0,0,'2014-11-19 04:28:27'),(11,'eb33a37ce87d10fe35ef463b9490333c',1,'shop','importProductFeed','','import_20141119_043016','','Array\n(\n    [total] => 0\n    [productsAdded] => 0\n    [productsUpdated] => 9\n    [productsInvalid] => 0\n    [success] => 1\n    [results] => Array\n        (\n            [0] => processing product Аксесуари для духових шаф\n            [1] => [INFO] updating existent product 392\n            [2] => [SUCCESS] Аксесуари для духових шаф\n            [3] => processing product Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [4] => [INFO] updating existent product 393\n            [5] => [SUCCESS] Дисковий ніж для нарізання овочів \"по-корейськи\"\n            [6] => processing product Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [7] => [INFO] updating existent product 394\n            [8] => [SUCCESS] Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю\n            [9] => processing product Двосторонній дисковий ніж-тертка (середня)\n            [10] => [INFO] updating existent product 395\n            [11] => [SUCCESS] Двосторонній дисковий ніж-тертка (середня)\n            [12] => processing product Дискова тертка для грубого натирання\n            [13] => [INFO] updating existent product 396\n            [14] => [SUCCESS] Дискова тертка для грубого натирання\n            [15] => processing product Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [16] => [INFO] updating existent product 397\n            [17] => [SUCCESS] Насадка з литого алюмінію для приготування фігурних виробів з тіста\n            [18] => processing product Млинок для зернових\n            [19] => [INFO] updating existent product 398\n            [20] => [SUCCESS] Млинок для зернових\n            [21] => processing product Цитрус-прес\n            [22] => [INFO] updating existent product 399\n            [23] => [SUCCESS] Цитрус-прес\n            [24] => processing product Приладдя для нарізання продуктів кубиками\n            [25] => [INFO] updating existent product 400\n            [26] => [SUCCESS] Приладдя для нарізання продуктів кубиками\n        )\n\n)\n',0,0,0,0,'2014-11-19 04:30:16');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_features`
--

LOCK TABLES `shop_features` WRITE;
/*!40000 ALTER TABLE `shop_features` DISABLE KEYS */;
INSERT INTO `shop_features` VALUES (1,1,'12міс','Гарантія','2014-11-18 11:23:50','2014-11-18 11:23:50'),(2,1,'Пластик','Корпус','2014-11-19 04:30:18','2014-11-19 04:30:18');
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
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productAttributes`
--

LOCK TABLES `shop_productAttributes` WRITE;
/*!40000 ALTER TABLE `shop_productAttributes` DISABLE KEYS */;
INSERT INTO `shop_productAttributes` VALUES (64,1,392,'IMAGE','3921416387749546c5ca5eaac9.jpg'),(65,1,392,'WARRANTY','12міс'),(66,1,393,'IMAGE','3931416387750546c5ca65cdf2.jpg'),(67,1,393,'WARRANTY','12міс'),(68,1,394,'IMAGE','3941416387750546c5ca6b3b3b.jpg'),(69,1,394,'WARRANTY','12міс'),(70,1,395,'IMAGE','3951416387751546c5ca767add.jpg'),(71,1,395,'WARRANTY','12міс'),(72,1,396,'IMAGE','3961416387751546c5ca7ce040.jpg'),(73,1,396,'WARRANTY','12міс'),(74,1,397,'IMAGE','3971416387752546c5ca85282e.jpg'),(75,1,397,'WARRANTY','12міс'),(76,1,398,'IMAGE','3981416387752546c5ca89a788.jpg'),(77,1,398,'WARRANTY','12міс'),(78,1,399,'IMAGE','3991416387753546c5ca904fc5.jpg'),(79,1,399,'WARRANTY','12міс'),(80,1,400,'IMAGE','4001416387753546c5ca97131d.jpg'),(81,1,400,'WARRANTY','12міс');
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productFeatures`
--

LOCK TABLES `shop_productFeatures` WRITE;
/*!40000 ALTER TABLE `shop_productFeatures` DISABLE KEYS */;
INSERT INTO `shop_productFeatures` VALUES (37,1,392,1,'2014-11-19 04:30:18','2014-11-19 04:30:18'),(38,1,393,1,'2014-11-19 04:30:18','2014-11-19 04:30:18'),(39,1,394,1,'2014-11-19 04:30:18','2014-11-19 04:30:18'),(40,1,395,1,'2014-11-19 04:30:18','2014-11-19 04:30:18'),(41,1,396,1,'2014-11-19 04:30:18','2014-11-19 04:30:18'),(42,1,397,1,'2014-11-19 04:30:18','2014-11-19 04:30:18'),(43,1,398,1,'2014-11-19 04:30:18','2014-11-19 04:30:18'),(44,1,399,1,'2014-11-19 04:30:18','2014-11-19 04:30:18'),(45,1,399,2,'2014-11-19 04:30:18','2014-11-19 04:30:18'),(46,1,400,1,'2014-11-19 04:30:18','2014-11-19 04:30:18');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productPrices`
--

LOCK TABLES `shop_productPrices` WRITE;
/*!40000 ALTER TABLE `shop_productPrices` DISABLE KEYS */;
INSERT INTO `shop_productPrices` VALUES (1,1,392,214.00,'2014-11-19 04:28:30'),(2,1,393,100.00,'2014-11-19 04:28:30'),(3,1,394,100.00,'2014-11-19 04:28:30'),(4,1,395,36.00,'2014-11-19 04:28:30'),(5,1,396,4.00,'2014-11-19 04:28:31'),(6,1,397,56.00,'2014-11-19 04:28:31'),(7,1,398,171.00,'2014-11-19 04:28:31'),(8,1,399,17.99,'2014-11-19 04:28:31'),(9,1,400,355.00,'2014-11-19 04:28:31');
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
) ENGINE=InnoDB AUTO_INCREMENT=401 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='shop products';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_products`
--

LOCK TABLES `shop_products` WRITE;
/*!40000 ALTER TABLE `shop_products` DISABLE KEYS */;
INSERT INTO `shop_products` VALUES (392,1,3,46,'Аксесуари для духових шаф','-hez-338250','','HEZ 338250',NULL,300.00,0,'ACTIVE','2014-11-19 04:01:18','2014-11-19 04:30:18'),(393,1,3,46,'Дисковий ніж для нарізання овочів \"по-корейськи\"','-muz45ag1','Для шатківниці кухонних комбайнів серій MUM4, MUM5, MCM55 та MCM6','MUZ45AG1',NULL,300.00,0,'ACTIVE','2014-11-19 04:01:18','2014-11-19 04:30:18'),(394,1,3,46,'Насадка з литого алюмінію для приготування фруктових напоїв з м\'якоттю','-muz45fv1','Для м\'ясорубки кухонних комбайнів серій MUM4 та MUM5','MUZ45FV1',NULL,300.00,0,'ACTIVE','2014-11-19 04:01:19','2014-11-19 04:30:18'),(395,1,3,46,'Двосторонній дисковий ніж-тертка (середня)','-muz45kp1','Для шатківниці кухонних комбайнів серій MUM4, MUM5, MCM55 та MCM6','MUZ45KP1',NULL,300.00,0,'ACTIVE','2014-11-19 04:01:19','2014-11-19 04:30:18'),(396,1,3,46,'Дискова тертка для грубого натирання','-muz45rs1','Для шатківниці кухонних комбайнів серій MUM4, MUM5, MCM55 та MCM6','MUZ45RS1',NULL,300.00,0,'ACTIVE','2014-11-19 04:01:19','2014-11-19 04:30:18'),(397,1,3,46,'Насадка з литого алюмінію для приготування фігурних виробів з тіста','-muz45sv1','Для м\'ясорубки кухонних комбайнів серій MUM4 та MUM5','MUZ45SV1',NULL,300.00,0,'ACTIVE','2014-11-19 04:01:20','2014-11-19 04:30:18'),(398,1,3,46,'Млинок для зернових','-muz4gm3','Для кухонних комбайнів Bosch серії MUM 4.','MUZ4GM3',NULL,300.00,0,'ACTIVE','2014-11-19 04:01:20','2014-11-19 04:30:18'),(399,1,3,46,'Цитрус-прес','-muz4zp1','Для кухонних комбайнів серії MUM4…','MUZ4ZP1',NULL,300.00,0,'ACTIVE','2014-11-19 04:01:20','2014-11-19 04:30:18'),(400,1,3,46,'Приладдя для нарізання продуктів кубиками','-muz5cc1','Для кухонних комбайнів серії MUM5…','MUZ5CC1',NULL,300.00,0,'ACTIVE','2014-11-19 04:01:21','2014-11-19 04:30:18');
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

-- Dump completed on 2014-11-19  9:03:46

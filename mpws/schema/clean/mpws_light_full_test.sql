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
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_accountAddresses`
--

LOCK TABLES `mpws_accountAddresses` WRITE;
/*!40000 ALTER TABLE `mpws_accountAddresses` DISABLE KEYS */;
INSERT INTO `mpws_accountAddresses` VALUES (45,1,139,'dsfsdfsdfs','fsdfsdf','fdgdgdg','dsadsad','REMOVED','2014-07-26 17:30:54','2014-08-01 23:01:10'),(46,1,139,'ddd','fff','ddd','sss','REMOVED','2014-07-26 17:31:06','2014-07-26 17:35:39'),(47,1,139,'ddd','fff','ddd','sss','REMOVED','2014-07-26 17:31:07','2014-07-26 17:35:39'),(48,1,139,'fdsfdsf','dsfdsfdsf','fdsfdsfds','fdsfdsf','REMOVED','2014-08-01 23:52:06','2014-08-01 23:52:16'),(49,1,139,'vsdfsd4334fdsf','35435435','vfgd54435','fdsfdsf','REMOVED','2014-08-02 00:00:30','2014-08-02 00:00:43'),(50,1,139,'fdsfdsfsdf','fdsfdsf','fdfsdf','fdsfdsf','REMOVED','2014-08-02 00:01:05','2014-08-02 00:04:36'),(51,1,139,'dsadsad','dsadsad','dsdsad','dasdsadas','REMOVED','2014-08-02 00:04:48','2014-08-02 00:08:47'),(52,1,139,'vcxvxc','vcxvcxv','vcxvxcv','vcxvcxv','REMOVED','2014-08-02 00:08:57','2014-08-02 00:23:20'),(53,1,139,'dsfsdf','fdsfds','fdsfdsf','fdsfdsf','REMOVED','2014-08-02 00:23:37','2014-08-02 00:24:00'),(54,1,139,'fgfdgfdg','gfdgfd','gfdgfdg','gfdgfdgdf','REMOVED','2014-08-02 00:24:18','2014-08-02 00:24:20'),(55,1,139,'xxxxx','zzzzz','ccccc','mmmmm','ACTIVE','2014-08-02 00:25:29','2014-08-02 00:25:29'),(56,1,141,'addr','797979','ua','lwo','ACTIVE','2014-08-05 10:32:13','2014-08-05 10:32:13'),(57,1,149,'addr','797979','ua','lwo','ACTIVE','2014-08-05 10:57:18','2014-08-05 10:57:18'),(58,1,150,'addr','797979','ua','lwo','ACTIVE','2014-08-05 11:00:52','2014-08-05 11:00:52'),(59,1,152,'addr','797979','ua','lwo','ACTIVE','2014-08-05 21:46:40','2014-08-05 21:46:40'),(60,1,156,'addr','797979','ua','lwo','ACTIVE','2014-08-05 22:13:23','2014-08-05 22:13:23'),(62,1,158,'addr','797979','ua','lwo','ACTIVE','2014-08-05 22:17:34','2014-08-05 22:17:34'),(63,1,159,'addr','797979','ua','lwo','ACTIVE','2014-08-05 22:26:21','2014-08-05 22:26:21'),(64,1,160,'addr','797979','ua','lwo','ACTIVE','2014-08-05 23:19:32','2014-08-05 23:19:32'),(65,1,161,'addr','797979','ua','lwo','ACTIVE','2014-08-05 23:19:56','2014-08-05 23:19:56'),(66,1,162,'addr','797979','ua','lwo','ACTIVE','2014-08-05 23:21:38','2014-08-05 23:21:38'),(67,1,163,'addr','797979','ua','lwo','ACTIVE','2014-08-05 23:22:27','2014-08-05 23:22:27'),(68,1,164,'addr','797979','ua','lwo','ACTIVE','2014-08-05 23:23:16','2014-08-05 23:23:16'),(69,1,165,'addr','797979','ua','lwo','ACTIVE','2014-08-06 00:03:28','2014-08-06 00:03:28'),(70,1,166,'addr','797979','ua','lwo','ACTIVE','2014-08-06 00:06:37','2014-08-06 00:06:37'),(71,1,167,'addr','797979','ua','lwo','ACTIVE','2014-08-06 00:09:23','2014-08-06 00:09:23'),(72,1,168,'addr','797979','ua','lwo','ACTIVE','2014-08-06 00:11:40','2014-08-06 00:11:40'),(73,1,169,'dsadasd','sadasd','sadsad','sadsadsad','ACTIVE','2014-08-06 00:21:44','2014-08-06 00:21:44'),(74,1,170,'dasdsa','asdad','sadsa','dsadsa','ACTIVE','2014-08-06 00:25:02','2014-08-06 00:25:02'),(75,1,171,'fdgfdgfdgfdgfd','gfdgfdg','fdgfd','gfdgfdgf','ACTIVE','2014-08-06 00:28:27','2014-08-06 00:28:27'),(76,1,172,'dasdasdas','dasd','sadsa','dsadas','ACTIVE','2014-08-06 00:29:34','2014-08-06 00:29:34'),(77,1,173,'fdsfds','fdsf','dsfds','fdsf','ACTIVE','2014-08-06 00:33:47','2014-08-06 00:33:47'),(78,1,174,'dsdsadsadsa','dsad','sadsa','dsada','ACTIVE','2014-08-06 09:11:53','2014-08-06 09:11:53'),(79,1,175,'fsfsdf','dsfds','fsdfds','fdsfdsf','ACTIVE','2014-08-06 09:20:53','2014-08-06 09:20:53'),(80,1,176,'rfesf','fsdfsdfds','dfds','fdsfdsfs','ACTIVE','2014-08-06 09:26:50','2014-08-06 09:26:50'),(81,1,177,'dfds','fdsfdsf','dsfds','fdsfdsf','ACTIVE','2014-08-07 01:06:53','2014-08-07 01:06:53'),(83,1,179,'dfds','fdsfdsf','dsfds','fdsfdsf','ACTIVE','2014-08-07 01:07:54','2014-08-07 01:07:54'),(87,1,183,'dfds','fdsfdsf','dsfds','fdsfdsf','ACTIVE','2014-08-07 01:18:01','2014-08-07 01:18:01'),(89,1,185,'dfds','fdsfdsf','dsfds','fdsfdsf','ACTIVE','2014-08-07 01:19:27','2014-08-07 01:19:27'),(90,1,186,'3fsddfdhgfgfhn','gfh','hgfhg','fhgfhgfh','ACTIVE','2014-08-07 01:22:32','2014-08-07 01:22:32'),(91,1,187,'4fsdfds','fdsfdsf','dsfds','fdsfdsf','ACTIVE','2014-08-07 01:24:48','2014-08-07 01:24:48'),(92,1,189,'4sdfdsfd','sfdsfdsf','dsf','dsfs','ACTIVE','2014-08-09 19:23:27','2014-08-09 19:23:27'),(93,1,190,'4sdfdsfd','sfdsfdsf','dsf','dsfs','ACTIVE','2014-08-09 19:24:10','2014-08-09 19:24:10'),(94,1,191,'4sdfdsfd','sfdsfdsf','dsf','dsfs','ACTIVE','2014-08-09 19:26:44','2014-08-09 19:26:44'),(95,1,192,'4sdfdsfd','sfdsfdsf','dsf','dsfs','ACTIVE','2014-08-09 19:30:14','2014-08-09 19:30:14'),(96,1,193,'DSADSA','AD','fdsfdsf','dsfdsfd','ACTIVE','2014-08-09 19:56:31','2014-08-09 19:56:31'),(97,1,194,'sfdfdsfdsf','sfds','fdsfd','sfsdfds','ACTIVE','2014-08-09 19:58:07','2014-08-09 19:58:07'),(98,1,195,'12dsaasdase','dasdsa','dsad','asdsadasd','ACTIVE','2014-08-10 00:04:56','2014-08-10 00:04:56'),(99,1,198,'???????','???????','????????','???????','ACTIVE','2014-08-10 00:14:48','2014-08-10 00:14:48');
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
  UNIQUE KEY `EMail_2` (`EMail`),
  KEY `EMail` (`EMail`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `mpws_accounts_ibfk_4` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_accounts`
--

LOCK TABLES `mpws_accounts` WRITE;
/*!40000 ALTER TABLE `mpws_accounts` DISABLE KEYS */;
INSERT INTO `mpws_accounts` VALUES (139,1,1,'dsfdsffdsfdsfs','dsadsad','admin@pb.com.ua','(546) 565-65-65','b2cff1386ea9cb5744731ac8e0d299dd','8de111e04ec15fc171c7723caa5342e2','ACTIVE','2014-07-26 16:34:38','2014-07-26 16:34:38','2014-08-10 00:33:04'),(140,1,0,'xxx','dddd','admin323@pb.com.ua','(787) 111-11-11','a5232aee5b6f36cbbd72d639e8429ba2','c3410e91b0da48f807a4f954e00f46d0','TEMP','2014-07-28 22:33:14','2014-07-28 22:33:14','2014-07-28 22:33:14'),(141,1,0,'aaaaa','','b@b.com','(888) 888-88-88','31f0da87d498ad541571a8063cd360d7','b171bbebe00c66a1238949e36be30b3e','TEMP','2014-08-05 10:32:13','2014-08-05 10:32:13','2014-08-05 10:32:13'),(149,1,0,'aaaaa','','b@b11.com','(888) 888-88-88','d8515d78f6615f01971995be7a575d5f','ff5d086315205802d2cacf122a95e8f0','TEMP','2014-08-05 10:57:18','2014-08-05 10:57:18','2014-08-05 10:57:18'),(150,1,0,'aaaaa','','b@b1331.com','(888) 888-88-88','56c71dc038e377dd3fdc80ef57589095','f1ac67d12a5c14b888330a97e207e6ef','TEMP','2014-08-05 11:00:52','2014-08-05 11:00:52','2014-08-05 11:00:52'),(152,1,0,'aaaaa','','b@b133dd1.com','(888) 888-88-88','13ed8cf405d7e03c15427c040ef31ef1','08de587005f209abdddd605fbc255cb9','TEMP','2014-08-05 21:46:40','2014-08-05 21:46:40','2014-08-05 21:46:40'),(156,1,0,'aaaaa','','be@b133dd1.com','(888) 888-88-88','64da2986218b7d72fafc89c6d5c9a7e5','4dd146d358f378aa1fa55ff56caa4318','TEMP','2014-08-05 22:13:23','2014-08-05 22:13:23','2014-08-05 22:13:23'),(158,1,0,'aaaaa','','bee4@b133dd1.com','(888) 888-88-88','7eb3997f1e316cf4f7414438b4a4c945','0ca271a1920a61c46f1819c5ef8c483e','TEMP','2014-08-05 22:17:34','2014-08-05 22:17:34','2014-08-05 22:17:34'),(159,1,0,'aaaaa','','bee4ee@b133dd1.com','(888) 888-88-88','7fce13a174f679951fbec794ce4d8776','699de0645e95896d2f030a83b26ac7e3','TEMP','2014-08-05 22:26:21','2014-08-05 22:26:21','2014-08-05 22:26:21'),(160,1,0,'aaaaa','','bee4wee@b133dd1.com','(888) 888-88-88','cba4f1a9fc6156a532c99ea4e8cb7040','b82e5a2bfc7ac60a17b06a1395d15f79','TEMP','2014-08-05 23:19:32','2014-08-05 23:19:32','2014-08-05 23:19:32'),(161,1,0,'aaaaa','','bee4wwee@b133dd1.com','(888) 888-88-88','46f0f272f798b779573f2f40aeaedd6d','d1c62cabeef80c939d3bcfd9b9b7c9b7','TEMP','2014-08-05 23:19:56','2014-08-05 23:19:56','2014-08-05 23:19:56'),(162,1,0,'aaaaa','','bee4wdwee@b133dd1.com','(888) 888-88-88','b37ba1117918a6eb4e838be3d628972f','636bf66b564f5dc66b743f4735ce2394','TEMP','2014-08-05 23:21:38','2014-08-05 23:21:38','2014-08-05 23:21:38'),(163,1,0,'aaaaa','','bee4wddwee@b133dd1.com','(888) 888-88-88','f1bc5ecb4e0a52341430eea899b7eecc','470c9ea741b29ed6f87c8a3406b2cdf4','TEMP','2014-08-05 23:22:27','2014-08-05 23:22:27','2014-08-05 23:22:27'),(164,1,0,'aaaaa','','bee4wwddwee@b133dd1.com','(888) 888-88-88','06d399734cafebc5867b6494aaf12329','09a4bc323e52bdb48f869fad517cbc34','TEMP','2014-08-05 23:23:16','2014-08-05 23:23:16','2014-08-05 23:23:16'),(165,1,0,'aaaaa','','bee4dsdwwddwee@b133dd1.com','(888) 888-88-88','c9cf421467cb02ce9a2ccc7eab1c749f','34e045032ecf17ebad3408d12974f239','TEMP','2014-08-06 00:03:28','2014-08-06 00:03:28','2014-08-06 00:03:28'),(166,1,0,'aaaaa','','bee4dsdwwdddwee@b133dd1.com','(888) 888-88-88','673d120911d1bdd0545bd14d938974df','873f8ae42b6e2e88d615de20a188b0d1','TEMP','2014-08-06 00:06:37','2014-08-06 00:06:37','2014-08-06 00:06:37'),(167,1,0,'aaaaa','','bee4dssdwwdddwee@b133dd1.com','(888) 888-88-88','1989771a705b32030208380008d543dc','d61311f1ad7dfd48acbfecdefd5b93b4','TEMP','2014-08-06 00:09:23','2014-08-06 00:09:23','2014-08-06 00:09:23'),(168,1,0,'aaaaa','','bee4dsssdwwdddwee@b133dd1.com','(888) 888-88-88','a1cdb434a8230b7095ca3d7044849c53','41a92b3618423c139fbe1a1e57358ca1','TEMP','2014-08-06 00:11:40','2014-08-06 00:11:40','2014-08-06 00:11:40'),(169,1,0,'fdsfdsf','','dsfs@dsada.com','(323) 232-32-32','200aafbd07bd0ce7dfbbbaea5ffb82fa','907f38ada9ff32ec5446c2a06a47176f','TEMP','2014-08-06 00:21:44','2014-08-06 00:21:44','2014-08-06 00:21:44'),(170,1,0,'dasdas','','dadsadsa2@edasdasdas.xom','(323) 232-32-32','620d95f14efef19c82a3cc66450f5363','a76cda7dfc5178fe4bf3ddc62607e439','TEMP','2014-08-06 00:25:02','2014-08-06 00:25:02','2014-08-06 00:25:02'),(171,1,0,'dsadsa','','dds@dfasdsa.com','(654) 665-66-56','a39c2c714b8beefbc4d08742fe012b12','033086957b6582dea4bf36fc77ab5ad8','TEMP','2014-08-06 00:28:27','2014-08-06 00:28:27','2014-08-06 00:28:27'),(172,1,0,'dasd','','sada@dad.com','(434) 234-32-42','7e50da76b8252ba5104fbacd11868e6d','6f9998056ba444fa76d03c6e107a1638','TEMP','2014-08-06 00:29:34','2014-08-06 00:29:34','2014-08-06 00:29:34'),(173,1,0,'dsadas','','asdas@dsad.com','(332) 432-43-24','034505c5ae4e3241b0e542409a297a55','9d2fcedbaf7333a097a9c6925f4d6738','TEMP','2014-08-06 00:33:47','2014-08-06 00:33:47','2014-08-06 00:33:47'),(174,1,0,'adasfd','','bfff@b.com','(323) 232-32-32','7bf66a9d2da5fe4c0774fe15c3f8d50e','cb222c8c825da700343751e9fa930303','TEMP','2014-08-06 09:11:53','2014-08-06 09:11:53','2014-08-06 09:11:53'),(175,1,0,'dasfdfdds','','fdsfdfs@refre.com','(333) 333-33-33','eefb8cddcd86c4fc21fd105fa4644b3d','733b75d10ca7dd9e50ef9a69c33bb54b','TEMP','2014-08-06 09:20:52','2014-08-06 09:20:52','2014-08-06 09:20:52'),(176,1,0,'gfsdgfdgfd','','gfdgfd@fdsfsd.com','(344) 242-34-32','45fc815d8ea378f01e383eb7eb8ddbdf','84658281a936dc689247f3c3f85f10f9','TEMP','2014-08-06 09:26:50','2014-08-06 09:26:50','2014-08-06 09:26:50'),(177,1,0,'fdsfd','','sfdsfdsf@fsdadfsa.com','(342) 343-24-32','5b37b6ebe57210bad050b45cd9520d26','8c7df0e3090a61f2fb120757b76106b8','TEMP','2014-08-07 01:06:53','2014-08-07 01:06:53','2014-08-07 01:06:53'),(179,1,0,'fdsfd','','sfdsfedesf@fsdadfsa.com','(342) 343-24-32','31f6acd9963eef85bdc833bdde9ddb07','1e07402128bf0436561807777426e0a8','TEMP','2014-08-07 01:07:54','2014-08-07 01:07:54','2014-08-07 01:07:54'),(183,1,0,'fdsfd','','sfdsfeddfesf@fsdadfsa.com','(342) 343-24-32','c633f43ad6b88377a7f12150c158a1a9','fa3023e0c2a87d322031bb48e839af4a','TEMP','2014-08-07 01:18:01','2014-08-07 01:18:01','2014-08-07 01:18:01'),(185,1,0,'fdsfd','','sfdsfeddfesssf@fsdadfsa.com','(342) 343-24-32','6f83ae9a95ac330e00d2bbb52a834e54','757a4bd547c1464060ec23c6ebc6b0b6','TEMP','2014-08-07 01:19:27','2014-08-07 01:19:27','2014-08-07 01:19:27'),(186,1,0,'fdfdsfs','','fds@dsadv.com','(455) 435-43-54','8bc583ad1ff73a65590e91a168978895','e6fdba282355578b1d1f4b3095a431f6','TEMP','2014-08-07 01:22:32','2014-08-07 01:22:32','2014-08-07 01:22:32'),(187,1,0,'dsfsad','','sadsa@eawsds.com','(343) 243-24-23','b625f78239294da70def43b860965ac0','828a5a02aee209617fde8062bdc3ea43','TEMP','2014-08-07 01:24:47','2014-08-07 01:24:47','2014-08-07 01:24:47'),(189,1,0,'ff','','dfdsfdsf@dasdas.com','(345) 324-34-34','9434973503a882591f78759c7c32ef84','1e37f61e8a9be3337e5115bf1ca88541','TEMP','2014-08-09 19:23:27','2014-08-09 19:23:27','2014-08-09 19:23:27'),(190,1,0,'ffdd','','dfdsfdsddf@dasdas.com','(345) 324-34-34','992785f6fe6409dcc26c9164313c2924','fb1ba562d7177395d2a78121242087e8','TEMP','2014-08-09 19:24:10','2014-08-09 19:24:10','2014-08-09 19:24:10'),(191,1,0,'ffddd','','dfdsfddsddf@dasdas.com','(345) 324-34-34','c1ad2558d1a8d6ab591d74f9df6c83b9','da314fc5ce65e39fd6d997cc8484b5a6','TEMP','2014-08-09 19:26:44','2014-08-09 19:26:44','2014-08-09 19:26:44'),(192,1,0,'ffdsdd','','dfdsfsddsddf@dasdas.com','(345) 324-34-34','fe51706019dcf06441fdd83daec3b339','cfb86dc35c1802936e4dfaf0933a3d0f','TEMP','2014-08-09 19:30:14','2014-08-09 19:30:14','2014-08-09 19:30:14'),(193,1,0,'dfdfds','','fds@SADSA.COM','(434) 243-24-32','01b51f22afb3249144390daec08d932c','f2b3733e11c3239c1211daf77df6bac8','TEMP','2014-08-09 19:56:31','2014-08-09 19:56:31','2014-08-09 19:56:31'),(194,1,0,'dfdsf','','dsfdsf@dsadas.com','(343) 243-24-23','125f6dbd1865859534aa0489edcd4df6','5ab1bcf198d915148cd3e0a93d544166','TEMP','2014-08-09 19:58:07','2014-08-09 19:58:07','2014-08-09 19:58:07'),(195,1,0,'dsadasd','','dsadsad@dsadas.com','(234) 213-12-32','121a1e02e1d7661125ebe7a8c810a4f6','f289741da192a3ebc8cbc3acd915023d','TEMP','2014-08-10 00:04:56','2014-08-10 00:04:56','2014-08-10 00:04:56'),(198,1,0,'aaaa','','zzzz@zzz.com','(232) 323-23-23','7fa1b82beeb765524860f512d0b16973','f65389e3361fa9c0f3ab9fb7b30bdfc1','TEMP','2014-08-10 00:14:48','2014-08-10 00:14:48','2014-08-10 00:14:48');
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
  `AccountID` int(11) NOT NULL,
  `CanAdmin` tinyint(1) NOT NULL,
  `CanCreate` tinyint(1) NOT NULL,
  `CanEdit` tinyint(1) NOT NULL,
  `CanViewReports` tinyint(1) NOT NULL,
  `CanAddUsers` tinyint(1) NOT NULL,
  `DateUpdated` datetime NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `AccountID` (`AccountID`),
  CONSTRAINT `mpws_permissions_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mpws_permissions`
--

LOCK TABLES `mpws_permissions` WRITE;
/*!40000 ALTER TABLE `mpws_permissions` DISABLE KEYS */;
INSERT INTO `mpws_permissions` VALUES (72,139,1,1,1,1,0,'2014-07-26 17:22:45','2014-07-26 16:34:38'),(73,140,0,0,0,0,0,'2014-07-28 22:33:14','2014-07-28 22:33:14'),(74,141,0,0,0,0,0,'2014-08-05 10:32:13','2014-08-05 10:32:13'),(75,149,0,0,0,0,0,'2014-08-05 10:57:18','2014-08-05 10:57:18'),(76,150,0,0,0,0,0,'2014-08-05 11:00:52','2014-08-05 11:00:52'),(77,152,0,0,0,0,0,'2014-08-05 21:46:40','2014-08-05 21:46:40'),(78,156,0,0,0,0,0,'2014-08-05 22:13:23','2014-08-05 22:13:23'),(80,158,0,0,0,0,0,'2014-08-05 22:17:34','2014-08-05 22:17:34'),(81,159,0,0,0,0,0,'2014-08-05 22:26:21','2014-08-05 22:26:21'),(82,160,0,0,0,0,0,'2014-08-05 23:19:32','2014-08-05 23:19:32'),(83,161,0,0,0,0,0,'2014-08-05 23:19:56','2014-08-05 23:19:56'),(84,162,0,0,0,0,0,'2014-08-05 23:21:38','2014-08-05 23:21:38'),(85,163,0,0,0,0,0,'2014-08-05 23:22:27','2014-08-05 23:22:27'),(86,164,0,0,0,0,0,'2014-08-05 23:23:16','2014-08-05 23:23:16'),(87,165,0,0,0,0,0,'2014-08-06 00:03:28','2014-08-06 00:03:28'),(88,166,0,0,0,0,0,'2014-08-06 00:06:37','2014-08-06 00:06:37'),(89,167,0,0,0,0,0,'2014-08-06 00:09:23','2014-08-06 00:09:23'),(90,168,0,0,0,0,0,'2014-08-06 00:11:40','2014-08-06 00:11:40'),(91,169,0,0,0,0,0,'2014-08-06 00:21:44','2014-08-06 00:21:44'),(92,170,0,0,0,0,0,'2014-08-06 00:25:02','2014-08-06 00:25:02'),(93,171,0,0,0,0,0,'2014-08-06 00:28:27','2014-08-06 00:28:27'),(94,172,0,0,0,0,0,'2014-08-06 00:29:34','2014-08-06 00:29:34'),(95,173,0,0,0,0,0,'2014-08-06 00:33:47','2014-08-06 00:33:47'),(96,174,0,0,0,0,0,'2014-08-06 09:11:53','2014-08-06 09:11:53'),(97,175,0,0,0,0,0,'2014-08-06 09:20:52','2014-08-06 09:20:52'),(98,176,0,0,0,0,0,'2014-08-06 09:26:50','2014-08-06 09:26:50'),(99,177,0,0,0,0,0,'2014-08-07 01:06:53','2014-08-07 01:06:53'),(101,179,0,0,0,0,0,'2014-08-07 01:07:54','2014-08-07 01:07:54'),(105,183,0,0,0,0,0,'2014-08-07 01:18:01','2014-08-07 01:18:01'),(107,185,0,0,0,0,0,'2014-08-07 01:19:27','2014-08-07 01:19:27'),(108,186,0,0,0,0,0,'2014-08-07 01:22:32','2014-08-07 01:22:32'),(109,187,0,0,0,0,0,'2014-08-07 01:24:48','2014-08-07 01:24:48'),(110,189,0,0,0,0,0,'2014-08-09 19:23:27','2014-08-09 19:23:27'),(111,190,0,0,0,0,0,'2014-08-09 19:24:10','2014-08-09 19:24:10'),(112,191,0,0,0,0,0,'2014-08-09 19:26:44','2014-08-09 19:26:44'),(113,192,0,0,0,0,0,'2014-08-09 19:30:14','2014-08-09 19:30:14'),(114,193,0,0,0,0,0,'2014-08-09 19:56:31','2014-08-09 19:56:31'),(115,194,0,0,0,0,0,'2014-08-09 19:58:07','2014-08-09 19:58:07'),(116,195,0,0,0,0,0,'2014-08-10 00:04:56','2014-08-10 00:04:56'),(119,198,0,0,0,0,0,'2014-08-10 00:14:48','2014-08-10 00:14:48');
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
  `Price` decimal(10,2) NOT NULL,
  `SellingPrice` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `IsPromo` tinyint(1) NOT NULL,
  `DateCreated` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `ProductID` (`ProductID`),
  KEY `OrderID` (`OrderID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_boughts_ibfk_5` FOREIGN KEY (`ProductID`) REFERENCES `shop_products` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_boughts_ibfk_6` FOREIGN KEY (`OrderID`) REFERENCES `shop_orders` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_boughts_ibfk_7` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_boughts`
--

LOCK TABLES `shop_boughts` WRITE;
/*!40000 ALTER TABLE `shop_boughts` DISABLE KEYS */;
INSERT INTO `shop_boughts` VALUES (1,0,16,2,554.00,0.00,1,0,'2014-08-05 11:00:53'),(2,0,16,5,554.00,0.00,1,0,'2014-08-05 22:17:34'),(3,1,16,6,554.00,0.00,1,0,'2014-08-05 22:26:21'),(4,1,16,7,554.00,0.00,1,0,'2014-08-05 23:19:32'),(5,1,18,8,65.00,0.00,1,0,'2014-08-05 23:19:56'),(6,1,16,9,554.00,0.00,1,0,'2014-08-05 23:21:38'),(7,1,18,9,65.00,0.00,1,0,'2014-08-05 23:21:38'),(8,1,27,10,7.00,0.00,1,0,'2014-08-05 23:22:27'),(9,1,5,10,17.00,0.00,1,0,'2014-08-05 23:22:27'),(10,1,12,11,17.00,0.00,1,1,'2014-08-05 23:23:16'),(11,1,25,12,7.00,0.00,1,1,'2014-08-06 00:03:28'),(12,1,27,12,7.00,0.00,2,0,'2014-08-06 00:03:28'),(13,1,16,12,554.00,0.00,1,0,'2014-08-06 00:03:28'),(14,1,18,12,65.00,0.00,1,0,'2014-08-06 00:03:28'),(15,1,22,12,65.00,0.00,1,1,'2014-08-06 00:03:28'),(16,1,23,12,83.00,0.00,1,1,'2014-08-06 00:03:28'),(17,1,25,13,7.00,0.00,1,1,'2014-08-06 00:06:37'),(18,1,18,14,65.00,0.00,1,0,'2014-08-06 00:09:23'),(19,1,16,14,554.00,0.00,1,0,'2014-08-06 00:09:23'),(20,1,16,15,554.00,0.00,1,0,'2014-08-06 00:11:40'),(21,1,16,16,554.00,0.00,1,0,'2014-08-06 00:21:44'),(22,1,16,17,554.00,0.00,1,0,'2014-08-06 00:25:02'),(23,1,18,17,65.00,0.00,1,0,'2014-08-06 00:25:02'),(24,1,16,18,554.00,0.00,1,0,'2014-08-06 00:28:27'),(25,1,18,19,65.00,0.00,1,0,'2014-08-06 00:29:34'),(26,1,16,19,554.00,0.00,1,0,'2014-08-06 00:29:34'),(27,1,16,20,554.00,0.00,1,0,'2014-08-06 00:33:47'),(28,1,18,20,65.00,0.00,1,0,'2014-08-06 00:33:47'),(29,1,23,21,18.99,0.00,1,1,'2014-08-06 09:11:53'),(30,1,22,21,18.36,0.00,1,1,'2014-08-06 09:11:53'),(31,1,18,22,65.00,0.00,1,0,'2014-08-06 09:20:53'),(32,1,16,22,554.00,0.00,1,0,'2014-08-06 09:20:53'),(33,1,22,22,52.65,0.00,1,1,'2014-08-06 09:20:53'),(34,1,22,23,52.65,0.00,1,1,'2014-08-06 09:26:50'),(35,1,22,24,65.00,59.00,1,1,'2014-08-07 01:06:53'),(36,1,22,25,65.00,59.00,1,1,'2014-08-07 01:07:54'),(37,1,27,26,7.00,7.00,1,0,'2014-08-07 01:18:01'),(38,1,18,26,65.00,65.00,1,0,'2014-08-07 01:18:01'),(39,1,22,26,65.00,59.00,1,1,'2014-08-07 01:18:01'),(40,1,22,27,65.00,65.00,2,1,'2014-08-07 01:19:27'),(41,1,22,28,65.00,59.00,1,1,'2014-08-07 01:22:32'),(42,1,22,29,65.00,58.50,2,1,'2014-08-07 01:24:48'),(43,1,18,30,65.00,65.00,4,0,'2014-08-09 19:23:28'),(44,1,5,31,17.00,17.00,5,0,'2014-08-09 19:24:10'),(45,1,27,32,7.00,7.00,1,0,'2014-08-09 19:26:44'),(46,1,27,33,7.00,7.00,1,0,'2014-08-09 19:30:14'),(47,1,5,34,17.00,17.00,1,0,'2014-08-09 19:56:32'),(48,1,28,34,0.00,0.00,1,0,'2014-08-09 19:56:32'),(49,1,27,35,7.00,7.00,5,0,'2014-08-09 19:58:07'),(50,1,5,35,17.00,17.00,11,0,'2014-08-09 19:58:07'),(51,1,28,35,0.00,0.00,4,0,'2014-08-09 19:58:07'),(52,1,29,35,0.00,0.00,4,0,'2014-08-09 19:58:07'),(53,1,28,36,0.00,0.00,13,0,'2014-08-10 00:04:56'),(54,1,27,36,7.00,7.00,11,0,'2014-08-10 00:04:56'),(55,1,26,36,7.00,7.00,11,0,'2014-08-10 00:04:56'),(56,1,5,36,17.00,17.00,1,0,'2014-08-10 00:04:56'),(57,1,7,36,46.25,46.25,6,0,'2014-08-10 00:04:56'),(58,1,26,37,7.00,7.00,1,0,'2014-08-10 00:14:48');
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
-- Table structure for table `shop_deliveryAgencies`
--

DROP TABLE IF EXISTS `shop_deliveryAgencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_deliveryAgencies` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `WebSite` varchar(300) NOT NULL,
  `State` enum('ACTIVE','REMOVED','','') NOT NULL DEFAULT 'ACTIVE',
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_deliveryAgencies`
--

LOCK TABLES `shop_deliveryAgencies` WRITE;
/*!40000 ALTER TABLE `shop_deliveryAgencies` DISABLE KEYS */;
INSERT INTO `shop_deliveryAgencies` VALUES (1,2,'wwww','wwwwee','ACTIVE','2014-08-08 01:47:24','2014-08-08 00:00:00'),(2,0,'gunsel','www.gunsel.com.ua','ACTIVE','2014-08-09 19:32:18','2014-08-09 00:00:00'),(3,1,'gunsel','www.gunsel.com.ua','ACTIVE','2014-08-09 19:32:30','2014-08-09 00:00:00');
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
  `DateCreated` datetime NOT NULL,
  `DateUpdated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Field` (`FieldName`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_features_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_features`
--

LOCK TABLES `shop_features` WRITE;
/*!40000 ALTER TABLE `shop_features` DISABLE KEYS */;
INSERT INTO `shop_features` VALUES (1,1,'LED','2014-06-11 00:00:00','2014-06-11 00:00:00'),(2,1,'16:9','2014-06-11 00:00:00','2014-06-11 00:00:00'),(3,1,'UltraFlat','2014-06-11 00:00:00','2014-06-11 00:00:00'),(4,1,'HD Support','2014-06-11 00:00:00','2014-06-11 00:00:00'),(5,1,'Wi-Fi','2014-06-11 00:00:00','2014-06-11 00:00:00');
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
  `Shipping` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `Warehouse` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `Comment` varchar(500) COLLATE utf8_bin DEFAULT NULL,
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
  CONSTRAINT `shop_orders_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `mpws_accounts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_orders_ibfk_2` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `shop_orders_ibfk_3` FOREIGN KEY (`AccountAddressesID`) REFERENCES `mpws_accountAddresses` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_orders`
--

LOCK TABLES `shop_orders` WRITE;
/*!40000 ALTER TABLE `shop_orders` DISABLE KEYS */;
INSERT INTO `shop_orders` VALUES (2,1,150,58,'company_gunsel','12','nope','NEW','9a6526c13c782efec386f9a2736fc6b1',NULL,'2014-08-05 11:00:52','2014-08-05 11:00:52'),(3,1,152,59,'company_gunsel','12','nope','NEW','78d8d7d15595d961b6a8c12ce0df33c2',NULL,'2014-08-05 21:46:40','2014-08-05 21:46:40'),(4,1,156,60,'company_gunsel','12','nope','NEW','ee8410f40da7bd0b93463f0819b0879e',NULL,'2014-08-05 22:13:23','2014-08-05 22:13:23'),(5,1,158,62,'company_gunsel','12','nope','NEW','0f132f394dcc8c534a25f6a24bd0e5cc',NULL,'2014-08-05 22:17:34','2014-08-05 22:17:34'),(6,1,159,63,'company_gunsel','12','nope','NEW','f4f424093eb502fddf6cdc41209d2078',NULL,'2014-08-05 22:26:21','2014-08-05 22:26:21'),(7,1,160,64,'company_gunsel','12','nope','NEW','8d2b8e887edacb6c72f9f09266380c11',NULL,'2014-08-05 23:19:32','2014-08-05 23:19:32'),(8,1,161,65,'company_gunsel','12','nope','SHOP_CLOSED','4a955999b078232fc5eebf4809df7d90',NULL,'2014-08-05 23:19:56','2014-08-05 23:19:56'),(9,1,162,66,'company_gunsel','12','nope','SHOP_CLOSED','f0d469e07594e920aacab92e70695185',NULL,'2014-08-05 23:21:38','2014-08-05 23:21:38'),(10,1,163,67,'company_gunsel','12','nope','SHOP_CLOSED','7872e875c896a4349ab17e44b21026d1',NULL,'2014-07-21 23:22:27','2014-08-05 23:22:27'),(11,1,164,68,'company_gunsel','12','nope','NEW','00dba5ca1ac72f8f8b5648843de5c51e',NULL,'2014-08-05 23:23:16','2014-08-05 23:23:16'),(12,1,165,69,'company_gunsel','12','nope','NEW','24d8632c86071a35f4cc540b13f70ef8',NULL,'2014-08-06 00:03:28','2014-08-06 00:03:28'),(13,1,166,70,'company_gunsel','12','nope','NEW','d25749e58cb0a29ba27e4566f19d53e6',NULL,'2014-08-06 00:06:37','2014-08-06 00:06:37'),(14,1,167,71,'company_gunsel','12','nope','NEW','f8e00c2987406517267884eb4ced3941',NULL,'2014-08-06 00:09:23','2014-08-06 00:09:23'),(15,1,168,72,'company_gunsel','12','nope','NEW','e50f308b9ae5d86f75e5c49392b60db8',NULL,'2014-08-06 00:11:40','2014-08-06 00:11:40'),(16,1,169,73,'','','sadsadsad','NEW','873feb22c4115c15d917b2f32acc64ce',NULL,'2014-08-06 00:21:44','2014-08-06 00:21:44'),(17,1,170,74,'','','dsadsadas','NEW','e52985a1808a49a43e2705b010c39b21',NULL,'2014-08-06 00:25:02','2014-08-06 00:25:02'),(18,1,171,75,'','','dgfdg','NEW','e83238a86524a69f325e38113a236718',NULL,'2014-08-06 00:28:27','2014-08-06 00:28:27'),(19,1,172,76,'','','dasdasd','NEW','c0faa37d81462b0f796a2853befdb50c',NULL,'2014-08-06 00:29:34','2014-08-06 00:29:34'),(20,1,173,77,'','','','NEW','81fda0652c414125e274f1bc2af3c96a',NULL,'2014-08-06 00:33:47','2014-08-06 00:33:47'),(21,1,174,78,'','','dsadsa','NEW','fb7fb857b0ea31a23a3f2d6874f8cc2f','1','2014-08-06 09:11:53','2014-08-06 09:11:53'),(22,1,175,79,'','','fdsfsdf','NEW','82b3ff53f6d5b5713dcf46969e2d6d3a','1','2014-08-06 09:20:53','2014-08-06 09:20:53'),(23,1,176,80,'','','','NEW','6cf3fccf7c9d3550815f8baef9ce5366','1','2014-08-06 09:26:50','2014-08-06 09:26:50'),(24,1,177,81,'','','','NEW','87e97f4151d330ec7ce5f8f9efbfb95e','1','2014-08-07 01:06:53','2014-08-07 01:06:53'),(25,1,179,83,'','','','NEW','4f6db05389ddcb8e97cf7e565faa24fe','1','2014-08-07 01:07:54','2014-08-07 01:07:54'),(26,1,183,87,'','','','SHOP_CLOSED','f97c2f446b987da5f1ab4475b531d0b9','1','2014-08-07 01:18:01','2014-08-07 01:18:01'),(27,1,185,89,'','','','NEW','252c8484881f943ab4373f78a129c104',NULL,'2014-08-07 01:19:27','2014-08-07 01:19:27'),(28,1,186,90,'','','gfh','NEW','cf7b9334634eba0bc507776dde9e9655','1','2014-08-07 01:22:32','2014-08-07 01:22:32'),(29,1,187,91,'','','dsfds','NEW','76e78f18af6b9bb669aa2d5e9b5251a5','1','2014-08-07 01:24:48','2014-08-07 01:24:48'),(30,1,189,92,'','','fdsfsd','NEW','5c3c1ad325fdf060f4ec6b2b5689b23e','1','2014-08-09 19:23:27','2014-08-09 19:23:27'),(31,1,190,93,'','','fdsfsd','NEW','5699061b56affc7e60fce59e9c1c24d5','1','2014-08-09 19:24:10','2014-08-09 19:24:10'),(32,1,191,94,'','','fdsfsd','NEW','492c3d0f1aa81d2a5df6692c855e5ef7',NULL,'2014-08-09 19:26:44','2014-08-09 19:26:44'),(33,1,192,95,'','','fdsfsd','NEW','b30daa51ab3f74cde4b0b517f709f8e6',NULL,'2014-08-09 19:30:14','2014-08-09 19:30:14'),(34,1,193,96,'','','sdsfdsfdsf','NEW','25b949a519db5e16fa996f285b8af681',NULL,'2014-08-09 19:56:32','2014-08-09 19:56:32'),(35,1,194,97,'','','fsdfsdfdsf','NEW','6e32d6208ad0ae01bd0b4c0773e9026d',NULL,'2014-08-09 19:58:07','2014-08-09 19:58:07'),(36,1,195,98,'','','32333','NEW','19a525b8f75d0b096362213a490001ef','1','2014-08-10 00:04:56','2014-08-10 00:04:56'),(37,1,198,99,'','','віавіаві','NEW','156cba9f6afda6ba0a7152498d0c19b2',NULL,'2014-08-10 00:14:48','2014-08-10 00:14:48');
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
INSERT INTO `shop_productAttributes` VALUES (1,1,4,'LABEL','test','ACTIVE'),(2,1,4,'TAGS','wash device','ACTIVE'),(3,1,5,'TAGS','light bulb','ACTIVE'),(4,1,5,'LABEL','smth elese','ACTIVE'),(5,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/03-EKOCYCLE-Beats-headphones.jpg','ACTIVE'),(6,1,5,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/3dnWhDVRASLCMuQg.jpg','ACTIVE'),(7,1,6,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/302835-apple-iphone-5-sprint.jpg','ACTIVE'),(8,1,7,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/1362020208.jpg','ACTIVE'),(9,1,3,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(10,1,3,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/1362020208.jpg','ACTIVE'),(11,1,10,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/1362020208.jpg','ACTIVE'),(12,1,11,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/1362020208.jpg','ACTIVE'),(13,1,12,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/dryer2.jpg','ACTIVE'),(14,1,6,'LABEL','smth elese','ACTIVE'),(15,1,7,'LABEL','smth elese','ACTIVE'),(16,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/episkevi-laptop.jpg','REMOVED'),(17,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(18,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(19,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(20,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(21,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(22,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/frigidaire-red-fasg7074lr-0511-mdn.jpg','ACTIVE'),(23,1,4,'IMAGE','https://dl.dropboxusercontent.com/u/6858514/temp_products_images/lenovo-c540-all-in-one-desktop-pc-100021362-large.jpg','ACTIVE');
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_productFeatures`
--

LOCK TABLES `shop_productFeatures` WRITE;
/*!40000 ALTER TABLE `shop_productFeatures` DISABLE KEYS */;
INSERT INTO `shop_productFeatures` VALUES (1,1,3,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(2,1,4,2,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(3,1,4,3,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(4,1,12,2,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(5,1,6,2,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(6,1,7,2,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(7,1,7,3,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(8,1,8,3,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(9,1,10,1,'2014-06-11 00:00:00','2014-06-11 00:00:00'),(10,1,16,3,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(11,1,17,4,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(12,1,18,4,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(13,1,19,4,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(14,1,20,4,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(15,1,21,4,'2014-06-21 00:00:00','2014-06-21 00:00:00'),(16,1,27,3,'2014-06-21 00:00:00','2014-06-21 00:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='shop products';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_products`
--

LOCK TABLES `shop_products` WRITE;
/*!40000 ALTER TABLE `shop_products` DISABLE KEYS */;
INSERT INTO `shop_products` VALUES (3,1,1,1,'TES 1','tes1','test test 33','test test 33','test test 33',213.00,1,'DISCOUNT','2014-07-06 20:54:29','2014-07-06 20:54:29'),(4,1,1,5,'LCD S32DV','lcds32dv','LCD S32DV Description','S32DV','S32DV11111',100.00,0,'DISCOUNT','2014-07-06 20:54:29','2014-07-06 20:54:29'),(5,1,1,2,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',17.00,0,'ACTIVE','2014-08-09 20:54:29','2014-07-06 20:54:29'),(6,1,1,1,'I AM HIDDEN PRODUCT','test2','test test','test test','test test',36.00,0,'ARCHIVED','2014-07-06 20:54:29','2014-07-06 20:54:29'),(7,1,4,1,'Ложки','logku','Опис тут','L100','ALLL1200100',46.25,0,'ACTIVE','2014-08-09 20:54:29','2014-07-06 20:54:29'),(8,1,16,7,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',56.00,0,'WAITING','2014-07-06 20:54:29','2014-07-06 20:54:29'),(9,1,15,1,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',71.00,0,'ACTIVE','2014-08-09 20:54:29','2014-07-06 20:54:29'),(10,1,13,8,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',171.00,1,'DISCOUNT','2014-08-09 20:54:29','2014-07-06 20:54:29'),(11,1,23,2,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',37.00,0,'PREORDER','2014-07-06 20:54:29','2014-07-06 20:54:29'),(12,1,3,3,'AAA S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',17.00,1,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(13,1,1,1,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',355.00,0,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(14,1,27,3,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',68.00,0,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(15,1,1,3,'Discount Two','disctwo','Product with discount','D2','DSCTWO0001',25.00,0,'DISCOUNT','2014-07-06 20:54:29','2014-07-06 20:54:29'),(16,1,1,1,'EEE S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',554.00,0,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(17,1,15,6,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',7.00,1,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(18,1,1,1,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',65.00,0,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(19,1,16,6,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',7.00,0,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(20,1,1,1,'BBB S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',55.00,0,'DISCOUNT','2014-07-06 20:54:29','2014-07-06 20:54:29'),(21,1,14,8,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',45.00,0,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(22,1,14,1,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',65.00,1,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(23,1,14,1,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',83.00,1,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(24,1,1,7,'GGG S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',7.00,0,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(25,1,23,1,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',7.00,1,'ACTIVE','2014-07-06 20:54:29','2014-07-06 20:54:29'),(26,1,21,8,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',7.00,0,'ACTIVE','2014-08-11 20:54:29','2014-07-06 20:54:29'),(27,1,1,1,'LCD S48DV','lcds48dv','LCD S48DV Description','S48DV','S48DV222222',7.00,0,'ACTIVE','2014-08-10 20:54:29','2014-07-06 20:54:29'),(28,1,7,2,'','fsdfsdfs','','',NULL,0.00,0,'ACTIVE','2014-08-10 20:54:29','2014-07-06 20:54:29'),(29,1,7,2,'','fsdfsdfs','','',NULL,0.00,0,'ACTIVE','2014-08-10 20:54:29','2014-07-06 20:54:29');
/*!40000 ALTER TABLE `shop_products` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_promo`
--

LOCK TABLES `shop_promo` WRITE;
/*!40000 ALTER TABLE `shop_promo` DISABLE KEYS */;
INSERT INTO `shop_promo` VALUES (1,1,'qwerty','2013-11-12 00:00:00','2014-11-27 00:00:00',10,'2014-07-06 00:00:00');
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
INSERT INTO `shop_relations` VALUES (1,3,10),(1,3,11),(1,8,18),(1,5,10),(1,5,3);
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
  `Property` varchar(50) NOT NULL,
  `Value` varchar(150) NOT NULL,
  `DateCreated` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `CustomerID` (`CustomerID`),
  CONSTRAINT `shop_settings_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `mpws_customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_settings`
--

LOCK TABLES `shop_settings` WRITE;
/*!40000 ALTER TABLE `shop_settings` DISABLE KEYS */;
INSERT INTO `shop_settings` VALUES (1,1,'UsePromo','1','2014-07-06 00:00:00');
/*!40000 ALTER TABLE `shop_settings` ENABLE KEYS */;
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
/*!50003 DROP PROCEDURE IF EXISTS `getAllShopCategoryBrandsWithSubCategories` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllShopCategoryBrandsWithSubCategories`(IN `cat_ids` VARCHAR(100))
SELECT o.ID, o.Name FROM shop_products AS p LEFT JOIN shop_origins AS o ON p.OriginID = o.ID WHERE  p.Status = "ACTIVE" AND o.Status ="ACTIVE" AND FIND_IN_SET (p.CategoryID, cat_ids) GROUP BY o.Name ;;
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

-- Dump completed on 2014-08-10  0:54:40

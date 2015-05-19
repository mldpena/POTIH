CREATE DATABASE  IF NOT EXISTS `dbs_hitop` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `dbs_hitop`;
-- MySQL dump 10.13  Distrib 5.6.23, for Win64 (x86_64)
--
-- Host: localhost    Database: dbs_hitop
-- ------------------------------------------------------
-- Server version	5.5.27

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
-- Table structure for table `branch`
--

DROP TABLE IF EXISTS `branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branch` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT '',
  `name` varchar(45) DEFAULT '',
  `is_show` int(1) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch`
--

LOCK TABLES `branch` WRITE;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` VALUES (1,'01','Manila',1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1);
/*!40000 ALTER TABLE `branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material_type`
--

DROP TABLE IF EXISTS `material_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material_type` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT '',
  `name` varchar(45) DEFAULT '',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material_type`
--

LOCK TABLES `material_type` WRITE;
/*!40000 ALTER TABLE `material_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `material_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(20) DEFAULT '0',
  `description` varchar(80) DEFAULT '',
  `type` int(1) DEFAULT '0',
  `material_type` bigint(20) DEFAULT '0',
  `subgrouping` bigint(20) DEFAULT '0',
  `is_show` int(1) DEFAULT '0',
  `min_inv` int(11) DEFAULT '0',
  `max_inv` int(11) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subgrouping`
--

DROP TABLE IF EXISTS `subgrouping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subgrouping` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT '',
  `name` varchar(45) DEFAULT '',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subgrouping`
--

LOCK TABLES `subgrouping` WRITE;
/*!40000 ALTER TABLE `subgrouping` DISABLE KEYS */;
/*!40000 ALTER TABLE `subgrouping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(30) DEFAULT '',
  `full_name` varchar(50) DEFAULT '',
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(45) DEFAULT '',
  `is_show` int(1) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '1',
  `last_modified_by` bigint(20) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'01','Lawrence Pena','superadmin','tvq~KrF4sXKBnqIZGs8r5ZAvqbK9jkpuN2oGXUD4tBBHSKEztvY3yjOHoWYEw1q7S0.QTtdUfRc68cy.qssG.Q--','09263188835',1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_permission`
--

DROP TABLE IF EXISTS `user_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) DEFAULT '0',
  `user_id` bigint(20) DEFAULT '0',
  `permission_code` int(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permission`
--

LOCK TABLES `user_permission` WRITE;
/*!40000 ALTER TABLE `user_permission` DISABLE KEYS */;
INSERT INTO `user_permission` VALUES (1,1,1,100);
/*!40000 ALTER TABLE `user_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'dbs_hitop'
--

--
-- Dumping routines for database 'dbs_hitop'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-05-19 15:33:04

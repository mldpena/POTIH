CREATE DATABASE  IF NOT EXISTS `dbs_hitop` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `dbs_hitop`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: dbs_hitop
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
  `is_show` int(1) DEFAULT '1',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch`
--

LOCK TABLES `branch` WRITE;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` VALUES (1,'01','Manila',1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1),(2,'02','Makati',1,'2015-05-24 02:26:23','2015-05-24 02:34:10',1,1);
/*!40000 ALTER TABLE `branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `damage_detail`
--

DROP TABLE IF EXISTS `damage_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `damage_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `memo` varchar(150) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `damage_detail`
--

LOCK TABLES `damage_detail` WRITE;
/*!40000 ALTER TABLE `damage_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `damage_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `damage_head`
--

DROP TABLE IF EXISTS `damage_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `damage_head` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reference_number` int(11) DEFAULT '0',
  `branch_id` bigint(20) DEFAULT '0',
  `entry_date` datetime DEFAULT NULL,
  `memo` varchar(150) DEFAULT '',
  `is_show` int(1) DEFAULT '1',
  `is_used` int(1) DEFAULT '0',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `damage_head`
--

LOCK TABLES `damage_head` WRITE;
/*!40000 ALTER TABLE `damage_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `damage_head` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `material_type`
--

DROP TABLE IF EXISTS `material_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material_type` (
  `id` bigint(20) NOT NULL,
  `code` varchar(10) DEFAULT '',
  `name` varchar(45) DEFAULT '',
  `is_show` int(1) DEFAULT '1',
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
INSERT INTO `material_type` VALUES (1,'T','TIGER BRONZE',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(2,'S','STAINLESS STEEL',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(3,'R','BRASS',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(4,'C','COPPER',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(5,'A','ALUMINUM',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(6,'B','BI / BLACK IRON',1,'2015-05-20 00:00:00','2015-05-23 05:38:12',1,1),(7,'G','GALVANIZED',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1);
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
  `material_code` varchar(20) DEFAULT '0',
  `description` varchar(80) DEFAULT '',
  `type` int(1) DEFAULT '0',
  `material_type_id` bigint(20) DEFAULT '0',
  `subgroup_id` bigint(20) DEFAULT '0',
  `is_show` int(1) DEFAULT '1',
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
-- Table structure for table `product_branch_inventory`
--

DROP TABLE IF EXISTS `product_branch_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_branch_inventory` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `inventory` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_branch_inventory`
--

LOCK TABLES `product_branch_inventory` WRITE;
/*!40000 ALTER TABLE `product_branch_inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_branch_inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_detail`
--

DROP TABLE IF EXISTS `return_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `memo` varchar(150) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_detail`
--

LOCK TABLES `return_detail` WRITE;
/*!40000 ALTER TABLE `return_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `return_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_head`
--

DROP TABLE IF EXISTS `return_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_head` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reference_number` int(11) DEFAULT '0',
  `branch_id` bigint(20) DEFAULT '0',
  `entry_date` datetime DEFAULT NULL,
  `customer` varchar(100) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `is_show` int(1) DEFAULT '1',
  `is_used` int(1) DEFAULT '0',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_head`
--

LOCK TABLES `return_head` WRITE;
/*!40000 ALTER TABLE `return_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `return_head` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subgroup`
--

DROP TABLE IF EXISTS `subgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subgroup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT '',
  `name` varchar(45) DEFAULT '',
  `is_show` int(1) DEFAULT '1',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subgroup`
--

LOCK TABLES `subgroup` WRITE;
/*!40000 ALTER TABLE `subgroup` DISABLE KEYS */;
INSERT INTO `subgroup` VALUES (1,'A','ANGEL BEAR',1,'2015-05-20 00:00:00','2015-05-23 05:39:04',1,1),(2,'C','COIL',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(3,'E','EMBROSSED / CHECKERED PLATE',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(4,'F','FLAT BAR',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1);
/*!40000 ALTER TABLE `subgroup` ENABLE KEYS */;
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
  `is_show` int(1) DEFAULT '1',
  `is_active` int(1) DEFAULT '1',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '1',
  `last_modified_by` bigint(20) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'01','Lawrence Pena','superadmin','83703b5229462cb6bfaf425152e46a8c','09263188835',1,1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1),(3,'02','Gian Egamino','gegamino','9ee83e747fa1b06311eccb0af875076f','09263188835',1,1,'2015-05-22 04:56:20','2015-05-22 05:41:26',1,1),(4,'03','Rigel Pesit','rigel','f3e97dcba0a308db57b1aeaee5a43d4c','1234567',1,1,'2015-05-23 06:26:20','2015-05-24 02:43:24',1,1),(5,'04','Kryzza Garra','kryzza','f3e97dcba0a308db57b1aeaee5a43d4c','1234567',1,1,'2015-05-23 06:31:47','2015-05-24 03:23:32',1,4);
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permission`
--

LOCK TABLES `user_permission` WRITE;
/*!40000 ALTER TABLE `user_permission` DISABLE KEYS */;
INSERT INTO `user_permission` VALUES (1,1,1,100),(4,1,3,100),(8,2,3,100),(15,1,5,100),(16,2,5,100);
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

-- Dump completed on 2015-05-24 22:26:43

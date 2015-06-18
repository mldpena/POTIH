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
  `is_show` int(1) DEFAULT '1',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch`
--

LOCK TABLES `branch` WRITE;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` VALUES (1,'01','Manila',1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1),(2,'02','Makati',1,'2015-05-24 02:26:23','2015-05-24 02:34:10',1,1),(3,'03','Pasay',1,'2015-05-26 04:26:38','2015-05-26 04:26:38',1,1),(4,'04','Valenzuela',1,'2015-05-26 03:45:00','2015-05-26 03:45:00',1,1),(6,'05','Bambang',1,'2015-05-27 07:16:11','2015-05-27 07:16:11',1,1);
/*!40000 ALTER TABLE `branch` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`branch_AFTER_INSERT` AFTER INSERT ON `branch` 
FOR EACH ROW
BEGIN
	CALL process_initialize_branch_inventory(NEW.`id`);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `description` varchar(200) DEFAULT '',
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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`damage_detail_BEFORE_INSERT` BEFORE INSERT ON `damage_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'DAMAGE DETAIL');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`damage_detail_BEFORE_UPDATE` BEFORE UPDATE ON `damage_detail` 
FOR EACH ROW
BEGIN
	IF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'DAMAGE DETAIL');
        CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'DAMAGE DETAIL');
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := (NEW.`quantity` - OLD.`quantity`) * -1;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'DAMAGE DETAIL');
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`damage_detail_BEFORE_DELETE` BEFORE DELETE ON `damage_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'DAMAGE DETAIL');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`damage_head_BEFORE_UPDATE` BEFORE UPDATE ON `damage_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('DAMAGE HEAD',NEW.`id`);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `inventory_adjust`
--

DROP TABLE IF EXISTS `inventory_adjust`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_adjust` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `old_inventory` int(11) DEFAULT '0',
  `new_inventory` int(11) DEFAULT '0',
  `memo` varchar(250) DEFAULT '',
  `is_show` int(1) DEFAULT '1',
  `status` int(1) DEFAULT '0',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_adjust`
--

LOCK TABLES `inventory_adjust` WRITE;
/*!40000 ALTER TABLE `inventory_adjust` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_adjust` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`inventory_adjust_AFTER_INSERT` AFTER INSERT ON `inventory_adjust` FOR EACH ROW
BEGIN
	IF(NEW.`status` = 2) THEN
		SET @quantity := NEW.`new_inventory` - NEW.`old_inventory`;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@quantity,NEW.`id`,'INVENTORY ADJUST');
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`inventory_adjust_BEFORE_UPDATE` BEFORE UPDATE ON `inventory_adjust` FOR EACH ROW
BEGIN
	IF(NEW.`status` <> 1 AND NEW.`status` <> OLD.`status`) THEN
		IF(OLD.`status` IN(1,3) AND NEW.`status` = 2) THEN
			SET @quantity := NEW.`new_inventory` - NEW.`old_inventory`;
		ELSEIF(OLD.`status` = 2 AND NEW.`status` = 3) THEN
			SET @quantity := NEW.`old_inventory` - NEW.`new_inventory`;
        END IF;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@quantity,NEW.`id`,'INVENTORY ADJUST');
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `is_show` int(1) DEFAULT '1',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
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
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'BJ014L20','BI Tube 1/4\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:29:49','2015-05-26 12:29:49',1,1),(2,'BJ056L20','BI Tube 5/16\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:30:14','2015-05-26 12:30:14',1,1),(3,'BJ038A20','BI Tube 3/8\" (1.0mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:34:41','2015-05-26 12:34:41',1,1),(4,'BJ012C20','BI Tube 1/2\" (1.6mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:34:52','2015-05-26 12:34:52',1,1),(5,'BJ058B19','BI Tube 5/8\" (1.2mm) x 19 Ft.',1,6,6,1,'2015-05-26 12:35:11','2015-05-26 12:35:11',1,1),(6,'BJ058B20','BI Tube 5/8\" (1.2mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:35:21','2015-05-26 12:35:21',1,1),(7,'BC06004F','Hot Rolled COIL 6.0mm x 4 Ft.',1,6,2,1,'2015-05-26 12:38:46','2015-05-26 12:38:46',1,1),(8,'BC05004F','Hot Rolled COIL 5.0mm x 4 Ft. ',1,6,2,1,'2015-05-26 12:38:58','2015-05-26 12:38:58',1,1),(9,'BC04504F','Hot Rolled COIL 4.5mm x 4 Ft. ',1,6,2,1,'2015-05-26 12:39:18','2015-05-26 12:39:18',1,1),(10,'SS304CUT','SS-304  CUTTINGS',0,0,0,1,'2015-05-26 12:47:26','2015-05-26 12:47:26',1,1),(11,'SS316CUT','SS-316  CUTTINGS',0,0,0,1,'2015-05-26 12:47:36','2015-05-26 12:47:36',1,1),(12,'ALUMNCUT','ALUMINUM  CUTTING',0,0,0,1,'2015-05-26 12:50:35','2015-05-26 12:50:35',1,1),(13,'MATLABOR','LABOR ONLY, MAT.  FROM CUSTOMER',0,0,0,1,'2015-05-26 12:52:17','2015-05-26 12:52:17',1,1),(14,'COPPRCUT','COPPER  CUTTINGS',0,0,0,1,'2015-05-26 12:52:27','2015-05-26 12:52:27',1,1),(21,'AC112233','sample product',1,5,2,1,'2015-05-27 07:13:05','2015-05-27 08:44:40',1,1);
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
  `min_inv` int(7) DEFAULT '1',
  `max_inv` int(7) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_productid` (`product_id`),
  KEY `idx_productid_branchid` (`product_id`,`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_branch_inventory`
--

LOCK TABLES `product_branch_inventory` WRITE;
/*!40000 ALTER TABLE `product_branch_inventory` DISABLE KEYS */;
INSERT INTO `product_branch_inventory` VALUES (1,1,1,0,1,1),(2,2,1,0,1,1),(3,3,1,0,1,1),(4,1,2,0,1,1),(5,2,2,0,1,1),(6,3,2,0,1,1),(7,1,3,0,1,1),(8,2,3,0,1,1),(9,3,3,0,1,1),(10,1,4,0,1,1),(11,2,4,0,1,1),(12,3,4,0,1,1),(13,1,5,0,1,1),(14,2,5,0,1,1),(15,3,5,0,1,1),(16,1,6,0,1,1),(17,2,6,0,1,1),(18,3,6,0,1,1),(19,1,7,0,1,1),(20,2,7,0,1,1),(21,3,7,0,1,1),(22,1,8,0,1,1),(23,2,8,0,1,1),(24,3,8,0,1,1),(25,1,9,0,1,1),(26,2,9,0,1,1),(27,3,9,0,1,1),(28,1,10,0,1,1),(29,2,10,0,1,1),(30,3,10,0,1,1),(31,1,11,0,1,1),(32,2,11,0,1,1),(33,3,11,0,1,1),(34,1,12,0,1,1),(35,2,12,0,1,1),(36,3,12,0,1,1),(37,1,13,0,1,1),(38,2,13,0,1,1),(39,3,13,0,1,1),(40,1,14,0,1,1),(41,2,14,0,1,1),(42,3,14,0,1,1),(43,4,1,0,1,1),(44,4,2,0,1,1),(45,4,3,0,1,1),(46,4,4,0,1,1),(47,4,5,0,1,1),(48,4,6,0,1,1),(49,4,7,0,1,1),(50,4,8,0,1,1),(51,4,9,0,1,1),(52,4,10,0,1,1),(53,4,11,0,1,1),(54,4,12,0,1,1),(55,4,13,0,1,1),(56,4,14,0,1,1),(57,1,21,0,1,1),(58,2,21,0,1,1),(59,3,21,0,1,1),(60,4,21,0,1,1),(61,6,1,0,1,1),(62,6,2,0,1,1),(63,6,3,0,1,1),(64,6,4,0,1,1),(65,6,5,0,1,1),(66,6,6,0,1,1),(67,6,7,0,1,1),(68,6,8,0,1,1),(69,6,9,0,1,1),(70,6,10,0,1,1),(71,6,11,0,1,1),(72,6,12,0,1,1),(73,6,13,0,1,1),(74,6,14,0,1,1),(75,6,21,0,1,1);
/*!40000 ALTER TABLE `product_branch_inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_detail`
--

DROP TABLE IF EXISTS `purchase_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `description` varchar(200) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `recv_quantity` int(9) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_headid` (`headid`),
  KEY `idx_id_headid` (`id`,`headid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_detail`
--

LOCK TABLES `purchase_detail` WRITE;
/*!40000 ALTER TABLE `purchase_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_head`
--

DROP TABLE IF EXISTS `purchase_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_head` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reference_number` int(11) DEFAULT '0',
  `branch_id` bigint(20) DEFAULT '0',
  `for_branchid` bigint(20) DEFAULT '0',
  `entry_date` datetime DEFAULT NULL,
  `supplier` varchar(100) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `is_show` int(1) DEFAULT '1',
  `is_used` int(1) DEFAULT '0',
  `is_imported` int(1) DEFAULT '0',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_isshow_isused_forbranchid` (`is_show`,`is_used`,`for_branchid`),
  KEY `idx_id_isshow_isused_forbranchid` (`id`,`is_show`,`is_used`,`for_branchid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_head`
--

LOCK TABLES `purchase_head` WRITE;
/*!40000 ALTER TABLE `purchase_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_head` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_receive_detail`
--

DROP TABLE IF EXISTS `purchase_receive_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_receive_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `memo` varchar(150) DEFAULT '',
  `purchase_detail_id` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_headid` (`headid`),
  KEY `idx_purchasedetailid` (`purchase_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_receive_detail`
--

LOCK TABLES `purchase_receive_detail` WRITE;
/*!40000 ALTER TABLE `purchase_receive_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_receive_detail` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_receive_detail_BEFORE_INSERT` BEFORE INSERT ON `purchase_receive_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,NEW.`quantity`,NEW.`headid`,'RECEIVE DETAIL');
	CALL process_update_receive(NEW.`purchase_detail_id`,NEW.`quantity`,'RECEIVE DETAIL');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_receive_detail_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_receive_detail` 
FOR EACH ROW
BEGIN
	IF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,(-1 * OLD.`quantity`),OLD.`headid`,'RECEIVE DETAIL');
        CALL process_update_receive(OLD.`purchase_detail_id`,(-1 * OLD.`quantity`),'RECEIVE DETAIL');
        
        CALL process_compute_inventory_for_detail(NEW.`product_id`,NEW.`quantity`,NEW.`headid`,'RECEIVE DETAIL');
        CALL process_update_receive(NEW.`purchase_detail_id`,NEW.`quantity`,'RECEIVE DETAIL');
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := (NEW.`quantity` - OLD.`quantity`);
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'RECEIVE DETAIL');
		CALL process_update_receive(NEW.`purchase_detail_id`,@qty,'RECEIVE DETAIL');
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_receive_detail_BEFORE_DELETE` BEFORE DELETE ON `purchase_receive_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,(-1 * OLD.`quantity`),OLD.`headid`,'RECEIVE DETAIL');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `purchase_receive_head`
--

DROP TABLE IF EXISTS `purchase_receive_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_receive_head` (
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
  PRIMARY KEY (`id`),
  KEY `idx_id_isshow` (`is_show`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_receive_head`
--

LOCK TABLES `purchase_receive_head` WRITE;
/*!40000 ALTER TABLE `purchase_receive_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_receive_head` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_receive_head_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_receive_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('RECEIVE HEAD',NEW.`id`);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `purchase_return_detail`
--

DROP TABLE IF EXISTS `purchase_return_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_return_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `description` varchar(200) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_headid` (`headid`),
  KEY `idx_id_headid` (`id`,`headid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_return_detail`
--

LOCK TABLES `purchase_return_detail` WRITE;
/*!40000 ALTER TABLE `purchase_return_detail` DISABLE KEYS */;
INSERT INTO `purchase_return_detail` VALUES (11,1,1,13,'',''),(12,1,5,2,'',''),(13,2,5,14,'','');
/*!40000 ALTER TABLE `purchase_return_detail` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_return_detail_BEFORE_INSERT` BEFORE INSERT ON `purchase_return_detail`
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'PURCHASE RETURN DETAIL');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_return_detail_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_return_detail` 
FOR EACH ROW
BEGIN
	IF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'PURCHASE RETURN DETAIL');
        CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'PURCHASE RETURN DETAIL');
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := (NEW.`quantity` - OLD.`quantity`) * -1;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'PURCHASE RETURN DETAIL');
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_return_detail_BEFORE_DELETE` BEFORE DELETE ON `purchase_return_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'PURCHASE RETURN DETAIL');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `purchase_return_head`
--

DROP TABLE IF EXISTS `purchase_return_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_return_head` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reference_number` int(11) DEFAULT '0',
  `branch_id` bigint(20) DEFAULT '0',
  `entry_date` datetime DEFAULT NULL,
  `supplier` varchar(100) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `is_show` int(1) DEFAULT '1',
  `is_used` int(1) DEFAULT '0',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_return_head`
--

LOCK TABLES `purchase_return_head` WRITE;
/*!40000 ALTER TABLE `purchase_return_head` DISABLE KEYS */;
INSERT INTO `purchase_return_head` VALUES (1,100001,1,'2015-06-07 04:09:06','LAWRENCE','',0,1,1,1,'2015-06-07 11:44:39','2015-06-08 09:40:10'),(2,100001,1,'2015-06-18 02:56:00','','',1,1,1,1,'2015-06-18 14:55:17','2015-06-18 02:56:00');
/*!40000 ALTER TABLE `purchase_return_head` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_return_head_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_return_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('PURCHASE RETURN HEAD',NEW.`id`);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `description` varchar(45) DEFAULT '',
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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`return_detail_BEFORE_INSERT` BEFORE INSERT ON `return_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,NEW.`quantity`,NEW.`headid`,'RETURN DETAIL');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`return_detail_BEFORE_UPDATE` BEFORE UPDATE ON `return_detail` 
FOR EACH ROW
BEGIN
	IF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,(-1 * OLD.`quantity`),OLD.`headid`,'RETURN DETAIL');
        CALL process_compute_inventory_for_detail(NEW.`product_id`,NEW.`quantity`,NEW.`headid`,'RETURN DETAIL');
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := NEW.`quantity` - OLD.`quantity`;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'RETURN DETAIL');
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`return_detail_BEFORE_DELETE` BEFORE DELETE ON `return_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,(-1 * OLD.`quantity`),OLD.`headid`,'RETURN DETAIL');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`return_head_BEFORE_UPDATE` BEFORE UPDATE ON `return_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('RETURN HEAD',NEW.`id`);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `stock_delivery_detail`
--

DROP TABLE IF EXISTS `stock_delivery_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_delivery_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `description` varchar(200) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `is_for_branch` int(1) DEFAULT '0',
  `recv_quantity` int(9) DEFAULT '0',
  `request_detail_id` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_headid` (`headid`),
  KEY `idx_id_headid` (`id`,`headid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_delivery_detail`
--

LOCK TABLES `stock_delivery_detail` WRITE;
/*!40000 ALTER TABLE `stock_delivery_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_delivery_detail` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`stock_delivery_detail_BEFORE_UPDATE` BEFORE UPDATE ON `stock_delivery_detail` 
FOR EACH ROW
BEGIN
	IF (OLD.`recv_quantity` <> NEW.`recv_quantity`) THEN
		SET @qty := (NEW.`recv_quantity` - OLD.`recv_quantity`);
        IF(NEW.`is_for_branch` = 1) THEN
			SET @table_detail_name := 'TRANSFER DETAIL';
		ELSE
			SET @table_detail_name := 'CUSTOMER DETAIL';
            SET @qty := @qty * -1;
        END IF;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,@table_detail_name);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `stock_delivery_head`
--

DROP TABLE IF EXISTS `stock_delivery_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_delivery_head` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reference_number` int(11) DEFAULT '0',
  `branch_id` bigint(20) DEFAULT '0',
  `to_branchid` bigint(20) DEFAULT '0',
  `entry_date` datetime DEFAULT NULL,
  `supplier` varchar(100) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `is_show` int(1) DEFAULT '1',
  `is_used` int(1) DEFAULT '0',
  `delivery_type` int(1) DEFAULT '0',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_delivery_head`
--

LOCK TABLES `stock_delivery_head` WRITE;
/*!40000 ALTER TABLE `stock_delivery_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_delivery_head` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subgroup`
--

LOCK TABLES `subgroup` WRITE;
/*!40000 ALTER TABLE `subgroup` DISABLE KEYS */;
INSERT INTO `subgroup` VALUES (1,'A','ANGEL BEAR',1,'2015-05-20 00:00:00','2015-05-23 05:39:04',1,1),(2,'C','COIL',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(3,'E','EMBROSSED / CHECKERED PLATE',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(4,'F','FLAT BAR',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(5,'H','HEXAGON BAR',1,'2015-05-26 12:18:49','2015-05-26 12:18:49',1,1),(6,'J','ROUND TUBE',1,'2015-05-26 12:19:00','2015-05-26 12:19:00',1,1),(7,'K','SQUARE TUBE',1,'2015-05-26 12:19:12','2015-05-26 12:19:12',1,1),(8,'L','RECTANGULAR TUBE',1,'2015-05-26 12:19:26','2015-05-26 12:19:26',1,1),(9,'M','MESH - IMPORTED',1,'2015-05-26 12:19:38','2015-05-26 12:19:38',1,1),(10,'N','PIPE',1,'2015-05-26 12:19:48','2015-05-26 12:19:48',1,1),(11,'P','PERFORATED SHEET',1,'2015-05-26 12:20:07','2015-05-26 12:20:07',1,1),(12,'Q','SQUARE BAR',1,'2015-05-26 12:20:26','2015-05-26 12:20:26',1,1),(13,'R','ROUND BAR',1,'2015-05-26 12:20:41','2015-05-26 12:20:41',1,1),(14,'S','SHEETS AND PLATES - PLAIN',1,'2015-05-26 12:20:54','2015-05-26 12:20:54',1,1),(15,'U','WIRE',1,'2015-05-26 12:21:04','2015-05-26 12:21:04',1,1),(16,'V','WIELDING ROD / ELECTRODE',1,'2015-05-26 12:21:27','2015-05-26 12:21:27',1,1),(17,'W','WEDLED WIRE SCREEN - IMPORTED',1,'2015-05-26 12:21:47','2015-05-26 12:21:47',1,1),(18,'X','EXPANDED METAL',1,'2015-05-26 12:22:21','2015-05-26 12:22:21',1,1);
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
  `is_first_login` int(1) DEFAULT '0',
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
INSERT INTO `user` VALUES (1,'01','Lawrence Pena','superadmin','83703b5229462cb6bfaf425152e46a8c','09263188835',1,1,1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1),(3,'02','Gian Egamino','gegamino','f3e97dcba0a308db57b1aeaee5a43d4c','09263188835',1,0,0,'2015-05-22 04:56:20','2015-05-26 03:47:06',1,1),(5,'04','Kryzza Garra','kryzza','f3e97dcba0a308db57b1aeaee5a43d4c','09263188835',1,1,1,'2015-05-23 06:31:47','2015-05-26 03:43:31',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permission`
--

LOCK TABLES `user_permission` WRITE;
/*!40000 ALTER TABLE `user_permission` DISABLE KEYS */;
INSERT INTO `user_permission` VALUES (1,1,1,100),(17,1,5,100),(18,2,5,100),(21,1,3,100),(22,2,3,100);
/*!40000 ALTER TABLE `user_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouserelease_detail`
--

DROP TABLE IF EXISTS `warehouserelease_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouserelease_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `description` varchar(45) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `qty_released` varchar(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouserelease_detail`
--

LOCK TABLES `warehouserelease_detail` WRITE;
/*!40000 ALTER TABLE `warehouserelease_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouserelease_detail` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`warehouserelease_detail_AFTER_UPDATE` AFTER UPDATE ON `warehouserelease_detail` FOR EACH ROW
BEGIN
	IF (OLD.`qty_released` <> NEW.`qty_released`) THEN
		SET @qty := -1 * (NEW.`qty_released` - OLD.`qty_released`);
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'RELEASE DETAIL');
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `warehouserelease_head`
--

DROP TABLE IF EXISTS `warehouserelease_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouserelease_head` (
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
-- Dumping data for table `warehouserelease_head`
--

LOCK TABLES `warehouserelease_head` WRITE;
/*!40000 ALTER TABLE `warehouserelease_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouserelease_head` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`warehouserelease_head_BEFORE_UPDATE` BEFORE UPDATE ON `warehouserelease_head` FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('RELEASE HEAD',NEW.`id`);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping events for database 'dbs_hitop'
--

--
-- Dumping routines for database 'dbs_hitop'
--
/*!50003 DROP PROCEDURE IF EXISTS `process_compute_inventory_for_detail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `process_compute_inventory_for_detail`(
	IN `product_id_d` BIGINT,
    IN `qty_d` INT(7),
    IN `head_id_d` BIGINT,
    IN `table_name_d` VARCHAR(50)
)
BEGIN
	SET @branch_id := 0;
    
	CASE 
		WHEN table_name_d = 'RETURN DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM return_head WHERE `id` = head_id_d;
		WHEN table_name_d = 'DAMAGE DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM damage_head WHERE `id` = head_id_d;
		WHEN table_name_d = 'RECEIVE DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM purchase_receive_head WHERE `id` = head_id_d;
		WHEN table_name_d = 'PURCHASE RETURN DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM purchase_return_head WHERE `id` = head_id_d;
		WHEN table_name_d = 'CUSTOMER DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM stock_delivery_head WHERE `id` = head_id_d;
		WHEN table_name_d = 'RELEASE DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM warehouserelease_head WHERE `id` = head_id_d;
        WHEN table_name_d = 'INVENTORY ADJUST' THEN
			SELECT `branch_id` INTO @branch_id FROM inventory_adjust WHERE `id` = head_id_d;
		WHEN table_name_d = 'TRANSFER DETAIL' THEN
			SELECT `to_branchid` INTO @branch_id FROM stock_delivery_head WHERE `id` = head_id_d;
			SET @from_branch_id := 0;
            SELECT `branch_id` INTO @from_branch_id FROM stock_delivery_head WHERE `id` = head_id_d;
            
            UPDATE product_branch_inventory
				SET `inventory` = `inventory` + (qty_d * -1)
				WHERE `branch_id` = @from_branch_id AND `product_id` = product_id_d;
	END CASE;
    
    UPDATE product_branch_inventory
		SET `inventory` = `inventory` + qty_d
        WHERE `branch_id` = @branch_id AND `product_id` = product_id_d;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `process_compute_inventory_for_head` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `process_compute_inventory_for_head`(
	IN `table_name_d` VARCHAR(50),
    IN `head_id_d` BIGINT
)
BEGIN
	DECLARE cursor_product_id BIGINT;
	DECLARE cursor_quantity INT;
    DECLARE cursor_other_id BIGINT;
	DECLARE done INT DEFAULT FALSE;
    DECLARE cursor_return CURSOR FOR SELECT `product_id`, `quantity` FROM return_detail WHERE `headid` = head_id_d;
    DECLARE cursor_damage CURSOR FOR SELECT `product_id`, `quantity` FROM damage_detail WHERE `headid` = head_id_d;
    DECLARE cursor_release CURSOR FOR SELECT `product_id`, `quantity` FROM warehouserelease_detail WHERE `headid` = head_id_d;
    DECLARE cursor_purchase_return CURSOR FOR SELECT `product_id`, `quantity` FROM purchase_return_detail WHERE `headid` = head_id_d;
    DECLARE cursor_received CURSOR FOR SELECT `product_id`, `quantity`, `purchase_detail_id` FROM purchase_receive_detail WHERE `headid` = head_id_d;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;		
	
    
    IF (table_name_d = 'RETURN HEAD') THEN
		OPEN cursor_return;
	ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
		OPEN cursor_damage;
	ELSEIF (table_name_d = 'RELEASE HEAD') THEN
		OPEN cursor_release;
	ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
		OPEN cursor_received;
	ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
		OPEN cursor_purchase_return;
	END IF;
        
	read_loop: LOOP
		IF (table_name_d = 'RETURN HEAD') THEN
			FETCH cursor_return INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
			FETCH cursor_damage INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'RELEASE HEAD') THEN
			FETCH cursor_release INTO cursor_product_id, cursor_quantity;
        ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
			FETCH cursor_received INTO cursor_product_id, cursor_quantity, cursor_other_id;
		ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
			FETCH cursor_purchase_return INTO cursor_product_id, cursor_quantity;
		END IF;
        
		IF done THEN
			LEAVE read_loop;
		END IF;
        
			IF (table_name_d = 'RETURN HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,(-1 * cursor_quantity),head_id_d,'RETURN DETAIL');
			ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,'DAMAGE DETAIL');
			ELSEIF (table_name_d = 'RELEASE HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,'RELEASE DETAIL');
            ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,(-1 * cursor_quantity),head_id_d,'RECEIVE DETAIL');
				CALL process_update_receive(cursor_other_id,(-1 * cursor_quantity),'RECEIVE DETAIL');
			ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,'PURCHASE RETURN DETAIL');
            END IF;
	END LOOP;
    
    IF (table_name_d = 'RETURN HEAD') THEN
		CLOSE cursor_return;
	ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
		CLOSE cursor_damage;
	ELSEIF (table_name_d = 'RELEASE HEAD') THEN
		CLOSE cursor_release;
    ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
		CLOSE cursor_received;
	ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
		CLOSE cursor_purchase_return;
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `process_initialize_branch_inventory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `process_initialize_branch_inventory`(
    IN `branch_id_d` BIGINT
)
BEGIN
	IF(branch_id_d <> 0 ) THEN
		SET @product_count := 0;
        SELECT COUNT(*) INTO @product_count FROM product;
        IF(@product_count <> 0) THEN
			INSERT INTO product_branch_inventory(`branch_id`,`product_id`)
            SELECT branch_id_d, `id` FROM product;
        END IF;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `process_update_receive` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `process_update_receive`(
	IN `detail_id_d` BIGINT,
    IN `quantity_d` INT,
    IN `table_name_d` VARCHAR(50)
)
BEGIN
	UPDATE `purchase_detail`
		SET `recv_quantity` = `recv_quantity` + quantity_d
        WHERE `id` = detail_id_d;
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

-- Dump completed on 2015-06-18 16:47:46

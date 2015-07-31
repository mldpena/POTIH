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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch`
--

LOCK TABLES `branch` WRITE;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` VALUES (1,'01','Manila',1,'2015-05-19 00:00:00','2015-06-30 04:21:45',1,1),(2,'02','Makati',1,'2015-05-24 02:26:23','2015-06-30 04:20:08',1,1),(3,'03','Pasay',1,'2015-05-26 04:26:38','2015-05-26 04:26:38',1,1),(4,'04','Valenzuela',1,'2015-05-26 03:45:00','2015-05-26 03:45:00',1,1),(6,'05','Bambang',1,'2015-05-27 07:16:11','2015-05-27 07:16:11',1,1),(7,'06','Malabon',1,'2015-07-08 09:53:12','2015-07-08 09:53:12',5,5),(8,'07','SAMPLE',0,'2015-07-22 12:16:29','2015-07-22 12:16:34',1,1);
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
-- Table structure for table `daily_transaction_summary`
--

DROP TABLE IF EXISTS `daily_transaction_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_transaction_summary` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) DEFAULT '0',
  `branch_id` bigint(20) DEFAULT '0',
  `date` date DEFAULT '0000-00-00',
  `purchase_receive` int(11) DEFAULT '0',
  `customer_return` int(11) DEFAULT '0',
  `stock_receive` int(11) DEFAULT '0',
  `adjust_increase` int(11) DEFAULT '0',
  `damage` int(11) DEFAULT '0',
  `purchase_return` int(11) DEFAULT '0',
  `stock_delivery` int(11) DEFAULT '0',
  `customer_delivery` int(11) DEFAULT '0',
  `adjust_decrease` int(11) DEFAULT '0',
  `warehouse_release` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_transaction_summary`
--

LOCK TABLES `daily_transaction_summary` WRITE;
/*!40000 ALTER TABLE `daily_transaction_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `daily_transaction_summary` ENABLE KEYS */;
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
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('DAMAGE HEAD',OLD.`id`,1);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`damage_head_AFTER_UPDATE` AFTER UPDATE ON `damage_head` 
FOR EACH ROW
BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('DAMAGE HEAD',NEW.`id`,-1);
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
F:BEGIN
	IF(NEW.`is_show` <> 1 AND OLD.`status` = 1) THEN
		SET @quantity := (OLD.`new_inventory` - OLD.`old_inventory`) * -1;
        
		CALL process_compute_inventory_for_detail(OLD.`product_id`,@quantity,OLD.`id`,'INVENTORY ADJUST');
	ELSEIF(NEW.`status` <> 1 AND NEW.`status` <> OLD.`status`) THEN
		IF(OLD.`status` IN(1,3) AND NEW.`status` = 2) THEN
			SET @quantity := NEW.`new_inventory` - NEW.`old_inventory`;
		ELSEIF(OLD.`status` = 2 AND NEW.`status` = 3) THEN
			SET @quantity := NEW.`old_inventory` - NEW.`new_inventory`;
		ELSEIF(OLD.`status` = 1 AND NEW.`status` = 3) THEN
			LEAVE F;
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material_type`
--

LOCK TABLES `material_type` WRITE;
/*!40000 ALTER TABLE `material_type` DISABLE KEYS */;
INSERT INTO `material_type` VALUES (1,'T','TIGER BRONZE',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(2,'S','STAINLESS STEEL',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(3,'R','BRASS',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(4,'Z','ZINGA',0,'2015-05-20 00:00:00','2015-06-30 02:07:20',1,1),(5,'A','ALUMINUM 2',1,'2015-05-20 00:00:00','2015-07-07 03:20:04',1,1),(6,'B','BI / BLACK IRON',1,'2015-05-20 00:00:00','2015-06-30 02:06:02',1,1),(7,'G','GALVANIZED',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(8,'Z','ZINGA',1,'2015-06-30 02:27:15','2015-06-30 02:27:15',1,1),(9,'L','LEMON',1,'2015-06-30 02:32:46','2015-06-30 02:32:46',1,1),(10,'K','KINAMON',1,'2015-06-30 02:32:55','2015-06-30 02:32:55',1,1),(11,'X','XENO',1,'2015-06-30 02:33:36','2015-06-30 02:33:36',1,1),(12,'P','PENTA',1,'2015-06-30 02:34:07','2015-06-30 02:34:07',1,1),(13,'H','HENTA',1,'2015-06-30 02:35:37','2015-06-30 02:35:37',1,1);
/*!40000 ALTER TABLE `material_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pickup_summary_detail`
--

DROP TABLE IF EXISTS `pickup_summary_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pickup_summary_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `release_head_id` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_headid` (`headid`),
  KEY `idx_id_headid` (`id`,`headid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pickup_summary_detail`
--

LOCK TABLES `pickup_summary_detail` WRITE;
/*!40000 ALTER TABLE `pickup_summary_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `pickup_summary_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pickup_summary_head`
--

DROP TABLE IF EXISTS `pickup_summary_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pickup_summary_head` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reference_number` int(11) DEFAULT '0',
  `branch_id` bigint(20) DEFAULT '0',
  `entry_date` datetime DEFAULT NULL,
  `is_show` int(1) DEFAULT '1',
  `created_by` bigint(20) DEFAULT '0',
  `last_modified_by` bigint(20) DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_isshow_isused_forbranchid` (`is_show`),
  KEY `idx_id_isshow_isused_forbranchid` (`id`,`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pickup_summary_head`
--

LOCK TABLES `pickup_summary_head` WRITE;
/*!40000 ALTER TABLE `pickup_summary_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `pickup_summary_head` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'BJ014L20','BI Tube 1/4\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:29:49','2015-05-26 12:29:49',1,1),(2,'BJ056L20','BI Tube 5/16\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:30:14','2015-05-26 12:30:14',1,1),(3,'BJ038A20','BI Tube 3/8\" (1.0mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:34:41','2015-05-26 12:34:41',1,1),(4,'BJ012C20','BI Tube 1/2\" (1.6mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:34:52','2015-05-26 12:34:52',1,1),(5,'BJ058B19','BI Tube 5/8\" (1.2mm) x 19 Ft.',1,6,6,1,'2015-07-26 10:27:57','2015-05-26 12:35:11',1,1),(6,'BJ058B20','BI Tube 5/8\" (1.2mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:35:21','2015-05-26 12:35:21',1,1),(7,'BC06004F','Hot Rolled COIL 6.0mm x 4 Ft.',1,6,2,1,'2015-05-26 12:38:46','2015-06-30 12:44:43',1,1),(8,'BC05004S','Hot Rolled COIL 5.0mm x 4 Ft. ',1,6,2,1,'2015-05-26 12:38:58','2015-06-30 12:54:40',1,1),(9,'SE112233','Hot Rolled COIL 4.5mm x 4 Ft. ',1,2,3,1,'2015-05-26 12:39:18','2015-06-30 01:29:35',1,1),(10,'SS304CUT','SS-304  CUTTINGS',0,0,0,1,'2015-05-26 12:47:26','2015-05-26 12:47:26',1,1),(11,'SS316CUT','SS-316  CUTTINGSS',0,0,0,1,'2015-07-24 12:01:51','2015-05-26 12:47:36',1,1),(12,'ALUMNCUT','ALUMINUM  CUTTING',0,0,0,1,'2015-05-26 12:50:35','2015-05-26 12:50:35',1,1),(13,'MATLABOR','LABOR ONLY, MAT.  FROM CUSTOMER',0,0,0,1,'2015-05-26 12:52:17','2015-05-26 12:52:17',1,1),(14,'COPPRCUT','COPPER  CUTTINGS',0,0,0,1,'2015-05-26 12:52:27','2015-05-26 12:52:27',1,1),(22,'AE123456','ALU EMBRO',1,5,3,1,'2015-06-30 02:26:00','2015-07-06 06:01:10',1,1),(23,'AL123123','SAMPLE',1,5,8,0,'2015-07-08 10:01:41','2015-07-21 04:16:01',5,1),(28,'SS231223','SAMPLE FOR NEW STRUCTURE',1,2,14,1,'2015-07-21 02:11:32','0000-00-00 00:00:00',1,0),(31,'SS223344','SAMPLE FOR IMPORT',1,2,14,0,'2015-07-21 07:48:16','2015-07-23 04:13:49',1,1),(32,'AC123131','SAMPLE FOR TESTING',1,5,2,1,'2015-07-31 06:58:02','0000-00-00 00:00:00',1,0),(33,'SS223344','SAMPLE FOR IMPORT',1,2,14,0,'2015-07-23 04:18:18','2015-07-23 04:38:59',1,1),(34,'SS223344','SAMPLE FOR IMPORT',1,2,14,0,'2015-07-23 04:39:10','2015-07-23 04:57:53',1,1),(35,'SS223344','SAMPLE FOR IMPORT',1,2,14,0,'2015-07-23 04:58:11','2015-07-23 04:59:36',1,1),(36,'SS223344','SAMPLE FOR IMPORT',1,2,14,0,'2015-07-23 04:59:55','2015-07-23 05:00:23',1,1),(37,'SS223344','SAMPLE FOR IMPORT',1,2,14,0,'2015-07-23 05:00:50','2015-07-23 06:00:48',1,1),(38,'SS223344','SAMPLE FOR IMPORT',1,2,14,0,'2015-07-23 06:00:53','2015-07-28 09:58:15',1,1),(39,'SS554433','SAMPLE FOR ME',1,2,14,1,'2015-07-24 12:02:15','0000-00-00 00:00:00',1,0),(40,'SS223344','SAMPLE FOR IMPORT',1,2,14,0,'2015-07-28 09:58:40','2015-07-28 09:59:04',1,1),(41,'SS223344','SAMPLE FOR IMPORT',1,2,14,1,'2015-07-28 10:09:11','0000-00-00 00:00:00',1,0);
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
  `min_inv` int(7) DEFAULT '0',
  `max_inv` int(7) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_productid` (`product_id`),
  KEY `idx_productid_branchid` (`product_id`,`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_branch_inventory`
--

LOCK TABLES `product_branch_inventory` WRITE;
/*!40000 ALTER TABLE `product_branch_inventory` DISABLE KEYS */;
INSERT INTO `product_branch_inventory` VALUES (1,1,1,0,0,0),(2,2,1,0,0,0),(3,3,1,0,0,0),(4,1,2,0,0,0),(5,2,2,0,0,0),(6,3,2,0,0,0),(7,1,3,0,0,0),(8,2,3,0,0,0),(9,3,3,0,0,0),(10,1,4,0,0,0),(11,2,4,0,0,0),(12,3,4,0,0,0),(13,1,5,0,0,0),(14,2,5,0,0,0),(15,3,5,0,0,0),(16,1,6,0,0,0),(17,2,6,0,0,0),(18,3,6,0,0,0),(19,1,7,0,0,0),(20,2,7,0,0,0),(21,3,7,0,0,0),(22,1,8,0,0,0),(23,2,8,0,0,0),(24,3,8,0,0,0),(25,1,9,0,0,0),(26,2,9,0,0,0),(27,3,9,0,0,0),(28,1,10,0,0,0),(29,2,10,0,0,0),(30,3,10,0,0,0),(31,1,11,0,0,0),(32,2,11,0,0,0),(33,3,11,0,0,0),(34,1,12,0,0,0),(35,2,12,0,0,0),(36,3,12,0,0,0),(37,1,13,0,0,0),(38,2,13,0,0,0),(39,3,13,0,0,0),(40,1,14,0,0,0),(41,2,14,0,0,0),(42,3,14,0,0,0),(43,4,1,0,0,0),(44,4,2,0,0,0),(45,4,3,0,0,0),(46,4,4,0,0,0),(47,4,5,0,0,0),(48,4,6,0,0,0),(49,4,7,0,0,0),(50,4,8,0,0,0),(51,4,9,0,0,0),(52,4,10,0,0,0),(53,4,11,0,0,0),(54,4,12,0,0,0),(55,4,13,0,0,0),(56,4,14,0,0,0),(61,6,1,0,0,0),(62,6,2,0,0,0),(63,6,3,0,0,0),(64,6,4,0,0,0),(65,6,5,0,0,0),(66,6,6,0,0,0),(67,6,7,0,0,0),(68,6,8,0,0,0),(69,6,9,0,0,0),(70,6,10,0,0,0),(71,6,11,0,0,0),(72,6,12,0,0,0),(73,6,13,0,0,0),(74,6,14,0,0,0),(76,1,22,0,0,0),(77,2,22,0,0,0),(78,3,22,0,0,0),(79,4,22,0,0,0),(80,6,22,0,0,0),(81,7,1,0,0,0),(82,7,2,0,0,0),(83,7,3,0,0,0),(84,7,4,0,0,0),(85,7,5,0,0,0),(86,7,6,0,0,0),(87,7,7,0,0,0),(88,7,8,0,0,0),(89,7,9,0,0,0),(90,7,10,0,0,0),(91,7,11,0,0,0),(92,7,12,0,0,0),(93,7,13,0,0,0),(94,7,14,0,0,0),(95,7,22,0,0,0),(96,1,23,0,0,0),(97,2,23,0,0,0),(98,3,23,0,0,0),(99,4,23,0,0,0),(100,6,23,0,0,0),(101,7,23,0,0,0),(126,1,28,0,0,0),(127,2,28,0,0,0),(128,3,28,0,0,0),(129,4,28,0,0,0),(130,6,28,0,0,0),(131,7,28,0,0,0),(144,1,31,0,0,0),(145,3,31,0,0,0),(146,2,31,0,0,0),(147,4,31,0,0,0),(148,6,31,0,0,0),(149,7,31,0,0,0),(150,8,1,0,0,0),(151,8,2,0,0,0),(152,8,3,0,0,0),(153,8,4,0,0,0),(154,8,5,0,0,0),(155,8,6,0,0,0),(156,8,7,0,0,0),(157,8,8,0,0,0),(158,8,9,0,0,0),(159,8,10,0,0,0),(160,8,11,0,0,0),(161,8,12,0,0,0),(162,8,13,0,0,0),(163,8,14,0,0,0),(164,8,22,0,0,0),(165,8,23,0,0,0),(166,8,28,0,0,0),(167,8,31,0,0,0),(168,1,32,0,0,0),(169,2,32,0,0,0),(170,3,32,0,0,0),(171,4,32,0,0,0),(172,6,32,0,0,0),(173,7,32,0,0,0),(174,1,33,0,0,0),(175,3,33,0,0,0),(176,2,33,0,0,0),(177,4,33,0,0,0),(178,6,33,0,0,0),(179,7,33,0,0,0),(180,1,34,0,0,0),(181,3,34,0,0,0),(182,2,34,0,0,0),(183,4,34,0,0,0),(184,6,34,0,0,0),(185,7,34,0,0,0),(186,1,35,0,0,0),(187,3,35,0,0,0),(188,2,35,0,0,0),(189,4,35,0,0,0),(190,6,35,0,0,0),(191,7,35,0,0,0),(192,1,36,0,0,0),(193,3,36,0,0,0),(194,2,36,0,0,0),(195,4,36,0,0,0),(196,6,36,0,0,0),(197,7,36,0,0,0),(198,1,37,0,0,0),(199,3,37,0,0,0),(200,2,37,0,0,0),(201,4,37,0,0,0),(202,6,37,0,0,0),(203,7,37,0,0,0),(204,1,38,0,0,0),(205,3,38,0,0,0),(206,2,38,0,0,0),(207,4,38,0,0,0),(208,6,38,0,0,0),(209,7,38,0,0,0),(210,1,39,0,0,0),(211,2,39,0,0,0),(212,3,39,0,0,0),(213,4,39,0,0,0),(214,6,39,0,0,0),(215,7,39,0,0,0),(216,1,40,0,0,0),(217,3,40,0,0,0),(218,2,40,0,0,0),(219,4,40,0,0,0),(220,6,40,0,0,0),(221,7,40,0,0,0),(222,1,41,0,0,0),(223,3,41,0,0,0),(224,2,41,0,0,0),(225,4,41,0,0,0),(226,6,41,0,0,0),(227,7,41,0,0,0);
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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_head_BEFORE_INSERT` BEFORE INSERT ON `purchase_head` FOR EACH ROW
BEGIN
	CALL process_insert_new_name(NEW.`supplier`,2);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_head_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_head` FOR EACH ROW
BEGIN
	IF(LOWER(OLD.`supplier`) <> LOWER(NEW.`supplier`)) THEN
		DELETE FROM `recent_name` WHERE LOWER(`name`) = LOWER(OLD.`supplier`) AND `type` = 2;
        CALL process_insert_new_name(NEW.`supplier`,2);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `receive_memo` varchar(50) DEFAULT '',
  `received_by` varchar(50) DEFAULT '',
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
    CALL process_update_receive(OLD.`purchase_detail_id`,(-1) * OLD.`quantity`,'RECEIVE DETAIL');
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
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('RECEIVE HEAD',OLD.`id`,1);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_receive_head_AFTER_UPDATE` AFTER UPDATE ON `purchase_receive_head` 
FOR EACH ROW
BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('RECEIVE HEAD',NEW.`id`,-1);
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_return_detail`
--

LOCK TABLES `purchase_return_detail` WRITE;
/*!40000 ALTER TABLE `purchase_return_detail` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_return_head`
--

LOCK TABLES `purchase_return_head` WRITE;
/*!40000 ALTER TABLE `purchase_return_head` DISABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_return_head_BEFORE_INSERT` BEFORE INSERT ON `purchase_return_head` FOR EACH ROW
BEGIN
	CALL process_insert_new_name(NEW.`supplier`,2);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_return_head_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_return_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('PURCHASE RETURN HEAD',NEW.`id`);
    END IF;
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('PURCHASE RETURN HEAD',OLD.`id`,1);
    END IF;
    
    IF(LOWER(OLD.`supplier`) <> LOWER(NEW.`supplier`)) THEN
		DELETE FROM `recent_name` WHERE LOWER(`name`) = LOWER(OLD.`supplier`) AND `type` = 2;
        CALL process_insert_new_name(NEW.`supplier`,2);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_return_head_AFTER_UPDATE` AFTER UPDATE ON `purchase_return_head` 
FOR EACH ROW
BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('PURCHASE RETURN HEAD',OLD.`id`,-1);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `recent_name`
--

DROP TABLE IF EXISTS `recent_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recent_name` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `type` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recent_name`
--

LOCK TABLES `recent_name` WRITE;
/*!40000 ALTER TABLE `recent_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `recent_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `release_detail`
--

DROP TABLE IF EXISTS `release_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `release_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `release_order_detail_id` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `release_detail`
--

LOCK TABLES `release_detail` WRITE;
/*!40000 ALTER TABLE `release_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `release_detail` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`release_detail_BEFORE_INSERT` BEFORE INSERT ON `release_detail`
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'RELEASE DETAIL');
	CALL process_update_receive(NEW.`release_order_detail_id`,NEW.`quantity`,'RELEASE DETAIL');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`release_detail_BEFORE_UPDATE` BEFORE UPDATE ON `release_detail` 
FOR EACH ROW
BEGIN
	IF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'RELEASE DETAIL');
        CALL process_update_receive(OLD.`release_order_detail_id`,(-1 * OLD.`quantity`),'RELEASE DETAIL');
        
        CALL process_compute_inventory_for_detail(NEW.`product_id`,( -1 * NEW.`quantity`),NEW.`headid`,'RELEASE DETAIL');
        CALL process_update_receive(NEW.`release_order_detail_id`,NEW.`quantity`,'RELEASE DETAIL');
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := (NEW.`quantity` - OLD.`quantity`);
		CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * @qty),NEW.`headid`,'RELEASE DETAIL');
		CALL process_update_receive(NEW.`release_order_detail_id`,@qty,'RELEASE DETAIL');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`release_detail_BEFORE_DELETE` BEFORE DELETE ON `release_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'RELEASE DETAIL');
    CALL process_update_receive(OLD.`release_order_detail_id`,(-1) * OLD.`quantity`,'RELEASE DETAIL');
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `release_head`
--

DROP TABLE IF EXISTS `release_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `release_head` (
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
-- Dumping data for table `release_head`
--

LOCK TABLES `release_head` WRITE;
/*!40000 ALTER TABLE `release_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `release_head` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`release_head_BEFORE_UPDATE` BEFORE UPDATE ON `release_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('RELEASE HEAD',NEW.`id`);
    END IF;
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('RELEASE HEAD',OLD.`id`,1);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`release_head_AFTER_UPDATE` AFTER UPDATE ON `release_head` 
FOR EACH ROW
BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('RELEASE HEAD',NEW.`id`,-1);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `release_order_detail`
--

DROP TABLE IF EXISTS `release_order_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `release_order_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `description` varchar(200) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `qty_released` int(9) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_headid` (`headid`),
  KEY `idx_id_headid` (`id`,`headid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `release_order_detail`
--

LOCK TABLES `release_order_detail` WRITE;
/*!40000 ALTER TABLE `release_order_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `release_order_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `release_order_head`
--

DROP TABLE IF EXISTS `release_order_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `release_order_head` (
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
  PRIMARY KEY (`id`),
  KEY `idx_isshow_isused_forbranchid` (`is_show`,`is_used`),
  KEY `idx_id_isshow_isused_forbranchid` (`id`,`is_show`,`is_used`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `release_order_head`
--

LOCK TABLES `release_order_head` WRITE;
/*!40000 ALTER TABLE `release_order_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `release_order_head` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`release_order_head_BEFORE_INSERT` BEFORE INSERT ON `release_order_head` 
FOR EACH ROW
BEGIN
	CALL process_insert_new_name(NEW.`customer`,1);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`release_order_head_BEFORE_UPDATE` BEFORE UPDATE ON `release_order_head` 
FOR EACH ROW
BEGIN
	IF(LOWER(OLD.`customer`) <> LOWER(NEW.`customer`)) THEN
		DELETE FROM `recent_name` WHERE LOWER(`name`) = LOWER(OLD.`customer`) AND `type` = 1;
        CALL process_insert_new_name(NEW.`customer`,1);
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
  `received_by` varchar(50) DEFAULT '',
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
	CALL process_compute_inventory_for_detail(NEW.`product_id`,NEW.`quantity`,NEW.`headid`,'CUSTOMER RETURN DETAIL');
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
		CALL process_compute_inventory_for_detail(OLD.`product_id`,(-1 * OLD.`quantity`),OLD.`headid`,'CUSTOMER RETURN DETAIL');
        CALL process_compute_inventory_for_detail(NEW.`product_id`,NEW.`quantity`,NEW.`headid`,'CUSTOMER RETURN DETAIL');
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := NEW.`quantity` - OLD.`quantity`;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'CUSTOMER RETURN DETAIL');
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
	CALL process_compute_inventory_for_detail(OLD.`product_id`,(-1 * OLD.`quantity`),OLD.`headid`,'CUSTOMER RETURN DETAIL');
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
  `received_by` varchar(45) DEFAULT '',
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`return_head_BEFORE_INSERT` BEFORE INSERT ON `return_head` FOR EACH ROW
BEGIN
	CALL process_insert_new_name(NEW.`customer`,1);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`return_head_BEFORE_UPDATE` BEFORE UPDATE ON `return_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('CUSTOMER RETURN HEAD',NEW.`id`);
    END IF;
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('CUSTOMER RETURN HEAD',OLD.`id`,1);
    END IF;
    
    IF(LOWER(OLD.`customer`) <> LOWER(NEW.`customer`)) THEN
		DELETE FROM `recent_name` WHERE LOWER(`name`) = LOWER(OLD.`customer`) AND `type` = 1;
        CALL process_insert_new_name(NEW.`customer`,1);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`return_head_AFTER_UPDATE` AFTER UPDATE ON `return_head` 
FOR EACH ROW
BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('CUSTOMER RETURN HEAD',NEW.`id`,-1);
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
  `receive_memo` varchar(120) DEFAULT '',
  `received_by` varchar(50) DEFAULT '',
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`stock_delivery_detail_BEFORE_INSERT` BEFORE INSERT ON `stock_delivery_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'DELIVERY DETAIL');
	
	IF(NEW.`request_detail_id` <> 0) THEN
		CALL process_update_receive(NEW.`request_detail_id`,NEW.`quantity`,'REQUEST DETAIL');
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`stock_delivery_detail_BEFORE_UPDATE` BEFORE UPDATE ON `stock_delivery_detail` 
FOR EACH ROW
BEGIN
	IF (OLD.`recv_quantity` <> NEW.`recv_quantity`) THEN
    
		SET @qty := (NEW.`recv_quantity` - OLD.`recv_quantity`);
        
        IF(NEW.`is_for_branch` = 1) THEN
			SET @table_detail_name := 'TRANSFER DETAIL';
        END IF;
        
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,@table_detail_name);
        
    ELSEIF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'DELIVERY DETAIL');
		CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'DELIVERY DETAIL');
        
        IF(OLD.`request_detail_id` <> 0) THEN
			CALL process_update_receive(OLD.`request_detail_id`,(-1 * OLD.`quantity`),'REQUEST DETAIL');
        END IF;
        
	ELSEIF (OLD.`quantity` <> NEW.`quantity` AND NEW.`is_for_branch` = 1) THEN
		SET @qty := (NEW.`quantity` - OLD.`quantity`) * -1;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'DELIVERY DETAIL');
        
        IF(NEW.`request_detail_id` <> 0) THEN
			CALL process_update_receive(NEW.`request_detail_id`,@qty * -1,'REQUEST DETAIL');
		END IF;
        
	ELSEIF(OLD.`is_for_branch` <> NEW.`is_for_branch`) THEN
		IF(OLD.`request_detail_id` <> 0) THEN
			CALL process_update_receive(NEW.`request_detail_id`,(-1 * OLD.`quantity`),'REQUEST DETAIL');
		END IF;
        
		IF(NEW.`request_detail_id` <> 0) THEN
			CALL process_update_receive(NEW.`request_detail_id`,NEW.`quantity`,'REQUEST DETAIL');
		END IF;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`stock_delivery_detail_BEFORE_DELETE` BEFORE DELETE ON `stock_delivery_detail` 
FOR EACH ROW
BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'DELIVERY DETAIL');
	
	IF(OLD.`request_detail_id` <> 0) THEN
		CALL process_update_receive(OLD.`request_detail_id`,(-1 * OLD.`quantity`),'REQUEST DETAIL');
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
  `delivery_receive_date` datetime DEFAULT '0000-00-00 00:00:00',
  `customer_receive_date` datetime DEFAULT '0000-00-00 00:00:00',
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
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`stock_delivery_head_BEFORE_UPDATE` BEFORE UPDATE ON `stock_delivery_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('DELIVERY HEAD',NEW.`id`);
    END IF;
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('DELIVERY HEAD',OLD.`id`,1);
    END IF;
    
    IF(DATE(OLD.`delivery_receive_date`) <> DATE(NEW.`delivery_receive_date`)) THEN
		CALL process_recompute_transaction_summary('DELIVERY RECEIVE HEAD',OLD.`id`,1);
    END IF;
    
    IF(DATE(OLD.`customer_receive_date`) <> DATE(NEW.`customer_receive_date`)) THEN
		CALL process_recompute_transaction_summary('CUSTOMER RECEIVE HEAD',OLD.`id`,1);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`stock_delivery_head_AFTER_UPDATE` AFTER UPDATE ON `stock_delivery_head` 
FOR EACH ROW
BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('DELIVERY HEAD',NEW.`id`,-1);
    END IF;
    
    IF(DATE(OLD.`delivery_receive_date`) <> DATE(NEW.`delivery_receive_date`)) THEN
		CALL process_recompute_transaction_summary('DELIVERY RECEIVE HEAD',NEW.`id`,-1);
    END IF;
    
    IF(DATE(OLD.`customer_receive_date`) <> DATE(NEW.`customer_receive_date`)) THEN
		CALL process_recompute_transaction_summary('CUSTOMER RECEIVE HEAD',NEW.`id`,-1);
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `stock_request_detail`
--

DROP TABLE IF EXISTS `stock_request_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_request_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `headid` bigint(20) DEFAULT '0',
  `quantity` int(9) DEFAULT '0',
  `product_id` bigint(20) DEFAULT '0',
  `description` varchar(200) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `qty_delivered` int(9) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_headid` (`headid`),
  KEY `idx_id_headid` (`id`,`headid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_request_detail`
--

LOCK TABLES `stock_request_detail` WRITE;
/*!40000 ALTER TABLE `stock_request_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_request_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_request_head`
--

DROP TABLE IF EXISTS `stock_request_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_request_head` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reference_number` int(11) DEFAULT '0',
  `branch_id` bigint(20) DEFAULT '0',
  `request_to_branchid` bigint(20) DEFAULT '0',
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
-- Dumping data for table `stock_request_head`
--

LOCK TABLES `stock_request_head` WRITE;
/*!40000 ALTER TABLE `stock_request_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_request_head` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subgroup`
--

LOCK TABLES `subgroup` WRITE;
/*!40000 ALTER TABLE `subgroup` DISABLE KEYS */;
INSERT INTO `subgroup` VALUES (1,'A','ANGEL BEAR',1,'2015-05-20 00:00:00','2015-06-30 02:47:44',1,1),(2,'C','COIL',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(3,'E','EMBROSSED / CHECKERED PLATE',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(4,'F','FLAT BAR',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(5,'H','HEXAGON BAR',1,'2015-05-26 12:18:49','2015-05-26 12:18:49',1,1),(6,'J','ROUND TUBE',1,'2015-05-26 12:19:00','2015-05-26 12:19:00',1,1),(7,'K','SQUARE TUBE',1,'2015-05-26 12:19:12','2015-05-26 12:19:12',1,1),(8,'L','RECTANGULAR TUBE',1,'2015-05-26 12:19:26','2015-05-26 12:19:26',1,1),(9,'M','MESH - IMPORTED',1,'2015-05-26 12:19:38','2015-05-26 12:19:38',1,1),(10,'N','PIPE',1,'2015-05-26 12:19:48','2015-05-26 12:19:48',1,1),(11,'P','PERFORATED SHEET',1,'2015-05-26 12:20:07','2015-05-26 12:20:07',1,1),(12,'Q','SQUARE BAR',1,'2015-05-26 12:20:26','2015-05-26 12:20:26',1,1),(13,'R','ROUND BAR',1,'2015-05-26 12:20:41','2015-05-26 12:20:41',1,1),(14,'S','SHEETS AND PLATES - PLAIN',1,'2015-05-26 12:20:54','2015-05-26 12:20:54',1,1),(15,'U','WIRE',1,'2015-05-26 12:21:04','2015-05-26 12:21:04',1,1),(16,'V','WIELDING ROD / ELECTRODE',1,'2015-05-26 12:21:27','2015-05-26 12:21:27',1,1),(17,'W','WEDLED WIRE SCREEN - IMPORTED',1,'2015-05-26 12:21:47','2015-05-26 12:21:47',1,1),(18,'X','EXPANDED METAL',1,'2015-05-26 12:22:21','2015-05-26 12:22:21',1,1),(19,'Z','ZINGA',0,'2015-06-30 02:54:54','2015-06-30 02:56:08',1,1),(20,'Z','ZINGA',0,'2015-06-30 02:56:17','2015-07-22 11:57:02',1,1);
/*!40000 ALTER TABLE `subgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_beginning_transaction`
--

DROP TABLE IF EXISTS `temp_beginning_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_beginning_transaction` (
  `product_id` bigint(20) NOT NULL,
  `branch_id` bigint(20) DEFAULT '0',
  `beginning_inventory` int(11) DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_beginning_transaction`
--

LOCK TABLES `temp_beginning_transaction` WRITE;
/*!40000 ALTER TABLE `temp_beginning_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_beginning_transaction` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'01','Lawrence Pena','superadmin','83703b5229462cb6bfaf425152e46a8c','09263188835',1,1,1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1),(3,'02','Gian Egamino','gegamino','f3e97dcba0a308db57b1aeaee5a43d4c','09263188835',1,1,0,'2015-05-22 04:56:20','2015-07-06 05:42:10',1,1),(5,'04','Kryzza Garra','kryzza','f3e97dcba0a308db57b1aeaee5a43d4c','09263188835',1,1,1,'2015-05-23 06:31:47','2015-07-31 05:24:51',1,1),(6,'05','Enerick Pangilinan','enerick','f3e97dcba0a308db57b1aeaee5a43d4c','12345678',1,1,1,'2015-06-30 03:52:24','2015-07-06 12:00:15',1,1),(7,'09','Benjolynne Sia','benjo','f3e97dcba0a308db57b1aeaee5a43d4c','',1,1,1,'2015-07-08 09:50:13','2015-07-22 12:00:19',5,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_branch`
--

DROP TABLE IF EXISTS `user_branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_branch` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT '0',
  `user_branch` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_branch`
--

LOCK TABLES `user_branch` WRITE;
/*!40000 ALTER TABLE `user_branch` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_branch` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7181 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permission`
--

LOCK TABLES `user_permission` WRITE;
/*!40000 ALTER TABLE `user_permission` DISABLE KEYS */;
INSERT INTO `user_permission` VALUES (1,1,1,100),(2301,1,6,0),(2302,2,6,0),(2303,3,6,0),(2304,4,6,0),(2305,6,6,0),(5408,1,3,100),(5409,2,3,100),(6488,1,7,101),(6489,1,7,102),(6490,1,7,103),(6491,1,7,104),(6492,1,7,105),(6493,1,7,106),(6494,1,7,107),(6495,1,7,108),(6496,1,7,109),(6497,1,7,110),(6498,1,7,111),(6499,1,7,112),(6500,1,7,113),(6501,1,7,114),(6502,1,7,115),(6503,1,7,116),(6504,1,7,117),(6505,1,7,118),(6506,1,7,119),(6507,1,7,120),(6508,1,7,121),(6509,1,7,131),(6510,1,7,132),(6511,1,7,133),(6512,1,7,134),(6513,1,7,135),(6514,1,7,136),(6515,1,7,137),(6516,1,7,138),(6517,1,7,139),(6518,1,7,140),(6519,1,7,141),(6520,1,7,142),(6521,1,7,143),(6522,1,7,144),(6523,1,7,145),(6524,1,7,156),(6525,1,7,157),(6526,1,7,158),(6527,1,7,159),(6528,1,7,160),(6529,1,7,161),(6530,1,7,162),(6531,1,7,163),(6532,1,7,164),(6533,1,7,165),(6534,1,7,171),(6535,1,7,172),(6536,1,7,173),(6537,1,7,174),(6538,1,7,175),(6539,1,7,176),(6540,1,7,177),(6541,1,7,178),(6542,1,7,179),(6543,1,7,180),(6544,1,7,181),(6545,1,7,191),(6546,1,7,192),(6547,1,7,193),(6548,1,7,194),(6549,1,7,195),(6550,1,7,196),(6551,1,7,199),(6552,1,7,200),(6553,1,7,201),(6554,1,7,202),(6555,1,7,203),(6556,1,7,211),(6557,1,7,212),(6558,1,7,213),(6559,2,7,101),(6560,2,7,102),(6561,2,7,103),(6562,2,7,104),(6563,2,7,105),(6564,2,7,106),(6565,2,7,107),(6566,2,7,108),(6567,2,7,109),(6568,2,7,110),(6569,2,7,111),(6570,2,7,112),(6571,2,7,113),(6572,2,7,114),(6573,2,7,115),(6574,2,7,116),(6575,2,7,117),(6576,2,7,118),(6577,2,7,119),(6578,2,7,120),(6579,2,7,121),(6580,2,7,131),(6581,2,7,132),(6582,2,7,133),(6583,2,7,134),(6584,2,7,135),(6585,2,7,136),(6586,2,7,137),(6587,2,7,138),(6588,2,7,139),(6589,2,7,140),(6590,2,7,141),(6591,2,7,142),(6592,2,7,143),(6593,2,7,144),(6594,2,7,145),(6595,2,7,156),(6596,2,7,157),(6597,2,7,158),(6598,2,7,159),(6599,2,7,160),(6600,2,7,161),(6601,2,7,162),(6602,2,7,163),(6603,2,7,164),(6604,2,7,165),(6605,2,7,171),(6606,2,7,172),(6607,2,7,173),(6608,2,7,174),(6609,2,7,175),(6610,2,7,176),(6611,2,7,177),(6612,2,7,178),(6613,2,7,179),(6614,2,7,180),(6615,2,7,181),(6616,2,7,191),(6617,2,7,192),(6618,2,7,193),(6619,2,7,194),(6620,2,7,195),(6621,2,7,196),(6622,2,7,199),(6623,2,7,200),(6624,2,7,201),(6625,2,7,202),(6626,2,7,203),(6627,2,7,211),(6628,2,7,212),(6629,2,7,213),(6630,3,7,101),(6631,3,7,102),(6632,3,7,103),(6633,3,7,104),(6634,3,7,105),(6635,3,7,106),(6636,3,7,107),(6637,3,7,108),(6638,3,7,109),(6639,3,7,110),(6640,3,7,111),(6641,3,7,112),(6642,3,7,113),(6643,3,7,114),(6644,3,7,115),(6645,3,7,116),(6646,3,7,117),(6647,3,7,118),(6648,3,7,119),(6649,3,7,120),(6650,3,7,121),(6651,3,7,131),(6652,3,7,132),(6653,3,7,133),(6654,3,7,134),(6655,3,7,135),(6656,3,7,136),(6657,3,7,137),(6658,3,7,138),(6659,3,7,139),(6660,3,7,140),(6661,3,7,141),(6662,3,7,142),(6663,3,7,143),(6664,3,7,144),(6665,3,7,145),(6666,3,7,156),(6667,3,7,157),(6668,3,7,158),(6669,3,7,159),(6670,3,7,160),(6671,3,7,161),(6672,3,7,162),(6673,3,7,163),(6674,3,7,164),(6675,3,7,165),(6676,3,7,171),(6677,3,7,172),(6678,3,7,173),(6679,3,7,174),(6680,3,7,175),(6681,3,7,176),(6682,3,7,177),(6683,3,7,178),(6684,3,7,179),(6685,3,7,180),(6686,3,7,181),(6687,3,7,191),(6688,3,7,192),(6689,3,7,193),(6690,3,7,194),(6691,3,7,195),(6692,3,7,196),(6693,3,7,199),(6694,3,7,200),(6695,3,7,201),(6696,3,7,202),(6697,3,7,203),(6698,3,7,211),(6699,3,7,212),(6700,3,7,213),(6701,4,7,101),(6702,4,7,102),(6703,4,7,103),(6704,4,7,104),(6705,4,7,105),(6706,4,7,106),(6707,4,7,107),(6708,4,7,108),(6709,4,7,109),(6710,4,7,110),(6711,4,7,111),(6712,4,7,112),(6713,4,7,113),(6714,4,7,114),(6715,4,7,115),(6716,4,7,116),(6717,4,7,117),(6718,4,7,118),(6719,4,7,119),(6720,4,7,120),(6721,4,7,121),(6722,4,7,131),(6723,4,7,132),(6724,4,7,133),(6725,4,7,134),(6726,4,7,135),(6727,4,7,136),(6728,4,7,137),(6729,4,7,138),(6730,4,7,139),(6731,4,7,140),(6732,4,7,141),(6733,4,7,142),(6734,4,7,143),(6735,4,7,144),(6736,4,7,145),(6737,4,7,156),(6738,4,7,157),(6739,4,7,158),(6740,4,7,159),(6741,4,7,160),(6742,4,7,161),(6743,4,7,162),(6744,4,7,163),(6745,4,7,164),(6746,4,7,165),(6747,4,7,171),(6748,4,7,172),(6749,4,7,173),(6750,4,7,174),(6751,4,7,175),(6752,4,7,176),(6753,4,7,177),(6754,4,7,178),(6755,4,7,179),(6756,4,7,180),(6757,4,7,181),(6758,4,7,191),(6759,4,7,192),(6760,4,7,193),(6761,4,7,194),(6762,4,7,195),(6763,4,7,196),(6764,4,7,199),(6765,4,7,200),(6766,4,7,201),(6767,4,7,202),(6768,4,7,203),(6769,4,7,211),(6770,4,7,212),(6771,4,7,213),(6772,6,7,101),(6773,6,7,102),(6774,6,7,103),(6775,6,7,104),(6776,6,7,105),(6777,6,7,106),(6778,6,7,107),(6779,6,7,108),(6780,6,7,109),(6781,6,7,110),(6782,6,7,111),(6783,6,7,112),(6784,6,7,113),(6785,6,7,114),(6786,6,7,115),(6787,6,7,116),(6788,6,7,117),(6789,6,7,118),(6790,6,7,119),(6791,6,7,120),(6792,6,7,121),(6793,6,7,131),(6794,6,7,132),(6795,6,7,133),(6796,6,7,134),(6797,6,7,135),(6798,6,7,136),(6799,6,7,137),(6800,6,7,138),(6801,6,7,139),(6802,6,7,140),(6803,6,7,141),(6804,6,7,142),(6805,6,7,143),(6806,6,7,144),(6807,6,7,145),(6808,6,7,156),(6809,6,7,157),(6810,6,7,158),(6811,6,7,159),(6812,6,7,160),(6813,6,7,161),(6814,6,7,162),(6815,6,7,163),(6816,6,7,164),(6817,6,7,165),(6818,6,7,171),(6819,6,7,172),(6820,6,7,173),(6821,6,7,174),(6822,6,7,175),(6823,6,7,176),(6824,6,7,177),(6825,6,7,178),(6826,6,7,179),(6827,6,7,180),(6828,6,7,181),(6829,6,7,191),(6830,6,7,192),(6831,6,7,193),(6832,6,7,194),(6833,6,7,195),(6834,6,7,196),(6835,6,7,199),(6836,6,7,200),(6837,6,7,201),(6838,6,7,202),(6839,6,7,203),(6840,6,7,211),(6841,6,7,212),(6842,6,7,213),(6843,7,7,101),(6844,7,7,102),(6845,7,7,103),(6846,7,7,104),(6847,7,7,105),(6848,7,7,106),(6849,7,7,107),(6850,7,7,108),(6851,7,7,109),(6852,7,7,110),(6853,7,7,111),(6854,7,7,112),(6855,7,7,113),(6856,7,7,114),(6857,7,7,115),(6858,7,7,116),(6859,7,7,117),(6860,7,7,118),(6861,7,7,119),(6862,7,7,120),(6863,7,7,121),(6864,7,7,131),(6865,7,7,132),(6866,7,7,133),(6867,7,7,134),(6868,7,7,135),(6869,7,7,136),(6870,7,7,137),(6871,7,7,138),(6872,7,7,139),(6873,7,7,140),(6874,7,7,141),(6875,7,7,142),(6876,7,7,143),(6877,7,7,144),(6878,7,7,145),(6879,7,7,156),(6880,7,7,157),(6881,7,7,158),(6882,7,7,159),(6883,7,7,160),(6884,7,7,161),(6885,7,7,162),(6886,7,7,163),(6887,7,7,164),(6888,7,7,165),(6889,7,7,171),(6890,7,7,172),(6891,7,7,173),(6892,7,7,174),(6893,7,7,175),(6894,7,7,176),(6895,7,7,177),(6896,7,7,178),(6897,7,7,179),(6898,7,7,180),(6899,7,7,181),(6900,7,7,191),(6901,7,7,192),(6902,7,7,193),(6903,7,7,194),(6904,7,7,195),(6905,7,7,196),(6906,7,7,199),(6907,7,7,200),(6908,7,7,201),(6909,7,7,202),(6910,7,7,203),(6911,7,7,211),(6912,7,7,212),(6913,7,7,213),(6914,1,5,101),(6915,1,5,102),(6916,1,5,103),(6917,1,5,104),(6918,1,5,105),(6919,1,5,106),(6920,1,5,107),(6921,1,5,108),(6922,1,5,109),(6923,1,5,110),(6924,1,5,111),(6925,1,5,112),(6926,1,5,113),(6927,1,5,114),(6928,1,5,115),(6929,1,5,116),(6930,1,5,117),(6931,1,5,118),(6932,1,5,119),(6933,1,5,120),(6934,1,5,121),(6935,1,5,131),(6936,1,5,132),(6937,1,5,133),(6938,1,5,134),(6939,1,5,135),(6940,1,5,241),(6941,1,5,136),(6942,1,5,137),(6943,1,5,138),(6944,1,5,139),(6945,1,5,140),(6946,1,5,161),(6947,1,5,162),(6948,1,5,163),(6949,1,5,164),(6950,1,5,165),(6951,1,5,234),(6952,1,5,235),(6953,1,5,236),(6954,1,5,237),(6955,1,5,238),(6956,1,5,242),(6957,1,5,239),(6958,1,5,240),(6959,1,5,171),(6960,1,5,172),(6961,1,5,173),(6962,1,5,174),(6963,1,5,175),(6964,1,5,176),(6965,1,5,177),(6966,1,5,178),(6967,1,5,179),(6968,1,5,180),(6969,1,5,181),(6970,1,5,141),(6971,1,5,142),(6972,1,5,143),(6973,1,5,144),(6974,1,5,145),(6975,1,5,156),(6976,1,5,157),(6977,1,5,158),(6978,1,5,159),(6979,1,5,160),(6980,1,5,223),(6981,1,5,224),(6982,1,5,225),(6983,1,5,226),(6984,1,5,227),(6985,1,5,199),(6986,1,5,200),(6987,1,5,201),(6988,1,5,202),(6989,1,5,203),(6990,1,5,232),(6991,1,5,233),(6992,1,5,191),(6993,1,5,192),(6994,1,5,193),(6995,1,5,194),(6996,1,5,195),(6997,1,5,196),(6998,1,5,197),(6999,1,5,198),(7000,1,5,211),(7001,1,5,212),(7002,1,5,213),(7003,2,5,101),(7004,2,5,102),(7005,2,5,103),(7006,2,5,104),(7007,2,5,105),(7008,2,5,106),(7009,2,5,107),(7010,2,5,108),(7011,2,5,109),(7012,2,5,110),(7013,2,5,111),(7014,2,5,112),(7015,2,5,113),(7016,2,5,114),(7017,2,5,115),(7018,2,5,116),(7019,2,5,117),(7020,2,5,118),(7021,2,5,119),(7022,2,5,120),(7023,2,5,121),(7024,2,5,131),(7025,2,5,132),(7026,2,5,133),(7027,2,5,134),(7028,2,5,135),(7029,2,5,241),(7030,2,5,136),(7031,2,5,137),(7032,2,5,138),(7033,2,5,139),(7034,2,5,140),(7035,2,5,161),(7036,2,5,162),(7037,2,5,163),(7038,2,5,164),(7039,2,5,165),(7040,2,5,234),(7041,2,5,235),(7042,2,5,236),(7043,2,5,237),(7044,2,5,238),(7045,2,5,242),(7046,2,5,239),(7047,2,5,240),(7048,2,5,171),(7049,2,5,172),(7050,2,5,173),(7051,2,5,174),(7052,2,5,175),(7053,2,5,176),(7054,2,5,177),(7055,2,5,178),(7056,2,5,179),(7057,2,5,180),(7058,2,5,181),(7059,2,5,141),(7060,2,5,142),(7061,2,5,143),(7062,2,5,144),(7063,2,5,145),(7064,2,5,156),(7065,2,5,157),(7066,2,5,158),(7067,2,5,159),(7068,2,5,160),(7069,2,5,223),(7070,2,5,224),(7071,2,5,225),(7072,2,5,226),(7073,2,5,227),(7074,2,5,199),(7075,2,5,200),(7076,2,5,201),(7077,2,5,202),(7078,2,5,203),(7079,2,5,232),(7080,2,5,233),(7081,2,5,191),(7082,2,5,192),(7083,2,5,193),(7084,2,5,194),(7085,2,5,195),(7086,2,5,196),(7087,2,5,197),(7088,2,5,198),(7089,2,5,211),(7090,2,5,212),(7091,2,5,213),(7092,3,5,101),(7093,3,5,102),(7094,3,5,103),(7095,3,5,104),(7096,3,5,105),(7097,3,5,106),(7098,3,5,107),(7099,3,5,108),(7100,3,5,109),(7101,3,5,110),(7102,3,5,111),(7103,3,5,112),(7104,3,5,113),(7105,3,5,114),(7106,3,5,115),(7107,3,5,116),(7108,3,5,117),(7109,3,5,118),(7110,3,5,119),(7111,3,5,120),(7112,3,5,121),(7113,3,5,131),(7114,3,5,132),(7115,3,5,133),(7116,3,5,134),(7117,3,5,135),(7118,3,5,241),(7119,3,5,136),(7120,3,5,137),(7121,3,5,138),(7122,3,5,139),(7123,3,5,140),(7124,3,5,161),(7125,3,5,162),(7126,3,5,163),(7127,3,5,164),(7128,3,5,165),(7129,3,5,234),(7130,3,5,235),(7131,3,5,236),(7132,3,5,237),(7133,3,5,238),(7134,3,5,242),(7135,3,5,239),(7136,3,5,240),(7137,3,5,171),(7138,3,5,172),(7139,3,5,173),(7140,3,5,174),(7141,3,5,175),(7142,3,5,176),(7143,3,5,177),(7144,3,5,178),(7145,3,5,179),(7146,3,5,180),(7147,3,5,181),(7148,3,5,141),(7149,3,5,142),(7150,3,5,143),(7151,3,5,144),(7152,3,5,145),(7153,3,5,156),(7154,3,5,157),(7155,3,5,158),(7156,3,5,159),(7157,3,5,160),(7158,3,5,223),(7159,3,5,224),(7160,3,5,225),(7161,3,5,226),(7162,3,5,227),(7163,3,5,199),(7164,3,5,200),(7165,3,5,201),(7166,3,5,202),(7167,3,5,203),(7168,3,5,232),(7169,3,5,233),(7170,3,5,191),(7171,3,5,192),(7172,3,5,193),(7173,3,5,194),(7174,3,5,195),(7175,3,5,196),(7176,3,5,197),(7177,3,5,198),(7178,3,5,211),(7179,3,5,212),(7180,3,5,213);
/*!40000 ALTER TABLE `user_permission` ENABLE KEYS */;
UNLOCK TABLES;

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
    
	CASE table_name_d
		WHEN 'CUSTOMER RETURN DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM return_head WHERE `id` = head_id_d;
		WHEN 'DAMAGE DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM damage_head WHERE `id` = head_id_d;
		WHEN 'RECEIVE DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM purchase_receive_head WHERE `id` = head_id_d;
		WHEN 'PURCHASE RETURN DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM purchase_return_head WHERE `id` = head_id_d;
		WHEN 'CUSTOMER DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM stock_delivery_head WHERE `id` = head_id_d;
		WHEN 'RELEASE DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM release_head WHERE `id` = head_id_d;
        WHEN 'INVENTORY ADJUST' THEN
			SELECT `branch_id` INTO @branch_id FROM inventory_adjust WHERE `id` = head_id_d;
		WHEN 'DELIVERY DETAIL' THEN
			SELECT `branch_id` INTO @branch_id FROM stock_delivery_head WHERE `id` = head_id_d;
		WHEN 'TRANSFER DETAIL' THEN
			SELECT `to_branchid` INTO @branch_id FROM stock_delivery_head WHERE `id` = head_id_d;
	END CASE;
    
    UPDATE product_branch_inventory
		SET `inventory` = `inventory` + qty_d
        WHERE `branch_id` = @branch_id AND `product_id` = product_id_d;
        
	CALL process_compute_transaction_summary(product_id_d,qty_d,head_id_d,table_name_d);
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
    DECLARE cursor_release CURSOR FOR SELECT `product_id`, `quantity` FROM release_detail WHERE `headid` = head_id_d;
    DECLARE cursor_purchase_return CURSOR FOR SELECT `product_id`, `quantity` FROM purchase_return_detail WHERE `headid` = head_id_d;
    DECLARE cursor_received CURSOR FOR SELECT `product_id`, `quantity`, `purchase_detail_id` FROM purchase_receive_detail WHERE `headid` = head_id_d;
    DECLARE cursor_delivery CURSOR FOR SELECT `product_id`, `quantity` FROM stock_delivery_detail WHERE `headid` = head_id_d AND `is_for_branch` = 1; 
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;		
	
    
    IF (table_name_d = 'CUSTOMER RETURN HEAD') THEN
		OPEN cursor_return;
	ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
		OPEN cursor_damage;
	ELSEIF (table_name_d = 'RELEASE HEAD') THEN
		OPEN cursor_release;
	ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
		OPEN cursor_received;
	ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
		OPEN cursor_purchase_return;
	ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
		OPEN cursor_delivery;
	END IF;
        
	read_loop: LOOP
		IF (table_name_d = 'CUSTOMER RETURN HEAD') THEN
			FETCH cursor_return INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
			FETCH cursor_damage INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'RELEASE HEAD') THEN
			FETCH cursor_release INTO cursor_product_id, cursor_quantity;
        ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
			FETCH cursor_received INTO cursor_product_id, cursor_quantity, cursor_other_id;
		ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
			FETCH cursor_purchase_return INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
			FETCH cursor_delivery INTO cursor_product_id, cursor_quantity;
		END IF;
        
		IF done THEN
			LEAVE read_loop;
		END IF;
        
			IF (table_name_d = 'CUSTOMER RETURN HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,(-1 * cursor_quantity),head_id_d,'CUSTOMER RETURN DETAIL');
			ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,'DAMAGE DETAIL');
			ELSEIF (table_name_d = 'RELEASE HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,'RELEASE DETAIL');
            ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,(-1 * cursor_quantity),head_id_d,'RECEIVE DETAIL');
				CALL process_update_receive(cursor_other_id,(-1 * cursor_quantity),'RECEIVE DETAIL');
			ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,'PURCHASE RETURN DETAIL');
			ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
				CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,'DELIVERY DETAIL');
            END IF;
	END LOOP;
    
    IF (table_name_d = 'CUSTOMER RETURN HEAD') THEN
		CLOSE cursor_return;
	ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
		CLOSE cursor_damage;
	ELSEIF (table_name_d = 'RELEASE HEAD') THEN
		CLOSE cursor_release;
    ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
		CLOSE cursor_received;
	ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
		CLOSE cursor_purchase_return;
	ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
		CLOSE cursor_delivery;
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `process_compute_transaction_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `process_compute_transaction_summary`(
	IN `product_id_d` BIGINT,
	IN `quantity_d` BIGINT,
    IN `head_id_d` BIGINT,
    IN `table_name_d` VARCHAR(50)
)
BEGIN
	SET @branch_id := 0;
    SET @entry_date := CAST('0000-00-00' AS DATE);
    SET @count := 0;
    
	CASE table_name_d
		WHEN 'RECEIVE DETAIL' THEN
			SELECT `branch_id`, CAST(`entry_date` AS DATE) INTO @branch_id, @entry_date FROM purchase_receive_head WHERE `id` = head_id_d;
			SELECT COUNT(*) INTO @count FROM daily_transaction_summary WHERE `product_id` = product_id_d AND `branch_id` = @branch_id AND `date` = @entry_date;
           
			IF(@count = 0) THEN
				INSERT INTO daily_transaction_summary(`product_id`,`date`,`branch_id`) VALUES(product_id_d,@entry_date,@branch_id);
			END IF;
            
            UPDATE daily_transaction_summary
				SET `purchase_receive` = `purchase_receive` + quantity_d
                WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
                
		WHEN 'CUSTOMER RETURN DETAIL' THEN
			SELECT `branch_id`, CAST(`entry_date` AS DATE) INTO @branch_id, @entry_date FROM return_head WHERE `id` = head_id_d;
			SELECT COUNT(*) INTO @count FROM daily_transaction_summary WHERE `product_id` = product_id_d AND `branch_id` = @branch_id AND `date` = @entry_date;
           
			IF(@count = 0) THEN
				INSERT INTO daily_transaction_summary(`product_id`,`date`,`branch_id`) VALUES(product_id_d,@entry_date,@branch_id);
			END IF;
            
            UPDATE daily_transaction_summary
				SET `customer_return` = `customer_return` + quantity_d
                WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
               
		WHEN 'DAMAGE DETAIL' THEN
			SELECT `branch_id`, CAST(`entry_date` AS DATE) INTO @branch_id, @entry_date FROM damage_head WHERE `id` = head_id_d;
			SELECT COUNT(*) INTO @count FROM daily_transaction_summary WHERE `product_id` = product_id_d AND `branch_id` = @branch_id AND `date` = @entry_date;
           
			IF(@count = 0) THEN
				INSERT INTO daily_transaction_summary(`product_id`,`date`,`branch_id`) VALUES(product_id_d,@entry_date,@branch_id);
			END IF;
            
            UPDATE daily_transaction_summary
				SET `damage` = `damage` + (quantity_d * -1)
                WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
	
		WHEN 'PURCHASE RETURN DETAIL' THEN
			SELECT `branch_id`, CAST(`entry_date` AS DATE) INTO @branch_id, @entry_date FROM purchase_return_head WHERE `id` = head_id_d;
			SELECT COUNT(*) INTO @count FROM daily_transaction_summary WHERE `product_id` = product_id_d AND `branch_id` = @branch_id AND `date` = @entry_date;
           
			IF(@count = 0) THEN
				INSERT INTO daily_transaction_summary(`product_id`,`date`,`branch_id`) VALUES(product_id_d,@entry_date,@branch_id);
			END IF;
            
            UPDATE daily_transaction_summary
				SET `purchase_return` = `purchase_return` + (quantity_d * -1)
                WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
              
		WHEN 'CUSTOMER DETAIL' THEN
			SELECT `branch_id`, CAST(`customer_receive_date` AS DATE) INTO @branch_id, @entry_date FROM stock_delivery_head WHERE `id` = head_id_d;
			SELECT COUNT(*) INTO @count FROM daily_transaction_summary WHERE `product_id` = product_id_d AND `branch_id` = @branch_id AND `date` = @entry_date;
           
			IF(@count = 0) THEN
				INSERT INTO daily_transaction_summary(`product_id`,`date`,`branch_id`) VALUES(product_id_d,@entry_date,@branch_id);
			END IF;
            
            UPDATE daily_transaction_summary
				SET `customer_delivery` = `customer_delivery` + (quantity_d * -1)
                WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
	 
		WHEN 'RELEASE DETAIL' THEN
			SELECT `branch_id`, CAST(`entry_date` AS DATE) INTO @branch_id, @entry_date FROM release_head WHERE `id` = head_id_d;
			SELECT COUNT(*) INTO @count FROM daily_transaction_summary WHERE `product_id` = product_id_d AND `branch_id` = @branch_id AND `date` = @entry_date;
           
			IF(@count = 0) THEN
				INSERT INTO daily_transaction_summary(`product_id`,`date`,`branch_id`) VALUES(product_id_d,@entry_date,@branch_id);
			END IF;
            
            UPDATE daily_transaction_summary
				SET `warehouse_release` = `warehouse_release` + (quantity_d * -1)
                WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
                
		WHEN 'INVENTORY ADJUST' THEN
            
			SELECT `branch_id`, CAST(`date_created` AS DATE) INTO @branch_id, @entry_date FROM inventory_adjust WHERE `id` = head_id_d;
			SELECT COUNT(*) INTO @count FROM daily_transaction_summary WHERE `product_id` = product_id_d AND `branch_id` = @branch_id AND `date` = @entry_date;
           
			IF(@count = 0) THEN
				INSERT INTO daily_transaction_summary(`product_id`,`date`,`branch_id`) VALUES(product_id_d,@entry_date,@branch_id);
			END IF;
            
            IF(quantity_d < 0) THEN
				UPDATE daily_transaction_summary
					SET `adjust_decrease` = `adjust_decrease` + (quantity_d * -1)
					WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
			ELSEIF(quantity_d > 0) THEN
				UPDATE daily_transaction_summary
					SET `adjust_increase` = `adjust_increase` + quantity_d
					WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
			END IF;
            
		WHEN 'DELIVERY DETAIL' THEN
			SELECT `branch_id`, CAST(`entry_date` AS DATE) INTO @branch_id, @entry_date FROM stock_delivery_head WHERE `id` = head_id_d;
			SELECT COUNT(*) INTO @count FROM daily_transaction_summary WHERE `product_id` = product_id_d AND `branch_id` = @branch_id AND `date` = @entry_date;
           
			IF(@count = 0) THEN
				INSERT INTO daily_transaction_summary(`product_id`,`date`,`branch_id`) VALUES(product_id_d,@entry_date,@branch_id);
			END IF;
            
            UPDATE daily_transaction_summary
				SET `stock_delivery` = `stock_delivery` + (quantity_d * -1)
                WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
		
        WHEN 'TRANSFER DETAIL' THEN
			SELECT `to_branchid`, CAST(`delivery_receive_date` AS DATE) INTO @branch_id, @entry_date FROM stock_delivery_head WHERE `id` = head_id_d;
			SELECT COUNT(*) INTO @count FROM daily_transaction_summary WHERE `product_id` = product_id_d AND `branch_id` = @branch_id AND `date` = @entry_date;
           
			IF(@count = 0) THEN
				INSERT INTO daily_transaction_summary(`product_id`,`date`,`branch_id`) VALUES(product_id_d,@entry_date,@branch_id);
			END IF;
            
            UPDATE daily_transaction_summary
				SET `stock_receive` = `stock_receive` + quantity_d
                WHERE `product_id` = product_id_d AND `date` = @entry_date AND `branch_id` = @branch_id;
                
	END CASE;
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
/*!50003 DROP PROCEDURE IF EXISTS `process_insert_new_name` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `process_insert_new_name`(
	IN `name_d` VARCHAR(100),
    IN `type_d` INT(1)
)
BEGIN
	SET @count = 0;
    
    SELECT COUNT(*) INTO @count FROM `recent_name` WHERE LOWER(`name`) = LOWER(name_d) AND `type` = type_d;
    
	IF(@count = 0) THEN
		INSERT INTO `recent_name`(`name`,`type`) VALUES(name_d,type_d);
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `process_recompute_transaction_summary` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `process_recompute_transaction_summary`(
	IN `table_name_d` VARCHAR(100),
    IN `head_id_d` BIGINT,
    IN `absolute_d` INT 
)
BEGIN
	DECLARE cursor_product_id BIGINT;
	DECLARE cursor_quantity INT;
    DECLARE cursor_other_id BIGINT;
	DECLARE done INT DEFAULT FALSE;
    
    DECLARE cursor_return CURSOR FOR SELECT `product_id`, `quantity` FROM return_detail WHERE `headid` = head_id_d;
    DECLARE cursor_damage CURSOR FOR SELECT `product_id`, `quantity` FROM damage_detail WHERE `headid` = head_id_d;
    DECLARE cursor_release CURSOR FOR SELECT `product_id`, `quantity` FROM release_detail WHERE `headid` = head_id_d;
    DECLARE cursor_purchase_return CURSOR FOR SELECT `product_id`, `quantity` FROM purchase_return_detail WHERE `headid` = head_id_d;
    DECLARE cursor_received CURSOR FOR SELECT `product_id`, `quantity`, `purchase_detail_id` FROM purchase_receive_detail WHERE `headid` = head_id_d;
    DECLARE cursor_delivery CURSOR FOR SELECT `product_id`, `quantity` FROM stock_delivery_detail WHERE `headid` = head_id_d AND `is_for_branch` = 1; 
    DECLARE cursor_delivery_receive CURSOR FOR SELECT `product_id`, `recv_quantity` FROM stock_delivery_detail WHERE `headid` = head_id_d AND `is_for_branch` = 1; 
    DECLARE cursor_customer_receive CURSOR FOR SELECT `product_id`, `recv_quantity` FROM stock_delivery_detail WHERE `headid` = head_id_d AND `is_for_branch` <> 1; 
    
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;		
	
    
    IF (table_name_d = 'CUSTOMER RETURN HEAD') THEN
		OPEN cursor_return;
	ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
		OPEN cursor_damage;
	ELSEIF (table_name_d = 'RELEASE HEAD') THEN
		OPEN cursor_release;
	ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
		OPEN cursor_received;
	ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
		OPEN cursor_purchase_return;
	ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
		OPEN cursor_delivery;
	ELSEIF (table_name_d = 'DELIVERY RECEIVE HEAD') THEN
		OPEN cursor_delivery_receive;
	ELSEIF (table_name_d = 'CUSTOMER RECEIVE HEAD') THEN
		OPEN cursor_customer_receive;
	END IF;
        
	read_loop: LOOP
		IF (table_name_d = 'CUSTOMER RETURN HEAD') THEN
			FETCH cursor_return INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
			FETCH cursor_damage INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'RELEASE HEAD') THEN
			FETCH cursor_release INTO cursor_product_id, cursor_quantity;
        ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
			FETCH cursor_received INTO cursor_product_id, cursor_quantity, cursor_other_id;
		ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
			FETCH cursor_purchase_return INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
			FETCH cursor_delivery INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'DELIVERY RECEIVE HEAD') THEN
			FETCH cursor_delivery_receive INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'CUSTOMER RECEIVE HEAD') THEN
			FETCH cursor_customer_receive INTO cursor_product_id, cursor_quantity;
		END IF;
        
		IF done THEN
			LEAVE read_loop;
		END IF;
        
			IF (table_name_d = 'CUSTOMER RETURN HEAD') THEN
				CALL process_compute_transaction_summary(cursor_product_id,(-1 * absolute_d * cursor_quantity),head_id_d,'CUSTOMER RETURN DETAIL');
			ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
				CALL process_compute_transaction_summary(cursor_product_id,absolute_d * cursor_quantity,head_id_d,'DAMAGE DETAIL');
			ELSEIF (table_name_d = 'RELEASE HEAD') THEN
				CALL process_compute_transaction_summary(cursor_product_id,absolute_d * cursor_quantity,head_id_d,'RELEASE DETAIL');
            ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
				CALL process_compute_transaction_summary(cursor_product_id,(-1 * absolute_d * cursor_quantity),head_id_d,'RECEIVE DETAIL');
			ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
				CALL process_compute_transaction_summary(cursor_product_id,absolute_d * cursor_quantity,head_id_d,'PURCHASE RETURN DETAIL');
			ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
				CALL process_compute_transaction_summary(cursor_product_id,absolute_d * cursor_quantity,head_id_d,'DELIVERY DETAIL');
            ELSEIF (table_name_d = 'DELIVERY RECEIVE HEAD') THEN
				CALL process_compute_transaction_summary(cursor_product_id,(-1 * absolute_d * cursor_quantity),head_id_d,'TRANSFER DETAIL');
            ELSEIF (table_name_d = 'CUSTOMER RECEIVE HEAD') THEN
				CALL process_compute_transaction_summary(cursor_product_id,absolute_d * cursor_quantity,head_id_d,'CUSTOMER DETAIL');
            END IF;
            
	END LOOP;
    
    IF (table_name_d = 'CUSTOMER RETURN HEAD') THEN
		CLOSE cursor_return;
	ELSEIF (table_name_d = 'DAMAGE HEAD') THEN
		CLOSE cursor_damage;
	ELSEIF (table_name_d = 'RELEASE HEAD') THEN
		CLOSE cursor_release;
    ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
		CLOSE cursor_received;
	ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
		CLOSE cursor_purchase_return;
	ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
		CLOSE cursor_delivery;
	ELSEIF (table_name_d = 'DELIVERY RECEIVE HEAD') THEN
		CLOSE cursor_delivery_receive;
	ELSEIF (table_name_d = 'CUSTOMER RECEIVE HEAD') THEN
		CLOSE cursor_customer_receive;
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
	CASE table_name_d
		WHEN 'RECEIVE DETAIL' THEN
			UPDATE `purchase_detail`
				SET `recv_quantity` = `recv_quantity` + quantity_d
				WHERE `id` = detail_id_d;
		
        WHEN 'RELEASE DETAIL' THEN
			UPDATE `release_order_detail`
				SET `qty_released` = `qty_released` + quantity_d
				WHERE `id` = detail_id_d;
                
		WHEN 'REQUEST DETAIL' THEN
			UPDATE `stock_request_detail`
				SET `qty_delivered` = `qty_delivered` + quantity_d
				WHERE `id` = detail_id_d;
	END CASE;
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

-- Dump completed on 2015-07-31 20:27:56

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
  PRIMARY KEY (`id`),
  KEY `idx_id_show` (`is_show`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch`
--

LOCK TABLES `branch` WRITE;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` VALUES (1,'01','Marilao',1,'2015-05-19 00:00:00','2015-06-30 04:21:45',1,1),(2,'02','Portrero',1,'2015-05-24 02:26:23','2015-06-30 04:20:08',1,1),(3,'03','Mapua',1,'2015-05-26 04:26:38','2015-05-26 04:26:38',1,1);
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
  PRIMARY KEY (`id`),
  KEY `idx_productid_date` (`product_id`,`date`),
  KEY `idx_productid_branchid_date` (`product_id`,`branch_id`,`date`)
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
  PRIMARY KEY (`id`),
  KEY `idx_status_isshow_branchid` (`status`,`is_show`,`branch_id`)
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `material_type`
--

LOCK TABLES `material_type` WRITE;
/*!40000 ALTER TABLE `material_type` DISABLE KEYS */;
INSERT INTO `material_type` VALUES (1,'T','TIGER BRONZE',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(2,'S','STAINLESS STEEL',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(3,'R','BRASS',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(5,'A','ALUMINUM',1,'2015-05-20 00:00:00','2015-08-13 05:15:38',1,1),(6,'B','BI / BLACK IRON',1,'2015-05-20 00:00:00','2015-06-30 02:06:02',1,1),(7,'G','GALVANIZED',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(14,'C','COPPER',1,'2015-08-13 06:11:15','2015-08-13 06:11:15',1,1);
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
  PRIMARY KEY (`id`),
  KEY `idx_isshow_type` (`is_show`,`type`),
  KEY `idx_isshow` (`is_show`),
  KEY `idx_isshow_subgroup` (`is_show`,`subgroup_id`),
  KEY `idx_isshow_materialtype` (`is_show`,`material_type_id`),
  KEY `idx_isshow_subgroup_materialtype` (`is_show`,`subgroup_id`,`material_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1338 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'CF018038','Copper Flat Bar 1/8 x 3/8 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(2,'CF018012','Copper Flat Bar 1/8 x 1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(3,'CF018058','Copper Flat Bar 1/8 x 5/8 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(4,'CF018034','Copper Flat Bar 1/8 x 3/4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(5,'CF018078','Copper Flat Bar 1/8 x 7/8 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(6,'CF018100','Copper Flat Bar 1/8 x 1 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(7,'CF018114','Copper Flat Bar 1/8 x 1-1/4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(8,'CF018112','Copper Flat Bar 1/8 x 1-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(9,'CF018200','Copper Flat Bar 1/8 x 2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(10,'CF018212','Copper Flat Bar 1/8 x 2-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(11,'CF316012','Copper Flat Bar 3/16 x 1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(12,'CF316034','Copper Flat Bar 3/16 x 3/4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(13,'CF316100','Copper Flat Bar 3/16 x 1 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(14,'CF316112','Copper Flat Bar 3/16 x 1-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(15,'CF316200','Copper Flat Bar 3/16 x 2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(16,'CF014012','Copper Flat Bar 1/4 x 1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(17,'CF014058','Copper Flat Bar 1/4 x 5/8 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(18,'CF014034','Copper Flat Bar 1/4 x 3/4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(19,'CF014078','Copper Flat Bar 1/4 x 7/8 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(20,'CF014100','Copper Flat Bar 1/4 x 1 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(21,'CF014114','Copper Flat Bar 1/4 x 1-1/4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(22,'CF014112','Copper Flat Bar 1/4 x 1-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(23,'CF014134','Copper Flat Bar 1/4 x 1-3/4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(24,'CF014200','Copper Flat Bar 1/4 x 2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(25,'CF014212','Copper Flat Bar 1/4 x 2-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(26,'CF014300','Copper Flat Bar 1/4 x 3 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(27,'CF014312','Copper Flat Bar 1/4 x 3-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(28,'CF014400','Copper Flat Bar 1/4 x 4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(29,'CF014500','Copper Flat Bar 1/4 x 5 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(30,'CF014600','Copper Flat Bar 1/4 x 6 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(31,'CF038100','Copper Flat Bar 3/8 x 1 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(32,'CF038114','Copper Flat Bar 3/8 x 1-1/4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(33,'CF038112','Copper Flat Bar 3/8 x 1-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(34,'CF038200','Copper Flat Bar 3/8 x 2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(35,'CF038212','Copper Flat Bar 3/8 x 2-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(36,'CF038300','Copper Flat Bar 3/8 x 3 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(37,'CF038400','Copper Flat Bar 3/8 x 4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(38,'CF038500','Copper Flat Bar 3/8 x 5 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(39,'CF038600','Copper Flat Bar 3/8 x 6 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(40,'CF012100','Copper Flat Bar 1/2 x 1 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(41,'CF012112','Copper Flat Bar 1/2 x 1-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(42,'CF012200','Copper Flat Bar 1/2 x 2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(43,'CF012212','Copper Flat Bar 1/2 x 2-1/2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(44,'CF012300','Copper Flat Bar 1/2 x 3 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(45,'CF012400','Copper Flat Bar 1/2 x 4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(46,'CF012500','Copper Flat Bar 1/2 x 5 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(47,'CF012600','Copper Flat Bar 1/2 x 6 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(48,'CF034200','Copper Flat Bar 3/4 x 2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(49,'CF012800','Copper Flat Bar 1/2 x 8 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(50,'CF034300','Copper Flat Bar 3/4  x 3 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(51,'CF034400','Copper Flat Bar 3/4 x 4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(52,'CF034600','Copper Flat Bar 3/4  x 6 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(53,'CF034800','Copper Flat Bar 3/4 x 8 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(54,'CF100200','Copper Flat Bar 1 x 2 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(55,'CF100300','Copper Flat Bar 1 x 3 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(56,'CF100400','Copper Flat Bar 1 x 4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(57,'CF100600','Copper Flat Bar 1 x 6 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(58,'CF100800','Copper Flat Bar 1 x 8 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(59,'CF114400','Copper Flat Bar 1-1/4 x 4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(60,'CF114600','Copper Flat Bar 1-1/4 x 6 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(61,'CF114800','Copper Flat Bar 1-1/4 x 8 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(62,'CF112400','Copper Flat Bar 1-1/2 x 4 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(63,'CF112600','Copper Flat Bar 1-1/2 x 6 x 20 Ft.',1,14,4,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(64,'CR031610','Copper Round Bar 3/16 x 10 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(65,'CR001420','Copper Round Bar 1/4 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(66,'CR051620','Copper Round Bar 5/16 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(67,'CR003820','Copper Round Bar 3/8 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(68,'CR001220','Copper Round Bar 1/2 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(69,'CR005820','Copper Round Bar 5/8 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(70,'CR003420','Copper Round Bar 3/4 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(71,'CR007820','Copper Round Bar 7/8 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(72,'CR100020','Copper Round Bar 1 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(73,'CR101820','Copper Round Bar 1-1/8 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(74,'CR101420','Copper Round Bar 1-1/4 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(75,'CR101220','Copper Round Bar 1-1/2 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(76,'CR103420','Copper Round Bar 1-3/4 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(77,'CR200020','Copper Round Bar 2 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(78,'CR201220','Copper Round Bar 2-1/2 x 20 Ft.',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(79,'CR3000A1','Copper Round Bar 3 x 3050mm',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(80,'CR4000A1','Copper Round Bar 4 x 3,500mm',1,14,13,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(81,'CS006048','Copper Sht #24 (0.6mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(82,'CS007048','Copper Sht #22 (0.7mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(83,'CS009048','Copper Sht #20 (0.9mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(84,'CS010048','Copper Sht 1.0mm x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(85,'CS012048','Copper Sht #18 (1.2mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(86,'CS013048','Copper Sht #16 (1.3mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(87,'CS015048','Copper Sht #16 (1.5mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(88,'CS020048','Copper Sht #14 (2.0mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(89,'CS023048','Copper Sht 3/32 (2.3mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(90,'CS030048','Copper Sht 1/8 (3.0mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(91,'CS045048','Copper Sht 3/16 (4.5mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(92,'CS060048','Copper Sht 1/4 (6.0mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(93,'CS095348','Copper Sht 3/8 (9.53mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(94,'CS130048','Copper Sht 1/2 (13.0mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(95,'CS158048','Copper Sht 5/8 (15.8mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(96,'CS193048','Copper Sht 3/4 (19.3mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(97,'CS254048','Copper Sht 1 (25.4mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(98,'CS317548','Copper Sht 1-1/4 (31.75mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(99,'CS390048','Copper Sht 1-1/2 (39.0mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(100,'CS510048','Copper Sht 2 (51.0mm) x 4 Ft. x 8 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(101,'CS640048','Copper Sht 2-1/2 (64.0mm) x 4 Ft. x 4 Ft.',1,14,14,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(102,'CQ001220','Copper Square Bar 1/2 x 20 Ft.',1,14,12,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(103,'CQ003420','Copper Square Bar 3/4 x 20 Ft.',1,14,12,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(104,'CQ010020','Copper Square Bar 1 x 20 Ft.',1,14,12,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(105,'CQ011420','Copper Square Bar 1-1/4 x 20 Ft.',1,14,12,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(106,'CQ011220','Copper Square Bar 1-1/2 x 20 Ft.',1,14,12,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(107,'CN001820','Copper Pipe 1/8 x 20 Ft.',1,14,10,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(108,'CN003820','Copper Pipe 3/8 x 20 Ft.',1,14,10,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(109,'CN003420','Copper Pipe 3/4 x 20 Ft.',1,14,10,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(110,'CN011420','Copper Pipe 1-1/4 x 20 Ft.',1,14,10,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(111,'CN011220','Copper Pipe 1-1/2 x 20 Ft.',1,14,10,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(112,'CN020020','Copper Pipe 2 x 20 Ft.',1,14,10,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(113,'CJ051620','Copper Tube 5/16 x 20 Ft.',1,14,6,1,'2015-08-13 06:11:33','0000-00-00 00:00:00',1,0),(114,'BJ014L20','BI Tube 1/4\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(115,'BJ056L20','BI Tube 5/16\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(116,'BJ038A20','BI Tube 3/8\" (1.0mm) x 20 Ft.',1,6,6,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(117,'BJ012C20','BI Tube 1/2\" (1.6mm) x 20 Ft.',1,6,6,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(118,'BJ058B19','BI Tube 5/8\" (1.2mm) x 19 Ft.',1,6,6,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(119,'BJ058B20','BI Tube 5/8\" (1.2mm) x 20 Ft.',1,6,6,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(120,'BC06004F','Hot Rolled COIL 6.0mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(121,'BC05004F','Hot Rolled COIL 5.0mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(122,'BC04504F','Hot Rolled COIL 4.5mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(123,'BC04004F','Hot Rolled COIL 4.0mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(124,'BC03004F','Hot Rolled COIL 3.0mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(125,'BC02004F','Cold Rolled COIL 2.0mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(126,'BC01354F','Cold Rolled COIL 1.35mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(127,'BC01054F','Cold Rolled COIL 1.05mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(128,'BC00854F','Cold Rolled COIL 0.85mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(129,'BC00704F','Cold Rolled COIL 0.70mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(130,'BC00604F','Cold Rolled COIL 0.60mm x 4 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(131,'BC00703F','Cold Rolled Coil 0.7mm x 3 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(132,'BC00603F','Cold Rolled Coil 0.6mm x 3 Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(133,'BCP6004F','BI Pickled & Oiled Coil 6.0mm x 4Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(134,'BCP5004F','BI Pickled & Oiled Coil 5.0mm x 4Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(135,'BCP4504F','BI Pickled & Oiled Coil 4.5mm x 4Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(136,'BCP4004F','BI Pickled & Oiled Coil 4.0mm x 4Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(137,'BCP3004F','BI Pickled & Oiled Coil 3.0mm x 4Ft.',1,6,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(138,'BS190048','BI Sheet 19.0mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(139,'BS160048','BI Sheet 16.0mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(140,'BS120048','BI Sheet 12.0mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(141,'BS100048','BI Sheet 10.0mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(142,'BS080048','BI Sheet 8.0mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(143,'BS060048','BI Sheet 6.0mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(144,'BS045048','BI Sheet 4.5mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(145,'BS040048','BI Sheet 4.0mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(146,'BS030048','BI Sheet 3.0mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(147,'BS020048','BI Sheet 2.0mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(148,'BS013548','BI Sheet 1.35mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(149,'BS010548','BI Sheet 1.05mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(150,'BS008548','BI Sheet 0.85mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(151,'BS007048','BI Sheet 0.70mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(152,'BS006048','BI Sheet 0.60mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(153,'BS004048','BI Sheet 0.40mm x 4 Ft. X 8 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(154,'BS007036','BI Sheet 0.7mm x 3Ft. X 6 Ft.',1,6,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(155,'GC01804F','Galvanized Coil 1.8mm x 4 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(156,'GC01404F','Galvanized Coil 1.4mm x 4 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(157,'GC01104F','Galvanized Coil 1.1mm x 4 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(158,'GC01103F','Galvanized Coil 1.1mm x 3 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(159,'GC00904F','Galvanized Coil 0.9mm x 4 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(160,'GC00804F','Galvanized Coil 0.8mm x 4 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(161,'GC00704F','Galvanized Coil 0.7mm x 4 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(162,'GC00604F','Galvanized Coil 0.6mm x 4 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(163,'GC00504F','Galvanized Coil 0.5mm x 4 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(164,'GC00404F','Galvanized Coil 0.4mm x 4 Feet',1,7,2,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(165,'GS018048','Galvanized Sht 1.8mm x 4F X 8F',1,7,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(166,'GS014048','Galvanized Sht 1.4mm x 4F X 8F',1,7,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(167,'GS011048','Galvanized Sht 1.1mm x 4F X 8F',1,7,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(168,'GS009048','Galvanized Sht 0.9mm x 4F X 8F',1,7,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(169,'GS008048','Galvanized Sht 0.8mm x 4F X 8F',1,7,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(170,'GS007048','Galvanized Sht 0.7mm x 4F X 8F',1,7,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(171,'GS006048','Galvanized Sht 0.6mm x 4F X 8F',1,7,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(172,'GS004048','Galvanized Sht 0.4mm x 4F X 8F',1,7,14,1,'2015-08-13 06:14:47','0000-00-00 00:00:00',1,0),(173,'RJ038L16','Brass Tube 1/8 (L) x 16 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(174,'RJ014L16','Brass Tube 1/4 (L) x 16 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(175,'RJ056L16','Brass Tube 5/16 (L) x 16 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(176,'RJ056H16','Brass Tube 5/16 x 3/16 (H) x 16 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(177,'RJ038A20','Brass Tube 3/8 x 5/16 x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(178,'RJ038B20','Brass Tube 3/8 x 9/32 x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(179,'RJ038C20','Brass Tube 3/8 x 1/4 x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(180,'RJ038E20','Brass Tube 3/8 x 7/32 x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(181,'RJ076L20','Brass Tube 7/16 (L) x 16 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(182,'RJ012L20','Brass Tube 1/2 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(183,'RJ012M20','Brass Tube 1/2 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(184,'RJ012H20','Brass Tube 1/2 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(185,'RJ096L20','Brass Tube 9/16 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(186,'RJ058L20','Brass Tube 5/8 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(187,'RJ058M20','Brass Tube 5/8 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(188,'RJ058H20','Brass Tube 5/8 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(189,'RJ034L20','Brass Tube 3/4 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(190,'RJ034M20','Brass Tube 3/4 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(191,'RJ034H16','Brass Tube 3/4 (H) x 16 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(192,'RJ034H20','Brass Tube 3/4 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(193,'RJ078L20','Brass Tube 7/8 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(194,'RJ078M20','Brass Tube 7/8 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(195,'RJ078H20','Brass Tube 7/8 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(196,'RJ100L20','Brass Tube 1 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(197,'RJ100M20','Brass Tube 1 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(198,'RJ100H20','Brass Tube 1 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(199,'RJ118L20','Brass Tube 1-1/8 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(200,'RJ118M20','Brass Tube 1-1/8 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(201,'RJ118H20','Brass Tube 1-1/8 (EH) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(202,'RJ114L26','Brass Tube 1-1/4 (L) x 2.6 Mtr.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(203,'RJ114L20','Brass Tube 1-1/4 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(204,'RJ114M20','Brass Tube 1-1/4 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(205,'RJ114H20','Brass Tube 1-1/4 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(206,'RJ138L20','Brass Tube 1-3/8 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(207,'RJ138H20','Brass Tube 1-3/8 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(208,'RJ112L20','Brass Tube 1-1/2 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(209,'RJ112M20','Brass Tube 1-1/2 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(210,'RJ112H20','Brass Tube 1-1/2 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(211,'RJ134L20','Brass Tube 1-3/4 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(212,'RJ134M20','Brass Tube 1-3/4 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(213,'RJ134H20','Brass Tube 1-3/4 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(214,'RJ115Z20','Brass Tube 1-15/16 x 1-13/16 x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(215,'RJ200L20','Brass Tube 2 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(216,'RJ200M20','Brass Tube 2 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(217,'RJ200H20','Brass Tube 2 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(218,'RJ20EH20','Brass Tube 2 (EH) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(219,'RJ214L20','Brass Tube 2-1/4 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(220,'RJ212L20','Brass Tube 2-1/2 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(221,'RJ212M20','Brass Tube 2-1/2 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(222,'RJ212H20','Brass Tube 2-1/2 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(223,'RJ300L20','Brass Tube 3 (L) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(224,'RJ300M20','Brass Tube 3 (M) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(225,'RJ400H20','Brass Tube 4 (H) x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(226,'RJ012D20','Brass Tube (SHAFT CASING) 1/2 I.D x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(227,'RJ058D20','Brass Tube (SHAFTCASING) 5/8 I.D x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(228,'RJ034D20','Brass Tube (SHAFT CASING) 3/4 I.D x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(229,'RJ078D20','Brass Tube (SHAFT CASING) 7/8 I.D x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(230,'RJ100D20','Brass Tube (SHAFT CASING) 1 I.D x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(231,'RJ114D20','Brass Tube (SHAFT CASING) 1-1/4 I.D x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(232,'RJ112D20','Brass Tube (SHAFT CASING) 1-1/2 I.D x 20 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(233,'RN001820','Brass Pipe 1/8 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(234,'RN001420','Brass Pipe 1/4 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(235,'RN003820','Brass Pipe 3/8 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(236,'RN001220','Brass Pipe 1/2 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(237,'RN003420','Brass Pipe 3/4 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(238,'RN010020','Brass Pipe 1 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(239,'RN011420','Brass Pipe 1-1/4 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(240,'RN011220','Brass Pipe 1-1/2 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(241,'RN020020','Brass Pipe 2 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(242,'RN021220','Brass Pipe 2-1/2 x 20 Ft.',1,3,10,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(243,'RJF01216','Brass Flutted Tube 1/2 x 16 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(244,'RJF03416','Brass Flutted Tube 3/4 x 16 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(245,'RJF10016','Brass Flutted Tube 1 x 16 Ft.',1,3,6,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(246,'RS005048','Brass Sheet 0.5mm x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(247,'RS006048','Brass Sheet #24 (0.6mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(248,'RS007048','Brass Sheet #22 (0.7mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(249,'RS009018','Brass Sheet #20 (0.9mm) x 1 Mtr x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(250,'RS009048','Brass Sheet #20 (0.9mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(251,'RS010048','Brass Sheet 1.0mm x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(252,'RS011048','Brass Sheet #18 (1.1mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(253,'RS015048','Brass Sheet #16 (1.5mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(254,'RS019548','Brass Sheet #14 (1.95mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(255,'RS025048','Brass Sheet 2.5mm x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(256,'RS030048','Brass Sheet 1/8 (3.0mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(257,'RS045048','Brass Sheet 3/16 (4.5mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(258,'RS060048','Brass Sheet 1/4 (6.0mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(259,'RS080048','Brass Sheet 5/16 (8.0mm) x 4 Ft. X 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(260,'RS095348','Brass Sheet 3/8 (9.53mm) x 4 Ft. X 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(261,'RS122048','Brass Sheet 1/2 (12.2mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(262,'RS152048','Brass Sheet 5/8 (15.2mm) x 4 Ft. X 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(263,'RS190548','Brass Sheet 3/4 (19.05mm ) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(264,'RS254048','Brass Sheet 1 (25.4mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(265,'RS323048','Brass Sheet 1-1/4 (32.3mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(266,'RS381048','Brass Sheet 1-1/2 (38.1mm) x 4 Ft. x 8 Ft.',1,3,14,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(267,'RF803820','Brass Flat Bar 1/8 x 3/8 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(268,'RF801210','Brass Flat Bar 1/8 x 1/2 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(269,'RF801220','Brass Flat Bar 1/8 x 1/2 x 20 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(270,'RF805810','Brass Flat Bar 1/8 x 5/8 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(271,'RF803410','Brass Flat Bar 1/8 x 3/4 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(272,'RF810010','Brass Flat Bar 1/8 x 1 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(273,'RF820010','Brass Flat Bar 1/8 x 2 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(274,'RF821210','Brass Flat Bar 1/8 x 2-1/2 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(275,'RF821212','Brass Flat Bar 1/8 x 2-1/2 x 12 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(276,'RF601210','Brass Flat Bar 3/16 x 1/2 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(277,'RF601220','Brass Flat Bar 3/16 x 1/2 x 20 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(278,'RF610010','Brass Flat Bar 3/16 x 1 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(279,'RF611420','Brass Flat Bar 3/16 x 1-1/4 x 20 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(280,'RF611210','Brass Flat Bar 3/16 x 1-1/2 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(281,'RF611220','Brass Flat Bar 3/16 x 1-1/2 x 20 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(282,'RF620010','Brass Flat Bar 3/16 x 2 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(283,'RF401210','Brass Flat Bar 1/4 x 1/2 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(284,'RF403410','Brass Flat Bar 1/4 x 3/4 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(285,'RF410010','Brass Flat Bar 1/4 x 1 x 10 Ft.',1,3,4,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(286,'RA801220','Brass Angle Bar 1/8 x 1/2 x 20 Ft.',1,3,1,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(287,'RA803420','Brass Angle Bar 1/8 x 3/4 x 20 Ft.',1,3,1,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(288,'RQ003810','Brass Square Bar 3/8 x 10 Ft.',1,3,12,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(289,'RQ00344M','Brass Square Bar 3/4 x 4 mtrs.',1,3,12,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(290,'RQ010010','Brass Square Bar 1 x 10 Ft.',1,3,12,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(291,'RQ011410','Brass Square Bar 1-1/4 x 10 Ft.',1,3,12,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(292,'RH00144M','Brass Hexagonal Bar 1/4 X 4 mtrs.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(293,'RH00564M','Brass Hexagonal Bar 5/16 x 4 mtrs.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(294,'RH003810','Brass Hexagonal Bar 3/8 x 10 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(295,'RH001210','Brass Hexagonal Bar 1/2 x 10 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(296,'RH009620','Brass Hexagonal Bar 9/16 x 20 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(297,'RH005810','Brass Hexagonal Bar 5/8 x 10 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(298,'RH00344M','Brass Hexagonal Bar 3/4 x 4 mtrs.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(299,'RH007810','Brass Hexagonal Bar 7/8 x 10 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(300,'RH1516ZZ','Brass Hexagonal Bar 15/16',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(301,'RH010010','Brass Hexagonal Bar 1 x 10 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(302,'RH011810','Brass Hexagonal Bar 1-1/8 x 10 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(303,'RH011410','Brass Hexagonal Bar 1-1/4 x 10 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(304,'RH011210','Brass Hexagonal Bar 1-1/2 x 10 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(305,'RH013410','Brass Hexagonal Bar 1-3/4 x 10 Ft.',1,3,5,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(306,'RR003610','Brass Round Bar 3/16 X 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(307,'RR00144M','Brass Round Bar 1/4 x 4 mtrs.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(308,'RR00564M','Brass Round Bar 5/16 x 4 mtrs.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(309,'RR003810','Brass Round Bar 3/8 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(310,'RR007610','Brass Round Bar 7/16 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(311,'RR001210','Brass Round Bar 1/2 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(312,'RR005810','Brass Round Bar 5/8 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(313,'RR011610','Brass Round Bar 11/16 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(314,'RR00344M','Brass Round Bar 3/4 x 4 mtrs.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(315,'RR007810','Brass Round Bar 7/8 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(316,'RR010010','Brass Round Bar 1 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(317,'RR011810','Brass Round Bar 1-1/8 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(318,'RR011410','Brass Round Bar 1-1/4 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(319,'RR013810','Brass Round Bar 1-3/8',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(320,'RR011210','Brass Round Bar 1-1/2 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(321,'RR013410','Brass Round Bar 1-3/4 x 10 Ft.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(322,'RR02004M','Brass Round Bar 2 x 4 mtrs.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(323,'RR0214ZZ','Brass Round Bar 2-1/4',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(324,'RR02124M','Brass Round Bar 2-1/2 x 4 mtrs.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(325,'RR0234ZZ','Brass Round Bar 2-3/4',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(326,'RR03003M','Brass Round Bar 3 x 3 mtrs.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(327,'RR0314ZZ','Brass Round Bar 3-1/4',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(328,'RR0312ZZ','Brass Round Bar 3-1/2',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(329,'RR04002M','Brass Round Bar 4 x 2 mtrs.',1,3,13,1,'2015-08-13 06:20:48','0000-00-00 00:00:00',1,0),(330,'AA801216','Alum Angle Bar 1/8 x 1/2 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(331,'AA803416','Alum Angle Bar 1/8 x 3/4 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(332,'AA810016','Alum Angle Bar 1/8 x 1 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(333,'AA811416','Alum Angle Bar 1/8 x 1-1/4 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(334,'AA811216','Alum Angle Bar 1/8 x 1-1/2 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(335,'AA820016','Alum Angle Bar 1/8 x 2 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(336,'AA610016','Alum Angle Bar 3/16 x 1 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(337,'AA611216','Alum Angle Bar 3/16 x 1-1/2  x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(338,'AA620016','Alum Angle Bar 3/16 x 2 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(339,'AA410016','Alum Angle Bar 1/4 x 1 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(340,'AA411216','Alum Angle Bar 1/4 x 1-1/2 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(341,'AA420016','Alum Angle Bar 1/4 x 2 x 16 Ft.',1,5,1,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(342,'AF101216','Alum Flat Bar 1/16 x 1/2 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(343,'AF801216','Alum Flat Bar 1/8 x 1/2 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(344,'AF805816','Alum Flat Bar 1/8 x 5/8 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(345,'AF803416','Alum Flat Bar 1/8 x 3/4 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(346,'AF810016','Alum Flat Bar 1/8 x 1 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(347,'AF811216','Alum Flat Bar 1/8 x 1-1/2 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(348,'AF820016','Alum Flat Bar 1/8 x 2 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(349,'AF830016','Alum Flat Bar 1/8 x 3 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(350,'AF610016','Alum Flat Bar 3/16 x 1 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(351,'AF611216','Alum Flat Bar 3/16 x 1-1/2 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(352,'AF620016','Alum Flat Bar 3/16 x 2 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(353,'AF401216','Alum Flat Bar 1/4 x 1/2 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(354,'AF403416','Alum Flat Bar 1/4 x 3/4 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(355,'AF410016','Alum Flat Bar 1/4 x 1 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(356,'AF411216','Alum Flat Bar 1/4 x 1-1/2 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(357,'AF420016','Alum Flat Bar 1/4 x 2 x 16 Ft.',1,5,4,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(358,'AJ010016','Alum Tube 1\" x 16 Ft.',1,5,6,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(359,'AJ011416','Alum Tube 1-1/4\" x 16 Ft.',1,5,6,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(360,'AJ011216','Alum Tube 1-1/2\" x 16 Ft.',1,5,6,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(361,'AJ020016','Alum Tube 2\" (1.5mm) x 16 Ft.',1,5,6,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(362,'AJ030016','Alum Tube 3\" (1.5mm) x 16 Ft.',1,5,6,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(363,'AJ040016','Alum Tube 4\" (1.5mm) x 16 Ft.',1,5,6,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(364,'ARK03616','Alum Round Bar (Ord) 3/16 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(365,'ARK01416','Alum Round Bar (Ord) 1/4 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(366,'ARK05616','Alum Round Bar (Ord) 5/16 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(367,'ARK03816','Alum Round Bar (Ord) 3/8 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(368,'ARK01216','Alum Round Bar (Ord) 1/2 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(369,'ARK05816','Alum Round Bar (Ord) 5/8 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(370,'ARK03416','Alum Round Bar (Ord) 3/4 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(371,'ARK10016','Alum Round Bar (Ord) 1 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(372,'ARK11416','Alum Round Bar (Ord) 1-1/4 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(373,'ARK11216','Alum Round Bar (Ord) 1-1/2 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(374,'ARK20016','Alum Round Bar (Ord) 2 x 16 Ft.',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(375,'AQ001216','Alum SQR Bar 1/2\" x 16 Ft.',1,5,12,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(376,'AK001216','Alum SQR Tube 1/2\" x 16 Ft.',1,5,7,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(377,'AN012ORD','Alum Pipe 1/2\" x 20 Ft.',1,5,10,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(378,'AN034S40','Alum Pipe 3/4\" (Sch 40) x 20 Ft.',1,5,10,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(379,'ARL0114T','Alum Round Bar (T-6) 1-1/4\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(380,'ARL0112T','Alum Round Bar (T-6) 1-1/2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(381,'ARL0134T','Alum Round Bar (T-6) 1-3/4\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(382,'ARL0200T','Alum Round Bar (T-6) 2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(383,'ARL0212T','Alum Round Bar (T-6) 2-1/2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(384,'ARL0300T','Alum Round Bar (T-6)  3\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(385,'ARL0312T','Alum Round Bar (T-6) 3-1/2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(386,'ARL0400T','Alum Round Bar (T-6)  4\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(387,'ARL0412T','Alum Round Bar (T-6) 4-1/2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(388,'ARL0500T','Alum Round Bar (T-6)  5\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(389,'ARL0512T','Alum Round Bar (T-6) 5-1/2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(390,'ARL0600T','Alum Round Bar (T-6)  6\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(391,'ARL0612T','Alum Round Bar (T-6) 6-1/2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(392,'ARL0700T','Alum Round Bar (T-6)  7\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(393,'ARL0712T','Alum Round Bar (T-6) 7-1/2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(394,'ARL0800T','Alum Round Bar (T-6)  8\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(395,'ARL0812T','Alum Round Bar (T-6) 8-1/2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(396,'ARL0900T','Alum Round Bar (T-6)  9\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(397,'ARL0912T','Alum Round Bar (T-6) 9-1/2\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(398,'ARL1000T','Alum Round Bar (T-6)  10\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(399,'ARL1100T','Alum Round Bar (T-6)  11\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(400,'ARL1200T','Alum Round Bar (T-6)  12\"  x 2 mtrs',1,5,13,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(401,'ASL0030H','Alum Plate (T-6) 1/8\" (3.0mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(402,'ASL0048H','Alum Plate (T-6) 3/16\" (4.8mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(403,'ASL0063H','Alum Plate (T-6) 1/4\" (6.35mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(404,'ASL0079H','Alum Plate (T-6) 5/16\" (7.94mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(405,'ASL0100H','Alum Plate (T-6)  10.0mm  x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(406,'ASL0127H','Alum Plate (T-6) 1/2\" (12.7mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(407,'ASL0158H','Alum Plate (T-6)  5/8\" (15.88mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(408,'ASL0190H','Alum Plate (T-6)  3/4\" (19.05mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(409,'ASL0222H','Alum Plate (T-6)  7/8\" (22.23mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(410,'ASL0254H','Alum Plate (T-6)  1\" (25.4mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(411,'ASL0317H','Alum Plate (T-6)  1-1/4\" (31.75mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(412,'ASL0350H','Alum Plate (T-6)  1-3/8\" (35.0mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(413,'ASL0381H','Alum Plate (T-6)  1-1/2\" (38.1mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(414,'ASL0450H','Alum Plate (T-6)  1-3/4\" (45.0mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(415,'ASL0508H','Alum Plate (T-6)  2\" (50.8mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(416,'ASL0571H','Alum Plate (T-6)  2-1/4\" (57.15mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(417,'ASL0635H','Alum Plate (T-6)  2-1/2\" (63.5mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(418,'ASL0698H','Alum Plate (T-6)  2-3/4\" (69.85mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(419,'ASL0762H','Alum Plate (T-6)  3\" (76.2mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(420,'ASL0889H','Alum Plate (T-6)  3-1/2\" (88.9mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(421,'ASL1010H','Alum Plate (T-6)  4\" (101.6mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(422,'ASL1140H','Alum Plate (T-6)  4-1/2\" (114.3mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(423,'ASL1270H','Alum Plate (T-6)  5\" (127mm) x 4Fx8F',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(424,'ASK0040H','Alum Sht #26 (0.4mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(425,'ASK0060H','Alum Sht #24 (0.6mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(426,'ASK0080H','Alum Sht #22 (0.8mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(427,'ASK0100H','Alum Sht #19 (1.0mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(428,'ASK0120H','Alum Sht #18 (1.2mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(429,'ASK0150H','Alum Sht #16 (1.5mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(430,'ASK0195H','Alum Sht #14 (1.95mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(431,'ASK0245H','Alum Sht 3/32\" (2.45mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(432,'ASK0300H','Alum Sht 1/8\" (3.0mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(433,'ASK0450H','Alum Sht 3/16\" (4.5mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(434,'ASK0600H','Alum Sht 1/4\" (6.0mm) ORD x 4 Ft. x 8 Ft.',1,5,14,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(435,'ACK0154F','Alum Coil #16 (1.5mm) x 4 Feet',1,5,2,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(436,'ACK0124F','Alum Coil #18 (1.2mm) x 4 Feet',1,5,2,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(437,'ACK0104F','Alum Coil #19 (1.0mm) x 4 Feet',1,5,2,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(438,'ACK0103F','Alum Coil #19 (1.0mm) x 3 Feet',1,5,2,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(439,'ACK0084F','Alum Coil #22 (0.8mm) x 4 Feet',1,5,2,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(440,'ACK0064F','Alum Coil #24 (0.6mm) x 4 Feet',1,5,2,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(441,'ACK0044F','Alum Coil #26 (0.4mm) x 4 Feet',1,5,2,1,'2015-08-13 06:26:22','0000-00-00 00:00:00',1,0),(442,'SW40143A','SS-304 Welded Wire Scrn 1/4 x 1/4 x 3Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(443,'SW40144A','SS-304 Welded Wire Scrn 1/4 x 1/4 x 4Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(444,'SW40133A','SS-304 Welded Wire Scrn 1/3 x 1/3 x 3Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(445,'SW40134A','SS-304 Welded Wire Scrn 1/3 x 1/3 x 4Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(446,'SW40123A','SS-304 Welded Wire Scrn 1/2 x 1/2 x 3Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(447,'SW40124A','SS-304 Welded Wire Scrn 1/2 x 1/2 x 4Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(448,'SW40343A','SS-304 Welded Wire Scrn 3/4 x 3/4 x 3Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(449,'SW40344A','SS-304 Welded Wire Scrn 3/4 x 3/4 x 4Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(450,'SW41003A','SS-304 Welded Wire Scrn 1 x 1 x 3 Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(451,'SW41004A','SS-304 Welded Wire Scrn 1 x 1 x 4 Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(452,'SW4R013A','SS-304 Rect WeldedWire Scrn 1 x 1/2 x 3Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(453,'SW4R014A','SS-304 Rect WeldedWire Scrn 1 x 1/2 x 4Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(454,'SW41123A','SS-304 WeldedWire Scrn 1-1/2 x 1-1/2 x 3Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(455,'SW41124A','SS-304 WeldedWire Scrn 1-1/2 x 1-1/2 x 4Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(456,'SW42003A','SS-304 Welded Wire Scrn 2 x 2 x 3Fx100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(457,'SW42004A','SS-304 Welded Wire Scrn 2 x 2 x 4Fx 100F',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(458,'SM4S343A','SS-304 Woven Mesh 3/4 x 3Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(459,'SM40023A','SS-304 Woven Mesh 1/2 (No. 2) x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(460,'SM40024A','SS-304 Woven Mesh 1/2 (No. 2) x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(461,'SM40033A','SS-304 Woven Mesh No. 3 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(462,'SM40034A','SS-304 Woven Mesh No. 3 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(463,'SM40043A','SS-304 Woven Mesh No. 4 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(464,'SM40044A','SS-304 Woven Mesh No. 4 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(465,'SM40051A','SS-304 Woven Mesh No. 5 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(466,'SM40053A','SS-304 Woven Mesh No. 5 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(467,'SM40054A','SS-304 Woven Mesh No. 5 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(468,'SM40061A','SS-304 Woven Mesh No. 6 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(469,'SM40063A','SS-304 Woven Mesh No. 6 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(470,'SM40064A','SS-304 Woven Mesh No. 6 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(471,'SM40081A','SS-304 Woven Mesh No. 8 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(472,'SM40083A','SS-304 Woven Mesh No. 8 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(473,'SM40084A','SS-304 Woven Mesh No.8 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(474,'SM40101A','SS-304 Woven Mesh No. 10 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(475,'SM40103A','SS-304 Woven Mesh No. 10 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(476,'SM40104A','SS-304 Woven Mesh No. 10 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(477,'SM40121A','SS-304 Woven Mesh No. 12 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(478,'SM40123A','SS-304 Woven Mesh No. 12 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(479,'SM40124A','SS-304 Woven Mesh No. 12 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(480,'SM40141A','SS-304 Woven Mesh No. 14 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(481,'SM40143A','SS-304 Woven Mesh No. 14 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(482,'SM40144A','SS-304 Woven Mesh No. 14 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(483,'SM40161A','SS-304 Woven Mesh No. 16 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(484,'SM40163A','SS-304 Woven Mesh No. 16 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(485,'SM40164A','SS-304 Woven Mesh No. 16 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(486,'SM40181A','SS-304 Woven Mesh No. 18 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(487,'SM40183A','SS-304 Woven Mesh No. 18 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(488,'SM40184A','SS-304 Woven Mesh No. 18 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(489,'SM40201A','SS-304 Woven Mesh No. 20 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(490,'SM40203A','SS-304 Woven Mesh No. 20 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(491,'SM40204A','SS-304 Woven Mesh No. 20 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(492,'SM40251A','SS-304 Woven Mesh No. 25 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(493,'SM40253A','SS-304 Woven Mesh No. 25 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(494,'SM40254A','SS-304 Woven Mesh No. 25 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(495,'SM40301A','SS-304 Woven Mesh No. 30 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(496,'SM40303A','SS-304 Woven Mesh No. 30 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(497,'SM40304A','SS-304 Woven Mesh No. 30 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(498,'SM40401A','SS-304 Woven Mesh No. 40 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(499,'SM40403A','SS-304 Woven Mesh No. 40 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(500,'SM40404A','SS-304 Woven Mesh No. 40 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(501,'SM40501A','SS-304 Woven Mesh No. 50 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(502,'SM40503A','SS-304 Woven Mesh No. 50 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(503,'SM40504A','SS-304 Woven Mesh No. 50 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(504,'SM40601A','SS-304 Woven Mesh No. 60 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(505,'SM40603A','SS-304 Woven Mesh No. 60 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(506,'SM40604A','SS-304 Woven Mesh No. 60 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(507,'SM40801A','SS-304 Woven Mesh No. 80 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(508,'SM40803A','SS-304 Woven Mesh No. 80 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(509,'SM40804A','SS-304 Woven Mesh No. 80 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(510,'SM41001A','SS-304 Woven Mesh No. 100 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(511,'SM41003A','SS-304 Woven Mesh No. 100 x 3 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(512,'SM41004A','SS-304 Woven Mesh No. 100 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(513,'SM41201A','SS-304 Woven Mesh No. 120 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(514,'SM41204A','SS-304 Woven Mesh No. 120 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(515,'SM41501A','SS-304 Woven Mesh No. 150 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(516,'SM41504A','SS-304 Woven Mesh No. 150 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(517,'SM41801A','SS-304 Woven Mesh No. 180 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(518,'SM41804A','SS-304 Woven Mesh No. 180 x 4 Ft. x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(519,'SM62001A','SS-316 Mesh (T-316) No. 200 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(520,'SM62501A','SS-316 Mesh (T-316) No. 250 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(521,'SM63001A','SS-316 Mesh (T-316) No. 300 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(522,'SM64001A','SS-316 Mesh (T-316) No. 400 x 1 meter x 100 Ft.',1,2,9,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(523,'SWINSC3A','Stainless (T-304) Window Screen 3 Ft. x 100 Ft.',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(524,'SWINSC4A','Stainless (T-304) Window Screen 4 Ft. x 100 Ft.',1,2,17,1,'2015-08-13 06:28:31','0000-00-00 00:00:00',1,0),(525,'AXFA032C','Alum Exp 0.4mm x 1.5mm x 3.0mm x 2F x 8F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(526,'AXFA052C','Alum Exp 0.4mm x 2.0mm x 4.0mm x 2F x 8F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(527,'AXRE104B','Alum Exp Metal 0.8mm x 1/4 x 1/2 x 4F x 60F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(528,'AXRG104B','Alum Exp Metal 1.0mm x 1/4 x 1/2 x 4F x 60F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(529,'AXBG183B','Alum Exp-Brown 1.0mm x 1/2 x 1 x 3F x 60F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(530,'AXBG184B','Alum Exp-Brown 1.0mm x 1/2 x 1 x 4F x 60F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(531,'AXGG183B','Alum Exp-Gold 1.0mm x 1/2 x 1 x 3F x 60F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(532,'AXGG184B','Alum Exp-Gold 1.0mm x 1/2 x 1 x 4F x 60F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(533,'AXFG183B','Alum Exp Metal  1.0mm x 1/2 x 1 x 3F x 60F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(534,'AXFG184B','Alum Exp Metal  1.0mm x 1/2 x 1 x 4F x 60F',1,5,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(535,'BXFC074C','BI Exp #24 x 1/8 x 1/4  FLATTENED  x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(536,'BXFD074C','BI Exp #22 x 1/8 x 1/4  FLATTENED x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(537,'BXRD104C','BI Exp #22 x 1/4 x 1/2  (6x12mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(538,'BXFD104C','BI Exp #22 x 1/4 x 1/2  FLATTENED x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(539,'BXRF104C','BI Exp #20 x 1/4 x 1/2  (6x12mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(540,'BXRH104C','BI Exp #18 x 1/4 x 1/2  (6x12mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(541,'BXRF134C','BI Exp #20 x 3/8 x 3/4  (9.5x19mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(542,'BXFF134C','BI Exp #20 x 3/8 x 3/4  FLATTENED  x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(543,'BXRH134C','BI Exp #18 x 3/8 x 3/4  (9.5x19mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(544,'BXFH134C','BI Exp #18 x 3/8 x 3/4  FLATTENED x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(545,'BXRJ134C','BI Exp #16 x 3/8 x 3/4  (9.5x19mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(546,'BXRF184C','BI Exp #20 x 1/2 x 1 (13x25mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(547,'BXRH184C','BI Exp #18 x 1/2 x 1 (13x25mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(548,'BXFH184C','BI Exp #18 x 1/2 x 1 (FLATTENED) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(549,'BXRJ184C','BI Exp #16 x 1/2 x 1 (13x25mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(550,'BXFJ184C','BI Exp #16 x 1/2 x 1 (FLATTENED) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(551,'BXRK184C','BI Exp #14 x 1/2 x 1 (13x25mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(552,'BXFK184C','BI Exp #14 x 1/2 x 1 (FLATTENED) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(553,'BXRJ204C','BI Exp #16 x 5/8 x 1-1/4 (16x32mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(554,'BXRK204C','BI Exp #14 x 5/8 x 1-1/4 (16x32mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(555,'BXRQ204C','BI Exp 3mm x 5/8 x 1-1/4 (16x32mm) x 4F X 8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(556,'BXRJ234C','BI Exp #16 x 3/4 x 1-1/2 (19x38mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(557,'BXRK234C','BI Exp #14 x 3/4 x 1-1/2 (19x38mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(558,'BXRQ234C','BI Exp 3.0mm x 3/4 x 1-1/2 (19x38mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(559,'BXRR234C','BI Exp 4.0mm x 3/4 x 1-1/2 (19x38mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(560,'BXRS234C','BI Exp 4.5mm x 3/4 x 1-1/2 (19x38mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(561,'BXPQ274C','BI Exp 3.0mm x 3/4 x 1 (19x25mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(562,'BXRK304C','BI Exp 2.0mm x 7/8 x 2 (22x50mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(563,'BXRQ304C','BI Exp 3.0mm x 7/8 x 2 (22x50mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(564,'BXRQ334C','BI Exp 3.0mm x 1 x 2  (25x50mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(565,'BXRQ374C','BI Exp 3.0mm x 1 x 3  (25x75mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(566,'BXRS374C','BI Exp 4.5mm x 1 x 3  (25x75mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(567,'BXRU374C','BI Exp 6.0mm x 1 x 3  (25x75mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(568,'BXRQ404C','BI Exp 3.0mm x 1-3/8x4  (34x101mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(569,'BXRS404C','BI Exp 4.5mm x 1-3/8x4  (34x101mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(570,'BXRU404C','BI Exp 6.0mm x 1-3/8x4  (34x101mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(571,'BXRQ454C','BI Exp 3.0mm x 1-3/8x5-1/4  (34x135mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(572,'BXRS454C','BI Exp 4.5mm x 1-3/8x5-1/4  (34x135mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(573,'BXRU454C','BI Exp 6.0mm x 1-3/8x5-1/4  (34x135mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(574,'BXRQ534C','BI Exp 3.0mm x 1-1/2x3  (38x76mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(575,'BXRR534C','BI Exp 4.0mm x 1-1/2x3  (38x76mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(576,'BXRS534C','BI Exp 4.5mm x 1-1/2x3  (38x76mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(577,'BXRT534C','BI Exp 5.0mm x 1-1/2x3  (38x76mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(578,'BXRU534C','BI Exp 6.0mm x 1-1/2x3  (38x76mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(579,'BXRQ604C','BI Exp 3.0mm x 1-3/4x3-1/2  (45x90mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(580,'BXRS604C','BI Exp 4.5mm x 1-3/4x3-1/2  (45x90mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(581,'BXRU604C','BI Exp 6.0mm x 1-3/4x3-1/2  (45x90mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(582,'BXRQ704C','BI Exp 3.0mm x 2 x 3  (50x75mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(583,'BXRS704C','BI Exp 4.5mm x 2 x 3  (50x75mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(584,'BXRU704C','BI Exp 6.0mm x 2 x 3  (50x75mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(585,'BXRQ784C','BI Exp 3.0mm x 2 x 4  (50x100mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(586,'BXRS784C','BI Exp 4.5mm x 2 x 4  (50x100mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(587,'BXRU784C','BI Exp 6.0mm x 2 x 4  (50x100mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(588,'BXRS904C','BI Exp 4.5mm x 2 x 6  (50x152mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(589,'BXRU904C','BI Exp 6.0mm x 2 x 6  (50x152mm) x 4X8F',1,6,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(590,'SX4A032C','SS-304 Exp 0.4mm x 1.5x3.0mm x 2F x 8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(591,'SX4A052C','SS-304 Exp 0.4mm x 2.0x4.0mm x 2F x 8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(592,'SX4B074C','SS-304 Exp 0.5mm x 1/8x1/4 (3x6mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(593,'SX4D104C','SS-304 Exp 0.7mm x 1/4x1/2 (6x12mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(594,'SX4F104C','SS-304 Exp 0.9mm x 1/4x1/2 (6x12mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(595,'SX4G104C','SS-304 Exp 1.0mm x 1/4x1/2 (6x12mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(596,'SX4D134C','SS-304 Exp 0.7mm x 3/8x3/4 (9.5x19mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(597,'SX4F134C','SS-304 Exp 0.9mm x 3/8x3/4 (9.5x19mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(598,'SX4H134C','SS-304 Exp 1.2mm x 3/8x3/4 (9.5x19mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(599,'SX4D184C','SS-304 Exp 0.7mm x 1/2x1 (13x25mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(600,'SX4G184C','SS-304 Exp 1.0mm x 1/2x1 (13x25mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(601,'SX4J184C','SS-304 Exp 1.5mm x 1/2x1 (13x25mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(602,'SX4K184C','SS-304 Exp 2.0mm x 1/2x1 (13x25mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(603,'SX4J204C','SS-304 Exp 1.5mm x 5/8X1-1/4 (16X32mm)x4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(604,'SX4K204C','SS-304 Exp 2.0mm x 5/8X1-1/4 (16X32mm)x4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(605,'SX4K234C','SS-304 Exp 2.0mm x 3/4x1-1/2 (19x38mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(606,'SX4Q234C','SS-304 Exp 3.0mm x 3/4x1-1/2 (19x38mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(607,'SX4R234C','SS-304 Exp 4.0mm x 3/4x1-1/2 (19x38mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(608,'SX4S234C','SS-304 Exp 4.5mm x 3/4x1-1/2 (19x38mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(609,'SX4Q334C','SS-304 Exp 3.0mm x 1 x 2  (25x50mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(610,'SX4S334C','SS-304 Exp 4.5mm x 1 x 2  (25x50mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(611,'SX4Q374C','SS-304 Exp 3.0mm x 1 x 3  (25x75mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(612,'SX4S374C','SS-304 Exp 4.5mm x 1 x 3  (25x75mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(613,'SX4Q534C','SS-304 Exp 3.0mm x 1-1/2 x 3 (38x76mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(614,'SX4S534C','SS-304 Exp 4.5mm x 1-1/2 x 3 (38x76mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(615,'SX4Q784C','SS-304 Exp 3.0mm x 2 x 4 (50x100mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(616,'SX4S784C','SS-304 Exp 4.5mm x 2 x 4 (50x100mm) x 4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(617,'SX6H234C','SS-316L Exp 1.2mm x 3/8x3/4 (19x38mm) x4x8F',1,2,18,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(618,'SE4CT14C','SS-304 Checkered 0.6mm TEE x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(619,'SE4DT14C','SS-304 Checkered 0.7mm TEE x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(620,'SE4ET14C','SS-304 Checkered 0.8mm TEE x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(621,'SE4FT14C','SS-304 Checkered 0.9mm TEE x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(622,'SE4GT14C','SS-304 Checkered 1.0mm TEE x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(623,'SE4HT14C','SS-304 Checkered 1.2mm TEE x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(624,'SE4CL14C','SS-304 Checkered 0.6mm LEAF x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(625,'SE4DL14C','SS-304 Checkered 0.7mm LEAF x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(626,'SE4EL14C','SS-304 Checkered 0.8mm LEAF x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(627,'SE4FL14C','SS-304 Checkered 0.9mm LEAF x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(628,'SE4GL14C','SS-304 Checkered 1.0mm LEAF x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(629,'SE4HL14C','SS-304 Checkered 1.2mm LEAF x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(630,'SE4JL14C','SS-304 Checkered 1.5mm LEAF x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(631,'SE4KL14C','SS-304 Checkered 2.0mm LEAF x 4 Ft. x 8 Ft.',1,2,3,1,'2015-08-13 06:32:51','0000-00-00 00:00:00',1,0),(632,'BP0AR14B','BI Perf #26 x 3.2mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(633,'BP0AR18B','BI Perf #26 x 4.8mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(634,'BP0AR24B','BI Perf #26 x 6.35mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(635,'BP0CR14B','BI Perf #24 x 3.2mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(636,'BP0CR18B','BI Perf #24 x 4.8mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(637,'BP0CR24B','BI Perf #24 x 6.35mm Hole x 4Fx 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(638,'BP0CR26B','BI Perf #24 x 7.94mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(639,'BP0CR28B','BI Perf #24 x 9.53mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(640,'BP0CR42B','BI Perf #24 x 12.7mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(641,'BP0DR06B','BI Perf #22 x 1.6mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(642,'BP0DR08B','BI Perf #22 x 2.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(643,'BP0DR10B','BI Perf #22 x 2.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(644,'BP0DR14B','BI Perf #22 x 3.2mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(645,'BP0DR16B','BI Perf #22 x 4.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(646,'BP0DR18B','BI Perf #22 x 4.8mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(647,'BP0DR20B','BI Perf #22 x 5.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(648,'BP0DR22B','BI Perf #22 x 6.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(649,'BP0DR24B','BI Perf #22 x 6.35mm Hole x 4Fx 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(650,'BP0DR26B','BI Perf #22 x 7.94mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(651,'BP0DR28B','BI Perf #22 x 9.53mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(652,'BP0DR32B','BI Perf #22 x 11.11mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(653,'BP0DR42B','BI Perf #22 x 12.7mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(654,'BP0DR48B','BI Perf #22 x 14.3mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(655,'BP0DR55B','BI Perf #22 x 15.8mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(656,'BP0DR60B','BI Perf #22 x 19.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(657,'BP0DR70B','BI Perf #22 x 25.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(658,'BP0FR06B','BI Perf #20 x 1.6mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(659,'BP0FR08B','BI Perf #20 x 2.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(660,'BP0FR10B','BI Perf #20 x 2.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(661,'BP0FR14B','BI Perf #20 x 3.2mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(662,'BP0FR16B','BI Perf #20 x 4.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(663,'BP0FR18B','BI Perf #20 x 4.8mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(664,'BP0FR20B','BI Perf #20 x 5.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(665,'BP0FR22B','BI Perf #20 x 6.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(666,'BP0FR24B','BI Perf #20 x 6.35mm Hole x 4Fx 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(667,'BP0FR26B','BI Perf #20 x 7.94mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(668,'BP0FR28B','BI Perf #20 x 9.53mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(669,'BP0FR32B','BI Perf #20 x 11.11mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(670,'BP0FR42B','BI Perf #20 x 12.7mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(671,'BP0FR48B','BI Perf #20 x 14.3mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(672,'BP0FR55B','BI Perf #20 x 15.8mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(673,'BP0FR60B','BI Perf #20 x 19.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(674,'BP0FR70B','BI Perf #20 x 25.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(675,'BP0HR06B','BI Perf #18 x 1.6mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(676,'BP0HR08B','BI Perf #18 x 2.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(677,'BP0HR10B','BI Perf #18 x 2.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(678,'BP0HR14B','BI Perf #18 x 3.2mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(679,'BP0HR16B','BI Perf #18 x 4.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(680,'BP0HR18B','BI Perf #18 x 4.8mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(681,'BP0HR20B','BI Perf #18 x 5.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(682,'BP0HR22B','BI Perf #18 x 6.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(683,'BP0HR24B','BI Perf #18 x 6.35mm Hole x 4Fx 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(684,'BP0HR26B','BI Perf #18 x 7.94mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(685,'BP0HR28B','BI Perf #18 x 9.53mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(686,'BP0HR32B','BI Perf #18 x 11.11mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(687,'BP0HR42B','BI Perf #18 x 12.7mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(688,'BP0HR48B','BI Perf #18 x 14.3mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(689,'BP0HR55B','BI Perf #18 x 15.8mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(690,'BP0HR60B','BI Perf #18 x 19.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(691,'BP0HR70B','BI Perf #18 x 25.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(692,'BP0JR06B','BI Perf #16 x 1.6mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(693,'BP0JR08B','BI Perf #16 x 2.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(694,'BP0JR10B','BI Perf #16 x 2.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(695,'BP0JR14B','BI Perf #16 x 3.2mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(696,'BP0JR16B','BI Perf #16 x 4.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(697,'BP0JR18B','BI Perf #16 x 4.8mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(698,'BP0JR20B','BI Perf #16 x 5.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(699,'BP0JR22B','BI Perf #16 x 6.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(700,'BP0JR24B','BI Perf #16 x 6.35mm Hole x 4Fx 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(701,'BP0JR26B','BI Perf #16 x 7.94mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(702,'BP0JR28B','BI Perf #16 x 9.53mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(703,'BP0JR32B','BI Perf #16 x 11.11mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(704,'BP0JR42B','BI Perf #16 x 12.7mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(705,'BP0JR55B','BI Perf #16 x 15.8mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(706,'BP0JR60B','BI Perf #16 x 19.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(707,'BP0JR70B','BI Perf #16 x 25.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(708,'BP0KR06B','BI Perf #14 x 1.6mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(709,'BP0KR08B','BI Perf #14 x 2.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(710,'BP0KR10B','BI Perf #14 x 2.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(711,'BP0KR14B','BI Perf #14 x 3.2mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(712,'BP0KR16B','BI Perf #14 x 4.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(713,'BP0KR18B','BI Perf #14 x 4.8mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(714,'BP0KR20B','BI Perf #14 x 5.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(715,'BP0KR22B','BI Perf #14 x 6.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(716,'BP0KR24B','BI Perf #14 x 6.35mm Hole x 4Fx 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(717,'BP0KR26B','BI Perf #14 x 7.94mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(718,'BP0KR28B','BI Perf #14 x 9.53mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(719,'BP0KR42B','BI Perf #14 x 12.7mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(720,'BP0KR55B','BI Perf #14 x 15.8mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(721,'BP0KR60B','BI Perf #14 x 19.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(722,'BP0KR70B','BI Perf #14 x 25.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(723,'BP0QR14B','BI Perf 3mm x 3.2mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(724,'BP0QR16B','BI Perf 3mm x 4.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(725,'BP0QR18B','BI Perf 3mm x 4.8mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(726,'BP0QR20B','BI Perf 3mm x 5.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(727,'BP0QR22B','BI Perf 3mm x 6.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(728,'BP0QR24B','BI Perf 3mm x 6.35mm Hole x 4Fx 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(729,'BP0QR26B','BI Perf 3mm x 7.94mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(730,'BP0QR28B','BI Perf 3mm x 9.53mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(731,'BP0QR42B','BI Perf 3mm x 12.7mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(732,'BP0QR60B','BI Perf 3mm x 19.0mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(733,'BP0QR70B','BI Perf 3mm x 25.4mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(734,'BP0RR16B','BI Perf 4mm x 4.0mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(735,'BP0RR18B','BI Perf 4mm x 4.8mm Hole x 4F x 8 F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(736,'BP0RR24B','BI Perf 4mm x 6.35mm Hole x 4Fx 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(737,'BP0RR28B','BI Perf 4mm x 9.53mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(738,'BP0RR42B','BI Perf 4mm x 12.7mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(739,'BP0UR24B','BI Perf 6mm x 6.35mm Hole x 4Fx 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(740,'BP0UR28B','BI Perf 6mm x 9.53mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(741,'BP0UR42B','BI Perf 6mm x 12.7mm Hole x 4F x 8F',1,6,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(742,'GP0AR14B','GAL Perf #26 x 3.2mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(743,'GP0AR18B','GAL Perf #26 x 4.8mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(744,'GP0CR14B','GAL Perf #24 x 3.2mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(745,'GP0CR18B','GAL Perf #24 x 4.8mm Hole x 4F x 8 F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(746,'GP0CR24B','GAL Perf #24 x 6.35mm Hole x 4Fx 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(747,'GP0DR14B','GAL Perf #22 x 3.2mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(748,'GP0DR18B','GAL Perf #22 x 4.8mm Hole x 4F x 8 F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(749,'GP0DR24B','GAL Perf #22 x 6.35mm Hole x 4Fx 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(750,'GP0ER14B','GAL Perf 0.8mm x 3.2mm Hole x 4F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(751,'GP0FR14B','GAL Perf #20 x 3.2mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(752,'GP0FR18B','GAL Perf #20 x 4.8mm Hole x 4F x 8 F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(753,'GP0FR24B','GAL Perf #20 x 6.35mm Hole x 4Fx 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(754,'GP0HR08B','GAL Perf #18 x 2.0mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(755,'GP0HR14B','GAL Perf #18 x 3.2mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(756,'GP0HR18B','GAL Perf #18 x 4.8mm Hole x 4F x 8 F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(757,'GP0HR24B','GAL Perf #18 x 6.35mm Hole x 4Fx 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(758,'GP0HR26B','GAL Perf #18 x 7.94mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(759,'GP0HR28B','GAL Perf #18 x 9.53mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(760,'GP0HR42B','GAL Perf #18 x 12.7mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(761,'GP0JR14B','GAL Perf #16 x 3.2mm Hole x 4F x 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(762,'GP0JR18B','GAL Perf #16 x 4.8mm Hole x 4F x 8 F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(763,'GP0JR24B','GAL Perf #16 x 6.35mm Hole x 4Fx 8F',1,7,11,1,'2015-08-13 06:39:34','0000-00-00 00:00:00',1,0),(764,'SF680342','SS-316L Flat Bar 3mm x 3/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(765,'SF681002','SS-316L Flat Bar 3mm x 1 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(766,'SF681142','SS-316L Flat Bar 3mm x 1-1/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(767,'SF681122','SS-316L Flat Bar 3mm x 1-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(768,'SF682002','SS-316L Flat Bar 3mm x 2 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(769,'SF682122','SS-316L Flat Bar 3mm x 2-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(770,'SF683002','SS-316L Flat Bar 3mm x 3 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(771,'SF660342','SS-316L Flat Bar 4.5mm x 3/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(772,'SF661002','SS-316L Flat Bar 4.5mm x 1 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(773,'SF661142','SS-316L Flat Bar 4.5mm x 1-1/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(774,'SF661122','SS-316L Flat Bar 4.5mm x 1-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(775,'SF662002','SS-316L Flat Bar 4.5mm x 2 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(776,'SF662122','SS-316L Flat Bar 4.5mm x 2-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(777,'SF663002','SS-316L Flat Bar 4.5mm x 3 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(778,'SF640342','SS-316L Flat Bar 6mm x 3/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(779,'SF641002','SS-316L Flat Bar 6mm x 1 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(780,'SF641142','SS-316L Flat Bar 6mm x 1-1/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(781,'SF641122','SS-316L Flat Bar 6mm x 1-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(782,'SF642002','SS-316L Flat Bar 6mm x 2 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(783,'SF642122','SS-316L Flat Bar 6mm x 2-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(784,'SF643002','SS-316L Flat Bar 6mm x 3 x 20 Ft.',1,2,4,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(785,'SR600182','Stainless (T-316L) Shafting 1/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(786,'SR600362','Stainless (T-316L) Shafting 3/16 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(787,'SR600142','Stainless (T-316L) Shafting 1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(788,'SR600562','Stainless (T-316L) Shafting 5/16 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(789,'SR600382','Stainless (T-316L) Shafting 3/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(790,'SR600122','Stainless (T-316L) Shafting 1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(791,'SR600582','Stainless (T-316L) Shafting 5/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(792,'SR600342','Stainless (T-316L) Shafting 3/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(793,'SR600782','Stainless (T-316L) Shafting 7/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(794,'SR601002','Stainless (T-316L) Shafting 1 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(795,'SR601182','Stainless (T-316L) Shafting 1-1/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(796,'SR601142','Stainless (T-316L) Shafting 1-1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(797,'SR601122','Stainless (T-316L) Shafting 1-1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(798,'SR601342','Stainless (T-316L) Shafting 1-3/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(799,'SR602002','Stainless (T-316L) Shafting 2 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(800,'SR602142','Stainless (T-316L) Shafting 2-1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(801,'SR602122','Stainless (T-316L) Shafting 2-1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(802,'SR602342','Stainless (T-316L) Shafting 2-3/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(803,'SR603002','Stainless (T-316L) Shafting 3 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(804,'SR603142','Stainless (T-316L) Shafting 3-1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(805,'SR603122','Stainless (T-316L) Shafting 3-1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(806,'SR604002','Stainless (T-316L) Shafting 4 x 20 Ft.',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(807,'SR60412X','Stainless (T-316L) Shafting 4-1/2  Inches',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(808,'SR60500X','Stainless (T-316L) Shafting 5  Inches',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(809,'SR60512X','Stainless (T-316L) Shafting 5-1/2  Inches',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(810,'SR60600X','Stainless (T-316L) Shafting 6  Inches',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(811,'SR60612X','Stainless (T-316L) Shafting 6-1/2  Inches',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(812,'SR60700X','Stainless (T-316L) Shafting 7  Inches',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(813,'SR60712X','Stainless (T-316L) Shafting 7-1/2  Inches',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(814,'SR60800X','Stainless (T-316L) Shafting 8  Inches',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(815,'SR60812X','Stainless (T-316L) Shafting 8-1/2  Inches',1,2,13,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(816,'SS600648','SS-316L Sheet #24 (0.6mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(817,'SS600748','SS-316L Sheet #22 (0.7mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(818,'SS600948','SS-316L Sheet #20 (0.9mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(819,'SS601048','SS-316L Sheet 1.0mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(820,'SS601248','SS-316L Sheet #18 (1.2mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(821,'SS601548','SS-316L Sheet #16 (1.5mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(822,'SS602048','SS-316L Sheet #14 (2.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(823,'SS6B3048','SS-316L Sheet 1/8 (3.0mm) 2B  4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(824,'SS603044','SS-316L Sheet 1/8 (3.0mm) x 4 Ft. x 4 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(825,'SS603048','SS-316L Sheet 1/8 (3.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(826,'SS604548','SS-316L Sheet 3/16 (4.5mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(827,'SS606048','SS-316L Sheet 1/4 (6.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(828,'SS608048','SS-316L Sheet 5/16 (8.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(829,'SS609051','SS-316L Sheet 3/8 (9.0mm) x 5 Ft. x 10 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(830,'SS610048','SS-316L Sheet 10.0mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(831,'SS612048','SS-316L Sheet 1/2 (12.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(832,'SS616048','SS-316L Sheet 5/8 (16.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(833,'SS620048','SS-316L Sheet 3/4 (20.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(834,'SS625048','SS-316L Sheet 1 (25mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(835,'SS630148','SS-316L Sheet 1-1/4 (30.18mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:41:54','0000-00-00 00:00:00',1,0),(836,'SA4J0128','SS (304) Bend Angle Bar 1.5mm x 1/2 x 8 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(837,'SA4J0348','SS (304) Bend Angle Bar 1.5mm x 3/4 x 8 Ft',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(838,'SA4J1008','SS (304) Bend Angle Bar 1.5mm x 1 x 8 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(839,'SA4J1128','SS (304) Bend Angle Bar 1.5mm x 1-1/2 x 8 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(840,'SA4Q0202','SS (304) Angle Bar 3mm x 20mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(841,'SA4Q0252','SS (304) Angle Bar 3mm x 25mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(842,'SA4Q0302','SS (304) Angle Bar 3mm x 30mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(843,'SA4Q0402','SS (304) Angle Bar 3mm x 40mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(844,'SA4Q0502','SS (304) Angle Bar 3mm x 50mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(845,'SA4R0252','SS (304) Angle Bar 4mm x 25mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(846,'SA4R0302','SS (304) Angle Bar 4mm x 30mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(847,'SA4R0402','SS (304) Angle Bar 4mm x 40mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(848,'SA4R0502','SS (304) Angle Bar 4mm x 50mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(849,'SA4T0252','SS (304) Angle Bar 5mm x 25mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(850,'SA4T0302','SS (304) Angle Bar 5mm x 30mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(851,'SA4T0402','SS (304) Angle Bar 5mm x 40mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(852,'SA4T0502','SS (304) Angle Bar 5mm x 50mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(853,'SA4T0652','SS (304) Angle Bar 5mm x 65mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(854,'SA4T0752','SS (304) Angle Bar 5mm x 75mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(855,'SA4U0402','SS (304) Angle Bar 6mm x 40mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(856,'SA4U0502','SS (304) Angle Bar 6mm x 50mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(857,'SA4U0652','SS (304) Angle Bar 6mm x 65mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(858,'SA4U0752','SS (304) Angle Bar 6mm x 75mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(859,'SA450502','SS (304) Angle Bar 8mm x 50mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(860,'SA450652','SS (304) Angle Bar 8mm x 65mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(861,'SA450752','SS (304) Angle Bar 8mm x 75mm x 20 Ft.',1,2,1,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(862,'SF4J0128','SS (304) Flat Bar 1.5mm x 1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(863,'SF4J0588','SS (304) Flat Bar 1.5mm x 5/8 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(864,'SF4J0348','SS (304) Flat Bar 1.5mm x 3/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(865,'SF4J1008','SS (304) Flat Bar 1.5mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(866,'SF4J1148','SS (304) Flat Bar 1.5mm x 1-1/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(867,'SF4J1128','SS (304) Flat Bar 1.5mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(868,'SF4J2008','SS (304) Flat Bar 1.5mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(869,'SF4K0348','SS (304) Flat Bar 2mm x 3/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(870,'SF4K1008','SS (304) Flat Bar 2mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(871,'SF4K1128','SS (304) Flat Bar 2mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(872,'SF4K2008','SS (304) Flat Bar 2mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(873,'SF4Q0128','SS (304) Flat Bar 3.0mm x 1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(874,'SF4Q0588','SS (304) Flat Bar 3.0mm x 5/8 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(875,'SF4Q0348','SS (304) Flat Bar 3.0mm x 3/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(876,'SF4Q1008','SS (304) Flat Bar 3.0mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(877,'SF4Q1148','SS (304) Flat Bar 3.0mm x 1-1/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(878,'SF4Q1128','SS (304) Flat Bar 3.0mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(879,'SF4Q2008','SS (304) Flat Bar 3.0mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(880,'SF4Q2128','SS (304) Flat Bar 3.0mm x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(881,'SF4Q3008','SS (304) Flat Bar 3.0mm x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(882,'SF4Q4008','SS (304) Flat Bar 3.0mm x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(883,'SF4Q5008','SS (304) Flat Bar 3.0mm x 5 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(884,'SF4Q6008','SS (304) Flat Bar 3.0mm x 6 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(885,'SF4Q8008','SS (304) Flat Bar 3.0mm x 8 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(886,'SF4Q0342','SS (304) Flat Bar 3.0mm x 3/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(887,'SF4Q1002','SS (304) Flat Bar 3.0mm x 1 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(888,'SF4Q1142','SS (304) Flat Bar 3.0mm x 1-1/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(889,'SF4Q1122','SS (304) Flat Bar 3.0mm x 1-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(890,'SF4Q2002','SS (304) Flat Bar 3.0mm x 2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(891,'SF4Q2122','SS (304) Flat Bar 3.0mm x 2-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(892,'SF4Q3002','SS (304) Flat Bar 3.0mm x 3 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(893,'SF4Q4002','SS (304) Flat Bar 3.0mm x 4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(894,'SF4Q5002','SS (304) Flat Bar 3.0mm x 5 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(895,'SF4Q6002','SS (304) Flat Bar 3.0mm x 6 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(896,'SF4R0128','SS (304) Flat Bar 4.0mm x 1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(897,'SF4R0348','SS (304) Flat Bar 4.0mm x 3/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(898,'SF4R1008','SS (304) Flat Bar 4.0mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(899,'SF4R1148','SS (304) Flat Bar 4.0mm x 1-1/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(900,'SF4R1128','SS (304) Flat Bar 4.0mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(901,'SF4R2008','SS (304) Flat Bar 4.0mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(902,'SF4R2128','SS (304) Flat Bar 4.0mm x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(903,'SF4R3008','SS (304) Flat Bar 4.0mm x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(904,'SF4R4008','SS (304) Flat Bar 4.0mm x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(905,'SF4R5008','SS (304) Flat Bar 4.0mm x 5 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(906,'SF4R6008','SS (304) Flat Bar 4.0mm x 6 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(907,'SF4R0342','SS (304) Flat Bar 4.0mm x 3/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(908,'SF4R1002','SS (304) Flat Bar 4.0mm x 1 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(909,'SF4R1142','SS (304) Flat Bar 4.0mm x 1-1/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(910,'SF4R1122','SS (304) Flat Bar 4.0mm x 1-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(911,'SF4R2002','SS (304) Flat Bar 4.0mm x 2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(912,'SF4R2122','SS (304) Flat Bar 4.0mm x 2-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(913,'SF4R3002','SS (304) Flat Bar 4.0mm x 3 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(914,'SF4R4002','SS (304) Flat Bar 4.0mm x 4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(915,'SF4R5002','SS (304) Flat Bar 4.0mm x 5 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(916,'SF4R6002','SS (304) Flat Bar 4.0mm x 6 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(917,'SF4S0128','SS (304) Flat Bar 4.5mm x 1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(918,'SF4S0348','SS (304) Flat Bar 4.5mm x 3/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(919,'SF4S1008','SS (304) Flat Bar 4.5mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(920,'SF4S1148','SS (304) Flat Bar 4.5mm x 1-1/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(921,'SF4S1128','SS (304) Flat Bar 4.5mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(922,'SF4S2008','SS (304) Flat Bar 4.5mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(923,'SF4S2128','SS (304) Flat Bar 4.5mm x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(924,'SF4S3008','SS (304) Flat Bar 4.5mm x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(925,'SF4S4008','SS (304) Flat Bar 4.5mm x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(926,'SF4S5008','SS (304) Flat Bar 4.5mm x 5 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(927,'SF4S6008','SS (304) Flat Bar 4.5mm x 6 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(928,'SF4S0342','SS (304) Flat Bar 4.5mm x 3/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(929,'SF4S1002','SS (304) Flat Bar 4.5mm x 1 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(930,'SF4S1142','SS (304) Flat Bar 4.5mm x 1-1/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(931,'SF4S1122','SS (304) Flat Bar 4.5mm x 1-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(932,'SF4S2002','SS (304) Flat Bar 4.5mm x 2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(933,'SF4S2122','SS (304) Flat Bar 4.5mm x 2-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(934,'SF4S3002','SS (304) Flat Bar 4.5mm x 3 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(935,'SF4S4002','SS (304) Flat Bar 4.5mm x 4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(936,'SF4S5002','SS (304) Flat Bar 4.5mm x 5 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(937,'SF4S6002','SS (304) Flat Bar 4.5mm x 6 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(938,'SF4T0128','SS (304) Flat Bar 5.0mm x 1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(939,'SF4T0348','SS (304) Flat Bar 5.0mm x 3/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(940,'SF4T1008','SS (304) Flat Bar 5.0mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(941,'SF4T1148','SS (304) Flat Bar 5.0mm x 1-1/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(942,'SF4T1128','SS (304) Flat Bar 5.0mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(943,'SF4T2008','SS (304) Flat Bar 5.0mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(944,'SF4T2128','SS (304) Flat Bar 5.0mm x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(945,'SF4T3008','SS (304) Flat Bar 5.0mm x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(946,'SF4T4008','SS (304) Flat Bar 5.0mm x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(947,'SF4T5008','SS (304) Flat Bar 5.0mm x 5 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(948,'SF4T6008','SS (304) Flat Bar 5.0mm x 6 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(949,'SF4T0342','SS (304) Flat Bar 5.0mm x 3/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(950,'SF4T1002','SS (304) Flat Bar 5.0mm x 1 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(951,'SF4T1142','SS (304) Flat Bar 5.0mm x 1-1/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(952,'SF4T1122','SS (304) Flat Bar 5.0mm x 1-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(953,'SF4T2002','SS (304) Flat Bar 5.0mm x 2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(954,'SF4T2122','SS (304) Flat Bar 5.0mm x 2-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(955,'SF4T3002','SS (304) Flat Bar 5.0mm x 3 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(956,'SF4T4002','SS (304) Flat Bar 5.0mm x 4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(957,'SF4T5002','SS (304) Flat Bar 5.0mm x 5 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(958,'SF4T6002','SS (304) Flat Bar 5.0mm x 6 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(959,'SF4U0128','SS (304) Flat Bar 6.0mm x 1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(960,'SF4U0348','SS (304) Flat Bar 6.0mm x 3/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(961,'SF4U1008','SS (304) Flat Bar 6.0mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(962,'SF4U1148','SS (304) Flat Bar 6.0mm x 1-1/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(963,'SF4U1128','SS (304) Flat Bar 6.0mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(964,'SF4U2008','SS (304) Flat Bar 6.0mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(965,'SF4U2128','SS (304) Flat Bar 6.0mm x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(966,'SF4U3008','SS (304) Flat Bar 6.0mm x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(967,'SF4U4008','SS (304) Flat Bar 6.0mm x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(968,'SF4U5008','SS (304) Flat Bar 6.0mm x 5 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(969,'SF4U6008','SS (304) Flat Bar 6.0mm x 6 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(970,'SF4U1002','SS (304) Flat Bar 6.0mm x 1 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(971,'SF4U1142','SS (304) Flat Bar 6.0mm x 1-1/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(972,'SF4U1122','SS (304) Flat Bar 6.0mm x 1-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(973,'SF4U2002','SS (304) Flat Bar 6.0mm x 2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(974,'SF4U2122','SS (304) Flat Bar 6.0mm x 2-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(975,'SF4U3002','SS (304) Flat Bar 6.0mm x 3 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(976,'SF4U4002','SS (304) Flat Bar 6.0mm x 4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(977,'SF4U5002','SS (304) Flat Bar 6.0mm x 5 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(978,'SF4U6002','SS (304) Flat Bar 6.0mm x 6 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(979,'SF451008','SS (304) Flat Bar 8.0mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(980,'SF451148','SS (304) Flat Bar 8.0mm x 1-1/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(981,'SF451128','SS (304) Flat Bar 8.0mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(982,'SF452008','SS (304) Flat Bar 8.0mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(983,'SF452128','SS (304) Flat Bar 8.0mm x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(984,'SF453008','SS (304) Flat Bar 8.0mm x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(985,'SF454008','SS (304) Flat Bar 8.0mm x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(986,'SF455008','SS (304) Flat Bar 8.0mm x 5 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(987,'SF456008','SS (304) Flat Bar 8.0mm x 6 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(988,'SF431008','SS (304) Flat Bar 9mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(989,'SF431148','SS (304) Flat Bar 9mm x 1-1/4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(990,'SF431128','SS (304) Flat Bar 9mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(991,'SF432008','SS (304) Flat Bar 9mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(992,'SF432128','SS (304) Flat Bar 9mm x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(993,'SF433008','SS (304) Flat Bar 9mm x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(994,'SF434008','SS (304) Flat Bar 9mm x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(995,'SF435008','SS (304) Flat Bar 9mm x 5 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(996,'SF436008','SS (304) Flat Bar 9mm x 6 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(997,'SF411008','SS (304) Flat Bar 10mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(998,'SF411128','SS (304) Flat Bar 10mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(999,'SF412008','SS (304) Flat Bar 10mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1000,'SF412128','SS (304) Flat Bar 10mm x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1001,'SF413008','SS (304) Flat Bar 10mm x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1002,'SF414008','SS (304) Flat Bar 10mm x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1003,'SF421008','SS (304) Flat Bar 12mm x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1004,'SF421128','SS (304) Flat Bar 12mm x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1005,'SF422008','SS (304) Flat Bar 12mm x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1006,'SF422128','SS (304) Flat Bar 12mm x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1007,'SF423008','SS (304) Flat Bar 12mm x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1008,'SF424008','SS (304) Flat Bar 12mm x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1009,'SF420342','SS (304) Flat Bar 1/2 x 3/4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1010,'SF471008','SS (304) Flat Bar 5/8 x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1011,'SF471128','SS (304) Flat Bar 5/8 x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1012,'SF472008','SS (304) Flat Bar 5/8 x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1013,'SF472128','SS (304) Flat Bar 5/8 x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1014,'SF473008','SS (304) Flat Bar 5/8 x 3 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1015,'SF474008','SS (304) Flat Bar 5/8 x 4 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1016,'SF491008','SS (304) Flat Bar 3/4 x 1 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1017,'SF491128','SS (304) Flat Bar 3/4 x 1-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1018,'SF492008','SS (304) Flat Bar 3/4 x 2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1019,'SF492128','SS (304) Flat Bar 3/4 x 2-1/2 x 8 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1020,'SF49300J','SS (304) Flat Bar 3/4 x 3 x 13 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1021,'SF493002','SS (304) Flat Bar 3/4 x 3 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1022,'SF494002','SS (304) Flat Bar 3/4 x 4 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1023,'SF412122','SS (304) Flat Bar 1 x 2-1/2 x 20 Ft.',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1024,'SF4L100X','SS (304) Flat Bar 30x100mm x',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1025,'SF4L150X','SS (304) Flat Bar 30x150mm x',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1026,'SF4L200X','SS (304) Flat Bar 30x200mm x',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1027,'SF4M150X','SS (304) Flat Bar 40x150mm x',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1028,'SF4M200X','SS (304) Flat Bar 40x200mm x',1,2,4,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1029,'SH400382','SS (304) Hexagon Bar 3/8 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1030,'SH400122','SS (304) Hexagon Bar 1/2 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1031,'SH400582','SS (304) Hexagon Bar 5/8 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1032,'SH400342','SS (304) Hexagon Bar 3/4 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1033,'SH400782','SS (304) Hexagon Bar 7/8 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1034,'SH401002','SS (304) Hexagon Bar 1 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1035,'SH401182','SS (304) Hexagon Bar 1-1/8 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1036,'SH401142','SS (304) Hexagon Bar 1-1/4 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1037,'SH401382','SS (304) Hexagon Bar 1-3/8 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1038,'SH401126','SS (304) Hexagon Bar 1-1/2 x 6 mtr.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1039,'SH401122','SS (304) Hexagon Bar 1-1/2 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1040,'SH401342','SS (304) Hexagon Bar 1-3/4 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1041,'SH402002','SS (304) Hexagon Bar 2 x 20 Ft.',1,2,5,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1042,'SQ400362','SS (304) Square Bar 3/16 x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1043,'SQ400142','SS (304) Square Bar 1/4 x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1044,'SQ405162','SS (304) Square Bar 5/16 x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1045,'SQ400382','SS (304) Square Bar 3/8 x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1046,'SQ400122','SS (304) Square Bar 1/2 x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1047,'SQ400582','SS (304) Square Bar 5/8 x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1048,'SQ400342','SS (304) Square Bar 3/4 x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1049,'SQ400782','SS (304) Square Bar 7/8  x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1050,'SQ401002','SS (304) Square Bar 1 x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1051,'SQ401142','SS (304) Square Bar 1-1/4  x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1052,'SQ401122','SS (304) Square Bar 1-1/2  x 20 Ft.',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1053,'SQ40200K','SS (304) Square Bar 2 x 4 meters',1,2,12,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1054,'SN4014A2','SS (304) Pipe 1/4 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1055,'SN4014B2','SS (304) Pipe 1/4 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1056,'SN4038A2','SS (304) Pipe 3/8 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1057,'SN4038B2','SS (304) Pipe 3/8 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1058,'SN4012A2','SS (304) Pipe 1/2 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1059,'SN4012B2','SS (304) Pipe 1/2 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1060,'SN4012C2','SS (304) Pipe 1/2 (Sch-80) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1061,'SN4034A2','SS (304) Pipe 3/4 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1062,'SN4034B2','SS (304) Pipe 3/4 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1063,'SN4100A2','SS (304) Pipe 1 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1064,'SN4100B2','SS (304) Pipe 1 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1065,'SN4114A2','SS (304) Pipe 1-1/4 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1066,'SN4114B2','SS (304) Pipe 1-1/4 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1067,'SN4114C2','SS (304) Pipe 1-1/4 (Sch-80) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1068,'SN4112A2','SS (304) Pipe 1-1/2 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1069,'SN4112B2','SS (304) Pipe 1-1/2 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1070,'SN4112C2','SS (304) Pipe 1-1/2 (Sch-80) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1071,'SN4200A2','SS (304) Pipe 2 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1072,'SN4200B2','SS (304) Pipe 2 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1073,'SN4212A2','SS (304) Pipe 2-1/2 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1074,'SN4212B2','SS (304) Pipe 2-1/2 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1075,'SN4212C2','SS (304) Pipe 2-1/2 (Sch-80) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1076,'SN4300A2','SS (304) Pipe 3 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1077,'SN4300B2','SS (304) Pipe 3 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1078,'SN4300C2','SS (304) Pipe 3 (Sch-80) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1079,'SN4400A2','SS (304) Pipe 4 (Sch-30) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1080,'SN4400B2','SS (304) Pipe 4 (Sch-40) x 20 Ft.',1,2,10,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1081,'SR200182','SS GREEN (204-Cu) Shafting 1/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1082,'SR200142','SS GREEN (204-Cu) Shafting 1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1083,'SR200382','SS GREEN (204-Cu) Shafting 3/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1084,'SR200582','SS GREEN (204-Cu) Shafting 5/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1085,'SR200342','SS GREEN (204-Cu) Shafting 3/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1086,'SR200782','SS GREEN (204-Cu) Shafting 7/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1087,'SR201382','SS GREEN (204-Cu) Shafting 1-3/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1088,'SR201122','SS GREEN (204-Cu) Shafting 1-1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1089,'SR202002','SS GREEN (204-Cu) Shafting 2 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1090,'SR202142','SS GREEN (204-Cu) Shafting 2-1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1091,'SR202122','SS GREEN (204-Cu) Shafting 2-1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1092,'SS4B0448','SS-304 Sheet #26 (0.4mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1093,'SS4B0648','SS-304 Sheet #24 (0.6mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1094,'SS4B0748','SS-304 Sheet #22 (0.7mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1095,'SS4B0848','SS-304 Sheet 0.8mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1096,'SS4B0948','SS-304 Sheet #20 (0.9mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1097,'SS4B1048','SS-304 Sheet 1.0mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1098,'SS4B1248','SS-304 Sheet #18 (1.2mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1099,'SS4B1548','SS-304 Sheet #16 (1.5mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1100,'SS4B2048','SS-304 Sheet #14 (2.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1101,'SS4B2548','SS-304 Sheet 2.5mm (2B) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1102,'SS4B3048','SS-304 Sheet 1/8 (3.0mm) 2B x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1103,'SS4H0648','SS-304 Sht HairLine PVC 0.6mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1104,'SS4H0748','SS-304 Sht HairLine PVC 0.7mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1105,'SS4H0848','SS-304 Sht HairLine PVC 0.8mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1106,'SS4H0948','SS-304 Sht HairLine PVC 0.9mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1107,'SS4H1048','SS-304 Sht HairLine PVC 1.0mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1108,'SS4H1248','SS-304 Sht HairLine PVC 1.2mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1109,'SS4H1548','SS-304 Sht HairLine PVC 1.5mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1110,'SS4H2048','SS-304 Sht HairLine PVC 2.0mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1111,'SS402548','SS-304 Sheet 2.5mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1112,'SS403048','SS-304 Sheet 1/8 (3.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1113,'SS404048','SS-304 Sheet 4.0mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1114,'SS404548','SS-304 Sheet 3/16 (4.5mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1115,'SS405048','SS-304 Sheet 5.0mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1116,'SS406048','SS-304 Sheet 1/4 (6.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1117,'SS408048','SS-304 Sheet 5/16 (8.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1118,'SS409048','SS-304 Sheet 3/8 (9.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1119,'SS410048','SS-304 Sheet 10.0mm x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1120,'SS412048','SS-304 Sheet 1/2 (12.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1121,'SS416048','SS-304 Sheet 5/8 (16.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1122,'SS420048','SS-304 Sheet 3/4 (20.0mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1123,'SS422048','SS-304 Sheet 7/8 (22mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1124,'SS425048','SS-304 Sheet 1 (25mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1125,'SS432048','SS-304 Sheet 1-1/4 (32mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1126,'SS438048','SS-304 Sheet 1-1/2 (38mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1127,'SS444048','SS-304 Sheet 1-3/4 (44mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1128,'SS450048','SS-304 Sheet 2 (50mm) x 4 Ft. x 8 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1129,'SS403042','SS-304 Sheet 1/8 (3.0mm) x 4 Ft. x 20 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1130,'SS404042','SS-304 Sheet 4.0mm x 4 Ft. x 20 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1131,'SS404542','SS-304 Sheet 3/16 (4.5mm) x 4 Ft. x 20 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1132,'SS405042','SS-304 Sheet 5.0mm x 4 Ft. x 20 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1133,'SS406042','SS-304 Sheet 1/4 (6.0mm) x 4 Ft. x 20 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1134,'SS412042','SS-304 Sheet 1/2 (12.0mm) x 4 Ft. x 20 Ft.',1,2,14,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1135,'SC4B204F','SS-304 Coil 2.0mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1136,'SC4B154F','SS-304 Coil 1.5mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1137,'SC4B124F','SS-304 Coil 1.2mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1138,'SC4B104F','SS-304 Coil 1.0mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1139,'SC4B094F','SS-304 Coil 0.9mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1140,'SC4B084F','SS-304 Coil 0.8mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1141,'SC4B074F','SS-304 Coil 0.7mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1142,'SC4B064F','SS-304 Coil 0.6mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1143,'SC4B054F','SS-304 Coil 0.5mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1144,'SC4B044F','SS-304 Coil 0.4mm x 4 Feet',1,2,2,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1145,'SK4HAAG2','SS-304 SQR Tube 1/2  HL (1.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1146,'SK4HAAJ2','SS-304 SQR Tube 1/2  HL (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1147,'SK4HCCG2','SS-304 SQR Tube 3/4  HL (1.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1148,'SK4BCCG2','SS-304 SQR Tube 3/4  BP (1.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1149,'SK4HCCH2','SS-304 SQR Tube 3/4  HL (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1150,'SK4BCCH2','SS-304 SQR Tube 3/4  BP (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1151,'SK4HCCJ2','SS-304 SQR Tube 3/4  HL (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1152,'SK4BCCJ2','SS-304 SQR Tube 3/4  BP (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1153,'SK4HEEG2','SS-304 SQR Tube 1  HL (1.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1154,'SK4BEEG2','SS-304 SQR Tube 1  BP (1.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1155,'SK4HEEH2','SS-304 SQR Tube 1  HL (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1156,'SK4BEEH2','SS-304 SQR Tube 1  BP (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1157,'SK4HEEJ2','SS-304 SQR Tube 1  HL (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1158,'SK4BEEJ2','SS-304 SQR Tube 1  BP (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1159,'SK4HEEK2','SS-304 SQR Tube 1  HL (2.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1160,'SK4BEEK2','SS-304 SQR Tube 1  BP (2.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1161,'SK4HEEQ2','SS-304 SQR Tube 1  HL (3.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1162,'SK4BFFG2','SS-304 SQR Tube 1-1/4  BP (1.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1163,'SK4HFFH2','SS-304 SQR Tube 1-1/4  HL (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1164,'SK4BFFH2','SS-304 SQR Tube 1-1/4  BP (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1165,'SK4HFFJ2','SS-304 SQR Tube 1-1/4  HL (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1166,'SK4BFFJ2','SS-304 SQR Tube 1-1/4  BP (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1167,'SK4HFFQ2','SS-304 SQR Tube 1-1/4  HL (3.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1168,'SK4BGGG2','SS-304 SQR Tube 1-1/2  BP (1.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1169,'SK4HGGH2','SS-304 SQR Tube 1-1/2  HL (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1170,'SK4BGGH2','SS-304 SQR Tube 1-1/2  BP (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1171,'SK4HGGJ2','SS-304 SQR Tube 1-1/2  HL (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1172,'SK4BGGJ2','SS-304 SQR Tube 1-1/2  BP (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1173,'SK4HGGK2','SS-304 SQR Tube 1-1/2  HL (2.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1174,'SK4HGGQ2','SS-304 SQR Tube 1-1/2  HL (3.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1175,'SK4HJJH2','SS-304 SQR Tube 2  HL (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1176,'SK4BJJH2','SS-304 SQR Tube 2  BP (1.2mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1177,'SK4HJJJ2','SS-304 SQR Tube 2  HL (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1178,'SK4BJJJ2','SS-304 SQR Tube 2  BP (1.5mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1179,'SK4HJJK2','SS-304 SQR Tube 2  HL (2.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1180,'SK4HJJQ2','SS-304 SQR Tube 2  HL (3.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1181,'SK4HLLK2','SS-304 SQR Tube 2-1/2  HL (2.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1182,'SK4BLLK2','SS-304 SQR Tube 2-1/2  BP (2.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1183,'SK4HLLQ2','SS-304 SQR Tube 2-1/2  HL (3.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1184,'SK4HMMQ2','SS-304 SQR Tube 2-3/4 HL(70mm)T-3mmx20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1185,'SK4HNNK2','SS-304 SQR Tube 3  HL  (2.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1186,'SK4HNNQ2','SS-304 SQR Tube 3  HL (3.0mm) x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1187,'SK4HQQK2','SS-304 SQR Tube 4 HL (100mm) T-2mm x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1188,'SK4HQQQ2','SS-304 SQR Tube 4 HL (100mm) T-3mm x 20 Ft.',1,2,7,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1189,'SJ4F0LJ2','SS-304 SEAMLESS TUBE 2-1/2 X 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1190,'SJ4F0NJ2','SS-304 SEAMLESS TUBE 3 X 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1191,'SV402000','SS-304 Welding Rod 2.0mm (5/64)',1,2,16,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1192,'SV402500','SS-304 Welding Rod 2.5mm (3/32)',1,2,16,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1193,'SV403200','SS-304 Welding Rod 3.2mm (1/8)',1,2,16,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1194,'SL4BAEG2','SS-304 Rect Tube 1/2 x 1 BP (1.0mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1195,'SL4HAEH2','SS-304 Rect Tube 1/2 x 1 HL (1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1196,'SL4BAEH3','SS-304 Rect Tube 1/2 x 1 BP (1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1197,'SL4HBFH2','SS-304 Rect Tube 15x30mm HL (1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1198,'SL4BBFH2','SS-304 Rect Tube 15x30mm BP(1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1199,'SL4HCGH2','SS-304 Rect Tube 3/4 x 1-1/2 HL(1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1200,'SL4BCGH2','SS-304 Rect Tube 3/4 x 1-1/2 BP(1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1201,'SL4HCGJ2','SS-304 Rect Tube 3/4 x 1-1/2 HL(1.5mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1202,'SL4BCGJ2','SS-304 Rect Tube 3/4 x 1-1/2 BP(1.5mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1203,'SL4HEGH2','SS-304 Rect Tube 1 x 1-1/2 HL(1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1204,'SL4BEGH2','SS-304 Rect Tube 1 x 1-1/2 BP(1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1205,'SL4HEJH2','SS-304 Rect Tube 1 x 2 HL (1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1206,'SL4BEJH2','SS-304 Rect Tube 1 x 2 BP (1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1207,'SL4HEJJ2','SS-304 Rect Tube 1 x 2 HL (1.5mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1208,'SL4BEJJ2','SS-304 Rect Tube 1 x 2 BP (1.5mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1209,'SL4BENJ2','SS-304 Rect Tube 1 x 3 BP (1.5mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1210,'SL4HFKH2','SS-304 Rect Tube 30X60mm HL(1.2mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1211,'SL4HJNJ2','SS-304 Rect Tube 2 x 3 HL (1.5mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1212,'SL4BJNJ2','SS-304 Rect Tube 2 x 3 BP (1.5mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1213,'SL4HJNK2','SS-304 Rect Tube 2 x 3 HL (2.0mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1214,'SL4BJNK2','SS-304 Rect Tube 2 x 3 BP (2.0mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1215,'SL4HJQJ2','SS-304 Rect Tube 2 x 4 HL (1.5mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1216,'SL4BJQJ2','SS-304 Rect Tube 2 x 4 BP (1.5mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1217,'SL4HJQK2','SS-304 Rect Tube 2 x 4 HL (2.0mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1218,'SL4BJQK2','SS-304 Rect Tube 2 x 4 BP (2.0mm) x 20 Ft.',1,2,8,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1219,'SU430SOF','SS-304 WIRE 3.0mm SOFT',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1220,'SU420SOF','SS-304 WIRE 2.0mm SOFT',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1221,'SU416SOF','SS-304 WIRE 1.6mm SOFT',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1222,'SU412SOF','SS-304 WIRE 1.2mm SOFT',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1223,'SU410SOF','SS-304 WIRE 1.0mm SOFT',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1224,'SU409SOF','SS-304 WIRE 0.9mm SOFT',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1225,'SU409HAR','SS-304 WIRE 0.9mm HARD',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1226,'SU407SOF','SS-304 WIRE 0.7mm HARD',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1227,'SU440SPR','SS-304 SPRING WIRE 4.0mm',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1228,'SU430SPR','SS-304 SPRING WIRE 3.0mm',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1229,'SU425SPR','SS-304 SPRING WIRE 2.5mm',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1230,'SU420SPR','SS-304 SPRING WIRE 2.0mm',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1231,'SU416SPR','SS-304 SPRING WIRE 1.6mm',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1232,'SU412SPR','SS-304 SPRING WIRE 1.2mm',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1233,'SU409SPR','SS-304 SPRING WIRE 0.9mm',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1234,'SU407SPR','SS-304 SPRING WIRE 0.7mm',1,2,15,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1235,'SJ400YD2','SS-304 Round Tube 5/16 (0.7mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1236,'SJ400ZG2','SS-304 Round Tube 3/8 (1.0mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1237,'SJ400AD2','SS-304 Round Tube 1/2 (0.7mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1238,'SJ400AG2','SS-304 Round Tube 1/2 (1.0mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1239,'SJ400AH2','SS-304 Round Tube 1/2 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1240,'SJ400AJ2','SS-304 Round Tube 1/2 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1241,'SJ400BG2','SS-304 Round Tube 5/8 (1.0mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1242,'SJ400BH2','SS-304 Round Tube 5/8 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1243,'SJ400BJ2','SS-304 Round Tube 5/8 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1244,'SJ400CG2','SS-304 Round Tube 3/4 (1.0mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1245,'SJ400CH2','SS-304 Round Tube 3/4 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1246,'SJ400CJ2','SS-304 Round Tube 3/4 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1247,'SJ400DG2','SS-304 Round Tube 7/8 (1.0mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1248,'SJ400DH2','SS-304 Round Tube 7/8 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:43:43','0000-00-00 00:00:00',1,0),(1249,'SJ400DJ2','SS-304 Round Tube 7/8 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1250,'SJ400EG2','SS-304 Round Tube 1 (1.0mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1251,'SJ400EH2','SS-304 Round Tube 1 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1252,'SJ400EJ2','SS-304 Round Tube 1 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1253,'SJ400RH2','SS-304 Round Tube 1-1/8 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1254,'SJ400FH2','SS-304 Round Tube 1-1/4 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1255,'SJ400FJ2','SS-304 Round Tube 1-1/4 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1256,'SJ400SH2','SS-304 Round Tube 1-3/8 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1257,'SJ400GH2','SS-304 Round Tube 1-1/2 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1258,'SJ400GJ2','SS-304 Round Tube 1-1/2 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1259,'SJ400GK2','SS-304 Round Tube 1-1/2 (2.0mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1260,'SJ400TH2','SS-304 Round Tube 1-5/8 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1261,'SJ400UH2','SS-304 Round Tube 1-3/4 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1262,'SJ400UJ2','SS-304 Round Tube 1-3/4 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1263,'SJ400JH2','SS-304 Round Tube 2 (1.2mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1264,'SJ400JJ2','SS-304 Round Tube 2 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1265,'SJ400JK2','SS-304 Round Tube 2 (2.0mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1266,'SJ400LJ2','SS-304 Round Tube 2-1/2 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1267,'SJ400NJ2','SS-304 Round Tube 3 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1268,'SJ400PJ2','SS-304 Round Tube 3-1/2 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1269,'SJ400QJ2','SS-304 Round Tube 4 (1.5mm) x 20 Ft.',1,2,6,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1270,'SR400182','Stainless (T-304) Shafting 1/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1271,'SR400362','Stainless (T-304) Shafting 3/16 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1272,'SR400142','Stainless (T-304) Shafting 1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1273,'SR400562','Stainless (T-304) Shafting 5/16 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1274,'SR400382','Stainless (T-304) Shafting 3/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1275,'SR400122','Stainless (T-304) Shafting 1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1276,'SR400582','Stainless (T-304) Shafting 5/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1277,'SR400342','Stainless (T-304) Shafting 3/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1278,'SR40M202','Stainless (T-304) Shafting 20mm x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1279,'SR400782','Stainless (T-304) Shafting 7/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1280,'SR401002','Stainless (T-304) Shafting 1 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1281,'SR401182','Stainless (T-304) Shafting 1-1/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1282,'SR401142','Stainless (T-304) Shafting 1-1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1283,'SR401382','Stainless (T-304) Shafting 1-3/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1284,'SR401122','Stainless (T-304) Shafting 1-1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1285,'SR401582','Stainless (T-304) Shafting 1-5/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1286,'SR401342','Stainless (T-304) Shafting 1-3/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1287,'SR402002','Stainless (T-304) Shafting 2 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1288,'SR402142','Stainless (T-304) Shafting 2-1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1289,'SR402382','Stainless (T-304) Shafting 2-3/8 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1290,'SR402122','Stainless (T-304) Shafting 2-1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1291,'SR402342','Stainless (T-304) Shafting 2-3/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1292,'SR403002','Stainless (T-304) Shafting 3 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1293,'SR403142','Stainless (T-304) Shafting 3-1/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1294,'SR403122','Stainless (T-304) Shafting 3-1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1295,'SR403342','Stainless (T-304) Shafting 3-3/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1296,'SR404002','Stainless (T-304) Shafting 4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1297,'SR404122','Stainless (T-304) Shafting 4-1/2 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1298,'SR404342','Stainless (T-304) Shafting 4-3/4 x 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1299,'SR405002','Stainless (T-304) Shafting 5 X 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1300,'SR405122','Stainless (T-304) Shafting 5-1/2 X 20 Ft.',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1301,'SR40600X','Stainless (T-304) Shafting 6 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1302,'SR40612X','Stainless (T-304) Shafting 6-1/2 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1303,'SR40700X','Stainless (T-304) Shafting 7 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1304,'SR40712X','Stainless (T-304) Shafting 7-1/2 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1305,'SR40800X','Stainless (T-304) Shafting 8 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1306,'SR40812X','Stainless (T-304) Shafting 8-1/2 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1307,'SR40900X','Stainless (T-304) Shafting 9 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1308,'SR40912X','Stainless (T-304) Shafting 9-1/2 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1309,'SR41000X','Stainless (T-304) Shafting 10 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1310,'SR41100X','Stainless (T-304) Shafting 11 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1311,'SR41200X','Stainless (T-304) Shafting 12 Inches',1,2,13,1,'2015-08-13 06:47:03','0000-00-00 00:00:00',1,0),(1312,'SS304CUT','SS-304  CUTTINGS',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1313,'SS316CUT','SS-316  CUTTINGS',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1314,'ALUMNCUT','ALUMINUM  CUTTING',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1315,'MATLABOR','LABOR ONLY, MAT.  FROM CUSTOMER',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1316,'COPPRCUT','COPPER  CUTTINGS',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1317,'BRASSCUT','BRASS  CUTTING',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1318,'TIGERCUT','TIGER BRONZE  CUTTINGS',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1319,'WATERCUT','WATERJET CUTTINGS',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1320,'BIPERFOR','BI  JOB ORDER PERF SHEET',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1321,'GALVPERF','GAL JOB ORDER PERF SHEET',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1322,'ALUMPERF','ALUMINUM JOB ORDER PERF SHEET',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1323,'S304PERF','SS-304  JOB ORDER PERF SHEET',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1324,'S316PERF','SS-316  JOB ORDER PERF SHEET',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1325,'BIEXPMET','BI  JOB ORDER EXP METAL',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1326,'GALVAEXP','GAL  JOB ORDER EXP METAL',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1327,'ALUMNEXP','ALUMINUM  JOB ORDER EXP METAL',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1328,'SS304EXP','SS-304  JOB ORDER EXP METAL',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1329,'SS316EXP','SS-316  JOB ORDER EXP METAL',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1330,'SSACCFIT','STAINLESS  ACC.  &  FITTINGS',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1331,'TIGBRACC','BRONZE  /  BRASS  ACC.  &   FITTINGS',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1332,'COPPRACC','COPPER  ACC.  &  FITTINGS',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1333,'MISCEL01','MISCELLANEOUS - 1',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1334,'MISCEL02','MISCELLANEOUS - 2',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1335,'MISCEL03','MISCELLANEOUS - 3',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1336,'MISCEL04','MISCELLANEOUS - 4',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0),(1337,'MISCEL05','MISCELLANEOUS - 5',0,0,0,1,'2015-08-13 06:59:42','0000-00-00 00:00:00',1,0);
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
  KEY `idx_productid_branchid` (`product_id`,`branch_id`),
  KEY `idx_productud_branchid_maxinv_inv` (`product_id`,`branch_id`,`max_inv`,`inventory`),
  KEY `idx_productud_branchid_mininv_inv` (`product_id`,`branch_id`,`min_inv`,`inventory`)
) ENGINE=InnoDB AUTO_INCREMENT=4012 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_branch_inventory`
--

LOCK TABLES `product_branch_inventory` WRITE;
/*!40000 ALTER TABLE `product_branch_inventory` DISABLE KEYS */;
INSERT INTO `product_branch_inventory` VALUES (1,1,1,0,0,0),(2,2,1,0,0,0),(3,3,1,0,0,0),(4,1,2,0,0,0),(5,2,2,0,0,0),(6,3,2,0,0,0),(7,1,3,0,0,0),(8,2,3,0,0,0),(9,3,3,0,0,0),(10,1,4,0,0,0),(11,2,4,0,0,0),(12,3,4,0,0,0),(13,1,5,0,0,0),(14,2,5,0,0,0),(15,3,5,0,0,0),(16,1,6,0,0,0),(17,2,6,0,0,0),(18,3,6,0,0,0),(19,1,7,0,0,0),(20,2,7,0,0,0),(21,3,7,0,0,0),(22,1,8,0,0,0),(23,2,8,0,0,0),(24,3,8,0,0,0),(25,1,9,0,0,0),(26,2,9,0,0,0),(27,3,9,0,0,0),(28,1,10,0,0,0),(29,2,10,0,0,0),(30,3,10,0,0,0),(31,1,11,0,0,0),(32,2,11,0,0,0),(33,3,11,0,0,0),(34,1,12,0,0,0),(35,2,12,0,0,0),(36,3,12,0,0,0),(37,1,13,0,0,0),(38,2,13,0,0,0),(39,3,13,0,0,0),(40,1,14,0,0,0),(41,2,14,0,0,0),(42,3,14,0,0,0),(43,1,15,0,0,0),(44,2,15,0,0,0),(45,3,15,0,0,0),(46,1,16,0,0,0),(47,2,16,0,0,0),(48,3,16,0,0,0),(49,1,17,0,0,0),(50,2,17,0,0,0),(51,3,17,0,0,0),(52,1,18,0,0,0),(53,2,18,0,0,0),(54,3,18,0,0,0),(55,1,19,0,0,0),(56,2,19,0,0,0),(57,3,19,0,0,0),(58,1,20,0,0,0),(59,2,20,0,0,0),(60,3,20,0,0,0),(61,1,21,0,0,0),(62,2,21,0,0,0),(63,3,21,0,0,0),(64,1,22,0,0,0),(65,2,22,0,0,0),(66,3,22,0,0,0),(67,1,23,0,0,0),(68,2,23,0,0,0),(69,3,23,0,0,0),(70,1,24,0,0,0),(71,2,24,0,0,0),(72,3,24,0,0,0),(73,1,25,0,0,0),(74,2,25,0,0,0),(75,3,25,0,0,0),(76,1,26,0,0,0),(77,2,26,0,0,0),(78,3,26,0,0,0),(79,1,27,0,0,0),(80,2,27,0,0,0),(81,3,27,0,0,0),(82,1,28,0,0,0),(83,2,28,0,0,0),(84,3,28,0,0,0),(85,1,29,0,0,0),(86,2,29,0,0,0),(87,3,29,0,0,0),(88,1,30,0,0,0),(89,2,30,0,0,0),(90,3,30,0,0,0),(91,1,31,0,0,0),(92,2,31,0,0,0),(93,3,31,0,0,0),(94,1,32,0,0,0),(95,2,32,0,0,0),(96,3,32,0,0,0),(97,1,33,0,0,0),(98,2,33,0,0,0),(99,3,33,0,0,0),(100,1,34,0,0,0),(101,2,34,0,0,0),(102,3,34,0,0,0),(103,1,35,0,0,0),(104,2,35,0,0,0),(105,3,35,0,0,0),(106,1,36,0,0,0),(107,2,36,0,0,0),(108,3,36,0,0,0),(109,1,37,0,0,0),(110,2,37,0,0,0),(111,3,37,0,0,0),(112,1,38,0,0,0),(113,2,38,0,0,0),(114,3,38,0,0,0),(115,1,39,0,0,0),(116,2,39,0,0,0),(117,3,39,0,0,0),(118,1,40,0,0,0),(119,2,40,0,0,0),(120,3,40,0,0,0),(121,1,41,0,0,0),(122,2,41,0,0,0),(123,3,41,0,0,0),(124,1,42,0,0,0),(125,2,42,0,0,0),(126,3,42,0,0,0),(127,1,43,0,0,0),(128,2,43,0,0,0),(129,3,43,0,0,0),(130,1,44,0,0,0),(131,2,44,0,0,0),(132,3,44,0,0,0),(133,1,45,0,0,0),(134,2,45,0,0,0),(135,3,45,0,0,0),(136,1,46,0,0,0),(137,2,46,0,0,0),(138,3,46,0,0,0),(139,1,47,0,0,0),(140,2,47,0,0,0),(141,3,47,0,0,0),(142,1,48,0,0,0),(143,2,48,0,0,0),(144,3,48,0,0,0),(145,1,49,0,0,0),(146,2,49,0,0,0),(147,3,49,0,0,0),(148,1,50,0,0,0),(149,2,50,0,0,0),(150,3,50,0,0,0),(151,1,51,0,0,0),(152,2,51,0,0,0),(153,3,51,0,0,0),(154,1,52,0,0,0),(155,2,52,0,0,0),(156,3,52,0,0,0),(157,1,53,0,0,0),(158,2,53,0,0,0),(159,3,53,0,0,0),(160,1,54,0,0,0),(161,2,54,0,0,0),(162,3,54,0,0,0),(163,1,55,0,0,0),(164,2,55,0,0,0),(165,3,55,0,0,0),(166,1,56,0,0,0),(167,2,56,0,0,0),(168,3,56,0,0,0),(169,1,57,0,0,0),(170,2,57,0,0,0),(171,3,57,0,0,0),(172,1,58,0,0,0),(173,2,58,0,0,0),(174,3,58,0,0,0),(175,1,59,0,0,0),(176,2,59,0,0,0),(177,3,59,0,0,0),(178,1,60,0,0,0),(179,2,60,0,0,0),(180,3,60,0,0,0),(181,1,61,0,0,0),(182,2,61,0,0,0),(183,3,61,0,0,0),(184,1,62,0,0,0),(185,2,62,0,0,0),(186,3,62,0,0,0),(187,1,63,0,0,0),(188,2,63,0,0,0),(189,3,63,0,0,0),(190,1,64,0,0,0),(191,2,64,0,0,0),(192,3,64,0,0,0),(193,1,65,0,0,0),(194,2,65,0,0,0),(195,3,65,0,0,0),(196,1,66,0,0,0),(197,2,66,0,0,0),(198,3,66,0,0,0),(199,1,67,0,0,0),(200,2,67,0,0,0),(201,3,67,0,0,0),(202,1,68,0,0,0),(203,2,68,0,0,0),(204,3,68,0,0,0),(205,1,69,0,0,0),(206,2,69,0,0,0),(207,3,69,0,0,0),(208,1,70,0,0,0),(209,2,70,0,0,0),(210,3,70,0,0,0),(211,1,71,0,0,0),(212,2,71,0,0,0),(213,3,71,0,0,0),(214,1,72,0,0,0),(215,2,72,0,0,0),(216,3,72,0,0,0),(217,1,73,0,0,0),(218,2,73,0,0,0),(219,3,73,0,0,0),(220,1,74,0,0,0),(221,2,74,0,0,0),(222,3,74,0,0,0),(223,1,75,0,0,0),(224,2,75,0,0,0),(225,3,75,0,0,0),(226,1,76,0,0,0),(227,2,76,0,0,0),(228,3,76,0,0,0),(229,1,77,0,0,0),(230,2,77,0,0,0),(231,3,77,0,0,0),(232,1,78,0,0,0),(233,2,78,0,0,0),(234,3,78,0,0,0),(235,1,79,0,0,0),(236,2,79,0,0,0),(237,3,79,0,0,0),(238,1,80,0,0,0),(239,2,80,0,0,0),(240,3,80,0,0,0),(241,1,81,0,0,0),(242,2,81,0,0,0),(243,3,81,0,0,0),(244,1,82,0,0,0),(245,2,82,0,0,0),(246,3,82,0,0,0),(247,1,83,0,0,0),(248,2,83,0,0,0),(249,3,83,0,0,0),(250,1,84,0,0,0),(251,2,84,0,0,0),(252,3,84,0,0,0),(253,1,85,0,0,0),(254,2,85,0,0,0),(255,3,85,0,0,0),(256,1,86,0,0,0),(257,2,86,0,0,0),(258,3,86,0,0,0),(259,1,87,0,0,0),(260,2,87,0,0,0),(261,3,87,0,0,0),(262,1,88,0,0,0),(263,2,88,0,0,0),(264,3,88,0,0,0),(265,1,89,0,0,0),(266,2,89,0,0,0),(267,3,89,0,0,0),(268,1,90,0,0,0),(269,2,90,0,0,0),(270,3,90,0,0,0),(271,1,91,0,0,0),(272,2,91,0,0,0),(273,3,91,0,0,0),(274,1,92,0,0,0),(275,2,92,0,0,0),(276,3,92,0,0,0),(277,1,93,0,0,0),(278,2,93,0,0,0),(279,3,93,0,0,0),(280,1,94,0,0,0),(281,2,94,0,0,0),(282,3,94,0,0,0),(283,1,95,0,0,0),(284,2,95,0,0,0),(285,3,95,0,0,0),(286,1,96,0,0,0),(287,2,96,0,0,0),(288,3,96,0,0,0),(289,1,97,0,0,0),(290,2,97,0,0,0),(291,3,97,0,0,0),(292,1,98,0,0,0),(293,2,98,0,0,0),(294,3,98,0,0,0),(295,1,99,0,0,0),(296,2,99,0,0,0),(297,3,99,0,0,0),(298,1,100,0,0,0),(299,2,100,0,0,0),(300,3,100,0,0,0),(301,1,101,0,0,0),(302,2,101,0,0,0),(303,3,101,0,0,0),(304,1,102,0,0,0),(305,2,102,0,0,0),(306,3,102,0,0,0),(307,1,103,0,0,0),(308,2,103,0,0,0),(309,3,103,0,0,0),(310,1,104,0,0,0),(311,2,104,0,0,0),(312,3,104,0,0,0),(313,1,105,0,0,0),(314,2,105,0,0,0),(315,3,105,0,0,0),(316,1,106,0,0,0),(317,2,106,0,0,0),(318,3,106,0,0,0),(319,1,107,0,0,0),(320,2,107,0,0,0),(321,3,107,0,0,0),(322,1,108,0,0,0),(323,2,108,0,0,0),(324,3,108,0,0,0),(325,1,109,0,0,0),(326,2,109,0,0,0),(327,3,109,0,0,0),(328,1,110,0,0,0),(329,2,110,0,0,0),(330,3,110,0,0,0),(331,1,111,0,0,0),(332,2,111,0,0,0),(333,3,111,0,0,0),(334,1,112,0,0,0),(335,2,112,0,0,0),(336,3,112,0,0,0),(337,1,113,0,0,0),(338,2,113,0,0,0),(339,3,113,0,0,0),(340,1,114,0,0,0),(341,2,114,0,0,0),(342,3,114,0,0,0),(343,1,115,0,0,0),(344,2,115,0,0,0),(345,3,115,0,0,0),(346,1,116,0,0,0),(347,2,116,0,0,0),(348,3,116,0,0,0),(349,1,117,0,0,0),(350,2,117,0,0,0),(351,3,117,0,0,0),(352,1,118,0,0,0),(353,2,118,0,0,0),(354,3,118,0,0,0),(355,1,119,0,0,0),(356,2,119,0,0,0),(357,3,119,0,0,0),(358,1,120,0,0,0),(359,2,120,0,0,0),(360,3,120,0,0,0),(361,1,121,0,0,0),(362,2,121,0,0,0),(363,3,121,0,0,0),(364,1,122,0,0,0),(365,2,122,0,0,0),(366,3,122,0,0,0),(367,1,123,0,0,0),(368,2,123,0,0,0),(369,3,123,0,0,0),(370,1,124,0,0,0),(371,2,124,0,0,0),(372,3,124,0,0,0),(373,1,125,0,0,0),(374,2,125,0,0,0),(375,3,125,0,0,0),(376,1,126,0,0,0),(377,2,126,0,0,0),(378,3,126,0,0,0),(379,1,127,0,0,0),(380,2,127,0,0,0),(381,3,127,0,0,0),(382,1,128,0,0,0),(383,2,128,0,0,0),(384,3,128,0,0,0),(385,1,129,0,0,0),(386,2,129,0,0,0),(387,3,129,0,0,0),(388,1,130,0,0,0),(389,2,130,0,0,0),(390,3,130,0,0,0),(391,1,131,0,0,0),(392,2,131,0,0,0),(393,3,131,0,0,0),(394,1,132,0,0,0),(395,2,132,0,0,0),(396,3,132,0,0,0),(397,1,133,0,0,0),(398,2,133,0,0,0),(399,3,133,0,0,0),(400,1,134,0,0,0),(401,2,134,0,0,0),(402,3,134,0,0,0),(403,1,135,0,0,0),(404,2,135,0,0,0),(405,3,135,0,0,0),(406,1,136,0,0,0),(407,2,136,0,0,0),(408,3,136,0,0,0),(409,1,137,0,0,0),(410,2,137,0,0,0),(411,3,137,0,0,0),(412,1,138,0,0,0),(413,2,138,0,0,0),(414,3,138,0,0,0),(415,1,139,0,0,0),(416,2,139,0,0,0),(417,3,139,0,0,0),(418,1,140,0,0,0),(419,2,140,0,0,0),(420,3,140,0,0,0),(421,1,141,0,0,0),(422,2,141,0,0,0),(423,3,141,0,0,0),(424,1,142,0,0,0),(425,2,142,0,0,0),(426,3,142,0,0,0),(427,1,143,0,0,0),(428,2,143,0,0,0),(429,3,143,0,0,0),(430,1,144,0,0,0),(431,2,144,0,0,0),(432,3,144,0,0,0),(433,1,145,0,0,0),(434,2,145,0,0,0),(435,3,145,0,0,0),(436,1,146,0,0,0),(437,2,146,0,0,0),(438,3,146,0,0,0),(439,1,147,0,0,0),(440,2,147,0,0,0),(441,3,147,0,0,0),(442,1,148,0,0,0),(443,2,148,0,0,0),(444,3,148,0,0,0),(445,1,149,0,0,0),(446,2,149,0,0,0),(447,3,149,0,0,0),(448,1,150,0,0,0),(449,2,150,0,0,0),(450,3,150,0,0,0),(451,1,151,0,0,0),(452,2,151,0,0,0),(453,3,151,0,0,0),(454,1,152,0,0,0),(455,2,152,0,0,0),(456,3,152,0,0,0),(457,1,153,0,0,0),(458,2,153,0,0,0),(459,3,153,0,0,0),(460,1,154,0,0,0),(461,2,154,0,0,0),(462,3,154,0,0,0),(463,1,155,0,0,0),(464,2,155,0,0,0),(465,3,155,0,0,0),(466,1,156,0,0,0),(467,2,156,0,0,0),(468,3,156,0,0,0),(469,1,157,0,0,0),(470,2,157,0,0,0),(471,3,157,0,0,0),(472,1,158,0,0,0),(473,2,158,0,0,0),(474,3,158,0,0,0),(475,1,159,0,0,0),(476,2,159,0,0,0),(477,3,159,0,0,0),(478,1,160,0,0,0),(479,2,160,0,0,0),(480,3,160,0,0,0),(481,1,161,0,0,0),(482,2,161,0,0,0),(483,3,161,0,0,0),(484,1,162,0,0,0),(485,2,162,0,0,0),(486,3,162,0,0,0),(487,1,163,0,0,0),(488,2,163,0,0,0),(489,3,163,0,0,0),(490,1,164,0,0,0),(491,2,164,0,0,0),(492,3,164,0,0,0),(493,1,165,0,0,0),(494,2,165,0,0,0),(495,3,165,0,0,0),(496,1,166,0,0,0),(497,2,166,0,0,0),(498,3,166,0,0,0),(499,1,167,0,0,0),(500,2,167,0,0,0),(501,3,167,0,0,0),(502,1,168,0,0,0),(503,2,168,0,0,0),(504,3,168,0,0,0),(505,1,169,0,0,0),(506,2,169,0,0,0),(507,3,169,0,0,0),(508,1,170,0,0,0),(509,2,170,0,0,0),(510,3,170,0,0,0),(511,1,171,0,0,0),(512,2,171,0,0,0),(513,3,171,0,0,0),(514,1,172,0,0,0),(515,2,172,0,0,0),(516,3,172,0,0,0),(517,1,173,0,0,0),(518,2,173,0,0,0),(519,3,173,0,0,0),(520,1,174,0,0,0),(521,2,174,0,0,0),(522,3,174,0,0,0),(523,1,175,0,0,0),(524,2,175,0,0,0),(525,3,175,0,0,0),(526,1,176,0,0,0),(527,2,176,0,0,0),(528,3,176,0,0,0),(529,1,177,0,0,0),(530,2,177,0,0,0),(531,3,177,0,0,0),(532,1,178,0,0,0),(533,2,178,0,0,0),(534,3,178,0,0,0),(535,1,179,0,0,0),(536,2,179,0,0,0),(537,3,179,0,0,0),(538,1,180,0,0,0),(539,2,180,0,0,0),(540,3,180,0,0,0),(541,1,181,0,0,0),(542,2,181,0,0,0),(543,3,181,0,0,0),(544,1,182,0,0,0),(545,2,182,0,0,0),(546,3,182,0,0,0),(547,1,183,0,0,0),(548,2,183,0,0,0),(549,3,183,0,0,0),(550,1,184,0,0,0),(551,2,184,0,0,0),(552,3,184,0,0,0),(553,1,185,0,0,0),(554,2,185,0,0,0),(555,3,185,0,0,0),(556,1,186,0,0,0),(557,2,186,0,0,0),(558,3,186,0,0,0),(559,1,187,0,0,0),(560,2,187,0,0,0),(561,3,187,0,0,0),(562,1,188,0,0,0),(563,2,188,0,0,0),(564,3,188,0,0,0),(565,1,189,0,0,0),(566,2,189,0,0,0),(567,3,189,0,0,0),(568,1,190,0,0,0),(569,2,190,0,0,0),(570,3,190,0,0,0),(571,1,191,0,0,0),(572,2,191,0,0,0),(573,3,191,0,0,0),(574,1,192,0,0,0),(575,2,192,0,0,0),(576,3,192,0,0,0),(577,1,193,0,0,0),(578,2,193,0,0,0),(579,3,193,0,0,0),(580,1,194,0,0,0),(581,2,194,0,0,0),(582,3,194,0,0,0),(583,1,195,0,0,0),(584,2,195,0,0,0),(585,3,195,0,0,0),(586,1,196,0,0,0),(587,2,196,0,0,0),(588,3,196,0,0,0),(589,1,197,0,0,0),(590,2,197,0,0,0),(591,3,197,0,0,0),(592,1,198,0,0,0),(593,2,198,0,0,0),(594,3,198,0,0,0),(595,1,199,0,0,0),(596,2,199,0,0,0),(597,3,199,0,0,0),(598,1,200,0,0,0),(599,2,200,0,0,0),(600,3,200,0,0,0),(601,1,201,0,0,0),(602,2,201,0,0,0),(603,3,201,0,0,0),(604,1,202,0,0,0),(605,2,202,0,0,0),(606,3,202,0,0,0),(607,1,203,0,0,0),(608,2,203,0,0,0),(609,3,203,0,0,0),(610,1,204,0,0,0),(611,2,204,0,0,0),(612,3,204,0,0,0),(613,1,205,0,0,0),(614,2,205,0,0,0),(615,3,205,0,0,0),(616,1,206,0,0,0),(617,2,206,0,0,0),(618,3,206,0,0,0),(619,1,207,0,0,0),(620,2,207,0,0,0),(621,3,207,0,0,0),(622,1,208,0,0,0),(623,2,208,0,0,0),(624,3,208,0,0,0),(625,1,209,0,0,0),(626,2,209,0,0,0),(627,3,209,0,0,0),(628,1,210,0,0,0),(629,2,210,0,0,0),(630,3,210,0,0,0),(631,1,211,0,0,0),(632,2,211,0,0,0),(633,3,211,0,0,0),(634,1,212,0,0,0),(635,2,212,0,0,0),(636,3,212,0,0,0),(637,1,213,0,0,0),(638,2,213,0,0,0),(639,3,213,0,0,0),(640,1,214,0,0,0),(641,2,214,0,0,0),(642,3,214,0,0,0),(643,1,215,0,0,0),(644,2,215,0,0,0),(645,3,215,0,0,0),(646,1,216,0,0,0),(647,2,216,0,0,0),(648,3,216,0,0,0),(649,1,217,0,0,0),(650,2,217,0,0,0),(651,3,217,0,0,0),(652,1,218,0,0,0),(653,2,218,0,0,0),(654,3,218,0,0,0),(655,1,219,0,0,0),(656,2,219,0,0,0),(657,3,219,0,0,0),(658,1,220,0,0,0),(659,2,220,0,0,0),(660,3,220,0,0,0),(661,1,221,0,0,0),(662,2,221,0,0,0),(663,3,221,0,0,0),(664,1,222,0,0,0),(665,2,222,0,0,0),(666,3,222,0,0,0),(667,1,223,0,0,0),(668,2,223,0,0,0),(669,3,223,0,0,0),(670,1,224,0,0,0),(671,2,224,0,0,0),(672,3,224,0,0,0),(673,1,225,0,0,0),(674,2,225,0,0,0),(675,3,225,0,0,0),(676,1,226,0,0,0),(677,2,226,0,0,0),(678,3,226,0,0,0),(679,1,227,0,0,0),(680,2,227,0,0,0),(681,3,227,0,0,0),(682,1,228,0,0,0),(683,2,228,0,0,0),(684,3,228,0,0,0),(685,1,229,0,0,0),(686,2,229,0,0,0),(687,3,229,0,0,0),(688,1,230,0,0,0),(689,2,230,0,0,0),(690,3,230,0,0,0),(691,1,231,0,0,0),(692,2,231,0,0,0),(693,3,231,0,0,0),(694,1,232,0,0,0),(695,2,232,0,0,0),(696,3,232,0,0,0),(697,1,233,0,0,0),(698,2,233,0,0,0),(699,3,233,0,0,0),(700,1,234,0,0,0),(701,2,234,0,0,0),(702,3,234,0,0,0),(703,1,235,0,0,0),(704,2,235,0,0,0),(705,3,235,0,0,0),(706,1,236,0,0,0),(707,2,236,0,0,0),(708,3,236,0,0,0),(709,1,237,0,0,0),(710,2,237,0,0,0),(711,3,237,0,0,0),(712,1,238,0,0,0),(713,2,238,0,0,0),(714,3,238,0,0,0),(715,1,239,0,0,0),(716,2,239,0,0,0),(717,3,239,0,0,0),(718,1,240,0,0,0),(719,2,240,0,0,0),(720,3,240,0,0,0),(721,1,241,0,0,0),(722,2,241,0,0,0),(723,3,241,0,0,0),(724,1,242,0,0,0),(725,2,242,0,0,0),(726,3,242,0,0,0),(727,1,243,0,0,0),(728,2,243,0,0,0),(729,3,243,0,0,0),(730,1,244,0,0,0),(731,2,244,0,0,0),(732,3,244,0,0,0),(733,1,245,0,0,0),(734,2,245,0,0,0),(735,3,245,0,0,0),(736,1,246,0,0,0),(737,2,246,0,0,0),(738,3,246,0,0,0),(739,1,247,0,0,0),(740,2,247,0,0,0),(741,3,247,0,0,0),(742,1,248,0,0,0),(743,2,248,0,0,0),(744,3,248,0,0,0),(745,1,249,0,0,0),(746,2,249,0,0,0),(747,3,249,0,0,0),(748,1,250,0,0,0),(749,2,250,0,0,0),(750,3,250,0,0,0),(751,1,251,0,0,0),(752,2,251,0,0,0),(753,3,251,0,0,0),(754,1,252,0,0,0),(755,2,252,0,0,0),(756,3,252,0,0,0),(757,1,253,0,0,0),(758,2,253,0,0,0),(759,3,253,0,0,0),(760,1,254,0,0,0),(761,2,254,0,0,0),(762,3,254,0,0,0),(763,1,255,0,0,0),(764,2,255,0,0,0),(765,3,255,0,0,0),(766,1,256,0,0,0),(767,2,256,0,0,0),(768,3,256,0,0,0),(769,1,257,0,0,0),(770,2,257,0,0,0),(771,3,257,0,0,0),(772,1,258,0,0,0),(773,2,258,0,0,0),(774,3,258,0,0,0),(775,1,259,0,0,0),(776,2,259,0,0,0),(777,3,259,0,0,0),(778,1,260,0,0,0),(779,2,260,0,0,0),(780,3,260,0,0,0),(781,1,261,0,0,0),(782,2,261,0,0,0),(783,3,261,0,0,0),(784,1,262,0,0,0),(785,2,262,0,0,0),(786,3,262,0,0,0),(787,1,263,0,0,0),(788,2,263,0,0,0),(789,3,263,0,0,0),(790,1,264,0,0,0),(791,2,264,0,0,0),(792,3,264,0,0,0),(793,1,265,0,0,0),(794,2,265,0,0,0),(795,3,265,0,0,0),(796,1,266,0,0,0),(797,2,266,0,0,0),(798,3,266,0,0,0),(799,1,267,0,0,0),(800,2,267,0,0,0),(801,3,267,0,0,0),(802,1,268,0,0,0),(803,2,268,0,0,0),(804,3,268,0,0,0),(805,1,269,0,0,0),(806,2,269,0,0,0),(807,3,269,0,0,0),(808,1,270,0,0,0),(809,2,270,0,0,0),(810,3,270,0,0,0),(811,1,271,0,0,0),(812,2,271,0,0,0),(813,3,271,0,0,0),(814,1,272,0,0,0),(815,2,272,0,0,0),(816,3,272,0,0,0),(817,1,273,0,0,0),(818,2,273,0,0,0),(819,3,273,0,0,0),(820,1,274,0,0,0),(821,2,274,0,0,0),(822,3,274,0,0,0),(823,1,275,0,0,0),(824,2,275,0,0,0),(825,3,275,0,0,0),(826,1,276,0,0,0),(827,2,276,0,0,0),(828,3,276,0,0,0),(829,1,277,0,0,0),(830,2,277,0,0,0),(831,3,277,0,0,0),(832,1,278,0,0,0),(833,2,278,0,0,0),(834,3,278,0,0,0),(835,1,279,0,0,0),(836,2,279,0,0,0),(837,3,279,0,0,0),(838,1,280,0,0,0),(839,2,280,0,0,0),(840,3,280,0,0,0),(841,1,281,0,0,0),(842,2,281,0,0,0),(843,3,281,0,0,0),(844,1,282,0,0,0),(845,2,282,0,0,0),(846,3,282,0,0,0),(847,1,283,0,0,0),(848,2,283,0,0,0),(849,3,283,0,0,0),(850,1,284,0,0,0),(851,2,284,0,0,0),(852,3,284,0,0,0),(853,1,285,0,0,0),(854,2,285,0,0,0),(855,3,285,0,0,0),(856,1,286,0,0,0),(857,2,286,0,0,0),(858,3,286,0,0,0),(859,1,287,0,0,0),(860,2,287,0,0,0),(861,3,287,0,0,0),(862,1,288,0,0,0),(863,2,288,0,0,0),(864,3,288,0,0,0),(865,1,289,0,0,0),(866,2,289,0,0,0),(867,3,289,0,0,0),(868,1,290,0,0,0),(869,2,290,0,0,0),(870,3,290,0,0,0),(871,1,291,0,0,0),(872,2,291,0,0,0),(873,3,291,0,0,0),(874,1,292,0,0,0),(875,2,292,0,0,0),(876,3,292,0,0,0),(877,1,293,0,0,0),(878,2,293,0,0,0),(879,3,293,0,0,0),(880,1,294,0,0,0),(881,2,294,0,0,0),(882,3,294,0,0,0),(883,1,295,0,0,0),(884,2,295,0,0,0),(885,3,295,0,0,0),(886,1,296,0,0,0),(887,2,296,0,0,0),(888,3,296,0,0,0),(889,1,297,0,0,0),(890,2,297,0,0,0),(891,3,297,0,0,0),(892,1,298,0,0,0),(893,2,298,0,0,0),(894,3,298,0,0,0),(895,1,299,0,0,0),(896,2,299,0,0,0),(897,3,299,0,0,0),(898,1,300,0,0,0),(899,2,300,0,0,0),(900,3,300,0,0,0),(901,1,301,0,0,0),(902,2,301,0,0,0),(903,3,301,0,0,0),(904,1,302,0,0,0),(905,2,302,0,0,0),(906,3,302,0,0,0),(907,1,303,0,0,0),(908,2,303,0,0,0),(909,3,303,0,0,0),(910,1,304,0,0,0),(911,2,304,0,0,0),(912,3,304,0,0,0),(913,1,305,0,0,0),(914,2,305,0,0,0),(915,3,305,0,0,0),(916,1,306,0,0,0),(917,2,306,0,0,0),(918,3,306,0,0,0),(919,1,307,0,0,0),(920,2,307,0,0,0),(921,3,307,0,0,0),(922,1,308,0,0,0),(923,2,308,0,0,0),(924,3,308,0,0,0),(925,1,309,0,0,0),(926,2,309,0,0,0),(927,3,309,0,0,0),(928,1,310,0,0,0),(929,2,310,0,0,0),(930,3,310,0,0,0),(931,1,311,0,0,0),(932,2,311,0,0,0),(933,3,311,0,0,0),(934,1,312,0,0,0),(935,2,312,0,0,0),(936,3,312,0,0,0),(937,1,313,0,0,0),(938,2,313,0,0,0),(939,3,313,0,0,0),(940,1,314,0,0,0),(941,2,314,0,0,0),(942,3,314,0,0,0),(943,1,315,0,0,0),(944,2,315,0,0,0),(945,3,315,0,0,0),(946,1,316,0,0,0),(947,2,316,0,0,0),(948,3,316,0,0,0),(949,1,317,0,0,0),(950,2,317,0,0,0),(951,3,317,0,0,0),(952,1,318,0,0,0),(953,2,318,0,0,0),(954,3,318,0,0,0),(955,1,319,0,0,0),(956,2,319,0,0,0),(957,3,319,0,0,0),(958,1,320,0,0,0),(959,2,320,0,0,0),(960,3,320,0,0,0),(961,1,321,0,0,0),(962,2,321,0,0,0),(963,3,321,0,0,0),(964,1,322,0,0,0),(965,2,322,0,0,0),(966,3,322,0,0,0),(967,1,323,0,0,0),(968,2,323,0,0,0),(969,3,323,0,0,0),(970,1,324,0,0,0),(971,2,324,0,0,0),(972,3,324,0,0,0),(973,1,325,0,0,0),(974,2,325,0,0,0),(975,3,325,0,0,0),(976,1,326,0,0,0),(977,2,326,0,0,0),(978,3,326,0,0,0),(979,1,327,0,0,0),(980,2,327,0,0,0),(981,3,327,0,0,0),(982,1,328,0,0,0),(983,2,328,0,0,0),(984,3,328,0,0,0),(985,1,329,0,0,0),(986,2,329,0,0,0),(987,3,329,0,0,0),(988,1,330,0,0,0),(989,2,330,0,0,0),(990,3,330,0,0,0),(991,1,331,0,0,0),(992,2,331,0,0,0),(993,3,331,0,0,0),(994,1,332,0,0,0),(995,2,332,0,0,0),(996,3,332,0,0,0),(997,1,333,0,0,0),(998,2,333,0,0,0),(999,3,333,0,0,0),(1000,1,334,0,0,0),(1001,2,334,0,0,0),(1002,3,334,0,0,0),(1003,1,335,0,0,0),(1004,2,335,0,0,0),(1005,3,335,0,0,0),(1006,1,336,0,0,0),(1007,2,336,0,0,0),(1008,3,336,0,0,0),(1009,1,337,0,0,0),(1010,2,337,0,0,0),(1011,3,337,0,0,0),(1012,1,338,0,0,0),(1013,2,338,0,0,0),(1014,3,338,0,0,0),(1015,1,339,0,0,0),(1016,2,339,0,0,0),(1017,3,339,0,0,0),(1018,1,340,0,0,0),(1019,2,340,0,0,0),(1020,3,340,0,0,0),(1021,1,341,0,0,0),(1022,2,341,0,0,0),(1023,3,341,0,0,0),(1024,1,342,0,0,0),(1025,2,342,0,0,0),(1026,3,342,0,0,0),(1027,1,343,0,0,0),(1028,2,343,0,0,0),(1029,3,343,0,0,0),(1030,1,344,0,0,0),(1031,2,344,0,0,0),(1032,3,344,0,0,0),(1033,1,345,0,0,0),(1034,2,345,0,0,0),(1035,3,345,0,0,0),(1036,1,346,0,0,0),(1037,2,346,0,0,0),(1038,3,346,0,0,0),(1039,1,347,0,0,0),(1040,2,347,0,0,0),(1041,3,347,0,0,0),(1042,1,348,0,0,0),(1043,2,348,0,0,0),(1044,3,348,0,0,0),(1045,1,349,0,0,0),(1046,2,349,0,0,0),(1047,3,349,0,0,0),(1048,1,350,0,0,0),(1049,2,350,0,0,0),(1050,3,350,0,0,0),(1051,1,351,0,0,0),(1052,2,351,0,0,0),(1053,3,351,0,0,0),(1054,1,352,0,0,0),(1055,2,352,0,0,0),(1056,3,352,0,0,0),(1057,1,353,0,0,0),(1058,2,353,0,0,0),(1059,3,353,0,0,0),(1060,1,354,0,0,0),(1061,2,354,0,0,0),(1062,3,354,0,0,0),(1063,1,355,0,0,0),(1064,2,355,0,0,0),(1065,3,355,0,0,0),(1066,1,356,0,0,0),(1067,2,356,0,0,0),(1068,3,356,0,0,0),(1069,1,357,0,0,0),(1070,2,357,0,0,0),(1071,3,357,0,0,0),(1072,1,358,0,0,0),(1073,2,358,0,0,0),(1074,3,358,0,0,0),(1075,1,359,0,0,0),(1076,2,359,0,0,0),(1077,3,359,0,0,0),(1078,1,360,0,0,0),(1079,2,360,0,0,0),(1080,3,360,0,0,0),(1081,1,361,0,0,0),(1082,2,361,0,0,0),(1083,3,361,0,0,0),(1084,1,362,0,0,0),(1085,2,362,0,0,0),(1086,3,362,0,0,0),(1087,1,363,0,0,0),(1088,2,363,0,0,0),(1089,3,363,0,0,0),(1090,1,364,0,0,0),(1091,2,364,0,0,0),(1092,3,364,0,0,0),(1093,1,365,0,0,0),(1094,2,365,0,0,0),(1095,3,365,0,0,0),(1096,1,366,0,0,0),(1097,2,366,0,0,0),(1098,3,366,0,0,0),(1099,1,367,0,0,0),(1100,2,367,0,0,0),(1101,3,367,0,0,0),(1102,1,368,0,0,0),(1103,2,368,0,0,0),(1104,3,368,0,0,0),(1105,1,369,0,0,0),(1106,2,369,0,0,0),(1107,3,369,0,0,0),(1108,1,370,0,0,0),(1109,2,370,0,0,0),(1110,3,370,0,0,0),(1111,1,371,0,0,0),(1112,2,371,0,0,0),(1113,3,371,0,0,0),(1114,1,372,0,0,0),(1115,2,372,0,0,0),(1116,3,372,0,0,0),(1117,1,373,0,0,0),(1118,2,373,0,0,0),(1119,3,373,0,0,0),(1120,1,374,0,0,0),(1121,2,374,0,0,0),(1122,3,374,0,0,0),(1123,1,375,0,0,0),(1124,2,375,0,0,0),(1125,3,375,0,0,0),(1126,1,376,0,0,0),(1127,2,376,0,0,0),(1128,3,376,0,0,0),(1129,1,377,0,0,0),(1130,2,377,0,0,0),(1131,3,377,0,0,0),(1132,1,378,0,0,0),(1133,2,378,0,0,0),(1134,3,378,0,0,0),(1135,1,379,0,0,0),(1136,2,379,0,0,0),(1137,3,379,0,0,0),(1138,1,380,0,0,0),(1139,2,380,0,0,0),(1140,3,380,0,0,0),(1141,1,381,0,0,0),(1142,2,381,0,0,0),(1143,3,381,0,0,0),(1144,1,382,0,0,0),(1145,2,382,0,0,0),(1146,3,382,0,0,0),(1147,1,383,0,0,0),(1148,2,383,0,0,0),(1149,3,383,0,0,0),(1150,1,384,0,0,0),(1151,2,384,0,0,0),(1152,3,384,0,0,0),(1153,1,385,0,0,0),(1154,2,385,0,0,0),(1155,3,385,0,0,0),(1156,1,386,0,0,0),(1157,2,386,0,0,0),(1158,3,386,0,0,0),(1159,1,387,0,0,0),(1160,2,387,0,0,0),(1161,3,387,0,0,0),(1162,1,388,0,0,0),(1163,2,388,0,0,0),(1164,3,388,0,0,0),(1165,1,389,0,0,0),(1166,2,389,0,0,0),(1167,3,389,0,0,0),(1168,1,390,0,0,0),(1169,2,390,0,0,0),(1170,3,390,0,0,0),(1171,1,391,0,0,0),(1172,2,391,0,0,0),(1173,3,391,0,0,0),(1174,1,392,0,0,0),(1175,2,392,0,0,0),(1176,3,392,0,0,0),(1177,1,393,0,0,0),(1178,2,393,0,0,0),(1179,3,393,0,0,0),(1180,1,394,0,0,0),(1181,2,394,0,0,0),(1182,3,394,0,0,0),(1183,1,395,0,0,0),(1184,2,395,0,0,0),(1185,3,395,0,0,0),(1186,1,396,0,0,0),(1187,2,396,0,0,0),(1188,3,396,0,0,0),(1189,1,397,0,0,0),(1190,2,397,0,0,0),(1191,3,397,0,0,0),(1192,1,398,0,0,0),(1193,2,398,0,0,0),(1194,3,398,0,0,0),(1195,1,399,0,0,0),(1196,2,399,0,0,0),(1197,3,399,0,0,0),(1198,1,400,0,0,0),(1199,2,400,0,0,0),(1200,3,400,0,0,0),(1201,1,401,0,0,0),(1202,2,401,0,0,0),(1203,3,401,0,0,0),(1204,1,402,0,0,0),(1205,2,402,0,0,0),(1206,3,402,0,0,0),(1207,1,403,0,0,0),(1208,2,403,0,0,0),(1209,3,403,0,0,0),(1210,1,404,0,0,0),(1211,2,404,0,0,0),(1212,3,404,0,0,0),(1213,1,405,0,0,0),(1214,2,405,0,0,0),(1215,3,405,0,0,0),(1216,1,406,0,0,0),(1217,2,406,0,0,0),(1218,3,406,0,0,0),(1219,1,407,0,0,0),(1220,2,407,0,0,0),(1221,3,407,0,0,0),(1222,1,408,0,0,0),(1223,2,408,0,0,0),(1224,3,408,0,0,0),(1225,1,409,0,0,0),(1226,2,409,0,0,0),(1227,3,409,0,0,0),(1228,1,410,0,0,0),(1229,2,410,0,0,0),(1230,3,410,0,0,0),(1231,1,411,0,0,0),(1232,2,411,0,0,0),(1233,3,411,0,0,0),(1234,1,412,0,0,0),(1235,2,412,0,0,0),(1236,3,412,0,0,0),(1237,1,413,0,0,0),(1238,2,413,0,0,0),(1239,3,413,0,0,0),(1240,1,414,0,0,0),(1241,2,414,0,0,0),(1242,3,414,0,0,0),(1243,1,415,0,0,0),(1244,2,415,0,0,0),(1245,3,415,0,0,0),(1246,1,416,0,0,0),(1247,2,416,0,0,0),(1248,3,416,0,0,0),(1249,1,417,0,0,0),(1250,2,417,0,0,0),(1251,3,417,0,0,0),(1252,1,418,0,0,0),(1253,2,418,0,0,0),(1254,3,418,0,0,0),(1255,1,419,0,0,0),(1256,2,419,0,0,0),(1257,3,419,0,0,0),(1258,1,420,0,0,0),(1259,2,420,0,0,0),(1260,3,420,0,0,0),(1261,1,421,0,0,0),(1262,2,421,0,0,0),(1263,3,421,0,0,0),(1264,1,422,0,0,0),(1265,2,422,0,0,0),(1266,3,422,0,0,0),(1267,1,423,0,0,0),(1268,2,423,0,0,0),(1269,3,423,0,0,0),(1270,1,424,0,0,0),(1271,2,424,0,0,0),(1272,3,424,0,0,0),(1273,1,425,0,0,0),(1274,2,425,0,0,0),(1275,3,425,0,0,0),(1276,1,426,0,0,0),(1277,2,426,0,0,0),(1278,3,426,0,0,0),(1279,1,427,0,0,0),(1280,2,427,0,0,0),(1281,3,427,0,0,0),(1282,1,428,0,0,0),(1283,2,428,0,0,0),(1284,3,428,0,0,0),(1285,1,429,0,0,0),(1286,2,429,0,0,0),(1287,3,429,0,0,0),(1288,1,430,0,0,0),(1289,2,430,0,0,0),(1290,3,430,0,0,0),(1291,1,431,0,0,0),(1292,2,431,0,0,0),(1293,3,431,0,0,0),(1294,1,432,0,0,0),(1295,2,432,0,0,0),(1296,3,432,0,0,0),(1297,1,433,0,0,0),(1298,2,433,0,0,0),(1299,3,433,0,0,0),(1300,1,434,0,0,0),(1301,2,434,0,0,0),(1302,3,434,0,0,0),(1303,1,435,0,0,0),(1304,2,435,0,0,0),(1305,3,435,0,0,0),(1306,1,436,0,0,0),(1307,2,436,0,0,0),(1308,3,436,0,0,0),(1309,1,437,0,0,0),(1310,2,437,0,0,0),(1311,3,437,0,0,0),(1312,1,438,0,0,0),(1313,2,438,0,0,0),(1314,3,438,0,0,0),(1315,1,439,0,0,0),(1316,2,439,0,0,0),(1317,3,439,0,0,0),(1318,1,440,0,0,0),(1319,2,440,0,0,0),(1320,3,440,0,0,0),(1321,1,441,0,0,0),(1322,2,441,0,0,0),(1323,3,441,0,0,0),(1324,1,442,0,0,0),(1325,2,442,0,0,0),(1326,3,442,0,0,0),(1327,1,443,0,0,0),(1328,2,443,0,0,0),(1329,3,443,0,0,0),(1330,1,444,0,0,0),(1331,2,444,0,0,0),(1332,3,444,0,0,0),(1333,1,445,0,0,0),(1334,2,445,0,0,0),(1335,3,445,0,0,0),(1336,1,446,0,0,0),(1337,2,446,0,0,0),(1338,3,446,0,0,0),(1339,1,447,0,0,0),(1340,2,447,0,0,0),(1341,3,447,0,0,0),(1342,1,448,0,0,0),(1343,2,448,0,0,0),(1344,3,448,0,0,0),(1345,1,449,0,0,0),(1346,2,449,0,0,0),(1347,3,449,0,0,0),(1348,1,450,0,0,0),(1349,2,450,0,0,0),(1350,3,450,0,0,0),(1351,1,451,0,0,0),(1352,2,451,0,0,0),(1353,3,451,0,0,0),(1354,1,452,0,0,0),(1355,2,452,0,0,0),(1356,3,452,0,0,0),(1357,1,453,0,0,0),(1358,2,453,0,0,0),(1359,3,453,0,0,0),(1360,1,454,0,0,0),(1361,2,454,0,0,0),(1362,3,454,0,0,0),(1363,1,455,0,0,0),(1364,2,455,0,0,0),(1365,3,455,0,0,0),(1366,1,456,0,0,0),(1367,2,456,0,0,0),(1368,3,456,0,0,0),(1369,1,457,0,0,0),(1370,2,457,0,0,0),(1371,3,457,0,0,0),(1372,1,458,0,0,0),(1373,2,458,0,0,0),(1374,3,458,0,0,0),(1375,1,459,0,0,0),(1376,2,459,0,0,0),(1377,3,459,0,0,0),(1378,1,460,0,0,0),(1379,2,460,0,0,0),(1380,3,460,0,0,0),(1381,1,461,0,0,0),(1382,2,461,0,0,0),(1383,3,461,0,0,0),(1384,1,462,0,0,0),(1385,2,462,0,0,0),(1386,3,462,0,0,0),(1387,1,463,0,0,0),(1388,2,463,0,0,0),(1389,3,463,0,0,0),(1390,1,464,0,0,0),(1391,2,464,0,0,0),(1392,3,464,0,0,0),(1393,1,465,0,0,0),(1394,2,465,0,0,0),(1395,3,465,0,0,0),(1396,1,466,0,0,0),(1397,2,466,0,0,0),(1398,3,466,0,0,0),(1399,1,467,0,0,0),(1400,2,467,0,0,0),(1401,3,467,0,0,0),(1402,1,468,0,0,0),(1403,2,468,0,0,0),(1404,3,468,0,0,0),(1405,1,469,0,0,0),(1406,2,469,0,0,0),(1407,3,469,0,0,0),(1408,1,470,0,0,0),(1409,2,470,0,0,0),(1410,3,470,0,0,0),(1411,1,471,0,0,0),(1412,2,471,0,0,0),(1413,3,471,0,0,0),(1414,1,472,0,0,0),(1415,2,472,0,0,0),(1416,3,472,0,0,0),(1417,1,473,0,0,0),(1418,2,473,0,0,0),(1419,3,473,0,0,0),(1420,1,474,0,0,0),(1421,2,474,0,0,0),(1422,3,474,0,0,0),(1423,1,475,0,0,0),(1424,2,475,0,0,0),(1425,3,475,0,0,0),(1426,1,476,0,0,0),(1427,2,476,0,0,0),(1428,3,476,0,0,0),(1429,1,477,0,0,0),(1430,2,477,0,0,0),(1431,3,477,0,0,0),(1432,1,478,0,0,0),(1433,2,478,0,0,0),(1434,3,478,0,0,0),(1435,1,479,0,0,0),(1436,2,479,0,0,0),(1437,3,479,0,0,0),(1438,1,480,0,0,0),(1439,2,480,0,0,0),(1440,3,480,0,0,0),(1441,1,481,0,0,0),(1442,2,481,0,0,0),(1443,3,481,0,0,0),(1444,1,482,0,0,0),(1445,2,482,0,0,0),(1446,3,482,0,0,0),(1447,1,483,0,0,0),(1448,2,483,0,0,0),(1449,3,483,0,0,0),(1450,1,484,0,0,0),(1451,2,484,0,0,0),(1452,3,484,0,0,0),(1453,1,485,0,0,0),(1454,2,485,0,0,0),(1455,3,485,0,0,0),(1456,1,486,0,0,0),(1457,2,486,0,0,0),(1458,3,486,0,0,0),(1459,1,487,0,0,0),(1460,2,487,0,0,0),(1461,3,487,0,0,0),(1462,1,488,0,0,0),(1463,2,488,0,0,0),(1464,3,488,0,0,0),(1465,1,489,0,0,0),(1466,2,489,0,0,0),(1467,3,489,0,0,0),(1468,1,490,0,0,0),(1469,2,490,0,0,0),(1470,3,490,0,0,0),(1471,1,491,0,0,0),(1472,2,491,0,0,0),(1473,3,491,0,0,0),(1474,1,492,0,0,0),(1475,2,492,0,0,0),(1476,3,492,0,0,0),(1477,1,493,0,0,0),(1478,2,493,0,0,0),(1479,3,493,0,0,0),(1480,1,494,0,0,0),(1481,2,494,0,0,0),(1482,3,494,0,0,0),(1483,1,495,0,0,0),(1484,2,495,0,0,0),(1485,3,495,0,0,0),(1486,1,496,0,0,0),(1487,2,496,0,0,0),(1488,3,496,0,0,0),(1489,1,497,0,0,0),(1490,2,497,0,0,0),(1491,3,497,0,0,0),(1492,1,498,0,0,0),(1493,2,498,0,0,0),(1494,3,498,0,0,0),(1495,1,499,0,0,0),(1496,2,499,0,0,0),(1497,3,499,0,0,0),(1498,1,500,0,0,0),(1499,2,500,0,0,0),(1500,3,500,0,0,0),(1501,1,501,0,0,0),(1502,2,501,0,0,0),(1503,3,501,0,0,0),(1504,1,502,0,0,0),(1505,2,502,0,0,0),(1506,3,502,0,0,0),(1507,1,503,0,0,0),(1508,2,503,0,0,0),(1509,3,503,0,0,0),(1510,1,504,0,0,0),(1511,2,504,0,0,0),(1512,3,504,0,0,0),(1513,1,505,0,0,0),(1514,2,505,0,0,0),(1515,3,505,0,0,0),(1516,1,506,0,0,0),(1517,2,506,0,0,0),(1518,3,506,0,0,0),(1519,1,507,0,0,0),(1520,2,507,0,0,0),(1521,3,507,0,0,0),(1522,1,508,0,0,0),(1523,2,508,0,0,0),(1524,3,508,0,0,0),(1525,1,509,0,0,0),(1526,2,509,0,0,0),(1527,3,509,0,0,0),(1528,1,510,0,0,0),(1529,2,510,0,0,0),(1530,3,510,0,0,0),(1531,1,511,0,0,0),(1532,2,511,0,0,0),(1533,3,511,0,0,0),(1534,1,512,0,0,0),(1535,2,512,0,0,0),(1536,3,512,0,0,0),(1537,1,513,0,0,0),(1538,2,513,0,0,0),(1539,3,513,0,0,0),(1540,1,514,0,0,0),(1541,2,514,0,0,0),(1542,3,514,0,0,0),(1543,1,515,0,0,0),(1544,2,515,0,0,0),(1545,3,515,0,0,0),(1546,1,516,0,0,0),(1547,2,516,0,0,0),(1548,3,516,0,0,0),(1549,1,517,0,0,0),(1550,2,517,0,0,0),(1551,3,517,0,0,0),(1552,1,518,0,0,0),(1553,2,518,0,0,0),(1554,3,518,0,0,0),(1555,1,519,0,0,0),(1556,2,519,0,0,0),(1557,3,519,0,0,0),(1558,1,520,0,0,0),(1559,2,520,0,0,0),(1560,3,520,0,0,0),(1561,1,521,0,0,0),(1562,2,521,0,0,0),(1563,3,521,0,0,0),(1564,1,522,0,0,0),(1565,2,522,0,0,0),(1566,3,522,0,0,0),(1567,1,523,0,0,0),(1568,2,523,0,0,0),(1569,3,523,0,0,0),(1570,1,524,0,0,0),(1571,2,524,0,0,0),(1572,3,524,0,0,0),(1573,1,525,0,0,0),(1574,2,525,0,0,0),(1575,3,525,0,0,0),(1576,1,526,0,0,0),(1577,2,526,0,0,0),(1578,3,526,0,0,0),(1579,1,527,0,0,0),(1580,2,527,0,0,0),(1581,3,527,0,0,0),(1582,1,528,0,0,0),(1583,2,528,0,0,0),(1584,3,528,0,0,0),(1585,1,529,0,0,0),(1586,2,529,0,0,0),(1587,3,529,0,0,0),(1588,1,530,0,0,0),(1589,2,530,0,0,0),(1590,3,530,0,0,0),(1591,1,531,0,0,0),(1592,2,531,0,0,0),(1593,3,531,0,0,0),(1594,1,532,0,0,0),(1595,2,532,0,0,0),(1596,3,532,0,0,0),(1597,1,533,0,0,0),(1598,2,533,0,0,0),(1599,3,533,0,0,0),(1600,1,534,0,0,0),(1601,2,534,0,0,0),(1602,3,534,0,0,0),(1603,1,535,0,0,0),(1604,2,535,0,0,0),(1605,3,535,0,0,0),(1606,1,536,0,0,0),(1607,2,536,0,0,0),(1608,3,536,0,0,0),(1609,1,537,0,0,0),(1610,2,537,0,0,0),(1611,3,537,0,0,0),(1612,1,538,0,0,0),(1613,2,538,0,0,0),(1614,3,538,0,0,0),(1615,1,539,0,0,0),(1616,2,539,0,0,0),(1617,3,539,0,0,0),(1618,1,540,0,0,0),(1619,2,540,0,0,0),(1620,3,540,0,0,0),(1621,1,541,0,0,0),(1622,2,541,0,0,0),(1623,3,541,0,0,0),(1624,1,542,0,0,0),(1625,2,542,0,0,0),(1626,3,542,0,0,0),(1627,1,543,0,0,0),(1628,2,543,0,0,0),(1629,3,543,0,0,0),(1630,1,544,0,0,0),(1631,2,544,0,0,0),(1632,3,544,0,0,0),(1633,1,545,0,0,0),(1634,2,545,0,0,0),(1635,3,545,0,0,0),(1636,1,546,0,0,0),(1637,2,546,0,0,0),(1638,3,546,0,0,0),(1639,1,547,0,0,0),(1640,2,547,0,0,0),(1641,3,547,0,0,0),(1642,1,548,0,0,0),(1643,2,548,0,0,0),(1644,3,548,0,0,0),(1645,1,549,0,0,0),(1646,2,549,0,0,0),(1647,3,549,0,0,0),(1648,1,550,0,0,0),(1649,2,550,0,0,0),(1650,3,550,0,0,0),(1651,1,551,0,0,0),(1652,2,551,0,0,0),(1653,3,551,0,0,0),(1654,1,552,0,0,0),(1655,2,552,0,0,0),(1656,3,552,0,0,0),(1657,1,553,0,0,0),(1658,2,553,0,0,0),(1659,3,553,0,0,0),(1660,1,554,0,0,0),(1661,2,554,0,0,0),(1662,3,554,0,0,0),(1663,1,555,0,0,0),(1664,2,555,0,0,0),(1665,3,555,0,0,0),(1666,1,556,0,0,0),(1667,2,556,0,0,0),(1668,3,556,0,0,0),(1669,1,557,0,0,0),(1670,2,557,0,0,0),(1671,3,557,0,0,0),(1672,1,558,0,0,0),(1673,2,558,0,0,0),(1674,3,558,0,0,0),(1675,1,559,0,0,0),(1676,2,559,0,0,0),(1677,3,559,0,0,0),(1678,1,560,0,0,0),(1679,2,560,0,0,0),(1680,3,560,0,0,0),(1681,1,561,0,0,0),(1682,2,561,0,0,0),(1683,3,561,0,0,0),(1684,1,562,0,0,0),(1685,2,562,0,0,0),(1686,3,562,0,0,0),(1687,1,563,0,0,0),(1688,2,563,0,0,0),(1689,3,563,0,0,0),(1690,1,564,0,0,0),(1691,2,564,0,0,0),(1692,3,564,0,0,0),(1693,1,565,0,0,0),(1694,2,565,0,0,0),(1695,3,565,0,0,0),(1696,1,566,0,0,0),(1697,2,566,0,0,0),(1698,3,566,0,0,0),(1699,1,567,0,0,0),(1700,2,567,0,0,0),(1701,3,567,0,0,0),(1702,1,568,0,0,0),(1703,2,568,0,0,0),(1704,3,568,0,0,0),(1705,1,569,0,0,0),(1706,2,569,0,0,0),(1707,3,569,0,0,0),(1708,1,570,0,0,0),(1709,2,570,0,0,0),(1710,3,570,0,0,0),(1711,1,571,0,0,0),(1712,2,571,0,0,0),(1713,3,571,0,0,0),(1714,1,572,0,0,0),(1715,2,572,0,0,0),(1716,3,572,0,0,0),(1717,1,573,0,0,0),(1718,2,573,0,0,0),(1719,3,573,0,0,0),(1720,1,574,0,0,0),(1721,2,574,0,0,0),(1722,3,574,0,0,0),(1723,1,575,0,0,0),(1724,2,575,0,0,0),(1725,3,575,0,0,0),(1726,1,576,0,0,0),(1727,2,576,0,0,0),(1728,3,576,0,0,0),(1729,1,577,0,0,0),(1730,2,577,0,0,0),(1731,3,577,0,0,0),(1732,1,578,0,0,0),(1733,2,578,0,0,0),(1734,3,578,0,0,0),(1735,1,579,0,0,0),(1736,2,579,0,0,0),(1737,3,579,0,0,0),(1738,1,580,0,0,0),(1739,2,580,0,0,0),(1740,3,580,0,0,0),(1741,1,581,0,0,0),(1742,2,581,0,0,0),(1743,3,581,0,0,0),(1744,1,582,0,0,0),(1745,2,582,0,0,0),(1746,3,582,0,0,0),(1747,1,583,0,0,0),(1748,2,583,0,0,0),(1749,3,583,0,0,0),(1750,1,584,0,0,0),(1751,2,584,0,0,0),(1752,3,584,0,0,0),(1753,1,585,0,0,0),(1754,2,585,0,0,0),(1755,3,585,0,0,0),(1756,1,586,0,0,0),(1757,2,586,0,0,0),(1758,3,586,0,0,0),(1759,1,587,0,0,0),(1760,2,587,0,0,0),(1761,3,587,0,0,0),(1762,1,588,0,0,0),(1763,2,588,0,0,0),(1764,3,588,0,0,0),(1765,1,589,0,0,0),(1766,2,589,0,0,0),(1767,3,589,0,0,0),(1768,1,590,0,0,0),(1769,2,590,0,0,0),(1770,3,590,0,0,0),(1771,1,591,0,0,0),(1772,2,591,0,0,0),(1773,3,591,0,0,0),(1774,1,592,0,0,0),(1775,2,592,0,0,0),(1776,3,592,0,0,0),(1777,1,593,0,0,0),(1778,2,593,0,0,0),(1779,3,593,0,0,0),(1780,1,594,0,0,0),(1781,2,594,0,0,0),(1782,3,594,0,0,0),(1783,1,595,0,0,0),(1784,2,595,0,0,0),(1785,3,595,0,0,0),(1786,1,596,0,0,0),(1787,2,596,0,0,0),(1788,3,596,0,0,0),(1789,1,597,0,0,0),(1790,2,597,0,0,0),(1791,3,597,0,0,0),(1792,1,598,0,0,0),(1793,2,598,0,0,0),(1794,3,598,0,0,0),(1795,1,599,0,0,0),(1796,2,599,0,0,0),(1797,3,599,0,0,0),(1798,1,600,0,0,0),(1799,2,600,0,0,0),(1800,3,600,0,0,0),(1801,1,601,0,0,0),(1802,2,601,0,0,0),(1803,3,601,0,0,0),(1804,1,602,0,0,0),(1805,2,602,0,0,0),(1806,3,602,0,0,0),(1807,1,603,0,0,0),(1808,2,603,0,0,0),(1809,3,603,0,0,0),(1810,1,604,0,0,0),(1811,2,604,0,0,0),(1812,3,604,0,0,0),(1813,1,605,0,0,0),(1814,2,605,0,0,0),(1815,3,605,0,0,0),(1816,1,606,0,0,0),(1817,2,606,0,0,0),(1818,3,606,0,0,0),(1819,1,607,0,0,0),(1820,2,607,0,0,0),(1821,3,607,0,0,0),(1822,1,608,0,0,0),(1823,2,608,0,0,0),(1824,3,608,0,0,0),(1825,1,609,0,0,0),(1826,2,609,0,0,0),(1827,3,609,0,0,0),(1828,1,610,0,0,0),(1829,2,610,0,0,0),(1830,3,610,0,0,0),(1831,1,611,0,0,0),(1832,2,611,0,0,0),(1833,3,611,0,0,0),(1834,1,612,0,0,0),(1835,2,612,0,0,0),(1836,3,612,0,0,0),(1837,1,613,0,0,0),(1838,2,613,0,0,0),(1839,3,613,0,0,0),(1840,1,614,0,0,0),(1841,2,614,0,0,0),(1842,3,614,0,0,0),(1843,1,615,0,0,0),(1844,2,615,0,0,0),(1845,3,615,0,0,0),(1846,1,616,0,0,0),(1847,2,616,0,0,0),(1848,3,616,0,0,0),(1849,1,617,0,0,0),(1850,2,617,0,0,0),(1851,3,617,0,0,0),(1852,1,618,0,0,0),(1853,2,618,0,0,0),(1854,3,618,0,0,0),(1855,1,619,0,0,0),(1856,2,619,0,0,0),(1857,3,619,0,0,0),(1858,1,620,0,0,0),(1859,2,620,0,0,0),(1860,3,620,0,0,0),(1861,1,621,0,0,0),(1862,2,621,0,0,0),(1863,3,621,0,0,0),(1864,1,622,0,0,0),(1865,2,622,0,0,0),(1866,3,622,0,0,0),(1867,1,623,0,0,0),(1868,2,623,0,0,0),(1869,3,623,0,0,0),(1870,1,624,0,0,0),(1871,2,624,0,0,0),(1872,3,624,0,0,0),(1873,1,625,0,0,0),(1874,2,625,0,0,0),(1875,3,625,0,0,0),(1876,1,626,0,0,0),(1877,2,626,0,0,0),(1878,3,626,0,0,0),(1879,1,627,0,0,0),(1880,2,627,0,0,0),(1881,3,627,0,0,0),(1882,1,628,0,0,0),(1883,2,628,0,0,0),(1884,3,628,0,0,0),(1885,1,629,0,0,0),(1886,2,629,0,0,0),(1887,3,629,0,0,0),(1888,1,630,0,0,0),(1889,2,630,0,0,0),(1890,3,630,0,0,0),(1891,1,631,0,0,0),(1892,2,631,0,0,0),(1893,3,631,0,0,0),(1894,1,632,0,0,0),(1895,2,632,0,0,0),(1896,3,632,0,0,0),(1897,1,633,0,0,0),(1898,2,633,0,0,0),(1899,3,633,0,0,0),(1900,1,634,0,0,0),(1901,2,634,0,0,0),(1902,3,634,0,0,0),(1903,1,635,0,0,0),(1904,2,635,0,0,0),(1905,3,635,0,0,0),(1906,1,636,0,0,0),(1907,2,636,0,0,0),(1908,3,636,0,0,0),(1909,1,637,0,0,0),(1910,2,637,0,0,0),(1911,3,637,0,0,0),(1912,1,638,0,0,0),(1913,2,638,0,0,0),(1914,3,638,0,0,0),(1915,1,639,0,0,0),(1916,2,639,0,0,0),(1917,3,639,0,0,0),(1918,1,640,0,0,0),(1919,2,640,0,0,0),(1920,3,640,0,0,0),(1921,1,641,0,0,0),(1922,2,641,0,0,0),(1923,3,641,0,0,0),(1924,1,642,0,0,0),(1925,2,642,0,0,0),(1926,3,642,0,0,0),(1927,1,643,0,0,0),(1928,2,643,0,0,0),(1929,3,643,0,0,0),(1930,1,644,0,0,0),(1931,2,644,0,0,0),(1932,3,644,0,0,0),(1933,1,645,0,0,0),(1934,2,645,0,0,0),(1935,3,645,0,0,0),(1936,1,646,0,0,0),(1937,2,646,0,0,0),(1938,3,646,0,0,0),(1939,1,647,0,0,0),(1940,2,647,0,0,0),(1941,3,647,0,0,0),(1942,1,648,0,0,0),(1943,2,648,0,0,0),(1944,3,648,0,0,0),(1945,1,649,0,0,0),(1946,2,649,0,0,0),(1947,3,649,0,0,0),(1948,1,650,0,0,0),(1949,2,650,0,0,0),(1950,3,650,0,0,0),(1951,1,651,0,0,0),(1952,2,651,0,0,0),(1953,3,651,0,0,0),(1954,1,652,0,0,0),(1955,2,652,0,0,0),(1956,3,652,0,0,0),(1957,1,653,0,0,0),(1958,2,653,0,0,0),(1959,3,653,0,0,0),(1960,1,654,0,0,0),(1961,2,654,0,0,0),(1962,3,654,0,0,0),(1963,1,655,0,0,0),(1964,2,655,0,0,0),(1965,3,655,0,0,0),(1966,1,656,0,0,0),(1967,2,656,0,0,0),(1968,3,656,0,0,0),(1969,1,657,0,0,0),(1970,2,657,0,0,0),(1971,3,657,0,0,0),(1972,1,658,0,0,0),(1973,2,658,0,0,0),(1974,3,658,0,0,0),(1975,1,659,0,0,0),(1976,2,659,0,0,0),(1977,3,659,0,0,0),(1978,1,660,0,0,0),(1979,2,660,0,0,0),(1980,3,660,0,0,0),(1981,1,661,0,0,0),(1982,2,661,0,0,0),(1983,3,661,0,0,0),(1984,1,662,0,0,0),(1985,2,662,0,0,0),(1986,3,662,0,0,0),(1987,1,663,0,0,0),(1988,2,663,0,0,0),(1989,3,663,0,0,0),(1990,1,664,0,0,0),(1991,2,664,0,0,0),(1992,3,664,0,0,0),(1993,1,665,0,0,0),(1994,2,665,0,0,0),(1995,3,665,0,0,0),(1996,1,666,0,0,0),(1997,2,666,0,0,0),(1998,3,666,0,0,0),(1999,1,667,0,0,0),(2000,2,667,0,0,0),(2001,3,667,0,0,0),(2002,1,668,0,0,0),(2003,2,668,0,0,0),(2004,3,668,0,0,0),(2005,1,669,0,0,0),(2006,2,669,0,0,0),(2007,3,669,0,0,0),(2008,1,670,0,0,0),(2009,2,670,0,0,0),(2010,3,670,0,0,0),(2011,1,671,0,0,0),(2012,2,671,0,0,0),(2013,3,671,0,0,0),(2014,1,672,0,0,0),(2015,2,672,0,0,0),(2016,3,672,0,0,0),(2017,1,673,0,0,0),(2018,2,673,0,0,0),(2019,3,673,0,0,0),(2020,1,674,0,0,0),(2021,2,674,0,0,0),(2022,3,674,0,0,0),(2023,1,675,0,0,0),(2024,2,675,0,0,0),(2025,3,675,0,0,0),(2026,1,676,0,0,0),(2027,2,676,0,0,0),(2028,3,676,0,0,0),(2029,1,677,0,0,0),(2030,2,677,0,0,0),(2031,3,677,0,0,0),(2032,1,678,0,0,0),(2033,2,678,0,0,0),(2034,3,678,0,0,0),(2035,1,679,0,0,0),(2036,2,679,0,0,0),(2037,3,679,0,0,0),(2038,1,680,0,0,0),(2039,2,680,0,0,0),(2040,3,680,0,0,0),(2041,1,681,0,0,0),(2042,2,681,0,0,0),(2043,3,681,0,0,0),(2044,1,682,0,0,0),(2045,2,682,0,0,0),(2046,3,682,0,0,0),(2047,1,683,0,0,0),(2048,2,683,0,0,0),(2049,3,683,0,0,0),(2050,1,684,0,0,0),(2051,2,684,0,0,0),(2052,3,684,0,0,0),(2053,1,685,0,0,0),(2054,2,685,0,0,0),(2055,3,685,0,0,0),(2056,1,686,0,0,0),(2057,2,686,0,0,0),(2058,3,686,0,0,0),(2059,1,687,0,0,0),(2060,2,687,0,0,0),(2061,3,687,0,0,0),(2062,1,688,0,0,0),(2063,2,688,0,0,0),(2064,3,688,0,0,0),(2065,1,689,0,0,0),(2066,2,689,0,0,0),(2067,3,689,0,0,0),(2068,1,690,0,0,0),(2069,2,690,0,0,0),(2070,3,690,0,0,0),(2071,1,691,0,0,0),(2072,2,691,0,0,0),(2073,3,691,0,0,0),(2074,1,692,0,0,0),(2075,2,692,0,0,0),(2076,3,692,0,0,0),(2077,1,693,0,0,0),(2078,2,693,0,0,0),(2079,3,693,0,0,0),(2080,1,694,0,0,0),(2081,2,694,0,0,0),(2082,3,694,0,0,0),(2083,1,695,0,0,0),(2084,2,695,0,0,0),(2085,3,695,0,0,0),(2086,1,696,0,0,0),(2087,2,696,0,0,0),(2088,3,696,0,0,0),(2089,1,697,0,0,0),(2090,2,697,0,0,0),(2091,3,697,0,0,0),(2092,1,698,0,0,0),(2093,2,698,0,0,0),(2094,3,698,0,0,0),(2095,1,699,0,0,0),(2096,2,699,0,0,0),(2097,3,699,0,0,0),(2098,1,700,0,0,0),(2099,2,700,0,0,0),(2100,3,700,0,0,0),(2101,1,701,0,0,0),(2102,2,701,0,0,0),(2103,3,701,0,0,0),(2104,1,702,0,0,0),(2105,2,702,0,0,0),(2106,3,702,0,0,0),(2107,1,703,0,0,0),(2108,2,703,0,0,0),(2109,3,703,0,0,0),(2110,1,704,0,0,0),(2111,2,704,0,0,0),(2112,3,704,0,0,0),(2113,1,705,0,0,0),(2114,2,705,0,0,0),(2115,3,705,0,0,0),(2116,1,706,0,0,0),(2117,2,706,0,0,0),(2118,3,706,0,0,0),(2119,1,707,0,0,0),(2120,2,707,0,0,0),(2121,3,707,0,0,0),(2122,1,708,0,0,0),(2123,2,708,0,0,0),(2124,3,708,0,0,0),(2125,1,709,0,0,0),(2126,2,709,0,0,0),(2127,3,709,0,0,0),(2128,1,710,0,0,0),(2129,2,710,0,0,0),(2130,3,710,0,0,0),(2131,1,711,0,0,0),(2132,2,711,0,0,0),(2133,3,711,0,0,0),(2134,1,712,0,0,0),(2135,2,712,0,0,0),(2136,3,712,0,0,0),(2137,1,713,0,0,0),(2138,2,713,0,0,0),(2139,3,713,0,0,0),(2140,1,714,0,0,0),(2141,2,714,0,0,0),(2142,3,714,0,0,0),(2143,1,715,0,0,0),(2144,2,715,0,0,0),(2145,3,715,0,0,0),(2146,1,716,0,0,0),(2147,2,716,0,0,0),(2148,3,716,0,0,0),(2149,1,717,0,0,0),(2150,2,717,0,0,0),(2151,3,717,0,0,0),(2152,1,718,0,0,0),(2153,2,718,0,0,0),(2154,3,718,0,0,0),(2155,1,719,0,0,0),(2156,2,719,0,0,0),(2157,3,719,0,0,0),(2158,1,720,0,0,0),(2159,2,720,0,0,0),(2160,3,720,0,0,0),(2161,1,721,0,0,0),(2162,2,721,0,0,0),(2163,3,721,0,0,0),(2164,1,722,0,0,0),(2165,2,722,0,0,0),(2166,3,722,0,0,0),(2167,1,723,0,0,0),(2168,2,723,0,0,0),(2169,3,723,0,0,0),(2170,1,724,0,0,0),(2171,2,724,0,0,0),(2172,3,724,0,0,0),(2173,1,725,0,0,0),(2174,2,725,0,0,0),(2175,3,725,0,0,0),(2176,1,726,0,0,0),(2177,2,726,0,0,0),(2178,3,726,0,0,0),(2179,1,727,0,0,0),(2180,2,727,0,0,0),(2181,3,727,0,0,0),(2182,1,728,0,0,0),(2183,2,728,0,0,0),(2184,3,728,0,0,0),(2185,1,729,0,0,0),(2186,2,729,0,0,0),(2187,3,729,0,0,0),(2188,1,730,0,0,0),(2189,2,730,0,0,0),(2190,3,730,0,0,0),(2191,1,731,0,0,0),(2192,2,731,0,0,0),(2193,3,731,0,0,0),(2194,1,732,0,0,0),(2195,2,732,0,0,0),(2196,3,732,0,0,0),(2197,1,733,0,0,0),(2198,2,733,0,0,0),(2199,3,733,0,0,0),(2200,1,734,0,0,0),(2201,2,734,0,0,0),(2202,3,734,0,0,0),(2203,1,735,0,0,0),(2204,2,735,0,0,0),(2205,3,735,0,0,0),(2206,1,736,0,0,0),(2207,2,736,0,0,0),(2208,3,736,0,0,0),(2209,1,737,0,0,0),(2210,2,737,0,0,0),(2211,3,737,0,0,0),(2212,1,738,0,0,0),(2213,2,738,0,0,0),(2214,3,738,0,0,0),(2215,1,739,0,0,0),(2216,2,739,0,0,0),(2217,3,739,0,0,0),(2218,1,740,0,0,0),(2219,2,740,0,0,0),(2220,3,740,0,0,0),(2221,1,741,0,0,0),(2222,2,741,0,0,0),(2223,3,741,0,0,0),(2224,1,742,0,0,0),(2225,2,742,0,0,0),(2226,3,742,0,0,0),(2227,1,743,0,0,0),(2228,2,743,0,0,0),(2229,3,743,0,0,0),(2230,1,744,0,0,0),(2231,2,744,0,0,0),(2232,3,744,0,0,0),(2233,1,745,0,0,0),(2234,2,745,0,0,0),(2235,3,745,0,0,0),(2236,1,746,0,0,0),(2237,2,746,0,0,0),(2238,3,746,0,0,0),(2239,1,747,0,0,0),(2240,2,747,0,0,0),(2241,3,747,0,0,0),(2242,1,748,0,0,0),(2243,2,748,0,0,0),(2244,3,748,0,0,0),(2245,1,749,0,0,0),(2246,2,749,0,0,0),(2247,3,749,0,0,0),(2248,1,750,0,0,0),(2249,2,750,0,0,0),(2250,3,750,0,0,0),(2251,1,751,0,0,0),(2252,2,751,0,0,0),(2253,3,751,0,0,0),(2254,1,752,0,0,0),(2255,2,752,0,0,0),(2256,3,752,0,0,0),(2257,1,753,0,0,0),(2258,2,753,0,0,0),(2259,3,753,0,0,0),(2260,1,754,0,0,0),(2261,2,754,0,0,0),(2262,3,754,0,0,0),(2263,1,755,0,0,0),(2264,2,755,0,0,0),(2265,3,755,0,0,0),(2266,1,756,0,0,0),(2267,2,756,0,0,0),(2268,3,756,0,0,0),(2269,1,757,0,0,0),(2270,2,757,0,0,0),(2271,3,757,0,0,0),(2272,1,758,0,0,0),(2273,2,758,0,0,0),(2274,3,758,0,0,0),(2275,1,759,0,0,0),(2276,2,759,0,0,0),(2277,3,759,0,0,0),(2278,1,760,0,0,0),(2279,2,760,0,0,0),(2280,3,760,0,0,0),(2281,1,761,0,0,0),(2282,2,761,0,0,0),(2283,3,761,0,0,0),(2284,1,762,0,0,0),(2285,2,762,0,0,0),(2286,3,762,0,0,0),(2287,1,763,0,0,0),(2288,2,763,0,0,0),(2289,3,763,0,0,0),(2290,1,764,0,0,0),(2291,2,764,0,0,0),(2292,3,764,0,0,0),(2293,1,765,0,0,0),(2294,2,765,0,0,0),(2295,3,765,0,0,0),(2296,1,766,0,0,0),(2297,2,766,0,0,0),(2298,3,766,0,0,0),(2299,1,767,0,0,0),(2300,2,767,0,0,0),(2301,3,767,0,0,0),(2302,1,768,0,0,0),(2303,2,768,0,0,0),(2304,3,768,0,0,0),(2305,1,769,0,0,0),(2306,2,769,0,0,0),(2307,3,769,0,0,0),(2308,1,770,0,0,0),(2309,2,770,0,0,0),(2310,3,770,0,0,0),(2311,1,771,0,0,0),(2312,2,771,0,0,0),(2313,3,771,0,0,0),(2314,1,772,0,0,0),(2315,2,772,0,0,0),(2316,3,772,0,0,0),(2317,1,773,0,0,0),(2318,2,773,0,0,0),(2319,3,773,0,0,0),(2320,1,774,0,0,0),(2321,2,774,0,0,0),(2322,3,774,0,0,0),(2323,1,775,0,0,0),(2324,2,775,0,0,0),(2325,3,775,0,0,0),(2326,1,776,0,0,0),(2327,2,776,0,0,0),(2328,3,776,0,0,0),(2329,1,777,0,0,0),(2330,2,777,0,0,0),(2331,3,777,0,0,0),(2332,1,778,0,0,0),(2333,2,778,0,0,0),(2334,3,778,0,0,0),(2335,1,779,0,0,0),(2336,2,779,0,0,0),(2337,3,779,0,0,0),(2338,1,780,0,0,0),(2339,2,780,0,0,0),(2340,3,780,0,0,0),(2341,1,781,0,0,0),(2342,2,781,0,0,0),(2343,3,781,0,0,0),(2344,1,782,0,0,0),(2345,2,782,0,0,0),(2346,3,782,0,0,0),(2347,1,783,0,0,0),(2348,2,783,0,0,0),(2349,3,783,0,0,0),(2350,1,784,0,0,0),(2351,2,784,0,0,0),(2352,3,784,0,0,0),(2353,1,785,0,0,0),(2354,2,785,0,0,0),(2355,3,785,0,0,0),(2356,1,786,0,0,0),(2357,2,786,0,0,0),(2358,3,786,0,0,0),(2359,1,787,0,0,0),(2360,2,787,0,0,0),(2361,3,787,0,0,0),(2362,1,788,0,0,0),(2363,2,788,0,0,0),(2364,3,788,0,0,0),(2365,1,789,0,0,0),(2366,2,789,0,0,0),(2367,3,789,0,0,0),(2368,1,790,0,0,0),(2369,2,790,0,0,0),(2370,3,790,0,0,0),(2371,1,791,0,0,0),(2372,2,791,0,0,0),(2373,3,791,0,0,0),(2374,1,792,0,0,0),(2375,2,792,0,0,0),(2376,3,792,0,0,0),(2377,1,793,0,0,0),(2378,2,793,0,0,0),(2379,3,793,0,0,0),(2380,1,794,0,0,0),(2381,2,794,0,0,0),(2382,3,794,0,0,0),(2383,1,795,0,0,0),(2384,2,795,0,0,0),(2385,3,795,0,0,0),(2386,1,796,0,0,0),(2387,2,796,0,0,0),(2388,3,796,0,0,0),(2389,1,797,0,0,0),(2390,2,797,0,0,0),(2391,3,797,0,0,0),(2392,1,798,0,0,0),(2393,2,798,0,0,0),(2394,3,798,0,0,0),(2395,1,799,0,0,0),(2396,2,799,0,0,0),(2397,3,799,0,0,0),(2398,1,800,0,0,0),(2399,2,800,0,0,0),(2400,3,800,0,0,0),(2401,1,801,0,0,0),(2402,2,801,0,0,0),(2403,3,801,0,0,0),(2404,1,802,0,0,0),(2405,2,802,0,0,0),(2406,3,802,0,0,0),(2407,1,803,0,0,0),(2408,2,803,0,0,0),(2409,3,803,0,0,0),(2410,1,804,0,0,0),(2411,2,804,0,0,0),(2412,3,804,0,0,0),(2413,1,805,0,0,0),(2414,2,805,0,0,0),(2415,3,805,0,0,0),(2416,1,806,0,0,0),(2417,2,806,0,0,0),(2418,3,806,0,0,0),(2419,1,807,0,0,0),(2420,2,807,0,0,0),(2421,3,807,0,0,0),(2422,1,808,0,0,0),(2423,2,808,0,0,0),(2424,3,808,0,0,0),(2425,1,809,0,0,0),(2426,2,809,0,0,0),(2427,3,809,0,0,0),(2428,1,810,0,0,0),(2429,2,810,0,0,0),(2430,3,810,0,0,0),(2431,1,811,0,0,0),(2432,2,811,0,0,0),(2433,3,811,0,0,0),(2434,1,812,0,0,0),(2435,2,812,0,0,0),(2436,3,812,0,0,0),(2437,1,813,0,0,0),(2438,2,813,0,0,0),(2439,3,813,0,0,0),(2440,1,814,0,0,0),(2441,2,814,0,0,0),(2442,3,814,0,0,0),(2443,1,815,0,0,0),(2444,2,815,0,0,0),(2445,3,815,0,0,0),(2446,1,816,0,0,0),(2447,2,816,0,0,0),(2448,3,816,0,0,0),(2449,1,817,0,0,0),(2450,2,817,0,0,0),(2451,3,817,0,0,0),(2452,1,818,0,0,0),(2453,2,818,0,0,0),(2454,3,818,0,0,0),(2455,1,819,0,0,0),(2456,2,819,0,0,0),(2457,3,819,0,0,0),(2458,1,820,0,0,0),(2459,2,820,0,0,0),(2460,3,820,0,0,0),(2461,1,821,0,0,0),(2462,2,821,0,0,0),(2463,3,821,0,0,0),(2464,1,822,0,0,0),(2465,2,822,0,0,0),(2466,3,822,0,0,0),(2467,1,823,0,0,0),(2468,2,823,0,0,0),(2469,3,823,0,0,0),(2470,1,824,0,0,0),(2471,2,824,0,0,0),(2472,3,824,0,0,0),(2473,1,825,0,0,0),(2474,2,825,0,0,0),(2475,3,825,0,0,0),(2476,1,826,0,0,0),(2477,2,826,0,0,0),(2478,3,826,0,0,0),(2479,1,827,0,0,0),(2480,2,827,0,0,0),(2481,3,827,0,0,0),(2482,1,828,0,0,0),(2483,2,828,0,0,0),(2484,3,828,0,0,0),(2485,1,829,0,0,0),(2486,2,829,0,0,0),(2487,3,829,0,0,0),(2488,1,830,0,0,0),(2489,2,830,0,0,0),(2490,3,830,0,0,0),(2491,1,831,0,0,0),(2492,2,831,0,0,0),(2493,3,831,0,0,0),(2494,1,832,0,0,0),(2495,2,832,0,0,0),(2496,3,832,0,0,0),(2497,1,833,0,0,0),(2498,2,833,0,0,0),(2499,3,833,0,0,0),(2500,1,834,0,0,0),(2501,2,834,0,0,0),(2502,3,834,0,0,0),(2503,1,835,0,0,0),(2504,2,835,0,0,0),(2505,3,835,0,0,0),(2506,1,836,0,0,0),(2507,2,836,0,0,0),(2508,3,836,0,0,0),(2509,1,837,0,0,0),(2510,2,837,0,0,0),(2511,3,837,0,0,0),(2512,1,838,0,0,0),(2513,2,838,0,0,0),(2514,3,838,0,0,0),(2515,1,839,0,0,0),(2516,2,839,0,0,0),(2517,3,839,0,0,0),(2518,1,840,0,0,0),(2519,2,840,0,0,0),(2520,3,840,0,0,0),(2521,1,841,0,0,0),(2522,2,841,0,0,0),(2523,3,841,0,0,0),(2524,1,842,0,0,0),(2525,2,842,0,0,0),(2526,3,842,0,0,0),(2527,1,843,0,0,0),(2528,2,843,0,0,0),(2529,3,843,0,0,0),(2530,1,844,0,0,0),(2531,2,844,0,0,0),(2532,3,844,0,0,0),(2533,1,845,0,0,0),(2534,2,845,0,0,0),(2535,3,845,0,0,0),(2536,1,846,0,0,0),(2537,2,846,0,0,0),(2538,3,846,0,0,0),(2539,1,847,0,0,0),(2540,2,847,0,0,0),(2541,3,847,0,0,0),(2542,1,848,0,0,0),(2543,2,848,0,0,0),(2544,3,848,0,0,0),(2545,1,849,0,0,0),(2546,2,849,0,0,0),(2547,3,849,0,0,0),(2548,1,850,0,0,0),(2549,2,850,0,0,0),(2550,3,850,0,0,0),(2551,1,851,0,0,0),(2552,2,851,0,0,0),(2553,3,851,0,0,0),(2554,1,852,0,0,0),(2555,2,852,0,0,0),(2556,3,852,0,0,0),(2557,1,853,0,0,0),(2558,2,853,0,0,0),(2559,3,853,0,0,0),(2560,1,854,0,0,0),(2561,2,854,0,0,0),(2562,3,854,0,0,0),(2563,1,855,0,0,0),(2564,2,855,0,0,0),(2565,3,855,0,0,0),(2566,1,856,0,0,0),(2567,2,856,0,0,0),(2568,3,856,0,0,0),(2569,1,857,0,0,0),(2570,2,857,0,0,0),(2571,3,857,0,0,0),(2572,1,858,0,0,0),(2573,2,858,0,0,0),(2574,3,858,0,0,0),(2575,1,859,0,0,0),(2576,2,859,0,0,0),(2577,3,859,0,0,0),(2578,1,860,0,0,0),(2579,2,860,0,0,0),(2580,3,860,0,0,0),(2581,1,861,0,0,0),(2582,2,861,0,0,0),(2583,3,861,0,0,0),(2584,1,862,0,0,0),(2585,2,862,0,0,0),(2586,3,862,0,0,0),(2587,1,863,0,0,0),(2588,2,863,0,0,0),(2589,3,863,0,0,0),(2590,1,864,0,0,0),(2591,2,864,0,0,0),(2592,3,864,0,0,0),(2593,1,865,0,0,0),(2594,2,865,0,0,0),(2595,3,865,0,0,0),(2596,1,866,0,0,0),(2597,2,866,0,0,0),(2598,3,866,0,0,0),(2599,1,867,0,0,0),(2600,2,867,0,0,0),(2601,3,867,0,0,0),(2602,1,868,0,0,0),(2603,2,868,0,0,0),(2604,3,868,0,0,0),(2605,1,869,0,0,0),(2606,2,869,0,0,0),(2607,3,869,0,0,0),(2608,1,870,0,0,0),(2609,2,870,0,0,0),(2610,3,870,0,0,0),(2611,1,871,0,0,0),(2612,2,871,0,0,0),(2613,3,871,0,0,0),(2614,1,872,0,0,0),(2615,2,872,0,0,0),(2616,3,872,0,0,0),(2617,1,873,0,0,0),(2618,2,873,0,0,0),(2619,3,873,0,0,0),(2620,1,874,0,0,0),(2621,2,874,0,0,0),(2622,3,874,0,0,0),(2623,1,875,0,0,0),(2624,2,875,0,0,0),(2625,3,875,0,0,0),(2626,1,876,0,0,0),(2627,2,876,0,0,0),(2628,3,876,0,0,0),(2629,1,877,0,0,0),(2630,2,877,0,0,0),(2631,3,877,0,0,0),(2632,1,878,0,0,0),(2633,2,878,0,0,0),(2634,3,878,0,0,0),(2635,1,879,0,0,0),(2636,2,879,0,0,0),(2637,3,879,0,0,0),(2638,1,880,0,0,0),(2639,2,880,0,0,0),(2640,3,880,0,0,0),(2641,1,881,0,0,0),(2642,2,881,0,0,0),(2643,3,881,0,0,0),(2644,1,882,0,0,0),(2645,2,882,0,0,0),(2646,3,882,0,0,0),(2647,1,883,0,0,0),(2648,2,883,0,0,0),(2649,3,883,0,0,0),(2650,1,884,0,0,0),(2651,2,884,0,0,0),(2652,3,884,0,0,0),(2653,1,885,0,0,0),(2654,2,885,0,0,0),(2655,3,885,0,0,0),(2656,1,886,0,0,0),(2657,2,886,0,0,0),(2658,3,886,0,0,0),(2659,1,887,0,0,0),(2660,2,887,0,0,0),(2661,3,887,0,0,0),(2662,1,888,0,0,0),(2663,2,888,0,0,0),(2664,3,888,0,0,0),(2665,1,889,0,0,0),(2666,2,889,0,0,0),(2667,3,889,0,0,0),(2668,1,890,0,0,0),(2669,2,890,0,0,0),(2670,3,890,0,0,0),(2671,1,891,0,0,0),(2672,2,891,0,0,0),(2673,3,891,0,0,0),(2674,1,892,0,0,0),(2675,2,892,0,0,0),(2676,3,892,0,0,0),(2677,1,893,0,0,0),(2678,2,893,0,0,0),(2679,3,893,0,0,0),(2680,1,894,0,0,0),(2681,2,894,0,0,0),(2682,3,894,0,0,0),(2683,1,895,0,0,0),(2684,2,895,0,0,0),(2685,3,895,0,0,0),(2686,1,896,0,0,0),(2687,2,896,0,0,0),(2688,3,896,0,0,0),(2689,1,897,0,0,0),(2690,2,897,0,0,0),(2691,3,897,0,0,0),(2692,1,898,0,0,0),(2693,2,898,0,0,0),(2694,3,898,0,0,0),(2695,1,899,0,0,0),(2696,2,899,0,0,0),(2697,3,899,0,0,0),(2698,1,900,0,0,0),(2699,2,900,0,0,0),(2700,3,900,0,0,0),(2701,1,901,0,0,0),(2702,2,901,0,0,0),(2703,3,901,0,0,0),(2704,1,902,0,0,0),(2705,2,902,0,0,0),(2706,3,902,0,0,0),(2707,1,903,0,0,0),(2708,2,903,0,0,0),(2709,3,903,0,0,0),(2710,1,904,0,0,0),(2711,2,904,0,0,0),(2712,3,904,0,0,0),(2713,1,905,0,0,0),(2714,2,905,0,0,0),(2715,3,905,0,0,0),(2716,1,906,0,0,0),(2717,2,906,0,0,0),(2718,3,906,0,0,0),(2719,1,907,0,0,0),(2720,2,907,0,0,0),(2721,3,907,0,0,0),(2722,1,908,0,0,0),(2723,2,908,0,0,0),(2724,3,908,0,0,0),(2725,1,909,0,0,0),(2726,2,909,0,0,0),(2727,3,909,0,0,0),(2728,1,910,0,0,0),(2729,2,910,0,0,0),(2730,3,910,0,0,0),(2731,1,911,0,0,0),(2732,2,911,0,0,0),(2733,3,911,0,0,0),(2734,1,912,0,0,0),(2735,2,912,0,0,0),(2736,3,912,0,0,0),(2737,1,913,0,0,0),(2738,2,913,0,0,0),(2739,3,913,0,0,0),(2740,1,914,0,0,0),(2741,2,914,0,0,0),(2742,3,914,0,0,0),(2743,1,915,0,0,0),(2744,2,915,0,0,0),(2745,3,915,0,0,0),(2746,1,916,0,0,0),(2747,2,916,0,0,0),(2748,3,916,0,0,0),(2749,1,917,0,0,0),(2750,2,917,0,0,0),(2751,3,917,0,0,0),(2752,1,918,0,0,0),(2753,2,918,0,0,0),(2754,3,918,0,0,0),(2755,1,919,0,0,0),(2756,2,919,0,0,0),(2757,3,919,0,0,0),(2758,1,920,0,0,0),(2759,2,920,0,0,0),(2760,3,920,0,0,0),(2761,1,921,0,0,0),(2762,2,921,0,0,0),(2763,3,921,0,0,0),(2764,1,922,0,0,0),(2765,2,922,0,0,0),(2766,3,922,0,0,0),(2767,1,923,0,0,0),(2768,2,923,0,0,0),(2769,3,923,0,0,0),(2770,1,924,0,0,0),(2771,2,924,0,0,0),(2772,3,924,0,0,0),(2773,1,925,0,0,0),(2774,2,925,0,0,0),(2775,3,925,0,0,0),(2776,1,926,0,0,0),(2777,2,926,0,0,0),(2778,3,926,0,0,0),(2779,1,927,0,0,0),(2780,2,927,0,0,0),(2781,3,927,0,0,0),(2782,1,928,0,0,0),(2783,2,928,0,0,0),(2784,3,928,0,0,0),(2785,1,929,0,0,0),(2786,2,929,0,0,0),(2787,3,929,0,0,0),(2788,1,930,0,0,0),(2789,2,930,0,0,0),(2790,3,930,0,0,0),(2791,1,931,0,0,0),(2792,2,931,0,0,0),(2793,3,931,0,0,0),(2794,1,932,0,0,0),(2795,2,932,0,0,0),(2796,3,932,0,0,0),(2797,1,933,0,0,0),(2798,2,933,0,0,0),(2799,3,933,0,0,0),(2800,1,934,0,0,0),(2801,2,934,0,0,0),(2802,3,934,0,0,0),(2803,1,935,0,0,0),(2804,2,935,0,0,0),(2805,3,935,0,0,0),(2806,1,936,0,0,0),(2807,2,936,0,0,0),(2808,3,936,0,0,0),(2809,1,937,0,0,0),(2810,2,937,0,0,0),(2811,3,937,0,0,0),(2812,1,938,0,0,0),(2813,2,938,0,0,0),(2814,3,938,0,0,0),(2815,1,939,0,0,0),(2816,2,939,0,0,0),(2817,3,939,0,0,0),(2818,1,940,0,0,0),(2819,2,940,0,0,0),(2820,3,940,0,0,0),(2821,1,941,0,0,0),(2822,2,941,0,0,0),(2823,3,941,0,0,0),(2824,1,942,0,0,0),(2825,2,942,0,0,0),(2826,3,942,0,0,0),(2827,1,943,0,0,0),(2828,2,943,0,0,0),(2829,3,943,0,0,0),(2830,1,944,0,0,0),(2831,2,944,0,0,0),(2832,3,944,0,0,0),(2833,1,945,0,0,0),(2834,2,945,0,0,0),(2835,3,945,0,0,0),(2836,1,946,0,0,0),(2837,2,946,0,0,0),(2838,3,946,0,0,0),(2839,1,947,0,0,0),(2840,2,947,0,0,0),(2841,3,947,0,0,0),(2842,1,948,0,0,0),(2843,2,948,0,0,0),(2844,3,948,0,0,0),(2845,1,949,0,0,0),(2846,2,949,0,0,0),(2847,3,949,0,0,0),(2848,1,950,0,0,0),(2849,2,950,0,0,0),(2850,3,950,0,0,0),(2851,1,951,0,0,0),(2852,2,951,0,0,0),(2853,3,951,0,0,0),(2854,1,952,0,0,0),(2855,2,952,0,0,0),(2856,3,952,0,0,0),(2857,1,953,0,0,0),(2858,2,953,0,0,0),(2859,3,953,0,0,0),(2860,1,954,0,0,0),(2861,2,954,0,0,0),(2862,3,954,0,0,0),(2863,1,955,0,0,0),(2864,2,955,0,0,0),(2865,3,955,0,0,0),(2866,1,956,0,0,0),(2867,2,956,0,0,0),(2868,3,956,0,0,0),(2869,1,957,0,0,0),(2870,2,957,0,0,0),(2871,3,957,0,0,0),(2872,1,958,0,0,0),(2873,2,958,0,0,0),(2874,3,958,0,0,0),(2875,1,959,0,0,0),(2876,2,959,0,0,0),(2877,3,959,0,0,0),(2878,1,960,0,0,0),(2879,2,960,0,0,0),(2880,3,960,0,0,0),(2881,1,961,0,0,0),(2882,2,961,0,0,0),(2883,3,961,0,0,0),(2884,1,962,0,0,0),(2885,2,962,0,0,0),(2886,3,962,0,0,0),(2887,1,963,0,0,0),(2888,2,963,0,0,0),(2889,3,963,0,0,0),(2890,1,964,0,0,0),(2891,2,964,0,0,0),(2892,3,964,0,0,0),(2893,1,965,0,0,0),(2894,2,965,0,0,0),(2895,3,965,0,0,0),(2896,1,966,0,0,0),(2897,2,966,0,0,0),(2898,3,966,0,0,0),(2899,1,967,0,0,0),(2900,2,967,0,0,0),(2901,3,967,0,0,0),(2902,1,968,0,0,0),(2903,2,968,0,0,0),(2904,3,968,0,0,0),(2905,1,969,0,0,0),(2906,2,969,0,0,0),(2907,3,969,0,0,0),(2908,1,970,0,0,0),(2909,2,970,0,0,0),(2910,3,970,0,0,0),(2911,1,971,0,0,0),(2912,2,971,0,0,0),(2913,3,971,0,0,0),(2914,1,972,0,0,0),(2915,2,972,0,0,0),(2916,3,972,0,0,0),(2917,1,973,0,0,0),(2918,2,973,0,0,0),(2919,3,973,0,0,0),(2920,1,974,0,0,0),(2921,2,974,0,0,0),(2922,3,974,0,0,0),(2923,1,975,0,0,0),(2924,2,975,0,0,0),(2925,3,975,0,0,0),(2926,1,976,0,0,0),(2927,2,976,0,0,0),(2928,3,976,0,0,0),(2929,1,977,0,0,0),(2930,2,977,0,0,0),(2931,3,977,0,0,0),(2932,1,978,0,0,0),(2933,2,978,0,0,0),(2934,3,978,0,0,0),(2935,1,979,0,0,0),(2936,2,979,0,0,0),(2937,3,979,0,0,0),(2938,1,980,0,0,0),(2939,2,980,0,0,0),(2940,3,980,0,0,0),(2941,1,981,0,0,0),(2942,2,981,0,0,0),(2943,3,981,0,0,0),(2944,1,982,0,0,0),(2945,2,982,0,0,0),(2946,3,982,0,0,0),(2947,1,983,0,0,0),(2948,2,983,0,0,0),(2949,3,983,0,0,0),(2950,1,984,0,0,0),(2951,2,984,0,0,0),(2952,3,984,0,0,0),(2953,1,985,0,0,0),(2954,2,985,0,0,0),(2955,3,985,0,0,0),(2956,1,986,0,0,0),(2957,2,986,0,0,0),(2958,3,986,0,0,0),(2959,1,987,0,0,0),(2960,2,987,0,0,0),(2961,3,987,0,0,0),(2962,1,988,0,0,0),(2963,2,988,0,0,0),(2964,3,988,0,0,0),(2965,1,989,0,0,0),(2966,2,989,0,0,0),(2967,3,989,0,0,0),(2968,1,990,0,0,0),(2969,2,990,0,0,0),(2970,3,990,0,0,0),(2971,1,991,0,0,0),(2972,2,991,0,0,0),(2973,3,991,0,0,0),(2974,1,992,0,0,0),(2975,2,992,0,0,0),(2976,3,992,0,0,0),(2977,1,993,0,0,0),(2978,2,993,0,0,0),(2979,3,993,0,0,0),(2980,1,994,0,0,0),(2981,2,994,0,0,0),(2982,3,994,0,0,0),(2983,1,995,0,0,0),(2984,2,995,0,0,0),(2985,3,995,0,0,0),(2986,1,996,0,0,0),(2987,2,996,0,0,0),(2988,3,996,0,0,0),(2989,1,997,0,0,0),(2990,2,997,0,0,0),(2991,3,997,0,0,0),(2992,1,998,0,0,0),(2993,2,998,0,0,0),(2994,3,998,0,0,0),(2995,1,999,0,0,0),(2996,2,999,0,0,0),(2997,3,999,0,0,0),(2998,1,1000,0,0,0),(2999,2,1000,0,0,0),(3000,3,1000,0,0,0),(3001,1,1001,0,0,0),(3002,2,1001,0,0,0),(3003,3,1001,0,0,0),(3004,1,1002,0,0,0),(3005,2,1002,0,0,0),(3006,3,1002,0,0,0),(3007,1,1003,0,0,0),(3008,2,1003,0,0,0),(3009,3,1003,0,0,0),(3010,1,1004,0,0,0),(3011,2,1004,0,0,0),(3012,3,1004,0,0,0),(3013,1,1005,0,0,0),(3014,2,1005,0,0,0),(3015,3,1005,0,0,0),(3016,1,1006,0,0,0),(3017,2,1006,0,0,0),(3018,3,1006,0,0,0),(3019,1,1007,0,0,0),(3020,2,1007,0,0,0),(3021,3,1007,0,0,0),(3022,1,1008,0,0,0),(3023,2,1008,0,0,0),(3024,3,1008,0,0,0),(3025,1,1009,0,0,0),(3026,2,1009,0,0,0),(3027,3,1009,0,0,0),(3028,1,1010,0,0,0),(3029,2,1010,0,0,0),(3030,3,1010,0,0,0),(3031,1,1011,0,0,0),(3032,2,1011,0,0,0),(3033,3,1011,0,0,0),(3034,1,1012,0,0,0),(3035,2,1012,0,0,0),(3036,3,1012,0,0,0),(3037,1,1013,0,0,0),(3038,2,1013,0,0,0),(3039,3,1013,0,0,0),(3040,1,1014,0,0,0),(3041,2,1014,0,0,0),(3042,3,1014,0,0,0),(3043,1,1015,0,0,0),(3044,2,1015,0,0,0),(3045,3,1015,0,0,0),(3046,1,1016,0,0,0),(3047,2,1016,0,0,0),(3048,3,1016,0,0,0),(3049,1,1017,0,0,0),(3050,2,1017,0,0,0),(3051,3,1017,0,0,0),(3052,1,1018,0,0,0),(3053,2,1018,0,0,0),(3054,3,1018,0,0,0),(3055,1,1019,0,0,0),(3056,2,1019,0,0,0),(3057,3,1019,0,0,0),(3058,1,1020,0,0,0),(3059,2,1020,0,0,0),(3060,3,1020,0,0,0),(3061,1,1021,0,0,0),(3062,2,1021,0,0,0),(3063,3,1021,0,0,0),(3064,1,1022,0,0,0),(3065,2,1022,0,0,0),(3066,3,1022,0,0,0),(3067,1,1023,0,0,0),(3068,2,1023,0,0,0),(3069,3,1023,0,0,0),(3070,1,1024,0,0,0),(3071,2,1024,0,0,0),(3072,3,1024,0,0,0),(3073,1,1025,0,0,0),(3074,2,1025,0,0,0),(3075,3,1025,0,0,0),(3076,1,1026,0,0,0),(3077,2,1026,0,0,0),(3078,3,1026,0,0,0),(3079,1,1027,0,0,0),(3080,2,1027,0,0,0),(3081,3,1027,0,0,0),(3082,1,1028,0,0,0),(3083,2,1028,0,0,0),(3084,3,1028,0,0,0),(3085,1,1029,0,0,0),(3086,2,1029,0,0,0),(3087,3,1029,0,0,0),(3088,1,1030,0,0,0),(3089,2,1030,0,0,0),(3090,3,1030,0,0,0),(3091,1,1031,0,0,0),(3092,2,1031,0,0,0),(3093,3,1031,0,0,0),(3094,1,1032,0,0,0),(3095,2,1032,0,0,0),(3096,3,1032,0,0,0),(3097,1,1033,0,0,0),(3098,2,1033,0,0,0),(3099,3,1033,0,0,0),(3100,1,1034,0,0,0),(3101,2,1034,0,0,0),(3102,3,1034,0,0,0),(3103,1,1035,0,0,0),(3104,2,1035,0,0,0),(3105,3,1035,0,0,0),(3106,1,1036,0,0,0),(3107,2,1036,0,0,0),(3108,3,1036,0,0,0),(3109,1,1037,0,0,0),(3110,2,1037,0,0,0),(3111,3,1037,0,0,0),(3112,1,1038,0,0,0),(3113,2,1038,0,0,0),(3114,3,1038,0,0,0),(3115,1,1039,0,0,0),(3116,2,1039,0,0,0),(3117,3,1039,0,0,0),(3118,1,1040,0,0,0),(3119,2,1040,0,0,0),(3120,3,1040,0,0,0),(3121,1,1041,0,0,0),(3122,2,1041,0,0,0),(3123,3,1041,0,0,0),(3124,1,1042,0,0,0),(3125,2,1042,0,0,0),(3126,3,1042,0,0,0),(3127,1,1043,0,0,0),(3128,2,1043,0,0,0),(3129,3,1043,0,0,0),(3130,1,1044,0,0,0),(3131,2,1044,0,0,0),(3132,3,1044,0,0,0),(3133,1,1045,0,0,0),(3134,2,1045,0,0,0),(3135,3,1045,0,0,0),(3136,1,1046,0,0,0),(3137,2,1046,0,0,0),(3138,3,1046,0,0,0),(3139,1,1047,0,0,0),(3140,2,1047,0,0,0),(3141,3,1047,0,0,0),(3142,1,1048,0,0,0),(3143,2,1048,0,0,0),(3144,3,1048,0,0,0),(3145,1,1049,0,0,0),(3146,2,1049,0,0,0),(3147,3,1049,0,0,0),(3148,1,1050,0,0,0),(3149,2,1050,0,0,0),(3150,3,1050,0,0,0),(3151,1,1051,0,0,0),(3152,2,1051,0,0,0),(3153,3,1051,0,0,0),(3154,1,1052,0,0,0),(3155,2,1052,0,0,0),(3156,3,1052,0,0,0),(3157,1,1053,0,0,0),(3158,2,1053,0,0,0),(3159,3,1053,0,0,0),(3160,1,1054,0,0,0),(3161,2,1054,0,0,0),(3162,3,1054,0,0,0),(3163,1,1055,0,0,0),(3164,2,1055,0,0,0),(3165,3,1055,0,0,0),(3166,1,1056,0,0,0),(3167,2,1056,0,0,0),(3168,3,1056,0,0,0),(3169,1,1057,0,0,0),(3170,2,1057,0,0,0),(3171,3,1057,0,0,0),(3172,1,1058,0,0,0),(3173,2,1058,0,0,0),(3174,3,1058,0,0,0),(3175,1,1059,0,0,0),(3176,2,1059,0,0,0),(3177,3,1059,0,0,0),(3178,1,1060,0,0,0),(3179,2,1060,0,0,0),(3180,3,1060,0,0,0),(3181,1,1061,0,0,0),(3182,2,1061,0,0,0),(3183,3,1061,0,0,0),(3184,1,1062,0,0,0),(3185,2,1062,0,0,0),(3186,3,1062,0,0,0),(3187,1,1063,0,0,0),(3188,2,1063,0,0,0),(3189,3,1063,0,0,0),(3190,1,1064,0,0,0),(3191,2,1064,0,0,0),(3192,3,1064,0,0,0),(3193,1,1065,0,0,0),(3194,2,1065,0,0,0),(3195,3,1065,0,0,0),(3196,1,1066,0,0,0),(3197,2,1066,0,0,0),(3198,3,1066,0,0,0),(3199,1,1067,0,0,0),(3200,2,1067,0,0,0),(3201,3,1067,0,0,0),(3202,1,1068,0,0,0),(3203,2,1068,0,0,0),(3204,3,1068,0,0,0),(3205,1,1069,0,0,0),(3206,2,1069,0,0,0),(3207,3,1069,0,0,0),(3208,1,1070,0,0,0),(3209,2,1070,0,0,0),(3210,3,1070,0,0,0),(3211,1,1071,0,0,0),(3212,2,1071,0,0,0),(3213,3,1071,0,0,0),(3214,1,1072,0,0,0),(3215,2,1072,0,0,0),(3216,3,1072,0,0,0),(3217,1,1073,0,0,0),(3218,2,1073,0,0,0),(3219,3,1073,0,0,0),(3220,1,1074,0,0,0),(3221,2,1074,0,0,0),(3222,3,1074,0,0,0),(3223,1,1075,0,0,0),(3224,2,1075,0,0,0),(3225,3,1075,0,0,0),(3226,1,1076,0,0,0),(3227,2,1076,0,0,0),(3228,3,1076,0,0,0),(3229,1,1077,0,0,0),(3230,2,1077,0,0,0),(3231,3,1077,0,0,0),(3232,1,1078,0,0,0),(3233,2,1078,0,0,0),(3234,3,1078,0,0,0),(3235,1,1079,0,0,0),(3236,2,1079,0,0,0),(3237,3,1079,0,0,0),(3238,1,1080,0,0,0),(3239,2,1080,0,0,0),(3240,3,1080,0,0,0),(3241,1,1081,0,0,0),(3242,2,1081,0,0,0),(3243,3,1081,0,0,0),(3244,1,1082,0,0,0),(3245,2,1082,0,0,0),(3246,3,1082,0,0,0),(3247,1,1083,0,0,0),(3248,2,1083,0,0,0),(3249,3,1083,0,0,0),(3250,1,1084,0,0,0),(3251,2,1084,0,0,0),(3252,3,1084,0,0,0),(3253,1,1085,0,0,0),(3254,2,1085,0,0,0),(3255,3,1085,0,0,0),(3256,1,1086,0,0,0),(3257,2,1086,0,0,0),(3258,3,1086,0,0,0),(3259,1,1087,0,0,0),(3260,2,1087,0,0,0),(3261,3,1087,0,0,0),(3262,1,1088,0,0,0),(3263,2,1088,0,0,0),(3264,3,1088,0,0,0),(3265,1,1089,0,0,0),(3266,2,1089,0,0,0),(3267,3,1089,0,0,0),(3268,1,1090,0,0,0),(3269,2,1090,0,0,0),(3270,3,1090,0,0,0),(3271,1,1091,0,0,0),(3272,2,1091,0,0,0),(3273,3,1091,0,0,0),(3274,1,1092,0,0,0),(3275,2,1092,0,0,0),(3276,3,1092,0,0,0),(3277,1,1093,0,0,0),(3278,2,1093,0,0,0),(3279,3,1093,0,0,0),(3280,1,1094,0,0,0),(3281,2,1094,0,0,0),(3282,3,1094,0,0,0),(3283,1,1095,0,0,0),(3284,2,1095,0,0,0),(3285,3,1095,0,0,0),(3286,1,1096,0,0,0),(3287,2,1096,0,0,0),(3288,3,1096,0,0,0),(3289,1,1097,0,0,0),(3290,2,1097,0,0,0),(3291,3,1097,0,0,0),(3292,1,1098,0,0,0),(3293,2,1098,0,0,0),(3294,3,1098,0,0,0),(3295,1,1099,0,0,0),(3296,2,1099,0,0,0),(3297,3,1099,0,0,0),(3298,1,1100,0,0,0),(3299,2,1100,0,0,0),(3300,3,1100,0,0,0),(3301,1,1101,0,0,0),(3302,2,1101,0,0,0),(3303,3,1101,0,0,0),(3304,1,1102,0,0,0),(3305,2,1102,0,0,0),(3306,3,1102,0,0,0),(3307,1,1103,0,0,0),(3308,2,1103,0,0,0),(3309,3,1103,0,0,0),(3310,1,1104,0,0,0),(3311,2,1104,0,0,0),(3312,3,1104,0,0,0),(3313,1,1105,0,0,0),(3314,2,1105,0,0,0),(3315,3,1105,0,0,0),(3316,1,1106,0,0,0),(3317,2,1106,0,0,0),(3318,3,1106,0,0,0),(3319,1,1107,0,0,0),(3320,2,1107,0,0,0),(3321,3,1107,0,0,0),(3322,1,1108,0,0,0),(3323,2,1108,0,0,0),(3324,3,1108,0,0,0),(3325,1,1109,0,0,0),(3326,2,1109,0,0,0),(3327,3,1109,0,0,0),(3328,1,1110,0,0,0),(3329,2,1110,0,0,0),(3330,3,1110,0,0,0),(3331,1,1111,0,0,0),(3332,2,1111,0,0,0),(3333,3,1111,0,0,0),(3334,1,1112,0,0,0),(3335,2,1112,0,0,0),(3336,3,1112,0,0,0),(3337,1,1113,0,0,0),(3338,2,1113,0,0,0),(3339,3,1113,0,0,0),(3340,1,1114,0,0,0),(3341,2,1114,0,0,0),(3342,3,1114,0,0,0),(3343,1,1115,0,0,0),(3344,2,1115,0,0,0),(3345,3,1115,0,0,0),(3346,1,1116,0,0,0),(3347,2,1116,0,0,0),(3348,3,1116,0,0,0),(3349,1,1117,0,0,0),(3350,2,1117,0,0,0),(3351,3,1117,0,0,0),(3352,1,1118,0,0,0),(3353,2,1118,0,0,0),(3354,3,1118,0,0,0),(3355,1,1119,0,0,0),(3356,2,1119,0,0,0),(3357,3,1119,0,0,0),(3358,1,1120,0,0,0),(3359,2,1120,0,0,0),(3360,3,1120,0,0,0),(3361,1,1121,0,0,0),(3362,2,1121,0,0,0),(3363,3,1121,0,0,0),(3364,1,1122,0,0,0),(3365,2,1122,0,0,0),(3366,3,1122,0,0,0),(3367,1,1123,0,0,0),(3368,2,1123,0,0,0),(3369,3,1123,0,0,0),(3370,1,1124,0,0,0),(3371,2,1124,0,0,0),(3372,3,1124,0,0,0),(3373,1,1125,0,0,0),(3374,2,1125,0,0,0),(3375,3,1125,0,0,0),(3376,1,1126,0,0,0),(3377,2,1126,0,0,0),(3378,3,1126,0,0,0),(3379,1,1127,0,0,0),(3380,2,1127,0,0,0),(3381,3,1127,0,0,0),(3382,1,1128,0,0,0),(3383,2,1128,0,0,0),(3384,3,1128,0,0,0),(3385,1,1129,0,0,0),(3386,2,1129,0,0,0),(3387,3,1129,0,0,0),(3388,1,1130,0,0,0),(3389,2,1130,0,0,0),(3390,3,1130,0,0,0),(3391,1,1131,0,0,0),(3392,2,1131,0,0,0),(3393,3,1131,0,0,0),(3394,1,1132,0,0,0),(3395,2,1132,0,0,0),(3396,3,1132,0,0,0),(3397,1,1133,0,0,0),(3398,2,1133,0,0,0),(3399,3,1133,0,0,0),(3400,1,1134,0,0,0),(3401,2,1134,0,0,0),(3402,3,1134,0,0,0),(3403,1,1135,0,0,0),(3404,2,1135,0,0,0),(3405,3,1135,0,0,0),(3406,1,1136,0,0,0),(3407,2,1136,0,0,0),(3408,3,1136,0,0,0),(3409,1,1137,0,0,0),(3410,2,1137,0,0,0),(3411,3,1137,0,0,0),(3412,1,1138,0,0,0),(3413,2,1138,0,0,0),(3414,3,1138,0,0,0),(3415,1,1139,0,0,0),(3416,2,1139,0,0,0),(3417,3,1139,0,0,0),(3418,1,1140,0,0,0),(3419,2,1140,0,0,0),(3420,3,1140,0,0,0),(3421,1,1141,0,0,0),(3422,2,1141,0,0,0),(3423,3,1141,0,0,0),(3424,1,1142,0,0,0),(3425,2,1142,0,0,0),(3426,3,1142,0,0,0),(3427,1,1143,0,0,0),(3428,2,1143,0,0,0),(3429,3,1143,0,0,0),(3430,1,1144,0,0,0),(3431,2,1144,0,0,0),(3432,3,1144,0,0,0),(3433,1,1145,0,0,0),(3434,2,1145,0,0,0),(3435,3,1145,0,0,0),(3436,1,1146,0,0,0),(3437,2,1146,0,0,0),(3438,3,1146,0,0,0),(3439,1,1147,0,0,0),(3440,2,1147,0,0,0),(3441,3,1147,0,0,0),(3442,1,1148,0,0,0),(3443,2,1148,0,0,0),(3444,3,1148,0,0,0),(3445,1,1149,0,0,0),(3446,2,1149,0,0,0),(3447,3,1149,0,0,0),(3448,1,1150,0,0,0),(3449,2,1150,0,0,0),(3450,3,1150,0,0,0),(3451,1,1151,0,0,0),(3452,2,1151,0,0,0),(3453,3,1151,0,0,0),(3454,1,1152,0,0,0),(3455,2,1152,0,0,0),(3456,3,1152,0,0,0),(3457,1,1153,0,0,0),(3458,2,1153,0,0,0),(3459,3,1153,0,0,0),(3460,1,1154,0,0,0),(3461,2,1154,0,0,0),(3462,3,1154,0,0,0),(3463,1,1155,0,0,0),(3464,2,1155,0,0,0),(3465,3,1155,0,0,0),(3466,1,1156,0,0,0),(3467,2,1156,0,0,0),(3468,3,1156,0,0,0),(3469,1,1157,0,0,0),(3470,2,1157,0,0,0),(3471,3,1157,0,0,0),(3472,1,1158,0,0,0),(3473,2,1158,0,0,0),(3474,3,1158,0,0,0),(3475,1,1159,0,0,0),(3476,2,1159,0,0,0),(3477,3,1159,0,0,0),(3478,1,1160,0,0,0),(3479,2,1160,0,0,0),(3480,3,1160,0,0,0),(3481,1,1161,0,0,0),(3482,2,1161,0,0,0),(3483,3,1161,0,0,0),(3484,1,1162,0,0,0),(3485,2,1162,0,0,0),(3486,3,1162,0,0,0),(3487,1,1163,0,0,0),(3488,2,1163,0,0,0),(3489,3,1163,0,0,0),(3490,1,1164,0,0,0),(3491,2,1164,0,0,0),(3492,3,1164,0,0,0),(3493,1,1165,0,0,0),(3494,2,1165,0,0,0),(3495,3,1165,0,0,0),(3496,1,1166,0,0,0),(3497,2,1166,0,0,0),(3498,3,1166,0,0,0),(3499,1,1167,0,0,0),(3500,2,1167,0,0,0),(3501,3,1167,0,0,0),(3502,1,1168,0,0,0),(3503,2,1168,0,0,0),(3504,3,1168,0,0,0),(3505,1,1169,0,0,0),(3506,2,1169,0,0,0),(3507,3,1169,0,0,0),(3508,1,1170,0,0,0),(3509,2,1170,0,0,0),(3510,3,1170,0,0,0),(3511,1,1171,0,0,0),(3512,2,1171,0,0,0),(3513,3,1171,0,0,0),(3514,1,1172,0,0,0),(3515,2,1172,0,0,0),(3516,3,1172,0,0,0),(3517,1,1173,0,0,0),(3518,2,1173,0,0,0),(3519,3,1173,0,0,0),(3520,1,1174,0,0,0),(3521,2,1174,0,0,0),(3522,3,1174,0,0,0),(3523,1,1175,0,0,0),(3524,2,1175,0,0,0),(3525,3,1175,0,0,0),(3526,1,1176,0,0,0),(3527,2,1176,0,0,0),(3528,3,1176,0,0,0),(3529,1,1177,0,0,0),(3530,2,1177,0,0,0),(3531,3,1177,0,0,0),(3532,1,1178,0,0,0),(3533,2,1178,0,0,0),(3534,3,1178,0,0,0),(3535,1,1179,0,0,0),(3536,2,1179,0,0,0),(3537,3,1179,0,0,0),(3538,1,1180,0,0,0),(3539,2,1180,0,0,0),(3540,3,1180,0,0,0),(3541,1,1181,0,0,0),(3542,2,1181,0,0,0),(3543,3,1181,0,0,0),(3544,1,1182,0,0,0),(3545,2,1182,0,0,0),(3546,3,1182,0,0,0),(3547,1,1183,0,0,0),(3548,2,1183,0,0,0),(3549,3,1183,0,0,0),(3550,1,1184,0,0,0),(3551,2,1184,0,0,0),(3552,3,1184,0,0,0),(3553,1,1185,0,0,0),(3554,2,1185,0,0,0),(3555,3,1185,0,0,0),(3556,1,1186,0,0,0),(3557,2,1186,0,0,0),(3558,3,1186,0,0,0),(3559,1,1187,0,0,0),(3560,2,1187,0,0,0),(3561,3,1187,0,0,0),(3562,1,1188,0,0,0),(3563,2,1188,0,0,0),(3564,3,1188,0,0,0),(3565,1,1189,0,0,0),(3566,2,1189,0,0,0),(3567,3,1189,0,0,0),(3568,1,1190,0,0,0),(3569,2,1190,0,0,0),(3570,3,1190,0,0,0),(3571,1,1191,0,0,0),(3572,2,1191,0,0,0),(3573,3,1191,0,0,0),(3574,1,1192,0,0,0),(3575,2,1192,0,0,0),(3576,3,1192,0,0,0),(3577,1,1193,0,0,0),(3578,2,1193,0,0,0),(3579,3,1193,0,0,0),(3580,1,1194,0,0,0),(3581,2,1194,0,0,0),(3582,3,1194,0,0,0),(3583,1,1195,0,0,0),(3584,2,1195,0,0,0),(3585,3,1195,0,0,0),(3586,1,1196,0,0,0),(3587,2,1196,0,0,0),(3588,3,1196,0,0,0),(3589,1,1197,0,0,0),(3590,2,1197,0,0,0),(3591,3,1197,0,0,0),(3592,1,1198,0,0,0),(3593,2,1198,0,0,0),(3594,3,1198,0,0,0),(3595,1,1199,0,0,0),(3596,2,1199,0,0,0),(3597,3,1199,0,0,0),(3598,1,1200,0,0,0),(3599,2,1200,0,0,0),(3600,3,1200,0,0,0),(3601,1,1201,0,0,0),(3602,2,1201,0,0,0),(3603,3,1201,0,0,0),(3604,1,1202,0,0,0),(3605,2,1202,0,0,0),(3606,3,1202,0,0,0),(3607,1,1203,0,0,0),(3608,2,1203,0,0,0),(3609,3,1203,0,0,0),(3610,1,1204,0,0,0),(3611,2,1204,0,0,0),(3612,3,1204,0,0,0),(3613,1,1205,0,0,0),(3614,2,1205,0,0,0),(3615,3,1205,0,0,0),(3616,1,1206,0,0,0),(3617,2,1206,0,0,0),(3618,3,1206,0,0,0),(3619,1,1207,0,0,0),(3620,2,1207,0,0,0),(3621,3,1207,0,0,0),(3622,1,1208,0,0,0),(3623,2,1208,0,0,0),(3624,3,1208,0,0,0),(3625,1,1209,0,0,0),(3626,2,1209,0,0,0),(3627,3,1209,0,0,0),(3628,1,1210,0,0,0),(3629,2,1210,0,0,0),(3630,3,1210,0,0,0),(3631,1,1211,0,0,0),(3632,2,1211,0,0,0),(3633,3,1211,0,0,0),(3634,1,1212,0,0,0),(3635,2,1212,0,0,0),(3636,3,1212,0,0,0),(3637,1,1213,0,0,0),(3638,2,1213,0,0,0),(3639,3,1213,0,0,0),(3640,1,1214,0,0,0),(3641,2,1214,0,0,0),(3642,3,1214,0,0,0),(3643,1,1215,0,0,0),(3644,2,1215,0,0,0),(3645,3,1215,0,0,0),(3646,1,1216,0,0,0),(3647,2,1216,0,0,0),(3648,3,1216,0,0,0),(3649,1,1217,0,0,0),(3650,2,1217,0,0,0),(3651,3,1217,0,0,0),(3652,1,1218,0,0,0),(3653,2,1218,0,0,0),(3654,3,1218,0,0,0),(3655,1,1219,0,0,0),(3656,2,1219,0,0,0),(3657,3,1219,0,0,0),(3658,1,1220,0,0,0),(3659,2,1220,0,0,0),(3660,3,1220,0,0,0),(3661,1,1221,0,0,0),(3662,2,1221,0,0,0),(3663,3,1221,0,0,0),(3664,1,1222,0,0,0),(3665,2,1222,0,0,0),(3666,3,1222,0,0,0),(3667,1,1223,0,0,0),(3668,2,1223,0,0,0),(3669,3,1223,0,0,0),(3670,1,1224,0,0,0),(3671,2,1224,0,0,0),(3672,3,1224,0,0,0),(3673,1,1225,0,0,0),(3674,2,1225,0,0,0),(3675,3,1225,0,0,0),(3676,1,1226,0,0,0),(3677,2,1226,0,0,0),(3678,3,1226,0,0,0),(3679,1,1227,0,0,0),(3680,2,1227,0,0,0),(3681,3,1227,0,0,0),(3682,1,1228,0,0,0),(3683,2,1228,0,0,0),(3684,3,1228,0,0,0),(3685,1,1229,0,0,0),(3686,2,1229,0,0,0),(3687,3,1229,0,0,0),(3688,1,1230,0,0,0),(3689,2,1230,0,0,0),(3690,3,1230,0,0,0),(3691,1,1231,0,0,0),(3692,2,1231,0,0,0),(3693,3,1231,0,0,0),(3694,1,1232,0,0,0),(3695,2,1232,0,0,0),(3696,3,1232,0,0,0),(3697,1,1233,0,0,0),(3698,2,1233,0,0,0),(3699,3,1233,0,0,0),(3700,1,1234,0,0,0),(3701,2,1234,0,0,0),(3702,3,1234,0,0,0),(3703,1,1235,0,0,0),(3704,2,1235,0,0,0),(3705,3,1235,0,0,0),(3706,1,1236,0,0,0),(3707,2,1236,0,0,0),(3708,3,1236,0,0,0),(3709,1,1237,0,0,0),(3710,2,1237,0,0,0),(3711,3,1237,0,0,0),(3712,1,1238,0,0,0),(3713,2,1238,0,0,0),(3714,3,1238,0,0,0),(3715,1,1239,0,0,0),(3716,2,1239,0,0,0),(3717,3,1239,0,0,0),(3718,1,1240,0,0,0),(3719,2,1240,0,0,0),(3720,3,1240,0,0,0),(3721,1,1241,0,0,0),(3722,2,1241,0,0,0),(3723,3,1241,0,0,0),(3724,1,1242,0,0,0),(3725,2,1242,0,0,0),(3726,3,1242,0,0,0),(3727,1,1243,0,0,0),(3728,2,1243,0,0,0),(3729,3,1243,0,0,0),(3730,1,1244,0,0,0),(3731,2,1244,0,0,0),(3732,3,1244,0,0,0),(3733,1,1245,0,0,0),(3734,2,1245,0,0,0),(3735,3,1245,0,0,0),(3736,1,1246,0,0,0),(3737,2,1246,0,0,0),(3738,3,1246,0,0,0),(3739,1,1247,0,0,0),(3740,2,1247,0,0,0),(3741,3,1247,0,0,0),(3742,1,1248,0,0,0),(3743,2,1248,0,0,0),(3744,3,1248,0,0,0),(3745,1,1249,0,0,0),(3746,2,1249,0,0,0),(3747,3,1249,0,0,0),(3748,1,1250,0,0,0),(3749,2,1250,0,0,0),(3750,3,1250,0,0,0),(3751,1,1251,0,0,0),(3752,2,1251,0,0,0),(3753,3,1251,0,0,0),(3754,1,1252,0,0,0),(3755,2,1252,0,0,0),(3756,3,1252,0,0,0),(3757,1,1253,0,0,0),(3758,2,1253,0,0,0),(3759,3,1253,0,0,0),(3760,1,1254,0,0,0),(3761,2,1254,0,0,0),(3762,3,1254,0,0,0),(3763,1,1255,0,0,0),(3764,2,1255,0,0,0),(3765,3,1255,0,0,0),(3766,1,1256,0,0,0),(3767,2,1256,0,0,0),(3768,3,1256,0,0,0),(3769,1,1257,0,0,0),(3770,2,1257,0,0,0),(3771,3,1257,0,0,0),(3772,1,1258,0,0,0),(3773,2,1258,0,0,0),(3774,3,1258,0,0,0),(3775,1,1259,0,0,0),(3776,2,1259,0,0,0),(3777,3,1259,0,0,0),(3778,1,1260,0,0,0),(3779,2,1260,0,0,0),(3780,3,1260,0,0,0),(3781,1,1261,0,0,0),(3782,2,1261,0,0,0),(3783,3,1261,0,0,0),(3784,1,1262,0,0,0),(3785,2,1262,0,0,0),(3786,3,1262,0,0,0),(3787,1,1263,0,0,0),(3788,2,1263,0,0,0),(3789,3,1263,0,0,0),(3790,1,1264,0,0,0),(3791,2,1264,0,0,0),(3792,3,1264,0,0,0),(3793,1,1265,0,0,0),(3794,2,1265,0,0,0),(3795,3,1265,0,0,0),(3796,1,1266,0,0,0),(3797,2,1266,0,0,0),(3798,3,1266,0,0,0),(3799,1,1267,0,0,0),(3800,2,1267,0,0,0),(3801,3,1267,0,0,0),(3802,1,1268,0,0,0),(3803,2,1268,0,0,0),(3804,3,1268,0,0,0),(3805,1,1269,0,0,0),(3806,2,1269,0,0,0),(3807,3,1269,0,0,0),(3808,1,1270,0,0,0),(3809,2,1270,0,0,0),(3810,3,1270,0,0,0),(3811,1,1271,0,0,0),(3812,2,1271,0,0,0),(3813,3,1271,0,0,0),(3814,1,1272,0,0,0),(3815,2,1272,0,0,0),(3816,3,1272,0,0,0),(3817,1,1273,0,0,0),(3818,2,1273,0,0,0),(3819,3,1273,0,0,0),(3820,1,1274,0,0,0),(3821,2,1274,0,0,0),(3822,3,1274,0,0,0),(3823,1,1275,0,0,0),(3824,2,1275,0,0,0),(3825,3,1275,0,0,0),(3826,1,1276,0,0,0),(3827,2,1276,0,0,0),(3828,3,1276,0,0,0),(3829,1,1277,0,0,0),(3830,2,1277,0,0,0),(3831,3,1277,0,0,0),(3832,1,1278,0,0,0),(3833,2,1278,0,0,0),(3834,3,1278,0,0,0),(3835,1,1279,0,0,0),(3836,2,1279,0,0,0),(3837,3,1279,0,0,0),(3838,1,1280,0,0,0),(3839,2,1280,0,0,0),(3840,3,1280,0,0,0),(3841,1,1281,0,0,0),(3842,2,1281,0,0,0),(3843,3,1281,0,0,0),(3844,1,1282,0,0,0),(3845,2,1282,0,0,0),(3846,3,1282,0,0,0),(3847,1,1283,0,0,0),(3848,2,1283,0,0,0),(3849,3,1283,0,0,0),(3850,1,1284,0,0,0),(3851,2,1284,0,0,0),(3852,3,1284,0,0,0),(3853,1,1285,0,0,0),(3854,2,1285,0,0,0),(3855,3,1285,0,0,0),(3856,1,1286,0,0,0),(3857,2,1286,0,0,0),(3858,3,1286,0,0,0),(3859,1,1287,0,0,0),(3860,2,1287,0,0,0),(3861,3,1287,0,0,0),(3862,1,1288,0,0,0),(3863,2,1288,0,0,0),(3864,3,1288,0,0,0),(3865,1,1289,0,0,0),(3866,2,1289,0,0,0),(3867,3,1289,0,0,0),(3868,1,1290,0,0,0),(3869,2,1290,0,0,0),(3870,3,1290,0,0,0),(3871,1,1291,0,0,0),(3872,2,1291,0,0,0),(3873,3,1291,0,0,0),(3874,1,1292,0,0,0),(3875,2,1292,0,0,0),(3876,3,1292,0,0,0),(3877,1,1293,0,0,0),(3878,2,1293,0,0,0),(3879,3,1293,0,0,0),(3880,1,1294,0,0,0),(3881,2,1294,0,0,0),(3882,3,1294,0,0,0),(3883,1,1295,0,0,0),(3884,2,1295,0,0,0),(3885,3,1295,0,0,0),(3886,1,1296,0,0,0),(3887,2,1296,0,0,0),(3888,3,1296,0,0,0),(3889,1,1297,0,0,0),(3890,2,1297,0,0,0),(3891,3,1297,0,0,0),(3892,1,1298,0,0,0),(3893,2,1298,0,0,0),(3894,3,1298,0,0,0),(3895,1,1299,0,0,0),(3896,2,1299,0,0,0),(3897,3,1299,0,0,0),(3898,1,1300,0,0,0),(3899,2,1300,0,0,0),(3900,3,1300,0,0,0),(3901,1,1301,0,0,0),(3902,2,1301,0,0,0),(3903,3,1301,0,0,0),(3904,1,1302,0,0,0),(3905,2,1302,0,0,0),(3906,3,1302,0,0,0),(3907,1,1303,0,0,0),(3908,2,1303,0,0,0),(3909,3,1303,0,0,0),(3910,1,1304,0,0,0),(3911,2,1304,0,0,0),(3912,3,1304,0,0,0),(3913,1,1305,0,0,0),(3914,2,1305,0,0,0),(3915,3,1305,0,0,0),(3916,1,1306,0,0,0),(3917,2,1306,0,0,0),(3918,3,1306,0,0,0),(3919,1,1307,0,0,0),(3920,2,1307,0,0,0),(3921,3,1307,0,0,0),(3922,1,1308,0,0,0),(3923,2,1308,0,0,0),(3924,3,1308,0,0,0),(3925,1,1309,0,0,0),(3926,2,1309,0,0,0),(3927,3,1309,0,0,0),(3928,1,1310,0,0,0),(3929,2,1310,0,0,0),(3930,3,1310,0,0,0),(3931,1,1311,0,0,0),(3932,2,1311,0,0,0),(3933,3,1311,0,0,0),(3934,1,1312,0,0,0),(3935,2,1312,0,0,0),(3936,3,1312,0,0,0),(3937,1,1313,0,0,0),(3938,2,1313,0,0,0),(3939,3,1313,0,0,0),(3940,1,1314,0,0,0),(3941,2,1314,0,0,0),(3942,3,1314,0,0,0),(3943,1,1315,0,0,0),(3944,2,1315,0,0,0),(3945,3,1315,0,0,0),(3946,1,1316,0,0,0),(3947,2,1316,0,0,0),(3948,3,1316,0,0,0),(3949,1,1317,0,0,0),(3950,2,1317,0,0,0),(3951,3,1317,0,0,0),(3952,1,1318,0,0,0),(3953,2,1318,0,0,0),(3954,3,1318,0,0,0),(3955,1,1319,0,0,0),(3956,2,1319,0,0,0),(3957,3,1319,0,0,0),(3958,1,1320,0,0,0),(3959,2,1320,0,0,0),(3960,3,1320,0,0,0),(3961,1,1321,0,0,0),(3962,2,1321,0,0,0),(3963,3,1321,0,0,0),(3964,1,1322,0,0,0),(3965,2,1322,0,0,0),(3966,3,1322,0,0,0),(3967,1,1323,0,0,0),(3968,2,1323,0,0,0),(3969,3,1323,0,0,0),(3970,1,1324,0,0,0),(3971,2,1324,0,0,0),(3972,3,1324,0,0,0),(3973,1,1325,0,0,0),(3974,2,1325,0,0,0),(3975,3,1325,0,0,0),(3976,1,1326,0,0,0),(3977,2,1326,0,0,0),(3978,3,1326,0,0,0),(3979,1,1327,0,0,0),(3980,2,1327,0,0,0),(3981,3,1327,0,0,0),(3982,1,1328,0,0,0),(3983,2,1328,0,0,0),(3984,3,1328,0,0,0),(3985,1,1329,0,0,0),(3986,2,1329,0,0,0),(3987,3,1329,0,0,0),(3988,1,1330,0,0,0),(3989,2,1330,0,0,0),(3990,3,1330,0,0,0),(3991,1,1331,0,0,0),(3992,2,1331,0,0,0),(3993,3,1331,0,0,0),(3994,1,1332,0,0,0),(3995,2,1332,0,0,0),(3996,3,1332,0,0,0),(3997,1,1333,0,0,0),(3998,2,1333,0,0,0),(3999,3,1333,0,0,0),(4000,1,1334,0,0,0),(4001,2,1334,0,0,0),(4002,3,1334,0,0,0),(4003,1,1335,0,0,0),(4004,2,1335,0,0,0),(4005,3,1335,0,0,0),(4006,1,1336,0,0,0),(4007,2,1336,0,0,0),(4008,3,1336,0,0,0),(4009,1,1337,0,0,0),(4010,2,1337,0,0,0),(4011,3,1337,0,0,0);
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
	SET @inventory_table_name := 'CUSTOMER DETAIL';
    
    IF(NEW.`is_for_branch` = 1) THEN
		SET @inventory_table_name := 'DELIVERY DETAIL';
	END IF;
    
	CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,@inventory_table_name);
	
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
	SET @new_inventory_table := 'CUSTOMER DETAIL';
	
    IF(NEW.`is_for_branch` = 1) THEN
		SET @new_inventory_table := 'DELIVERY DETAIL';
    END IF;
    
    IF(OLD.`is_for_branch` <> NEW.`is_for_branch`) THEN
		SET @old_inventory_table := 'CUSTOMER DETAIL';
		SET @new_inventory_table := 'DELIVERY DETAIL';
        
		IF(OLD.`is_for_branch` = 1) THEN
			SET @old_inventory_table := 'DELIVERY DETAIL';
            SET @new_inventory_table := 'CUSTOMER DETAIL';
		END IF;
        
        IF(OLD.`request_detail_id` <> 0) THEN
			CALL process_update_receive(OLD.`request_detail_id`,(-1 * OLD.`quantity`),'REQUEST DETAIL');
		END IF;
        
		IF(NEW.`request_detail_id` <> 0) THEN
			CALL process_update_receive(NEW.`request_detail_id`,NEW.`quantity`,'REQUEST DETAIL');
		END IF;
        
        CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,@old_inventory_table);
        CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,@new_inventory_table);
	
    ELSEIF (OLD.`recv_quantity` <> NEW.`recv_quantity`) THEN
    
		SET @qty := (NEW.`recv_quantity` - OLD.`recv_quantity`);
        
        IF(NEW.`is_for_branch` = 1) THEN
            CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'TRANSFER DETAIL');
        END IF;
    
    ELSEIF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,@new_inventory_table);
		CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,@new_inventory_table);
        
        IF(OLD.`request_detail_id` <> 0) THEN
			CALL process_update_receive(OLD.`request_detail_id`,(-1 * OLD.`quantity`),'REQUEST DETAIL');
        END IF;
        
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := (NEW.`quantity` - OLD.`quantity`) * -1;
		
        CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,@new_inventory_table);
        
        IF(NEW.`request_detail_id` <> 0) THEN
			CALL process_update_receive(NEW.`request_detail_id`,@qty * -1,'REQUEST DETAIL');
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
	SET @inventory_table_name := 'CUSTOMER DETAIL';
    
    IF(OLD.`is_for_branch` = 1) THEN
		SET @inventory_table_name := 'DELIVERY DETAIL';
	END IF;
    
    CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,@inventory_table_name);
	
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
  PRIMARY KEY (`id`),
  KEY `idx_isshow_tobranchid_id` (`is_show`,`to_branchid`,`id`)
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
  PRIMARY KEY (`id`),
  KEY `idx_isshow_tobranchid_id` (`is_show`,`request_to_branchid`,`id`),
  KEY `idx_isshow_tobranchid` (`is_show`,`request_to_branchid`)
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subgroup`
--

LOCK TABLES `subgroup` WRITE;
/*!40000 ALTER TABLE `subgroup` DISABLE KEYS */;
INSERT INTO `subgroup` VALUES (1,'A','ANGLE BAR',1,'2015-05-20 00:00:00','2015-08-13 06:27:53',1,1),(2,'C','COIL',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(3,'E','EMBROSSED / CHECKERED PLATE',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(4,'F','FLAT BAR',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(5,'H','HEXAGON BAR',1,'2015-05-26 12:18:49','2015-05-26 12:18:49',1,1),(6,'J','ROUND TUBE',1,'2015-05-26 12:19:00','2015-05-26 12:19:00',1,1),(7,'K','SQUARE TUBE',1,'2015-05-26 12:19:12','2015-05-26 12:19:12',1,1),(8,'L','RECTANGULAR TUBE',1,'2015-05-26 12:19:26','2015-05-26 12:19:26',1,1),(9,'M','MESH - IMPORTED',1,'2015-05-26 12:19:38','2015-05-26 12:19:38',1,1),(10,'N','PIPE',1,'2015-05-26 12:19:48','2015-05-26 12:19:48',1,1),(11,'P','PERFORATED SHEET',1,'2015-05-26 12:20:07','2015-05-26 12:20:07',1,1),(12,'Q','SQUARE BAR',1,'2015-05-26 12:20:26','2015-05-26 12:20:26',1,1),(13,'R','ROUND BAR',1,'2015-05-26 12:20:41','2015-05-26 12:20:41',1,1),(14,'S','SHEETS AND PLATES - PLAIN',1,'2015-05-26 12:20:54','2015-05-26 12:20:54',1,1),(15,'U','WIRE',1,'2015-05-26 12:21:04','2015-05-26 12:21:04',1,1),(16,'V','WIELDING ROD / ELECTRODE',1,'2015-05-26 12:21:27','2015-05-26 12:21:27',1,1),(17,'W','WEDLED WIRE SCREEN - IMPORTED',1,'2015-05-26 12:21:47','2015-05-26 12:21:47',1,1),(18,'X','EXPANDED METAL',1,'2015-05-26 12:22:21','2015-05-26 12:22:21',1,1);
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
  KEY `idx_user_pass` (`username`,`password`),
  KEY `idx_isshow_username_fullname_id` (`is_show`,`id`,`username`,`full_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'00','Lawrence Pena','nelsoft.superadmin','83703b5229462cb6bfaf425152e46a8c','09263188835',1,1,1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1),(2,'02','Marilao Admin','mo.admin','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:24:28','2015-08-05 08:24:28',1,1),(3,'03','Marilao Encoder','mo.encoder','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:24:52','2015-08-05 08:24:52',1,1),(4,'05','Portrero Admin','po.admin','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:25:11','2015-08-05 08:25:11',1,1),(5,'06','Portrero Encoder','po.encoder','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:25:40','2015-08-05 08:25:40',1,1),(6,'07','Mapua Admin','ma.admin','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:26:01','2015-08-05 08:26:01',1,1),(7,'08','Mapua Encoder','ma.encoder','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:26:30','2015-08-05 08:26:30',1,1),(8,'01','superadmin','superadmin','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-06 12:58:41','2015-08-06 12:58:41',1,1);
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
  `user_branch_id` bigint(20) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id`),
  KEY `idx_userid_branchid` (`user_id`,`user_branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_branch`
--

LOCK TABLES `user_branch` WRITE;
/*!40000 ALTER TABLE `user_branch` DISABLE KEYS */;
INSERT INTO `user_branch` VALUES (1,1,1),(2,1,2),(3,1,3),(4,2,1),(5,3,1),(6,4,2),(7,5,2),(8,6,3),(9,7,3),(10,8,1),(11,8,2),(12,8,3);
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
  `user_id` bigint(20) DEFAULT '0',
  `permission_code` int(5) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permission`
--

LOCK TABLES `user_permission` WRITE;
/*!40000 ALTER TABLE `user_permission` DISABLE KEYS */;
INSERT INTO `user_permission` VALUES (1,1,100),(2,2,100),(3,3,101),(4,3,102),(5,3,103),(6,3,104),(7,3,105),(8,3,106),(9,3,107),(10,3,108),(11,3,109),(12,3,110),(13,3,111),(14,3,112),(15,3,113),(16,3,114),(17,3,115),(18,3,116),(19,3,117),(20,3,118),(21,3,119),(22,3,120),(23,3,121),(24,3,131),(25,3,132),(26,3,133),(27,3,134),(28,3,135),(29,3,136),(30,3,137),(31,3,138),(32,3,139),(33,3,140),(34,3,161),(35,3,162),(36,3,163),(37,3,164),(38,3,165),(39,3,234),(40,3,235),(41,3,236),(42,3,237),(43,3,238),(44,3,239),(45,3,240),(46,3,171),(47,3,172),(48,3,173),(49,3,174),(50,3,175),(51,3,176),(52,3,177),(53,3,178),(54,3,179),(55,3,180),(56,3,181),(57,3,141),(58,3,142),(59,3,143),(60,3,144),(61,3,145),(62,3,156),(63,3,157),(64,3,158),(65,3,159),(66,3,160),(67,3,223),(68,3,224),(69,3,225),(70,3,226),(71,3,227),(72,3,199),(73,3,200),(74,3,201),(75,3,202),(76,3,203),(77,3,191),(78,3,192),(79,3,193),(80,3,194),(81,3,195),(82,4,100),(83,5,101),(84,5,102),(85,5,103),(86,5,104),(87,5,105),(88,5,106),(89,5,107),(90,5,108),(91,5,109),(92,5,110),(93,5,111),(94,5,112),(95,5,113),(96,5,114),(97,5,115),(98,5,116),(99,5,117),(100,5,118),(101,5,119),(102,5,120),(103,5,121),(104,5,131),(105,5,132),(106,5,133),(107,5,134),(108,5,135),(109,5,136),(110,5,137),(111,5,138),(112,5,139),(113,5,140),(114,5,161),(115,5,162),(116,5,163),(117,5,164),(118,5,165),(119,5,234),(120,5,235),(121,5,236),(122,5,237),(123,5,238),(124,5,239),(125,5,240),(126,5,171),(127,5,172),(128,5,173),(129,5,174),(130,5,175),(131,5,176),(132,5,177),(133,5,178),(134,5,179),(135,5,180),(136,5,181),(137,5,141),(138,5,142),(139,5,143),(140,5,144),(141,5,145),(142,5,156),(143,5,157),(144,5,158),(145,5,159),(146,5,160),(147,5,223),(148,5,224),(149,5,225),(150,5,226),(151,5,227),(152,5,199),(153,5,200),(154,5,201),(155,5,202),(156,5,203),(157,5,191),(158,5,192),(159,5,193),(160,5,194),(161,5,195),(162,6,100),(163,7,101),(164,7,102),(165,7,103),(166,7,104),(167,7,105),(168,7,106),(169,7,107),(170,7,108),(171,7,109),(172,7,110),(173,7,111),(174,7,112),(175,7,113),(176,7,114),(177,7,115),(178,7,116),(179,7,117),(180,7,118),(181,7,119),(182,7,120),(183,7,121),(184,7,131),(185,7,132),(186,7,133),(187,7,134),(188,7,135),(189,7,136),(190,7,137),(191,7,138),(192,7,139),(193,7,140),(194,7,161),(195,7,162),(196,7,163),(197,7,164),(198,7,165),(199,7,234),(200,7,235),(201,7,236),(202,7,237),(203,7,238),(204,7,239),(205,7,240),(206,7,171),(207,7,172),(208,7,173),(209,7,174),(210,7,175),(211,7,176),(212,7,177),(213,7,178),(214,7,179),(215,7,180),(216,7,181),(217,7,141),(218,7,142),(219,7,143),(220,7,144),(221,7,145),(222,7,156),(223,7,157),(224,7,158),(225,7,159),(226,7,160),(227,7,223),(228,7,224),(229,7,225),(230,7,226),(231,7,227),(232,7,199),(233,7,200),(234,7,201),(235,7,202),(236,7,203),(237,7,191),(238,7,192),(239,7,193),(240,7,194),(241,7,195),(242,8,100);
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
    DECLARE cursor_for_branch INT(1);
    
	DECLARE done INT DEFAULT FALSE;
    
    DECLARE cursor_return CURSOR FOR SELECT `product_id`, `quantity` FROM return_detail WHERE `headid` = head_id_d;
    DECLARE cursor_damage CURSOR FOR SELECT `product_id`, `quantity` FROM damage_detail WHERE `headid` = head_id_d;
    DECLARE cursor_release CURSOR FOR SELECT `product_id`, `quantity`, `release_order_detail_id` FROM release_detail WHERE `headid` = head_id_d;
    DECLARE cursor_purchase_return CURSOR FOR SELECT `product_id`, `quantity` FROM purchase_return_detail WHERE `headid` = head_id_d;
    DECLARE cursor_received CURSOR FOR SELECT `product_id`, `quantity`, `purchase_detail_id` FROM purchase_receive_detail WHERE `headid` = head_id_d;
    DECLARE cursor_delivery CURSOR FOR SELECT `product_id`, `quantity`, `request_detail_id`, `is_for_branch` FROM stock_delivery_detail WHERE `headid` = head_id_d; 
    
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
			FETCH cursor_release INTO cursor_product_id, cursor_quantity, cursor_other_id;
        ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
			FETCH cursor_received INTO cursor_product_id, cursor_quantity, cursor_other_id;
		ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
			FETCH cursor_purchase_return INTO cursor_product_id, cursor_quantity;
		ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
			FETCH cursor_delivery INTO cursor_product_id, cursor_quantity, cursor_other_id, cursor_for_branch;
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
				
                IF(cursor_other_id <> 0) THEN
					CALL process_update_receive(cursor_other_id,(-1 * cursor_quantity),'RELEASE DETAIL');
				END IF;
                
            ELSEIF (table_name_d = 'RECEIVE HEAD') THEN
            
				CALL process_compute_inventory_for_detail(cursor_product_id,(-1 * cursor_quantity),head_id_d,'RECEIVE DETAIL');
				CALL process_update_receive(cursor_other_id,(-1 * cursor_quantity),'RECEIVE DETAIL');
			
            ELSEIF (table_name_d = 'PURCHASE RETURN HEAD') THEN
				
                CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,'PURCHASE RETURN DETAIL');
			
            ELSEIF (table_name_d = 'DELIVERY HEAD') THEN
				SET @inventory_table_name := 'CUSTOMER DETAIL';
                
                IF(cursor_for_branch = 1) then
					SET @inventory_table_name := 'DELIVERY DETAIL';
                END IF;
                
                CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,@inventory_table_name);
				
                IF(cursor_other_id <> 0) THEN
					CALL process_update_receive(cursor_other_id,(-1 * cursor_quantity),'REQUEST DETAIL');
				END IF;
            
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

-- Dump completed on 2015-08-18 20:21:54

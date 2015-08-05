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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'BJ014L20','BI Tube 1/4\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:29:49','2015-05-26 12:29:49',1,1),(2,'BJ056L20','BI Tube 5/16\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:30:14','2015-05-26 12:30:14',1,1),(3,'BJ038A20','BI Tube 3/8\" (1.0mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:34:41','2015-05-26 12:34:41',1,1),(4,'BJ012C20','BI Tube 1/2\" (1.6mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:34:52','2015-05-26 12:34:52',1,1),(5,'BJ058B19','BI Tube 5/8\" (1.2mm) x 19 Ft.',1,6,6,1,'2015-07-26 10:27:57','2015-05-26 12:35:11',1,1),(6,'BJ058B20','BI Tube 5/8\" (1.2mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:35:21','2015-05-26 12:35:21',1,1),(7,'BC06004F','Hot Rolled COIL 6.0mm x 4 Ft.',1,6,2,1,'2015-05-26 12:38:46','2015-06-30 12:44:43',1,1),(8,'BC05004S','Hot Rolled COIL 5.0mm x 4 Ft. ',1,6,2,1,'2015-05-26 12:38:58','2015-06-30 12:54:40',1,1),(9,'SE112233','Hot Rolled COIL 4.5mm x 4 Ft. ',1,2,3,1,'2015-05-26 12:39:18','2015-06-30 01:29:35',1,1),(10,'SS304CUT','SS-304  CUTTINGS',0,0,0,1,'2015-05-26 12:47:26','2015-05-26 12:47:26',1,1),(11,'SS316CUT','SS-316  CUTTINGSS',0,0,0,1,'2015-07-24 12:01:51','2015-05-26 12:47:36',1,1),(12,'ALUMNCUT','ALUMINUM  CUTTING',0,0,0,1,'2015-05-26 12:50:35','2015-05-26 12:50:35',1,1),(13,'MATLABOR','LABOR ONLY, MAT.  FROM CUSTOMER',0,0,0,1,'2015-05-26 12:52:17','2015-05-26 12:52:17',1,1),(14,'COPPRCUT','COPPER  CUTTINGS',0,0,0,1,'2015-05-26 12:52:27','2015-05-26 12:52:27',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_branch_inventory`
--

LOCK TABLES `product_branch_inventory` WRITE;
/*!40000 ALTER TABLE `product_branch_inventory` DISABLE KEYS */;
INSERT INTO `product_branch_inventory` VALUES (1,1,1,0,0,0),(2,2,1,0,0,0),(3,3,1,0,0,0),(4,1,2,0,0,0),(5,2,2,0,0,0),(6,3,2,0,0,0),(7,1,3,0,0,0),(8,2,3,0,0,0),(9,3,3,0,0,0),(10,1,4,0,0,0),(11,2,4,0,0,0),(12,3,4,0,0,0),(13,1,5,0,0,0),(14,2,5,0,0,0),(15,3,5,0,0,0),(16,1,6,0,0,0),(17,2,6,0,0,0),(18,3,6,0,0,0),(19,1,7,0,0,0),(20,2,7,0,0,0),(21,3,7,0,0,0),(22,1,8,0,0,0),(23,2,8,0,0,0),(24,3,8,0,0,0),(25,1,9,0,0,0),(26,2,9,0,0,0),(27,3,9,0,0,0),(28,1,10,0,0,0),(29,2,10,0,0,0),(30,3,10,0,0,0),(31,1,11,0,0,0),(32,2,11,0,0,0),(33,3,11,0,0,0),(34,1,12,0,0,0),(35,2,12,0,0,0),(36,3,12,0,0,0),(37,1,13,0,0,0),(38,2,13,0,0,0),(39,3,13,0,0,0),(40,1,14,0,0,0),(41,2,14,0,0,0),(42,3,14,0,0,0);
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
            CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'TRANSFER DETAIL');
        END IF;
        
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
			CALL process_update_receive(OLD.`request_detail_id`,(-1 * OLD.`quantity`),'REQUEST DETAIL');
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
INSERT INTO `user` VALUES (1,'01','Lawrence Pena','superadmin','83703b5229462cb6bfaf425152e46a8c','09263188835',1,1,1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1),(2,'02','Marilao Admin','mo.admin','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:24:28','2015-08-05 08:24:28',1,1),(3,'03','Marilao Encoder','mo.encoder','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:24:52','2015-08-05 08:24:52',1,1),(4,'05','Portrero Admin','po.admin','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:25:11','2015-08-05 08:25:11',1,1),(5,'06','Portrero Encoder','po.encoder','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:25:40','2015-08-05 08:25:40',1,1),(6,'07','Mapua Admin','ma.admin','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:26:01','2015-08-05 08:26:01',1,1),(7,'08','Mapua Encoder','ma.encoder','9ee83e747fa1b06311eccb0af875076f','',1,1,0,'2015-08-05 08:26:30','2015-08-05 08:26:30',1,1);
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_branch`
--

LOCK TABLES `user_branch` WRITE;
/*!40000 ALTER TABLE `user_branch` DISABLE KEYS */;
INSERT INTO `user_branch` VALUES (1,1,1),(2,1,2),(3,1,3),(4,2,1),(5,3,1),(6,4,2),(7,5,2),(8,6,3),(9,7,3);
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permission`
--

LOCK TABLES `user_permission` WRITE;
/*!40000 ALTER TABLE `user_permission` DISABLE KEYS */;
INSERT INTO `user_permission` VALUES (1,1,100),(2,2,100),(3,3,101),(4,3,102),(5,3,103),(6,3,104),(7,3,105),(8,3,106),(9,3,107),(10,3,108),(11,3,109),(12,3,110),(13,3,111),(14,3,112),(15,3,113),(16,3,114),(17,3,115),(18,3,116),(19,3,117),(20,3,118),(21,3,119),(22,3,120),(23,3,121),(24,3,131),(25,3,132),(26,3,133),(27,3,134),(28,3,135),(29,3,136),(30,3,137),(31,3,138),(32,3,139),(33,3,140),(34,3,161),(35,3,162),(36,3,163),(37,3,164),(38,3,165),(39,3,234),(40,3,235),(41,3,236),(42,3,237),(43,3,238),(44,3,239),(45,3,240),(46,3,171),(47,3,172),(48,3,173),(49,3,174),(50,3,175),(51,3,176),(52,3,177),(53,3,178),(54,3,179),(55,3,180),(56,3,181),(57,3,141),(58,3,142),(59,3,143),(60,3,144),(61,3,145),(62,3,156),(63,3,157),(64,3,158),(65,3,159),(66,3,160),(67,3,223),(68,3,224),(69,3,225),(70,3,226),(71,3,227),(72,3,199),(73,3,200),(74,3,201),(75,3,202),(76,3,203),(77,3,191),(78,3,192),(79,3,193),(80,3,194),(81,3,195),(82,4,100),(83,5,101),(84,5,102),(85,5,103),(86,5,104),(87,5,105),(88,5,106),(89,5,107),(90,5,108),(91,5,109),(92,5,110),(93,5,111),(94,5,112),(95,5,113),(96,5,114),(97,5,115),(98,5,116),(99,5,117),(100,5,118),(101,5,119),(102,5,120),(103,5,121),(104,5,131),(105,5,132),(106,5,133),(107,5,134),(108,5,135),(109,5,136),(110,5,137),(111,5,138),(112,5,139),(113,5,140),(114,5,161),(115,5,162),(116,5,163),(117,5,164),(118,5,165),(119,5,234),(120,5,235),(121,5,236),(122,5,237),(123,5,238),(124,5,239),(125,5,240),(126,5,171),(127,5,172),(128,5,173),(129,5,174),(130,5,175),(131,5,176),(132,5,177),(133,5,178),(134,5,179),(135,5,180),(136,5,181),(137,5,141),(138,5,142),(139,5,143),(140,5,144),(141,5,145),(142,5,156),(143,5,157),(144,5,158),(145,5,159),(146,5,160),(147,5,223),(148,5,224),(149,5,225),(150,5,226),(151,5,227),(152,5,199),(153,5,200),(154,5,201),(155,5,202),(156,5,203),(157,5,191),(158,5,192),(159,5,193),(160,5,194),(161,5,195),(162,6,100),(163,7,101),(164,7,102),(165,7,103),(166,7,104),(167,7,105),(168,7,106),(169,7,107),(170,7,108),(171,7,109),(172,7,110),(173,7,111),(174,7,112),(175,7,113),(176,7,114),(177,7,115),(178,7,116),(179,7,117),(180,7,118),(181,7,119),(182,7,120),(183,7,121),(184,7,131),(185,7,132),(186,7,133),(187,7,134),(188,7,135),(189,7,136),(190,7,137),(191,7,138),(192,7,139),(193,7,140),(194,7,161),(195,7,162),(196,7,163),(197,7,164),(198,7,165),(199,7,234),(200,7,235),(201,7,236),(202,7,237),(203,7,238),(204,7,239),(205,7,240),(206,7,171),(207,7,172),(208,7,173),(209,7,174),(210,7,175),(211,7,176),(212,7,177),(213,7,178),(214,7,179),(215,7,180),(216,7,181),(217,7,141),(218,7,142),(219,7,143),(220,7,144),(221,7,145),(222,7,156),(223,7,157),(224,7,158),(225,7,159),(226,7,160),(227,7,223),(228,7,224),(229,7,225),(230,7,226),(231,7,227),(232,7,199),(233,7,200),(234,7,201),(235,7,202),(236,7,203),(237,7,191),(238,7,192),(239,7,193),(240,7,194),(241,7,195);
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
    DECLARE cursor_release CURSOR FOR SELECT `product_id`, `quantity`, `release_order_detail_id` FROM release_detail WHERE `headid` = head_id_d;
    DECLARE cursor_purchase_return CURSOR FOR SELECT `product_id`, `quantity` FROM purchase_return_detail WHERE `headid` = head_id_d;
    DECLARE cursor_received CURSOR FOR SELECT `product_id`, `quantity`, `purchase_detail_id` FROM purchase_receive_detail WHERE `headid` = head_id_d;
    DECLARE cursor_delivery CURSOR FOR SELECT `product_id`, `quantity`, `request_detail_id` FROM stock_delivery_detail WHERE `headid` = head_id_d; 
    
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
			FETCH cursor_delivery INTO cursor_product_id, cursor_quantity, cursor_other_id;
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
				CALL process_compute_inventory_for_detail(cursor_product_id,cursor_quantity,head_id_d,'DELIVERY DETAIL');
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

-- Dump completed on 2015-08-05 21:14:27

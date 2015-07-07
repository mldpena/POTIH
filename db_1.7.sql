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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch`
--

LOCK TABLES `branch` WRITE;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` VALUES (1,'01','Manila',1,'2015-05-19 00:00:00','2015-06-30 04:21:45',1,1),(2,'02','Makati',1,'2015-05-24 02:26:23','2015-06-30 04:20:08',1,1),(3,'03','Pasay',1,'2015-05-26 04:26:38','2015-05-26 04:26:38',1,1),(4,'04','Valenzuela',1,'2015-05-26 03:45:00','2015-05-26 03:45:00',1,1),(6,'05','Bambang',1,'2015-05-27 07:16:11','2015-05-27 07:16:11',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'BJ014L20','BI Tube 1/4\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:29:49','2015-05-26 12:29:49',1,1),(2,'BJ056L20','BI Tube 5/16\" (0.8mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:30:14','2015-05-26 12:30:14',1,1),(3,'BJ038A20','BI Tube 3/8\" (1.0mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:34:41','2015-05-26 12:34:41',1,1),(4,'BJ012C20','BI Tube 1/2\" (1.6mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:34:52','2015-05-26 12:34:52',1,1),(5,'BJ058B19','BI Tube 5/8\" (1.2mm) x 19 Ft.',1,6,6,1,'2015-05-26 12:35:11','2015-05-26 12:35:11',1,1),(6,'BJ058B20','BI Tube 5/8\" (1.2mm) x 20 Ft.',1,6,6,1,'2015-05-26 12:35:21','2015-05-26 12:35:21',1,1),(7,'BC06004F','Hot Rolled COIL 6.0mm x 4 Ft.',1,6,2,1,'2015-05-26 12:38:46','2015-06-30 12:44:43',1,1),(8,'BC05004S','Hot Rolled COIL 5.0mm x 4 Ft. ',1,6,2,1,'2015-05-26 12:38:58','2015-06-30 12:54:40',1,1),(9,'SE112233','Hot Rolled COIL 4.5mm x 4 Ft. ',1,2,3,1,'2015-05-26 12:39:18','2015-06-30 01:29:35',1,1),(10,'SS304CUT','SS-304  CUTTINGS',0,0,0,1,'2015-05-26 12:47:26','2015-05-26 12:47:26',1,1),(11,'SS316CUT','SS-316  CUTTINGS',0,0,0,1,'2015-05-26 12:47:36','2015-05-26 12:47:36',1,1),(12,'ALUMNCUT','ALUMINUM  CUTTING',0,0,0,1,'2015-05-26 12:50:35','2015-05-26 12:50:35',1,1),(13,'MATLABOR','LABOR ONLY, MAT.  FROM CUSTOMER',0,0,0,1,'2015-05-26 12:52:17','2015-05-26 12:52:17',1,1),(14,'COPPRCUT','COPPER  CUTTINGS',0,0,0,1,'2015-05-26 12:52:27','2015-05-26 12:52:27',1,1),(22,'AE123456','ALU EMBRO',1,5,3,1,'2015-06-30 02:26:00','2015-07-06 06:01:10',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_branch_inventory`
--

LOCK TABLES `product_branch_inventory` WRITE;
/*!40000 ALTER TABLE `product_branch_inventory` DISABLE KEYS */;
INSERT INTO `product_branch_inventory` VALUES (1,1,1,0,0,0),(2,2,1,0,0,0),(3,3,1,0,0,0),(4,1,2,0,0,0),(5,2,2,0,0,0),(6,3,2,0,0,0),(7,1,3,0,0,0),(8,2,3,0,0,0),(9,3,3,0,0,0),(10,1,4,0,0,0),(11,2,4,0,0,0),(12,3,4,0,0,0),(13,1,5,0,0,0),(14,2,5,0,0,0),(15,3,5,0,0,0),(16,1,6,0,0,0),(17,2,6,0,0,0),(18,3,6,0,0,0),(19,1,7,0,0,0),(20,2,7,0,0,0),(21,3,7,0,0,0),(22,1,8,0,0,0),(23,2,8,0,0,0),(24,3,8,0,0,0),(25,1,9,0,0,0),(26,2,9,0,0,0),(27,3,9,0,0,0),(28,1,10,0,0,0),(29,2,10,0,0,0),(30,3,10,0,0,0),(31,1,11,0,0,0),(32,2,11,0,0,0),(33,3,11,0,0,0),(34,1,12,0,0,0),(35,2,12,0,0,0),(36,3,12,0,0,0),(37,1,13,0,0,0),(38,2,13,0,0,0),(39,3,13,0,0,0),(40,1,14,0,0,0),(41,2,14,0,0,0),(42,3,14,0,0,0),(43,4,1,0,0,0),(44,4,2,0,0,0),(45,4,3,0,0,0),(46,4,4,0,0,0),(47,4,5,0,0,0),(48,4,6,0,0,0),(49,4,7,0,0,0),(50,4,8,0,0,0),(51,4,9,0,0,0),(52,4,10,0,0,0),(53,4,11,0,0,0),(54,4,12,0,0,0),(55,4,13,0,0,0),(56,4,14,0,0,0),(61,6,1,0,0,0),(62,6,2,0,0,0),(63,6,3,0,0,0),(64,6,4,0,0,0),(65,6,5,0,0,0),(66,6,6,0,0,0),(67,6,7,0,0,0),(68,6,8,0,0,0),(69,6,9,0,0,0),(70,6,10,0,0,0),(71,6,11,0,0,0),(72,6,12,0,0,0),(73,6,13,0,0,0),(74,6,14,0,0,0),(76,1,22,0,0,0),(77,2,22,0,0,0),(78,3,22,0,0,0),(79,4,22,0,0,0),(80,6,22,0,0,0);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`purchase_return_head_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_return_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('PURCHASE RETURN HEAD',NEW.`id`);
    END IF;
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('PURCHASE RETURN HEAD',OLD.`id`,1);
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
  `description` varchar(45) DEFAULT '',
  `memo` varchar(150) DEFAULT '',
  `qty_released` varchar(10) DEFAULT '0',
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`release_detail_AFTER_UPDATE` AFTER UPDATE ON `release_detail` 
FOR EACH ROW
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
-- Dumping data for table `release_head`
--

LOCK TABLES `release_head` WRITE;
/*!40000 ALTER TABLE `release_head` DISABLE KEYS */;
/*!40000 ALTER TABLE `release_head` ENABLE KEYS */;
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbs_hitop`.`return_head_BEFORE_UPDATE` BEFORE UPDATE ON `return_head` 
FOR EACH ROW
BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('CUSTOMER RETURN HEAD',NEW.`id`);
    END IF;
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('CUSTOMER RETURN HEAD',OLD.`id`,1);
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
	IF(NEW.`is_for_branch` = 1) THEN
		CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'DELIVERY DETAIL');
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
		ELSE
			SET @table_detail_name := 'CUSTOMER DETAIL';
            SET @qty := @qty * -1;
        END IF;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,@table_detail_name);
    ELSEIF (OLD.`product_id` <> NEW.`product_id`) THEN
		IF(OLD.`is_for_branch` = 1) THEN
			CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'DELIVERY DETAIL');
		END IF;
        
        IF(NEW.`is_for_branch` = 1 ) THEN
			CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'DELIVERY DETAIL');
        END IF;
	ELSEIF (OLD.`quantity` <> NEW.`quantity` AND NEW.`is_for_branch` = 1) THEN
		SET @qty := (NEW.`quantity` - OLD.`quantity`) * -1;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'DELIVERY DETAIL');
	ELSEIF(OLD.`is_for_branch` <> NEW.`is_for_branch`) THEN
		IF(OLD.`is_for_branch` = 1) THEN
			CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'DELIVERY DETAIL');
        END IF;
        
        IF(NEW.`is_for_branch` = 1) THEN
			CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'DELIVERY DETAIL');
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
	IF(OLD.`is_for_branch` = 1) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'DELIVERY DETAIL');
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
INSERT INTO `subgroup` VALUES (1,'A','ANGEL BEAR',1,'2015-05-20 00:00:00','2015-06-30 02:47:44',1,1),(2,'C','COIL',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(3,'E','EMBROSSED / CHECKERED PLATE',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(4,'F','FLAT BAR',1,'2015-05-20 00:00:00','2015-05-20 00:00:00',1,1),(5,'H','HEXAGON BAR',1,'2015-05-26 12:18:49','2015-05-26 12:18:49',1,1),(6,'J','ROUND TUBE',1,'2015-05-26 12:19:00','2015-05-26 12:19:00',1,1),(7,'K','SQUARE TUBE',1,'2015-05-26 12:19:12','2015-05-26 12:19:12',1,1),(8,'L','RECTANGULAR TUBE',1,'2015-05-26 12:19:26','2015-05-26 12:19:26',1,1),(9,'M','MESH - IMPORTED',1,'2015-05-26 12:19:38','2015-05-26 12:19:38',1,1),(10,'N','PIPE',1,'2015-05-26 12:19:48','2015-05-26 12:19:48',1,1),(11,'P','PERFORATED SHEET',1,'2015-05-26 12:20:07','2015-05-26 12:20:07',1,1),(12,'Q','SQUARE BAR',1,'2015-05-26 12:20:26','2015-05-26 12:20:26',1,1),(13,'R','ROUND BAR',1,'2015-05-26 12:20:41','2015-05-26 12:20:41',1,1),(14,'S','SHEETS AND PLATES - PLAIN',1,'2015-05-26 12:20:54','2015-05-26 12:20:54',1,1),(15,'U','WIRE',1,'2015-05-26 12:21:04','2015-05-26 12:21:04',1,1),(16,'V','WIELDING ROD / ELECTRODE',1,'2015-05-26 12:21:27','2015-05-26 12:21:27',1,1),(17,'W','WEDLED WIRE SCREEN - IMPORTED',1,'2015-05-26 12:21:47','2015-05-26 12:21:47',1,1),(18,'X','EXPANDED METAL',1,'2015-05-26 12:22:21','2015-05-26 12:22:21',1,1),(19,'Z','ZINGA',0,'2015-06-30 02:54:54','2015-06-30 02:56:08',1,1),(20,'Z','ZINGA',1,'2015-06-30 02:56:17','2015-06-30 02:56:17',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'01','Lawrence Pena','superadmin','83703b5229462cb6bfaf425152e46a8c','09263188835',1,1,1,'2015-05-19 00:00:00','2015-05-19 00:00:00',1,1),(3,'02','Gian Egamino','gegamino','f3e97dcba0a308db57b1aeaee5a43d4c','09263188835',1,1,0,'2015-05-22 04:56:20','2015-07-06 05:42:10',1,1),(5,'04','Kryzza Garra','kryzza','f3e97dcba0a308db57b1aeaee5a43d4c','09263188835',1,1,1,'2015-05-23 06:31:47','2015-07-06 05:26:20',1,1),(6,'05','Enerick Pangilinan','enerick','f3e97dcba0a308db57b1aeaee5a43d4c','12345678',1,1,1,'2015-06-30 03:52:24','2015-07-06 12:00:15',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=5410 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permission`
--

LOCK TABLES `user_permission` WRITE;
/*!40000 ALTER TABLE `user_permission` DISABLE KEYS */;
INSERT INTO `user_permission` VALUES (1,1,1,100),(2301,1,6,0),(2302,2,6,0),(2303,3,6,0),(2304,4,6,0),(2305,6,6,0),(5073,1,5,101),(5074,1,5,102),(5075,1,5,103),(5076,1,5,104),(5077,1,5,105),(5078,1,5,106),(5079,1,5,107),(5080,1,5,108),(5081,1,5,109),(5082,1,5,110),(5083,1,5,111),(5084,1,5,112),(5085,1,5,113),(5086,1,5,114),(5087,1,5,115),(5088,1,5,116),(5089,1,5,117),(5090,1,5,118),(5091,1,5,119),(5092,1,5,120),(5093,1,5,121),(5094,1,5,131),(5095,1,5,132),(5096,1,5,133),(5097,1,5,136),(5098,1,5,137),(5099,1,5,138),(5100,1,5,139),(5101,1,5,140),(5102,1,5,141),(5103,1,5,142),(5104,1,5,143),(5105,1,5,144),(5106,1,5,145),(5107,1,5,156),(5108,1,5,157),(5109,1,5,158),(5110,1,5,159),(5111,1,5,160),(5112,1,5,161),(5113,1,5,162),(5114,1,5,163),(5115,1,5,164),(5116,1,5,165),(5117,1,5,171),(5118,1,5,172),(5119,1,5,173),(5120,1,5,176),(5121,1,5,177),(5122,1,5,178),(5123,1,5,179),(5124,1,5,180),(5125,1,5,181),(5126,1,5,191),(5127,1,5,192),(5128,1,5,193),(5129,1,5,194),(5130,1,5,195),(5131,1,5,196),(5132,1,5,199),(5133,1,5,200),(5134,1,5,201),(5135,1,5,202),(5136,1,5,203),(5137,1,5,211),(5138,1,5,212),(5139,1,5,213),(5140,2,5,101),(5141,2,5,102),(5142,2,5,103),(5143,2,5,104),(5144,2,5,105),(5145,2,5,106),(5146,2,5,107),(5147,2,5,108),(5148,2,5,109),(5149,2,5,110),(5150,2,5,111),(5151,2,5,112),(5152,2,5,113),(5153,2,5,114),(5154,2,5,115),(5155,2,5,116),(5156,2,5,117),(5157,2,5,118),(5158,2,5,119),(5159,2,5,120),(5160,2,5,121),(5161,2,5,131),(5162,2,5,132),(5163,2,5,133),(5164,2,5,136),(5165,2,5,137),(5166,2,5,138),(5167,2,5,139),(5168,2,5,140),(5169,2,5,141),(5170,2,5,142),(5171,2,5,143),(5172,2,5,144),(5173,2,5,145),(5174,2,5,156),(5175,2,5,157),(5176,2,5,158),(5177,2,5,159),(5178,2,5,160),(5179,2,5,161),(5180,2,5,162),(5181,2,5,163),(5182,2,5,164),(5183,2,5,165),(5184,2,5,171),(5185,2,5,172),(5186,2,5,173),(5187,2,5,176),(5188,2,5,177),(5189,2,5,178),(5190,2,5,179),(5191,2,5,180),(5192,2,5,181),(5193,2,5,191),(5194,2,5,192),(5195,2,5,193),(5196,2,5,194),(5197,2,5,195),(5198,2,5,196),(5199,2,5,199),(5200,2,5,200),(5201,2,5,201),(5202,2,5,202),(5203,2,5,203),(5204,2,5,211),(5205,2,5,212),(5206,2,5,213),(5207,3,5,101),(5208,3,5,102),(5209,3,5,103),(5210,3,5,104),(5211,3,5,105),(5212,3,5,106),(5213,3,5,107),(5214,3,5,108),(5215,3,5,109),(5216,3,5,110),(5217,3,5,111),(5218,3,5,112),(5219,3,5,113),(5220,3,5,114),(5221,3,5,115),(5222,3,5,116),(5223,3,5,117),(5224,3,5,118),(5225,3,5,119),(5226,3,5,120),(5227,3,5,121),(5228,3,5,131),(5229,3,5,132),(5230,3,5,133),(5231,3,5,136),(5232,3,5,137),(5233,3,5,138),(5234,3,5,139),(5235,3,5,140),(5236,3,5,141),(5237,3,5,142),(5238,3,5,143),(5239,3,5,144),(5240,3,5,145),(5241,3,5,156),(5242,3,5,157),(5243,3,5,158),(5244,3,5,159),(5245,3,5,160),(5246,3,5,161),(5247,3,5,162),(5248,3,5,163),(5249,3,5,164),(5250,3,5,165),(5251,3,5,171),(5252,3,5,172),(5253,3,5,173),(5254,3,5,176),(5255,3,5,177),(5256,3,5,178),(5257,3,5,179),(5258,3,5,180),(5259,3,5,181),(5260,3,5,191),(5261,3,5,192),(5262,3,5,193),(5263,3,5,194),(5264,3,5,195),(5265,3,5,196),(5266,3,5,199),(5267,3,5,200),(5268,3,5,201),(5269,3,5,202),(5270,3,5,203),(5271,3,5,211),(5272,3,5,212),(5273,3,5,213),(5274,4,5,101),(5275,4,5,102),(5276,4,5,103),(5277,4,5,104),(5278,4,5,105),(5279,4,5,106),(5280,4,5,107),(5281,4,5,108),(5282,4,5,109),(5283,4,5,110),(5284,4,5,111),(5285,4,5,112),(5286,4,5,113),(5287,4,5,114),(5288,4,5,115),(5289,4,5,116),(5290,4,5,117),(5291,4,5,118),(5292,4,5,119),(5293,4,5,120),(5294,4,5,121),(5295,4,5,131),(5296,4,5,132),(5297,4,5,133),(5298,4,5,136),(5299,4,5,137),(5300,4,5,138),(5301,4,5,139),(5302,4,5,140),(5303,4,5,141),(5304,4,5,142),(5305,4,5,143),(5306,4,5,144),(5307,4,5,145),(5308,4,5,156),(5309,4,5,157),(5310,4,5,158),(5311,4,5,159),(5312,4,5,160),(5313,4,5,161),(5314,4,5,162),(5315,4,5,163),(5316,4,5,164),(5317,4,5,165),(5318,4,5,171),(5319,4,5,172),(5320,4,5,173),(5321,4,5,176),(5322,4,5,177),(5323,4,5,178),(5324,4,5,179),(5325,4,5,180),(5326,4,5,181),(5327,4,5,191),(5328,4,5,192),(5329,4,5,193),(5330,4,5,194),(5331,4,5,195),(5332,4,5,196),(5333,4,5,199),(5334,4,5,200),(5335,4,5,201),(5336,4,5,202),(5337,4,5,203),(5338,4,5,211),(5339,4,5,212),(5340,4,5,213),(5341,6,5,101),(5342,6,5,102),(5343,6,5,103),(5344,6,5,104),(5345,6,5,105),(5346,6,5,106),(5347,6,5,107),(5348,6,5,108),(5349,6,5,109),(5350,6,5,110),(5351,6,5,111),(5352,6,5,112),(5353,6,5,113),(5354,6,5,114),(5355,6,5,115),(5356,6,5,116),(5357,6,5,117),(5358,6,5,118),(5359,6,5,119),(5360,6,5,120),(5361,6,5,121),(5362,6,5,131),(5363,6,5,132),(5364,6,5,133),(5365,6,5,136),(5366,6,5,137),(5367,6,5,138),(5368,6,5,139),(5369,6,5,140),(5370,6,5,141),(5371,6,5,142),(5372,6,5,143),(5373,6,5,144),(5374,6,5,145),(5375,6,5,156),(5376,6,5,157),(5377,6,5,158),(5378,6,5,159),(5379,6,5,160),(5380,6,5,161),(5381,6,5,162),(5382,6,5,163),(5383,6,5,164),(5384,6,5,165),(5385,6,5,171),(5386,6,5,172),(5387,6,5,173),(5388,6,5,176),(5389,6,5,177),(5390,6,5,178),(5391,6,5,179),(5392,6,5,180),(5393,6,5,181),(5394,6,5,191),(5395,6,5,192),(5396,6,5,193),(5397,6,5,194),(5398,6,5,195),(5399,6,5,196),(5400,6,5,199),(5401,6,5,200),(5402,6,5,201),(5403,6,5,202),(5404,6,5,203),(5405,6,5,211),(5406,6,5,212),(5407,6,5,213),(5408,1,3,100),(5409,2,3,100);
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

-- Dump completed on 2015-07-07  8:50:21
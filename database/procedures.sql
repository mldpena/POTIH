-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 11, 2016 at 11:26 AM
-- Server version: 5.5.48-cll
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hitopmds_dbs_hitop`
--

DELIMITER $$
--
-- Procedures
--
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
END$$

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
END$$

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
END$$

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
END$$

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
END$$

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
END$$

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
END$$

DELIMITER ;

-- --------------------------------------------------------


--
-- Triggers `branch`
--
DROP TRIGGER IF EXISTS `branch_AFTER_INSERT`;
DELIMITER //
CREATE TRIGGER `branch_AFTER_INSERT` AFTER INSERT ON `branch`
 FOR EACH ROW BEGIN
	CALL process_initialize_branch_inventory(NEW.`id`);
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Triggers `damage_detail`
--
DROP TRIGGER IF EXISTS `damage_detail_BEFORE_DELETE`;
DELIMITER //
CREATE TRIGGER `damage_detail_BEFORE_DELETE` BEFORE DELETE ON `damage_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'DAMAGE DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `damage_detail_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `damage_detail_BEFORE_INSERT` BEFORE INSERT ON `damage_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'DAMAGE DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `damage_detail_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `damage_detail_BEFORE_UPDATE` BEFORE UPDATE ON `damage_detail`
 FOR EACH ROW BEGIN
	IF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'DAMAGE DETAIL');
        CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'DAMAGE DETAIL');
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := (NEW.`quantity` - OLD.`quantity`) * -1;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'DAMAGE DETAIL');
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Triggers `damage_head`
--
DROP TRIGGER IF EXISTS `damage_head_AFTER_UPDATE`;
DELIMITER //
CREATE TRIGGER `damage_head_AFTER_UPDATE` AFTER UPDATE ON `damage_head`
 FOR EACH ROW BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('DAMAGE HEAD',NEW.`id`,-1);
    END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `damage_head_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `damage_head_BEFORE_UPDATE` BEFORE UPDATE ON `damage_head`
 FOR EACH ROW BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('DAMAGE HEAD',NEW.`id`);
    END IF;
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('DAMAGE HEAD',OLD.`id`,1);
    END IF;
    
END
//
DELIMITER ;

-- --------------------------------------------------------

DROP TRIGGER IF EXISTS `inventory_adjust_AFTER_INSERT`;
DELIMITER //
CREATE TRIGGER `inventory_adjust_AFTER_INSERT` AFTER INSERT ON `inventory_adjust`
 FOR EACH ROW BEGIN
	IF(NEW.`status` = 2) THEN
		SET @quantity := NEW.`new_inventory` - NEW.`old_inventory`;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@quantity,NEW.`id`,'INVENTORY ADJUST');
	END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `inventory_adjust_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `inventory_adjust_BEFORE_UPDATE` BEFORE UPDATE ON `inventory_adjust`
 FOR EACH ROW F:BEGIN
	IF(NEW.`status` <> 1 AND NEW.`status` <> OLD.`status` AND NEW.`is_show` = 1) THEN
		IF(OLD.`status` IN(1,3) AND NEW.`status` = 2) THEN
			SET @quantity := NEW.`new_inventory` - NEW.`old_inventory`;
		ELSEIF(OLD.`status` = 2 AND NEW.`status` = 3) THEN
			SET @quantity := NEW.`old_inventory` - NEW.`new_inventory`;
		ELSEIF(OLD.`status` = 1 AND NEW.`status` = 3) THEN
			LEAVE F;
		END IF;
		
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@quantity,NEW.`id`,'INVENTORY ADJUST');
	END IF;
END
//
DELIMITER ;


--
-- Triggers `purchase_head`
--
DROP TRIGGER IF EXISTS `purchase_head_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `purchase_head_BEFORE_INSERT` BEFORE INSERT ON `purchase_head`
 FOR EACH ROW BEGIN
	CALL process_insert_new_name(NEW.`supplier`,2);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `purchase_head_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `purchase_head_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_head`
 FOR EACH ROW BEGIN
	IF(LOWER(OLD.`supplier`) <> LOWER(NEW.`supplier`)) THEN
		DELETE FROM `recent_name` WHERE LOWER(`name`) = LOWER(OLD.`supplier`) AND `type` = 2;
        CALL process_insert_new_name(NEW.`supplier`,2);
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
--
-- Triggers `purchase_receive_detail`
--
DROP TRIGGER IF EXISTS `purchase_receive_detail_BEFORE_DELETE`;
DELIMITER //
CREATE TRIGGER `purchase_receive_detail_BEFORE_DELETE` BEFORE DELETE ON `purchase_receive_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,(-1 * OLD.`quantity`),OLD.`headid`,'RECEIVE DETAIL');
    CALL process_update_receive(OLD.`purchase_detail_id`,(-1) * OLD.`quantity`,'RECEIVE DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `purchase_receive_detail_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `purchase_receive_detail_BEFORE_INSERT` BEFORE INSERT ON `purchase_receive_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,NEW.`quantity`,NEW.`headid`,'RECEIVE DETAIL');
	CALL process_update_receive(NEW.`purchase_detail_id`,NEW.`quantity`,'RECEIVE DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `purchase_receive_detail_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `purchase_receive_detail_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_receive_detail`
 FOR EACH ROW BEGIN
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
END
//
DELIMITER ;

-- --------------------------------------------------------

-- Triggers `purchase_receive_head`
--
DROP TRIGGER IF EXISTS `purchase_receive_head_AFTER_UPDATE`;
DELIMITER //
CREATE TRIGGER `purchase_receive_head_AFTER_UPDATE` AFTER UPDATE ON `purchase_receive_head`
 FOR EACH ROW BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('RECEIVE HEAD',NEW.`id`,-1);
    END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `purchase_receive_head_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `purchase_receive_head_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_receive_head`
 FOR EACH ROW BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('RECEIVE HEAD',NEW.`id`);
    END IF;
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('RECEIVE HEAD',OLD.`id`,1);
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Triggers `purchase_return_detail`
--
DROP TRIGGER IF EXISTS `purchase_return_detail_BEFORE_DELETE`;
DELIMITER //
CREATE TRIGGER `purchase_return_detail_BEFORE_DELETE` BEFORE DELETE ON `purchase_return_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'PURCHASE RETURN DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `purchase_return_detail_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `purchase_return_detail_BEFORE_INSERT` BEFORE INSERT ON `purchase_return_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'PURCHASE RETURN DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `purchase_return_detail_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `purchase_return_detail_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_return_detail`
 FOR EACH ROW BEGIN
	IF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'PURCHASE RETURN DETAIL');
        CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'PURCHASE RETURN DETAIL');
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := (NEW.`quantity` - OLD.`quantity`) * -1;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'PURCHASE RETURN DETAIL');
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Triggers `purchase_return_head`
--
DROP TRIGGER IF EXISTS `purchase_return_head_AFTER_UPDATE`;
DELIMITER //
CREATE TRIGGER `purchase_return_head_AFTER_UPDATE` AFTER UPDATE ON `purchase_return_head`
 FOR EACH ROW BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('PURCHASE RETURN HEAD',OLD.`id`,-1);
    END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `purchase_return_head_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `purchase_return_head_BEFORE_INSERT` BEFORE INSERT ON `purchase_return_head`
 FOR EACH ROW BEGIN
	CALL process_insert_new_name(NEW.`supplier`,2);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `purchase_return_head_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `purchase_return_head_BEFORE_UPDATE` BEFORE UPDATE ON `purchase_return_head`
 FOR EACH ROW BEGIN
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
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Triggers `release_detail`
--
DROP TRIGGER IF EXISTS `release_detail_BEFORE_DELETE`;
DELIMITER //
CREATE TRIGGER `release_detail_BEFORE_DELETE` BEFORE DELETE ON `release_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,'RELEASE DETAIL');
    CALL process_update_receive(OLD.`release_order_detail_id`,(-1) * OLD.`quantity`,'RELEASE DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `release_detail_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `release_detail_BEFORE_INSERT` BEFORE INSERT ON `release_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,'RELEASE DETAIL');
	CALL process_update_receive(NEW.`release_order_detail_id`,NEW.`quantity`,'RELEASE DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `release_detail_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `release_detail_BEFORE_UPDATE` BEFORE UPDATE ON `release_detail`
 FOR EACH ROW BEGIN
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
END
//
DELIMITER ;

-- --------------------------------------------------------


--
-- Triggers `release_head`
--
DROP TRIGGER IF EXISTS `release_head_AFTER_UPDATE`;
DELIMITER //
CREATE TRIGGER `release_head_AFTER_UPDATE` AFTER UPDATE ON `release_head`
 FOR EACH ROW BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('RELEASE HEAD',NEW.`id`,-1);
    END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `release_head_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `release_head_BEFORE_UPDATE` BEFORE UPDATE ON `release_head`
 FOR EACH ROW BEGIN
	IF(NEW.`is_show` <> 1) THEN
		CALL process_compute_inventory_for_head('RELEASE HEAD',NEW.`id`);
    END IF;
    
    IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('RELEASE HEAD',OLD.`id`,1);
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Triggers `release_order_head`
--
DROP TRIGGER IF EXISTS `release_order_head_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `release_order_head_BEFORE_INSERT` BEFORE INSERT ON `release_order_head`
 FOR EACH ROW BEGIN
	CALL process_insert_new_name(NEW.`customer`,1);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `release_order_head_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `release_order_head_BEFORE_UPDATE` BEFORE UPDATE ON `release_order_head`
 FOR EACH ROW BEGIN
	IF(LOWER(OLD.`customer`) <> LOWER(NEW.`customer`)) THEN
		DELETE FROM `recent_name` WHERE LOWER(`name`) = LOWER(OLD.`customer`) AND `type` = 1;
        CALL process_insert_new_name(NEW.`customer`,1);
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `return_detail`
--

--
-- Triggers `return_detail`
--
DROP TRIGGER IF EXISTS `return_detail_BEFORE_DELETE`;
DELIMITER //
CREATE TRIGGER `return_detail_BEFORE_DELETE` BEFORE DELETE ON `return_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(OLD.`product_id`,(-1 * OLD.`quantity`),OLD.`headid`,'CUSTOMER RETURN DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `return_detail_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `return_detail_BEFORE_INSERT` BEFORE INSERT ON `return_detail`
 FOR EACH ROW BEGIN
	CALL process_compute_inventory_for_detail(NEW.`product_id`,NEW.`quantity`,NEW.`headid`,'CUSTOMER RETURN DETAIL');
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `return_detail_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `return_detail_BEFORE_UPDATE` BEFORE UPDATE ON `return_detail`
 FOR EACH ROW BEGIN
	IF (OLD.`product_id` <> NEW.`product_id`) THEN
		CALL process_compute_inventory_for_detail(OLD.`product_id`,(-1 * OLD.`quantity`),OLD.`headid`,'CUSTOMER RETURN DETAIL');
        CALL process_compute_inventory_for_detail(NEW.`product_id`,NEW.`quantity`,NEW.`headid`,'CUSTOMER RETURN DETAIL');
	ELSEIF (OLD.`quantity` <> NEW.`quantity`) THEN
		SET @qty := NEW.`quantity` - OLD.`quantity`;
		CALL process_compute_inventory_for_detail(NEW.`product_id`,@qty,NEW.`headid`,'CUSTOMER RETURN DETAIL');
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `return_head`
--

--
-- Triggers `return_head`
--
DROP TRIGGER IF EXISTS `return_head_AFTER_UPDATE`;
DELIMITER //
CREATE TRIGGER `return_head_AFTER_UPDATE` AFTER UPDATE ON `return_head`
 FOR EACH ROW BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('CUSTOMER RETURN HEAD',NEW.`id`,-1);
    END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `return_head_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `return_head_BEFORE_INSERT` BEFORE INSERT ON `return_head`
 FOR EACH ROW BEGIN
	CALL process_insert_new_name(NEW.`customer`,1);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `return_head_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `return_head_BEFORE_UPDATE` BEFORE UPDATE ON `return_head`
 FOR EACH ROW BEGIN
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
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `stock_delivery_detail`
--


--
-- Triggers `stock_delivery_detail`
--
DROP TRIGGER IF EXISTS `stock_delivery_detail_BEFORE_DELETE`;
DELIMITER //
CREATE TRIGGER `stock_delivery_detail_BEFORE_DELETE` BEFORE DELETE ON `stock_delivery_detail`
 FOR EACH ROW BEGIN
	SET @inventory_table_name := 'CUSTOMER DETAIL';
    
    IF(OLD.`is_for_branch` = 1) THEN
		SET @inventory_table_name := 'DELIVERY DETAIL';
	END IF;
    
    CALL process_compute_inventory_for_detail(OLD.`product_id`,OLD.`quantity`,OLD.`headid`,@inventory_table_name);
	
	IF(OLD.`request_detail_id` <> 0) THEN
		CALL process_update_receive(OLD.`request_detail_id`,(-1 * OLD.`quantity`),'REQUEST DETAIL');
	END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `stock_delivery_detail_BEFORE_INSERT`;
DELIMITER //
CREATE TRIGGER `stock_delivery_detail_BEFORE_INSERT` BEFORE INSERT ON `stock_delivery_detail`
 FOR EACH ROW BEGIN
	SET @inventory_table_name := 'CUSTOMER DETAIL';
    
    IF(NEW.`is_for_branch` = 1) THEN
		SET @inventory_table_name := 'DELIVERY DETAIL';
	END IF;
    
	CALL process_compute_inventory_for_detail(NEW.`product_id`,(-1 * NEW.`quantity`),NEW.`headid`,@inventory_table_name);
	
	IF(NEW.`request_detail_id` <> 0) THEN
		CALL process_update_receive(NEW.`request_detail_id`,NEW.`quantity`,'REQUEST DETAIL');
	END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `stock_delivery_detail_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `stock_delivery_detail_BEFORE_UPDATE` BEFORE UPDATE ON `stock_delivery_detail`
 FOR EACH ROW BEGIN
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
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Triggers `stock_delivery_head`
--
DROP TRIGGER IF EXISTS `stock_delivery_head_AFTER_UPDATE`;
DELIMITER //
CREATE TRIGGER `stock_delivery_head_AFTER_UPDATE` AFTER UPDATE ON `stock_delivery_head`
 FOR EACH ROW BEGIN
	IF(DATE(OLD.`entry_date`) <> DATE(NEW.`entry_date`)) THEN
		CALL process_recompute_transaction_summary('DELIVERY HEAD',NEW.`id`,-1);
    END IF;
    
    IF(DATE(OLD.`delivery_receive_date`) <> DATE(NEW.`delivery_receive_date`)) THEN
		CALL process_recompute_transaction_summary('DELIVERY RECEIVE HEAD',NEW.`id`,-1);
    END IF;
    
    IF(DATE(OLD.`customer_receive_date`) <> DATE(NEW.`customer_receive_date`)) THEN
		CALL process_recompute_transaction_summary('CUSTOMER RECEIVE HEAD',NEW.`id`,-1);
    END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `stock_delivery_head_BEFORE_UPDATE`;
DELIMITER //
CREATE TRIGGER `stock_delivery_head_BEFORE_UPDATE` BEFORE UPDATE ON `stock_delivery_head`
 FOR EACH ROW BEGIN
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
END
//
DELIMITER ;

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

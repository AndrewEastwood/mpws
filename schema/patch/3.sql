ALTER TABLE `shop_products` ADD `PrevPrice` DECIMAL(10,2) NULL DEFAULT NULL AFTER `Price`;

DROP TRIGGER IF EXISTS `backupProductPrice`;
DROP TRIGGER IF EXISTS `backupProductPrevPrice`;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=CURRENT_USER*/ /*!50003 TRIGGER `backupProductPrevPrice` BEFORE UPDATE ON `shop_products`
 FOR EACH ROW IF NEW.PrevPrice != OLD.PrevPrice THEN
    INSERT INTO shop_productPrices SET CustomerID = NEW.CustomerID, ProductID = NEW.ID, Price = OLD.PrevPrice, DateCreated = NOW();
END IF */;;
DELIMITER ;
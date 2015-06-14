ALTER TABLE `shop_products` CHANGE `ExternalKey` `ExternalKey` VARCHAR(600) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE `shop_products` CHANGE `Model` `Model` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;
ALTER TABLE `shop_products` CHANGE `SKU` `SKU` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;
ALTER TABLE `shop_products` CHANGE `ExternalKey` `ExternalKey` VARCHAR(600) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '';
ALTER TABLE `mpws_customer` CHANGE `SnapshotURL` `SnapshotURL` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '', CHANGE `SitemapURL` `SitemapURL` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '';
ALTER TABLE `shop_products` CHANGE `Status` `Status` ENUM('DISCOUNT','ACTIVE','WAITING','PREORDER','DEFECT','ARCHIVED','REMOVED') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE';

ALTER TABLE `shop_origins` CHANGE `Name` `Name` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `shop_products` CHANGE `Model` `Model` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `shop_products` CHANGE `SearchText` `SearchText` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `shop_products` CHANGE `Name` `Name` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `mpws_permissions` CHANGE `CanAdmin` `CanAdmin`
    TINYINT(1) NOT NULL DEFAULT '0', CHANGE `CanCreate` `CanCreate`
    TINYINT(1) NOT NULL DEFAULT '0', CHANGE `CanEdit` `CanEdit`
    TINYINT(1) NOT NULL DEFAULT '0', CHANGE `CanViewReports` `CanViewReports`
    TINYINT(1) NOT NULL DEFAULT '0', CHANGE `CanAddUsers` `CanAddUsers`
    TINYINT(1) NOT NULL DEFAULT '0';


ALTER TABLE `shop_settingsAlerts` CHANGE `NewProductAdded` `NewProductAdded` INT NULL DEFAULT NULL;
ALTER TABLE `shop_settingsAlerts` CHANGE `ProductPriceGoesDown` `ProductPriceGoesDown` INT NULL DEFAULT NULL;
ALTER TABLE `shop_settingsAlerts` CHANGE `PromoIsStarted` `PromoIsStarted` INT NULL DEFAULT NULL;
ALTER TABLE `shop_settingsAlerts` CHANGE `AddedNewOrigin` `AddedNewOrigin` INT NULL DEFAULT NULL;
ALTER TABLE `shop_settingsAlerts` CHANGE `AddedNewCategory` `AddedNewCategory` INT NULL DEFAULT NULL;
ALTER TABLE `shop_settingsAlerts` CHANGE `AddedNewDiscountedProduct` `AddedNewDiscountedProduct` INT NULL DEFAULT NULL;

ALTER TABLE `shop_settingsAlerts` ADD INDEX(`NewProductAdded`);
ALTER TABLE `shop_settingsAlerts` ADD INDEX(`ProductPriceGoesDown`);
ALTER TABLE `shop_settingsAlerts` ADD INDEX(`PromoIsStarted`);
ALTER TABLE `shop_settingsAlerts` ADD INDEX(`AddedNewOrigin`);
ALTER TABLE `shop_settingsAlerts` ADD INDEX(`AddedNewCategory`);
ALTER TABLE `shop_settingsAlerts` ADD INDEX(`AddedNewDiscountedProduct`);

ALTER TABLE `shop_settingsAlerts` ADD
    FOREIGN KEY (`NewProductAdded`) REFERENCES `mpws_light`.`mpws_emails`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `shop_settingsAlerts` ADD
    FOREIGN KEY (`ProductPriceGoesDown`) REFERENCES `mpws_light`.`mpws_emails`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `shop_settingsAlerts` ADD
    FOREIGN KEY (`PromoIsStarted`) REFERENCES `mpws_light`.`mpws_emails`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `shop_settingsAlerts` ADD
    FOREIGN KEY (`AddedNewOrigin`) REFERENCES `mpws_light`.`mpws_emails`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `shop_settingsAlerts` ADD
    FOREIGN KEY (`AddedNewCategory`) REFERENCES `mpws_light`.`mpws_emails`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `shop_settingsAlerts` ADD
    FOREIGN KEY (`AddedNewDiscountedProduct`) REFERENCES `mpws_light`.`mpws_emails`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
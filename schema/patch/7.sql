ALTER TABLE `mpws_permissions` ADD `Others` TEXT NULL AFTER `CanMaintain`;

ALTER TABLE `shop_settingsAddress` CHANGE `Status` `Status` ENUM('ACTIVE','DISABLED') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'ACTIVE';
ALTER TABLE `shop_settingsAddress` CHANGE `Phone1Label` `Phone1Label` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    CHANGE `Phone2Label` `Phone2Label` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    CHANGE `Phone3Label` `Phone3Label` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    CHANGE `Phone4Label` `Phone4Label` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
    CHANGE `Phone5Label` `Phone5Label` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;

ALTER TABLE `shop_productAttributes` ADD `DateCreated` DATETIME NOT NULL ;

ALTER TABLE `mpws_subscribers` ADD `Email` VARCHAR(300) NOT NULL AFTER `AccountID`;
ALTER TABLE `mpws_subscribers` ADD `Token` VARCHAR(32) NOT NULL AFTER `ContentType`;
ALTER TABLE `mpws_subscribers` CHANGE `Email` `Email` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_bin NULL;
ALTER TABLE `mpws_subscribers` CHANGE `Enabled` `Status` ENUM('ACTIVE','REMOVED') NOT NULL;
ALTER TABLE `mpws_subscribers` CHANGE `DateUpdated` `LastSent` DATETIME NOT NULL AFTER `Status`;
ALTER TABLE `mpws_subscribers` CHANGE `AccountID` `AccountID` INT(11) NULL;

DROP PROCEDURE `getShopCatalogLocation`; CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCatalogLocation`(IN `catid` INT, IN `cid` INT UNSIGNED ZEROFILL) NOT DETERMINISTIC READS SQL DATA SQL SECURITY DEFINER BEGIN SELECT T2.ID, T2.CustomerID, T2.Name, T2.ExternalKey FROM ( SELECT @r AS _id, (SELECT @r := ParentID FROM shop_categories WHERE ID = _id) AS ParentID, @l := @l + 1 AS lvl FROM (SELECT @r := catid, @l := 0) vars, shop_categories h WHERE @r <> 0 ) T1 JOIN shop_categories T2 ON T1._id = T2.ID WHERE T2.CustomerID = cid ORDER BY T1.lvl DESC; END
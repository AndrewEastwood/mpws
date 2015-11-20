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
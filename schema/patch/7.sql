ALTER TABLE `mpws_permissions` ADD `Others` TEXT NULL AFTER `CanMaintain`;
ALTER TABLE `mpws_subscribers` CHANGE `ContentType` `Type` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE `mpws_subscribers` ADD `Token` VARCHAR(32) NOT NULL AFTER `Enabled`,
ADD `EMail` VARCHAR(100) NOT NULL AFTER `Token`,
ADD `Status` ENUM('TEMP','ACTIVE','REMOVED') NOT NULL AFTER `EMail`;
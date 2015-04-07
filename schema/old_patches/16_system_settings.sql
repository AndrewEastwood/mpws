ALTER TABLE `mpws_customerSettings` ADD `Title` VARCHAR(200) NOT NULL DEFAULT 'no title' AFTER `CustomerID`, ADD `Plugins` VARCHAR(500) NOT NULL DEFAULT 'system' AFTER `Title`, ADD `Lang` VARCHAR(50) NOT NULL DEFAULT 'en-US' AFTER `Plugins`, ADD `Locale` VARCHAR(10) NOT NULL DEFAULT 'en' AFTER `Lang`, ADD `Host` VARCHAR(100) NOT NULL DEFAULT 'localhost' AFTER `Locale`, ADD `Schema` VARCHAR(10) NOT NULL DEFAULT 'http' AFTER `Host`;
ALTER TABLE `mpws_customerSettings`
  DROP `Property`,
  DROP `Value`,
  DROP `Status`;
  ALTER TABLE `mpws_customerSettings` ADD `HomePage` VARCHAR(200) NOT NULL DEFAULT 'localhost' AFTER `Schema`;
  ALTER TABLE `mpws_customerSettings` CHANGE `Schema` `Protocol` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'http';
  UPDATE  `mpws_patches` SET LastPatchNo=16;
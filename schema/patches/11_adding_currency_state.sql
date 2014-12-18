ALTER TABLE  `shop_currency` ADD  `Status` ENUM(  'ACTIVE',  'REMOVED',  '',  '' ) NOT NULL DEFAULT  'ACTIVE' AFTER  `Rate` ;
ALTER TABLE `shop_orders` CHANGE `CurrencyName` `CurrencyBaseName` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE `shop_orders` ADD `CurrencyName` VARCHAR(10) NOT NULL AFTER `CurrencyRate`;
UPDATE `shop_orders` SET `CurrencyName` = `CurrencyBaseName`;
UPDATE `shop_orders` SET `CurrencyBaseName` = (SELECT `Value` FROM `shop_settings` WHERE `Property` = 'DBPriceCurrencyType');
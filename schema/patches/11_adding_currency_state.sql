ALTER TABLE `shop_currency` ADD  `Status` ENUM(  'ACTIVE',  'REMOVED' ) NOT NULL DEFAULT  'ACTIVE' AFTER  `Rate` ;
ALTER TABLE `shop_orders` CHANGE `CurrencyName` `ExchangeRateID` INT NOT NULL;
ALTER TABLE `shop_orders` CHANGE `CurrencyRate` `CustomerCurrencyRate` DECIMAL(10,2) NOT NULL;
ALTER TABLE `shop_orders` ADD INDEX(`ExchangeRateID`);
UPDATE `shop_orders` SET `ExchangeRateID` = (SELECT `ID` FROM `shop_currency` WHERE `CurrencyA` = (SELECT `Value` FROM `shop_settings` WHERE `Property` = 'DBPriceCurrencyType'));
UPDATE `shop_orders` SET `CustomerCurrencyRate` = 1;
ALTER TABLE `shop_orders` ADD FOREIGN KEY (`ExchangeRateID`) REFERENCES `shop_currency`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `shop_orders` ADD `CustomerCurrencyName` VARCHAR(10) NOT NULL AFTER `CustomerCurrencyRate`;
UPDATE `shop_orders` SET `CustomerCurrencyName` = (SELECT `CurrencyA` FROM `shop_currency` WHERE `CurrencyA` = (SELECT `Value` FROM `shop_settings` WHERE `Property` = 'DBPriceCurrencyType'));
UPDATE  `mpws_patches` SET LastPatchNo=11
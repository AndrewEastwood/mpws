ALTER TABLE  `shop_orders` ADD  `CurrencyName` VARCHAR( 10 ) NOT NULL AFTER  `DeliveryID` ,
ADD  `CurrencyRate` DECIMAL( 10, 2 ) NOT NULL AFTER  `CurrencyName` ;
UPDATE `shop_orders` SET `CurrencyName` = (SELECT Value FROM `shop_settings` WHERE `Property` = 'DBPriceCurrencyType') WHERE `CurrencyName` = '';
UPDATE `shop_orders` SET `CurrencyRate` = 1.0 WHERE `CurrencyRate` = 0;

UPDATE  `mpws_patches` SET LastPatchNo=9
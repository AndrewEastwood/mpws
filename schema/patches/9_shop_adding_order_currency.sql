ALTER TABLE  `shop_orders` ADD  `CurrencyName` VARCHAR( 10 ) NOT NULL AFTER  `DeliveryID` ,
ADD  `CurrencyRate` DECIMAL( 10, 2 ) NOT NULL AFTER  `CurrencyName` ;
ALTER TABLE `shop_currency` DROP `RateA`;
ALTER TABLE `shop_currency` CHANGE `RateB` `Rate` DECIMAL( 10, 2 ) NOT NULL;
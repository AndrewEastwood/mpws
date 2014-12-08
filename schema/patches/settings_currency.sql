ALTER TABLE `shop_currency` CHANGE `Currency` `CurrencyA` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE `shop_currency` CHANGE `Rate` `RateA` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE  `shop_currency` ADD  `CurrencyB` VARCHAR( 10 ) NOT NULL AFTER  `RateA` ,
ADD  `RateB` DECIMAL NOT NULL AFTER  `CurrencyB` ;
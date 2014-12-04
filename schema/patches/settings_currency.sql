ALTER TABLE  `shop_currency` ADD  `CurrencyB` VARCHAR( 10 ) NOT NULL AFTER  `RateA` ,
ADD  `RateB` DECIMAL NOT NULL AFTER  `CurrencyB` ;

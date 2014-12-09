ALTER TABLE  `shop_currency` DROP  `IsMain` ;
ALTER TABLE  `shop_currency` DROP  `DateLastAccess` ;
ALTER TABLE  `shop_currency` DROP  `Status` ;
ALTER TABLE  `shop_settings` CHANGE  `Type`  `Type` ENUM(  'ADDRESS',  'ALERTS',  'EXCHANGERATES',  'OPENHOURS',  'FORMORDER',  'WEBSITE',  'MISC',  'PRODUCT',  'SEO' ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT  'MISC';
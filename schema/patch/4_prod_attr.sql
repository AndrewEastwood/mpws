ALTER TABLE `shop_productAttributes`
  CHANGE `Attribute` `Attribute` ENUM('IMAGE', 'ISBN', 'EXPIRE', 'TAGS', 'VIDEO'
  , 'WARRANTY', 'BANNER_LARGE', 'BANNER_MEDIUM', 'BANNER_SMALL', 'BANNER_MICRO'
  , 'BANNER_TEXT_LINE1', 'BANNER_TEXT_LINE2', 'PROMO_TEXT') CHARACTER SET utf8
  COLLATE utf8_bin NOT NULL;

ALTER TABLE `shop_products`
  ADD `ShowBanner` BOOLEAN NOT NULL DEFAULT false after `IsFeatured`; 

ALTER TABLE `shop_products` ADD `SearchText` VARCHAR(300) NOT NULL AFTER `Status`;

UPDATE `shop_products`
LEFT JOIN `shop_origins` ON `shop_products`.`OriginID` = `shop_origins`.`ID`
SET `SearchText` = LOWER(CONCAT_WS(' ', `shop_products`.`Name`, `shop_origins`.`Name`, `shop_products`.`Model`));
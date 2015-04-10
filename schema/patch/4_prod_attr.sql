ALTER TABLE `shop_productattributes`
  CHANGE `attribute` `attribute` ENUM('IMAGE', 'ISBN', 'EXPIRE', 'TAGS', 'VIDEO'
  , 'WARRANTY', 'BANNER_LARGE', 'BANNER_MEDIUM', 'BANNER_SMALL', 'BANNER_MICRO'
  , 'BANNER_TEXT_LINE1', 'BANNER_TEXT_LINE2', 'PROMO_TEXT') CHARACTER SET utf8
  COLLATE utf8_bin NOT NULL;

ALTER TABLE `shop_products`
  ADD `showbanner` BOOLEAN NOT NULL DEFAULT false after `isfeatured`; 
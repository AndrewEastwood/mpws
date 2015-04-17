ALTER TABLE `mpws_customer` ADD `SnapshotURL` VARCHAR(300) NOT NULL AFTER `Plugins`;
ALTER TABLE `mpws_customer` ADD `SitemapURL` VARCHAR(500) NOT NULL AFTER `SnapshotURL`;
ALTER TABLE `shop_productAttributes` CHANGE `Attribute` `Attribute` ENUM (
    'IMAGE'
    ,'ISBN'
    ,'EXPIRE'
    ,'TAGS'
    ,'VIDEO'
    ,'WARRANTY'
    ,'BANNER_LARGE'
    ,'BANNER_MEDIUM'
    ,'BANNER_SMALL'
    ,'BANNER_MICRO'
    ,'BANNER_TEXT_LINE1'
    ,'BANNER_TEXT_LINE2'
    ,'PROMO_TEXT'
    ,'SKU'
    ) CHAR

SET utf8 COLLATE utf8_bin NOT NULL;
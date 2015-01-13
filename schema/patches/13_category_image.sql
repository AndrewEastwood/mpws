ALTER TABLE `shop_categories` ADD `Image` VARCHAR(200) NOT NULL AFTER `Description`;
UPDATE  `mpws_patches` SET LastPatchNo=13;
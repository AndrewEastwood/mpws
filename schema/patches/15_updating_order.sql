ALTER TABLE `shop_orders` DROP FOREIGN KEY `shop_orders_ibfk_3`;
ALTER TABLE `shop_orders` CHANGE `AccountAddressesID` `UserAddressesID` INT(11) NOT NULL;
ALTER TABLE `shop_orders` ADD FOREIGN KEY (`UserAddressesID`) REFERENCES `mpws_light`.`mpws_userAddresses`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
UPDATE  `mpws_patches` SET LastPatchNo=15;
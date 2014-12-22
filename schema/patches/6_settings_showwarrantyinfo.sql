INSERT INTO  `shop_settings` (
`ID` ,
`CustomerID` ,
`Property` ,
`Label` ,
`Value` ,
`Status` ,
`Type` ,
`DateCreated` ,
`DateUpdated`
)
VALUES (
NULL ,  '1',  'ShowWarrantyInfo',  '',  '',  'ACTIVE',  'PRODUCT',  '2014-10-07 02:51:18',  '2014-10-07 02:51:18'
);

INSERT INTO  `shop_settings` (
`ID` ,
`CustomerID` ,
`Property` ,
`Label` ,
`Value` ,
`Status` ,
`Type` ,
`DateCreated` ,
`DateUpdated`
)
VALUES (
NULL ,  '1',  'ShowContacts',  '',  '',  'ACTIVE',  'PRODUCT',  '2014-10-07 02:51:18',  '2014-10-07 02:51:18'
);

DELETE FROM `shop_settings` WHERE `shop_settings`.`Property` = 'ContactUID';

ALTER TABLE `shop_settings` CHANGE `Value` `Value` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;
UPDATE  `mpws_patches` SET LastPatchNo=6
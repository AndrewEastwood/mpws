INSERT INTO  `mpws_light`.`shop_settings` (
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

INSERT INTO  `mpws_light`.`shop_settings` (
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

DELETE FROM `mpws_light`.`shop_settings` WHERE `shop_settings`.`Property` = 'ContactUID';
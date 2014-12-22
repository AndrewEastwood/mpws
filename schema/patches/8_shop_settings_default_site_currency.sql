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
NULL ,  '1',  'SiteDefaultPriceCurrencyType', NULL , NULL ,  'ACTIVE',  'WEBSITE',  NOW(),  NOW()
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
NULL ,  '1',  'ShowSiteCurrencySelector', NULL , "0" ,  'ACTIVE',  'WEBSITE',  NOW(),  NOW()
);

UPDATE  `mpws_patches` SET LastPatchNo=8
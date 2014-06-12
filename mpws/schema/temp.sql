-- get category available specs

SELECT shop_categories.Name, shop_specFields.FieldName
FROM  `shop_categories` 
LEFT JOIN shop_specCategoryGroups ON shop_specCategoryGroups.CategoryID = shop_categories.ID
LEFT JOIN shop_specFields ON shop_specCategoryGroups.SpecFieldID = shop_specFields.ID
WHERE shop_categories.ID =1
LIMIT 0 , 30


SELECT shop_specFields.FieldName as `Specs`
FROM  `shop_specCategoryGroups` LEFT JOIN shop_specFields ON shop_specCategoryGroups.SpecFieldID = shop_specFields.ID
WHERE shop_specCategoryGroups.CategoryID =1

SELECT shop_specFields.ID, shop_specFields.FieldName
FROM  `shop_specCategoryGroups` LEFT JOIN shop_specFields ON shop_specCategoryGroups.SpecFieldID = shop_specFields.ID
WHERE shop_specCategoryGroups.CategoryID =1
LIMIT 0 , 30

SELECT shop_specProductValues.Value, shop_specFields.FieldName FROM `shop_specProductValues` LEFT JOIN shop_specFields ON shop_specProductValues.SpecFieldID = shop_specFields.ID
WHERE shop_specProductValues.ProductID = 1

SELECT shop_products.Name, shop_specFields.FieldName, shop_specProductValues.Value
FROM  `shop_specProductValues` 
LEFT JOIN shop_products ON shop_specProductValues.ProductID = shop_products.ID
LEFT JOIN shop_specFields ON shop_specProductValues.SpecFieldID = shop_specFields.ID
WHERE shop_products.ID =3
LIMIT 0 , 30


SELECT shop_products.ID FROM shop_products
LEFT JOIN shop_specProductValues ON shop_products.ID = shop_specProductValues.ProductID WHERE shop_specProductValues.SpecFieldID in (2,3) GROUP BY shop_products.ID
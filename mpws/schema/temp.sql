-- get category available specs

SELECT shop_categories.Name, shop_specFields.FieldName
FROM  `shop_categories` 
LEFT JOIN shop_specCategoryGroups ON shop_specCategoryGroups.CategoryID = shop_categories.ID
LEFT JOIN shop_specFields ON shop_specCategoryGroups.SpecFieldID = shop_specFields.ID
WHERE shop_categories.ID =1
LIMIT 0 , 30


SELECT shop_specFields.ID, shop_specFields.FieldName
FROM  `shop_specCategoryGroups` LEFT JOIN shop_specFields ON shop_specCategoryGroups.SpecFieldID = shop_specFields.ID
WHERE shop_specCategoryGroups.CategoryID =1
LIMIT 0 , 30


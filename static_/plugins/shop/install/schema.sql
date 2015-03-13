DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `getAllShopCategoryBrands`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllShopCategoryBrands`(IN catid INT)
BEGIN
  SELECT o.ID,
         o.Name
  FROM   shop_products AS p
         LEFT JOIN shop_origins AS o
                ON p.OriginID = o.ID
  WHERE  p.Status = 'ACTIVE'
         AND o.Status = 'ACTIVE'
         AND p.CategoryID = catid
  GROUP  BY o.Name; 
-- SELECT o.ID, o.Name FROM shop_products AS `p` LEFT JOIN shop_origins AS `o` ON p.OriginID = o.ID WHERE p.Enabled = 1 AND o.Enabled = 1 AND p.CategoryID = catid GROUP BY o.Name;
END$$

DROP PROCEDURE IF EXISTS `getAllShopCategorySubCategories`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllShopCategorySubCategories`(IN catid INT)
BEGIN
  SELECT c.ID,
         c.Name
  FROM   shop_products AS p
         LEFT JOIN shop_categories AS c
                ON p.CategoryID = c.ID
  WHERE  p.Status = 'ACTIVE'
         AND c.Status = 'ACTIVE'
         AND c.ParentID = catid
  GROUP  BY c.Name; 
-- SELECT c.ID, c.ParentID, c.Name FROM shop_categories AS `c` WHERE c.ParentID = catid AND c.Enabled = 1 GROUP BY c.Name;
END$$

DROP PROCEDURE IF EXISTS `getShopCategoryLocation`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryLocation`(IN catid INT)
BEGIN
SELECT T2.ID, T2.Name
FROM (
    SELECT
        @r AS _id,
        (SELECT @r := ParentID FROM shop_categories WHERE ID = _id) AS ParentID,
        @l := @l + 1 AS lvl
    FROM
        (SELECT @r := catid, @l := 0) vars,
        shop_categories h
    WHERE @r <> 0) T1
JOIN shop_categories T2
ON T1._id = T2.ID
ORDER BY T1.lvl DESC;
END$$


DROP PROCEDURE IF EXISTS `getShopCategoryPriceEdges`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryPriceEdges`(IN catid INT)
BEGIN
SELECT MAX( p.Price ) AS 'PriceMax' , MIN( p.price ) AS 'PriceMin' FROM shop_products AS  `p` WHERE p.CategoryID = catid;
END$$




-- DROP PROCEDURE IF EXISTS `getShopCategoryBrands`$$
-- CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategoryBrands`(IN catid INT)
-- BEGIN
-- SELECT o.ID, o.Name FROM shop_products AS `p` LEFT JOIN shop_origins AS `o` ON p.OriginID = o.ID WHERE p.CategoryID = catid GROUP BY o.Name;
-- END$$


-- DROP PROCEDURE IF EXISTS `getShopCategorySubCategories`$$
-- CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopCategorySubCategories`(IN catid INT)
-- BEGIN
-- SELECT
--   c.ID, c.ParentID, c.Name,
--   (SELECT count(*) FROM shop_products AS `p` WHERE p.CategoryID = c.ID AND p.Status = 'ACTIVE') AS `ProductCount`
-- FROM shop_categories AS `c` WHERE c.ParentID = catid AND c.Status = 'ACTIVE' GROUP BY c.Name;
-- END$$

-- DROP PROCEDURE IF EXISTS `getShopSiteOrdersCount`$$
-- CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopSiteOrdersCount`(IN `CustomerID` INT)
-- BEGIN
--   SELECT COUNT(*) as `OrderCount`
--   FROM shop_orders AS o
--   WHERE o.CustomerID = CustomerID
--   ORDER BY o.DateCreated DESC;
-- END$$

-- DROP PROCEDURE IF EXISTS `getShopSiteProductsCount`$$
-- CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopSiteProductsCount`(IN `CustomerID` INT)
--     NO SQL
-- BEGIN
--   SELECT COUNT(*) as `ProductCount`
--   FROM shop_products AS o
--   WHERE o.CustomerID = CustomerID
--   ORDER BY o.DateCreated DESC;
-- END$$

DELIMITER ;
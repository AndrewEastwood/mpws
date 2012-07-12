
    $plugin['QUERY'] = array();
    
    $plugin['QUERY']['API'] = array();
    $plugin['QUERY']['API']['STAT_WR_ORDERS'] = '
        SELECT
            wr.Name,
            wo.ID as `OrderID`, 
            (
                SELECT 
                    count(*) 
                FROM `writer_orders`
                WHERE WriterID = wr.ID
            ) as `AllOrderCount`, 
            (
                SELECT 
                    count(*) 
                FROM `writer_orders`
                WHERE WriterID = wr.ID
                AND Status = "PENDING"
            ) as `PendingOrderCount`, 
            (
                SELECT 
                    count(*) 
                FROM `writer_orders`
                WHERE WriterID = wr.ID
                AND Status = "IN PROGRESS"
            ) as `InProgressOrderCount`, 
            (
                SELECT 
                    count(*) 
                FROM `writer_orders`
                WHERE WriterID = wr.ID
                AND Status = "NEW"
            ) as `NewOrderCount`
        FROM `writer_writers`  as `wr` 
        LEFT JOIN `writer_orders` as `wo` ON wr.ID = wo.WriterID
        GROUP BY wr.Name;';
    

    $plugin['QUERY']['API']['STAT_ORDER_DEADLINE'] = '
        SELECT
            *, ( 
                (UNIX_TIMESTAMP(  `DateDeadline` ) - UNIX_TIMESTAMP( NOW( ) ) ) /3600) AS  `HoursLeft`
        FROM writer_orders
        WHERE STATUS !=  "CLOSED"
        HAVING HoursLeft < 4;';

    $plugin['QUERY']['API']['STAT_WRITERS_FREE'] = '
        SELECT
            wo.ID, wr.Name,
            wr.Active, wr.IsOnline, wr.DateLastAccess
        FROM
            `writer_orders` AS  `wo` 
        RIGHT JOIN
            writer_writers AS  `wr` ON wo.WriterID = wr.ID
        WHERE wo.ID IS NULL';
        
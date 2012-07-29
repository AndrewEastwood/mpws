
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
                AND PublicStatus = "PENDING"
            ) as `PendingOrderCount`, 
            (
                SELECT 
                    count(*) 
                FROM `writer_orders`
                WHERE WriterID = wr.ID
                AND PublicStatus = "IN PROGRESS"
            ) as `InProgressOrderCount`, 
            (
                SELECT 
                    count(*) 
                FROM `writer_orders`
                WHERE WriterID = wr.ID
                AND PublicStatus = "NEW"
            ) as `NewOrderCount`, 
            (
                SELECT 
                    count(*) 
                FROM `writer_orders`
                WHERE WriterID = wr.ID
                AND PublicStatus = "REWORK"
            ) as `ReworkOrderCount`, 
            (
                SELECT 
                    count(*) 
                FROM `writer_orders`
                WHERE WriterID = wr.ID
                AND PublicStatus = "REOPEN"
            ) as `ReopenOrderCount`, 
            (
                SELECT 
                    count(*) 
                FROM `writer_orders`
                WHERE WriterID = wr.ID
                AND PublicStatus = "TO REFUND"
            ) as `ToRefundOrderCount`
        FROM `writer_writers`  as `wr` 
        LEFT JOIN `writer_orders` as `wo` ON wr.ID = wo.WriterID
        GROUP BY wr.Name;';
    

    $plugin['QUERY']['API']['STAT_ORDER_DEADLINE'] = '
        SELECT
            *, ( 
                (UNIX_TIMESTAMP(  `DateDeadline` ) - UNIX_TIMESTAMP( NOW( ) ) ) /3600) AS  `HoursLeft`
        FROM writer_orders
        WHERE PublicStatus !=  "CLOSED"
        HAVING HoursLeft < 4;';

    $plugin['QUERY']['API']['STAT_WRITERS_FREE'] = '
        SELECT
            wo.ID, wr.Name,
            wr.Active, wr.IsOnline, wr.DateLastAccess
        FROM
            `writer_orders` AS  `wo` 
        RIGHT JOIN
            writer_writers AS  `wr` ON wo.WriterID = wr.ID
        WHERE wo.ID IS NULL AND wr.Active = 1 AND wr.IsOnline = 1';
        
    $plugin['QUERY']['STAT_UNPAID_ORDERS'] = '
        SELECT 
            writer_orders.ID, Title, Price, Pages, StudentID, writer_orders.DateCreated, writer_invoices.invoice_id
        FROM 
            `writer_orders`
        LEFT JOIN 
            `writer_invoices` ON writer_orders.OrderToken = writer_invoices.merchant_order_id
        WHERE writer_invoices.invoice_id IS NULL
        ORDER BY writer_orders.DateCreated DESC';
    
    $plugin['QUERY']['STAT_UNPAID_SALES'] = '
        SELECT 
            writer_sales.ID, Title, Price, Pages, StudentID, writer_sales.DateCreated, writer_invoices.invoice_id
        FROM 
            `writer_sales`
        LEFT JOIN 
            `writer_sale` ON writer_sales.SaleID = writer_sale.ID 
        LEFT JOIN 
            `writer_invoices` ON writer_sales.SalesToken = writer_invoices.merchant_order_id
        WHERE writer_invoices.invoice_id IS NULL
        ORDER BY writer_sales.DateCreated DESC';
        
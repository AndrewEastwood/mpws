mpws.module.define('writer', (function(window, document, $){
    
    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function _drawChart(a) {

        //mpws.tools.log(a);
        
        // writer orders count
        // render as ColumnChart
        if (a.writerToOrderCount) {
            // Create the data table.
            //var data = new google.visualization.DataTable();
            //data.addColumn('string', 'Writer');
            //data.addColumn('number', 'Order Count');
            
            //var column_writer = ['Writer'];
            //var column_order_new = ['New'];
            //var column_order_pending = ['Pending'];
            //var column_order_working = ['Working'];
            var rows = [['Name', 'New', 'Pending', 'Working', 'Reopen', 'Rework', 'To Refund']];
            var _addedRowsCount = 0;
            for (var idx in a.writerToOrderCount) {
                var _row = a.writerToOrderCount[idx];
                //column_writer.push(_row.Name);
                //column_order_new.push(+_row.NewOrderCount);
                //column_order_pending.push(+_row.PendingOrderCount);
                //column_order_working.push(+_row.InProgressOrderCount);
                if (+_row.AllOrderCount != 0) {
                    rows.push([_row.Name, 
                            +_row.NewOrderCount, 
                            +_row.PendingOrderCount, 
                            +_row.InProgressOrderCount,
                            +_row.ReopenOrderCount,
                            +_row.ReworkOrderCount,
                            +_row.ToRefundOrderCount,
                    ]);
                    _addedRowsCount++;
                }
                //mpws.tools.log();
            }
            /*var rows = [
                column_writer, 
                column_order_new,
                column_order_working,
                column_order_pending
            ];*/
            
            mpws.tools.log(rows);
            if (_addedRowsCount) {
                //data.addRows(rows);

                var data = google.visualization.arrayToDataTable(rows);

                // Set chart options
                var options = {
                    'title':'Writer Orders',
                    'width':500,
                    'height':300,
                    'backgroundColor': '#eee',
                    'isStacked': true,
                    'hAxis': {
                        'baseline': 0,
                        'minValue': 0,
                        'logScale': 0,
                        'minorGridlines': {
                            'count': 0
                        },
                        'viewWindow': {
                            'min': 0
                        }
                    }
                };


                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
                chart.draw(data, options);
                //mpws.tools.log('count');
            }
        }
    }
    
    $(document).ready(function () {
        // Set a callback to run when the Google Visualization API is loaded.
        //google.setOnLoadCallback(drawChart);
        // chnages message status
        $('.MPWSControl_MessageMarkAsRead').click(function(){
            mpws.api.objectRequest(this);
        });
        // date pickers
        $('.MPWSControlOrderDateRange').datepicker({
                dateFormat: "yy-mm-dd 00:00:00",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true
        });
        //- assign order to writer
        
        //mpws.tools.log();
        if($('#chart_div').length)
            mpws.api.pageRequest('get_teamload', _drawChart);
        
        // order tabs
        if (mpws.display == 'orders')
            $("#MPWSOrderMessagesTabsID").tabs();
        
        // init base url
        if(tinyMCE)
            tinyMCE.baseURL = "/static/wysiwyg/tiny_mce";
    });

    return {
        drawStatistic: _drawChart
    };

})(window, document, jQuery));

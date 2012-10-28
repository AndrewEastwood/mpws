{
    DASHBOARD: {
        name: 'dashboard',
        colors: [],
        fonts: [],
        render: function (d, c, o) {
            log('DASHBOARD RENDERING');
            if (!google.visualization.Dashboard)
                return;
            //log(d);
            //log(c);
            //log(o);
            var _reportData = o.report.getReportData(d);
            var data = new google.visualization.arrayToDataTable(_reportData);
            var charObjects = $(o.renderObject).toArray();
            log(charObjects);
            for (var cObj in charObjects) {
                var _dBoard = charObjects[cObj];
                var dItems = o.report.getReportCharts(_reportData, data, o.report.owner);
                var dashboard  = new google.visualization.Dashboard(_dBoard);
                // setup dashboard's internal containers
                $(_dBoard).find('div').each(
                    function(){
                        $(this).attr('id', $(this).attr('id') + '_' + o.report.owner + '_ID');
                    }
                );
                log('DASHBOARD: binding charts:');
                log(dItems);
                dashboard.bind(dItems.controls, dItems.charts);
                log('DASHBOARD draw with data:');
                log(data);
                dashboard.draw(data);
            }
        }
    },
    BAR: {
        name: 'bar',
        colors: [],
        fonts: [],
        render: function (d, c, o) {
            log('BAR RENDERING');
            //log(d);
            //log(c);
            //log(o);
            if (!google.visualization.BarChart)
                return;
            var _reportData = o.report.getReportData(d);
            var data = new google.visualization.arrayToDataTable(_reportData, false);
            var charObjects = $(o.renderObject).toArray();
            for (var cObj in charObjects) {
                //log ('[' + cObj + ']-------- loop for: ' + o.renderObject);
                //log (charObjects);
                //log (charObjects[cObj]);
                var chart = new google.visualization.BarChart(charObjects[cObj]);
                chart.draw(data, o.report.options);
                if (o.report.events && Object.keys(o.report.events).length)
                    for (var eventName in o.report.events)
                        google.visualization.events.addListener(chart, eventName, function () {o.report.events[eventName](chart, data);});
            }
        }
    },
    PIE: {
        name: 'pie',
        colors: [],
        fonts: [],
        render: function (d, c, o) {
            log('PIE RENDERING');
            if (!google.visualization.PieChart)
                return;
            var _reportData = o.report.getReportData(d);
            // Create and populate the data table.
            var data = new google.visualization.arrayToDataTable(_reportData);
            // Create and draw the visualization.
            var charObjects = $(o.renderObject).toArray();
            for (var cObj in charObjects) {
                var chart = new google.visualization.PieChart(charObjects[cObj]);
                chart.draw(data, o.report.options);
                if (o.report.events && Object.keys(o.report.events).length)
                    for (var eventName in o.report.events)
                        google.visualization.events.addListener(chart, eventName, function () {o.report.events[eventName](chart, data);});
            }
        }
    },
    TABLE: {
        name: 'activity',
        colors: [],
        fonts: [],
        render: function (d, c, o) {
            log('ACTIVITY RENDERING');
            if (!google.visualization.Table)
                return;
            var _reportData = o.report.getReportData(d);
            // Create and populate the data table.
            var data = new google.visualization.arrayToDataTable(_reportData);
            // Create and draw the visualization.
            var charObjects = $(o.renderObject).toArray();
            for (var cObj in charObjects) {
                var table = new google.visualization.Table(charObjects[cObj]);
                table.draw(data, o.report.options);
                if (o.report.events && Object.keys(o.report.events).length)
                    for (var eventName in o.report.events)
                        google.visualization.events.addListener(table, eventName, function () {o.report.events[eventName](table, data);});
            }
        }
    },
    HISTOGRAM: {
        name: 'histogram',
        colors: [],
        fonts: [],
        render: function (d, c, o) {
            log('HISTOGRAM RENDERING');
            if (!google.visualization.ColumnChart)
                return;
            var _reportData = o.report.getReportData(d);
            // Create and populate the data table.
            var data = new google.visualization.arrayToDataTable(_reportData);
            // Create and draw the visualization.
            var charObjects = $(o.renderObject).toArray();
            for (var cObj in charObjects) {
                var chart = new google.visualization.ColumnChart(charObjects[cObj]);
                chart.draw(data, o.report.options);
                if (o.report.events && Object.keys(o.report.events).length)
                    for (var eventName in o.report.events)
                        google.visualization.events.addListener(chart, eventName, function () {o.report.events[eventName](chart, data);});
            }
        }
    },
    AREA: {
        name: 'line',
        colors: [],
        fonts: [],
        render: function (d, c, o) {
            log('LINEAR RENDERING');
            if (!google.visualization.AreaChart)
                return;
            var _reportData = o.report.getReportData(d);
            // Create and populate the data table.
            var data = new google.visualization.arrayToDataTable(_reportData);
            // Create and draw the visualization.
            var charObjects = $(o.renderObject).toArray();
            for (var cObj in charObjects) {
                var chart = new google.visualization.AreaChart(charObjects[cObj]);
                chart.draw(data, o.report.options);
                if (o.report.events && Object.keys(o.report.events).length)
                    for (var eventName in o.report.events)
                        google.visualization.events.addListener(chart, eventName, function () {o.report.events[eventName](chart, data);});
            }
        }
    },
    LINE: {
        name: 'line',
        colors: [],
        fonts: [],
        render: function (d, c, o) {
            log('LINEAR RENDERING');
            if (!google.visualization.AreaChart)
                return;
            var _reportData = o.report.getReportData(d);
            // Create and populate the data table.
            var data = new google.visualization.arrayToDataTable(_reportData);
            // Create and draw the visualization.
            var charObjects = $(o.renderObject).toArray();
            for (var cObj in charObjects) {
                var chart = new google.visualization.LineChart(charObjects[cObj]);
                chart.draw(data, o.report.options);
                if (o.report.events && Object.keys(o.report.events).length)
                    for (var eventName in o.report.events)
                        google.visualization.events.addListener(chart, eventName, function () {o.report.events[eventName](chart, data);});
            }
        }
    },
    COMBO: {
        name: 'line',
        colors: [],
        fonts: [],
        render: function (d, c, o) {
            log('COMBO RENDERING');
            if (!google.visualization.ComboChart)
                return;
            var _reportData = o.report.getReportData(d);
            // Create and populate the data table.
            var data = new google.visualization.arrayToDataTable(_reportData);
            // Create and draw the visualization.
            var charObjects = $(o.renderObject).toArray();
            for (var cObj in charObjects) {
                var chart = new google.visualization.ComboChart(charObjects[cObj]);
                chart.draw(data, o.report.options);
                if (o.report.events && Object.keys(o.report.events).length)
                    for (var eventName in o.report.events)
                        google.visualization.events.addListener(chart, eventName, function () {o.report.events[eventName](chart, data);});
            }
        }
    }
}
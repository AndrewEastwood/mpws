/* MPWS visual report engine
 * --------
 */
mpws.module.define('visualReport', (function(window, document, $){

    if (mpws.module.get('visualReport'))
        return;

    // main object
    var visualReport = {};
    // report contexts
    var _contexts = {}

    // general container
    visualReport.reportTypes = {
        DASHBOARD: {
            name: 'dashboard',
            colors: [],
            fonts: [],
            render: function (d, c, o) {
                //mpws.tools.log('DASHBOARD RENDERING');
                if (!google.visualization.Dashboard)
                    return;
                //log(d);
                //log(c);
                //log(o);
                var _reportData = o.report.getReportData(d);
                var data = new google.visualization.arrayToDataTable(_reportData);
                var charObjects = $(o.renderObject).toArray();
                //mpws.tools.log(charObjects);
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
                    //mpws.tools.log('DASHBOARD: binding charts:');
                    //mpws.tools.log(dItems);
                    dashboard.bind(dItems.controls, dItems.charts);
                    //mpws.tools.log('DASHBOARD draw with data:');
                    //mpws.tools.log(data);
                    dashboard.draw(data);
                }
            }
        },
        BAR: {
            name: 'bar',
            colors: [],
            fonts: [],
            render: function (d, c, o) {
                //mpws.tools.log('BAR RENDERING');
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
                //mpws.tools.log('PIE RENDERING');
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
                //mpws.tools.log('ACTIVITY RENDERING');
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
                //mpws.tools.log('HISTOGRAM RENDERING');
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
                //mpws.tools.log('LINEAR RENDERING');
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
                //mpws.tools.log('LINEAR RENDERING');
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
                //mpws.tools.log('COMBO RENDERING');
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
    };
    //visualReport.pageID = 'defaultPageID';
    // user defined reports
    visualReport.userReports = {};
    // options
    visualReport.opts = {};
    // data storage
    visualReport.dataStorage = {}

    visualReport.getType = function (name) {
        return this.reportTypes[name];
    };

    visualReport.addData = function (realm, dataObject) {
        //mpws.tools.log("adding data: " + realm + " : " + dataObject);
        //log("adding data: " + dataObject);
        this.dataStorage[realm] = dataObject;
    }; // add data

    visualReport.addUserReport = function (reportObject) {
        this.userReports[reportObject.Setup.app] = reportObject;
    };

    visualReport.getUserReport = function (reportName) {
        return this.userReports[reportName];
    };
    
    // render report
    visualReport.renderSingleReport = function (reportScript, realm) {
        // report type
        // data key name
        // axis Y - fieldName
        // axis X - fieldName
        // group filed names 
        // 
        if (typeof(reportScript) === "string" && realm)
            reportScript = visualReport.userReports[realm].Scripts[reportScript];
        //mpws.tools.log("rendering single report: " + reportScript.name);
        var _reportData = false;
        if (reportScript.useData === '__ALL__')
            _reportData = visualReport.dataStorage;
        else
            _reportData = visualReport.dataStorage[reportScript.useData || realm];
        var _c = visualReport.getRenderObject(reportScript);
        reportScript.base.render(_reportData, _c, reportScript);
    }; // show

    visualReport.renderAllReports = function (renderOpts) {
        //log("do autoproceed all reports: forceRendering is " + forceRendering);
        //this = visualReport;
        // run over user reports
        // get report script
        var _vScript = visualReport.getUserReport(renderOpts.app);
        //mpws.tools.log(_vScript.Scripts);
        for (var ri in _vScript.Scripts) {
            var _reportScript = _vScript.Scripts[ri];
            if (_reportScript && renderOpts && renderOpts.isAutoload && _reportScript.autoshow)
                visualReport.renderSingleReport(_reportScript, _vScript.Setup.app);
        }
    }
    
    visualReport.getRenderObject = function (reportScript) {
        var $jRo = $(reportScript.renderObject);
        var ctx = false;
        if (reportScript.provider == 'CANVAS')
            ctx = $jRo[0].getContext("2d");
        var rObj = {
            surface: ctx,
            owner: $jRo,
            size: {
                w: $jRo.width(),
                h: $jRo.height()
            }
        }
        return rObj;
    }
    
    visualReport.asyncFnController = function (wObj, callback, param) {
        //mpws.tools.log("asyncFnController: " + wObj);
        if (!!callback & wObj == 0)
            callback(param);
    }

    visualReport.getTransformedData = function (data, config/* visible fields */){
        var useHeaders = typeof(config.useHeaders) === "boolean" ? config.useHeaders : true;
        var uniqueFiled = config.uniqueFiled || "";
        var uniqueDataObjectContainer = {};
        var mergeFunction = config.customMergeFunction || function (dataRow, skipIndex, container) {
            var mergedData = [];
            //log('------ megre fn and skip index ' + skipIndex + ' ------');
            for (var idx in dataRow)
                if (idx == skipIndex) {
                    mergedData.push(dataRow[idx]);
                } else {
                    if (!!container && container[idx])
                        mergedData.push(container[idx] + dataRow[idx]);
                    else
                        mergedData.push(dataRow[idx]);
                }
            return mergedData;
        };
        //var headerRow = ["Implementer", "GeneralTime"];
        var headerRow = config.visibleFields;
        var rawDataRows = [];
        var reportData = [];

        // getting all values by required fields
        for (var idxR in data.rows) {
            var _drow = [];
            for (var idxC in headerRow) {
                _drow.push(data.rows[idxR][headerRow[idxC]]);
            }
            rawDataRows.push(_drow);
        }
        
        // adding custom fileds
        if (!!config.customFields)
            for (var idxCuH in config.customFields) {
                // adding header
                headerRow.push(config.customFields[idxCuH].Title);
                // updating all data
                for (var idxRForC in rawDataRows) {
                    rawDataRows[idxRForC].push(config.customFields[idxCuH].Value);
                }
            }
        

        // getting filed index of unique value
        var _uniqueValue = false;
        var _uniqueIndex = 0;
        for (var idxC in headerRow)
            if (uniqueFiled == headerRow[idxC]) {
                _uniqueIndex = idxC;
                break;
            }

        // merge field values
        // TODO: optimize
        var doMerge = true;
        if (typeof(config.mergeData) === "boolean")
            doMerge = config.mergeData;
        if (doMerge && mergeFunction)
            for (var idxR in rawDataRows) {
                _uniqueValue = rawDataRows[idxR][_uniqueIndex];
                //log(uniqueDataObjectContainer[_uniqueValue]);
                //log(rawDataRows[idxR]);
                //log('-----------------------------------');
                if (!!uniqueDataObjectContainer[_uniqueValue]) {
                    //log('---- do merge with previous data of key: ' + _uniqueValue);
                    var md = mergeFunction(rawDataRows[idxR], _uniqueIndex, uniqueDataObjectContainer[_uniqueValue], config.customData || false, uniqueDataObjectContainer);
                    //log(md);
                    if (typeof(md) === 'object' && md.hasOwnProperty('overrideUniqueValue') && md.hasOwnProperty('data')) {
                        _uniqueValue = md.overrideUniqueValue;
                        md = md.data;
                    }
                    if (typeof(md) !== 'undefined')
                        uniqueDataObjectContainer[_uniqueValue] = md;
                } else {
                    //log('---- making new data with key: ' + _uniqueValue);
                    var md = mergeFunction(rawDataRows[idxR], _uniqueIndex, false, config.customData || false, uniqueDataObjectContainer);
                    //log(md);
                    if (typeof(md) === 'object' && md.hasOwnProperty('overrideUniqueValue') && md.hasOwnProperty('data')) {
                        _uniqueValue = md.overrideUniqueValue;
                        md = md.data;
                    }
                    if (typeof(md) !== 'undefined')
                        uniqueDataObjectContainer[_uniqueValue] = md;
                }
            }
            
        //log('Merged Data');
        //log(uniqueDataObjectContainer);

        //log('Use Headers: ' + useHeaders);
        // if we use google datatable
        var _retData = data;
        switch (config.type) {
            case 'GDataTable': {
                var reportData = [];
                // adding header line over all data
                if (useHeaders)
                    reportData.push(headerRow);
                for (var uIdx in uniqueDataObjectContainer) {
                    //var _dataRow = [];
                    reportData.push(uniqueDataObjectContainer[uIdx]);
                }
                _retData = reportData;
                break;
            }
            case 'simple': {
                var _retData = [];
                // adding header line over all data
                if (useHeaders)
                    _retData.push(headerRow);
                for (var uIdx in rawDataRows) {
                    //var _dataRow = [];
                    _retData.push(rawDataRows[uIdx]);
                }
                break;
            }
            case 'raw': {
                _retData = rawDataRows;
                break;
            }
            case 'merged': {
                _retData = uniqueDataObjectContainer;
                break;
            }
        }
        
        
        //log('Before removeHeaderKeysInEndUp:');
        //log(_retData);

        //log('---------- Removing header field ' + headerRow[rmI] + ' at index ' + rmI);
        // will remove header field names and relevant data fields
        if (!!config.removeHeaderKeysInEndUp) {
            //var _headerRow = [];
            // remove unnecessary fields
            for (var rmfIdx in config.removeHeaderKeysInEndUp) {
                var rmI = headerRow.indexOf(config.removeHeaderKeysInEndUp[rmfIdx]);
                if (rmI >= 0) {
                    //log('---------- Removing header field ' + headerRow[rmI] + ' at index ' + rmI);
                    delete(headerRow[rmI]);
                    if (config.removeRelevantDataFieldsByHeaderKeysInEndUp) {
                        //log('- attempting to remove relevant data fields by header key ' + headerRow[rmI]);
                        if (headerRow.length == _retData[(useHeaders?1:0)].length)
                            for (var dataIdx in _retData) {
                                if (useHeaders && dataIdx == 0)
                                    continue; // skip headers
                                //log('Removing data from merged container');
                                //log(_retData[dataIdx]);
                                delete(_retData[dataIdx][rmI]);
                            }
                        else
                            mpws.tools.log('FAILED: can not remove data field by header key because data row deoes not match to header (different size)');
                    }
                    //log('--------------------------------------');
                }
            }
            // adding remined fields
            var _filteredData = [];
            if (useHeaders) {
                var _headerRow = [];
                for (var hIdx in headerRow)
                    if (typeof(headerRow[hIdx]) !== 'undefined')
                        _headerRow.push(headerRow[hIdx]);
                _filteredData.push(_headerRow);
            }
            // adding rows
            for (var drIdx in _retData) {
                var _dRow = [];
                if (useHeaders && drIdx == 0)
                    continue; // skip headers
                for (var rcIdx in _retData[drIdx]) {
                    if (typeof(_retData[drIdx][rcIdx]) !== 'undefined')
                        _dRow.push(_retData[drIdx][rcIdx]);
                }
                _filteredData.push(_dRow);
            }
            
            // reset returning data
            _retData = _filteredData;
        }
        
        //log('Returning data:');
        //log(_retData);

        return _retData;
    }

    visualReport.onLoading = function() {};
    visualReport.onLoaded = function() {};
    
    visualReport.load = function (/* dataPackages */){

        visualReport.onLoading();
        
        var wObj = arguments.length;
        var dataPackages = arguments;
        // do main init
        //mpws.tools.log(dataPackages);
        // download report script
        
        for (var di in dataPackages) {
            //log('key = ' + di);
            var dataPackage = dataPackages[di];
            //mpws.tools.log("loading data: " + dataPackage.app);
            //log("from package: ");
            //mpws.tools.log(dataPackage);
            $.ajax({
                url: dataPackage.url,
                dataType: 'text',
                success: function (data) {
                    var _dObj = data.split('\n^^^^^^^^^^^^^^^^^\n');
                    //console.log(_dObj.length);
                    //mpws.tools.log('DOWNLOADED DATA LENGTH ' + data.length);
                    if (_dObj.length > 1) {
                        //mpws.tools.log('MULTI DATA OBJECT');
                        // add multiple data objects
                        // with 
                        var releases = [];
                        for (var rIdx in _dObj) {
                            //console.log(JSON.stringify(c["5.7.0"]));
                            releases[rIdx] = visualReport.dataWrapper(_dObj[rIdx], {delim: ",",textdelim: "\""});
                            //console.log(c);
                            visualReport.addData(dataPackage.app + '_' + rIdx, releases[rIdx]);
                        }
                    } else {
                        //mpws.tools.log('SINGLE DATA OBJECT');
                        // single data objects
                        // using additional library
                        var _rawData = visualReport.dataWrapper(data, {delim: ",",textdelim: "\""});
                        //
                        //log(dataPackageName);
                        //mpws.tools.log(_rawData);
                        visualReport.addData(dataPackage.app, _rawData);
                        // if there are defined user reports
                        // proceed them
                        // with wObj
                    }
                    // process reports
                    visualReport.asyncFnController(--wObj, visualReport.renderAllReports, {app: dataPackage.app, isAutoload: dataPackage.autoload || true});
                }
            });
        }

        
    }; // load
    
    // report data wrapper
    visualReport.dataWrapper = function (reportData, opts) {
        // uses csvjson module
        return mpws.module.get('csvjson').csvjson.csv2json(reportData, opts);
    };
    
    /*visualReport.create = function (opts, pkgs) {
        /* visualReport.create * /
        var _reportObj = /*{}* / visualReport;
        /*
        for (var vrp in visualReport)
            _reportObj[vrp] = visualReport[vrp];
        * /
        _reportObj.opts = opts;

        if (pkgs) {
            _reportObj.load(pkgs);
        }

        return _reportObj;
    }*/

    visualReport.extend = function(reportName, customReport, _extendedReport) {
        if (reportName)
            _extendedReport = visualReport.getUserReport(reportName);
        //log(_extendedReport);
        //log('-------------------------------------');
        for (var prop in customReport) {
            //log(prop);
            //log(customReport[prop]);
            //log(_extendedReport[prop]);
            if (typeof(customReport[prop]) === "object" && typeof(_extendedReport[prop]) === "object")
                _extendedReport[prop] = visualReport.extend(false, _extendedReport[prop], customReport[prop]);
            else
                _extendedReport[prop] = customReport[prop];
        }
        return _extendedReport;
    };

    visualReport.setup = function(targetUIID, targetScriptID , ui, script, callback) {
        // get container
        var contUI = document.getElementById(targetUIID);
        var contSCRIPT = document.getElementById(targetScriptID);
        // setup script
        var reportScript = eval('('+script+')');
        // add script
        visualReport.addUserReport(reportScript);
        // inject ui
        contUI.innerHTML = $(ui).filter('div').html();
        // inject script
        contSCRIPT.innerHTML = $(ui).filter('script').text();
        // callback
        if($.isFunction(callback))
            callback(reportScript.Setup);
    };

    return visualReport;

})(window, document, jQuery));
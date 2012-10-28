var visualReport = {};


function log(text) {

    console.log(text);

}

(function(visualReport, $, undefined){

    // general container
    //visualReport.pageID = 'defaultPageID';
    // user defined reports
    visualReport.userReports = {};
    // report displays
    visualReport.reportTypes = {};
    // options
    visualReport.opts = {};

    //
    var _contexts = {}
    visualReport.dataStorage = {}

    // constructor
    function _init(callback) {
        // set report collection

        //get a reference to the canvas 
        //console.log('#' + this.pageID + ' canvas');
        /*
        $('#' + this.pageID + ' canvas').each(function(){
            
            //console.log($(this).attr('rel'));
            visualReport.contexts[$(this).attr('rel')] = $(this)[0].getContext("2d");
        });*/
        
        log('getting available types');
        $.ajax({
            url: "/libs/tools/reporting/si/reports/types.js?sid=" + getRandom(),
            context: this,
            dataType: 'text',
            success: function(data){
                // load base object
                visualReport.reportTypes = eval('(' + data + ')');
                // get custom reports
                // temporary injected
                log('getting custom reports');
                log("reports/custom" + (visualReport.opts.app ? ('_' + visualReport.opts.app) : '') + ".js?sid=" + getRandom());
                $.ajax({
                    url: "/libs/tools/reporting/si/reports/custom" + (visualReport.opts.app ? ('_' + visualReport.opts.app) : '') + ".js?sid=" + getRandom(),
                    context: this,
                    dataType: 'text',
                    success: function(data){
                        visualReport.userReports = eval('(' + data + ')');
                        log("init complited");
                        callback();
                    }
                });
            }
        });
        

    }; // init

    visualReport.addData = function (keyName, dataObject) {
        log("adding data: " + keyName + " : " + dataObject);
        //log("adding data: " + dataObject);
        this.dataStorage[keyName] = dataObject;
    }; // add data

    visualReport.addUserReport = function (name, reportObject) {
        this.userReports[name] = reportObject;
    };

    visualReport.getUserReport = function (name) {
        return this.userReports[name];
    };
    
    // render report
    visualReport.renderSingleReport = function (reportScript) {
        // report type
        // data key name
        // axis Y - fieldName
        // axis X - fieldName
        // group filed names 
        // 
        if (typeof(reportScript) === "string")
            reportScript = visualReport.userReports[reportScript];
        log("rendering single report: " + reportScript.name);
        var _reportData = false;
        if (reportScript.useData == '__ALL__')
            _reportData = visualReport.dataStorage;
        else
            _reportData = visualReport.dataStorage[reportScript.useData];
        var _c = visualReport.getRenderObject(reportScript);
        reportScript.base.render(_reportData, _c, reportScript);
    }; // show

    visualReport.renderAllReports = function (renderOpts) {
        //log("do autoproceed all reports: forceRendering is " + forceRendering);
        //this = visualReport;
        // run over user reports
        log(visualReport.userReports);
        for (var ri in visualReport.userReports) {
            var _reportScript = visualReport.userReports[ri];
            if (_reportScript && renderOpts && renderOpts.isAutoload && _reportScript.autoshow)
                visualReport.renderSingleReport(_reportScript);
        }
        visualReport.onLoaded();
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
        log("asyncFnController: " + wObj);
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
                            log('FAILED: can not remove data field by header key because data row deoes not match to header (different size)');
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
        log(dataPackages);
        _init(function(){
            for (var di in dataPackages) {
                //log('key = ' + di);
                var dataPackage = dataPackages[di];
                log("loading data: " + dataPackage.name);
                //log("from package: ");
                log(dataPackage);
                $.ajax({
                    url: dataPackages[di].fullurl ? dataPackages[di].fullurl : "data.php?" + dataPackages[di].url + "&dw=true",
                    dataType: 'text',
                    success: function (data) {
                        var _dObj = data.split('\n^^^^^^^^^^^^^^^^^\n');
                        //console.log(_dObj.length);
                        if (_dObj.length > 1) {
                            // add multiple data objects
                            // with 
                            var releases = [];
                            for (var rIdx in _dObj) {
                                //console.log(JSON.stringify(c["5.7.0"]));
                                releases[rIdx] = csvjson.csv2json(_dObj[rIdx], {delim: ",",textdelim: "\""});
                                //console.log(c);
                                visualReport.addData(dataPackage.name + '_' + rIdx, releases[rIdx]);
                            }
                        } else {
                            // single data objects
                            // using additional library
                            var csvObj = csvjson.csv2json(data, {delim: ",",textdelim: "\""});
                            //
                            //log(dataPackageName);
                            visualReport.addData(dataPackage.name, csvObj);
                            // if there are defined user reports
                            // proceed them
                            // with wObj
                        }
                        // process reports
                        visualReport.asyncFnController(--wObj, visualReport.renderAllReports, {isAutoload: dataPackage.autoload || true});
                    }
                });
            }
        });
        
    }; // load

    visualReport.create = function (opts, pkgs) {
        /* visualReport.create */
        var _reportObj = /*{}*/ visualReport;
        /*
        for (var vrp in visualReport)
            _reportObj[vrp] = visualReport[vrp];
        */
        _reportObj.opts = opts;

        if (pkgs) {
            _reportObj.load(pkgs);
        }

        return _reportObj;
    }

    visualReport.extend = function(reportName, customReport, _extendedReport) {
        if (reportName)
            _extendedReport = visualReport.getUserReport(reportName);
        //log(_extendedReport);
        //log('-------------------------------------');
        for (var prop in customReport) {
            //log(prop);
            //log(customReport[prop]);
            //log(_extendedReport[prop]);
            if (typeof(customReport[prop]) == "object" && typeof(_extendedReport[prop]) == "object")
                _extendedReport[prop] = visualReport.extend(false, _extendedReport[prop], customReport[prop]);
            else
                _extendedReport[prop] = customReport[prop];
        }
        return _extendedReport;
    }
})(window.visualReport = window.visualReport || {}, jQuery);
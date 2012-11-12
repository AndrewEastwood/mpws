/* GUI report script: 5/9/2012 */
{
    Setup: {app: 'weekly'},
    Scripts: {
        // ************************************
        //             common reports
        // ************************************
        GeneralTime: {
            // info
            name: 'GeneralTime',
            base: visualReport.reportTypes.BAR,
            renderObject: 'div#reportContainerTeamGeneralTimeID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'General Time'
                    //vAxis: {title: '',  titleTextStyle: {color: 'red'}},
                    //hAxis: {title: 'Reported hours'},
                    //height: '400px'
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: My Team General Report');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueFiled: 'Implementer',
                        visibleFields: ["Implementer", "GeneralTime"]
                    });

                    // remove users with no //logged tasks
                    var cleanDataObj = [];
                    for (var uidx in dataObj)
                        if (uidx == 0 || dataObj[uidx][1] !== 0)
                            cleanDataObj.push(dataObj[uidx]);

                    // convert unique object to array
                    //log(cleanDataObj);
                    return cleanDataObj;

                }
            }
        },
        TaskTypes: {
            // info
            name: 'TaskTypes',
            base: visualReport.reportTypes.PIE,
            renderObject: '#reportContainerTaskTypesID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'Handled Tasks (by items count)',
                    is3D: true
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: Overview');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueFiled: 'Case Type',
                        visibleFields: ["Case Type", "GeneralTime"],
                        customFields: [{Title: "Item Total", Value: 1}],
                        removeHeaderKeysInEndUp: ["GeneralTime"], //these fields will be removed in end up
                        customMergeFunction: function (dataRow, skipIndex, container) {
                            /*
                            var mergedData = [];
                            //log('------ megre fn and skip index ' + skipIndex + ' ------');
                            //log(container);
                            //log(dataRow);
                            for (var idx in dataRow) {
                                if (idx == skipIndex) {
                                    mergedData.push((dataRow[idx]==0)?"No Type":dataRow[idx]);
                                } else {
                                    if (!!container && !isNaN(container[idx]-0)) {
                                        //log('container['+idx+'] = ' + container[idx]);
                                        mergedData.push(container[idx] + 1);
                                    }
                                    else {
                                        //log('dataRow['+idx+'] = ' + dataRow[idx]);
                                        mergedData.push(dataRow[idx]);
                                    }
                                }
                            }
                            //log('====================================');
                            //log(mergedData);
                            //log('====================================');
                            return mergedData;
                            */

                            // do not include other tasks (accept Case Record Type only)
                            // skip empty tasks
                            if (dataRow[1] == 0)
                                return;

                            // if you are here than it is support case
                            return [
                                dataRow[0] == '' ? 'No Type' : dataRow[0],
                                container ? container[1] + 1 : dataRow[2]
                            ];


                        }
                    });
                    // convert unique object to array
                    //log(dataObj);


                    var _order = ['No Type','UI Migration','Other','PIE','Social Commerce Upgrade'];
                    var _newDataObj = [dataObj[0]];
                    // reorder data
                    //log(_newDataObj);
                    //log(dataObj);
                    //log('----------- reorder ---------------');
                    for (var _oidx in _order)
                        for (var _didx in dataObj) {
                            //log('looking for: ' + _order[_oidx]);
                            //log('running data type: ' + dataObj[_didx][0] + ' at index: ' + _didx);
                            if (_order[_oidx] == dataObj[_didx][0]) {
                                //log('---- found data');
                                //log(dataObj[_didx]);
                                _newDataObj.push(dataObj[_didx]);
                                //log(_newDataObj);
                                //log('--------------------------');
                                break;
                            }
                        }

                    //log(_dataObj);
                    return _newDataObj;
                }
            }
        },
        TaskTotal: {
            // info
            name: 'TaskTotal',
            base: visualReport.reportTypes.HISTOGRAM,
            renderObject: '#reportContainerTaskTotalID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'Engineer Total Tasks'
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: TaskTotal');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueFiled: 'Implementer',
                        visibleFields: ["Implementer", "GeneralTime"],
                        customFields: [{Title: "Item Total", Value: 1}],
                        removeHeaderKeysInEndUp: ["GeneralTime"], //these fields will be removed in end up
                        customMergeFunction: function (dataRow, skipIndex, container) {
                            /*var mergedData = [];
                            //log('------ megre fn and skip index ' + skipIndex + ' ------');
                            //log(container);
                            //log(dataRow);
                            for (var idx in dataRow) {
                                if (idx == skipIndex) {
                                    mergedData.push((dataRow[idx]==0)?"No Type":dataRow[idx]);
                                } else {
                                    if (!!container && !isNaN(container[idx]-0)) {
                                        //log('container['+idx+'] = ' + container[idx]);
                                        mergedData.push(container[idx] + 1);
                                    }
                                    else {
                                        //log('dataRow['+idx+'] = ' + dataRow[idx]);
                                        mergedData.push(dataRow[idx]);
                                    }
                                }
                            }
                            //log('====================================');
                            //log(mergedData);
                            //log('====================================');
                            */

                            // skip empty tasks
                            if (dataRow[1] == 0)
                                return;

                            // if you are here than it is support case
                            return [
                                dataRow[0] == '' ? 'Unknown User' : dataRow[0],
                                container ? container[1] + 1 : dataRow[2]
                            ];

                            //return mergedData;
                        }
                    });
                    // convert unique object to array
                    //log(dataObj);
                    return dataObj;

                    var reportData = [['', 'Germany', 'USA', 'Brazil', 'Canada', 'France', 'RU'],
                    ['', 700, 300, 400, 500, 600, 800]];

                    return reportData;

                }
            }
        },
        TimeTypes: {
            // info
            name: 'TimeTypes',
            base: visualReport.reportTypes.BAR,
            renderObject: '#reportContainerTypeAndTypesID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'General Time On Each Task',
                    vAxis: {
                        //title: 'Implementors',
                        //maxValue: 40
                    },
                    hAxis: {
                        title: 'Time Spent',
                        //maxValue: 40,
                        viewWindow: {
                            //max: 40
                        }
                    },
                    isStacked: true,
                    animation:{
                        duration: 3000,
                        easing: 'in',
                    }
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: Time On Tasks');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueFiled: 'Implementer',
                        customFields: [
                            {Title: "No Type", Value: 0},
                            {Title: "UI Migration", Value: 0},
                            {Title: "Other", Value: 0},
                            {Title: "PIE", Value: 0},
                            {Title: "Social Commerce Upgrade", Value: 0}
                        ],
                        removeHeaderKeysInEndUp: ["Case Type", "GeneralTime"],
                        removeRelevantDataFieldsByHeaderKeysInEndUp: false,
                        visibleFields: ["Implementer", "Case Type", "GeneralTime"],
                        customMergeFunction: function (dataRow, skipIndex, container) {
                            var mergedData = ["",0,0,0,0,0];
                            var taskTypes = [undefined,"No Type", "UI Migration", "Other", "PIE", "Social Commerce Upgrade"];

                            //log('------ megre fn and skip index ' + skipIndex + ' ------');

                            var _currentType = (dataRow[1]==0)?"No Type":dataRow[1];
                            var _currentTimeOnTask = dataRow[2];
                            var _typeIndex = taskTypes.indexOf(_currentType);

                            // implementer
                            mergedData[0] = dataRow[0];
                            // time on task
                            mergedData[_typeIndex] = dataRow[2];

                            // merging with previous data
                            if (!!container) {
                                mergedData[1] += container[1];
                                mergedData[2] += container[2];
                                mergedData[3] += container[3];
                                mergedData[4] += container[4];
                                mergedData[5] += container[5];
                            }

                            return mergedData;
                        }
                    });

                    // remove users with no //logged tasks
                    var cleanDataObj = [];
                    for (var uidx in dataObj)
                        if (uidx == 0 || mpws.tools.arraySumm(dataObj[uidx], 1) !== 0)
                            cleanDataObj.push(dataObj[uidx]);
                    // convert unique object to array
                    //log(cleanDataObj);
                    return cleanDataObj;

                }
            }
        },
        SIvsFA: {
            // info
            name: 'SIvsFA',
            base: visualReport.reportTypes.PIE,
            renderObject: '#reportContainerSIvsFAID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'FA vs. SI with Product matching (by items count)',
                    is3D: true
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: SIvsFA');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        visibleFields: ["Case Record Type", "Subject", "GeneralTime"],   // running index will be 0 and 1
                        customFields: [{Title: "Item Total", Value: 1}],  // running index will be 2
                        removeHeaderKeysInEndUp: ["Subject", "GeneralTime"], //these fields will be removed in end up
                        customMergeFunction: function (dataRow, skipIndex, container, cfg, mo) {
                            var mergedData = [];
                            //log('------ megre fn and skip index ' + skipIndex + ' ------');
                            //log('---------------------------');
                            //log(container);
                            //log('---------------------------');
                            //log(dataRow);

                            // skip empty tasks
                            if (dataRow[2] == 0)
                                return;

                            //log('******** data row SIvsFA ******* ');
                            //log(dataRow);

                            // custom filed type
                            var _CFT_ProductMatching = ((''+dataRow[1]).indexOf('SSSI_Syndication') >= 0);
                            var _recordType = _CFT_ProductMatching ? 'Product Matching' : dataRow[0];
                            var _recordCount = dataRow[3];

                            if (_CFT_ProductMatching && mo.hasOwnProperty(_recordType))
                                _recordCount += mo[_recordType][1];
                            else
                                if (container)
                                    _recordCount += container[1];

                            if (_CFT_ProductMatching)
                                mergedData = {
                                    'overrideUniqueValue': _recordType,
                                    'data': [_recordType, _recordCount]
                                };
                            else
                                mergedData = [_recordType, _recordCount];


                            //log(mo);
                            /*
                            for (var idx in dataRow) {
                                //log (idx);
                                if (idx == 1) // do not add subject value
                                    continue;

                                var _val = dataRow[idx];
                                // if it is first index (Case Record Type)
                                // replace it with 'Product Matching'
                                // to separate this field from standart case type (SI)
                                if (idx == 0 && _CFT_ProductMatching)
                                    _val = 'Product Matching'
                                //log('runnig value: ' + _val);
                                if (idx == skipIndex) {
                                    mergedData.push(_val);
                                } else {
                                    if (!!container && !isNaN(container[1]-0)) {
                                        //log('container['+idx+'] = ' + container[idx]);
                                        mergedData.push(container[1] + 1);
                                    }
                                    else {
                                        //log('dataRow['+idx+'] = ' + dataRow[idx]);
                                        mergedData.push(_val);
                                    }
                                }
                            }
                            //log('====================================');

                            */


                            //log('====================================');
                            return mergedData;
                        }
                    });
                    //log(dataObj);
                    return dataObj;
                }
            }
        },
        ComplexityCause : {
            // info
            name: 'ComplexityCause',
            base: visualReport.reportTypes.PIE,
            renderObject: '#reportContainerComplexityCauseID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'Complexity Cause (SE) (by items count)',
                    is3D: true
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: Overview');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        visibleFields: ["Complexity Cause (SE)", "Case Record Type", "GeneralTime"],   // running index will be 0 and 1
                        customFields: [{Title: "Item Total", Value: 1}],  // running index will be 2
                        removeHeaderKeysInEndUp: ["Case Record Type", "GeneralTime"], //these fields will be removed in end up
                        customMergeFunction: function (dataRow, skipIndex, container, cfg, mo) {

                            // do not include other tasks (accept Case Record Type only)
                            // skip empty tasks
                            if (dataRow[1] != 'Support Team' || dataRow[2] == 0)
                                return;

                            // if you are here than it is support case
                            return [
                                dataRow[0] == '' ? 'None' : dataRow[0],
                                container ? container[1] + 1 : dataRow[3]
                            ];

                            //log('====================================');
                            //log(mo);
                            //log('====================================');
                            //return mergedData;
                        }
                    });
                    //log(dataObj);
                    return dataObj;
                }
            }
        },
        MyTeamFull: {
            // info
            name: 'MyTeamFull',
            base: visualReport.reportTypes.TABLE,
            renderObject: '#reportContainerTeamReportID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'Team Overview',
                    allowHtml: true
                },
                events: {
                    select: function(sender, data) {
                        //log($);
                        //log(sender);
                        //log(data);
                        //log(sender.getSelection());
                        //log('You selected ' + data.getValue(row, 0));

                        var row = sender.getSelection()[0].row;
                        var userName = data.getValue(row, 0);
                        var page = $('#userPageID').clone();
                        var pageUniqueId = mpws.tools.random();

                        // setup user page
                        // and reaplce all id to be unique
                        //log('setup user page');
                        page.removeClass('MPWSHidden');
                        page.attr("dashboard", pageUniqueId)
                        page.find('.pageTitle').html(userName);
                        page.find('.buttonClose').click(function(){
                            $('#userPageID[dashboard="'+pageUniqueId+'"]').remove();
                            if (!$('#userPageID[dashboard]').length)
                                $('.mainContentWrapper').animate({opacity: 1});
                        });/*
                        page.find('*[id]').each(function(){
                            $(this).attr('id', $(this).attr('id').substr(0, $(this).attr('id').length - 2) + '_' + pageUniqueId + '_ID' );
                        });*/

                        // inject user page
                        //log('inject user page');
                        $('.mainContentWrapper').animate({opacity: 0.2});
                        $('body').append(page);

                        // setup paging
                        //log('setup paging');
                        mpws.module.get('web:plugin:reporting').initUserPaging(pageUniqueId);

                        // setup user reports
                        //log('setup user reports');
                        var _userReports = [
                            //clone(visualReport.userReports.UserDashboard),
                            mpws.tools.clone(visualReport.userReports['weekly'].Scripts.UserReport_Overview1),
                            mpws.tools.clone(visualReport.userReports['weekly'].Scripts.UserReport_Overview2),
                            mpws.tools.clone(visualReport.userReports['weekly'].Scripts.UserReport_Overview3),
                            mpws.tools.clone(visualReport.userReports['weekly'].Scripts.UserReport_Overview4),
                            mpws.tools.clone(visualReport.userReports['weekly'].Scripts.UserReport_Overview5),
                            mpws.tools.clone(visualReport.userReports['weekly'].Scripts.UserReport_Overview6),
                            mpws.tools.clone(visualReport.userReports['weekly'].Scripts.UserReport_Overview7)
                        ];

                        // render reports
                        //log('render reports:');
                        //log(_userReports);
                        for (var urIdx in _userReports) {
                            _userReports[urIdx].renderObject = '#userPageID[dashboard="'+pageUniqueId+'"] ' + _userReports[urIdx].renderObject;
                            if (urIdx == 'UserDashboard')
                                _userReports[urIdx].report.owner = pageUniqueId;
                            else
                                _userReports[urIdx].report.owner = userName;
                            visualReport.renderSingleReport(_userReports[urIdx], 'weekly');
                        }
                        //log('---- done');
                    }
                },
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: Time On Tasks');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueFiled: 'Implementer', // this field will contain all other data (merged) in the case when you use standart merge function
                        customFields: [
                            // types (6 + 6)
                            {Title: "FA count", Value: 0},
                            {Title: "FA hours", Value: 0},
                            {Title: "UI count", Value: 0},
                            {Title: "UI hours", Value: 0},
                            {Title: "Other count", Value: 0},
                            {Title: "Other hours", Value: 0},
                            {Title: "PIE count", Value: 0},
                            {Title: "PIE hours", Value: 0},
                            {Title: "SCI count", Value: 0},
                            {Title: "SCI hours", Value: 0},
                            {Title: "Matching count", Value: 0},
                            {Title: "Matching hours", Value: 0},
                            // addmitional (7)
                            {Title: "TOTAL", Value: 0},
                            {Title: "W.Goal", Value: 0},
                            {Title: "Days", Value: 0},
                            {Title: "G.Goal", Value: 0},
                            {Title: "Status", Value: ""},
                            {Title: "%", Value: 0}//,
                            //{Title: "W.Days", Value: 0}
                        ],// +19 for visible fields
                        removeHeaderKeysInEndUp: ["Case Type", "GeneralTime", "Subject", "CF_AtWork", "CF_WorkDays", "CF_Goal"], //these fields will be removed in end up
                        removeRelevantDataFieldsByHeaderKeysInEndUp: false,
                        visibleFields: ["Implementer", "Case Type", "GeneralTime", "Subject", "CF_AtWork", "CF_WorkDays", "CF_Goal"],  // involved data fields (dataRow will contain only 3 cells with relevant values)
                        customMergeFunction: function (dataRow, skipIndex, container, cfg, mo) {
                            // dataRow indexces
                            // 0 - Implementer
                            // 1 - Case Type
                            // 2 - GeneralTime
                            // 3 - Subject
                            // 4 - CF_AtWork
                            // 5 - CF_WorkDays
                            // 6 - CF_Goal

                            // must have same range as visible fields
                            var mergedData = [
                                "", // implementer
                                // types 1
                                0, 0, // FA (count & hours)
                                0, 0, // UI
                                0, 0, // Other
                                0, 0, // PIE
                                0, 0, // SCI
                                0, 0, // Matching
                                // additional 13
                                0, 0, 0, 0, "", 0/*, 0*/]; // 20 cells
                                /*
                                    13 total
                                    14 w.goal
                                    15 days
                                    16 g.goal
                                    17 status
                                    18 % of work
                                    - 19 at work
                                */
                            /*
                            // put task types into their right position as they are in mergedData
                            var taskTypesIndexcesOverMData = [
                                undefined,
                                "FA", "FA",
                                "UI count", "UI hours",
                                "Other count", "Other hours",
                                "PIE count", "PIE hours",
                                "SCI count", "SCI hours",
                                "Matching count", "Matching hours"];
                            */
                            var taskTypes = [undefined, "FA", "UI Migration", "Other", "PIE", "Social Commerce Upgrade", "Matching"];

                            //log('------ megre fn and skip index ' + skipIndex + ' ------');

                            //log('-------- begin data row dump --------------');
                            //log(dataRow);
                            //log('-------- end data row --------------');

                            // skip empty tasks
                            // dataRow[2] = GeneralTime
                            if (dataRow[2] == 0)
                                return;

                            // custom filed type
                            var _currentType = (dataRow[1]==0) ? "FA" : dataRow[1];
                            var _CFT_ProductMatching = ((''+dataRow[3]).indexOf('SSSI_Syndication') >= 0);
                            if (_CFT_ProductMatching)
                                _currentType = "Matching";
                            var _typeIndexCount = taskTypes.indexOf(_currentType) * 2 - 1;
                            var _typeIndexHours = taskTypes.indexOf(_currentType) * 2;

                            // implementer
                            mergedData[0] = dataRow[0];
                            // time on task
                            mergedData[_typeIndexCount] = 1;
                            mergedData[_typeIndexHours] = dataRow[2];
                            // general time (logged work)
                            mergedData[13] = dataRow[2];
                            // weekly goal = (GeneralGoal * UserAtWorkDays) / AllDays
                            mergedData[14] = (dataRow[6] * dataRow[4]) / dataRow[5];
                            // at work
                            //mergedData[19] = dataRow[4];
                            // work days
                            mergedData[15] = dataRow[4];
                            // general goal
                            mergedData[16] = dataRow[6];
                            // % = y = (Total * 100) / WeeklyGoal
                            mergedData[18] = Math.floor((mergedData[13] * 100) / mergedData[14]);

                            // merging with previous data
                            if (!!container) {
                                for (var i = 1; i < 14; i++)
                                    mergedData[i] += container[i];
                                //mergedData[15] += container[15];
                                // % = y = (Total * 100) / WeeklyGoal
                                mergedData[18] = Math.floor((mergedData[13] * 100) / mergedData[14]);
                            }

                            //log(mergedData);
                            //log('----------------------------------------------------');
                            return mergedData;



                        }
                    });

                    //log('******** my full team dump ***********');
                    //log(dataObj);
                    var _resolutionRow = [
                                "<b>Team</b>", // implementer
                                // types 1
                                0, 0, // FA (count & hours)
                                0, 0, // UI
                                0, 0, // Other
                                0, 0, // PIE
                                0, 0, // SCI
                                0, 0, // Matching
                                // additional 13
                                0, 0, 0, 0, "", 0/*, 0*/]; // 20 cells
                                /*
                                    13 total
                                    14 w.goal
                                    15 days
                                    16 g.goal
                                    17 status
                                    18 % of work
                                    - 19 at work
                                */
                    // add summary row 
                    for (var rIdx in dataObj) {
                        for (var cIdx in dataObj[rIdx])
                            if (typeof(dataObj[rIdx][cIdx]) == "number")
                                _resolutionRow[cIdx] += dataObj[rIdx][cIdx];
                    }

                    // recalc %
                    _resolutionRow[18] = Math.floor((_resolutionRow[13] * 100) / _resolutionRow[14]);
                    dataObj.push(_resolutionRow);

                    // Status
                    var _userStatus = [
                        '<span style="color:#990099;font-weight:bold;text-align:right;">VERY BAD<span>',
                        '<span style="color:#dc3912;font-weight:bold;text-align:right;">BAD<span>',
                        '<span style="color:#ff9900;font-weight:bold;text-align:right;">NOT BAD<span>',
                        '<span style="color:#66aa00;font-weight:bold;text-align:left;">GOOD<span>',
                        '<span style="color:#109618;font-weight:bold;text-align:left;">VERY GOOD<span>',
                        '<span style="color:#aaaa11;font-weight:bold;text-align:left;">AWESOME<span>'];
                    for (var rIdx in dataObj) {
                        var ww = (dataObj[rIdx][18] / 25);
                        if (ww > 5) ww = 5;
                        dataObj[rIdx][17] = _userStatus[Math.floor(ww)];
                    }

                    /*
                    for (var rIdx in dataObj) {
                        if (rIdx == 0)
                            continue; // skip headers

                        // Weekly Goal
                        // GeneraGoal - WorkDays
                        // WeeklyGoal - AtWork
                        // WeeklyGoal = (GeneraGoal * AtWork) / WorkDays
                        //dataObj[rIdx][14] = (dataObj[rIdx][16] * dataObj[rIdx][15] / dataObj[rIdx][15] );


                        // Status
                        var _userStatus = '<span style="color:#66aa00;font-weight:bold;float:left;margin-left:50px;">GOOD<span>';
                        if ((dataObj[rIdx][6] - (dataObj[rIdx][7] / 4)) > dataObj[rIdx][7])
                            _userStatus = '<span style="color:#109618;font-weight:bold;float:left;margin-left:25px;">VERY GOOD<span>';
                        if ((dataObj[rIdx][6] - (dataObj[rIdx][7] / 2)) > dataObj[rIdx][7])
                            _userStatus = '<span style="color:#aaaa11;font-weight:bold;float:left;margin-left:00px;font-size:120%;">AWESOME<span>';
                        if (dataObj[rIdx][6] < dataObj[rIdx][7] && (dataObj[rIdx][6] + 1) <= dataObj[rIdx][7])
                            _userStatus = '<span style="color:#ff9900;font-weight:bold;float:right;margin-right:50px;">NOT BAD<span>';
                        if (dataObj[rIdx][6] < dataObj[rIdx][7] && (dataObj[rIdx][6] + (dataObj[rIdx][7] / 4)) <= dataObj[rIdx][7])
                            _userStatus = '<span style="color:#dc3912;font-weight:bold;float:right;margin-right:25px;">BAD<span>';
                        if (dataObj[rIdx][6] < dataObj[rIdx][7] && (dataObj[rIdx][6] + (dataObj[rIdx][7] / 2)) <= dataObj[rIdx][7])
                            _userStatus = '<span style="color:#990099;font-weight:bold;float:right;margin-right:0px;">VERY BAD<span>';
                        dataObj[rIdx][10] = _userStatus;
                        // % of work
                        dataObj[rIdx][11] = Math.floor(dataObj[rIdx][6] * 100 / dataObj[rIdx][6]) + '%';
                        //dataObj[rIdx][9] = dataObj[rIdx][6] > dataObj[rIdx][7] ? '<span style="color:#109618;font-weight:bold;">AWESOME<span>' : ( dataObj[rIdx][6] + 1 >= dataObj[rIdx][7] ? '<span style="color:#3366cc;font-weight:bold;">NOT BAD<span>' : '<span style="color:#dc3912;font-weight:bold;">BAD<span>' );
                    }



                    // colors
                    // awesome: #109618;
                    // not bad: #3366cc;
                    // bad: #dc3912;

                    // add summary row 
                    var _resolutionRow = ["<b>Team</b>",0,0,0,0,0,0,0,0,0,"",""];
                    for (var rIdx in dataObj) {
                        if (rIdx == 0)
                            continue; // skip headers
                        var _lastCIdx = 0;
                        for (var cIdx in dataObj[rIdx]) {
                            _lastCIdx = cIdx;
                            if (cIdx == 0)
                                continue; // skip cell title
                            //log(dataObj[rIdx][cIdx]);
                            if (Math.floor(cIdx) >= dataObj[rIdx].length - 2) {
                                //log('- skipping at : ' + cIdx);
                                continue; // skip last row
                            }
                            //log('running index: ' + cIdx + ' and item count: ' + dataObj[rIdx].length + ' c: ' + (Math.floor(cIdx) + 1) + ' = '+ (cIdx + 1 == dataObj[rIdx].length));
                            // sum data
                            _resolutionRow[cIdx] += dataObj[rIdx][cIdx];
                            _resolutionRow[cIdx] = roundNumber(_resolutionRow[cIdx], 2);
                        }
                        _resolutionRow[_lastCIdx] = roundNumber(_resolutionRow[_lastCIdx], 2);
                        //log('------');
                    }
                    // resoulution row % summary
                    _resolutionRow[11] = Math.floor(_resolutionRow[6] * 100 / _resolutionRow[7]) + '%';


                    dataObj.push(_resolutionRow);
                    */
                    // convert unique object to array
                    //log(dataObj);
                    return dataObj;

                }
            }
        },
        TaskTypesByHours: {
            // info
            name: 'TaskTypesByHours',
            base: visualReport.reportTypes.PIE,
            renderObject: '#reportContainerTaskTypesByHoursID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'Handled Tasks (by hours)',
                    is3D: true
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: Overview');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueFiled: 'Case Type',
                        visibleFields: ["Case Type", "GeneralTime"],
                        customFields: [{Title: "Hours", Value: 1}],
                        removeHeaderKeysInEndUp: ["GeneralTime"], //these fields will be removed in end up
                        customMergeFunction: function (dataRow, skipIndex, container) {
                            /*
                            var mergedData = [];
                            //log('------ megre fn and skip index ' + skipIndex + ' ------');
                            //log(container);
                            //log(dataRow);
                            for (var idx in dataRow) {
                                if (idx == skipIndex) {
                                    mergedData.push((dataRow[idx]==0)?"No Type":dataRow[idx]);
                                } else {
                                    if (!!container && !isNaN(container[idx]-0)) {
                                        //log('container['+idx+'] = ' + container[idx]);
                                        mergedData.push(container[idx] + 1);
                                    }
                                    else {
                                        //log('dataRow['+idx+'] = ' + dataRow[idx]);
                                        mergedData.push(dataRow[idx]);
                                    }
                                }
                            }
                            //log('====================================');
                            //log(mergedData);
                            //log('====================================');
                            return mergedData;
                            */

                            // do not include other tasks (accept Case Record Type only)
                            // skip empty tasks
                            if (dataRow[1] == 0)
                                return;

                            // if you are here than it is support case
                            return [
                                dataRow[0] == '' ? 'No Type' : dataRow[0],
                                container ? container[1] + dataRow[1] : dataRow[1]
                            ];


                        }
                    });
                    // convert unique object to array
                    //log(dataObj);


                    var _order = ['No Type','UI Migration','Other','PIE','Social Commerce Upgrade'];
                    var _newDataObj = [dataObj[0]];
                    // reorder data
                    //log(_newDataObj);
                    //log(dataObj);
                    //log('----------- reorder ---------------');
                    for (var _oidx in _order)
                        for (var _didx in dataObj) {
                            //log('looking for: ' + _order[_oidx]);
                            //log('running data type: ' + dataObj[_didx][0] + ' at index: ' + _didx);
                            if (_order[_oidx] == dataObj[_didx][0]) {
                                //log('---- found data');
                                //log(dataObj[_didx]);
                                _newDataObj.push(dataObj[_didx]);
                                //log(_newDataObj);
                                //log('--------------------------');
                                break;
                            }
                        }

                    //log(_dataObj);
                    return _newDataObj;
                }
            }
        },
        SIvsFAByHours: {
            // info
            name: 'SIvsFAByHours',
            base: visualReport.reportTypes.PIE,
            renderObject: '#reportContainerSIvsFAByHoursID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'FA vs. SI with Product matching (by hours)',
                    is3D: true
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: Overview');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        visibleFields: ["Case Record Type", "Subject", "GeneralTime"],   // running index will be 0 and 1
                        customFields: [{Title: "Hours", Value: 0}],  // running index will be 2
                        removeHeaderKeysInEndUp: ["Subject", "GeneralTime"], //these fields will be removed in end up
                        customMergeFunction: function (dataRow, skipIndex, container, cfg, mo) {
                            var mergedData = [];
                            //log('------ megre fn and skip index ' + skipIndex + ' ------');
                            //log('---------------------------');
                            //log(container);
                            //log('---------------------------');
                            //log(dataRow);
                            // skip empty tasks
                            if (dataRow[2] == 0)
                                return;

                            // custom filed type
                            var _CFT_ProductMatching = ((''+dataRow[1]).indexOf('SSSI_Syndication') >= 0);
                            var _recordType = _CFT_ProductMatching ? 'Product Matching' : dataRow[0];
                            var _recordHours = dataRow[2];

                            if (_CFT_ProductMatching && mo.hasOwnProperty(_recordType))
                                _recordHours += mo[_recordType][1];
                            else
                                if (container)
                                    _recordHours += container[1];

                            if (_CFT_ProductMatching)
                                mergedData = {
                                    'overrideUniqueValue': _recordType,
                                    'data': [_recordType, _recordHours]
                                };
                            else
                                mergedData = [_recordType, _recordHours];


                            //log(mo);
                            //log('====================================');
                            //log('====================================');
                            return mergedData;
                        }
                    });
                    //log(dataObj);
                    return dataObj;
                }
            }
        },
        ComplexityCauseByHours : {
            // info
            name: 'ComplexityCauseByHours',
            base: visualReport.reportTypes.PIE,
            renderObject: '#reportContainerComplexityCauseByHoursID',
            autoshow: true,
            provider: 'GWT',
            // report 
            report: {
                options: {
                    title: 'Complexity Cause (SE) (by hours)',
                    is3D: true
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: Overview');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        visibleFields: ["Complexity Cause (SE)", "Case Record Type", "GeneralTime"],   // running index will be 0 and 1
                        customFields: [{Title: "Hours", Value: 0}],  // running index will be 2
                        removeHeaderKeysInEndUp: ["Case Record Type", "GeneralTime"], //these fields will be removed in end up
                        customMergeFunction: function (dataRow, skipIndex, container, cfg, mo) {

                            // do not include other tasks (accept Case Record Type only)
                            // skip empty tasks
                            if (dataRow[1] != 'Support Team' || dataRow[2] == 0)
                                return;

                            // if you are here than it is support case
                            return [
                                dataRow[0] == '' ? 'None' : dataRow[0],
                                container ? container[1] + 1 : dataRow[2]
                            ];

                            //log('====================================');
                            //log(mo);
                            //log('====================================');
                            //return mergedData;
                        }
                    });
                    //log(dataObj);
                    return dataObj;
                }
            }
        },








        // ************************************
        //             user reports
        // ************************************
        UserDashboard: {
            // general info
            name: 'UserDashboard',
            base: visualReport.reportTypes.DASHBOARD,
            renderObject: '#dashboardContainerUserStatisticID',
            autoshow: false,
            provider: 'GWT',
            // report 
            report: {
                owner: '',
                options: {},
                events: {},
                getReportCharts: function(rawData, dataObj, dashboardUID) {
                    //log('UserDashboard -> getReportCharts -> ' + dashboardUID);
                    var charts = [];

                    // Create a range slider, passing some options
                    var donutRangeSlider = new google.visualization.ControlWrapper({
                      'controlType': 'NumberRangeFilter',
                      'containerId': 'Filter_' + dashboardUID + '_ID',
                      'options': {
                        'filterColumnLabel': 'Donuts eaten'
                      }
                    });

                    //log('Filter_' + dashboardUID + '_ID');

                    // Create a pie chart, passing some options
                    var pieChart = new google.visualization.ChartWrapper({
                      'chartType': 'PieChart',
                      'containerId': 'Chart_' + dashboardUID + '_ID',
                      'options': {
                        'width': 300,
                        'height': 300,
                        'pieSliceText': 'value',
                        'legend': 'right'
                      }
                    });

                    //log('Chart_' + dashboardUID + '_ID');

                    charts = [donutRangeSlider, pieChart];

                    //log(charts);

                    var dItems = {
                        controls: [donutRangeSlider],
                        charts: [pieChart]
                    }

                    return dItems;
                },
                getReportData: function(rawData) {
                    /*
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueFiled: 'Implementer',
                        visibleFields: ["Implementer", "GeneralTime"]
                    });

                    var newDataObj = [];
                    for (var rIdx in dataObj)
                        if (rIdx == 0 || dataObj[rIdx][0] == this.owner)
                            newDataObj.push(dataObj[rIdx]);
                    */
                    // Create our data table.
                    var newDataObj = [
                      ['Name', 'Donuts eaten'],
                      ['Michael' , 5],
                      ['Elisa', 7],
                      ['Robert', 3],
                      ['John', 2],
                      ['Jessica', 6],
                      ['Aaron', 1],
                      ['Margareth', 8]
                    ];

                    //log(newDataObj);
                    //log(this);
                    return newDataObj;
                }
            }
        },
        UserReport_Overview1: {
            // info
            name: 'GeneralTime',
            base: visualReport.reportTypes.BAR,
            renderObject: '#reportContainerUserTaskTotalID',
            autoshow: false,
            provider: 'GWT',
            // report 
            report: {
                owner: '',
                options: {
                    title: 'General Time',
                    hAxis: {
                        //logScale: false,
                        baseline: 0,
                        textStyle: {
                            color: 'white'
                        }
                    },
                    axisTitlesPosition: 'none'
                },
                events: {},
                getReportData: function(rawData) {
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueFiled: 'Implementer',
                        visibleFields: ["Implementer", "GeneralTime"]
                    });

                    var newDataObj = [];
                    for (var rIdx in dataObj)
                        if ((rIdx == 0 || dataObj[rIdx][0] == this.owner) && dataObj[rIdx][1] != 0)
                            newDataObj.push(dataObj[rIdx]);

                    //log(newDataObj);
                    //log(this);
                    return newDataObj;
                }
            }
        },
        UserReport_Overview2: {
            // info
            name: 'TimeTypes',
            base: visualReport.reportTypes.BAR,
            renderObject: '#reportContainerUserTypeAndTypesID',
            autoshow: false,
            provider: 'GWT',
            // report 
            report: {
                owner: '',
                options: {
                    title: 'Time On Task',
                    axisTitlesPosition: 'none'
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: Time On Tasks');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueFiled: 'Implementer',
                        customFields: [
                            {Title: "FA", Value: 0},
                            {Title: "UI Migration", Value: 0},
                            {Title: "Other", Value: 0},
                            {Title: "PIE", Value: 0},
                            {Title: "Social Commerce Upgrade", Value: 0}
                        ],
                        removeHeaderKeysInEndUp: ["Case Type", "GeneralTime"],
                        removeRelevantDataFieldsByHeaderKeysInEndUp: false,
                        visibleFields: ["Implementer", "Case Type", "GeneralTime"],
                        customMergeFunction: function (dataRow, skipIndex, container) {
                            var mergedData = ["",0,0,0,0,0];
                            var taskTypes = [undefined,"FA", "UI Migration", "Other", "PIE", "Social Commerce Upgrade"];

                            //log('------ megre fn and skip index ' + skipIndex + ' ------');

                            var _currentType = (dataRow[1]==0)?"FA":dataRow[1];
                            var _currentTimeOnTask = dataRow[2];
                            var _typeIndex = taskTypes.indexOf(_currentType);

                            // implementer
                            mergedData[0] = dataRow[0];
                            // time on task
                            mergedData[_typeIndex] = dataRow[2];

                            // merging with previous data
                            if (!!container) {
                                mergedData[1] += container[1];
                                mergedData[2] += container[2];
                                mergedData[3] += container[3];
                                mergedData[4] += container[4];
                                mergedData[5] += container[5];
                            }

                            //log(mergedData);
                            //log('--------------------------------------------');
                            return mergedData;
                        }
                    });
                    // convert unique object to array

                    var newDataObj = [];
                    for (var rIdx in dataObj)
                        if (rIdx == 0 || dataObj[rIdx][0] == this.owner)
                            newDataObj.push(dataObj[rIdx]);
                    //log(dataObj);
                    return newDataObj;

                }
            }
        },
        UserReport_Overview3: {
            // info
            name: 'GeneralTime',
            base: visualReport.reportTypes.BAR,
            renderObject: '#reportContainerUserOverviewID',
            autoshow: false,
            provider: 'GWT',
            // report 
            report: {
                owner: '',
                options: {
                    title: 'Communication vs. Implementation',
                    isStacked: true
                },
                events: {},
                getReportData: function(rawData) {
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'simple',
                        visibleFields: ["Implementer", "Case Type", "Time Spent - Communication", "Time Spent - Engineering", "GeneralTime"],
                        mergeData: false
                    });
                    //log(dataObj);
                    // filter by owner
                    var userData = [];
                    for (var rIdx in dataObj)
                        if (rIdx == 0 || dataObj[rIdx][0] == this.owner)
                            userData.push(dataObj[rIdx]);
                    //log(userData);
                    // getting indexes
                    var _i_ct = userData[0].indexOf("Case Type");
                    var _i_tsc = userData[0].indexOf("Time Spent - Communication");
                    var _i_tse = userData[0].indexOf("Time Spent - Engineering");

                    // group tasks by type
                    var userDataByType = {};
                    for (var rIdx in userData)
                        if (userData[rIdx][4] !== 0)
                            if (rIdx != 0) {
                                if (userDataByType[userData[rIdx][_i_ct]]) {
                                    userDataByType[userData[rIdx][_i_ct]].comm += userData[rIdx][_i_tsc];
                                    userDataByType[userData[rIdx][_i_ct]].imp += userData[rIdx][_i_tse];
                                } else {
                                    //log('creating new entry: ' + userData[rIdx][_i_ct]);
                                    userDataByType[userData[rIdx][_i_ct]] = {comm: userData[rIdx][_i_tsc], imp: userData[rIdx][_i_tse]};
                                }
                            }
                    // transform typed tasks
                    var dataObj = [['Time', 'Communication', 'Implementation']];
                    for (var pIdx in userDataByType) {
                        var _uItem = userDataByType[pIdx];
                        dataObj.push([pIdx == '0' ? 'FA' : pIdx, _uItem.comm, _uItem.imp]);
                    }
                    //log(userDataByType);
                    //log(newDataObj);
                    //log(dataObj);
                    //return newDataObj;
                    return dataObj;

                }
            }
        },
        UserReport_Overview4: {
            // info
            name: 'GeneralTime',
            base: visualReport.reportTypes.BAR,
            renderObject: '#reportContainerUserHandledTasksID',
            autoshow: false,
            provider: 'GWT',
            // report 
            report: {
                owner: '',
                options: {
                    title: 'Handled Tasks'
                },
                events: {},
                getReportData: function(rawData) {
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'simple',
                        visibleFields: ["Implementer", "Case Type", "GeneralTime"],
                        mergeData: false
                    });
                    //log(dataObj);
                    // filter by owner
                    var userData = [];
                    for (var rIdx in dataObj)
                        if (rIdx == 0 || dataObj[rIdx][0] == this.owner)
                            userData.push(dataObj[rIdx]);
                    // group tasks by type
                    var userDataByType = {};
                    for (var rIdx in userData)
                        if (userData[rIdx][2] != 0)
                            if (rIdx != 0) {
                                if (userDataByType[userData[rIdx][1]])
                                    userDataByType[userData[rIdx][1]]++;
                                else
                                    userDataByType[userData[rIdx][1]] = 1
                            }
                    // transform typed tasks
                    var dataObj = [['Type', 'Task Count']];
                    for (var pIdx in userDataByType) 
                        dataObj.push([pIdx == '0' ? 'FA' : pIdx, userDataByType[pIdx]]);
                    //log(userDataByType);
                    //log(newDataObj);
                    //log(dataObj);
                    //return newDataObj;
                    return dataObj;

                }
            }
        },
        UserReport_Overview5: {
            // info
            name: 'UserReport_Overview5',
            base: visualReport.reportTypes.PIE,
            renderObject: '#reportContainerUserInTeamByTimeID',
            autoshow: false,
            provider: 'GWT',
            // report 
            report: {
                owner: '',
                options: {
                    title: 'You In Team By Time',
                    is3D: true
                },
                events: {},
                getReportData: function(rawData) {
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'simple',
                        useHeaders: false,
                        visibleFields: ["Implementer", "GeneralTime"],
                        mergeData: false
                    });
                    //log(dataObj);
                    // filter by owner
                    var newDataObj = [
                        ['Owner', 'Time'],
                        [this.owner, 0], 
                        ['Team', 0]
                    ];
                    //log(dataObj);
                    for (var rIdx in dataObj) {
                        if (dataObj[rIdx][1] !== 0) {
                            if (dataObj[rIdx][0] == this.owner)
                                newDataObj[1][1] += dataObj[rIdx][1];
                            else
                                newDataObj[2][1] += dataObj[rIdx][1];
                        }
                    }
                    //log(userDataByType);
                    //log(newDataObj);
                    //log(dataObj);
                    //return newDataObj;
                    return newDataObj;

                }
            }
        },
        UserReport_Overview6: {
            // info
            name: 'UserReport_Overview6',
            base: visualReport.reportTypes.PIE,
            renderObject: '#reportContainerUserInTeamByTasksID',
            autoshow: false,
            provider: 'GWT',
            // report 
            report: {
                owner: '',
                options: {
                    title: 'You In Team By Cases',
                    is3D: true
                },
                events: {},
                getReportData: function(rawData) {
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'simple',
                        useHeaders: false,
                        visibleFields: ["Implementer", "GeneralTime"],
                        mergeData: false
                    });
                    //log(dataObj);
                    // filter by owner
                    var newDataObj = [
                        ['Owner', 'Task Count'],
                        [this.owner, 0], 
                        ['Team', 0]
                    ];
                    //log(dataObj);
                    for (var rIdx in dataObj) {
                        if (dataObj[rIdx][1] !== 0) {
                            if (dataObj[rIdx][0] == this.owner)
                                newDataObj[1][1]++;
                            else
                                newDataObj[2][1]++;
                        }
                    }
                    //log(userDataByType);
                    //log(newDataObj);
                    //log(dataObj);
                    //return newDataObj;
                    return newDataObj;

                }
            }
        },
        UserReport_Overview7: {
            // info
            name: 'UserReport_Overview7',
            base: visualReport.reportTypes.TABLE,
            renderObject: '#reportContainerUserHandledCasesID',
            autoshow: false,
            provider: 'GWT',
            // report 
            report: {
                owner: '',
                options: {
                    title: 'Team Overview',
                    allowHtml: true
                },
                events: {},
                getReportData: function(rawData) {
                    // do all transformations over data
                    //log('getting report data: Time On Tasks');
                    //log(rawData);
                    var dataObj = visualReport.getTransformedData(rawData, {
                        type: 'GDataTable',
                        uniqueField: 'Case Number',
                        customData: this.owner,
                        removeHeaderKeysInEndUp: ['Implementer'],
                        removeRelevantDataFieldsByHeaderKeysInEndUp: true,
                        visibleFields: ["Case Number", "Implementer", "Case Record Type", "Case Type", "Subject", "Status", "Time Spent - Communication", "Time Spent - Engineering", "GeneralTime"],  // involved data fields (dataRow will contain only 3 cells with relevant values)
                        customMergeFunction: function (dataRow, skipIndex, container, customData) {
                            if (customData == dataRow[1] && dataRow[8] != 0) {
                                if (dataRow[3] == '0')
                                    dataRow[3] = 'FA';
                                dataRow[0] = '<span>' + dataRow[0] + '</span>';
                                //dataRow[7] = '<h3><b>' + dataRow[7] + '</b></h3>';
                                return dataRow;
                            }
                        }
                    });

                    // add summary row 
                    var _resolutionRow = ["-","-","-","-","-",0,0,0];
                    for (var rIdx in dataObj) {
                        if (rIdx == 0)
                            continue; // skip headers
                        for (var cIdx in dataObj[rIdx]) {
                            if (cIdx < 5)
                                continue; // skip text cells
                            //log('running index: ' + cIdx + ' and item count: ' + dataObj[rIdx].length + ' c: ' + (Math.floor(cIdx) + 1) + ' = '+ (cIdx + 1 == dataObj[rIdx].length));
                            // sum data
                            _resolutionRow[cIdx] += dataObj[rIdx][cIdx];
                        }
                        //log('------');
                    }

                    for (var rIdx in dataObj) {
                        if (rIdx == 0)
                            continue; // skip headers
                        for (var cIdx in dataObj[rIdx]) {
                            dataObj[rIdx][cIdx] = '<span>' + dataObj[rIdx][cIdx] + '</span>';
                        }
                        //log('------');
                    }

                    _resolutionRow[5] = '<h2><b>' + _resolutionRow[5] + '</b></h2>';
                    _resolutionRow[6] = '<h2><b>' + _resolutionRow[6] + '</b></h2>';
                    _resolutionRow[7] = '<h2><b>' + _resolutionRow[7] + '</b></h2>';

                    dataObj.push(_resolutionRow);

                    //log(dataObj);
                    return dataObj;

                }
            }
        }
    }
} 
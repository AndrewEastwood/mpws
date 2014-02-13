/* GUI report script: 5/9/2012 */
{
    // ************************************
    //             common reports
    // ************************************
    RL_TasksVsCasesPerRelease: {
        // info
        name: 'RL_TasksVsCasesPerRelease',
        base: visualReport.reportTypes.HISTOGRAM,
        useData: '__ALL__',
        renderObject: 'div#reportContainerTasksPerReleaseID',
        autoshow: true,
        provider: 'GWT',
        // report 
        report: {
            options: {
                title: 'Team Tasks',
                //vAxis: {title: '',  titleTextStyle: {color: 'red'}},
                //hAxis: {title: 'Reported hours'},
                //height: '400px'
                hAxis: {
                    logScale: false,
                    baseline: 0
                }
            },
            events: {},
            getReportData: function(rawData) {
                log('======= RL_TasksPerRelease =========');
                //log(rawData);
                //log('olololololol');

                var dataObj = [["Release", "Tasks", "Hours"]];
                for (var _rL in rawData) {
                    var _nonEmptyTasksPerRelease = 0;
                    var _hours = 0;
                    //log(rawData[_rL]);
                    for (var _rowIdx in rawData[_rL]['rows']) {
                        //log('Case time on task: ' + rawData[_rL][_rowIdx]['GeneralTime']);
                        if (rawData[_rL]['rows'][_rowIdx]['GeneralTime'] != 0)
                            _nonEmptyTasksPerRelease++;
                            _hours += rawData[_rL]["rows"][_rowIdx]["GeneralTime"];
                        }
                    dataObj.push([rawData[_rL]["rows"][0]["CF_ReleaseName"], _nonEmptyTasksPerRelease, _hours]);
                }

                //log(dataObj);
                return dataObj;
            }
        }
    },
    RL_ReportedHours: {
        // info
        name: 'RL_ReportedHours',
        base: visualReport.reportTypes.HISTOGRAM,
        useData: '__ALL__',
        renderObject: 'div#reportContainerReportedHoursID',
        autoshow: false,
        provider: 'GWT',
        // report 
        report: {
            options: {
                title: 'Team Reported Hours',
                //vAxis: {title: '',  titleTextStyle: {color: 'red'}},
                //hAxis: {title: 'Reported hours'},
                //height: '400px'
                hAxis: {
                    logScale: false,
                    baseline: 0
                }
            },
            events: {},
            getReportData: function(rawData) {
                log('======= RL_ReportedHours =========');
                //log(rawData);
                //log('olololololol');

                var dataObj = [["Release", "Hours"]];
                for (var _rL in rawData) {
                    for (var _rowIdx in rawData[_rL]["rows"]) {
                        _hours += rawData[_rL]["rows"][_rowIdx]["GeneralTime"];
                    }
                    dataObj.push([rawData[_rL]["rows"][0]["CF_ReleaseName"], _hours]);
                }

                //log(dataObj);
                return dataObj;
            }
        }
    },
    RL_ReleaseActivity: {
        // info
        name: 'RL_ReleaseActivity',
        base: visualReport.reportTypes.LINE,
        useData: '__ALL__',
        renderObject: 'div#reportContainerReleaseActivityID',
        autoshow: true,
        provider: 'GWT',
        // report 
        report: {
            options: {
                title: 'Team Release Activity',
                curveType: 'function',
                pointSize: 5
                //vAxis: {title: '',  titleTextStyle: {color: 'red'}},
                //hAxis: {title: 'Reported hours'},
                //height: '400px'
            },
            events: {},
            getReportData: function(rawData) {
                log('======= RL_ReleaseActivity =========');
                //log(rawData);
                //log('olololololol');
                var dataObj = [["Release", "Reported", "Expected"]];
                for (var _rL in rawData) {
                    var _hoursR = 0;
                    var _hoursE = 0;
                    var _usersGoals = {};
                    for (var _rowIdx in rawData[_rL]["rows"]) {
                        // add\rewrite user goal
                        var _impName = rawData[_rL]["rows"][_rowIdx]["Implementer"];
                        if (_usersGoals[_impName]) {
                            _usersGoals[_impName]['NormalGoal'] = rawData[_rL]["rows"][_rowIdx]["CF_Goal"];
                            _usersGoals[_impName]['RealGoal'] = (rawData[_rL]["rows"][_rowIdx]["CF_Goal"] * rawData[_rL]["rows"][_rowIdx]["CF_AtWork"] / rawData[_rL]["rows"][_rowIdx]["CF_WorkDays"]);
                            _usersGoals[_impName]['CF_AtWork'] = rawData[_rL]["rows"][_rowIdx]["CF_AtWork"];
                            _usersGoals[_impName]['CF_WorkDays'] = rawData[_rL]["rows"][_rowIdx]["CF_WorkDays"];
                        }
                        else
                            _usersGoals[_impName] = {'NormalGoal': 0, 'RealGoal': 0};
                        // summ reported hours
                        _hoursR += rawData[_rL]["rows"][_rowIdx]["GeneralTime"];
                        //_hoursE += (rawData[_rL]["rows"][_rowIdx]["CF_Goal"]);
                        //var ____currH = (rawData[_rL]["rows"][_rowIdx]["CF_Goal"] * rawData[_rL]["rows"][_rowIdx]["CF_AtWork"] / rawData[_rL]["rows"][_rowIdx]["CF_WorkDays"]);
                        //log(rawData[_rL]["rows"][_rowIdx]["CF_Goal"] +'*'+ rawData[_rL]["rows"][_rowIdx]["CF_AtWork"] +'/'+ rawData[_rL]["rows"][_rowIdx]["CF_WorkDays"] +'='+____currH );
                        // CF_Goal CF_AtWork
                        //log(_hoursR + ' < R:E > ' + _hoursE);
                    }
                    //log(_usersGoals);
                    // summ users goals for running release
                    for (var _rowIdx in _usersGoals)
                        _hoursE += _usersGoals[_rowIdx]['RealGoal'];
                    dataObj.push([rawData[_rL]["rows"][0]["CF_ReleaseName"], _hoursR, _hoursE]);
                }
                //log(dataObj);
                return dataObj;
            }
        }
    },
    RL_UsersActivity: {
        // info
        name: 'RL_UsersActivity',
        base: visualReport.reportTypes.COMBO,
        useData: '__ALL__',
        renderObject: 'div#reportContainerUsersActivityID',
        autoshow: true,
        provider: 'GWT',
        // report 
        report: {
            options: {
                title: 'Team Release Activity',
                //vAxis: {title: '',  titleTextStyle: {color: 'red'}},
                //hAxis: {title: 'Reported hours'},
                //height: '400px'
                vAxis: {title: "Hours"},
                hAxis: {title: "Releases", logScale: true},
                seriesType: "bars",
                series: {15:{type: "line", lineWidth: 2, areaOpacity: 0.5, curveType: 'function',pointSize: 6}}
            },
            events: {},
            getReportData: function(rawData) {
                log('======= RL_UsersActivity =========');
                //log(rawData);
                //log('olololololol');

                var dataObj = [];
                var _firstColumn = [];
                for (var _rL in rawData) {
                    //var _hoursR = 0;
                    //var _hoursE = 0;
                    var _usersGoals = {};
                    var _dataColumn = [];
                    //var __totCases = 0;
                    //var __sumCases = 0;
                    //log('-------------------------- next release:');
                    for (var _rowIdx in rawData[_rL]["rows"]) {
                        // add\rewrite user goal
                        var __entry = rawData[_rL]["rows"][_rowIdx];
                        var _impName = __entry["Implementer"];
                        /*if (_impName == 'Pavlo Bilyk') {
                            __totCases++;
                            __sumCases+=__entry["GeneralTime"];
                            //log('[' + __entry["Case Number"] + '] ==> ' + __entry["GeneralTime"] + '    E: ' + __sumCases);
                        }*/
                        if (!!!_usersGoals[_impName]) {
                            _usersGoals[_impName] = {};
                            _usersGoals[_impName]['Name'] = _impName;
                            _usersGoals[_impName]['NormalGoal'] = __entry["CF_Goal"];
                            _usersGoals[_impName]['RealGoal'] = (__entry["CF_Goal"] * __entry["CF_AtWork"] / __entry["CF_WorkDays"]);
                            _usersGoals[_impName]['CF_AtWork'] = __entry["CF_AtWork"];
                            _usersGoals[_impName]['CF_WorkDays'] = __entry["CF_WorkDays"];
                            _usersGoals[_impName]['GeneralTime'] = __entry["GeneralTime"];
                        } else
                            _usersGoals[_impName]['GeneralTime'] += __entry["GeneralTime"];
                        //if (_impName == 'Pavlo Bilyk')
                        //    log(__entry["GeneralTime"]);

                        //log(_impName + ' => ' + _usersGoals[_impName]['GeneralTime']);
                        // summ reported hours
                        //_hoursR += rawData[_rL]["rows"][_rowIdx]["GeneralTime"];
                        //_hoursE += (rawData[_rL]["rows"][_rowIdx]["CF_Goal"]);
                        //var ____currH = (rawData[_rL]["rows"][_rowIdx]["CF_Goal"] * rawData[_rL]["rows"][_rowIdx]["CF_AtWork"] / rawData[_rL]["rows"][_rowIdx]["CF_WorkDays"]);
                        //log(rawData[_rL]["rows"][_rowIdx]["CF_Goal"] +'*'+ rawData[_rL]["rows"][_rowIdx]["CF_AtWork"] +'/'+ rawData[_rL]["rows"][_rowIdx]["CF_WorkDays"] +'='+____currH );
                        // CF_Goal CF_AtWork
                        //log(_hoursR + ' < R:E > ' + _hoursE);
                    }
 
                    //log(_usersGoals);
                    //log('Pavlo Bilyk ______ total cases = ' + __totCases);
                    
                    if (!_firstColumn.length) {
                        _firstColumn = ['Release'];
                        for (var _rowIdx in _usersGoals)
                            _firstColumn.push(_usersGoals[_rowIdx]['Name']);
                        // adding header ruw
                        _firstColumn.push('Average');
                        dataObj.push(_firstColumn);
                    }

                    _dataColumn.push(rawData[_rL]["rows"][0]["CF_ReleaseName"]);
                    var _avgHours = 0;
                    for (var _rowIdx in _usersGoals) {
                        _avgHours += _usersGoals[_rowIdx]['GeneralTime'];
                        _dataColumn.push(_usersGoals[_rowIdx]['GeneralTime']);
                    }
                    // avg. hours
                    _dataColumn.push(roundNumber(_avgHours / (_dataColumn.length - 1), 2));
                    dataObj.push(_dataColumn);


                    /*
                    // summ users goals for running release
                    if (!_headerRow.length) {
                        for (var _rowIdx in _usersGoals)
                            _headerRow.push(_rowIdx);
                        // adding header ruw
                        dataObj.push(_headerRow);
                    }
                    // adding data
                    var _hoursE = [];
                    for (var _rowIdx in _usersGoals)
                        _hoursE.push(_usersGoals[_rowIdx]['GeneralTime']);
                        
                        */
                        
                        
                    //dataObj.push(_hoursE);
                    
                    
                }

                log(dataObj);
                return dataObj;
            }
        }
    },
    RL_UserActivityLinear2: {
        // info
        name: 'RL_UserActivityLinear2',
        base: visualReport.reportTypes.LINE,
        useData: '__ALL__',
        renderObject: 'div#reportContainerUsersActivityLinearID',
        autoshow: true,
        provider: 'GWT',
        // report 
        report: {
            options: {
                title: 'Team Release Activity Linear',
                curveType: 'function',
                vAxis : {
                    gridlines: {color: '#333', count: 60},
                    minValue: 0,
                    logScale: false,
                    viewWindowMode: 'explicit',
                    viewWindow: {
                        min: 0,
                        max: 40
                    }
                },
                pointSize: 6,
                height: 2000,
                //width: 1800
            },
            events: {},
            getReportData: function(rawData) {
                log('======= RL_UserActivityLinear2 =========');
                var dataObj = [];
                var _firstColumn = [];
                for (var _rL in rawData) {
                    var _usersGoals = {};
                    var _dataColumn = [];
                    for (var _rowIdx in rawData[_rL]["rows"]) {
                        // add\rewrite user goal
                        var __entry = rawData[_rL]["rows"][_rowIdx];
                        var _impName = __entry["Implementer"];
                        if (!!!_usersGoals[_impName]) {
                            _usersGoals[_impName] = {};
                            _usersGoals[_impName]['Name'] = _impName;
                            _usersGoals[_impName]['NormalGoal'] = __entry["CF_Goal"];
                            _usersGoals[_impName]['RealGoal'] = (__entry["CF_Goal"] * __entry["CF_AtWork"] / __entry["CF_WorkDays"]);
                            _usersGoals[_impName]['CF_AtWork'] = __entry["CF_AtWork"];
                            _usersGoals[_impName]['CF_WorkDays'] = __entry["CF_WorkDays"];
                            _usersGoals[_impName]['GeneralTime'] = __entry["GeneralTime"];
                        } else
                            _usersGoals[_impName]['GeneralTime'] += __entry["GeneralTime"];
                    }

                    if (!_firstColumn.length) {
                        _firstColumn = ['Release'];
                        for (var _rowIdx in _usersGoals)
                            _firstColumn.push(_usersGoals[_rowIdx]['Name']);
                        dataObj.push(_firstColumn);
                    }

                    _dataColumn.push(rawData[_rL]["rows"][0]["CF_ReleaseName"]);
                    for (var _rowIdx in _usersGoals) {
                        _dataColumn.push(_usersGoals[_rowIdx]['GeneralTime']);
                    }
                    dataObj.push(_dataColumn);
                }
                log(dataObj);
                return dataObj;
            }
        }
    }
} 
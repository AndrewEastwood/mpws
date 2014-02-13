APP.Modules.register("widgets/dataTable", [], [

    'lib/jquery',
    'lib/underscore',
    'model/BTableModelClass',
    'view/BTableViewClass',
    'lib/utils',
    'lib/htmlComponents',

], function (app, Sandbox, $, _, BTableModelClass, BTableViewClass, Utils, HtmlComponents){

    function DataTable (/* configs */) {

        var _configs = [].slice.call(arguments, 0);
        var _dataTableModel = null;
        var _dataTableView = null;
        var _config = {
            tableName: false,
            id : "wgt_" + (new Date()).getTime() + '_' + Utils.getRandomString(),
            source : {
                url: null,
                data: null
            },
            container : false,
            style: {
                showHeader : true,
                cellPrefix: 'data-table-cell-',
                cellContent: 'data-table-cell-content',
                setRowCustomStateByKey: false
            },
            custom: {
                // additional features:
                // attachToWindowResize: false,
                // relatedContainers: null,
                // sortLastRow: true,
                // showSummary: {
                //     label: "Summary:",
                //     column: "someColumnName",
                //     append: true,
                //     prepend: false
                // },
                // forceTableMaxWidth: false,
                // doRefreshTillFirstResize: 100 (ms)
            },
            messages: {
                empty: 'Data is empty'
            },
            columns : {
                // column options
                // name: {
                //     isCustom: true, // default is false
                //     isAttribute: false, // default is false
                //     isVisible : true, // default is false
                //     isEditable : false, // default is false
                //     isSortable: true, // default is false
                //     isInAttributeRow: false, // default is undefined
                //     inRowDataValue: false, // default is the value of dataTable data by column key
                //     inRowAttributeValue: "", // default is undefined
                //     displayInAttributeRow: true, // default is false
                //     displayIndex : 100, // default is 100
                //     overflowEllipsis : true, // default is false
                //     caption: "Name", // default is empty string
                //     width: 50, // default is undefined
                //     minWidth: '200px', // default is undefined
                //     maxWidth: '400px', // default is undefined
                //     useSmartWidth: true, // default is false
                //     overflowEllipsis: false, // default is true
                //     type: 'money', // possible values: ['numeric', 'text', 'money'] default is text
                //     formatValue: function (cellContent, viewCell, modelCell) {
                //         return Moment(cellContent).format("MMM D, YYYY");
                //     }
                //     formatCaption: function (cellContent, viewCell, modelCell) {
                //         return Moment(cellContent).format("MMM D, YYYY");
                //     }
                // }
            },
            events: {
                // available events
                // onDataParse
                // onDataAdded
                // onDataSorted
                // onDoSort
                // onDataEmpty
                // onRenderComplete
                // onModeEdit
                // onModeEditSave
                // onModeEditCancel
                // onNormal
                // onCellHeaderClick
                // onCellDataClick
            }
        };
        //app.log('dataTable >> constructor: ', _.clone(_configs));

        this.setConfiguration = function (newConfig, extend) {
            if (!newConfig)
                return false;

            if (_.isArray(newConfig)) {
                var cfg = _.clone(newConfig);
                newConfig.unshift(true, {});
                newConfig = $.extend.apply($, newConfig);
                //app.log('dataTable >> making new configuration: ', _.clone(newConfig));
            }

            if (extend)
                _config = $.extend(true, {}, _config, newConfig);
            else
                _config = newConfig;

            return this;
        }

        this.getConfiguration = function () {
            return _config;
        }

        this.getDataTableModel = function () {
            return _dataTableModel;
        }

        this.getDataTableView = function () {
            return _dataTableView;
        }

        this.getDataTableElement = function () {
            return _dataTableView.$el;
        }

        this.dataLoadingAnimation = function (stop) {
            if (!_config.container)
                return;
            // hide/show ajax-loading animation
            if (stop)
                $(_config.container).removeClass(HtmlComponents.CSS_RENDER_LOADING);
            else {
                if (!$(_config.container).hasClass(HtmlComponents.CSS_RENDER_LOADING))
                    $(_config.container).html(' ').addClass(HtmlComponents.CSS_RENDER_LOADING);
            }
        }

        this.render = function (url, data, callback) {

            // app.log(true, 'dataTable >> render with config:', _config);
            var self = this;

            if (!_.isString(url) && _.isObject(url) && !data) {
                data = url;
                url = null;
            }

            // adjust args
            if (!callback) {

                if (_.isFunction(data)) {
                    callback = data;
                    data = null;
                }

                if (_.isFunction(url)) {
                    callback = url;
                    url = null;
                }
            }

            if (url)
                _config.source.url = url;
            if (data)
                _config.source.data = data;

            var _sourceURL = Utils.combineUrl(_config.source.url, _config.source.data);
            // app.log(true, 'dataTable >> model new url', _sourceURL);

            self.dataLoadingAnimation();

            _dataTableModel = new BTableModelClass({
                config: _config
            });

            // window._dataTableModel = _dataTableModel;
            _dataTableModel.on("table:state", function(state) {
                // app.log('------state is changed:', state);
                if (state === "dataEmpty")
                    self.dataLoadingAnimation(true);
                if (state === "renderComplete") {
                    _dataTableModel.refresh();
                    // this will be invoked only once
                    // if (_config.custom && _config.custom.doRefreshTillFirstResize && _config.custom.attachToWindowResize) {
                    //     // set refresh interval
                    //     var _limit = 3;
                    //     _config.custom.doRefreshTillFirstResize = setInterval(function(){
                    //         _dataTableModel.refresh();
                    //         if (_limit < 0)
                    //             clearInterval(_config.custom.doRefreshTillFirstResize);
                    //         _limit--;
                    //     }, _config.custom.doRefreshTillFirstResize);
                    //     // listen to widnwo resize event and to clear timeout
                    //     Sandbox.eventSubscribe('page:resized', function(){
                    //         if (_config.custom.doRefreshTillFirstResize) {
                    //             clearInterval(_config.custom.doRefreshTillFirstResize);
                    //             _config.custom.doRefreshTillFirstResize = false;
                    //         }
                    //     });
                    //}
                    if (_.isFunction(callback))
                        callback.call(self.getDataTableView(), self.getDataTableModel());
                    self.dataLoadingAnimation(true);
                }
            });

            if (_sourceURL)
                _dataTableModel.url = _sourceURL;

            _dataTableView = new BTableViewClass({
                model : _dataTableModel
            });

            // app.log(true, url, data, callback)
            if (_sourceURL) {
                // app.log(true, 'widget/dataTable >> render: _dataTableModel.fetch();');
                _dataTableModel.fetch({
                    error: function () {
                        _dataTableModel.parse({});
                        dhtmlx.message("Server Error");
                    }
                });
            }
            else if (data) {
                // app.log(true, 'widget/dataTable >> render: _dataTableModel.parse();');
                _dataTableModel.parse(data);
            }
            else
                _dataTableView.render();

            return this;
        }

        this.setConfiguration(_configs, true);
    }

    return DataTable;

});
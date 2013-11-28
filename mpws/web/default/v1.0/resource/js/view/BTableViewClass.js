qB.Modules.register("view/BTableViewClass", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'view/BRowViewClass',
    'lib/utils',
    'lib/htmlComponents',
    'lib/sprintf',

], function(qB, Sandbox, $, _, Backbone, BRowViewClass, Utils, HtmlComponents, StrFormat) {

    var _libHtml = new HtmlComponents();
    var _logThisFile = false;

    var BTableViewClass = Backbone.View.extend({
        // tagName : 'div',
        // className : 'data-table',
        template: function () {
            return _libHtml.generateTag({
                tag: 'div',
                attr: {
                    class: 'data-table render-mode-normal'
                }
            });
        },
        initialize : function () {
            // var self = this;
            // qB.log(_logThisFile, 'BTableViewClass >> initialize');
            this.model.on('table:state', this.customStateDispatcher, this);
        },
        render : function (extraElements) {
            // get and render all rows

            // clear container and add rows
            var config = this.model.getConfig();

            // create list of row views
            var rowCollection = this.model.get('rowCollection');
            var itemsToRender = [];

            // qB.log(_logThisFile, 'BTableViewClass >> table rendering', this, rowCollection);

            // render collection data
            if (rowCollection.length) {

                // setup group title
                var _groupTitle = (config.custom && config.custom.groupTitle) || false;
                if (typeof _groupTitle === "function")
                    _groupTitle = _groupTitle();

                var _rowGroup = _libHtml.generateTag({
                    tag: 'div',
                    attr: {
                        class: 'data-table-row-group'
                    }
                });
                var _rowGroupTitle = _libHtml.generateTag({
                    tag: 'h3',
                    attr: {
                        class: 'data-table-group-title'
                    },
                    html: _groupTitle
                });
                var _rowGroupRows = _libHtml.generateTag({
                    tag: 'div',
                    attr: {
                        class: 'data-table-row-group-rows'
                    }
                });

                // add header
                var rowHead = this.model.get('rowHead');
                if (rowHead) {
                    var viewRowHead = null;

                    // if (rowHead.view) {
                    //     qB.log('---- adding existed view of row header');
                    //     viewRowHead = rowHead.view;
                    // } else {
                        viewRowHead = new BRowViewClass({
                            model : rowHead
                        });
                        // rowHead.view  = viewRowHead;
                    // }

                    viewRowHead.render();

                    itemsToRender.push(viewRowHead.$el);
                }

                // add table data
                rowCollection.forEach(function(rowModel){
                    // qB.log('before row adding', rowModel);
                    var view = null;

                    // if (rowModel.view) {
                        // qB.log('---- adding existed view of row data');
                        // view = rowModel.view;
                    // } else {
                        view = new BRowViewClass({
                            model : rowModel
                        });
                        // rowModel.view  = view;
                        view.render();
                    // }
                    // qB.log('before row adding');
                    _rowGroupRows.append(view.$el);
                });

                // if (rowCollection.length)
                // add rows
                //_rowGroupRows.append(viewRows);

                // qB.log(true, '_rowGroupTitle.text()', _rowGroupTitle.text());
                // setup group
                _rowGroup.append(_rowGroupTitle);
                _rowGroup.append(_rowGroupRows);

                itemsToRender.push(_rowGroup);


            }

            // setup group title
            var _tableTitle = (config.custom && config.custom.tableTitle) || false;
            if (typeof _tableTitle === "function")
                _tableTitle = _tableTitle();

            if (_tableTitle)
                itemsToRender.unshift(_libHtml.generateTag({
                    tag: 'h3',
                    attr: {
                        class: 'data-table-title'
                    },
                    html: _tableTitle
                }));

            this.$el = this.template();
            this.$el.append(itemsToRender);

            qB.log(_logThisFile, 'itemsToRender', itemsToRender);
            qB.log(_logThisFile, 'this.$el', this.$el);

            // // this.$el.append();
            if (extraElements) {
                // qB.log(_logThisFile, 'Extra elements to redner: ', extraElements)
                this.$el.append(extraElements);
            }

            // add attributes
            var cssID = config.tableName;
            if (cssID) {
                var className = this.$el.attr('class').split(' ')[0] + '_' + cssID;
                this.$el.attr('id', className + '_ID');
                this.$el.addClass(className);
            }

            this.model.trigger("table:state", "renderComplete", this);

            return this;
        },
        customStateDispatcher: function (state, responce, callback) {

            responce = responce || {};

            qB.log(_logThisFile, 'BTableViewClass >>> customStateDispatcher: state: ', state, ' responce', responce)

            this.model.set('state', state);

            var config = this.model.getConfig();
            var self = this;
            var allowCustomEvents = true;
            var _embeddedElement = self.$el;

            var _renderIntoContainer = function () {
                // render into container
                // if (config.container) {// clone with events into container {
                //     $(config.container).html('');
                //     $(config.container).append(_embeddedElement);
                //     // $(config.container).append(_embeddedElement);
                // }
                if (config.container)
                    _libHtml.renderIntoContainer(config.container, _embeddedElement);
            }

            var _showDataSummary = function () {
                // show summary
                if (config.custom && config.custom.showSummary) {
                    var configSummary = config.custom.showSummary;

                    var summaryData = self.model.customGetSummaryDataByColumn(configSummary.column);
                
                    // show summary data
                    if (summaryData) {
                        var summaryRow = _libHtml.getLabelValue("Total:", StrFormat.sprintf("%.2f", summaryData), "summary datatable-summary");

                        if (configSummary.append)
                            config.container.append(summaryRow.el.clone());
                        if (configSummary.prepend)
                            config.container.prepend(summaryRow.el.clone());
                    }
                }
            }

            var _attachResizer = function () {
                // attach customs
                if (config.custom.attachToWindowResize) {
                    qB.log(_logThisFile, '_attachResizer', config, _embeddedElement, true);
                    // window.A = {};
                    // window.A.Utils = Utils;
                    // window.A.config = config;
                    // window.A._embeddedElement = _embeddedElement;
                    Utils.setTableColumnsWidth(config, _embeddedElement, true);
                }
            }

            var _showUIStateLabelFn = function () {
                var uiState = self.model.getState();
                // add message
                if (config.messages && config.messages[uiState])
                    self.render(_libHtml.getLabelWarn(config.messages[uiState]));
                else
                    self.render();
            }

            var _onRenderCompletePostFn = function () {
                _renderIntoContainer();
                _showDataSummary();
                _attachResizer();
                // setTimeout(_attachResizer, 100);
            }

            qB.log(_logThisFile, 'BTableViewClass >> customStateDispatcher state=', state);

            switch(state) {
                case "dataParse":
                    break;
                case "dataAdded":
                case "dataSorted":
                    this.render();
                    break;
                case "doSort":
                    this.model.customRowsSort(responce.modelCell);
                    break;
                case "dataEmpty":
                    this.model.setState('empty');
                    _showUIStateLabelFn();
                    // if (config.container)
                    //     $(config.container).removeClass(HtmlComponents.CSS_RENDER_LOADING);
                    // _showUIStateLabelFn();
                    break;
                case "renderComplete":
                    _onRenderCompletePostFn();
                    // if (config.container)
                    //     $(config.container).removeClass(HtmlComponents.CSS_RENDER_LOADING);
                    break;
                case "modeEditStart":
                    // _showUIStateLabelFn();
                    // this.$el.addClass('render-mode-edit');
                    // this.$el.removeClass('render-mode-normal');
                    // qB.log('LOL', responce);
                    // _attachResizer();
                    _attachResizer();
                    break;
                case "modeEditSave":
                case "modeEditCancel":
                    // qB.log(responce);
                    // qB.log(_logThisFile, 'modeEditSave/modeEditCancel');
                    this.model.setStateNormal();
                    _attachResizer();
                // case "modeEditEnd":
                    // this.$el.addClass('render-mode-normal');
                    // this.$el.removeClass('render-mode-edit');
                    break;
                case "refresh":
                    _attachResizer();
                    break;
                case "update":
                    this.render();
                    break;
                case "cellHeaderClick":
                    if (responce.modelCell && this.model.isNormal()) {
                        // sort rows by clicked cell if possible
                        responce.modelCell.customSortStart();
                    }
                    break;
                case "cellDataClick":
                    qB.log(_logThisFile, 'cellDataClick');
                    if (responce.modelCell && this.model.isNormal()) {
                        // or start edit (if possible)
                        responce.modelCell.customEditStart(responce);
                    } else
                        allowCustomEvents = false;
                    break;
                case "rowHeaderMouseenter":
                case "rowDataMouseenter":
                    // qB.log(responce.viewRow);
                    // if (this.model.isEdit())
                    //     break;
                    // if (responce.viewRow)
                    //     responce.viewRow.$el.addClass('render-selected');
                    break;
                case "rowHeaderMouseleave":
                case "rowDataMouseleave":
                    // // qB.log(responce.viewRow);
                    // if (this.model.isEdit())
                    //     break;
                    // if (responce.viewRow)
                    //     responce.viewRow.$el.removeClass('render-selected');
                    break;
            }

            // update table render mode
            // according to uiState
            if (this.model.isEdit()) {
                self.$el.removeClass('render-mode-normal');
                self.$el.addClass('render-mode-edit');
            } else {
                self.$el.removeClass('render-mode-edit');
                self.$el.addClass('render-mode-normal');
            }

            if (this.model.isEmpty())
                this.$el.addClass("render-mode-empty");
            else
                this.$el.removeClass("render-mode-empty");

            // trigger custom event
            var _eventName = "on" + state.capitalize();
            if (_.isFunction(config.events[_eventName]) && allowCustomEvents) {

                var _eventData = responce ? _(responce).clone() : {};
                _eventData.data = responce.data || null;
                _eventData.event = responce.event || null;
                _eventData.sender = responce.sender || this.model.getConfig().tableName

                qB.log(_logThisFile, 'calling',_eventName);

                config.events[_eventName].call(null, _eventData, this, this.model, callback);
            }
            else if (callback) {
                qB.log(_logThisFile, 'BTableViewClass >>> customStateDispatcher: calling default callback');
                callback(null); 
            }

            return this;

        },
        customGetState: function () {
            return this.model.get("state");
        },
    });

    return BTableViewClass;

})
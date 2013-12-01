APP.Modules.register("model/BTableModelClass", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'collection/BRowCollectionClass',
    'model/BRowModelClass'

], function (app, Sandbox, $, _, Backbone, BRowCollectionClass, BRowModelClass){

    var _logThisFile = false;

    var BTableModelClass = Backbone.Model.extend({
        defaults : {
            rowHead : null,
            // row items
            rowCollection : null,
            uiState: 'normal',
            state : null,
            config: null
        },
        initialize : function () {
            // app.log('BTableModelClass', 'initialize', this);

            var rowColl = new BRowCollectionClass();

            rowColl.setCustomOptions({
                modelTable: this
            });

            var _config = this.getConfig();

            if (_config.custom && _config.custom.defaultSorting) {
                rowColl.setSortKey(_config.custom.defaultSorting.field);
                rowColl.setSortDirection(_config.custom.defaultSorting.orderASC);
            }

            this.set('rowCollection', rowColl);
            // this.on('table:sort', this.customRowsSort);
        },
        parse : function (responce) {
            // app.log(_logThisFile, 'BTableModelClass >> data received', responce);

            var rowColl = this.get('rowCollection');
            rowColl.reset();
            // app.log(_logThisFile, 'BTableModelClass::rowCollection >> ', rowColl);

            if (!responce || _.isEmpty(responce)) {
                app.log(_logThisFile, 'BTableModelClass >>> empty data occured');
                this.trigger("table:state", "dataEmpty");
                return false;
            }

            var self = this;

            var _eventData = {
                data: _(responce).clone()
            }

            this.trigger("table:state", "dataParse", _eventData, function (error, newData) {

                var data = newData || responce;

                // app.log(_logThisFile, 'BTableModelClass >>> parse: data:', data);

                var _config = self.getConfig();

                var _dataRowCount = Object.getOwnPropertyNames(data).length - 1;
                var _rowIndex = 0;
                var _isLastRow = false;
                // app.log(_logThisFile, 'BTableModelClass >>> parse: data:',Object.getOwnPropertyNames(data), _dataRowCount);

                for (var key in data) {

                    _isLastRow = (_rowIndex + 1) == _dataRowCount;

                    // app.log(_logThisFile, 'running row index is', _rowIndex);

                    // add attribute rows
                    var _attributeRows = [];
                    if (_.isArray(data[key]._attributes))
                        for (var i = 0, len = data[key]._attributes.length; i < len; i++)
                            _attributeRows.push(new BRowModelClass({
                                rawData : _.extend({}, data[key], data[key]._attributes[i]),
                                isAttributeRow: true,
                                modelTable : self
                            }));

                    // if (_isLastRow)
                    //     app.log(_logThisFile, 'Last table row:', data[key]);

                    // add data row
                    rowColl.add(new BRowModelClass({
                        rawData : data[key],
                        linkedAttributeRowModels: _attributeRows,
                        modelTable : self,
                        allowToBeSorted: !(_isLastRow && _config.custom && _config.custom.sortLastRow === false)
                    }));

                    _rowIndex++;
                    
                }

                if (self.getConfig().style.showHeader) {
                    // app.log(_logThisFile, 'BTableModelClass >>> addding row header');
                    var rowHead = new BRowModelClass({
                        rawData : data[0],
                        modelTable : self,
                        isHead : true
                    });
                    self.set('rowHead', rowHead);
                }

                if (_dataRowCount === 0) {
                    self.trigger("table:state", "dataEmpty");
                    return false;
                }

                rowColl.sort();

                self.trigger("table:state", "dataAdded", {data: data});

                return false;

            });

            return true;
        },
        customRowsSortByFieldName : function (sortKey) {

            // find cell by key
            var _modelCell = null;

            this.get('rowHead').get('cellCollection').each(function(modelCell){
                // m.set("state", "normal");
                if (modelCell.get('accessKey') === sortKey) {
                    _modelCell = modelCell;
                    return;
                }
            })

            if (_modelCell)
                this.customRowsSort(_modelCell);

        },
        customRowsSort : function (modelCell) {

            var sortKey = modelCell.get('accessKey');
            var sortASC = modelCell.get('sortMode') != "sort-asc";

            // sort row models
            this.get('rowCollection').setSortKey(sortKey);
            this.get('rowCollection').setSortDirection(sortASC);
            this.get('rowCollection').sort();

            modelCell.getRowCellCollection().each(function(m){
                m.set("state", "normal");
            })

            // set cell state
            modelCell.set('sortMode', sortASC ? "sort-asc" : "sort-desc");
            modelCell.set('state', "sorted");

            // trigger to refresh table
            this.trigger("table:state", "dataSorted");
            Sandbox.eventNotify("widget:dataTable:dataSorted");
        },
        getConfig: function () {
            return this.get('config');
        },
        getRows: function () {
            return this.get('rowCollection');
        },
        customGetColumnValues: function (columnName) {
            var rowColl = this.get('rowCollection');
            var columnValues = [];
            rowColl.each(function(rowModel){
                var cells = rowModel.get('cellCollection');
                cells.each(function(modelCell){
                    if (modelCell.get('accessKey') === columnName)
                        columnValues.push(modelCell.get('value'));
                });
            });
            return columnValues;
        },
        customGetSummaryDataByColumn: function (columnName) {
            var _values = this.customGetColumnValues(columnName);
            if (_values && _values.length)
                return _values.reduce(function(a, b){return a + b;});
            return 0;
        },
        customGetSortInfo: function () {
            return this.get('rowCollection').customSort;
        },
        // states
        isNormal: function () {
            return this.get('uiState') === 'normal';
        },
        isEdit: function () {
            return this.get('uiState') === 'edit';
        },
        isEmpty: function () {
            return this.get('uiState') === 'empty';
        },
        isSave: function () {
            return this.get('uiState') === 'save';
        },
        isCancel: function () {
            return this.get('uiState') === 'cancel';
        },
        setState: function (state) {
            app.log(_logThisFile, 'setState', state);
            this.set('uiState', state);
        },
        getState: function () {
            return this.get('uiState');
        },
        setStateEdit: function () {
            this.setState('edit');
        },
        setStateNormal: function () {
            this.setState('normal');
        },
        setStateCancel: function () {
            this.setState('cancel');
        },
        setStateSave: function () {
            this.setState('save');
        },
        refresh: function () {
            this.trigger("table:state", "refresh");
        },
        update: function () {
            this.trigger("table:state", "update");
        },
        truncateData: function () {
            this.get('rowCollection').reset();
            this.trigger("table:state", "dataEmpty");
        }
    });

    return BTableModelClass;

})
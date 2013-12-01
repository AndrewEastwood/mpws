APP.Modules.register("model/BRowModelClass", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone',
    'collection/BCellCollectionClass',
    'model/BCellModelClass',

], function (app, Sandbox, $, _, Backbone, BCellCollectionClass, BCellModelClass){

    var BRowModelClass = Backbone.Model.extend({
        events: {
            "remove": "eventToTableDispatcher"
        },
        defaults : {
            rawData : null,
            isHead  : false,
            isAttributeRow: false,
            linkedAttributeRowModels: [],
            // cell items
            modelTable : null,
            cellCollection : null,
            uiState: 'normal'
        },
        initialize : function (data) {
            // app.log('-- BRowModelClass', 'initialize', data.rawData);
            // app.log(this);
            var config = data.modelTable.getConfig();
            var cc = new BCellCollectionClass();
            var self = this;
            var cellRealIndex = 0;
            var _addNewCellFn = function (cellAccessKey, options) {
                var cellValue =  null;

                // set cell value
                if (!_.isUndefined(options.inRowDataValue))
                    cellValue = options.inRowDataValue;
                else if (!_.isUndefined(data.rawData[cellAccessKey]))
                    cellValue = data.rawData[cellAccessKey];

                // override cell value when it goes within attribute row
                if (data.isAttributeRow && options.displayInAttributeRow && !_.isUndefined(options.inRowAttributeValue))
                    cellValue = options.inRowAttributeValue;

                var cellConfig = _.extend({},
                    {
                        accessKey : cellAccessKey,
                        isHeader : self.get('isHead'),
                        value : cellValue,
                        text: cellValue,
                        index : cellRealIndex++,
                        modelTable : data.modelTable,
                        displayIndex: self.getDisplayIndex(cellAccessKey) || 0, 
                        collection : cc,
                        modelRow: self,
                        isInAttributeRow: data.isAttributeRow
                    }, options);
                
                if (config.custom && config.custom.defaultSorting && config.custom.defaultSorting.field == cellAccessKey) 
                    cellConfig.sortMode = config.custom.defaultSorting.orderASC ? "sort-asc" : "sort-desc";
                
                // app.log(cellConfig);
                // cellConfig.cellDisplayIndex = -cellConfig.cellDisplayIndex;
                // app.log(true, 'BRowModelClass >>> adding new cell ', cellConfig)
                cc.add(new BCellModelClass(cellConfig));
            }

            // if (data.isAttributeRow)
            //     app.log(true, 'rowdata:', data.rawData)
            // loop through all data and add them into row model
            if (!data.isAttributeRow)
                for(var cellAccessKey in data.rawData)
                    if (cellAccessKey !== '_attributes')
                        _addNewCellFn(cellAccessKey, config.columns[cellAccessKey] || {});

            // add custom/attribute columns
            _(config.columns).each(function(options, cellAccessKey){
                if (options.isCustom)
                    return _addNewCellFn(cellAccessKey, options);
                if (data.isAttributeRow) {
                    if (options.isAttribute || options.displayInAttributeRow) {
                        // app.log(true, 'adding attribute cell with options:', options)
                        _addNewCellFn(cellAccessKey, options);
                    }
                    return;
                }
                if (data.isHead && options.isAttribute)
                    return _addNewCellFn(cellAccessKey, options);
            });

            // this.set('isAttributeRow', data.isAttributeRow);
            this.set('cellCollection', cc);
        },
        eventToTableDispatcher: function (event) {
            var state = "rowRemoved";
            if (this.length == 0)
                state = "dataEmpty";
            this.getTable().trigger("table:state", state, {
                event: event
            });
        },
        getDisplayIndex: function (columnAccessKey) {
            var _config = this.get('modelTable').getConfig();
            var _dip_idx = 0;
            for (var key in _config.columns)
                if (key === columnAccessKey)
                    return _dip_idx;
                else
                    _dip_idx++;
            return false;
        },
        getTable: function () {
            return this.get('modelTable');
        },
        getConfig: function () {
            return this.get('modelTable').getConfig();
        },
        getCellByKey: function (accessKey) {
            return this.get('cellCollection').where({accessKey:accessKey});
        },
        getLastVisibleCell: function () {},
        isNormal: function () {
            return this.get('uiState') === 'normal';
        },
        isEdit: function () {
            return this.get('uiState') === 'edit';
        },
        isSave: function () {
            return this.get('uiState') === 'save';
        },
        isCancel: function () {
            return this.get('uiState') === 'cancel';
        },
        setState: function (state) {
            if (this.getState() === state)
                return false;

            this.get('cellCollection').each(function(modelCell){
                // app.log('TROLOLO', modelCell)
                modelCell.setState(state);
            });
            this.set('uiState', state);

            return true;
        },
        getState: function () {
            return this.get('uiState');
        },
        setStateEdit: function () {
            return this.setState('edit');
        },
        setStateNormal: function () {
            return this.setState('normal');
        },
        setStateCancel: function () {
            return this.setState('cancel');
        },
        setStateSave: function () {
            return this.setState('save');
        },
        refresh: function () {
            this.get('cellCollection').each(function(modelCell){
                // app.log('TROLOLO', modelCell)
                modelCell.trigger('refresh');
            });
            this.getTable().trigger("table:state", "refresh");
        }
    });

    return BRowModelClass;

})
APP.Modules.register("model/BCellModelClass", [], [

    'lib/jquery',
    'lib/underscore',
    'lib/backbone'

], function (app, Sandbox, $, _, Backbone){

    var _logThisFile = false;
    var BCellModelClass = Backbone.Model.extend({
        defaults : {
            caption: "",
            accessKey : false,
            isInAttributeRow: false,
            isAttribute: false,
            isVisible : true,
            isHeader : false,
            isSortable : false,
            isEditable : false,
            state : "normal",
            uiState: 'normal',
            sortMode: null,
            value : "",
            title : null, // if null then the same as value
            index : 0,
            displayIndex : 100,
            type : "text", // numeric || money
            overflowEllipsis : true,
            // onDisplay : null,
            // onEdit : null,
            data: null,
            collection: null,
            modelRow: null,
            modelTable : null,
            // format: null,
            formatValue: null,
            formatCaption: null,
            formatValueTitle : null,
            formatCaptionTitle : null
        },
        getCellConfig: function () {
            return this.attributes;
        },
        getConfig: function () {
            return this.get('modelTable').getConfig();
        },
        getTable: function () {
            return this.get('modelTable');
        },
        getRow: function () {
            return this.get('modelRow');
        },
        getRows: function () {
            return this.getTable().getRows();
        },
        getRowCellCollection: function () {
            return this.get('modelRow').get('cellCollection');
        },
        customGetCellByKey: function (accessKey) {
            var cells = this.get('collection').where({accessKey: accessKey});
            if (cells.length === 1)
                return cells[0];
            return cells;
        },
        customGetRowObject: function (wrapper) {
            var _row = {};
            var _c = this.get('collection');

            _c.each(function(modelCell){
                _row[modelCell.get('accessKey')] = modelCell.getValue();
            });

            if (wrapper) 
                return wrapper(_row);

            return _row;
        },
        customGetRowArray: function (wrapper) {
            var _row = [];
            var _c = this.get('collection');

            _c.each(function(modelCell){
                _row.push({
                    accessKey: modelCell.get('accessKey'),
                    value: modelCell.getValue(),
                    title: modelCell.get('title')
                });
            });

            if (wrapper) {
                // app.log(_logThisFile, wrapper(_row))
                return wrapper(_row);
            }

            return _row;
        },
        customGetCellValueByKeyInRow: function (accessKey, keyToReturn) {
            var _cellEntry = this.customGetRowArray(_).where({accessKey: accessKey});
            if (_cellEntry) {

                var _data = _cellEntry.length == 1 ? _cellEntry[0] : _cellEntry;

                if (keyToReturn) {
                    if (_data.length)
                        return _(_data).pluck(keyToReturn);
                    else
                        return _data[keyToReturn];
                }
                else
                    return _data;
            }
            return null;

        },
        customUpdateRowValuesByJSON: function (dataJSON) {
            if (_.isEmpty(dataJSON))
                return;
            this.customWithEachCellInRowDo(function(modelCell){
                app.log(_logThisFile, 'modelCell', modelCell);
                var newValue = dataJSON[modelCell.get('accessKey')];
                if (typeof newValue !== "undefined")
                    modelCell.setValue(newValue);
            });
        },
        customWithEachCellInRowDo: function (actionFn) {
            var cellCollections = this.getRowCellCollection();
            cellCollections.each(actionFn);
        },
        customAllowToSort : function () {
            return this.get('isHeader') && this.get('isSortable');
        },
        customAllowToEdit : function () {
            return !this.get('isHeader') && this.get('isEditable');
        },
        customAllowToEditStart : function () {
            return this.customAllowToEdit() && this.getTable().isNormal();//.get('state') != 'modeEditStart';
        },
        customAllowToEditStop : function () {
            return this.customAllowToEdit() && this.getTable().isEdit() && this.getRow().isEdit();//.get('state') == 'modeEditStart';
        },
        customSortStart : function () {
            if (this.customAllowToSort())
                this.getTable().trigger('table:state', "doSort", {
                    modelCell: this
                });
        },
        customEditStart : function (responce) {
            if (this.customAllowToEditStart()) {
                app.log(_logThisFile, 'customEditStart');

                // set inner state
                this.setInnerState('clicked');

                this.getTable().setStateEdit();
                this.getRow().setStateEdit();
                this.getTable().trigger('table:state', 'modeEditStart', {
                    modelCell: this,
                    modelRow: this.getRow(),
                    viewCell: responce.viewCell || null,
                });
                // this.getRow().trigger('table:state', this, 'edit:start');
            }
        },
        customEditCancel : function (event, force) {
            app.log(_logThisFile, 'BCellModelClass >> customEditCancel');
            if (this.customAllowToEditStop() || force) {
                if (event) {
                    if (event.stopPropagation)
                        event.stopPropagation();
                    else if (window.event.stopPropagation)
                        window.event.stopPropagation();
                    if (event.preventDefault)
                        event.preventDefault();
                    else
                        event.returnValue = false;
                }

                this.customWithEachCellInRowDo(function(modelCell){
                    modelCell.setInnerState('normal');
                });
                // this.set('uiState', 'normal');
                // this.getTable().setState('cancel');
                // this.getRow().setState('cancel');
                this.setStateNormal();
                this.getTable().setStateNormal();
                this.getTable().trigger('table:state', 'modeEditCancel', {
                    modelCell: this,
                    modelRow: this.getRow()
                });
                this.getRow().setStateNormal();
                this.getTable().refresh();
                // this.getRow().trigger('table:state', this, 'edit:cancel');
            }
        },
        customEditSave : function (event, force) {
            if (this.customAllowToEditStop() || force) {
                if (event) {
                    if (event.stopPropagation)
                        event.stopPropagation();
                    else if (window.event.stopPropagation)
                        window.event.stopPropagation();
                    if (event.preventDefault)
                        event.preventDefault();
                    else
                        event.returnValue = false;
                }

                this.customWithEachCellInRowDo(function(modelCell){
                    modelCell.set('state', 'normal');
                });
                // this.set('uiState', 'normal');
                // this.getTable().setState('save');
                // this.getRow().setState('save');
                this.setStateNormal();
                this.getTable().setStateNormal();
                this.getTable().trigger('table:state', 'modeEditSave', {
                    modelCell: this,
                    modelRow: this.getRow()
                });
                this.getRow().setStateNormal();
                this.getTable().refresh();
                // this.getRow().trigger('table:state', this, 'edit:save');
            }
        },
        // states
        isNormal: function () {
            return this.getState() === 'normal';
        },
        isEdit: function () {
            return this.getState() === 'edit';
        },
        isSave: function () {
            return this.getState() === 'save';
        },
        isCancel: function () {
            return this.getState() === 'cancel';
        },
        setState: function (state) {
            if (this.getState() === state)
                return false;
            app.log(_logThisFile, 'BCellModelClass setState', state);
            this.set('uiState', state);
            return true;
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
        // inner state
        getInnerState: function () {
            return this.get('state');
        },
        setInnerState: function (state) {
            return this.set('state', state);
        },
        // data
        getValue: function () {
            return this.get('value');
        },
        setValue: function (value) {
            // app.log(_logThisFile, 'new value setted for ', this.get('accessKey'), ' ===> ', value);
            this.set('value', value);
        },
        getAccessKey: function () {
            return this.get('accessKey');
        }
    });

    return BCellModelClass;

})
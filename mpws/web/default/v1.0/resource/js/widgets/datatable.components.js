APP.Modules.register("widget/datatable.components", [], [

    'lib/zepto'

], function (app, Sandbox, $){

    app.log('I AM INTERNAL COMMON VIEW', $);


    function DataTableComponents () {}

    DataTableComponents.activateTableBunchActions = function (dataTable) {

        app.log(true, 'DataTableComponents.activateTableBunchActions', dataTable)

        var tableRowHeader = '.MPWSDataTableRowCaptions';
        var tableRowGroup = '.MPWSDataTableRowGroup';
        var tableRowRecord = '.MPWSDataTableRow';

        var _setCheckboxStateFn = function (checkboxes, state) {
            $(checkboxes).each(function(){
                if (state)
                    this.setAttribute("checked", "checked");
                else
                    this.removeAttribute("checked", "checked");
            });
        }

        var _setRowStateFn = function (rows, state, isTopRow) {

            var $checkbox = $(this);
            var checkboxName = $checkbox.attr('name');
            var checkBoxSelector = 'input[type="checkbox"][name="' + checkboxName + '"]';

            var _checkboxes = null;

            if (isTopRow) {
                 // set state 'checked' for all data rows
                _checkboxes = $(rows).find(checkBoxSelector);
                app.log(rows, checkBoxSelector, _checkboxes);
                _setCheckboxStateFn(_checkboxes, state);
            } else {
                // find top row checkbox
                var _cbtop = $(dataTable).find(tableRowHeader + ' ' + checkBoxSelector);
                var _totalCB = $(dataTable).find(tableRowGroup + ' ' + checkBoxSelector);
                var _checkedCB = $(dataTable).find(tableRowGroup + ' ' + checkBoxSelector + ':checked');
                var _topCBState = _totalCB.length == _checkedCB.length;
                app.log(_totalCB.length , _checkedCB.length, _topCBState);
                _setCheckboxStateFn(_cbtop, _topCBState);
            }

            if (state)
                $(rows).addClass('selected');
            else
                $(rows).removeClass('selected');
        }

        var _checkboxStateFn = function (checkbox) {
            return $(checkbox).is(':checked') ? true : null;
        }

        app.log(dataTable.selector + ' input[type="checkbox"]')
        $(dataTable.selector + ' input[type="checkbox"]').on('click', function () {
            var _isTopRow = $(this).parents(tableRowHeader).length > 0;
            // app.log('------>>>> ', this, $(this), $(this).parents('.MPWSDataTableRowGroup'))
            var rows = null;
            if (_isTopRow)
                rows = $(dataTable).find(tableRowGroup + ' ' + tableRowRecord);
            else
                rows = $(this).parents(tableRowRecord)
            _setRowStateFn.call(this, rows, _checkboxStateFn(this), _isTopRow);
        });

    }

    return DataTableComponents;
    
});
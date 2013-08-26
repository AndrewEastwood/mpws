APP.Modules.register("widget/datatable.components", [], [

    'lib/zepto'

], function (app, Sandbox, $){

    app.log('I AM INTERNAL COMMON VIEW', $);


    function DataTableComponents () {}

    DataTableComponents.activateTableBunchActions = function (dataTable) {

        app.log(true, 'DataTableComponents.activateTableBunchActions', dataTable)

        var tableRowHeader = $(dataTable).find('.MPWSDataTableRowCaptions');
        var tableRowRecord = $(dataTable).find('.MPWSDataTableRowGroup .MPWSDataTableRow');

        // APP.log('bunch controls', $(tableBunchCheckboxCmn), $(tableBunchCheckboxRow));



        var _checkAllRowsSelected = function () {
            var _rowsCount = $(tableRowRecord).length;
            var _rowsSelectedCount = $(tableRowRecord).find('.selected').length;
            if (_rowsCount === _rowsSelectedCount)
                _selectAllRows(null, true, true);
            if (_rowsSelectedCount === 0)
                _selectAllRows(null, true, null);
        }

        var _selectRowFn = function (row) {
            $(row).addClass('selected');
            $(row).find('input[type="checkbox"]').attr('unchecked', null);
            $(row).find('input[type="checkbox"]').attr('checked', true);
            _checkAllRowsSelected();
        }

        var _unselectRowFn = function (row) {
            $(row).removeClass('selected');
            $(row).find('input[type="checkbox"]').attr('unchecked', null);
            $(row).find('input[type="checkbox"]').attr('checked', null);
            _checkAllRowsSelected();
        }

        var _selectAllRows = function (events, justSetCheckbox, state) {

            var _senderName = $(this).attr('name');
            var _checkbox = $(tableRowRecord).find('[name="' + _senderName + '"]');

            if (justSetCheckbox)
                $(_checkbox).attr('checked', state);
            else {
                var _state = $(this).is(':checked') ? true : null;
            }
        }

        var _onRowDataSelected = function () {

            $(this).toggleClass('selected');

            if($(this).hasClass('selected'))
                _selectRowFn($(this));
            else
                _unselectRowFn($(this));
        }
        var _onRowTopSelected = function () {
            _selectAllRows($(this))
        }


        $(tableRowHeader).find('input[type="checkbox"]').on('click', _onRowTopSelected);
        $(tableRowRecord).find('input[type="checkbox"]').on('click', _onRowDataSelected);
        $(tableRowRecord).on('click', _onRowDataSelected)
    }

    return DataTableComponents;
    
});
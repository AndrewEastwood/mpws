APP.Modules.register("view/internal.common", [], [

    'lib/zepto',
    'widget/datatable.components'

], function (app, Sandbox, $, DataTableComponents){

    var dataTablePHPComponent = $('.MPWSComponentDataTable');

    DataTableComponents.activateTableBunchActions(dataTablePHPComponent);
    
});
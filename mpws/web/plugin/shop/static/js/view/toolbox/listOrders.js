define("plugin/shop/js/view/toolbox/listOrders", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/collection/toolbox/listOrders',
    'plugin/shop/js/view/toolbox/orderEntry',
    // 'plugin/shop/js/view/toolboxOrderItem'
    'default/js/lib/bootstrap-dialog',
    "default/js/lib/backgrid",
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-select-all",
], function (Sandbox, MView, CollListOrders, ViewOrderEntry, BootstrapDialog, Backgrid, lang) {

    Sandbox.eventSubscribe('shop-product-edit', function(data){
        // debugger;
        var orderEntry = new ViewOrderEntry();
        BootstrapDialog.show({
            type: BootstrapDialog.TYPE_WARNING,
            title: "Замовлення #" + data.oid,
            message: orderEntry.$el,
            cssClass: 'shop-order-entry',
            buttons: [{
                label: "Добре",
                action: function (dialog) {
                    dialog.close();
                }
            }]
        });
        orderEntry.fetchAndRender({
            orderID: data.oid
        });
    })
    // enable the select-all extension
    // name: "",
    // cell: "select-row",
    // headerCell: "select-all"
    // Column definitions
    var columns = [{
        // enable the select-all extension
        name: "",
        cell: "select-row",
        headerCell: "select-all",
        // label: lang.pluginMenu_Orders_Grid_Column_ID,
        // cell: "string",
        // editable: false
    }, {
        name: "Status",
        label: lang.pluginMenu_Orders_Grid_Column_Status,
        cell: "string",
        editable: false,
        formatter: {
            fromRaw: function (value) {
                var _status = lang['order_status_' + value];
                return _status;
            }
        }
    }, {
        name: "Shipping",
        label: lang.pluginMenu_Orders_Grid_Column_Shipping,
        cell: "string",
        editable: false,
        formatter: {
            fromRaw: function (value) {
                var _logisticAgency = lang['logisticAgency_' + value];
                if (_logisticAgency)
                    return _logisticAgency;

                return lang.logisticAgency_Unknown;
            }
        }
    }, {
        name: "Warehouse",
        label: lang.pluginMenu_Orders_Grid_Column_Warehouse,
        cell: "string",
        editable: false
    }, {
        name: "DateUpdated",
        label: lang.pluginMenu_Orders_Grid_Column_DateUpdated,
        cell: "string",
        editable: false
    }, {
        name: "DateCreated",
        label: lang.pluginMenu_Orders_Grid_Column_DateCreated,
        cell: "string",
        editable: false
    }, {
        name: "Actions",
        label: "Actions",
        cell: "string",
        editable: false,
        formatter: {
            fromRaw: function (value, model) {
                // debugger;
                var _link = $('<a>').attr({
                    href: "javascript://",
                    "data-oid": model.get('ID'),
                    "data-action": "shop-product-edit"
                }).text('Edit');
                // debugger;
                return _link.get(0);
            }
        }
    }];

    var collection = new CollListOrders();

    var ToolboxListOrdersGrid = new Backgrid.Grid({
      columns: columns,
      collection: collection
    });


    var Paginator = new Backgrid.Extension.Paginator({

      // If you anticipate a large number of pages, you can adjust
      // the number of page handles to show. The sliding window
      // will automatically show the next set of page handles when
      // you click next at the end of a window.
      windowSize: 20, // Default is 10

      // Used to multiple windowSize to yield a number of pages to slide,
      // in the case the number is 5
      slideScale: 0.25, // Default is 0.5

      // Whether sorting should go back to the first page
      goBackFirstOnSort: false, // Default is true

      collection: collection
    });


    var ToolboxListOrders = MView.extend({
        className: 'shop-toolbox-orders',
        render: function () {
            // var _grid = new ToolboxListOrdersGrid();
            // var _paginator = new Paginator();
            // var $paginatorExample = toolboxListOrders.$el;//$("#paginator-example-result");
            this.$el.append(ToolboxListOrdersGrid.render().el);
            this.$el.append(Paginator.render().el);
            collection.fetch({reset: true});
        }
    });



    // var ToolboxListOrders = MView.extend({
    //     className: 'shop-toolbox-orders',
    //     collection: new CollListOrders(),
    //     itemViewClass: OrderItem,
    //     initialize: function () {
    //         MView.prototype.initialize.call(this);
    //         var self = this;
    //         var grid = new Backgrid.Grid({
    //             columns: columns,
    //             collection: this.collection,
    //         });

    //         this.on('mview:renderComplete', function () {
    //             self.$el.html(grid.render().el);
    //         });
    //     }
    //     // autoRender: true
    // });

    return ToolboxListOrders;

});
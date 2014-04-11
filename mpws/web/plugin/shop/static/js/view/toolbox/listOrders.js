define("plugin/shop/js/view/toolbox/listOrders", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/collection/toolbox/listOrders',
    'plugin/shop/js/view/toolbox/orderEntry',
    'plugin/shop/js/view/toolbox/filteringListOrders',
    'default/js/lib/bootstrap-dialog',
    "default/js/lib/backgrid",
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-select-all",
    "default/js/lib/backgrid-htmlcell"
], function (Sandbox, MView, CollectionListOrders, ViewOrderEntry, FilteringListOrders, BootstrapDialog, Backgrid, lang) {

    Sandbox.eventSubscribe('shop-toolbox-order-edit', function(data){
        var orderEntry = new ViewOrderEntry();
        BootstrapDialog.show({
            title: lang.orderEntry_Popup_title + data.oid,
            message: orderEntry.$el,
            cssClass: 'shop-toolbox-order-edit',
            buttons: [{
                label: lang.orderEntry_Popup_button_OK,
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
    // {
    //     // enable the select-all extension
    //     name: "",
    //     cell: "select-row",
    //     headerCell: "select-all",
    //     // label: lang.pluginMenu_Orders_Grid_Column_ID,
    //     // cell: "string",
    //     // editable: false
    // },
    var columns = [{
        name: "Status",
        label: lang.pluginMenu_Orders_Grid_Column_Status,
        cell: "html",
        editable: false,
        formatter: {
            fromRaw: function (value) {
                var _wrapper = $('<div>');
                var _icon = $('<i>').addClass('fa');
                var _label = $('<span>').text(lang['order_status_' + value]);
                switch (value) {
                    case "NEW" : {
                        _icon.addClass('fa-dot-circle-o')
                        break;
                    }
                    case "ACTIVE" : {
                        _icon.addClass('fa-circle')
                        break;
                    }
                    case "LOGISTIC_DELIVERING" : {
                        _icon.addClass('fa-plane')
                        break;
                    }
                    case "LOGISTIC_DELIVERED" : {
                        _icon.addClass('fa-gift')
                        break;
                    }
                    case "SHOP_CLOSED" : {
                        _icon.addClass('fa-check')
                        break;
                    }
                }

                _wrapper.append([_icon, _label])

                return _wrapper;
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
        cell: "datetime",
        editable: false
    }, {
        name: "DateCreated",
        label: lang.pluginMenu_Orders_Grid_Column_DateCreated,
        cell: "datetime",
        editable: false
    }, {
        name: "Actions",
        label: lang.pluginMenu_Orders_Grid_Column_Actions,
        cell: "html",
        editable: false,
        formatter: {
            fromRaw: function (value, model) {
                // debugger;
                var _link = $('<a>').attr({
                    href: "javascript://",
                    "data-oid": model.get('ID'),
                    "data-action": "shop-toolbox-order-edit"
                }).text(lang.pluginMenu_Orders_Grid_link_Edit);
                // debugger;
                return _link;
            }
        }
    }];

    var collection = new CollectionListOrders();

    var ToolboxListOrdersGrid = new Backgrid.Grid({
      columns: columns,
      collection: collection
    });

    // data filtering
    var filteringListOrders = new FilteringListOrders();

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


    var ListOrders = MView.extend({
        className: 'shop-toolbox-orders',
        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe("plugin:shop:orderList:refresh", function () {
                self.render();
            });

            Sandbox.eventSubscribe("plugin:shop:orderList:filter", function (filter) {
                // debugger;
                collection.queryParams.filter = filter;
                collection.fetch({reset: true});
            });
        },
        render: function () {
            this.$el.empty();
            this.$el.append(filteringListOrders.render().el);
            // debugger;
            this.$el.append(ToolboxListOrdersGrid.render().el);
            this.$el.append(Paginator.render().el);
            collection.fetch({reset: true});
        }
    });

    return ListOrders;

});
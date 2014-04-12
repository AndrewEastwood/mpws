define("plugin/shop/js/view/toolbox/listOrders", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/collection/toolbox/listOrders',
    'plugin/shop/js/view/toolbox/orderEntry',
    'plugin/shop/js/view/toolbox/filteringListOrders',
    "default/js/lib/backgrid",
    /* template */
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/listOrders',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-select-all",
    "default/js/lib/backgrid-htmlcell"
], function (Sandbox, MView, CollectionListOrders, ViewOrderEntry, FilteringListOrders, Backgrid, tpl, lang) {

    var orderEntry = new ViewOrderEntry();
    Sandbox.eventSubscribe('shop-toolbox-order-edit', function(data){
        orderEntry.fetchAndRender({
            orderID: data.oid
        });
    });

// {
//     name: "Status",
//     label: lang.pluginMenu_Orders_Grid_Column_Status,
//     cell: "html",
//     editable: false,
//     formatter: {
//         fromRaw: function (value) {
//             var _wrapper = $('<div>');
//             var _icon = $('<i>').addClass('fa');
//             var _label = $('<span>').text(lang['order_status_' + value]);
//             switch (value) {
//                 case "NEW" : {
//                     _icon.addClass('fa-dot-circle-o')
//                     break;
//                 }
//                 case "ACTIVE" : {
//                     _icon.addClass('fa-circle')
//                     break;
//                 }
//                 case "LOGISTIC_DELIVERING" : {
//                     _icon.addClass('fa-plane')
//                     break;
//                 }
//                 case "LOGISTIC_DELIVERED" : {
//                     _icon.addClass('fa-gift')
//                     break;
//                 }
//                 case "SHOP_CLOSED" : {
//                     _icon.addClass('fa-check')
//                     break;
//                 }
//             }

//             _wrapper.append([_icon, _label])

//             return _wrapper;
//         }
//     }
// }, 
// Just like SelectCell, Select2Cell supports option lists and groups
// var numbers = [{name: 10, values: [
//   [1, 1], [2, 2], [3, 3], [4, 4], [5, 5],
//   [6, 6], [7, 7], [8, 8], [9, 9], [10, 10]
// ]}];

// var MySelect2Cell = Backgrid.Extension.Select2Cell.extend({
//   // any options specific to `select2` goes here
//   select2Options: {
//     // default is false because Backgrid will save the cell's value
//     // and exit edit mode on enter
//     openOnEnter: false
//   },
//   optionValues: numbers,
//   // since the value obtained from the underlying `select` element will always be a string,
//   // you'll need to provide a `toRaw` formatting method to convert the string back to a
//   // type suitable for your model, which is an integer in this case.
//   formatter: _.extend({}, Backgrid.SelectFormatter.prototype, {
//     toRaw: function (formattedValue, model) {
//       return formattedValue == null ? [] : _.map(formattedValue, function (v) {
//         return parseInt(v);
//       })
//     }
//   })
// });
    var columnActions = {
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
    };

    var columnStatuses = {
        name: "Statuses",
        label: lang.pluginMenu_Orders_Grid_Column_Status,
        cell: "select",
        cell: Backgrid.SelectCell.extend({
            // It's possible to render an option group or use a
            // function to provide option values too.
            optionValues: [["Male", "m"], ["Female", "f"]]
        })
    };

    var columnShipping = {
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
    };

    var columnWarehouse = {
        name: "Warehouse",
        label: lang.pluginMenu_Orders_Grid_Column_Warehouse,
        cell: "string",
        editable: false
    };

    var columnDateUpdated = {
        name: "DateUpdated",
        label: lang.pluginMenu_Orders_Grid_Column_DateUpdated,
        cell: "datetime",
        editable: false
    };

    var columnDateCreated = {
        name: "DateCreated",
        label: lang.pluginMenu_Orders_Grid_Column_DateCreated,
        cell: "datetime",
        editable: false
    };

    var columns = [columnActions, columnStatuses, columnShipping, columnWarehouse, columnDateUpdated, columnDateCreated];

    var ListOrders = MView.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-orders',
        initialize: function () {
            var self = this;
            var jqXHR = null;

            var _getTableCreateFn = function (status) {
                var collection = new CollectionListOrders();

                // set collection status
                collection.queryParams.status = status;

                var ToolboxListOrdersGrid = new Backgrid.Grid({
                  columns: columns,
                  collection: collection
                });

                // data filtering
                // var filteringListOrders = new FilteringListOrders();

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

                return {
                    status: status,
                    collection: collection,
                    ToolboxListOrdersGrid: ToolboxListOrdersGrid,
                    Paginator: Paginator,
                    fetch: function (options) {
                        collection.fetch(options);
                    }
                }
            }

            // create seperated lists for the following orders:
            // new
            // in progress
            // shipped
            // delivered
            // closed
            // debugger;
            var _statuses = ["NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED"];
            var _ordersByStatus = _(_statuses).map(function(status){
                return _getTableCreateFn(status);
            });

            Sandbox.eventSubscribe("plugin:shop:orderList:refresh", function () {
                // debugger;
                self.collection.fetch({reset: true});
            });

            // Sandbox.eventSubscribe("plugin:shop:orderList:filter", function (filter) {
            //     if (jqXHR)
            //         jqXHR.abort();
            //     collection.queryParams.filter = filter;
            //     jqXHR = self.collection.fetch({reset: true});
            // });

            this.on('mview:renderComplete', function () {
                var self = this;
                _(_ordersByStatus).each(function(orderListBuilder){

                    var $tabPage = self.$('.tab-pane#order_status_' + orderListBuilder.status + '-ID');

                    $tabPage.empty();
                    $tabPage.append(orderListBuilder.ToolboxListOrdersGrid.render().el);
                    $tabPage.append(orderListBuilder.Paginator.render().el);
                    orderListBuilder.fetch({reset: true});

                });
                // this.$el.empty();
                // this.$el.append(filteringListOrders.render().el);
                // // debugger;
                // this.$el.append(ToolboxListOrdersGrid.render().el);
                // this.$el.append(Paginator.render().el);
                // collection.fetch({reset: true});
            });
        }
    });

    return ListOrders;

});
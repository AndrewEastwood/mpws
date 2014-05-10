define("plugin/shop/toolbox/js/view/listOrders", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/common/js/lib/utils',
    'plugin/shop/toolbox/js/collection/listOrders',
    'plugin/shop/toolbox/js/view/popupOrder',
    'plugin/shop/toolbox/js/view/filteringListOrders',
    "default/js/lib/backgrid",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/listOrders',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-select-all",
    "default/js/lib/backgrid-htmlcell"
], function (Sandbox, MView, ShopUtils, CollectionListOrders, PopupOrderEntry, FilteringListOrders, Backgrid, tpl, lang) {

    // TODO: do smth to fetch states from backend
    var statuses = ["NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED"];
    var orderStatusValues = [];
    _(statuses).map(function(status){
        return orderStatusValues.push([lang["order_status_" + status] || status, status]);
    })
    // debugger;
    Sandbox.eventSubscribe('plugin:shop:order:edit', function(data){
        var popupOrder = new PopupOrderEntry();
        popupOrder.fetchAndRender({
            orderID: data.oid
        });
    });
    // Sandbox.eventSubscribe('plugin:shop:orderList:dataReceived', function(data){
    //     //
    // });

    var columnActions = {
        name: "Actions",
        label: lang.pluginMenu_Orders_Grid_Column_Actions,
        cell: "html",
        editable: false,
        sortable: false,
        formatter: {
            fromRaw: function (value, model) {
                // debugger;
                var _link = $('<a>').attr({
                    href: "javascript://",
                    "data-oid": model.get('ID'),
                    "data-action": "plugin:shop:order:edit"
                }).text(lang.pluginMenu_Orders_Grid_link_Edit);
                // debugger;
                return _link;
            }
        }
    };

    var columnStatus = {
        name: "Status",
        label: lang.pluginMenu_Orders_Grid_Column_Status,
        cell: Backgrid.SelectCell.extend({
            // It's possible to render an option group or use a
            // function to provide option values too.
            optionValues: orderStatusValues,
            initialize: function () {
                // this.prototype.initialize.call(this);
                Backgrid.SelectCell.prototype.initialize.apply(this, arguments);
                // debugger;
                this.listenTo(this.model, "change:Status", function(model, status) {
                    // debugger;
                    ShopUtils.updateOrderStatus(model.get('ID'), status);
                });
            }
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
        cell: "string",
        editable: false
    };

    var columnDateCreated = {
        name: "DateCreated",
        label: lang.pluginMenu_Orders_Grid_Column_DateCreated,
        cell: "string",
        editable: false
    };

    var columns = [columnActions, columnStatus, columnShipping, columnWarehouse, columnDateUpdated, columnDateCreated];

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
                    className: "backgrid table table-responsive",
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
                    // goBackFirstOnSort: false, // Default is true

                    collection: collection
                });

                return {
                    status: status,
                    collection: collection,
                    Grid: ToolboxListOrdersGrid,
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
            var _ordersByStatus = _(statuses).map(function (status){
                return _getTableCreateFn(status);
            });

            // refresh all lists
            Sandbox.eventSubscribe("plugin:shop:orderItem:updated", function () {
                // debugger;
                _(_ordersByStatus).each(function(orderList) {
                    orderList.fetch({reset: true});
                });
            });

            // when we know how many records are availabel of particular filter
            // we do update  tapPage badge with records count
            Sandbox.eventSubscribe('plugin:shop:orderList:parseState', function (data) {
                // debugger;
                var $badge = self.$('a[href="#order_status_' + data.collection.queryParams.status + '-ID"] .badge');
                $badge.text(data.state.totalRecords || "");
            });

            // inject all lists into tabPages
            this.on('mview:renderComplete', function () {
                var self = this;
                _(_ordersByStatus).each(function(orderListBuilder){
                    var $tabPage = self.$('.tab-pane#order_status_' + orderListBuilder.status + '-ID');
                    $tabPage.empty();
                    $tabPage.append(orderListBuilder.Grid.render().el);
                    $tabPage.append(orderListBuilder.Paginator.render().el);
                    orderListBuilder.fetch({reset: true});
                });
            });
        }
    });

    return ListOrders;

});
define("plugin/shop/toolbox/js/view/listOrders", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/collection/listOrders',
    'plugin/shop/toolbox/js/view/popupOrder',
    "default/js/lib/backgrid",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/listOrders',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-select-all",
    "default/js/lib/backgrid-htmlcell"
], function (Sandbox, Backbone, Utils, CollectionListOrders, PopupOrderEntry, Backgrid, tpl, lang) {

    // TODO: do smth to fetch states from backend
    var statuses = ["NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED"];
    var orderStatusValues = [];
    var dataSources = {};

    var columnAccountFullName = {
        name: "AccountFullName",
        label: lang.pluginMenu_Orders_Grid_Column_AccountFullName,
        cell: "string",
        editable: false
    };
    var columnAccountPhone = {
        name: "AccountPhone",
        label: lang.pluginMenu_Orders_Grid_Column_AccountPhone,
        cell: "string",
        editable: false
    };
    var columnInfoTotal = {
        name: "InfoTotal",
        label: lang.pluginMenu_Orders_Grid_Column_InfoTotal,
        cell: "string",
        editable: false,
        formatter: {
            fromRaw: function (value) {
                return value + ' грн.';
            }
        }
    };

    var columnActions = {
        name: "Actions",
        label: lang.pluginMenu_Orders_Grid_Column_Actions,
        cell: "html",
        editable: false,
        sortable: false,
        formatter: {
            fromRaw: function (value, model) {
                // debugger;
                var _link = $('<a/>').attr({
                    href: "javascript://"
                }).text(lang.pluginMenu_Orders_Grid_link_Edit);
                // debugger;
                _link.on('click', function(){
                    // debugger;
                    var popupOrder = new PopupOrderEntry(model.toJSON());
                    popupOrder.render();
                    popupOrder.listenTo(popupOrder.model, 'change', function(){
                        // debugger;
                        // var _newModelData = popupOrder.model.toJSON();
                        // _newModelData.Status = [_newModelData.Status];
                        // model.set(_newModelData).trigger('change');
                        Sandbox.eventNotify('plugin:shop:orderList:fetch', {reset: true});
                    });
                });
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
                    model.saveOrderStatus(status).success(function(){
                        Sandbox.eventNotify('plugin:shop:orderList:fetch', {reset: true});
                    });
                    // ShopUtils.updateOrderStatus(model.get('ID'), status);
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

    var columns = [columnActions, columnAccountFullName, columnAccountPhone, columnInfoTotal, columnStatus, columnShipping, columnWarehouse, columnDateUpdated, columnDateCreated];

    function getOrderDataSource (status) {
        var collection = new CollectionListOrders();

        // set collection status
        collection.queryParams.status = status;

        var ToolboxListOrdersGrid = new Backgrid.Grid({
            className: "backgrid table table-responsive",
            columns: columns,
            collection: collection
        });

        var Paginator = new Backgrid.Extension.Paginator({
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

    // Sandbox.eventSubscribe('plugin:shop:orderList:dataReceived', function(data){
    //     //
    // });
    // create seperated lists for the following orders:
    // new
    // in progress
    // shipped
    // delivered
    // closed
    // debugger;
    _(statuses).map(function (status){
        dataSources[status] =getOrderDataSource(status);
        orderStatusValues.push([lang["order_status_" + status] || status, status]);
    });

    var ListOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-orders',
        initialize: function () {
            var self = this;

            this.customDataSources = {
                fetch: function (options) {
                    _(dataSources).invoke('fetch', options);
                }
            }

            _(dataSources).each(function(dataSource){
                self.listenTo(dataSource.collection, 'reset', self.render);
            });

// function() {
                    // debugger;
                    // _(dataSources).invoke('fetch', {reset: true});
                // }
            // refresh all lists
            Sandbox.eventSubscribe("plugin:shop:orderList:fetch", function (options) {
                self.customDataSources.fetch(options);
            });

            // when we know how many records are availabel of particular filter
            // we do update  tapPage badge with records count
            // Sandbox.eventSubscribe('plugin:shop:orderList:parseState', function (data) {
            //     // debugger;
            // });
        },
        render: function () {
            //debugger;
            var self = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            _(dataSources).each(function(dataSource){
                var $tabPage = self.$('.tab-pane#order_status_' + dataSource.status + '-ID');
                $tabPage.empty();
                $tabPage.append(dataSource.Grid.render().el);
                $tabPage.append(dataSource.Paginator.render().el);
                // debugger;
                var $badge = self.$('a[href="#order_status_' + dataSource.status + '-ID"] .badge');
                $badge.text(dataSource.collection.state.totalRecords || "");
            });
        }
    });

    return ListOrders;

});
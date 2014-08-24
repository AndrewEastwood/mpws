define("plugin/shop/toolbox/js/view/listOrders", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/collection/basicOrders',
    "default/js/lib/backgrid",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/buttonMenuOrderListItem',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-select-all",
    "default/js/lib/backgrid-htmlcell"
], function (Sandbox, Backbone, Utils, CollectionListOrders, Backgrid, tplBtnMenuMainItem, lang) {

    function getColumns () {

        // TODO: do smth to fetch states from server
        var statuses = ["NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED"];
        var orderStatusValues = _(statuses).map(function (status){ return [lang["order_status_" + status] || status, status]; });

        var columnActions = {
            name: "Actions",
            label: lang.pluginMenu_Orders_Grid_Column_Actions,
            cell: "html",
            editable: false,
            sortable: false,
            formatter: {
                fromRaw: function (value, model) {
                    var btn = tplBtnMenuMainItem(model.toJSON());
                    return btn;
                }
            }
        };

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

        var columnHasPromo = {
            name: "HasPromo",
            label: lang.pluginMenu_Orders_Grid_Column_HasPromo,
            cell: "html",
            editable: false,
            formatter: {
                fromRaw: function (value) {
                    if (!!value)
                        return $('<i/>').addClass('fa fa-check-circle-o');
                }
            }
        };

        var columnDiscount = {
            name: "Discount",
            label: lang.pluginMenu_Orders_Grid_Column_Discount,
            cell: "string",
            editable: false,
            formatter: {
                fromRaw: function (value) {
                    if (value)
                        return value + ' %';
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
                    // debugger;
                    // this.prototype.initialize.call(this);
                    Backgrid.SelectCell.prototype.initialize.apply(this, arguments);
                    // debugger;
                    this.listenTo(this.model, "change:Status", function(model, status) {
                        // debugger;
                        console.log('status is changed', status);
                        // if (status && _.isString(status))
                        model.saveOrderStatus(status).success(function(){
                            model.collection.fetch({reset: true});
                            // model.collection.reset();
                            // Sandbox.eventNotify('plugin:shop:orderList:fetch', {reset: true});
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

        return _.extend({}, {
            columnActions: columnActions,
            columnAccountFullName: columnAccountFullName,
            columnAccountPhone: columnAccountPhone,
            columnInfoTotal: columnInfoTotal,
            columnHasPromo: columnHasPromo,
            columnDiscount: columnDiscount,
            columnStatus: columnStatus,
            columnShipping: columnShipping,
            columnWarehouse: columnWarehouse,
            columnDateUpdated: columnDateUpdated,
            columnDateCreated: columnDateCreated
        });
    }

    var ListOrders = Backbone.View.extend({
        initialize: function () {
            var self = this;
            // this.options = options;
            this.columns = {};
            // this.$counter = $('<span/>');
            if (this.collection) {
                this.listenTo(this.collection, 'reset', this.render);
                // this.render();
            }
            // Sandbox.eventSubscribe('plugin:shop:order:changed', function (diff) {
            //     // debugger;
            //     var status = self.collection.queryParams.status;
            //     if (diff.current.Status === status || diff.previous.Status === status)
            //         self.collection.fetch({reset: true});
            // });

            // displays active records count
        },
        render: function () {
            console.log('listOrders: render');
            // debugger;
            // this.columns = getColumns();
            if (this.toolboxListOrdersGrid)
                this.toolboxListOrdersGrid.remove();
            this.toolboxListOrdersGrid = null;
            if (this.paginator)
                this.paginator.remove();
            this.paginator = null;
                this.toolboxListOrdersGrid = new Backgrid.Grid({
                    className: "backgrid table table-responsive",
                    columns: _(getColumns()).values(),
                    collection: this.collection
                });
                this.paginator = new Backgrid.Extension.Paginator({
                    collection: this.collection
                });
                this.toolboxListOrdersGrid.render();
                this.paginator.render();
            this.$el.off().empty();
            if (this.toolboxListOrdersGrid)
                this.$el.append(this.toolboxListOrdersGrid.$el)
            if (this.paginator)
                this.$el.append(this.paginator.$el);

            // if (this.collection)
            //     this.$counter.html(this.collection.state.totalRecords);

            window.g1 = this;
            return this;
        }
    });

    return ListOrders;
});
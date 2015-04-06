define([
    'backbone',
    'handlebars',
    'utils',
    "backgrid",
    "formatter-price",
    /* collection */
    "plugins/shop/toolbox/js/collection/listOrders",
    /* template */
    'text!plugins/shop/toolbox/hbs/buttonMenuOrderListItem.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    /* extensions */
    "backgrid-paginator",
    "backgrid-select-all",
    "backgrid-htmlcell"
], function (Backbone, Handlebars, Utils, Backgrid, priceFmt, CollectionOrders, tplBtnMenuMainItem, lang) {

    function getColumns() {
        // we show following statuses only
        var statuses = ["NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED", "CUSTOMER_CANCELED", "SHOP_REFUNDED"];
        var orderStatusValues = _(statuses).map(function (status) {
            return [lang["order_status_" + status] || status, status];
        });

        var columnActions = {
            className: "custom-row-context-menu",
            name: "Actions",
            label: lang.pluginMenu_Orders_Grid_Column_Actions,
            cell: "html",
            editable: false,
            sortable: false,
            formatter: {
                fromRaw: function (value, model) {
                    var btn = Handlebars.compile(tplBtnMenuMainItem)(Utils.getHBSTemplateData(model.toJSON()));
                    return btn;
                }
            }
        };

        var columnHash = {
            name: "Hash",
            label: lang.pluginMenu_Orders_Grid_Column_Hash,
            cell: "html",
            editable: false,
            sortable: false,
            formatter: {
                fromRaw: function (value, model) {
                    var hash = $('<span>').addClass('label label-primary').text(value);
                    return hash;
                }
            }
        };

        var columnUserFullName = {
            name: "UserFullName",
            label: lang.pluginMenu_Orders_Grid_Column_UserFullName,
            cell: "string",
            editable: false
        };

        var columnUserPhone = {
            name: "UserPhone",
            label: lang.pluginMenu_Orders_Grid_Column_UserPhone,
            cell: "string",
            editable: false
        };

        var columnInfoTotal = {
            name: "InfoTotal",
            label: lang.pluginMenu_Orders_Grid_Column_InfoTotal,
            cell: "string",
            editable: false,
            formatter: {
                fromRaw: function (value, model) {
                    var _currencyDisplay = APP.instances.shop.settings.MISC.DBPriceCurrencyType;
                    return priceFmt(model.get('totalSummary')._total, _currencyDisplay, APP.instances.shop.settings.EXCHANAGERATESDISPLAY);
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
                initialize: function (options) {
                    var skipSave = false;
                    Backgrid.SelectCell.prototype.initialize.apply(this, arguments);
                    this.listenTo(this.model, "change:Status", function (model) {
                        if (skipSave) {
                            return;
                        }
                        skipSave = true;
                        model.save(model.changed).done(function () {
                            model.collection.fetch({
                                reset: true
                            }, {
                                success: function () {
                                    skipSave = false;
                                }
                            });
                        });
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
            columnHash: columnHash,
            columnUserFullName: columnUserFullName,
            columnUserPhone: columnUserPhone,
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
        className: 'list list-orders',
        initialize: function (options) {
            this.options = options || {};
            this.collection = this.collection || new CollectionOrders();
            this.listenTo(this.collection, 'reset', this.render);
            var columns = getColumns();
            if (this.options.adjustColumns)
                columns = this.options.adjustColumns(columns);
            this.grid = new Backgrid.Grid({
                className: "backgrid table table-responsive",
                columns: _(columns).values(),
                collection: this.collection
            });
            this.paginator = new Backgrid.Extension.Paginator({
                collection: this.collection
            });
        },
        render: function () {
            // console.log('listOrders: render');
            this.$el.off().empty();
            if (this.collection.length) {
                this.$el.append(this.grid.render().$el);
                this.$el.append(this.paginator.render().$el);
            } else {
                this.$el.html(this.grid.emptyText);
            }
            return this;
        }
    });

    return ListOrders;
});
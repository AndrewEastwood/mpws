define("plugin/shop/toolbox/js/view/listProducts", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    "default/js/lib/backgrid",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/buttonMenuProductListItem',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-select-all",
    "default/js/lib/backgrid-htmlcell"
], function (Sandbox, Backbone, Utils, Backgrid, tplBtnMenuMainItem, lang) {

    function getColumns () {
        // TODO: do smth to fetch states from server
        var statuses = ["ACTIVE", "ARCHIVED", "DISCOUNT", "DEFECT", "WAITING", "PREORDER"];
        var orderStatusValues = _(statuses).map(function (status){ return [lang["product_status_" + status] || status, status]; });

        var columnActions = {
            name: "Actions",
            label: "",
            cell: "html",
            editable: false,
            sortable: false,
            formatter: {
                fromRaw: function (value, model) {
                    var btn = tplBtnMenuMainItem(Utils.getHBSTemplateData(model.toJSON()));
                    return btn;
                }
            }
        };

        var columnName = {
            name: "Name",
            label: lang.pluginMenu_Products_Grid_Column_Name,
            cell: "string"
        };

        var columnModel = {
            name: "Model",
            label: lang.pluginMenu_Products_Grid_Column_Model,
            cell: "string"
        };

        var columnSKU = {
            name: "SKU",
            label: lang.pluginMenu_Products_Grid_Column_SKU,
            cell: "string"
        };

        var columnPrice = {
            name: "Price",
            label: lang.pluginMenu_Products_Grid_Column_Price,
            cell: "number",
            formatter: {
                fromRaw: function (value) {
                    return value + ' грн.';
                }
            }
        };

        var columnStatus = {
            name: "Status",
            label: lang.pluginMenu_Products_Grid_Column_Status,
            cell: Backgrid.SelectCell.extend({
                // It's possible to render an option group or use a
                // function to provide option values too.
                optionValues: orderStatusValues,
                initialize: function (options) {
                    Backgrid.SelectCell.prototype.initialize.apply(this, arguments);
                    this.listenTo(this.model, "change:Status", function(model) {
                        model.save(model.changed, {
                            patch: true,
                            success: function() {
                                model.collection.fetch({reset: true});
                            }
                        });
                    });
                }
            })
        };

        var columnDateUpdated = {
            name: "DateUpdated",
            label: lang.pluginMenu_Products_Grid_Column_DateUpdated,
            cell: "string",
            editable: false
        };

        var columnDateCreated = {
            name: "DateCreated",
            label: lang.pluginMenu_Products_Grid_Column_DateCreated,
            cell: "string",
            editable: false
        };

        return _.extend({}, {
            columnActions: columnActions,
            columnName: columnName,
            columnModel: columnModel,
            columnSKU: columnSKU,
            columnPrice: columnPrice,
            columnStatus: columnStatus,
            columnDateUpdated: columnDateUpdated,
            columnDateCreated: columnDateCreated
        });
    }

    var ListOrders = Backbone.View.extend({
        initialize: function (options) {
            this.options = options;
            if (this.collection) {
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
            }
        },
        render: function () {
            // console.log('listOrders: render');
            // debugger;
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
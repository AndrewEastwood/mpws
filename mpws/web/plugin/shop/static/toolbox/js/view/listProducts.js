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
                var btn = tplBtnMenuMainItem(model.toJSON());
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

    var ListOrders = Backbone.View.extend({
        initialize: function (options) {
            var self = this;
            this.options = options;
            this.columns = {
                columnActions: columnActions,
                columnName: columnName,
                columnModel: columnModel,
                columnSKU: columnSKU,
                columnStatus: columnStatus,
                columnPrice: columnPrice,
                columnDateUpdated: columnDateUpdated,
                columnDateCreated: columnDateCreated
            };
            if (this.collection)
                this.listenTo(this.collection, 'reset', this.render);
            // Sandbox.eventSubscribe('plugin:shop:prduct:changed', function (diff) {
            //     // debugger;
            //     var status = self.collection.queryParams.status;
            //     if (diff.current.Status === status || diff.previous.Status === status)
            //         self.collection.fetch({reset: true});
            // });

            // displays active records count
            this.$counter = $('<span/>');
        },
        render: function () {
            var toolboxListOrdersGrid = new Backgrid.Grid({
                className: "backgrid table table-responsive",
                columns: _(this.columns).values(),
                collection: this.collection
            });

            var paginator = new Backgrid.Extension.Paginator({
                collection: this.collection
            });

            this.$el.empty().append(toolboxListOrdersGrid.render().$el)
                .append(paginator.render().$el);

            this.$counter.html(this.collection.state.totalRecords);

            return this;
        }
    });

    return ListOrders;
});
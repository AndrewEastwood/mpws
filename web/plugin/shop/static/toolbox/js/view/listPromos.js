define("plugin/shop/toolbox/js/view/listPromos", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    "default/js/lib/backgrid",
    /* collection */
    "plugin/shop/toolbox/js/collection/listPromos",
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/buttonMenuPromoListItem',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    /* extensions */
    "default/js/lib/backgrid-paginator",
    "default/js/lib/backgrid-select-all",
    "default/js/lib/backgrid-htmlcell"
], function (Sandbox, Backbone, Utils, Backgrid, CollectionOrders, tplBtnMenuMainItem, lang) {

    function getColumns() {

        var columnActions = {
            className: "custom-row-context-menu",
            name: "Actions",
            label: lang.listPromos_Column_Actions,
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

        var columnCode = {
            name: "Code",
            label: lang.listPromos_Column_Code,
            cell: "string",
            editable: false
        };

        var columnDateStart = {
            name: "DateStart",
            label: lang.listPromos_Column_DateStart,
            cell: "string",
            editable: false
        };

        var columnDateExpire = {
            name: "DateExpire",
            label: lang.listPromos_Column_DateExpire,
            cell: "string",
            editable: false
        };

        var columnDiscount = {
            name: "Discount",
            label: lang.listPromos_Column_Discount,
            cell: "string",
            editable: false,
            formatter: {
                fromRaw: function (value) {
                    if (value)
                        return value + ' %';
                }
            }
        };

        var columnDateCreated = {
            name: "DateCreated",
            label: lang.pluginMenu_Orders_Grid_Column_DateCreated,
            cell: "string",
            editable: false
        };

        return _.extend({}, {
            columnActions: columnActions,
            columnCode: columnCode,
            columnDateStart: columnDateStart,
            columnDateExpire: columnDateExpire,
            columnDiscount: columnDiscount,
            columnDateCreated: columnDateCreated
        });
    }

    var ListOrders = Backbone.View.extend({
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
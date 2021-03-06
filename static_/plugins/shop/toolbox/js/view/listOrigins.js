define([
    'backbone',
    'handlebars',
    'utils',
    "backgrid",
    /* collection */
    "plugins/shop/toolbox/js/collection/listOrigins",
    /* template */
    'text!plugins/shop/toolbox/hbs/buttonMenuOriginListItem.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    /* extensions */
    "backgrid-paginator",
    "backgrid-select-all",
    "backgrid-htmlcell"
], function (Backbone, Handlebars, Utils, Backgrid, CollectionOrigins, tplBtnMenuMainItem, lang) {

    function getColumns() {
        // TODO: do smth to fetch states from server
        // var statuses = ["ACTIVE", "REMOVED"];
        // var orderStatusValues = _(statuses).map(function (status){ return [lang["origin_status_" + status] || status, status]; });

        var columnActions = {
            className: "custom-row-context-menu",
            name: "Actions",
            label: "",
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

        var columnName = {
            name: "Name",
            label: lang.pluginMenu_Origins_Grid_Column_Name,
            cell: "string"
        };

        var columnHomePage = {
            name: "HomePage",
            label: lang.pluginMenu_Origins_Grid_Column_HomePage,
            cell: "string"
        };

        // var columnStatus = {
        //     name: "Status",
        //     label: lang.pluginMenu_Origins_Grid_Column_Status,
        //     cell: Backgrid.SelectCell.extend({
        //         // It's possible to render an option group or use a
        //         // function to provide option values too.
        //         optionValues: orderStatusValues,
        //         initialize: function (options) {
        //             Backgrid.SelectCell.prototype.initialize.apply(this, arguments);
        //             this.listenTo(this.model, "change:Status", function(model) {
        //                 model.save(model.changed, {
        //                     patch: true,
        //                     success: function() {
        //                         model.collection.fetch({reset: true});
        //                     }
        //                 });
        //             });
        //         }
        //     })
        // };

        var columnDateUpdated = {
            name: "DateUpdated",
            label: lang.pluginMenu_Origins_Grid_Column_DateUpdated,
            cell: "string",
            editable: false
        };

        var columnDateCreated = {
            name: "DateCreated",
            label: lang.pluginMenu_Origins_Grid_Column_DateCreated,
            cell: "string",
            editable: false
        };

        return _.extend({}, {
            columnActions: columnActions,
            columnName: columnName,
            columnHomePage: columnHomePage,
            // columnStatus: columnStatus,
            columnDateUpdated: columnDateUpdated,
            columnDateCreated: columnDateCreated
        });
    }

    var ListOrders = Backbone.View.extend({
        className: 'list list-origins',
        initialize: function (options) {
            this.options = options || {};
            this.collection = this.collection || new CollectionOrigins();
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
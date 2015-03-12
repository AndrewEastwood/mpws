define([
    'backbone',
    'handlebars',
    'utils',
    "backgrid",
    'bootstrap-dialog',
    /* collection */
    "plugins/shop/toolbox/js/collection/listPromos",
    /* template */
    'text!plugins/shop/toolbox/hbs/buttonMenuPromoListItem.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    /* extensions */
    "backgrid-paginator",
    "backgrid-select-all",
    'moment',
    "backgrid-htmlcell"
], function (Backbone, Handlebars, Utils, Backgrid, BootstrapDialog, CollectionOrders, tplBtnMenuMainItem, lang) {

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
                    var btn = [];
                    if (model.get('_isExpired')) {
                        btn.push($('<i/>').addClass('fa fa-ellipsis-v text-danger'));
                        btn.push($('<i/>').addClass('fa fa-ellipsis-v text-danger'));
                    } else {
                        if (model.get('_isFuture')) {
                            btn.push($('<i/>').addClass('fa fa-ellipsis-v text-warning'));
                            btn.push($('<i/>').addClass('fa fa-ellipsis-v text-warning'));
                        } else {
                            btn.push($('<i/>').addClass('fa fa-ellipsis-v text-success'));
                            btn.push($('<i/>').addClass('fa fa-ellipsis-v text-success'));
                        }
                        btn.push(Handlebars.compile(tplBtnMenuMainItem)(Utils.getHBSTemplateData(model.toJSON())));
                    }

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
            editable: false,
            formatter: {
                fromRaw: function (value) {
                    return moment(value).format('YYYY-MM-DD');
                }
            }
        };

        var columnDateExpire = {
            name: "DateExpire",
            label: lang.listPromos_Column_DateExpire,
            cell: "string",
            editable: false,
            formatter: {
                fromRaw: function (value) {
                    return moment(value).format('YYYY-MM-DD');
                }
            }
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
        events: {
            'click .expire-promo': 'expirePromo'
        },
        className: 'list list-promos',
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
            this.delegateEvents();
            return this;
        },
        expirePromo: function (event) {
            var id = $(event.target).data('id'),
                model = this.collection.get(id);

            if (!model) {
                return;
            }

            BootstrapDialog.confirm("Запинити цю промо-акцію?", function (rez) {
                if (rez) {
                    model.destroy();
                }
            });
        }
    });

    return ListOrders;
});
define([
    'backbone',
    'handlebars',
    'utils',
    "backgrid",
    "formatter-price",
    /* collection */
    "plugins/shop/toolbox/js/collection/listProducts",
    /* template */
    'text!plugins/shop/toolbox/hbs/buttonMenuProductListItem.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    /* extensions */
    "spin",
    "backgrid-paginator",
    "backgrid-select-all",
    "backgrid-htmlcell"
], function (Backbone, Handlebars, Utils, Backgrid, priceFmt, CollectionProducts, tplBtnMenuMainItem, lang, Spinner) {

    var opts = {
        lines: 9, // The number of lines to draw
        length: 5, // The length of each line
        width: 8, // The line thickness
        radius: 15, // The radius of the inner circle
        corners: 0.9, // Corner roundness (0..1)
        rotate: 0, // The rotation offset
        direction: 1, // 1: clockwise, -1: counterclockwise
        color: '#000', // #rgb or #rrggbb or array of colors
        speed: 1.1, // Rounds per second
        trail: 58, // Afterglow percentage
        shadow: false, // Whether to render a shadow
        hwaccel: false, // Whether to use hardware acceleration
        className: 'mpwsDataSpinner', // The CSS class to assign to the spinner
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        top: '10%', // Top position relative to parent
        left: '50%' // Left position relative to parent
    };

    var spinner = new Spinner(opts).spin();

    function getColumns() {
        // TODO: do smth to fetch states from server
        var statuses = ["ACTIVE", "ARCHIVED", "DISCOUNT", "DEFECT", "WAITING", "PREORDER"];
        var orderStatusValues = _(statuses).map(function (status) {
            return [lang["product_status_" + status] || status, status];
        });

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
                    var dnd = $('<span class="dndrow"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>');
                    return [dnd, btn];
                }
            }
        };
        var columnID = {
            name: "ID",
            label: lang.pluginMenu_Products_Grid_Column_ID,
            cell: 'html',
            editable: false,
            sortable: false,
            formatter: {
                fromRaw: function (value, model) {
                    var id = $('<span>').addClass('label label-primary').text(value);
                    return id;
                }
            }
        };

        var columnName = {
            name: "Name",
            label: lang.pluginMenu_Products_Grid_Column_Name,
            cell: Backgrid.StringCell.extend({
                initialize: function (options) {
                    Backgrid.StringCell.prototype.initialize.apply(this, arguments);
                    this.listenTo(this.model, "change:Name", function (model) {
                        model.save(model.changed, {
                            patch: true,
                            silent: true,
                            success: function () {
                                model.collection.fetch({
                                    reset: true
                                });
                            }
                        });
                    });
                }
            })
        };

        var columnModel = {
            name: "Model",
            label: lang.pluginMenu_Products_Grid_Column_Model,
            cell: Backgrid.StringCell.extend({
                initialize: function (options) {
                    Backgrid.StringCell.prototype.initialize.apply(this, arguments);
                    this.listenTo(this.model, "change:Model", function (model) {
                        model.save(model.changed, {
                            patch: true,
                            silent: true,
                            success: function () {
                                model.collection.fetch({
                                    reset: true
                                });
                            }
                        });
                    });
                }
            })
        };

        var columnOriginName = {
            name: "OriginName",
            label: lang.pluginMenu_Products_Grid_Column_OriginName,
            cell: "string",
            editable: false,
            formatter: {
                fromRaw: function (value, model) {
                    return model.get('_origin').Name
                }
            }
        };

        var columnCategoryName = {
            name: "CategoryName",
            label: lang.pluginMenu_Products_Grid_Column_CategoryName,
            cell: "string",
            editable: false,
            formatter: {
                fromRaw: function (value, model) {
                    return model.get('_category').Name
                }
            }
        };

        var columnSKU = {
            name: "SKU",
            label: lang.pluginMenu_Products_Grid_Column_SKU,
            cell: Backgrid.StringCell.extend({
                initialize: function (options) {
                    Backgrid.StringCell.prototype.initialize.apply(this, arguments);
                    this.listenTo(this.model, "change:SKU", function (model) {
                        model.save(model.changed, {
                            patch: true,
                            silent: true,
                            success: function () {
                                model.collection.fetch({
                                    reset: true
                                });
                            }
                        });
                    });
                }
            })
        };

        var columnPrice = {
            name: "Price",
            label: lang.pluginMenu_Products_Grid_Column_Price,
            cell: Backgrid.NumberCell.extend({
                initialize: function (options) {
                    Backgrid.StringCell.prototype.initialize.apply(this, arguments);
                    this.listenTo(this.model, "change:Price", function (model) {
                        model.save(model.changed, {
                            patch: true,
                            silent: true,
                            success: function () {
                                model.collection.fetch({
                                    reset: true
                                });
                            }
                        });
                    });
                }
            }),
            formatter: {
                fromRaw: function (value, model) {
                    var _prices = model.get('_prices'),
                        _currencyDisplay = APP.instances.shop.settings.MISC.DBPriceCurrencyType;
                    return priceFmt(_prices.price, _currencyDisplay, APP.instances.shop.settings.EXCHANAGERATESDISPLAY);

                    // if (_currencyDisplay) {
                    //     if (_currencyDisplay.showBeforeValue) {
                    //         return _currencyDisplay.text + _prices.price;
                    //     } else {
                    //         return _prices.price + _currencyDisplay.text;
                    //     }
                    // } else {
                    //     return _prices.price;
                    // }
                },
                toRaw: function (value) {
                    var matches = value.replace( /^\D+/g, '').match(/^([0-9\.]+)/)
                    if (matches && matches[1])
                        return parseFloat(matches[0]);
                    throw "CanParseProductPrise"
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
                    this.listenTo(this.model, "change:Status", function (model) {
                        model.save(model.changed, {
                            patch: true,
                            success: function () {
                                model.collection.fetch({
                                    reset: true
                                });
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
            columnID: columnID,
            columnName: columnName,
            columnModel: columnModel,
            columnOriginName: columnOriginName,
            columnCategoryName: columnCategoryName,
            columnSKU: columnSKU,
            columnPrice: columnPrice,
            columnStatus: columnStatus,
            columnDateUpdated: columnDateUpdated,
            columnDateCreated: columnDateCreated
        });
    }

    var ListOrders = Backbone.View.extend({
        className: 'list list-products',
        initialize: function (options) {
            this.options = options || {};
            this.collection = this.collection || new CollectionProducts();
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'request', this.startLoadingAnim);
            this.listenTo(this.collection, 'sync error', this.stopLoadingAnim);
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
            _.bindAll(this, 'startLoadingAnim', 'stopLoadingAnim', 'render');
        },
        startLoadingAnim: function () {
            var self = this;
            setTimeout(function () {
                console.log('adding spinner');
                self.$('.mpwsDataSpinner').remove();
                self.$el.append(spinner.el);
            }, 0);
        },
        stopLoadingAnim: function () {
            setTimeout(function () {
                this.$('.mpwsDataSpinner').remove();
            }, 200);
        },
        render: function () {
            console.log('listOrders: render');
            // debugger;
            this.$el.off().empty();
            if (this.collection.length) {
                this.$el.append(this.grid.render().$el);
                this.$el.append(this.paginator.render().$el);
                this.startLoadingAnim();
            } else {
                this.$el.html(this.grid.emptyText);
            }
            return this;
        }
    });

    return ListOrders;
});
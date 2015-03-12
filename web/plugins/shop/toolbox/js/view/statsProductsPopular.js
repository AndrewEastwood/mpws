define([
    'backbone',
    'plugins/shop/toolbox/js/collection/statsProductsPopular',
    'plugins/shop/toolbox/js/view/listProducts',
    'formatter-price',
    'utils',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/statsProductsPopular',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, CollectionProductsPopular, ViewListProducts, priceFmt, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            this.collection = new CollectionProductsPopular();
            this.viewList = new ViewListProducts({
                collection: this.collection,
                adjustColumns: function (columns) {
                    delete columns.columnID;
                    delete columns.columnDateUpdated;
                    delete columns.columnDateCreated;
                    delete columns.columnSKU;
                    delete columns.columnModel;
                    columns.SoldTotal = {
                        name: "SoldTotal",
                        label: "Продано",
                        cell: "string",
                        editable: false
                    };
                    columns.SumTotal = {
                        name: "SumTotal",
                        label: "На суму",
                        cell: "string",
                        editable: false,
                        formatter: {
                            fromRaw: function (value) {
                                var _currencyDisplay = APP.instances.shop.settings.MISC.DBPriceCurrencyType;
                                return priceFmt(value, _currencyDisplay, APP.instances.shop.settings.EXCHANAGERATESDISPLAY);
                            }
                        }
                    };
                    return columns;
                }
            });
            this.viewList.grid.emptyText = "Немає популярних товарів";
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.panel-body').html(this.viewList.$el);
            return this;
        }
    });
});
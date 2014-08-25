define("plugin/shop/toolbox/js/view/statsProductsPopular", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/collection/statsProductsPopular',
    'plugin/shop/toolbox/js/view/listProducts',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/statsProductsPopular',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Backbone, CollectionProductsPopular, ViewListProducts, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.collection = new CollectionProductsPopular();
            this.viewList = new ViewListProducts({
                collection: this.collection,
                adjustColumns: function (columns) {
                    delete columns.columnDateUpdated;
                    delete columns.columnDateCreated;
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
                                return value + ' грн.';
                            }
                        }
                    };
                    return columns;
                }
            });
            this.viewList.grid.emptyText = "Немає популярних товарів";
            this.render();
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.panel-body').html(this.viewList.$el);
            return this;
        }
    });
});
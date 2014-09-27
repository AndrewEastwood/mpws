define("plugin/shop/toolbox/js/view/statsProductsNonPopular", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/collection/statsProductsNonPopular',
    'plugin/shop/toolbox/js/view/listProducts',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/statsProductsNonPopular',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Backbone, CollectionProductsNonPopular, ViewListProducts, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.collection = new CollectionProductsNonPopular();
            this.viewList = new ViewListProducts({
                collection: this.collection,
                adjustColumns: function (columns) {
                    delete columns.columnDateUpdated;
                    delete columns.columnDateCreated;
                    return columns;
                }
            });
            this.viewList.grid.emptyText = "Немає не популярних товарів";
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.panel-body').html(this.viewList.$el);
            return this;
        }
    });
});
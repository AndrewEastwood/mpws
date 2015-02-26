define([
    'backbone',
    'plugins/shop/toolbox/js/collection/statsProductsNonPopular',
    'plugins/shop/toolbox/js/view/listProducts',
    'utils',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/statsProductsNonPopular',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
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
                    delete columns.columnID;
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
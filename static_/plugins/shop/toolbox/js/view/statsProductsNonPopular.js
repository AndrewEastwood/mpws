define([
    'backbone',
    'handlebars',
    'plugins/shop/toolbox/js/collection/statsProductsNonPopular',
    'plugins/shop/toolbox/js/view/listProducts',
    'utils',
    /* template */
    'text!plugins/shop/toolbox/hbs/statsProductsNonPopular.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, Handlebars, CollectionProductsNonPopular, ViewListProducts, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: Handlebars.compile(tpl), // check
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
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.$('.panel-body').html(this.viewList.$el);
            return this;
        }
    });
});
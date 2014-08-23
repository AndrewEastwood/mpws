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
            this.list = new ViewListProducts({collection: this.collection});
            // delete listView.columns.columnStatus;
            // delete this.list.columns.columnShipping;
            // delete this.list.columns.columnWarehouse;
            delete this.list.columns.columnDateUpdated;
            delete this.list.columns.columnDateCreated;
            this.listenTo(this.collection, 'update reset', this.render);
        },
        render: function () {
            // debugger;
            // adjust columns
            // render into panel body
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            if (this.collection.length)
                this.$('.panel-body').html(this.list.$el);
            return this;
        }
    });
});
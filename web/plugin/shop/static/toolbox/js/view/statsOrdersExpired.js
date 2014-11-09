define("plugin/shop/toolbox/js/view/statsOrdersExpired", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/collection/statsOrdersExpired',
    'plugin/shop/toolbox/js/view/listOrders',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/statsOrdersExpired',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Backbone, CollectionOrdersExpired, ViewListOrders, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-danger',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.collection = new CollectionOrdersExpired();
            this.viewList = new ViewListOrders({
                collection: this.collection,
                adjustColumns: function (columns) {
                    // adjust columns
                    delete columns.columnHash;
                    delete columns.columnShipping;
                    delete columns.columnWarehouse;
                    delete columns.columnDateUpdated;
                    delete columns.columnDateCreated;
                    return columns;
                }
            });
            this.viewList.grid.emptyText = "Немає протермінованих замовлень";
        },
        render: function () {
            // render into panel body
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.panel-body').html(this.viewList.$el);
            return this;
        }
    });
});
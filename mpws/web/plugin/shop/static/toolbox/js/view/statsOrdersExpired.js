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
                    delete columns.columnShipping;
                    delete columns.columnWarehouse;
                    delete columns.columnDateUpdated;
                    delete columns.columnDateCreated;
                    return columns;
                }
            });
        },
        render: function () {
            // render into panel body
            if (this.$el.is(':empty')) {
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                this.viewList.grid.emptyText = "Немає протермінованих замовлень";
                this.$('.panel-body').html(this.viewList.$el);
            }
            return this;
        }
    });
});
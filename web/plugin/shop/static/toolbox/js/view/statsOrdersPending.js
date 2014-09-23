define("plugin/shop/toolbox/js/view/statsOrdersPending", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/collection/statsOrdersPending',
    'plugin/shop/toolbox/js/view/listOrders',
    'default/js/lib/utils',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/statsOrdersPending',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Backbone, CollectionOrdersTodays, ViewListOrders, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-success',
        lang: lang,
        template: tpl,
        initialize: function () {
            this.collection = new CollectionOrdersTodays();
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
                this.viewList.grid.emptyText = "Всі замовлення оброблені";
                this.$('.panel-body').html(this.viewList.$el);
            }
            return this;
        }
    });
});
define([
    'backbone',
    'plugins/shop/toolbox/js/collection/statsOrdersPending',
    'plugins/shop/toolbox/js/view/listOrders',
    'utils',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/statsOrdersPending',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
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
                    delete columns.columnHash;
                    delete columns.columnShipping;
                    delete columns.columnWarehouse;
                    delete columns.columnDateUpdated;
                    delete columns.columnDateCreated;
                    return columns;
                }
            });
            this.viewList.grid.emptyText = "Всі замовлення оброблені";
        },
        render: function () {
            // render into panel body
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.panel-body').html(this.viewList.$el);
            return this;
        }
    });
});
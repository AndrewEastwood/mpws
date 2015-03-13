define([
    'backbone',
    'handlebars',
    'plugins/shop/toolbox/js/collection/statsOrdersExpired',
    'plugins/shop/toolbox/js/view/listOrders',
    'utils',
    /* template */
    'text!plugins/shop/toolbox/hbs/statsOrdersExpired.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, Handlebars, CollectionOrdersExpired, ViewListOrders, Utils, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-danger',
        lang: lang,
        template: Handlebars.compile(tpl), // check
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
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.$('.panel-body').html(this.viewList.$el);
            return this;
        }
    });
});
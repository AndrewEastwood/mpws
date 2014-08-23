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
        events: {
            'click .refresh': 'refresh'
        },
        initialize: function () {
            // debugger;
            this.list = new ViewListOrders({collection: this.collection});
            // adjust columns
            // delete listView.columns.columnStatus;
            delete this.list.columns.columnShipping;
            delete this.list.columns.columnWarehouse;
            delete this.list.columns.columnDateUpdated;
            delete this.list.columns.columnDateCreated;
            this.collection = new CollectionOrdersTodays();
            this.listenTo(this.collection, 'update reset', this.render);
        },
        refresh: function () {
            this.collection.fetch({reset: true});
        },
        render: function () {
            // render into panel body
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            if (this.collection.length)
                this.$('.panel-body').html(this.list.$el);
            return this;
        }
    });
});
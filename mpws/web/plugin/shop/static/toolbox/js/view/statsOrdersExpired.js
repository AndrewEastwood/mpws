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
        events: {
            'click .refresh': 'refresh'
        },
        initialize: function () {
            this.collection = new CollectionOrdersExpired();
            this.list = new ViewListOrders({collection: this.collection});
            // delete listView.columns.columnStatus;
            delete this.list.columns.columnShipping;
            delete this.list.columns.columnWarehouse;
            delete this.list.columns.columnDateUpdated;
            delete this.list.columns.columnDateCreated;
            this.listenTo(this.collection, 'update reset', this.render);
        },
        refresh: function () {
            this.collection.fetch({update: true});
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
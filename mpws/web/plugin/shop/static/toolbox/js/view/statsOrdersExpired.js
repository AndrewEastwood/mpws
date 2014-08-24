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
            this.listenTo(this.collection, 'reset', this.render);
        },
        refresh: function () {
            this.collection.fetch({reset: true});
        },
        render: function () {
            // debugger;
            // adjust columns
            // render into panel body
            if (this.viewList) {
                this.viewList.$el.off();
                this.viewList.undelegateEvents();
                this.viewList.remove();
            }
            this.viewList = new ViewListOrders({collection: this.collection});
            // delete listView.columns.columnStatus;
            delete this.viewList.columns.columnShipping;
            delete this.viewList.columns.columnWarehouse;
            delete this.viewList.columns.columnDateUpdated;
            delete this.viewList.columns.columnDateCreated;


            this.viewList.render();
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            if (this.collection.length)
                this.$('.panel-body').html(this.viewList.$el);
            return this;
        }
    });
});
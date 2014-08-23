define('plugin/shop/toolbox/js/view/managerOrders', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/collection/basicOrders',
    'plugin/shop/toolbox/js/view/listOrders',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerOrders',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, CollectionOrders, ViewListOrders, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-orders',
        // statuses: ["NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED"],
        // listsByStatus: {},
        initialize: function (options) {
            this.options = options;
            this.collection = new CollectionOrders();
            if (this.options.status)
                this.collection.queryParams.status = this.options.status;
            this.listenTo(this.collection, 'update reset', this.render);
            this.list = new ViewListOrders({collection: this.collection});
            this.list.render();
        },
        render: function () {
            // TODO:
            // add expired and todays orders
            var self = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            self.$('.tab-link orders-' + this.options.status + ' .badge').html(this.list.$counter);
            self.$('.tab-pane').html(this.list.$el);

            // _(this.listsByStatus).each(function(listView, status){
            //     listView.collection.fetch({reset: true});
            // });
            // debugger;
            return this;
        }
    });

    return ManagerOrders;

});
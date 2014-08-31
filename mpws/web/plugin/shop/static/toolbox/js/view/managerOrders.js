define('plugin/shop/toolbox/js/view/managerOrders', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/collection/basicOrders',
    'plugin/shop/toolbox/js/view/listOrders',
    'plugin/shop/toolbox/js/model/statsOrdersOverview',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerOrders',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, CollectionOrders, ViewListOrders, ModelOrdersOverview, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-orders',
        initialize: function (options) {
            var self = this;
            // debugger;
            // set options
            this.setOptions(options);
            // create collection and viewList
            this.collection = new CollectionOrders();
            this.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            this.collection.setCustomQueryParam("Stats", true);
            this.listenTo(this.collection, 'reset', this.render);
            this.listenTo(this.collection, 'sync', function (collection, resp, options) {
                if (resp && resp.stats)
                    self.refreshBadges(resp.stats);
            });
            this.viewList = new ViewListOrders({collection: this.collection});
            this.viewList.grid.emptyText = lang.pluginMenu_Orders_Grid_noData_ByStatus;
            this.viewList.render();
        },
        setOptions: function (options) {
            // merge with defaults
            this.options = _.defaults({}, options, {
                status: "NEW"
            });
            // and adjust them
            if (!this.options.Status)
                this.options.Status = "NEW";
        },
        refreshBadges: function (stats) {
            this.$('.tab-link .badge').html("0");
            _(stats).each(function(ordersCount, orderStatus){
                this.$('.tab-link.orders-' + orderStatus.toLowerCase() + ' .badge').html(parseInt(ordersCount, 10) || 0);
            });
        },
        render: function () {
            // TODO:
            // add expired and todays orders
            var self = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            var currentStatus = this.collection.getCustomQueryField("Status");
            self.$('.tab-link.orders-' + currentStatus.toLowerCase()).addClass('active');
            // show sub-view
            self.$('.tab-pane').html(self.viewList.$el);
            return this;
        }
    });

    return ManagerOrders;

});
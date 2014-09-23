define('plugin/shop/toolbox/js/view/managerOrders', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/listOrders',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerOrders',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, ViewOrdersListOrders, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-orders',
        initialize: function (options) {
            // debugger;
            // set options
            this.setOptions(options);
            // create collection and viewOrdersList
            this.viewOrdersList = new ViewOrdersListOrders();
            this.viewOrdersList.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            this.viewOrdersList.collection.setCustomQueryParam("Stats", true);
            this.listenTo(this.viewOrdersList.collection, 'reset', this.render);
            this.listenTo(this.viewOrdersList.collection, 'sync', $.proxy(function (collection, resp, options) {
                if (resp && resp.stats)
                    this.refreshBadges(resp.stats);
            }, this));
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
            _(stats).each(function(count, status){
                this.$('.tab-link.orders-' + status.toLowerCase() + ' .badge').html(parseInt(count, 10) || 0);
            });
        },
        render: function () {
            // TODO:
            // add expired and todays orders
            if (this.$el.is(':empty')) {
                this.viewOrdersList.grid.emptyText = lang.pluginMenu_Orders_Grid_noData_ByStatus;
                this.viewOrdersList.render();
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                // show sub-view
                this.$('.tab-pane').html(this.viewOrdersList.$el);
            }
            var currentStatus = this.viewOrdersList.collection.getCustomQueryField("Status");
            this.$('.tab-link.orders-' + currentStatus.toLowerCase()).addClass('active');
            return this;
        }
    });

    return ManagerOrders;

});
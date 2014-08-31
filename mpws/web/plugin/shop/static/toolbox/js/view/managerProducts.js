define('plugin/shop/toolbox/js/view/managerProducts', [
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
            // debugger;
            // set options
            this.setOptions(options);
            // create collection and viewList
            this.collection = new CollectionOrders();
            this.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            this.listenTo(this.collection, 'reset', this.render);
            this.viewList = new ViewListOrders({collection: this.collection});
            // create stats model
            this.modelStats = new ModelOrdersOverview();
            this.modelStats.on('change', this.refreshBadges, this);
            // render viewList (it's subview) only once
            // because it's $el is being changed every time
            // when collection raises reset event
            this.viewList.render();
        },
        setOptions: function (options) {
            // merge with defaults
            this.options = _.defaults({}, options, {
                status: "NEW"
            });
            // and adjust thme
            if (!this.options.Status)
                this.options.Status = "NEW";
        },
        refreshBadges: function () {
            // debugger;
            var stats = this.modelStats.toJSON();
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
            // debugger;
            self.$('.tab-link.orders-' + currentStatus.toLowerCase()).addClass('active');
            // show sub-view
            self.$('.tab-pane').html(this.viewList.$el);
            this.modelStats.clear({silent: true}).fetch();
            return this;
        }
    });

    return ManagerOrders;

});
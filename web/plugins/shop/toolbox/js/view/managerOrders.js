define([
    'backbone',
    'handlebars',
    'utils',
    'plugins/shop/toolbox/js/view/listOrders',
    /* template */
    'text!plugins/shop/toolbox/hbs/managerOrders.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-tagsinput'
], function (Backbone, Handlebars, Utils, ViewOrdersListOrders, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: Handlebars.compile(tpl), // check
        lang: lang,
        className: 'plugin-shop-orders',
        events: {
            'itemAdded .search': 'search',
            'itemRemoved .search': 'search'
        },
        initialize: function (options) {
            // debugger;
            this.options = options || {};
            this.viewOrdersList = new ViewOrdersListOrders();
            if (this.options.status) {
                this.viewOrdersList.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            }
            this.viewOrdersList.collection.setCustomQueryParam("Stats", true);
            this.listenTo(this.viewOrdersList.collection, 'reset', this.render);
            this.listenTo(this.viewOrdersList.collection, 'sync', $.proxy(function (collection, resp, options) {
                if (resp && resp.stats)
                    this.refreshBadges(resp.stats);
            }, this));
        },
        getDisplayStatus: function () {
            var status = this.options && this.options.status || 'all';
            return status.toLowerCase();
        },
        refreshBadges: function (stats) {
            var self = this;
            this.$('.tab-link .badge').html("0");
            this.$('.tab-link.orders-' + this.getDisplayStatus()).addClass('active');
            _(stats).each(function(count, status){
                self.$('.tab-link.orders-' + status.toLowerCase() + ' .badge').html(parseInt(count, 10) || 0);
            });
        },
        render: function () {
            // TODO:
            // add expired and todays orders
            if (this.$el.is(':empty')) {
                this.viewOrdersList.grid.emptyText = $('<h4>').text(lang.pluginMenu_Orders_Grid_noData_ByStatus);
                this.viewOrdersList.render();
                this.$el.html(this.template(Utils.getHBSTemplateData(this)));
                // show sub-view
                this.$('.orders').html(this.viewOrdersList.$el);
                this.$('.search').tagsinput();
            }
            // var currentStatus = this.viewOrdersList.collection.getCustomQueryField("Status") || 'all';
            // this.$('.tab-link.orders-' + currentStatus.toLowerCase()).addClass('active');
            return this;
        },
        search: function () {
            this.viewOrdersList.collection.setCustomQueryParam("Search", $(".search").tagsinput('items'));
            this.viewOrdersList.collection.fetch();
        }
    });

    return ManagerOrders;

});
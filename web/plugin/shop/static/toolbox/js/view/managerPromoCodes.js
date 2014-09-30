define('plugin/shop/toolbox/js/view/managerPromoCodes', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/listPromos',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerPromoCodes',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, ViewOrdersListPromos, tpl, lang) {

    var ManagerPromos = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-promos',
        initialize: function (options) {
            // debugger;
            // set options
            this.setOptions(options);
            // create collection and viewPromosList
            this.viewPromosList = new ViewOrdersListPromos();
            // this.viewPromosList.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            // this.viewPromosList.collection.setCustomQueryParam("Stats", true);
            this.listenTo(this.viewPromosList.collection, 'reset', this.render);
            // this.listenTo(this.viewPromosList.collection, 'sync', $.proxy(function (collection, resp, options) {
            //     if (resp && resp.stats)
            //         this.refreshBadges(resp.stats);
            // }, this));
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
            _(stats).each(function (count, status) {
                this.$('.tab-link.orders-' + status.toLowerCase() + ' .badge').html(parseInt(count, 10) || 0);
            });
        },
        render: function () {
            // TODO:
            // add expired and todays orders
            if (this.$el.is(':empty')) {
                this.viewPromosList.grid.emptyText = lang.pluginMenu_Orders_Grid_noData_ByStatus;
                this.viewPromosList.render();
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                // show sub-view
                this.$('.promo-list').html(this.viewPromosList.$el);
            }
            // var currentStatus = this.viewPromosList.collection.getCustomQueryField("Status");
            // this.$('.tab-link.orders-' + currentStatus.toLowerCase()).addClass('active');
            return this;
        }
    });

    return ManagerPromos;

});
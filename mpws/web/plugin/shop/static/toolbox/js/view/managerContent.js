define('plugin/shop/toolbox/js/view/managerContent', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/managerContent_Products',
    'plugin/shop/toolbox/js/view/categoriesTree',
    'plugin/shop/toolbox/js/view/listOrigins',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerContent',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, ViewListProducts, ViewCategoriesTree, ViewListOrigins, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-products',
        initialize: function (options) {
            // set options
            this.setOptions(options);

            // ini sub-views
            this.viewProductsList = new ViewListProducts();
            this.viewOriginsList = new ViewListOrigins();
            this.viewCatergoriesTree = new ViewCategoriesTree();

            // subscribe on events
            this.viewProductsList.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            this.viewProductsList.collection.setCustomQueryParam("Stats", true);
            this.listenTo(this.viewProductsList.collection, 'reset', this.render);
            this.listenTo(this.viewProductsList.collection, 'sync', $.proxy(function (collection, resp, options) {
                if (this && resp.stats)
                    this.refreshBadges(resp.stats);
            }, this));

            this.viewCatergoriesTree.on('changed:category', _.debounce(function (activeCategoryID) {
                this.viewProductsList.collection.setCustomQueryField("CategoryID", activeCategoryID);
                // temporary solution
                this.viewProductsList.collection.fetch({reset: true});
            }, 1000), this);
        },
        setOptions: function (options) {
            // merge with defaults
            this.options = _.defaults({}, options, {
                status: "ACTIVE"
            });
            // and adjust thme
            if (!this.options.Status)
                this.options.Status = "ACTIVE";
        },
        refreshBadges: function (stats) {
            this.$('.tab-link .badge').html("0");
            _(stats).each(function(count, status) {
                this.$('.tab-link.products-' + status.toLowerCase() + ' .badge').html(parseInt(count, 10) || 0);
            });
        },
        render: function () {
            // TODO:
            // add expired and todays products
            // permanent layout and some elements
            if (this.$el.is(':empty')) {
                this.viewOriginsList.grid.emptyText = lang.pluginMenu_Origins_Grid_noData;
                this.viewProductsList.grid.emptyText = lang.pluginMenu_Products_Grid_noData_ByStatus;
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                this.$('.tree').html(this.viewCatergoriesTree.$el);
                this.$('.products').html(this.viewProductsList.$el);
                this.$('.origins').html(this.viewOriginsList.$el);
            }
            return this;
        }
    });

    return ManagerOrders;

});
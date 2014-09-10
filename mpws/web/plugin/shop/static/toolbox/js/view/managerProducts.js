define('plugin/shop/toolbox/js/view/managerProducts', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/listProducts',
    'plugin/shop/toolbox/js/view/categoriesTree',
    'plugin/shop/toolbox/js/view/listOrigins',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerProducts',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, ViewListProducts, ViewCategoriesTree, ViewListOrigins, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-products',
        initialize: function (options) {
            var self = this;
            // set options
            this.setOptions(options);
            this.viewProductsList = new ViewListProducts();
            this.viewProductsList.collection.setCustomQueryField("Status", this.options.status.toUpperCase());
            this.viewProductsList.collection.setCustomQueryParam("Stats", true);
            this.listenTo(this.viewProductsList.collection, 'reset', this.render);
            this.listenTo(this.viewProductsList.collection, 'sync', function (collection, resp, options) {
                if (resp && resp.stats)
                    self.refreshBadges(resp.stats);
            });
            this.viewProductsList.grid.emptyText = lang.pluginMenu_Products_Grid_noData_ByStatus;
            this.viewProductsList.render();

            this.viewCatergoriesTree = new ViewCategoriesTree();
            this.viewCatergoriesTree.collection.fetch({reset: true});
            this.viewCatergoriesTree.on('changed:category', function (activeCategoryID) {
                self.viewProductsList.collection.setCustomQueryField("CategoryID", activeCategoryID);
                self.viewProductsList.collection.fetch({reset: true});
            }, this);

            this.viewListOrigins = new ViewListOrigins();
            this.viewListOrigins.grid.emptyText = lang.pluginMenu_Origins_Grid_noData;
            this.viewListOrigins.collection.fetch({reset: true});
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
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                this.$('.tree').html(this.viewCatergoriesTree.$el);
                this.$('.products').html(this.viewProductsList.$el);
                this.$('.origins').html(this.viewListOrigins.$el);
            }
            // render/refresh product list
            var currentStatus = this.viewProductsList.collection.getCustomQueryField("Status");
            this.$('.tab-link.products-' + currentStatus.toLowerCase()).addClass('active');
            return this;
        }
    });

    return ManagerOrders;

});
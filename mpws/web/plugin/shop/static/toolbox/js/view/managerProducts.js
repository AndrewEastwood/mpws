define('plugin/shop/toolbox/js/view/managerProducts', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/collection/basicProducts',
    'plugin/shop/toolbox/js/view/listProducts',
    'plugin/shop/toolbox/js/view/categoriesTree',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerProducts',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, CollectionOrders, ViewListOrders, ViewCategoriesTree, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-products',
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
            this.viewList.grid.emptyText = lang.pluginMenu_Products_Grid_noData_ByStatus;
            this.viewList.render();
            this.viewCatergoriesTree = new ViewCategoriesTree();
            this.viewCatergoriesTree.collection.fetch({reset: true});
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
            _(stats).each(function(count, status){
                this.$('.tab-link.products-' + status.toLowerCase() + ' .badge').html(parseInt(count, 10) || 0);
            });
        },
        render: function () {
            // TODO:
            // add expired and todays products
            var self = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            var currentStatus = this.collection.getCustomQueryField("Status");
            // debugger;
            self.$('.tab-link.products-' + currentStatus.toLowerCase()).addClass('active');
            // show sub-view
            self.$('.tab-pane').html(this.viewList.$el);
            self.$('.tree').html(this.viewCatergoriesTree.$el);
            return this;
        }
    });

    return ManagerOrders;

});
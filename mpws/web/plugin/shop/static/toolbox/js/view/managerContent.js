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

            // ini sub-views
            this.viewProductsList = new ViewListProducts(options);
            this.viewOriginsList = new ViewListOrigins(options);
            this.viewCatergoriesTree = new ViewCategoriesTree(options);

            // subscribe on events
            this.listenTo(this.viewProductsList.collection, 'reset', this.render);

            this.viewCatergoriesTree.on('changed:category', _.debounce(function (activeCategoryID) {
                this.viewProductsList.collection.setCustomQueryField("CategoryID", activeCategoryID);
                // temporary solution
                this.viewProductsList.collection.fetch({reset: true});
            }, 1000), this);
        },
        render: function () {
            // TODO:
            // add expired and todays products
            // permanent layout and some elements
            if (this.$el.is(':empty')) {
                // this.viewOriginsList.grid.emptyText = lang.pluginMenu_Origins_Grid_noData;
                // this.viewProductsList.grid.emptyText = lang.pluginMenu_Products_Grid_noData_ByStatus;
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
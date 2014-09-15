define('plugin/shop/toolbox/js/view/managerContent', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/managerContent_Products',
    'plugin/shop/toolbox/js/view/managerContent_Origins',
    'plugin/shop/toolbox/js/view/categoriesTree',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerContent',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, ViewListProducts, ViewListOrigins, ViewCategoriesTree, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-products',
        initialize: function (options) {
            // set options
            // ini sub-views
            // debugger;
            this.viewProductsList = new ViewListProducts(options);
            this.viewOriginsList = new ViewListOrigins(options);
            this.viewCatergoriesTree = new ViewCategoriesTree(options);

            // subscribe on events
            this.listenTo(this.viewProductsList.collection, 'reset', this.render);

            this.viewCatergoriesTree.on('categoryTree:changed:category', _.debounce(function (activeCategory) {
                this.viewProductsList.collection.setCustomQueryField("CategoryID", activeCategory.id);
                this.viewProductsList.collection.fetch({reset: true});
            }, 200), this);
        },
        render: function () {
            // TODO:
            // add expired and todays products
            // permanent layout and some elements
            if (this.$el.is(':empty')) {
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
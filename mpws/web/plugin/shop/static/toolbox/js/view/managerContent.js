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
            // debugger;
            this.viewProductsList = new ViewListProducts(options);
            this.viewOriginsList = new ViewListOrigins(options);
            this.viewCatergoriesTree = new ViewCategoriesTree(options);

            // subscribe on events
            this.listenTo(this.viewProductsList.collection, 'reset', this.render);

            this.viewCatergoriesTree.on('categoryTree:changed:category', _.debounce(function (activeCategoryID) {
                var self = this;
                // self.viewProductsList.trigger('categoryTree:changed:category');
                // setTimeout(function() {
                    self.viewProductsList.collection.setCustomQueryField("CategoryID", activeCategoryID);
                    // temporary solution
                    self.viewProductsList.collection.fetch({reset: true});
                // }, 5000);
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
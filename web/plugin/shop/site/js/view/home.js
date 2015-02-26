define("plugin/shop/site/js/view/home", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/home',
    'plugin/shop/site/js/view/listProductLatest',
    'plugin/shop/site/js/view/categoryNavigation'
], function (Backbone, Utils, tpl, ListProductLatest, CategoryNavigation) {

    var PageHome = Backbone.View.extend({
        className: 'shop-page-home',
        template: tpl,
        listProductLatest: new ListProductLatest(),
        categoryNav: new CategoryNavigation(),
        initialize: function () {
            // Site.placeholders.shop.productListOverview.html(listProductLatest.el);
            // debugger;
            this.listProductLatest.collection.fetch({
                reset: true
            });
            this.categoryNav.model.fetch({
                reset: true
            });
        },
        render: function() {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            if (this.$('.shop-products-latest').is(':empty')) {
                this.$('.shop-products-latest').html(this.listProductLatest.$el);
            }
            if (this.$('.shop-category-nav').is(':empty')) {
                this.$('.shop-category-nav').html(this.categoryNav.$el);
            }
            return this;
        }
    });

    return PageHome;

});
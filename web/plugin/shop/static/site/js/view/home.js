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
        initialize: function () {
            // Site.placeholders.shop.productListOverview.html(listProductLatest.el);
            // debugger;
        },
        render: function() {
            var listProductLatest = new ListProductLatest();
            var categoryNav = new CategoryNavigation();
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.$('.shop-products-latest').html(listProductLatest.el);
            this.$('.shop-category-nav').html(categoryNav.el);
            listProductLatest.collection.fetch({
                reset: true
            });
            categoryNav.model.fetch();
            return this;
        }
    });

    return PageHome;

});
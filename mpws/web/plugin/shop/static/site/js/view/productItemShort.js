define("plugin/shop/site/js/view/productItemShort", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productItemShort'
], function (Backbone, Utils, tpl) {

    var ProductItemShort = Backbone.View.extend({
        className: 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-3 col-lg-3',
        template: tpl,
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return ProductItemShort;

});
define("plugin/shop/site/js/view/productItemShort", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productItemShort'
], function (Sandbox, _, Backbone, Utils, tpl) {

    var ProductItemShort = Backbone.View.extend({
        className: 'shop-product-item shop-product-item-short col-xs-12 col-sm-6 col-md-3 col-lg-3',
        template: tpl,
        initialize: function () {

            _.bindAll(this, 'buttonWish');

            Sandbox.eventSubscribe('plugin:shop:wishlist:add', this.buttonWish(true));
            Sandbox.eventSubscribe('plugin:shop:wishlist:remove', this.buttonWish(false));
            Sandbox.eventSubscribe('plugin:shop:wishlist:clear', this.buttonWish(false));
        },
        buttonWish: function (state) {
            var self = this;
            return function (data) {
                if (data && data.id && data.id === self.model.id) {
                    self.$('.btn-add-to-wish').toggleClass('disabled', state);
                }
            }
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return ProductItemShort;

});
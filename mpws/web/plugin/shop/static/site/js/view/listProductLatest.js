define("plugin/shop/site/js/view/listProductLatest", [
    'default/js/lib/backbone',
    'plugin/shop/site/js/collection/listProductLatest',
    'plugin/shop/site/js/view/productItemShort'
], function (Backbone, CollListProductLatest, ProductItemShort) {

    // debugger;
    var ListProductLatest = Backbone.View.extend({
        className: 'shop-product-list shop-product-list-latest clearfix',
        collection: new CollListProductLatest(),
        initialize: function () {
            // debugger;
            this.collection.on('reset', this.render, this);
        },
        render: function () {
            // debugger;
            var self = this;
            this.collection.each(function(model){
                var productView = new ProductItemShort({model: model});
                self.$el.append(productView.render().el);
            });
            return this;
        }
    });

    return ListProductLatest;

});
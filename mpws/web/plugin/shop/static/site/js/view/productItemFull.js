define("plugin/shop/site/js/view/productItemFull", [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/site/js/view/productItemShort',
    'plugin/shop/site/js/model/product',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productItemFull',
    /* enhanced ui */
    'default/js/lib/bootstrap-magnify',
    'default/js/lib/lightbox',
    'default/js/lib/jquery.sparkline'
], function (Backbone, _, ViewProductItemShort, ModelProduct, Utils, tpl) {

    var ProductItemFull = ViewProductItemShort.extend({
        className: 'shop-product-item shop-product-item-full',
        template: tpl,
        initialize: function (options) {
            // debugger;
            // _.bindAll(this, 'buttonWish', 'buttonCompare');
            ViewProductItemShort.prototype.initialize.call(this);
            this.model = new ModelProduct({
                id: options.productID
            });
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            // show lense over product
            this.$('.shop-product-image-main img').magnify();
            // show price chart (powered by http://omnipotent.net/jquery.sparkline)
            // debugger;
            var _prices = (this.model.get('Prices') || []).filter(function(p) { return parseFloat(p); });
            if (_prices.length) {
                this.$("#sparkline").sparkline(_prices, {
                    type: 'bar',
                    width: '150px',
                    height: '15px',
                    lineColor: '#cf7400',
                    fillColor: false,
                    drawNormalOnTop: true
                });
            }
            return this;
        }
    });

    return ProductItemFull;
});
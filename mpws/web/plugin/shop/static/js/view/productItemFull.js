define("plugin/shop/js/view/productItemFull", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/model/productItemFull',
    'default/js/plugin/hbs!plugin/shop/hbs/productItemFull',
    /* enhanced ui */
    'default/js/lib/bootstrap-magnify',
    'default/js/lib/lightbox',
    'default/js/lib/jquery.sparkline'
], function (Sandbox, MView, ProductItemFull, tpl) {

    var ProductItemFull = MView.extend({
        tagName: 'div',
        className: 'shop-product-item shop-product-item-full',
        model: new ProductItemFull(),
        template: tpl,
        initialize: function () {
            this.on('mview:renderComplete', function () {
                // show lense over product
                this.$('.shop-product-image-main img').magnify();
                // show price chart (powered by http://omnipotent.net/jquery.sparkline)
                var _prices = this.model.get('Prices').filter(function(p) { return parseFloat(p); });
                if (_prices.length)
                    this.$("#sparkline").sparkline(_prices, {
                        type: 'bar',
                        width: '150px',
                        height: '15px',
                        lineColor: '#cf7400',
                        fillColor: false,
                        drawNormalOnTop: true
                    });
            }, this);
        },
    });

    return ProductItemFull;

});


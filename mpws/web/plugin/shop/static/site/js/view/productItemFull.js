define("plugin/shop/site/js/view/productItemFull", [
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/productItemFull',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/productItemFull',
    /* enhanced ui */
    'default/js/lib/bootstrap-magnify',
    'default/js/lib/lightbox',
    'default/js/lib/jquery.sparkline'
], function (Backbone, Model, Utils, tpl) {

    var ProductItemFull = Backbone.View.extend({
        className: 'shop-product-item shop-product-item-full',
        template: tpl,
        initialize: function () {
            this.model = new Model({
                id: this.options.id
            });
        },
        render: function () {
            var self = this;
            this.model.fetch({
                success: function() {
                    self.$el.html(self.template(Utils.getHBSTemplateData(self)));
                    // show lense over product
                    self.$('.shop-product-image-main img').magnify();
                    // show price chart (powered by http://omnipotent.net/jquery.sparkline)
                    // debugger;
                    var _prices = (self.model.get('Prices') || []).filter(function(p) { return parseFloat(p); });
                    if (_prices.length) {
                        self.$("#sparkline").sparkline(_prices, {
                            type: 'bar',
                            width: '150px',
                            height: '15px',
                            lineColor: '#cf7400',
                            fillColor: false,
                            drawNormalOnTop: true
                        });
                    }
                }
            });
            return this;
        }
    });

    return ProductItemFull;
});
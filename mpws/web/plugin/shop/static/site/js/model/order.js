define('plugin/shop/site/js/model/order', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/site/js/collection/listProductCart',
    'plugin/shop/common/js/lib/utils'
], function (Sandbox, Backbone, _, ListProductCart, ShopUtils) {

    // debugger;
    var model = Backbone.Model.extend({
        idAttribute: "ID",
        shoppingCart: new ListProductCart(),
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'order'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe('plugin:shop:list_cart:changed', function(data) {
                // Sandbox.eventNotify('plugin:shop:order:changed', self);
                debugger;
                self.trigger('change');
            });
            this.shoppingCart.on('reset', function () {
                debugger;
            });
        },
        parse: function (data) {
            // debugger;
            if (data.account)
                this.set('account', data.account);
            if (data.info)
                this.set('info', data.info);
            this.shoppingCart.reset(_(data.items).map(function(item){ return item; }), {parse: true});
            // return ShopUtils.adjustProductItem(data);
        },
        getProductCount: function () {
            return this.shoppingCart.length;
        },
        toJSON: function () {
            // debugger;
            return {
                account: this.get('account'),
                info: this.get('info'),
                items: this.shoppingCart.toJSON()
            }
        }
    });

    order = new model();
    return order;

});
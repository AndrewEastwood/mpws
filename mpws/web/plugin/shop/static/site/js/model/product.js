define('plugin/shop/site/js/model/product', [
    'default/js/lib/backbone',
    'plugin/shop/common/js/lib/utils'
], function (Backbone, ShopUtils) {

    // debugger;
    return Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'product'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        parse: function (data) {
            return ShopUtils.adjustProductItem(data);
        }
    });

});
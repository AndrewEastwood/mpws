define('plugin/shop/site/js/model/product', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/common/js/lib/utils'
], function (Backbone, _, ShopUtils) {
    // debugger;
    return Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'products',
                id: this.id
            };
            return APP.getApiLink(_params);
        }
    });
});
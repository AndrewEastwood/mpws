define([
    'backbone',
    'underscore',
    'plugins/shop/common/js/lib/utils'
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
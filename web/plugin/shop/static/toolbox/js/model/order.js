define('plugin/shop/toolbox/js/model/order', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Order = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'orders'
            };
            if (!this.isNew()) {
                _params.id = this.id;
            }
            return APP.getApiLink(_params);
        }
    });

    return Order;
});
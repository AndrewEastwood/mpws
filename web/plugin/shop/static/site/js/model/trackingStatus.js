define('plugin/shop/site/js/model/trackingStatus', [
    'default/js/lib/backbone',
], function (Backbone) {

    var TrackingSystem = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'orders',
                hash: this.get('Hash')
            };
            return APP.getApiLink(_params);
        }
    });

    return TrackingSystem;

});
define([
    'backbone',
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
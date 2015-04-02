define([
    'backbone',
], function (Backbone) {

    var TrackingSystem = Backbone.Model.extend({
        idAttribute: 'ID',
        url: function () {
            return APP.getApiLink('shop', 'orders', this.get('Hash'))
            // var params =  {
            //     source: 'shop',
            //     fn: 'orders',
            //     hash: this.get('Hash')
            // };
            // return APP.getApiLink(params);
        }
    });

    return TrackingSystem;

});
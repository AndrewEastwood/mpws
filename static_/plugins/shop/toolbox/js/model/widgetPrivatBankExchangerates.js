define([
    'backbone'
], function (Backbone) {

    return Backbone.Model.extend({
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'exchangerates',
                type: 'privatbank'
            };
            return APP.getApiLink(_params);
        }
    });

});
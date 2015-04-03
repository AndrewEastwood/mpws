define([
    'backbone'
], function (Backbone) {

    return Backbone.Model.extend({
        url: function () {
            var _params = {
                type: 'privatbank'
            };
            return APP.getApiLink('shop', 'exchangerates', _params);
        }
    });

});
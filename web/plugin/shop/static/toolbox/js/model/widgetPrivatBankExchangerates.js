define('plugin/shop/toolbox/js/model/widgetPrivatBankExchangerates', [
    'default/js/lib/backbone'
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
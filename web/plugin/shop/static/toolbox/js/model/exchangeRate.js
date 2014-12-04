define('plugin/shop/toolbox/js/model/exchangeRate', [
    'default/js/lib/backbone'
], function (Backbone) {

    var ExchangeRate = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'exchangerates'
            };
            if (!this.isNew()) {
                _params.id = this.id;
            }
            return APP.getApiLink(_params);
        }
    });

    return ExchangeRate;
});
define('plugin/shop/toolbox/js/model/promo', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Promo = Backbone.Model.extend({
        idAttribute: "ID",
        urlRoot: function () {
            var _params = {
                source: 'shop',
                fn: 'promos'
            };
            if (!this.isNew()) {
                _params.id = this.id;
            }
            return APP.getApiLink(_params);
        }
    });

    return Promo;

});
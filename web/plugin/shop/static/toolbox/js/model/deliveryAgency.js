define('plugin/shop/toolbox/js/model/deliveryAgency', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Origin = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'delivery'
            };
            if (!this.isNew()) {
                _params.id = this.id;
            }
            return APP.getApiLink(_params);
        }
    });

    return Origin;
});
define('plugin/system/toolbox/js/model/customer', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Customer = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'system',
                fn: 'customers'
            };
            if (!this.isNew()) {
                _params.id = this.id;
            }
            return APP.getApiLink(_params);
        }
    });

    return Customer;
});
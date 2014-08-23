define('plugin/shop/toolbox/js/model/product', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone'
], function (Sandbox, Backbone) {

    var Product = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'product'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        }
    });

    return Product;

});
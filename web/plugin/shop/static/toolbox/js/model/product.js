define('plugin/shop/toolbox/js/model/product', [
    'default/js/lib/backbone'
], function (Backbone) {

    var Product = Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'products'
            };
            if (!this.isNew()) {
                _params.id = this.id;
            }
            return APP.getApiLink(_params);
        },
        parse: function (data) {
            this.extras = {};
            this.extras.featureTypes = _(data._featuresTree).keys();
            this.extras.featureItems = _(data._featuresTree).reduce(function (memo, list) { var items = _(list).values(); return _(memo.concat(items)).uniq(); }, []);
            return data;
        }
    });

    return Product;

});
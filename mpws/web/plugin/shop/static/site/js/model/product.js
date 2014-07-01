define('plugin/shop/site/js/model/product', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/common/js/lib/utils'
], function (Backbone, _, ShopUtils) {

    // debugger;
    return Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params =  {
                source: 'shop',
                fn: 'product'
            };
            if (!this.isNew())
                _params.id = this.id;
            return APP.getApiLink(_params);
        },
        parse: function (data) {
            // debugger;
            return ShopUtils.adjustProductItem(data);
        },
        getFeatures: function (compatibilityList) {
            var _features = this.get('Features');
            var k = null;
            // debugger;
            if (compatibilityList instanceof Backbone.Collection) {
                compatibilityList.each(function(model){
                    var f = model.getFeatures();
                    for (k in f)
                        if (!_features[k])
                            _features[k] = null;
                })
            } else if (_.isObject(compatibilityList))
                for (k in compatibilityList)
                    if (!_features[k])
                        _features[k] = null;
            return _features;
        }
    });

});
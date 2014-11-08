define('plugin/shop/site/js/model/product', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/common/js/lib/utils'
], function (Backbone, _, ShopUtils) {

    // debugger;
    return Backbone.Model.extend({
        idAttribute: "ID",
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'products',
                id: this.id
            };
            // if (!this.isNew())
            //     _params.id = this.id;
            return APP.getApiLink(_params);
        },
        getFeatures: function (compatibilityList) {
            var _features = {};

            var k = null;
            // debugger;
            if (compatibilityList instanceof Backbone.Collection) {
                compatibilityList.each(function (model) {
                    var f = model.getFeatures();
                    for (k in f)
                        if (!_features[k]) {
                            _features[k] = f[k];
                            _features[k].active = false;
                        }
                })
            } else if (_.isObject(compatibilityList))
                for (k in compatibilityList)
                    if (!_features[k]) {
                        _features[k] = f[k];
                        _features[k].active = false;
                    }

                    // transform features
            _(this.get('Features')).each(function (fName, fKey) {
                _features[fName] = {
                    key: fKey,
                    name: fName,
                    active: true
                }
            });

            return _features;
        }
    });

});
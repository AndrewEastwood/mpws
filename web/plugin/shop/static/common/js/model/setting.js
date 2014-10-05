define('plugin/shop/common/js/model/setting', [
    'default/js/lib/backbone',
    'default/js/lib/underscore'
], function (Backbone, _) {

    return Backbone.Model.extend({
        url: function () {
            var _params = {
                source: 'shop',
                fn: 'setting'
            };
            return APP.getApiLink(_params);
        }
    });

});
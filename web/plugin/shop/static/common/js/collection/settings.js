define('plugin/shop/common/js/collection/settings', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/common/js/model/setting',
    'default/js/lib/moment/locale/uk'
], function (Backbone, _, ModelSetting) {

    return Backbone.Collection.extend({
        model: ModelSetting,
        url: function () {
            return APP.getApiLink({
                source: 'shop',
                fn: 'settings',
                type: this.sType || null
            });
        },
        getType: function () {
            return this.sType;
        },
        setType: function (type) {
            this.sType = type;
            return this;
        }
    });

});
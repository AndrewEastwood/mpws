define('plugin/shop/common/js/collection/settings', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/common/js/model/setting'
], function (Backbone, _, ModelSetting) {

    return Backbone.Collection.extend({
        model: ModelSetting,
        url: function () {
            return APP.getApiLink({
                source: 'shop',
                fn: 'settings',
                type: this.getType() || null
            });
        },
        getType: function () {
            return this.sType;
        },
        setType: function (type) {
            // debugger
            this.sType = type;
            return this;
        }
    });

});
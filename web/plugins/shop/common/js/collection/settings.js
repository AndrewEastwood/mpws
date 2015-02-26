define([
    'backbone',
    'underscore',
    'plugins/shop/common/js/model/setting'
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
            this.sType = type;
            return this;
        }
    });

});
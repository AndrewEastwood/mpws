define([
    'backbone',
    'underscore',
    'plugins/shop/common/js/model/setting'
], function (Backbone, _, ModelSetting) {

    return Backbone.Collection.extend({
        model: ModelSetting,
        url: function () {
            var options = {type: this.getType() || null};
            return APP.getApiLink('shop', 'settings', options);
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
define('plugin/shop/common/js/collection/settings', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/common/js/model/setting'
], function (Backbone, _, ModelSetting) {

    return Backbone.Collection.extend({
        queryParams: {},
        model: ModelSetting,
        url: function () {
            debugger;
            var _params = _.extend({
                source: 'shop',
                fn: 'settings'
            }, this.queryParams);

            return APP.getApiLink(_params);
        },

        setCustomQueryField: function (field, value) {
            this.queryParams['_f' + field] = value;
        },

        getCustomQueryField: function (field) {
            return this.queryParams["_f" + field];
        },

        setCustomQueryParam: function (param, value) {
            this.queryParams['_p' + param] = value;
        },

        getCustomQueryParam: function (param) {
            return this.queryParams["_p" + param];
        }
    });

});
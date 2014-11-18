define('plugin/shop/common/js/collection/settings', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'plugin/shop/common/js/model/setting'
], function (Backbone, _, ModelSetting) {

    return Backbone.Collection.extend({
        model: ModelSetting,
        url: function () {
            var _params = _.extend({
                source: 'shop',
                fn: 'settings'
            }, this.queryParams);
            return APP.getApiLink(_params);
        },

        initialize: function () {
            this.queryParams = {};
        },

        parse: function (data) {
            return data.items;
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
        },

        getPropertyByName: function (name) {
            return this.findWhere('Property', name);
        },

        toSettings: function () {
            var settings = {};
            this.each(function (model) {
                settings[model.get('Property')] = model.toJSON();
            });
            return settings;
        }
    });

});
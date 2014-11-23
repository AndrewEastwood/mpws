define('plugin/shop/common/js/collection/settings', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'default/js/lib/moment/moment',
    'plugin/shop/common/js/model/setting'
], function (Backbone, _, moment, ModelSetting) {

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
            var uid = null;
                property = null,
                openHoursData = null,
                settings = {},
                addresses = {},
                openHoursReg = /.*OpenHoursOn(.*)/;
            this.each(function (model) {
                property = model.get('Property');
                settings[property] = model.toJSON();
                // get addresses map
                if (model.isAddress()) {
                    uid = model.getAddressUID();
                    addresses[uid] = addresses[uid] || {
                        uid: uid,
                        OpenHoursDaysMap: {},
                        OpenHoursToday: null
                    };
                    addresses[uid][model.getAddressFieldName()] = model.toJSON();
                    openHoursData = property.match(openHoursReg);
                    if (openHoursData && openHoursData.length === 2) {
                        // set day open hours value
                        addresses[uid].OpenHoursDaysMap[openHoursData[1]] = {
                            day: moment(openHoursData[1], 'ddd').format('dddd'),
                            dayShort: openHoursData[1],
                            hours: model.get('Value')
                        };
                    }
                    // get todays open hours
                    if (!addresses[uid].OpenHoursToday) {
                        addresses[uid].OpenHoursToday = addresses[uid].OpenHoursDaysMap[moment().format('ddd')];
                    }
                }
            });
            settings.addresses = addresses
            settings.addressCount = Object.getOwnPropertyNames(addresses).length;
            return settings;
        }
    });

});
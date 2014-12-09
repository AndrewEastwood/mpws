define('plugin/shop/common/js/collection/settings', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'default/js/lib/moment/moment',
    'plugin/shop/common/js/model/setting',
    'default/js/lib/moment/locale/uk'
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
            this.availableConversions = data.availableConversions;
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
            var that = this,
                uid = null;
                property = null,
                openHoursData = null,
                settings = {},
                addresses = {},
                currencyList = {},
                openHoursReg = /.*OpenHoursOn(.*)/,
                contactReg = /.*_(\w+)_contact.*/;
            this.each(function (model) {
                property = model.get('Property');
                settings[property] = model.toJSON();
                // get addresses map
                if (model.isAddress()) {
                    uid = model.getAddressUID();
                    addresses[uid] = addresses[uid] || {
                        uid: uid,
                        OpenHoursDaysMap: {},
                        Contacts: [],
                        OpenHoursToday: null
                    };
                    addresses[uid][model.getAddressFieldName()] = model.toJSON();
                    openHoursData = property.match(openHoursReg);
                    contactData = property.match(contactReg);
                    if (openHoursData && openHoursData.length === 2) {
                        // set day open hours value
                        addresses[uid].OpenHoursDaysMap[openHoursData[1]] = {
                            day: moment(openHoursData[1], 'ddd', 'en').locale('uk').format('dddd'),
                            dayShort: moment(openHoursData[1], 'ddd', 'en').locale('uk').format('ddd'),
                            hours: model.get('Value')
                        };
                    }
                    if (contactData && contactData.length === 2) {
                        addresses[uid].Contacts.push({
                            type: contactData[1],
                            label: model.get('Label'),
                            contact: model.get('Value')
                        });
                    }
                    // get todays open hours
                    if (!addresses[uid].OpenHoursToday) {
                        addresses[uid].OpenHoursToday = addresses[uid].OpenHoursDaysMap[moment().locale('en').format('ddd')];
                    }
                }
                if (_(that.availableConversions).indexOf(model.get('Property')) >= 0) {
                    currencyList[model.get('Property')] = {
                        name: model.get('Property'),
                        text: model.get('Label'),
                        showBeforeValue: model.get('Value') === "1"
                    };
                }
            });
            settings.currencyList = currencyList;
            settings.addresses = addresses
            settings.addressCount = Object.getOwnPropertyNames(addresses).length;
            return settings;
        }
    });

});
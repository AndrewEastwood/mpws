define('plugin/shop/common/js/model/setting', [
    'default/js/lib/backbone',
    'default/js/lib/underscore',
    'default/js/lib/moment/moment',
    'default/js/lib/moment/locale/uk'
], function (Backbone, _, moment) {

    var days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    return Backbone.Model.extend({
        idAttribute: 'ID',
        url: function () {
            return APP.getApiLink({
                source: 'shop',
                fn: 'settings',
                type: this.getType() || null
            });
        },
        getType: function () {
            var type = null;
            if (this.collection) {
                type = this.collection.getType();
            }
            return type || this.sType;
        },
        setType: function (type) {
            this.sType = type;
            return this;
        },
        isAddress: function () {
            return this.getType() === 'ADDRESS';
        },
        isCurrency: function () {
            return this.getType() === 'EXCHANGERATES';
        },
        parse: function (data) {
            if (this.isAddress() || data.ADDRESS) {
                var addrData = data.ADDRESS || [data];
                _(addrData).each(function (addrItem) {
                    addrItem.OpenHoursToday = addrItem['Hours' + moment().locale('en').format('dddd')];
                    addrItem.OpenHoursMap = [];
                    addrItem.PhonesMap = [];
                    addrItem.ActivePhones = 0;
                    _(days).each(function (dayName) {
                        addrItem.OpenHoursMap.push({
                            day: moment(dayName, 'ddd', 'en').locale('uk').format('dddd'),
                            dayShort: moment(dayName, 'ddd', 'en').locale('uk').format('ddd'),
                            hours: addrItem['Hours' + dayName],
                            isToday: moment().locale('en').format('dddd') === dayName
                        });
                    });
                    for (var i = 1; i <= 5; i++) {
                        if (addrItem['Phone' + i + 'Label'] && addrItem['Phone' + i + 'Value']) {
                            addrItem.ActivePhones++;
                            addrItem.PhonesMap.push({
                                label: addrItem['Phone' + i + 'Label'],
                                value: addrItem['Phone' + i + 'Value']
                            });
                        }
                    }
                });
            }

            if (data.EXCHANAGERATESDISPLAY) {
                var currencyList = {};
                data.CUSTOM = {currencyList: currencyList};
                _(data.EXCHANAGERATESDISPLAY).each(function (exRateItem) {
                    currencyList[exRateItem.CurrencyName] = exRateItem;
                    currencyList[exRateItem.CurrencyName].fromBaseToThis = data.EXCHANAGERATES.availableConversions[exRateItem.CurrencyName];
                    currencyList[exRateItem.CurrencyName].fromThisToBase = data.EXCHANAGERATES.availableMutipliers[exRateItem.CurrencyName];

                });
            }
            return data;
        },
        toSettings: function () {
            var settings = this.toJSON();

            if (this.getType()) {
                return settings;
            }
            // var that = this,
            //     uid = null;
            //     property = null,
            //     openHoursData = null,
            //     settings = {},
            //     addresses = {},
            //     currencyList = {},
            //     openHoursReg = /.*OpenHoursOn(.*)/,
            //     contactReg = /.*_(\w+)_contact.*/;
            // this.each(function (model) {
            //     property = model.get('Property');
            //     settings[property] = model.toJSON();
            //     // get addresses map
            //     if (model.isAddress()) {
            //         uid = model.getAddressUID();
            //         addresses[uid] = addresses[uid] || {
            //             uid: uid,
            //             OpenHoursDaysMap: {},
            //             Contacts: [],
            //             OpenHoursToday: null
            //         };
            //         addresses[uid][model.getAddressFieldName()] = model.toJSON();
            //         openHoursData = property.match(openHoursReg);
            //         contactData = property.match(contactReg);
            //         if (openHoursData && openHoursData.length === 2) {
            //             // set day open hours value
            //             addresses[uid].OpenHoursDaysMap[openHoursData[1]] = {
            //                 day: moment(openHoursData[1], 'ddd', 'en').locale('uk').format('dddd'),
            //                 dayShort: moment(openHoursData[1], 'ddd', 'en').locale('uk').format('ddd'),
            //                 hours: model.get('Value')
            //             };
            //         }
            //         if (contactData && contactData.length === 2) {
            //             addresses[uid].Contacts.push({
            //                 type: contactData[1],
            //                 label: model.get('Label'),
            //                 contact: model.get('Value')
            //             });
            //         }
            //         // get todays open hours
            //         if (!addresses[uid].OpenHoursToday) {
            //             addresses[uid].OpenHoursToday = addresses[uid].OpenHoursDaysMap[moment().locale('en').format('ddd')];
            //         }
            //     }
            //     if (that.availableConversions[model.get('Property')]) {
            //         currencyList[model.get('Property')] = {
            //             name: model.get('Property'),
            //             text: model.get('Label'),
            //             showBeforeValue: model.get('Value') === "1",
            //             fromBaseToThis: that.availableConversions[model.get('Property')],
            //             fromThisToBase: that.availableMutipliers[model.get('Property')]
            //         };
            //     }
            // });
            // settings.currencyList = currencyList;
            // settings.addresses = addresses
            // settings.addressCount = Object.getOwnPropertyNames(addresses).length;
            // if (settings.DBPriceCurrencyType) {
            //     settings.DBPriceCurrencyType._display = currencyList[settings.DBPriceCurrencyType.Value];
            // }
            // if (settings.ShowSiteCurrencySelector) {
            //     settings.ShowSiteCurrencySelector = settings.ShowSiteCurrencySelector._isActive;
            // }
            return settings;
        }
    });

});
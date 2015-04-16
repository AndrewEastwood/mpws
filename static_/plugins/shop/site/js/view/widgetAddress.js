define([
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'cachejs',
    /* collection */
    'plugins/shop/common/js/collection/settings',
    /* template */
    'text!plugins/shop/site/hbs/widgetAddress.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation'
], function (_, Backbone, Handlebars, Utils, Cache, CollectionSettings, tpl, lang) {

    var WidgetAddress = Backbone.View.extend({
        className: 'address-widget',
        id: 'address-widget-ID',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'click .address-option': 'changeUserAddress'
        },
        initialize: function() {
            this.collection = new CollectionSettings();
            this.collection.setType('ADDRESS');
            this.listenTo(this.collection, 'sync', this.render);
        },
        render: function () {
            if (this.collection.isEmpty()) {
                return this;
            }
            var tplData = null,
                activeAddressID = WidgetAddress.getActiveAddressID(),
                activeAddress = this.collection.get(activeAddressID);
            this.collection.each(function (model) {
                model.set('isActive', false)
            });
            activeAddress.set('isActive', true);
            tplData = Utils.getHBSTemplateData(this);
            tplData.extras = {
                activeAddress: activeAddress,
                addressCount: this.collection.length
            };
            this.$el.toggleClass('hidden', this.collection.length === 0);
            this.$el.html(this.template(tplData));
            return this;
        },
        changeUserAddress: function (event) {
            Cache.set('userAddrID', $(event.target).parents('li').data('ref'));
            this.render();
        },
        getInfoPayment: function () {
            var addr = WidgetAddress.getActiveAddress();
            return addr && addr.InfoPayment || '';
        },
        getInfoShipping: function () {
            var addr = WidgetAddress.getActiveAddress();
            return addr && addr.InfoShipping || '';
        },
        getInfoWarranty: function () {
            var addr = WidgetAddress.getActiveAddress();
            return addr && addr.InfoWarranty || '';
        },
        getInfoAddressLine: function (asHtml) {
            var addr = WidgetAddress.getActiveAddress(),
                addrLine = $('<span>');
            if (addr) {
                addrLine.append($('<span>').text((addr.AddressLine1 || addr.AddressLine2 || addr.AddressLine3) + ','));
                addrLine.append($('<span>').text(addr.City));
                addrLine.append('&nbsp;');
                addrLine.append($('<span>').text(addr.PhoneHotline || addr.Phone1Value
                    || addr.Phone2Value || addr.Phone3Value || addr.Phone4Value || addr.Phone5Value));
                addrLine.append('&nbsp;');
                addrLine.append($('<a>').attr('href', 'mailto:' + addr.EmailSupport).text(addr.EmailSupport));
            }
            if (asHtml) {
                return addrLine;
            }
            return addrLine.text();
        },
        getCopyright: function () {
            var addr = WidgetAddress.getActiveAddress(),
                cp = '&copy; ' + new Date().getFullYear();
            if (addr) {
                cp += ' ,' + addr.ShopName;
            }
            return cp;
        },
        getSocialLinks: function () {
            var addr = WidgetAddress.getActiveAddress(),
                links = {};
            if (addr) {
                _(addr).each(function (v, k) {
                    if (/^Social/.test(k)) {
                        links[k.match(/^Social(.*)/)[1].toLowerCase()] = v;
                    }
                });
            }
            return links;
        }
    }, {
        setActiveAddressID: function (activeID) {
           Cache.set('userAddrID', WidgetAddress.getActiveAddressID(activeID));
        },
        getActiveAddressID: function (activeID) {
            var userAddrID = activeID || Cache.get('userAddrID') || null,
                addr = _(WidgetAddress.plugin.settings.ADDRESS).findWhere({ID: userAddrID});
            if (_.isNull(userAddrID) || !addr) {
                addr = _(WidgetAddress.plugin.settings.ADDRESS).first();
                if (addr && addr.ID) {
                    WidgetAddress.setActiveAddressID(addr.ID);
                    userAddrID = addr.ID;
                } else {
                    userAddrID = null;
                }
            }
            return userAddrID;
        },
        getActiveAddress: function () {
            var userAddrID = WidgetAddress.getActiveAddressID();
            return _(WidgetAddress.plugin.settings.ADDRESS).findWhere({ID: userAddrID}) || {};
        }
    });

    return WidgetAddress;

});
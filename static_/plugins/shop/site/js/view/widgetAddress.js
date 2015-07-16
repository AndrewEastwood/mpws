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
            _.bindAll(this, 'updateElements');
            this.updateElements();
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
        updateElements: function () {
            // elements
            var addr = WidgetAddress.getActiveAddress();
            if (!this.$copy) {
                this.$copy = $('<span/>').addClass('shop-address-copy');
            }
            if (!this.$addressLine) {
                this.$addressLine = $('<span/>').addClass('shop-address-line');
            }
            if (!this.$infoWarranty) {
                this.$infoWarranty = $('<span/>').addClass('shop-info-warranty');
            }
            if (!this.$infoPayment) {
                this.$infoPayment = $('<span/>').addClass('shop-info-payment');
            }
            if (!this.$infoShipping) {
                this.$infoShipping = $('<span/>').addClass('shop-info-shipping');
            }
            if (!this.$linkFacebook) {
                this.$linkFacebook = $('<a/>').addClass('shop-link-social shop-link-social-facebook')
                    .html($('<i/>').addClass('fa fa-fw fa-facebook'));
            }
            if (!this.$linkGooglePlus) {
                this.$linkGooglePlus = $('<a/>').addClass('shop-link-social shop-link-social-googleplus')
                    .html($('<i/>').addClass('fa fa-fw fa-google-plus'));
            }
            if (!this.$linkLinkedIn) {
                this.$linkLinkedIn = $('<a/>').addClass('shop-link-social shop-link-social-linkedin')
                    .html($('<i/>').addClass('fa fa-fw fa-linkedin'));
            }
            if (!this.$linkTwitter) {
                this.$linkTwitter = $('<a/>').addClass('shop-link-social shop-link-social-twitter')
                    .html($('<i/>').addClass('fa fa-fw fa-twitter'));
            }
            if (addr) {
                this.$infoPayment.html(addr.InfoPayment);
                this.$infoShipping.html(addr.InfoShipping);
                this.$infoWarranty.html(addr.InfoWarranty);
                this.$addressLine.empty();
                var addrItems = [];
                if (addr.AddressLine1 || addr.AddressLine2 || addr.AddressLine3) {
                    addrItems.push($('<span>').addClass('mpws-shop-addr-el mpws-shop-addr-el-text')
                        .text((addr.AddressLine1 || addr.AddressLine2 || addr.AddressLine3)));
                }
                if (addr.City) {
                    addrItems.push($('<span>').addClass('mpws-shop-addr-el mpws-shop-addr-el-text')
                        .text(addr.City));
                }
                if (addr.PhoneHotline || addr.Phone1Value || addr.Phone2Value || addr.Phone3Value || addr.Phone4Value || addr.Phone5Value) {
                    addrItems.push($('<span>').addClass('mpws-shop-addr-el mpws-shop-addr-el-text')
                        .text(addr.PhoneHotline || addr.Phone1Value || addr.Phone2Value || addr.Phone3Value || addr.Phone4Value || addr.Phone5Value));
                }
                if (addr.EmailSupport) {
                    addrItems.push($('<a>').addClass('mpws-shop-addr-el mpws-shop-addr-email')
                        .attr('href', 'mailto:' + addr.EmailSupport).text(addr.EmailSupport));
                }
                this.$addressLine.html(addrItems);
                this.$addressLine.find('.mpws-shop-addr-el-text').after($('<span>').html(',&nbsp;'));
                this.$copy.html('&copy; ' + new Date().getFullYear() + ' ,' + addr.ShopName);
                this.$linkFacebook.attr('href', addr.SocialFacebook || 'javascript://');
                this.$linkGooglePlus.attr('href', addr.SocialGooglePlus || 'javascript://');
                this.$linkLinkedIn.attr('href', addr.SocialLinkedIn || 'javascript://');
                this.$linkTwitter.attr('href', addr.SocialTwitter || 'javascript://');
            }
        },
        changeUserAddress: function (event) {
            Cache.set('userAddrID', $(event.target).parents('li').data('ref'));
            this.render();
            this.updateElements();
        },
    }, {
        setActiveAddressID: function (activeID) {
            Cache.set('userAddrID', WidgetAddress.getActiveAddressID(activeID));
            Backbone.trigger('changed:plugin-shop-address', WidgetAddress.getActiveAddressID(activeID));
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
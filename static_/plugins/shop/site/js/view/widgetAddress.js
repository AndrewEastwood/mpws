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
            // this.collection.setCustomQueryField('Type', 'ADDRESS');
            // this.collection.setCustomQueryField('Status', 'REMOVED:!=');
            this.listenTo(this.collection, 'sync', this.render);
        },
        render: function () {
            if (this.collection.isEmpty()) {
                return this;
            }
            var tplData = null,
                userAddrID = Cache.get('userAddrID') || null,
                activeAddress = this.collection.get(userAddrID);
            this.collection.each(function (model) {
                model.set('isActive', false)
            });
            // debugger
            // get first address when we don;t have user's choice
            if (!activeAddress) {
                activeAddress = this.collection.at(0);
                Cache.set('userAddrID', activeAddress.id);
            }
            activeAddress.set('isActive', true);
            activeAddress = activeAddress.toJSON() || {};
            tplData = Utils.getHBSTemplateData(this);
            tplData.extras = {
                activeAddress: activeAddress,
                addressCount: this.collection.length
            };
            this.$el.toggleClass('hidden', this.collection.length === 0);
            this.$el.html(this.template(tplData));
            // set active address
            // debugger
            APP.instances.shop.setActiveAddress(activeAddress);
            Backbone.trigger('changed:plugin-shop-address', activeAddress);
            return this;
        },
        changeUserAddress: function (event) {
            // debugger
            Cache.set('userAddrID', $(event.target).parents('li').data('ref'));
            this.render();
            // var addressUID = $(event.target).parents('li').data('ref'),
            //     activeAddress = this.collection.get(addressUID);
            // this.$('.address-item').addClass('hidden');
            // this.$('#' + addressUID).removeClass('hidden');
            // Cache.set('userAddrID', addressUID);
            // if (activeAddress) {
            //     activeAddress = activeAddress.toJSON();
            //     APP.instances.shop.settings._activeAddress = activeAddress;
            //     this.$('.address-switcher .shoptitle').text(activeAddress.ShopName);
            // }
        }
    });

    return WidgetAddress;

});
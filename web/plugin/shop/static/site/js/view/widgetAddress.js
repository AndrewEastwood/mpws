define("plugin/shop/site/js/view/widgetAddress", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/lib/cache',
    /* collection */
    'plugin/shop/common/js/collection/settings',
    /* template */
    'default/js/plugin/hbs!plugin/shop/site/hbs/widgetAddress',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation'
], function (_, Backbone, Utils, Cache, CollectionSettings, tpl, lang) {

    var WidgetAddress = Backbone.View.extend({
        className: 'address-widget',
        id: 'address-widget-ID',
        template: tpl,
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
            // get first address when we don;t have user's choice
            if (activeAddress === null) {
                Cache.set('userAddrID', firstAddressUID);
                activeAddress = this.collection.at(0);
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
            APP.instances.shop.settings._activeAddress = activeAddress;
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
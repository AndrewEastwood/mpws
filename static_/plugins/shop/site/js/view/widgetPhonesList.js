define([
    'underscore',
    'backbone',
    'handlebars',
    'utils',
    'cachejs',
    'plugins/shop/site/js/view/widgetAddress',
    /* template */
    'text!plugins/shop/site/hbs/widgetPhonest.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation'
], function (_, Backbone, Handlebars, Utils, Cache, ViewWidgetAddresses, tpl, lang) {

    var WidgetPhonesList = Backbone.View.extend({
        tagName: 'ul',
        className: 'shop-phones-list',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        initialize: function() {
            _.bindAll(this, 'render');
            Backbone.on('changed:plugin-shop-address', this.render);
        },
        render: function () {
            var tplData = null,
                activeAddress = ViewWidgetAddresses.getActiveAddress();
            tplData = Utils.getHBSTemplateData(this);
            tplData.data = activeAddress;
            this.$el.html(this.template(tplData));
            return this;
        }
    });

    return WidgetPhonesList;

});
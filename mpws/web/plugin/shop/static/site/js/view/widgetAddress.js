define("plugin/shop/site/js/view/widgetAddress", [
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    /* model */
    'plugin/shop/site/js/model/address',
    /* template */
    'default/js/plugin/hbs!plugin/shop/site/hbs/widgetAddress',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation'
], function (_, Backbone, Utils, ModelAddress, tpl, lang) {

    var WidgetContacts = Backbone.View.extend({
        tagName: 'address',
        className: 'address-widget',
        id: 'address-widget-ID',
        template: tpl,
        lang: lang,
        initialize: function() {
            this.model = new ModelAddress();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            // debugger;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
        }
    });

    return WidgetContacts;

});
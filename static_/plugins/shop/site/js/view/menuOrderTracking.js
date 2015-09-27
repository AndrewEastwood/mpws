define([
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/shop/site/hbs/menuOrderTracking.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation'
], function (Backbone, Handlebars, Utils, tpl, lang) {

    var MenuOrderTracking = Backbone.View.extend({
        tagName: 'a',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.$el.attr({
                href: Handlebars.helpers.bb_link(APP.instances.shop.urls.shopTracking, {asRoot: true, _id: ''})
            });
            return this;
        }
    });

    return MenuOrderTracking;

});
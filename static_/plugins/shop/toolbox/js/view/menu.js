define([
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/shop/toolbox/hbs/menu.hbs',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, Handlebars, Utils, tpl, lang) {

    var Menu = Backbone.View.extend({
        // tagName: 'li',
        // id: 'shop-menu-ID',
        // attributes: {
        //     rel: "menu"
        // },
        lang: lang,
        template: Handlebars.compile(tpl), // check
        initialize: function () {
            var self = this;
            APP.Sandbox.eventSubscribe('global:auth:status:inactive', function () {
                self.off();
                self.remove();
            });
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            this.$el = $(this.$el.html());
            return this;
        }
    });

    return Menu;

});
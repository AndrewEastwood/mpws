define([
    'backbone',
    'utils',
    'hbs!plugins/shop/toolbox/hbs/menu',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Backbone, Utils, tpl, lang) {

    var Menu = Backbone.View.extend({
        // tagName: 'li',
        // id: 'shop-menu-ID',
        // attributes: {
        //     rel: "menu"
        // },
        lang: lang,
        template: tpl,
        initialize: function () {
            var self = this;
            APP.Sandbox.eventSubscribe('global:auth:status:inactive', function () {
                self.off();
                self.remove();
            });
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$el = $(this.$el.html());
            return this;
        }
    });

    return Menu;

});
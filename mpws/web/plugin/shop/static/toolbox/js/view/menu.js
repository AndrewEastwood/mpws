define("plugin/shop/toolbox/js/view/menu", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, tpl, lang) {

    var Menu = Backbone.View.extend({
        tagName: 'li',
        id: 'shop-menu-ID',
        attributes: {
            rel: "menu"
        },
        lang: lang,
        template: tpl,
        initialize: function () {
            var self = this;
            Sandbox.eventSubscribe('global:auth:status:inactive', function () {
                self.off();
                self.remove();
            });
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
        }
    });

    return Menu;

});
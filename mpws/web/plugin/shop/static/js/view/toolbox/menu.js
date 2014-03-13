define("plugin/shop/js/view/toolbox/menu", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
], function (Sandbox, Site, MView, tpl, lang) {

    var menu = new (MView.extend({
        lang: lang,
        template: tpl,
        initialize: function () {
            var self = this;
            this.on('mview:renderComplete', function () {
                self.setActive();
            });
            Sandbox.eventSubscribe('plugin:shop:toolbox:menu:refresh', function () {
                self.setActive();
            });
        },
        setActive: function () {
            this.$('a.list-group-item').removeClass('active');
            this.$('a.list-group-item[href*="' + Backbone.history.fragment + '"]').addClass('active');
            this.$('a.list-group-item[href*="' + Backbone.history.fragment + '"]').parents('.panel-collapse').addClass('in');
        }
    }))();
    menu.render();

    // debugger;
    Sandbox.eventSubscribe('global:loader:complete', function () {
        // debugger;
        Sandbox.eventNotify('site:content:render', {
            name: 'CommmonToolboxMenu',
            el: menu.$el,
            append: true
        });
    });

});
define("plugin/account/js/view/toolboxMenu", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/hbs/toolboxMenu',
    /* lang */
    'default/js/plugin/i18n!plugin/account/nls/account',
], function (Sandbox, Site, MView, tpl, lang) {

    var toolboxMenuAccount = new (MView.extend({
        // tagName: 'li',
        lang: lang,
        template: tpl,
        // initialize: function () {
        //     var _self = this;
        //     Sandbox.eventSubscribe('shop:compare:info', function (data) {
        //         var _count = data && data.products && data.products.length || 0;
        //         if (_count)
        //             _self.$('.counter').text(_count);
        //         else
        //             _self.$('.counter').empty();
        //     });
        //     ModelProductsCompareInstance.getInfo();
        // }
    }))();
    toolboxMenuAccount.render();

    // return MenuCompare;
    // debugger;
    Sandbox.eventSubscribe('global:loader:complete', function () {
        // debugger;
        Site.placeholders.common.menuPlugin.append(toolboxMenuAccount.$el);
    });

});
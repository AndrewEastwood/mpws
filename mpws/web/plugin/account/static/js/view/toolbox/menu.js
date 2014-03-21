define("plugin/account/js/view/toolbox/menu", [
    'default/js/lib/sandbox',
    'customer/js/site',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/account/hbs/toolbox/menu',
    /* lang */
    'default/js/plugin/i18n!plugin/account/nls/site',
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
        Sandbox.eventNotify('global:content:render', {
            name: 'CommmonToolboxMenu',
            el: toolboxMenuAccount.$el,
            append: true
        });
    });

});
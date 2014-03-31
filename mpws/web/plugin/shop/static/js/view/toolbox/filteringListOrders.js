define("plugin/shop/js/view/toolbox/filteringListOrders", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    /* template */
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/filteringListOrders'
], function (Sandbox, MView, tpl) {

    var FilteringListOrders = MView.extend({
        template: tpl
    });

    return FilteringListOrders;

});
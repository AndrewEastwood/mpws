define("plugin/shop/js/view/toolbox/filteringListOrders", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    /* template */
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/filteringListOrders'
], function (Sandbox, MView, tpl) {

    var FilteringListOrders = MView.extend({
        template: tpl,
        events: {
            'click input': 'applyFiltering'
        },
        applyFiltering: function () {
            debugger;
            // var self = this;
            // Sandbox.eventSubscribe("plugin:shop:orderList:refresh", function () {
            //     self.render();
            // });
        }
    });

    return FilteringListOrders;

});
define("plugin/shop/toolbox/js/view/filteringListOrders", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/filteringListOrders'
], function (Sandbox, MView, tpl) {

    var FilteringListOrders = MView.extend({
        template: tpl,
        events: {
            'click input': 'applyFiltering'
        },
        applyFiltering: function () {
            // debugger;
            // var self = this;
            var _filterData = {
                status: []
            };

            this.$('input:checked').each(function(){
                _filterData.status.push($(this).val());
            });

            _filterData.status = _filterData.status.join(',');

            Sandbox.eventNotify("plugin:shop:orderList:filter", _filterData);
        }
    });

    return FilteringListOrders;

});
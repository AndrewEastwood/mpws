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
            // debugger;
            // var self = this;
            var _filterData = {
                status: [],
                demo: 123
            };

            this.$('input:checked').each(function(){
                _filterData.status.push($(this).val());
            });

            _filterData.status = _filterData.status.join(',')

            Sandbox.eventNotify("plugin:shop:orderList:filter", _filterData);
        }
    });

    return FilteringListOrders;

});
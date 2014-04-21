define("plugin/shop/js/view/trackingStatus", [
    'default/js/view/mView',
    'plugin/shop/js/model/trackingStatus',
    'default/js/plugin/hbs!plugin/shop/hbs/site/trackingStatus'
], function (MView, ModelTrackingStatus, tpl) {

    var TrackingStatus = MView.extend({
        className: 'row shop-tracking-status',
        id: 'shop-tracking-status-ID',
        template: tpl,
        events: {
            'click #shopGetOrderStatusID': 'getOrderStatus'
        },
        initialize: function () {
            this.model = new ModelTrackingStatus();
        },
        getOrderStatus: function (){
            this.fetchAndRender({
                orderHash: this.$('input#inputOrderTrackingID').val()
            });
        }
    });

    return TrackingStatus;

});
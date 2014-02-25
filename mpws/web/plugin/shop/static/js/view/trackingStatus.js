define("plugin/shop/js/view/trackingStatus", [
    'default/js/view/mView',
    'plugin/shop/js/model/trackingStatus',
    'default/js/plugin/hbs!plugin/shop/hbs/trackingStatus'
], function (MView, ModelTrackingStatus, tpl) {

    var TrackingStatus = MView.extend({
        className: 'row shop-tracking-status',
        id: 'shop-tracking-status-ID',
        model: new ModelTrackingStatus(),
        template: tpl,
        events: {
            'click #shopGetOrderStatusID': 'getOrderStatus'
        },
        initialize: function () {
            MView.prototype.initialize.call(this);
            this.on('mview:renderComplete', function () {
                
            }, this);
        },
        getOrderStatus: function (){
            this.fetchAndRender({
                orderHash: this.$('input#inputOrderTrackingID').val()
            });
        }
    });

    return TrackingStatus;

});
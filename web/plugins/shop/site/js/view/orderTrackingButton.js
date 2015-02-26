define([
    'backbone',
    'utils',
    'hbs!plugins/shop/site/hbs/orderTrackingButton'
], function (Backbone, Utils, tpl) {

    var TrackingButton = Backbone.View.extend({
        className: 'btn-group shop-order-tracking-button',
        id: 'shop-order-tracking-button-ID',
        template: tpl,
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
        }
    });

    return TrackingButton;

});
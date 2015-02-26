define("plugin/shop/site/js/view/orderTrackingButton", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/orderTrackingButton'
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
define([
    'backbone',
    'handlebars',
    'utils',
    'text!plugins/shop/site/hbs/orderTrackingButton.hbs'
], function (Backbone, Handlebars, Utils, tpl) {

    var TrackingButton = Backbone.View.extend({
        className: 'btn-group shop-order-tracking-button',
        id: 'shop-order-tracking-button-ID',
        template: Handlebars.compile(tpl), // check
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
        }
    });

    return TrackingButton;

});
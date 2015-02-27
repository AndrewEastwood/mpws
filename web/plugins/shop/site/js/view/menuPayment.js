define([
    'backbone',
    'utils',
    'bootstrap-dialog',
    'hbs!plugins/shop/site/hbs/menuPayment'
], function (Backbone, Utils, BootstrapDialog, tpl) {

    var MenuPayment = Backbone.View.extend({
        tagName: 'li',
        template: tpl,
        events: {
            'click': 'showPopup'
        },
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        },
        showPopup: function () {
            BootstrapDialog.show({
                draggable: false,
                cssClass: 'popup-shop-info popup-shop-payments',
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: APP.instances.shop.settings._activeAddress.InfoPayment
            });
        }
    });

    return MenuPayment;

});
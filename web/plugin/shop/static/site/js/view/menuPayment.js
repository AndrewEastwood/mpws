define("plugin/shop/site/js/view/menuPayment", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuPayment'
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
                message: APP.instances.shop.settings._activeAddress.Payment.Value
            });
        }
    });

    return MenuPayment;

});
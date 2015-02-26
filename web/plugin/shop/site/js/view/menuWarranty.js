define("plugin/shop/site/js/view/menuWarranty", [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/plugin/hbs!plugin/shop/site/hbs/menuWarranty'
], function (Backbone, Utils, BootstrapDialog, tpl) {

    var MenuWarranty = Backbone.View.extend({
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
                cssClass: 'popup-shop-info popup-shop-warranty',
                type: BootstrapDialog.TYPE_WARNING,
                title: $(event.target).html().trim(),
                message: APP.instances.shop.settings._activeAddress.InfoWarranty
            });
        }
    });

    return MenuWarranty;

});
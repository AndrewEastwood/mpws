define("plugin/shop/toolbox/js/view/popupOrder", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/model/popupOrder',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupOrder',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, MView, ModelOrderItem, ShopUtils, BootstrapDialog, tpl, lang) {

    var dlg = new BootstrapDialog({
        cssClass: 'shop-toolbox-origin-edit',
        // onhidden: function(dialogRef){
        //     dialogIsShown = false;
        // },
        buttons: [{
            label: lang.popup_order_button_Close,
            action: function (dialog) {
                dialogIsShown = false;
                dialog.close();
            }
        }]
    });
    var orderItemModel = new ModelOrderItem();
    var OrderItem = MView.extend({
        template: tpl,
        lang: lang,
        model: orderItemModel,
        initialize: function () {
            MView.prototype.initialize.call(this);
            var self = this;
            var dialogIsShown = false;
            debugger;
            this.on('mview:renderComplete', function () {
                var orderID = this.model.get('order').ID;
                // if (!dialogIsShown)
                // dialogIsShown = true;
                var source = self.$('#order-status-control-ID option').map(function(idx, option){
                    return {
                        value: $(option).attr('value'),
                        text: $(option).text()
                    }
                });
                var $controlOrderStatus = self.$('#order-status-ID');
                $controlOrderStatus.editable({
                    mode: 'inline',
                    name: "Status",
                    title: lang.popup_order_control_status,
                    type: "select",
                    value: this.model.get('order').Status,
                    pk: this.model.get('order').ID,
                    source: $.makeArray(source),
                });
                $controlOrderStatus.on('save', function(event, editData) {
                    var jqxhr = ShopUtils.updateOrderStatus(orderID, editData.newValue);
                    jqxhr.done(function(data){
                        self.model.set(self.model.parse.call(self.model, data));
                        self.render();
                    });
                });
                self.$('.helper').tooltip();
            });
            // order-status-ID
        }
    });

    return OrderItem;

});
define("plugin/shop/js/view/toolbox/orderEntry", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/js/model/toolbox/orderEntry',
    'default/js/lib/bootstrap-dialog',
    /* template */
    'default/js/plugin/hbs!plugin/shop/hbs/toolbox/orderEntry',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/toolbox',
    // "default/js/lib/select2/select2",
    'default/js/lib/bootstrap-editable'
], function (Sandbox, MView, ModelOrderEntry, BootstrapDialog, tpl, lang) {

    var OrderItem = MView.extend({
        // tagName: 'div',
        // className: 'shop-order-entry',
        template: tpl,
        lang: lang,
        model: new ModelOrderEntry(),
        initialize: function (orderID) {
            MView.prototype.initialize.call(this);
            var self = this;
            // this.updateUrl({
            //     orderID: orderID
            // });
            this.on('mview:renderComplete', function () {

                BootstrapDialog.show({
                    title: lang.orderEntry_Popup_title + data.oid,
                    message: self.$el,
                    cssClass: 'shop-toolbox-order-edit',
                    buttons: [{
                        label: lang.orderEntry_Popup_button_OK,
                        action: function (dialog) {
                            dialog.close();
                        }
                    }]
                });

                var source = self.$('#order-status-control-ID option').map(function(idx, option){
                    return {
                        value: $(option).attr('value'),
                        text: $(option).text()
                    }
                });
                // debugger;
                self.$('#order-status-ID').editable({
                    mode: 'inline',
                    name: "Status",
                    title: lang.orderEntry_Popup_control_status,
                    type: "select",
                    value: this.model.get('order').Status,
                    pk: this.model.get('order').ID,
                    url: self.model.getUrl({
                        action: "orderUpdate"
                    }),
                    source: $.makeArray(source),
                    success: function (data) {
                        // debugger;
                        self.model.set(self.model.parse.call(self.model, data));
                        self.render();
                        Sandbox.eventNotify('plugin:shop:orderList:refresh');
                    }
                });
                // self.$('select').select2();
                self.$('.helper').tooltip();
            });
            // order-status-ID
        }
    });

    return OrderItem;

});
define("plugin/shop/toolbox/js/view/popupOrigin", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/model/origin',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupOrigin',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, MView, ModelOriginItem, ShopUtils, Utils, BootstrapDialog, tpl, lang) {

    var OriginItem = MView.extend({
        template: tpl,
        lang: lang,
        initialize: function () {
            var self = this;
            // debugger;
            this.model = new ModelOriginItem();
            MView.prototype.initialize.call(this);
            var isEdit = !!this.options.originID;
            var dlg = new BootstrapDialog({
                cssClass: 'shop-toolbox-origin-edit',
                buttons: [{
                    id: "save",
                    label: lang.popup_origin_button_Save,
                    action: function (dialog) {
                        // debugger;
                        var data = self.getFormFields();
                        // dialogIsShown = false;
                        if (isEdit)
                            self.model.updateItem(self.options.originID, data);
                        else
                            self.model.createItem(data);
                        dialog.close();
                    }
                }]
            });

            if (isEdit)
                dlg.setTitle(lang.popup_origin_title_edit);
            else
                dlg.setTitle(lang.popup_origin_title_new);

            this.on('mview:renderComplete', function () {

                dlg.setMessage(this.$el);

                if (!dlg.isOpened())
                    dlg.open();
                // debugger;
                Utils.ActivateButtonWhenFormChanges(self.$('form'), dlg.getButton("save"));
                // var orderID = this.model.get('order').ID;
                // if (!dialogIsShown)
                //     BootstrapDialog.show({
                //         title: lang.orderEntry_Popup_title + orderID,
                //         message: self.$el,
                //         cssClass: 'shop-toolbox-origin-edit',
                //         onhidden: function(dialogRef){
                //             dialogIsShown = false;
                //         },
                //         buttons: [{
                //             label: lang.orderEntry_Popup_button_OK,
                //             action: function (dialog) {
                //                 dialogIsShown = false;
                //                 dialog.close();
                //             }
                //         }]
                //     });
                // dialogIsShown = true;
                // var source = self.$('#order-status-control-ID option').map(function(idx, option){
                //     return {
                //         value: $(option).attr('value'),
                //         text: $(option).text()
                //     }
                // });
                // var $controlOrderStatus = self.$('#order-status-ID');
                // $controlOrderStatus.editable({
                //     mode: 'inline',
                //     name: "Status",
                //     title: lang.orderEntry_Popup_control_status,
                //     type: "select",
                //     value: this.model.get('order').Status,
                //     pk: this.model.get('order').ID,
                //     source: $.makeArray(source),
                // });
                // $controlOrderStatus.on('save', function(event, editData) {
                //     var jqxhr = ShopUtils.updateOrderStatus(orderID, editData.newValue);
                //     jqxhr.done(function(data){
                //         self.model.set(self.model.parse.call(self.model, data));
                //         self.render();
                //     });
                // });
                // self.$('.helper').tooltip();
            });
            // order-status-ID
        },
        renderEdit: function (originID) {
            debugger;
            this.
            this.model.getItem(originID);
        },
        renderCreate: function () {
            debugger;
            this.model.getStatusList();
        }
    });

    return OriginItem;

});
define("plugin/shop/toolbox/js/view/popupCategory", [
    'default/js/lib/sandbox',
    'default/js/view/mView',
    'plugin/shop/toolbox/js/model/popupCategory',
    'default/js/lib/bootstrap-dialog',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupCategory',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    // "default/js/lib/select2/select2",
    'default/js/lib/bootstrap-editable'
], function (Sandbox, MView, ModelOrderEntry, BootstrapDialog, tpl, lang) {

    var orderItemModel = new ModelOrderEntry();
    var OrderItem = MView.extend({
        // tagName: 'div',
        // className: 'shop-order-entry',
        template: tpl,
        lang: lang,
        model: orderItemModel,
        events: {
            'click .category-props-add': 'propAdd',
            'click .category-props-del': 'propDel'
        },
        propAdd: function () {
            var tpl = this.$('#property-template').html();
            this.$('.list-category-properties').append(tpl);
        },
        propDel: function (event) {
            $(event.target).parents('li').remove();
        },
        initialize: function () {
            MView.prototype.initialize.call(this);
            var self = this;
            var dialogIsShown = false;
            // this.updateUrl({
            //     orderID: orderID
            // });
            this.on('mview:renderComplete', function () {

                var orderID = "fff";//this.model.get('order').ID;

                if (!dialogIsShown)
                    BootstrapDialog.show({
                        title: lang.popup_category_title + orderID,
                        message: self.$el,
                        cssClass: 'shop-popup-category',
                        onhidden: function(dialogRef){
                            dialogIsShown = false;
                        },
                        buttons: [{
                            label: lang.popup_category_button_Close,
                            action: function (dialog) {
                                dialogIsShown = false;
                                dialog.close();
                            }
                        }]
                    });
                dialogIsShown = true;

                var source = self.$('#order-status-control-ID option').map(function(idx, option){
                    return {
                        value: $(option).attr('value'),
                        text: $(option).text()
                    }
                });
                // debugger;
                // var $controlOrderStatus = self.$('#order-status-ID');
                // $controlOrderStatus.editable({
                //     mode: 'inline',
                //     name: "Status",
                //     title: lang.orderEntry_Popup_control_status,
                //     type: "select",
                //     value: this.model.get('order').Status,
                //     pk: this.model.get('order').ID,
                //     // url: self.model.getUrl({
                //     //     action: "orderUpdate"
                //     // }),
                //     source: $.makeArray(source),
                //     // success: function (data) {
                //     //     // debugger;
                //     //     self.model.set(self.model.parse.call(self.model, data));
                //     //     self.render();
                //     //     Sandbox.eventNotify('plugin:shop:orderList:refresh');
                //     // }
                // });
                // $controlOrderStatus.on('save', function(event, editData) {
                //     // debugger;
                //     // var _select = this;
                //     // var _editable = ;
                //     var dfd = OrderItem.updateOrderStatus(orderID, editData.newValue);
                //     dfd.done(function(data){
                //         // debugger;
                //         // $controlOrderStatus.removeClass($controlOrderStatus.data('editable').options.unsavedclass)
                //         self.model.set(self.model.parse.call(self.model, data));
                //         self.render();
                //     });
                // })
                // self.$('select').select2();
                self.$('.helper').tooltip();
            });
            // order-status-ID
        }
    });

    OrderItem.updateOrderStatus = function (orderID, status) {
        // debugger;
        var _url = orderItemModel.getUrl({
            orderID: orderID,
            action: "orderUpdate",
        });
        var dfd = $.ajax({
            type: 'POST',
            url: _url,
            data: {
                Status: status
            }
        });
        dfd.done(function(){
            Sandbox.eventNotify('plugin:shop:orderList:refresh');
        });
        return dfd;
    }

    return OrderItem;

});
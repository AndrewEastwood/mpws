define("plugin/shop/toolbox/js/view/popupOrder", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/model/popupOrder',
    'plugin/shop/common/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupOrder',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, Backbone, Utils, ModelOrderPopup, ShopUtils, BootstrapDialog, tpl, lang) {

    var OrderItem = Backbone.View.extend({
        template: tpl,
        lang: lang,
        initialize: function (orderData) {
            // var self = this;
            // var dialogIsShown = false;
            debugger;
            this.model = new ModelOrderPopup(orderData);
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var self = this;

            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            var orderID = this.model.id;
            // if (!dialogIsShown)
            // dialogIsShown = true;
            var source = this.$('#order-status-control-ID option').map(function(idx, option){
                return {
                    value: $(option).attr('value'),
                    text: $(option).text()
                }
            });
            var $controlOrderStatus = this.$('#order-status-ID');
            $controlOrderStatus.editable({
                mode: 'inline',
                name: "Status",
                title: lang.popup_order_control_status,
                type: "select",
                value: this.model.get('Status'),
                pk: this.model.id,
                source: $.makeArray(source),
            });
            $controlOrderStatus.on('save', function(event, editData) {
                // self.model.set('Status', editData.newValue);
                self.model.save({Status: editData.newValue}, {patch: true});
                // var jqxhr = ShopUtils.updateOrderStatus(orderID, editData.newValue);
                // jqxhr.done(function(data){
                //     this.model.set(this.model.parse.call(this.model, data));
                //     this.render();
                // });
            });
            this.$('.helper').tooltip();

            BootstrapDialog.show({
                message: this.$el,
                cssClass: 'pluginShopOrderPopup',
                buttons: [{
                    label: lang.popup_order_button_Close,
                    action: function (dialog) {
                        // dialogIsShown = false;
                        dialog.close();
                    }
                }]
            });
        }
    });

    return OrderItem;

});
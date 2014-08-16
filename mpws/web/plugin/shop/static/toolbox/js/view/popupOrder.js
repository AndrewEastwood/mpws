define("plugin/shop/toolbox/js/view/popupOrder", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/model/order',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    'default/js/lib/bootstrap-alert',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/popupOrder',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, Backbone, ModelOrder, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitleByStatus (status) {
        switch (status) {
            case 'NEW': {
                return $('<span/>').addClass('fa fa-dot-circle-o').append(' ', lang.order_status_NEW);
            }
            case 'ACTIVE': {
                return $('<span/>').addClass('fa fa-clock-o').append(' ', lang.order_status_ACTIVE);
            }
            case 'LOGISTIC_DELIVERING': {
                return $('<span/>').addClass('fa fa-plane').append(' ', lang.order_status_LOGISTIC_DELIVERING);
            }
            case 'LOGISTIC_DELIVERED': {
                return $('<span/>').addClass('fa fa-gift').append(' ', lang.order_status_LOGISTIC_DELIVERED);
            }
            case 'SHOP_CLOSED': {
                return $('<span/>').addClass('fa fa-check').append(' ', lang.order_status_SHOP_CLOSED);
            }
        }
    }

    var OrderItem = Backbone.View.extend({
        template: tpl,
        lang: lang,
        initialize: function (orderData) {
            if (_.isArray(orderData.Status))
                orderData.Status = orderData.Status[0];
            this.model = new ModelOrder(orderData);
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var self = this;

            var $title = $('<span/>').html(_getTitleByStatus(self.model.get('Status')));

            this.$el.html(tpl(Utils.getHBSTemplateData(this)));

            var orderID = this.model.id;
            // if (!dialogIsShown)
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
                self.model.saveOrderStatus(editData.newValue).success(function(response){
                    if (!response || !response.success) {
                        BSAlert.danger('Помилка під час оновлення замовлення');
                    }
                    $title.html(_getTitleByStatus(response.Status));
                });
            });
            this.$('.helper').tooltip();

            if (!self.dialogIsShown) {
                BootstrapDialog.show({
                    title: $title,
                    message: this.$el,
                    cssClass: 'pluginShopOrderPopup',
                    onhide: function () {
                        self.dialogIsShown = false;
                    },
                    buttons: [{
                        label: lang.popup_order_button_Close,
                        action: function (dialog) {
                            dialog.close();
                        }
                    }]
                });
                self.dialogIsShown = true;
            }

        }
    });

    return OrderItem;

});
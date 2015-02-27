define([
    'sandbox',
    'backbone',
    'plugins/shop/toolbox/js/model/order',
    'utils',
    'bootstrap-dialog',
    'bootstrap-alert',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/editOrder',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation',
    'bootstrap-editable'
], function (Sandbox, Backbone, ModelOrder, Utils, BootstrapDialog, BSAlert, tpl, lang) {

    function _getTitleByStatus (status) {
        switch (status) {
        case 'NEW':
            {
                return $('<span/>').addClass('fa fa-dot-circle-o').append(' ', lang.order_status_NEW);
            }
        case 'ACTIVE':
            {
                return $('<span/>').addClass('fa fa-clock-o').append(' ', lang.order_status_ACTIVE);
            }
        case 'LOGISTIC_DELIVERING':
            {
                return $('<span/>').addClass('fa fa-plane').append(' ', lang.order_status_LOGISTIC_DELIVERING);
            }
        case 'LOGISTIC_DELIVERED':
            {
                return $('<span/>').addClass('fa fa-gift').append(' ', lang.order_status_LOGISTIC_DELIVERED);
            }
        case 'SHOP_CLOSED':
            {
                return $('<span/>').addClass('fa fa-check').append(' ', lang.order_status_SHOP_CLOSED);
            }
        case 'CUSTOMER_CANCELED':
            {
                return $('<span/>').addClass('fa fa-times').append(' ', lang.order_status_CUSTOMER_CANCELED);
            }
        case 'SHOP_REFUNDED':
            {
                return $('<span/>').addClass('fa fa-dollar').append(' ', lang.order_status_SHOP_REFUNDED);
            }
        }
    }

    var EditOrder = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'bootstrap-dialog type-primary size-normal plugin-shop-order',
        initialize: function () {
            this.model = new ModelOrder();
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var that = this;
            var tplData = Utils.getHBSTemplateData(this);
            tplData.data.isToolbox = tplData.isToolbox;
            tplData.data.urls = tplData.instances.shop.urls;

            var $dialog = new BootstrapDialog({
                closable: false,
                draggable: false,
                title: _getTitleByStatus(that.model.get('Status')),
                message: $(tpl(tplData)),
                buttons: [{
                    label: "Надіслати фактуру",
                    cssClass: 'hidden',
                    action: function (dialog) {
                        // dialog.close();
                        Backbone.history.navigate(APP.instances.shop.urls.ordersList, true);
                    }
                }, {
                    label: "Друкувати фактуру",
                    cssClass: 'hidden',
                    action: function (dialog) {
                        // dialog.close();
                        Backbone.history.navigate(APP.instances.shop.urls.ordersList, true);
                    }
                }, {
                    label: "Надіслати код-відстеження",
                    cssClass: 'hidden',
                    action: function (dialog) {
                        // dialog.close();
                        Backbone.history.navigate(APP.instances.shop.urls.ordersList, true);
                    }
                }, {
                    label: lang.popup_order_button_Close,
                    cssClass: 'btn-warning',
                    action: function (dialog) {
                        // dialog.close();
                        Backbone.history.navigate(APP.instances.shop.urls.ordersList, true);
                    }
                }]
            });

            $dialog.realize();
            $dialog.updateTitle();
            $dialog.updateMessage();
            $dialog.updateClosable();

            this.$el.html($dialog.getModalContent());

            var orderID = this.model.id;
            // if (!dialogIsShown)
            var source = this.$('#order-status-control-ID option').map(function (idx, option) {
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
            $controlOrderStatus.on('save', function (event, editData) {
                that.model.save({
                    Status: editData.newValue
                }, {
                    patch: true,
                    success: function (model, response) {
                        if (!response || !response.success) {
                            BSAlert.danger('Помилка під час оновлення замовлення');
                        }
                    },
                    error: function () {
                        BSAlert.danger('Помилка під час оновлення замовлення');
                    }
                });
            });
            var $controlOrderInternalComment = this.$('#shop-order-internalComment-ID');
            var lazyLayout = _.debounce(function () {
                that.model.save({
                    InternalComment: $controlOrderInternalComment.val()
                }, {
                    patch: true,
                    success: function (model, response) {
                        if (!response || !response.success) {
                            BSAlert.danger('Помилка під час оновлення замовлення');
                        }
                    },
                    error: function () {
                        BSAlert.danger('Помилка під час оновлення замовлення');
                    }
                });
            }, 300);
            $controlOrderInternalComment.on('keyup', lazyLayout);
            this.$('.helper').tooltip();

            return this;
        }
    });

    return EditOrder;

});
define('plugin/shop/toolbox/js/view/managerOrders', [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/collection/listOrders',
    'plugin/shop/toolbox/js/view/listOrders',
    'plugin/shop/toolbox/js/view/popupOrder',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerOrders',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, CollectionOrders, ViewOrders, PopupOrderEntry, tpl, lang) {

    var ManagerOrders = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'shop-toolbox-orders',
        statuses: ["NEW", "ACTIVE", "LOGISTIC_DELIVERING", "LOGISTIC_DELIVERED", "SHOP_CLOSED"],
        listsByStatus: {},
        initialize: function () {
            var self = this;
            _(this.statuses).each(function(status){
                self.listsByStatus[status] = new ViewOrders({
                    collection: new CollectionOrders(status)
                });
            });
        },
        render: function (activePage) {
            // TODO:
            // add expired and todays orders
            var self = this;
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            _(this.listsByStatus).each(function(listView, status){
                var $tabPage = self.$('.tab-pane#order_status_' + status + '-ID');
                var $badge = self.$('a[href="#order_status_' + status + '-ID"] .badge');
                $tabPage.html(listView.$el);
                $badge.html(listView.$counter);
                listView.collection.fetch({reset: true});
            });
            // debugger;
            return this;
        },
        openOrder: function (orderID) {
            // debugger;
            var popupOrder = new PopupOrderEntry();
            popupOrder.model.set('ID', orderID);
            popupOrder.model.fetch();
            // popupOrder.listenTo(popupOrder.model, 'change', function (popupOrderModel) {
            //     Sandbox.eventNotify('plugin:shop:listOrders:fetch', {
            //         status: popupOrderModel.previousAttributes().Status,
            //         options: {
            //             reset: true
            //         }
            //     });
            //     Sandbox.eventNotify('plugin:shop:listOrders:fetch', {
            //         status: popupOrderModel.changedAttributes().Status,
            //         options: {
            //             reset: true
            //         }
            //     });
            //     // debugger;
            //     // Sandbox.eventNotify('plugin:shop:orderList:fetch', {reset: true});
            // });
        },
        activateTabPage: function (pageName) {
            // debugger;
            this.$('li.tab-link').removeClass('active');
            this.$('div.tab-pane').removeClass('active');
            var $tabPageLink = this.$('li.tab-link.orders-' + pageName);
            $tabPageLink.toggleClass('active', true);
            this.$($tabPageLink.find('a').attr('href')).addClass('active');
        }
    });

    return ManagerOrders;

});
define('plugin/shop/toolbox/js/view/managerCustomers', [
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'plugin/shop/toolbox/js/view/listCustomers',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/managerCustomers',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation'
], function (Backbone, Utils, ViewListCustomers, tpl, lang) {

    var ManagerCustomers = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'system-manager-customers',
        initialize: function () {
            this.viewCustomerList = new ViewListCustomers();
            this.collection = this.viewCustomerList.collection;
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            // TODO:
            // add expired and todays orders
            if (this.$el.is(':empty')) {
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                this.viewCustomerList.grid.emptyText = lang.managers.customers.noData;
                this.viewCustomerList.render();
                // show sub-view
                this.$('.list').html(this.viewCustomerList.$el);
            }
            return this;
        }
    });

    return ManagerCustomers;

});
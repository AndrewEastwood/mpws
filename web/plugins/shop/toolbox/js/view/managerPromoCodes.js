define([
    'sandbox',
    'backbone',
    'utils',
    'plugins/shop/toolbox/js/view/listPromos',
    /* template */
    'hbs!plugins/shop/toolbox/hbs/managerPromoCodes',
    /* lang */
    'i18n!plugins/shop/toolbox/nls/translation'
], function (Sandbox, Backbone, Utils, ViewOrdersListPromos, tpl, lang) {

    var ManagerPromos = Backbone.View.extend({
        template: tpl,
        lang: lang,
        className: 'plugin-shop-promos',
        events: {
            'click #show_expired': 'showExpired'
        },
        initialize: function (options) {
            // debugger;
            // set options
            this.setOptions(options);
            // create collection and viewPromosList
            this.viewPromosList = new ViewOrdersListPromos();
            this.collection = this.viewPromosList.collection;
            this.listenTo(this.collection, 'reset', this.render);
        },
        setOptions: function (options) {
            // merge with defaults
            this.options = _.defaults({}, options, {
                status: "NEW"
            });
            // and adjust them
            if (!this.options.Status)
                this.options.Status = "NEW";
        },
        showExpired: function (event) {
            this.viewPromosList.collection.fetchWithExpired($(event.target).is(':checked'), {
                reset: true
            });
        },
        render: function () {
            // TODO:
            // add expired and todays orders
            if (this.$el.is(':empty')) {
                this.$el.html(tpl(Utils.getHBSTemplateData(this)));
                this.viewPromosList.grid.emptyText = $('<h4>').text(lang.listPromos_Promo_Grid_noData);
                this.viewPromosList.render();
                // show sub-view
                this.$('.promo-list').html(this.viewPromosList.$el);
            }
            return this;
        }
    });

    return ManagerPromos;

});
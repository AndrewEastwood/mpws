define([
    'backbone',
    'handlebars',
    'plugins/shop/site/js/model/trackingStatus',
    'utils',
    'text!plugins/shop/site/hbs/trackingStatus.hbs',
    /* lang */
    'i18n!plugins/shop/site/nls/translation'
], function (Backbone, Handlebars, ModelTrackingStatus, Utils, tpl, lang) {

    var TrackingStatus = Backbone.View.extend({
        className: 'row shop-tracking-status',
        id: 'shop-tracking-status-ID',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        model: new ModelTrackingStatus(),
        events: {
            'click #shopGetOrderStatusID': 'setOrderHash'
        },
        initialize: function () {
            this.listenTo(this.model, 'change:Hash', function () {
                this.model.fetch();
            });
            this.listenTo(this.model, 'sync', this.render);
        },
        setOrderHash: function (hash){
            // debugger;
            this.model.clear({silent: true});
            if (hash instanceof $.Event)
                this.model.set('Hash', this.$('input#inputOrderTrackingID').val() || null);
            else
                this.model.set('Hash', hash);
        },
        render: function () {
            // debugger;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            var $timeline = this.$('.order-status-timeline-completion');
            switch (this.model.get('Status')) {
                case "NEW": {
                    $timeline.addClass('c0');
                    this.$('.order-status').removeClass('hidden');
                    this.$('.image-order-status-new').removeClass('disabled');
                    break;
                }
                case "ACTIVE": {
                    $timeline.addClass('c1');
                    this.$('.order-status').removeClass('hidden');
                    this.$('.image-order-status-new').removeClass('disabled');
                    this.$('.image-order-status-active').removeClass('disabled');
                    break;
                }
                case "LOGISTIC_DELIVERING": {
                    $timeline.addClass('c2');
                    this.$('.order-status').removeClass('hidden');
                    this.$('.image-order-status-new').removeClass('disabled');
                    this.$('.image-order-status-active').removeClass('disabled');
                    this.$('.image-order-status-intransit').removeClass('disabled');
                    break;
                }
                case "LOGISTIC_DELIVERED": {
                    this.$('.order-status').removeClass('hidden');
                    this.$('.image-order-status-new').removeClass('disabled');
                    this.$('.image-order-status-active').removeClass('disabled');
                    this.$('.image-order-status-intransit').removeClass('disabled');
                    this.$('.image-order-status-delivered').removeClass('disabled');
                    $timeline.addClass('c3');
                    break;
                }
                case "SHOP_CLOSED": {
                    $timeline.addClass('c4');
                    this.$('.order-status').removeClass('hidden');
                    this.$('.image-order-status').removeClass('disabled');
                    this.$('.image-order-status-completed .closed').removeClass('hidden');
                    break;
                }
            }
            return this;
        }
    });

    return TrackingStatus;

});
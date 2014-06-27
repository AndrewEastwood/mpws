define("plugin/shop/site/js/view/trackingStatus", [
    'default/js/lib/backbone',
    'plugin/shop/site/js/model/trackingStatus',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/shop/site/hbs/trackingStatus',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation'
], function (Backbone, ModelTrackingStatus, Utils, tpl, lang) {

    var TrackingStatus = Backbone.View.extend({
        className: 'row shop-tracking-status',
        id: 'shop-tracking-status-ID',
        template: tpl,
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
                this.model.set('Hash', this.$('input#inputOrderTrackingID').val());
            else
                this.model.set('Hash', hash);
        },
        render: function () {
            // debugger;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            return this;
        }
    });

    return TrackingStatus;

});
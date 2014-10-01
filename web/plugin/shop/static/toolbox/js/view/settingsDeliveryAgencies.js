define("plugin/shop/toolbox/js/view/settingsDeliveryAgencies", [
    'default/js/lib/backbone',
    'plugin/shop/toolbox/js/collection/listDeliveryAgencies',
    'default/js/lib/utils',
    'default/js/lib/bootstrap-dialog',
    /* template */
    'default/js/plugin/hbs!plugin/shop/toolbox/hbs/settingsDeliveryAgencies',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/toolbox/nls/translation',
    'default/js/lib/bootstrap-editable',
    'default/js/lib/bootstrap-switch'
], function (Backbone, CollectionOrdersExpired, Utils, BootstrapDialog, tpl, lang) {

    return Backbone.View.extend({
        className: 'panel panel-default',
        lang: lang,
        template: tpl,
        events: {
            'switchChange.bootstrapSwitch .switcher': 'setAgencyState',
            'click .remove-delivery': 'deleteAgency'
        },
        initialize: function () {
            this.collection = new CollectionOrdersExpired();
            this.collection.queryParams.limit = 0;
            this.collection.setCustomQueryField('Status', 'REMOVED:!=');
            this.listenTo(this.collection, 'reset', this.render);
        },
        render: function () {
            this.$el.html(tpl(Utils.getHBSTemplateData(this)));
            this.$('.switcher').bootstrapSwitch({
                size: 'mini',
                wrapperClass: 'delivery'
            });
            this.$('.editable').editable({
                mode: 'inline',
                emptytext: '',
                validate: function (value) {
                    if ($.trim(value) === '') {
                        return 'Введіть назву перевізника';
                    }
                }
            });
            return this;
        },
        setAgencyState: function (event, state) {
            var id = $(event.target).val(),
                model = this.collection.get(id);

            if (model) {
                model.save({
                    Status: !!state ? 'ACTIVE' : 'DISABLED'
                }, {
                    patch: true,
                    success: function (model) {
                        model.collection.fetch({
                            reset: true
                        });
                    }
                });
            }
        },
        deleteAgency: function (event) {
            var self = this,
                id = $(event.target).data('id'),
                model = this.collection.get(id);

            if (!model) {
                return;
            }

            BootstrapDialog.confirm("Видалити цей сервіс?", function (rez) {
                if (rez) {
                    model.destroy({
                        success: function () {
                            self.collection.fetch({
                                reset: true
                            });
                        }
                    });
                }
            });
        }
    });

});
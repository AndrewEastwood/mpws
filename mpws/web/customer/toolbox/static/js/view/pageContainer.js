define("customer/js/view/pageContainer", [
    'default/js/lib/sandbox',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!customer/hbs/pageContent',
    /* lang */
    'default/js/plugin/i18n!customer/nls/translation',
], function (Sandbox, Backbone, Utils, tpl, lang) {

    return Backbone.View.extend({
        attributes: {
            id: "wrapper"
        },
        lang: lang,
        template: tpl,
        render: function () {
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            Sandbox.eventNotify('global:content:render', {
                name: 'Page',
                el: this.$el
            });
            Sandbox.eventNotify('customer:container:ready', this);
        }
    });
});
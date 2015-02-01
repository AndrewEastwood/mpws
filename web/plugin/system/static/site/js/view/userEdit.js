define("plugin/system/site/js/view/userEdit", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    'default/js/plugin/hbs!plugin/system/site/hbs/userEdit',
    /* lang */
    'default/js/plugin/i18n!plugin/system/site/nls/translation',
    'default/js/lib/bootstrap-editable',
    'default/js/lib/jquery.maskedinput'
], function (Sandbox, $, Backbone, Utils, tpl, lang) {

    var AccountEdit = Backbone.View.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        template: tpl,
        lang: lang,
        initialize: function () {
            if (this.model)
                this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            var self = this;
            // debugger;

            this.$el.html(this.template(Utils.getHBSTemplateData(this)));

            $('.myeditable_phone').on('shown', function() {
                // debugger;
                $(this).data('editable').input.$input.mask('(999) 999-99-99');
            });

            this.$('.editable').editable({
                mode: 'inline',
                emptytext: lang.profile_page_edit_label_emptyValue
            });

            //make username required
            this.$('#firstname').editable('option', 'validate', function(v) {
                if(!v)
                    return lang.profile_page_edit_error_requiredField;
            });

            this.$('#save-btn').click(function() {
                self.model.save(self.$('.myeditable').editable('getValue'), {patch: true});
            });

            return this;
        }
    });

    return AccountEdit;

});
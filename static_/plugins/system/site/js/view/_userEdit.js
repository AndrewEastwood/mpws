define([
    'jquery',
    'backbone',
    'handlebars',
    'utils',
    'cachejs',
    'text!plugins/system/site/hbs/userEdit.hbs',
    /* lang */
    'i18n!plugins/system/site/nls/translation',
    'bootstrap-editable',
    'jquery.maskedinput'
], function ($, Backbone, Handlebars, Utils, cachejs, tpl, lang) {

    var AccountEdit = Backbone.View.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        template: Handlebars.compile(tpl), // check
        lang: lang,
        events: {
            'click .nav-tabs li': 'saveActiveTab'
        },
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
        },
        saveActiveTab: function () {
            cachejs.set('toolboxUserEditActiveTab', this.$('.nav-tabs li.active').find('a').attr('href'));
        }
    });

    return AccountEdit;

});
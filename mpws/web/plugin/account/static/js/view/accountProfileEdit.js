define("plugin/account/js/view/accountProfileEdit", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/view/mView',
    'plugin/account/js/model/account',
    'default/js/plugin/hbs!plugin/account/hbs/accountProfileEdit',
    /* lang */
    'default/js/plugin/i18n!plugin/account/nls/site',
    'default/js/lib/bootstrap-editable'
], function (Sandbox, $, MView, ModelAccountInstance, tpl, lang) {

    var AccountProfileEdit = MView.extend({
        // tagName: 'li',
        // className: 'col-sm-9 col-md-9',
        template: tpl,
        lang: lang,
        model: ModelAccountInstance,
        initialize: function () {
            var self = this;
            this.model.clearErrors();
            this.model.clearStates();
            this.listenTo(this.model, "change", this.render);
            this.on('mview:renderComplete', function () {
                //toggle `popup` / `inline` mode
                // $.fn.editable.defaults.mode = 'inline';
                //make username editable
                self.$('.editable').editable({
                    mode: 'inline',
                    emptytext: lang.profile_page_edit_label_emptyValue
                });

                //make username required
                self.$('#firstname').editable('option', 'validate', function(v) {
                    if(!v) return lang.profile_page_edit_error_requiredField;
                });

                self.$('#save-btn').click(function() {
                    self.model.editProfile(self.$('.myeditable').editable('getValue'));
                });
            });
        }
    });

    return AccountProfileEdit;

});
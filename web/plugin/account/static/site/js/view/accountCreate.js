define("plugin/account/site/js/view/accountCreate", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/mView',
    /* model */
    'plugin/account/common/js/model/accountCreate',
    /* template */
    'default/js/plugin/hbs!plugin/account/site/hbs/accountCreate',
    /* lang */
    'default/js/plugin/i18n!plugin/account/site/nls/translation'
], function ($, _, MView, ModelAccountCreate, tpl, lang) {

    var View = MView.getNew();

    var AccountCreate = View.extend({
        className: 'container',
        template: tpl,
        lang: lang,
        events: {
            "submit .form": 'doRegister',
        },
        initialize: function () {
            this.model = new ModelAccountCreate();
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        },
        doRegister: function () {
            var self = this;
            // debugger;
            var _fields = ['FirstName','LastName','EMail','Password','ConfirmPassword'];
            var _account = {};

            _(_fields).each(function(fldName){
                _account[fldName] = self.$('[name="' + fldName + '"]').val();
            });

            // send request with user data and update model
            $.post(this.model.url, {account: _account}, function (data){
                self.model.set(data.account);
            });

            return false;
        }
    });

    return AccountCreate;

});l
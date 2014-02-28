define("plugin/account/js/view/accountCreate", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/mView',
    /* model */
    'plugin/account/js/model/accountCreate',
    /* template */
    'default/js/plugin/hbs!plugin/account/hbs/accountCreate',
    /* lang */
    'default/js/plugin/i18n!plugin/account/nls/account'
], function ($, _, MView, ModelAccountCreate, tpl, lang) {

    var View = MView.getNew();

    var AccountCreate = View.extend({
        viewName: 'AccountCreatePage',
        className: 'container',
        template: tpl,
        lang: lang,
        model: new ModelAccountCreate(),
        events: {
            "submit .form": 'doRegister',
        },
        initialize: function () {
            MView.prototype.initialize.call(this);
            this.listenTo(this.model, "change", this.render);
        },
        doRegister: function () {
            var self = this;
            // get user fields
            // debugger;
            var _fields = ['FirstName','LastName','EMail','Password','ConfirmPassword'];
            var _account = {};

            _(_fields).each(function(fldName){
                _account[fldName] = self.$('[name="' + fldName + '"]').val();
            });

            // this.$('error-message').html('');

            $.post(this.model.url, {account: _account}, function(data){
                // debugger;
                // if (data)
                // var _data = self.parse(data);

                // _(_data).each(function(val, key){
                //     self.set(key, val, {silent: true});
                // });
                // debugger;
                self.model.set(data.account);
                // if (data && data.account && data.account.error) {
                //     if (_.isArray(data.account.error))
                //         _(data.account.error).each(function(err){
                //             var _fldMsg = err.split('_');
                //             if (_fldMsg.length === 2)
                //                 self.$('.' + _fldMsg[0] + '_Error').text(lang["register_error_" + err]);
                //         });
                // }

                // self.trigger('change');

            });

            return false;
        }
    });

    return AccountCreate;

});l
define("plugin/shop/js/view/cartStandalone", [
    'customer/js/site',
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/cartStandalone',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/nls/shop',
    "default/js/lib/jquery.cookie",
    "default/js/lib/select2/select2",
], function (Site, Sandbox, _, MView, tpl, lang) {

    $.cookie.json = true;

    var CartStandalone = MView.extend({
        // tagName: 'div',
        className: 'row shop-cart-standalone',
        id: 'shop-cart-standalone-ID',
        template: tpl,
        lang: lang,
        initialize: function() {
            MView.prototype.initialize.call(this);
            var self = this;
            this.listenTo(this.model, "change", this.render);

            // save user info
            var _userInfoChanged = _.debounce(function () {
                $.cookie("shopUser", self.collectUserInfo.call(self));
            }, 100);
            this.$el.on('keypress', 'input[type="text"],textarea', _userInfoChanged);
            this.$el.on('click', 'input[type="checkbox"]', _userInfoChanged);
            this.$el.on('change', 'select', _userInfoChanged);

            this.on('mview:renderComplete', function () {

                // restore user info
                var _shopUser = $.cookie("shopUser");
                if (_shopUser)
                    _(_shopUser).each(function(val, key){
                        // debugger;
                        var _input = self.$('[name="' + key + '"]');

                        if (_input.is(':checkbox'))
                            _input.prop('checked', !!val);
                        else
                            _input.val(val);
                    });

                self.$('select').select2();
                
                // if we have saved order we clear user data
                if (self.model.has('status') &&  self.model.get('status').orderID)
                    self.clearUserInfo();

                // if we have account plugin
                if (Site.hasPlugin('account')) {
                    // account is signed in
                    if (self.model.getExtras().account) {
                        self.$('#account-profile-addresses-ID').on('change', function (event) {
                            if ($(this).val())
                                self.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', true).addClass('hide');
                            else
                                self.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', false).removeClass('hide');
                        });
                        self.$('#account-profile-addresses-ID').trigger('change');
                    } else
                        self.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', false).removeClass('hide');
                }
            });

            Sandbox.eventSubscribe('shop:cart:save', function () {
                self.model.checkout(self.collectUserInfo());
            });

            // debugger;
            if (Site.hasPlugin('account')) {
                Sandbox.eventNotify('account:status');
                Sandbox.eventSubscribe('account:status:received', function (data) {
                    // debugger;
                    self.model.setExtras('account', data);
                    self.render();
                });
                Sandbox.eventSubscribe('account:signed:in', function (data) {
                    // debugger;
                    self.model.setExtras('account', data);
                    $.cookie("shopUser", null);
                    self.render();
                });
                Sandbox.eventSubscribe('account:signed:out', function (data) {
                    self.model.removeExtras('account');
                    $.cookie("shopUser", null);
                    self.render();
                });
                Sandbox.eventSubscribe('account:profile:address:added', function (data) {
                    self.model.setExtras('account', data);
                    self.render();
                });
                Sandbox.eventSubscribe('account:profile:address:updated', function (data) {
                    self.model.setExtras('account', data);
                    self.render();
                });
                Sandbox.eventSubscribe('account:profile:address:removed', function (data) {
                    self.model.setExtras('account', data);
                    self.render();
                });
            }

        },
        collectUserInfo: function () {
            // collect user info
            // debugger;
            var _userInfo = {};
            this.$('input,textarea,select').not('disable').each(function(){
                if (!/^shopCart/.test($(this).attr('name')))
                    return;
                if ($(this).is(':checkbox'))
                    _userInfo[$(this).attr('name')] = $(this).is(':checked');
                else
                    _userInfo[$(this).attr('name')] = $(this).val();
            });
            return _userInfo;
        },
        clearUserInfo: function () {
            $.cookie("shopUser", null);
        }
    });

    return CartStandalone;

});
define("plugin/shop/site/js/view/cartStandalone", [
    "application",
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/view/mView',
    'plugin/shop/site/js/model/cart',
    'default/js/plugin/hbs!plugin/shop/site/hbs/cartStandalone',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
    "default/js/lib/jquery.cookie",
    "default/js/lib/select2/select2",
], function (Site, Sandbox, _, MView, ModelCartInstance, tpl, lang) {

    $.cookie.json = true;

    var CartStandalone = MView.extend({
        // tagName: 'div',
        model: ModelCartInstance,
        className: 'row shop-cart-standalone',
        id: 'shop-cart-standalone-ID',
        template: tpl,
        lang: lang,
        initialize: function() {
            MView.prototype.initialize.call(this);
            var self = this;
            this.listenTo(this.model, "change", this.render);


            this.on('mview:renderComplete', function () {

                // save user info
                var _userInfoChanged = _.debounce(function () {
                    $.cookie("shopUser", self.collectUserInfo.call(self));
                }, 100);
                self.$el.on('keypress', 'input[type="text"],textarea', _userInfoChanged);
                self.$el.on('click', 'input[type="checkbox"]', _userInfoChanged);
                self.$el.on('change', 'select', _userInfoChanged);
            
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
                    // debugger;
                    if (self.model.hasExtras('account')) {
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

                self.$('#shopping-cart-logistic-ID').on('change', function (event) {
                    if ($(this).val())
                        self.$('.form-group-warehouse').prop('disable', false).removeClass('hide');
                    else
                        self.$('.form-group-warehouse').prop('disable', true).addClass('hide');
                });

            });

            Sandbox.eventSubscribe('plugin:shop:cart:save', function () {
                self.model.checkout(self.collectUserInfo());
            });

            Sandbox.eventSubscribe('plugin:account:status:received', function (data) {
                // debugger;
                if (!_.isEmpty(data))
                    self.model.setExtras('account', data);
            });
            Sandbox.eventSubscribe('plugin:account:signed:in', function (data) {
                // debugger;
                // console.log('shop account:in', data);
                if (!_.isEmpty(data)) {
                    self.model.setExtras('account', data);
                    $.cookie("shopUser", null);
                    self.model.trigger('change');
                }
            });
            Sandbox.eventSubscribe('plugin:account:signed:out', function (data) {
                // debugger;
                // console.log('shop account:out', data);
                self.model.removeExtras('account');
                $.cookie("shopUser", null);
                self.model.trigger('change');
            });

            Sandbox.eventNotify('plugin:account:status');

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
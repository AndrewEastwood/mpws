define("plugin/shop/site/js/view/cartStandalone", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/utils',
    // 'plugin/shop/site/js/collection/listProductCart',
    'plugin/shop/site/js/model/order',
    'default/js/plugin/hbs!plugin/shop/site/hbs/cartStandalone',
    /* lang */
    'default/js/plugin/i18n!plugin/shop/site/nls/translation',
    "default/js/lib/jquery.cookie",
    "default/js/lib/select2/select2",
], function (Sandbox, _, Backbone, Utils, cartCollectionInstance, tpl, lang) {

    $.cookie.json = true;

    var CartStandalone = Backbone.View.extend({
        // tagName: 'div',
        // collection: cartCollectionInstance,
        model: cartCollectionInstance,
        className: 'row shop-cart-standalone',
        id: 'shop-cart-standalone-ID',
        template: tpl,
        lang: lang,
        initialize: function() {
            // this.listenTo(this.collection, "reset", this.render);
            // this.listenTo(this.collection, 'sync', this.render);
            this.listenTo(this.model, 'change', this.render);
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
        },
        render: function () {
            var self = this;
            // debugger;
            this.$el.html(this.template(Utils.getHBSTemplateData(this)));
            // save user info
            var _userInfoChanged = _.debounce(function () {
                $.cookie("shopUser", self.collectUserInfo.call(self));
            }, 100);
            this.$el.on('keypress', 'input[type="text"],textarea', _userInfoChanged);
            this.$el.on('click', 'input[type="checkbox"]', _userInfoChanged);
            this.$el.on('change', 'select', _userInfoChanged);

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

            this.$('select').select2();
            
            // if we have saved order we clear user data
            // if (self.model.has('status') &&  self.model.get('status').orderID)
            //     self.clearUserInfo();

            // if we have account plugin
            if (APP.hasPlugin('account')) {
                // account is signed in
                // debugger;
                if (!!this.model.get('account')) {
                    this.$('#account-profile-addresses-ID').on('change', function (event) {
                        if ($(this).val())
                            self.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', true).addClass('hide');
                        else
                            self.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', false).removeClass('hide');
                    });
                    self.$('#account-profile-addresses-ID').trigger('change');
                } else
                    this.$('.form-group-address, .form-group-pobox, .form-group-country, .form-group-city').prop('disable', false).removeClass('hide');
            }

            this.$('#shopping-cart-logistic-ID').on('change', function (event) {
                if ($(this).val())
                    self.$('.form-group-warehouse').prop('disable', false).removeClass('hide');
                else
                    self.$('.form-group-warehouse').prop('disable', true).addClass('hide');
            });
            return this;
        }
    });

    return CartStandalone;

});
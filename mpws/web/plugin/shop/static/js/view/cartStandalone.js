define("plugin/shop/js/view/cartStandalone", [
    'default/js/lib/sandbox',
    'default/js/lib/underscore',
    'default/js/view/mView',
    'default/js/plugin/hbs!plugin/shop/hbs/cartStandalone',
    "default/js/lib/jquery.cookie"
], function (Sandbox, _, MView, tpl) {

    $.cookie.json = true;

    var CartEmbedded = MView.extend({
        // tagName: 'div',
        className: 'row shop-cart-standalone',
        id: 'shop-cart-standalone-ID',
        template: tpl,
        initialize: function() {
            MView.prototype.initialize.call(this);
            var self = this;
            this.listenTo(this.model, "change", this.render);

            // save user info
            var _userInfoChanged = _.debounce(function () {
                $.cookie("shopUser", self.collectUserInfo());
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
            });

            Sandbox.eventSubscribe('shop:cart:save', function () {
                self.model.checkout(self.collectUserInfo());
            });
        },
        collectUserInfo: function () {
            // collect user info
            var _userInfo = {};
            self.$('input,textarea,select').each(function(){
                if (!/^shopCart/.test($(this).attr('name')))
                    return;
                if ($(this).is(':checkbox'))
                    _userInfo[$(this).attr('name')] = $(this).is(':checked');
                else
                    _userInfo[$(this).attr('name')] = $(this).val();
            });
            return _userInfo;
        }
    });

    return CartEmbedded;

});
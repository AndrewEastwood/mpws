define("default/js/view/menu", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/mView',
    /* template */
    'default/js/plugin/hbs!default/hbs/menu'
], function ($, _, MView, tpl) {

    var Menu = MView.extend({
        template: tpl,
        menuItems: [],
        addMenuItem: function (item) {

            // debugger;

            if (item instanceof $) {
                if (item.is('li'))
                    this.menuItems.push(item);
                else
                    this.menuItems.push($('<li>').append(item));
            } else if (typeof item === "string")
                this.menuItems.push($('<li>').append(item));
            else if (Array.isArray(item))
                for (var key in item)
                    this.addMenuItem(item[key]);

            // if (this.$el.is(":empty")) {
                // return;
            // }

        },
        render: function (options, callback) {
            var _self = this;
            // debugger;
            MView.prototype.render.call(this, options, function() {

                _(_self.menuItems).each(function (item) {
                    _self.$el.find('.navbar-nav-main').append(item);
                    // _self.addMenuItem(item);
                 });

                if (typeof callback === "function")
                    callback(null, _self);
            });
        },
        showOrHideSearch: function () {
        }
    });

    return Menu;

});
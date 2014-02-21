define("default/js/view/menu", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/view/mView',
    /* template */
    'default/js/plugin/hbs!default/hbs/menu'
], function ($, _, MView, tpl) {

    var View = MView.getNew();

    var Menu = View.extend({
        template: tpl,
        menuItems: [],
        initialize: function () {
            MView.prototype.initialize.call(this);
            this.on('mview:renderComplete', function () {
                // debugger;
                this.renderMenuItems();
            }, this);
            this.on('menu:itemAdded', function () {
                // debugger;
                this.renderMenuItems();
            }, this);
        },
        addMenuItem: function (item) {
            // debugger;
            if (item instanceof $) {
                if (item.is('li'))
                    this.menuItems.push(item);
                else
                    this.menuItems.push($('<li>').append(item));
                this.trigger('menu:itemAdded');
            } else if (typeof item === "string") {
                this.menuItems.push($('<li>').append(item));
                this.trigger('menu:itemAdded');
            } else if (Array.isArray(item)) {
                for (var key in item)
                    this.addMenuItem(item[key]);
            }
        },
        isReady: function () {
            return this.getMenuItemPlaceholder().length > 0;
        },
        renderMenuItems: function (item) {
            if (!this.isReady())
                return false;

            var _self = this;
            _(this.menuItems).each(function (item) {
                // _self.renderMenuItem(item);
                _self.getMenuItemPlaceholder().append(item);
                // _self.addMenuItem(item);
             });
        },
        getMenuItemPlaceholder: function () {
            return this.$el.find('.navbar-nav-main');
        }
    });

    return Menu;

});
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
        containerItemsLeft: '.navbar-nav-main',
        containerItemsRight: '.navbar-right',
        menuItems: {
            left: [],
            right: []
        },
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
        addMenuItem: function (item, rightSide) {
            var menuSide = rightSide ? 'right' : 'left';
            // debugger;
            if (item instanceof $) {
                if (item.is('li'))
                    this.menuItems[menuSide].push(item);
                else
                    this.menuItems[menuSide].push($('<li>').append(item));
                this.trigger('menu:itemAdded');
            } else if (typeof item === "string") {
                this.menuItems[menuSide].push($('<li>').append(item));
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
            _(this.menuItems.left).each(function (item) {
                // _self.renderMenuItem(item);
                _self.getMenuItemPlaceholder().append(item);
                // _self.addMenuItem(item);
             });
            _(this.menuItems.right).each(function (item) {
                // _self.renderMenuItem(item);
                _self.getMenuItemPlaceholder(true).append(item);
                // _self.addMenuItem(item);
             });
        },
        getMenuItemPlaceholder: function (rightSide) {
            if (rightSide)
                return this.$(this.containerItemsRight);
            return this.$(this.containerItemsLeft);
        }
    });

    return Menu;

});
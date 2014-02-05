define("default/js/site", [
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    // views + models
    'default/js/view/menu',
    'default/js/view/breadcrumb'
], function ($, _, Backbone, Cache, Menu, Breadcrumb) {

    var Site = function (options) {

        var _views = {};
        var _placeholders = _.extend({}, options.placeholders || {});

        // init common views
        if (options && options.views && options.views.menu)
            _views.menu = new Menu(options.views.menu);

        if (options && options.views && options.views.breadcrumb)
            _views.breadcrumb = new Breadcrumb(options.views.breadcrumb);

        return {
            config: app.config,
            options: options,
            views: _views,
            setConfig: function (config) {
                _config = config;
            },
            start: function () {
                // debugger;
                if (_views.menu)
                    _views.menu.render();
            },
            showBreadcrumbLocation: function (options) {
                if (_views.breadcrumb)
                    _views.breadcrumb.fetchAndRender(options);
                return false;
            },
            addMenuItem: function (item) {
                if (_views.menu)
                    _views.menu.addMenuItem(item);
                return false;
            },
            getPlaceholder: function (name) {
                return _placeholders[name];
            },
            setPlaceholder: function (name, content) {
                var _ph = this.getPlaceholder(name);
                if (_ph instanceof $ && _ph.length)
                    _ph.html(content);
            }
        }
    }


    return Site;
});
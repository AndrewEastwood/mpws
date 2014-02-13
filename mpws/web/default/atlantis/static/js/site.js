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

        $.xhrPool = [];
        $.xhrPool.abortAll = function() {
            $(this).each(function(idx, jqXHR) {
                jqXHR.abort();
            });
            $.xhrPool.length = 0
        };

        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                $.xhrPool.push(xhr);
            },
            complete: function(jqXHR) {
                var index = $.xhrPool.indexOf(jqXHR);
                if (index > -1) {
                    $.xhrPool.splice(index, 1);
                }
            }
        });

        // Backbone.history.fragments = [];
        // Backbone.history.mpwsGetPreviousFragment = function () {
        //     var fcount = Backbone.history.fragments.length;
        //     if (fcount >= 2)
        //         return Backbone.history.fragments[fcount - 2];
        //     return false;
        // };
        // Backbone.Router.prototype.route = function(route, name, callback) {
        //     if (!_.isRegExp(route))
        //         route = this._routeToRegExp(route);
        //     if (!callback)
        //         callback = this[name];
        //     Backbone.history.route(route, _.bind(function(fragment) {
        //         // debugger;
        //         var args = this._extractParameters(route, fragment);
        //         callback && callback.apply(this, args);
        //         this.trigger.apply(this, ['route:' + name].concat(args));
        //         this.trigger('route', name, args);
        //         Backbone.history.trigger('route', this, name, args);
        //         Backbone.history.fragments.push(fragment);
        //     }, this));
        //     return this;
        // };

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
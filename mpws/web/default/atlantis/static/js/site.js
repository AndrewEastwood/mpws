define("default/js/site", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/cache',
    // views + models
    'default/js/view/menu',
    'default/js/view/breadcrumb',
    'default/js/lib/extend.string'
], function (Sandbox, $, _, Backbone, Cache, Menu, Breadcrumb) {

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

        // Sandbox message handler
        $('body').on('click', '[data-action]', function (event) {
            // debugger;
            var _data = $(this).data() || {};
            _data.event = event;
            Sandbox.eventNotify($(this).data('action'), _data);
        });

        $(window).on('hashchange', function() {
            var _hashTags = window.location.hash.substr(1).split('/');
            $('body').attr('class', "MPWSPage");
            if (_hashTags && _hashTags[0])
                $('body').addClass("Page" + _hashTags[0].ucWords());
        });
        $(window).trigger('hashchange');

        var Router = Backbone.Router.extend({
            routes: {
                "": "index",
                "login": "login",
                "logout": "logout",
                // "*nothing": "unknown",
            },
            index: function () {
                // debugger;
                Sandbox.eventNotify('site:page:index');
            },
            login: function () {
                // debugger;
                Sandbox.eventNotify('site:page:login');
            },
            logout: function () {
                // debugger;
                Sandbox.eventNotify('site:page:logout');
            },
            unknown: function () {
                // debugger;
                Sandbox.eventNotify('site:page:404');
            }
        });

        return {
            placeholders: _placeholders,
            config: app.config,
            options: options,
            views: _views,
            plugins: window.app.config.PLUGINS,
            setConfig: function (config) {
                _config = config;
            },
            start: function () {
                var defaultRouter = new Router();
                // debugger;
                if (_views.menu)
                    _views.menu.render();

                if (options.site && options.site.title)
                    $('head title').text(options.site.title);
            },
            showBreadcrumbLocation: function (options) {
                if (_views.breadcrumb)
                    _views.breadcrumb.fetchAndRender(options);
                return false;
            },
            addMenuItemLeft: function (item) {
                if (_views.menu)
                    _views.menu.addMenuItem(item);
                return false;
            },
            addMenuItemRight: function (item) {
                if (_views.menu)
                    _views.menu.addMenuItem(item, true);
                return false;
            },
            hasPlugin: function (pluginName) {
                return _(window.app.config.PLUGINS).indexOf(pluginName) >= 0;
            }
        }
    }


    return Site;
});
define("default/js/site", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url',
    'default/js/lib/extend.string',
], function (Sandbox, $, _, Backbone, JSUrl) {

    var Site = function (options) {

        var _views = {};
        var _placeholders = _.extend({}, options.placeholders || {});

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
                xhr.always(function (data, status, xhr){
                    var index = $.xhrPool.indexOf(xhr);
                    if (index > -1) {
                        $.xhrPool.splice(index, 1);
                    }
                    if (data && data.redirect)
                        window.location = data.redirect;
                    // if (status === "success")
                });
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
                debugger;
                Sandbox.eventNotify(_site.realm + ':page:index');
            },
            login: function () {
                // debugger;
                Sandbox.eventNotify(_site.realm + ':page:login');
            },
            logout: function () {
                // debugger;
                Sandbox.eventNotify(_site.realm + ':page:logout');
            },
            unknown: function () {
                // debugger;
                Sandbox.eventNotify(_site.realm + ':page:404');
            }
        });

        var defaultRouter = new Router();

        var _site = {
            realm: app.config.ISTOOLBOX ? 'toolbox' : 'site',
            placeholders: _placeholders,
            config: app.config,
            options: options,
            views: _views,
            plugins: window.app.config.PLUGINS,
            hasPlugin: function (pluginName) {
                return _(window.app.config.PLUGINS).indexOf(pluginName) >= 0;
            },
            getApiLink: function (source, fn, extraOptions) {

                var _url = new JSUrl(app.config.URL_API);
                _url.query.token = app.config.TOKEN;

                if (source)
                    _url.query.source = source;

                if (fn)
                    _url.query.fn = fn;

                _(extraOptions).each(function (v, k) {
                    _url.query[k] = !!v ? v : "";
                });

                return _url.toString();
            },
            renderBefore: null,
            renderAfter: null,
            render: function (options) {
                // console.log('_renderFn', options);

                if (!options || !options.name)
                    return;

                if (_.isFunction(_site.renderBefore))
                    _site.renderBefore(options);

                // debugger;
                var _container = _placeholders[options.name];

                if (!_container || !_container.length)
                    return;

                if (options.append)
                    _container.append(options.el);
                else if (options.prepend)
                    _container.prepend(options.el);
                else
                    _container.html(options.el);

                if (_.isFunction(_site.renderAfter))
                    _site.renderAfter(options);
            }
        }

        Sandbox.eventSubscribe(_site.realm + ':content:render', function (options) {
            if (_.isArray(options))
                _(options).each(function(option){
                    _site.render(option);
                });
            else
                _site.render(options);
        });

        return _site;
    }


    return Site;
});
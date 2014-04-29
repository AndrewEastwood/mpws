define("application", [
    'default/js/lib/sandbox',
    'cmn_jquery',
    'default/js/lib/underscore',
    'default/js/lib/backbone',
    'default/js/lib/url',
    'default/js/lib/contentInjection',
    'default/js/lib/extend.string',
], function (Sandbox, $, _, Backbone, JSUrl, contentInjection) {

    var _site = null;

    function Site (options) {

        if (_site)
            return _site;

        var _views = {};
        var _placeholders = _.extend({}, options.placeholders || {});

        $.xhrPool = [];
        $.xhrPool.abortAll = function() {
            $(this).each(function(idx, jqXHR) {
                jqXHR.abort();
            });
            $.xhrPool.length = 0
        };

        $( document ).ajaxComplete(function(event, jqxhr, data) {
            if (!jqxhr.responseText)
                return

            // console.log(jqxhr.responseText);

            var responseObj = JSON.parse(jqxhr.responseText);

            if (responseObj && responseObj.error && responseObj.error === "InvalidPublicTokenKey")
                Sandbox.eventNotify("global:session:expired", responseObj.error);

            if (responseObj && responseObj.error && responseObj.error === "AccessDenied")
                Sandbox.eventNotify("global:session:expired", responseObj.error);

        });

        // Sandbox message handler
        $('body').on('click', '[data-action]', function (event) {
            // debugger;
            var _data = $(this).data() || {};
            _data.event = event;
            Sandbox.eventNotify($(this).data('action'), _data);
        });

        // find links and set them active accordint to current route
        var _setActiveMenuItemsFn = function () {
            // debugger;
            $('a.auto-active').removeClass('active');
            $('a.auto-active').parents('.panel-collapse').removeClass('in');

            // debugger;
            if (window.location.hash != '#') {
                $('a.auto-active[href^="' + window.location.hash + '"]').addClass('active');
                $('a.auto-active[href^="' + window.location.hash + '"]').parents('.panel-collapse').addClass('in');
            }
        }

        Sandbox.eventSubscribe('global:menu:set-active', function () {
            // debugger;
            _setActiveMenuItemsFn();
        });

        $(window).on('hashchange', function() {
            // set page name
            var _hashTags = window.location.hash.substr(1).split('/');
            $('body').attr('class', "MPWSPage");
            if (_hashTags && _hashTags[0])
                $('body').addClass("Page" + _hashTags[0].ucWords());

            // set active links
            Sandbox.eventNotify('global:menu:set-active');

            // notify all subscribers that we have changed our route
            Sandbox.eventNotify('global:route', window.location.hash);
        });
        $(window).trigger('hashchange');

        var Router = Backbone.Router.extend({
            routes: {
                "": "index",
                "login": "login",
                "logout": "logout"
            },
            index: function () {
                // debugger;
                Sandbox.eventNotify('global:page:index');
            },
            login: function () {
                // debugger;
                Sandbox.eventNotify('global:page:login');
            },
            logout: function () {
                // debugger;
                Sandbox.eventNotify('global:page:logout');
            }
        });

        var defaultRouter = new Router();

        _site = {
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

                contentInjection.injectContent(_container, options);

                if (_.isFunction(_site.renderAfter))
                    _site.renderAfter(options);

                // refresh links (make them active)
                Sandbox.eventNotify('global:menu:set-active');
            }
        }

        Sandbox.eventSubscribe('global:content:render', function (options) {
            if (_.isArray(options))
                _(options).each(function(option){
                    _site.render(option);
                });
            else
                _site.render(options);
        });

        // update instance
        _(_site).each(function(prop, key) {
            Site[key] = prop;
        });

        return _site;
    }

    return Site;
});
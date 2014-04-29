var APP = {
    config: JSON.parse(JSON.stringify(MPWS)),
    getModulesToDownload: function() {
        var modules = [
            'default/js/lib/sandbox',
            'cmn_jquery',
            'default/js/lib/underscore',
            'default/js/lib/backbone',
            'default/js/lib/url',
            'default/js/lib/contentInjection',
            this.config.ROUTER,
            'default/js/plugin/css!customer/css/theme.css'
        ];
        // include site file
        for (var key in this.config.PLUGINS)
            modules.push('plugin/' + this.config.PLUGINS[key] + '/' + (this.config.ISTOOLBOX ? 'toolbox' : 'site') + '/js/router');
        return modules;
    },
    init: function () {
        // set requirejs configuration
        require.config({
            locale: this.config.LOCALE,
            baseUrl: this.config.PATH_STATIC_BASE,
            // mpws: this.config,
            paths: {
                // application
                // application: this.config.URL_STATIC_DEFAULT + "js/app",
                // default paths
                default: this.config.URL_STATIC_DEFAULT,
                // website (origin customer)
                website: this.config.URL_STATIC_WEBSITE,
                // customer paths (current running customer)
                customer: this.config.URL_STATIC_CUSTOMER,
                // plugin paths
                plugin: this.config.URL_STATIC_PLUGIN,
                // version suppress
                cmn_jquery: this.config.URL_STATIC_DEFAULT + 'js/lib/jquery-1.9.1'
            },
            waitSeconds: 15,
            urlArgs: "mpws_bust=" + (this.config.ISDEV ? (new Date()).getTime() : this.config.BUILD)
        });
    }
};

// if (!APP.config.ISDEV)
//     delete MPWS;

// window.app = {
//     config: _globalConfig
// };




// debugger;

// console.log(_filesToRequest);
// start customer application
// require(_filesToRequest, function (Sandbox, Site) {
//     var _args = [].slice.call(arguments, 1);
//     var _routers = [];

//     // setup plugin routers
//     var pluginCount  = _args.length;
//     if (pluginCount > 1)
//         for (var i = 1; i < pluginCount; i++) {
//             // debugger;
//             var router = new _args[i](Site);
//             _routers.push(router);
//         }

//     // notify all that loader completed its tasks
//     Sandbox.eventNotify('global:loader:complete');

//     // start HTML5 History push
//     Backbone.history.start();
// });

APP.init();

require(APP.getModulesToDownload(), function (Sandbox, $, _, Backbone, JSUrl, contentInjection, CustomerRouter) {

    debugger;
    // var _site = null;

    // function Site (options) {

        // if (_site)
        //     return _site;

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


        var _site = {
            realm: app.config.ISTOOLBOX ? 'toolbox' : 'site',
            placeholders: _placeholders,
            config: app.config,
            options: options,
            views: _views,
            plugins: APP.config.PLUGINS,
            hasPlugin: function (pluginName) {
                return _(APP.config.PLUGINS).indexOf(pluginName) >= 0;
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
        // _(_site).each(function(prop, key) {
        //     Site[key] = prop;
        // });

        // return _site;
    // }

    // notify all that loader completed its tasks
    Sandbox.eventNotify('global:loader:complete');

    // start HTML5 History push
    Backbone.history.start();
    // return Site;
});
var APP = {
    config: JSON.parse(JSON.stringify(MPWS)),
    commonElements: [],
    instances: {},
    isCompleted: false,
    getModulesToDownload: function() {
        var modules = [
            'default/js/lib/sandbox',
            'cmn_jquery',
            'default/js/lib/underscore',
            'default/js/lib/backbone',
            'default/js/lib/cache',
            'default/js/lib/auth',
            'default/js/lib/contentInjection',
            'default/js/plugin/has',
            // this.config.ROUTER,
            'default/js/plugin/css!customer/css/theme.css'
        ];
        return modules;
    },
    getPluginRoutersToDownload: function () {
        var modules = [];
        // include site file
        for (var key in this.config.PLUGINS)
            modules.push('plugin/' + this.config.PLUGINS[key] + '/' + (this.config.ISTOOLBOX ? 'toolbox' : 'site') + '/js/router');
        return modules;
    },
    hasPlugin: function (pluginName) {
        return _(this.config.PLUGINS).indexOf(pluginName) >= 0;
    },
    getAuthLink: function (options) {
        throw "Implment function getAuthLink";
    },
    getApiLink: function (options) {
        throw "Implment function getApiLink";
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
            waitSeconds: 20,
            urlArgs: "mpws_bust=" + (this.config.ISDEV ? (new Date()).getTime() : this.config.BUILD)
        });
    }
};

APP.init();

require(["default/js/lib/url"], function(JSUrl) {
    APP.getApiLink = function (extraOptions) {
        var _url = new JSUrl(APP.config.URL_API);
        _url.query.token = APP.config.TOKEN;
        if (!_.isEmpty(extraOptions))
            _(extraOptions).each(function (v, k) {
                _url.query[k] = !!v ? v : "";
            });
        return _url.toString();
    }
    APP.getAuthLink = function (extraOptions) {
        var _url = new JSUrl(APP.config.URL_AUTH);
        _url.query.token = APP.config.TOKEN;
        if (!_.isEmpty(extraOptions))
            _(extraOptions).each(function (v, k) {
                _url.query[k] = !!v ? v : "";
            });
        return _url.toString();
    }
})

require(APP.getModulesToDownload(), function (Sandbox, $, _, Backbone, Cache, Auth, contentInjection, CssInjection /* plugins goes here */) {

    APP.commonElements = $('div[name^="Common"]:not(:has(*))');

    Backbone.Model.prototype.isEmpty = function () {
        return _.isEmpty(this.attributes);
    }

    var renderFn = function (options) {
        // debugger;
        if (!options || !options.name)
            return;
        // debugger;
        var $el = $('[name="' + options.name + '"]');
        if ($el.length === 0) {
            if (APP.config.ISDEV) {
                throw "Render Error: Unable to resolve element by name: " + options.name;
            }
        } else
            contentInjection.injectContent($el, options);
        // $el.each(function(){
        // });
    }

    var xhrPool = [];
    $(document).ajaxSend(function(e, jqXHR, options){
        xhrPool.push(jqXHR);
    });
    $(document).ajaxComplete(function(event, jqXHR, data) {
        xhrPool = $.grep(xhrPool, function(x){return x!=jqXHR});
        if (!jqXHR.responseText)
            return;
        // debugger;
        var response = JSON.parse(jqXHR.responseText);
        // if (response && response.error && response.error === "InvalidTokenKey") {
        //     window.location.reload();
        // }
        Sandbox.eventNotify("global:ajax:responce", response);
    });
    APP.xhrAbortAll = function() {
        $.each(xhrPool, function(idx, jqXHR) {
            jqXHR.abort();
        });
    };

    // Sandbox message handler
    $('body').on('click', '[data-action]', function (event) {
        // debugger;
        var _data = $(this).data() || {};
        _data.event = event;
        Sandbox.eventNotify($(this).data('action'), _data);
    });

    Sandbox.eventSubscribe('global:page:setTitle', function (title) {
        if (_.isArray(title))
            $('title').text(title.join(' - '));
        else if (_.isString(title))
            $('title').text(title);
    });

    // find links and set them active accordint to current route
    Sandbox.eventSubscribe('global:menu:set-active', function (selector) {
        // debugger;
        // _setActiveMenuItemsFn();
        // debugger;
        if (selector) {
            $('[rel="menu"]').removeClass('active');
            $(selector + '[rel="menu"]').addClass('active');
        } else {
            $('a.auto-active').removeClass('active');
            $('a.auto-active').parents('.panel-collapse').removeClass('in');
            $('a.auto-active').parents('li').removeClass('active');
            // debugger;
            var hash = !!window.location.hash ? window.location.hash : '#';
            $('a.auto-active[href="' + hash + '"]').addClass('active');
            $('a.auto-active[href="' + hash + '"]').parents('.panel-collapse').addClass('in');
            $('a.auto-active[href="' + hash + '"]').parents('li').addClass('active');
        }

    });

    // debugger;
    Sandbox.eventSubscribe('global:content:render', function (options) {
        // debugger;
        if (_.isArray(options))
            _(options).each(function(option){
                renderFn(option);
            });
        else
            renderFn(options);
        // refresh links (make them active)
        Sandbox.eventNotify('global:menu:set-active');
    });

    $(window).on('hashchange', function() {
        // set page name
        var fragment = Backbone.history.getFragment();
        var _hashTags = fragment.split('/');
        $('body').attr('class', "MPWSPage");
        if (_hashTags && _hashTags[0])
            $('body').addClass("Page" + _hashTags[0].ucWords());

        // set active links
        Sandbox.eventNotify('global:menu:set-active');

        // notify all subscribers that we have changed our route
        Sandbox.eventNotify('global:route', fragment);

        // save location
        if (fragment !== "signout" && fragment !== "signin")
            Cache.saveInLocalStorage("location", fragment);
    });

    // Backbone.Router.prototype.before = function () {};
    // Backbone.Router.prototype.after = function () {};

    // Backbone.Router.prototype.route = function (route, name, callback) {
    //   if (!_.isRegExp(route)) route = this._routeToRegExp(route);
    //   if (_.isFunction(name)) {
    //     callback = name;
    //     name = '';
    //   }
    //   if (!callback) callback = this[name];

    //   var router = this;

    //   Backbone.history.route(route, function(fragment) {
    //     var args = router._extractParameters(route, fragment);

    //     router.before.apply(router, arguments);
    //     callback && callback.apply(router, args);
    //     router.after.apply(router, arguments);

    //     router.trigger.apply(router, ['route:' + name].concat(args));
    //     router.trigger('route', name, args);
    //     Backbone.history.trigger('route', router, name, args);
    //   });
    //   return this;
    // };

    var Router = Backbone.Router.extend({
        routes: {
            "": "index",
            "signin": "signin",
            "signout": "signout"
        },
        index: function () {
            // debugger;
            Sandbox.eventNotify('global:page:index', '');
        },
        signin: function () {
            // debugger;
            Sandbox.eventNotify('global:page:signin', 'signin');
        },
        signout: function () {
            // debugger;
            Auth.signout(function(){
                Sandbox.eventNotify('global:page:signout', 'signout');
            });
        }
    });

    var defaultRouter = new Router();

    require([APP.config.ROUTER], function(CustomerRouter){
        if (_.isFunction(CustomerRouter)) {
            var customerRouter = new CustomerRouter();
            APP.instances['CustomerRouter'] = customerRouter;
        }

        var releasePluginsFn = function () {
            var pluginList = APP.getPluginRoutersToDownload();
            var pluginNames = _(pluginList).map(function(pluginListItem){ return pluginListItem.match(/^plugin\/(\w+)\//)[1]; });
            require(pluginList, function(){
                var _pluginsObjects = [].slice.call(arguments);
                // initialize plugins
                _(_pluginsObjects).each(function(plugin, key){
                    if (_.isFunction(plugin)) {
                        var plg = new plugin();
                        APP.instances[pluginNames[key]] = plg;
                    }
                });
                // start HTML5 History push
                Backbone.history.start();
                // notify all that loader completed its tasks
                Sandbox.eventNotify('global:loader:complete');
                // return Site;
                $(window).trigger('hashchange');
                // get auth status
                Auth.getStatus();
                // set completion state
                APP.isCompleted = true;
            });
        }

        if (_.isObject(CustomerRouter) && CustomerRouter.releasePlugins)
            CustomerRouter.releasePlugins(releasePluginsFn);
        else
            releasePluginsFn();
    });

});
var APP = {
    config: JSON.parse(JSON.stringify(MPWS)),
    commonElements: [],
    instances: {},
    getModulesToDownload: function() {
        var modules = [
            'default/js/lib/sandbox',
            'cmn_jquery',
            'default/js/lib/underscore',
            'default/js/lib/backbone',
            'default/js/lib/cache',
            'default/js/lib/contentInjection',
            this.config.ROUTER,
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
})

require(APP.getModulesToDownload(), function (Sandbox, $, _, Backbone, Cache, contentInjection, CustomerRouter, CssInjection /* plugins goes here */) {

    APP.commonElements = $('div[name^="Common"]:not(:has(*))');

    $.xhrPool = []; 
    $.xhrPool.abortAll = function() {
        $(this).each(function(idx, jqXHR) {
            jqXHR.abort();
        });
        $.xhrPool.length = 0
    };

    $(document).ajaxComplete(function(event, jqxhr, data) {
        if (!jqxhr.responseText)
            return

        // debugger;


        // if (data.account) {
        //     // debugger;
        //     // if (Backbone.history.fragment !== "signout" && Backbone.history.fragment !== "signin") {
        //         var _location = Cache.getFromLocalStorage("location") || '';
        //         Backbone.history.navigate(_location, true);
        //     // }
        // } else {
        //     if (Backbone.history.fragment !== "signin")
                
        // }
        // console.log(jqxhr.responseText);
        var responseObj = JSON.parse(jqxhr.responseText);

        if (responseObj && responseObj.error && responseObj.error === "InvalidTokenKey") {
            window.location.reload();
        }

        // if (responseObj && responseObj.error && responseObj.error === "AccessDenied")
        //     Sandbox.eventNotify("global:session:expired", responseObj.error);

        if (responseObj) {
            if(responseObj.authenticated && Backbone.history.fragment === "signin")
                Backbone.history.navigate(Cache.getFromLocalStorage("location") || '', true);
            else if (!responseObj.authenticated) {
                $.xhrPool.abortAll();
                // Sandbox.eventNotify("global:session:needlogin", responseObj.error);
                if (APP.config.ISTOOLBOX && Backbone.history.fragment !== "signin") {
                    Backbone.history.navigate('signin');
                    window.location.reload();
                }
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

    Sandbox.eventSubscribe('global:page:setTitle', function (title) {
        if (_.isArray(title))
            $('title').text(title.join(' - '));
        else if (_.isString(title))
            $('title').text(title);
    });

    // find links and set them active accordint to current route
    Sandbox.eventSubscribe('global:menu:set-active', function () {
        // debugger;
        // _setActiveMenuItemsFn();
        // debugger;
        $('a.auto-active').removeClass('active');
        $('a.auto-active').parents('.panel-collapse').removeClass('in');

        // debugger;
        if (window.location.hash != '#') {
            $('a.auto-active[href^="' + window.location.hash + '"]').addClass('active');
            $('a.auto-active[href^="' + window.location.hash + '"]').parents('.panel-collapse').addClass('in');
        }
    });

    $(window).on('hashchange', function() {
        // set page name
        var fragment = window.location.hash.substr(1);
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
    $(window).trigger('hashchange');

    // Sandbox.eventSubscribe('plugin:account:status:received', function (data) {

    //     // debugger;
    //     if (data.account) {
    //         // debugger;
    //         // if (Backbone.history.fragment !== "signout" && Backbone.history.fragment !== "signin") {
    //             var _location = Cache.getFromLocalStorage("location") || '';
    //             Backbone.history.navigate(_location, true);
    //         // }
    //     } else {
    //         $.xhrPool.abortAll();
    //         if (Backbone.history.fragment !== "signin")
    //             Backbone.history.navigate("signin", true);
    //     }
    //     // debugger;
    //     // if (!renderCompleteSent) {
    //     //     renderCompleteSent = true;
    //     //     Sandbox.eventNotify('plugin:toolbox:render:complete');
    //     // }
    // });

    var Router = Backbone.Router.extend({
        routes: {
            "": "index",
            "signin": "signin",
            "signout": "signout"
        },
        index: function () {
            // debugger;
            Sandbox.eventNotify('global:page:index');
        },
        signin: function () {
            // debugger;
            Sandbox.eventNotify('global:page:signin');
        },
        signout: function () {
            // debugger;
            Sandbox.eventNotify('global:page:signout');
        }
    });

    var defaultRouter = new Router();

    if (_.isFunction(CustomerRouter)) {
        var customerRouter = new CustomerRouter();
        APP.instances['CustomerRouter'] = customerRouter;
    }

    var renderFn = function (options) {
        // debugger;
        if (!options || !options.name)
            return;
        // debugger;
        var el = $('[name="' + options.name + '"]');
        if (el.length == 0)
            throw "Render Error: Unable to resolve element by name: " + options.name;
        contentInjection.injectContent(el, options);
    }

    Sandbox.eventSubscribe('global:content:render', function (options) {
        if (_.isArray(options))
            _(options).each(function(option){
                renderFn(option);
            });
        else
            renderFn(options);
        // refresh links (make them active)
        Sandbox.eventNotify('global:menu:set-active');
    });

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
        });
    }

    if (_.isObject(CustomerRouter) && CustomerRouter.releasePlugins)
        CustomerRouter.releasePlugins(releasePluginsFn);
    else
        releasePluginsFn();


});
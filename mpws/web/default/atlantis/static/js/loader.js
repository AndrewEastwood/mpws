var APP = {
    config: JSON.parse(JSON.stringify(MPWS)),
    commonElements: [],
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
    hasPlugin: function (pluginName) {
        return _(APP.config.PLUGINS).indexOf(pluginName) >= 0;
    },
    getApiLink: function () {
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
            waitSeconds: 15,
            urlArgs: "mpws_bust=" + (this.config.ISDEV ? (new Date()).getTime() : this.config.BUILD)
        });
    }
};

APP.init();

require(APP.getModulesToDownload(), function (Sandbox, $, _, Backbone, JSUrl, contentInjection, CustomerRouter, CssInjection /* plugins goes here */) {

    APP.commonElements = $('div[name^="Common"]:not(:has(*))');
    // function
    APP.getApiLink = function (source, fn, extraOptions) {

        var _url = new JSUrl(APP.config.URL_API);
        _url.query.token = APP.config.TOKEN;

        if (source)
            _url.query.source = source;

        if (fn)
            _url.query.fn = fn;

        _(extraOptions).each(function (v, k) {
            _url.query[k] = !!v ? v : "";
        });

        return _url.toString();
    }

    // debugger;
    var _pluginsObjects = [].slice.call(arguments, 8);

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

        // console.log(jqxhr.responseText);
        var responseObj = JSON.parse(jqxhr.responseText);

        if (responseObj && responseObj.error && responseObj.error === "InvalidPublicTokenKey")
            Sandbox.eventNotify("global:session:expired", responseObj.error);

        if (responseObj && responseObj.error && responseObj.error === "AccessDenied")
            Sandbox.eventNotify("global:session:expired", responseObj.error);

        if (responseObj && responseObj.error && responseObj.error === "LoginRequired")
            Sandbox.eventNotify("global:session:expired", responseObj.error);
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

    // initialize plugins
    _(_pluginsObjects).each(function(plugin){
        new plugin();
    });

    // notify all that loader completed its tasks
    Sandbox.eventNotify('global:loader:complete');

    // start HTML5 History push
    Backbone.history.start();
    // return Site;
});
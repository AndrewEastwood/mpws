var APP = {
    config: JSON.parse(JSON.stringify(MPWS)),
    commonElements: [],
    instances: {},
    isCompleted: false,
    getModulesToDownload: function () {
        var modules = [
            'default/js/lib/sandbox',
            'cmn_jquery',
            'default/js/lib/underscore',
            'default/js/lib/backbone',
            'default/js/lib/cache',
            'default/js/lib/auth',
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
    getRJConfig: function () {
        return {
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
                cmn_jquery: this.config.URL_STATIC_DEFAULT + '/js/lib/jquery-1.9.1'
            },
            waitSeconds: 20,
            urlArgs: "mpws_bust=" + (this.config.ISDEV ? (new Date()).getTime() : this.config.BUILD)
        };
    },
    init: function () {
        // set requirejs configuration
        require.config(this.getRJConfig());
    },
    backgroundTaskIds: {},
    dfd: {},
    utils: {
        replaceArray: function (replaceString, find, replace) {
            var regex; 
            for (var i = 0; i < find.length; i++) {
                regex = new RegExp(find[i], "g");
                replaceString = replaceString.replace(regex, replace[i]);
            }
            return replaceString;
        }
    }
};

APP.init();

require(["default/js/lib/sandbox", "default/js/lib/url", "default/js/lib/underscore", "default/js/lib/cache"], function (Sandbox, JSUrl, _, Cache) {
    APP.getApiLink = function (extraOptions /* or args */) {
        var _url = new JSUrl(APP.config.URL_API);
        if (typeof extraOptions === "object" && extraOptions.source && extraOptions.fn) {
            // backward compatibility
            _url.path += extraOptions.source + '/' + extraOptions.fn + '/';
            var queryItems = _.omit(extraOptions, 'source', 'fn');
            _(queryItems).each(function (v, k) {
                _url.query[k] = !!v ? v : "";
            });
        } else {
            _url.path += [].slice.call(arguments).join('/') + '/';
        }
        return _url.toString();
    }
    APP.getAuthLink = function (extraOptions) {
        var _url = new JSUrl(APP.config.URL_API + '/system/auth/');
        return _url.toString();
    }
    // APP.getApiLink = function (extraOptions) {
    //     var _url = new JSUrl(APP.config.URL_API);
    //     // _url.query.token = APP.config.TOKEN;
    //     if (!_.isEmpty(extraOptions))
    //         _(extraOptions).each(function (v, k) {
    //             _url.query[k] = !!v ? v : "";
    //         });
    //     return _url.toString();
    // }
    // APP.getAuthLink = function (extraOptions) {
    //     var _url = new JSUrl(APP.config.URL_AUTH);
    //     // _url.query.token = APP.config.TOKEN;
    //     if (!_.isEmpty(extraOptions))
    //         _(extraOptions).each(function (v, k) {
    //             _url.query[k] = !!v ? v : "";
    //         });
    //     return _url.toString();
    // }
    APP.getUploadUrl = function (extraOptions) {
        var _url = new JSUrl(APP.config.URL_UPLOAD);
        // _url.query.token = APP.config.TOKEN;
        if (!_.isEmpty(extraOptions))
            _(extraOptions).each(function (v, k) {
                _url.query[k] = !!v ? v : "";
            });
        return _url.toString();
    }
    // APP.triggerBackgroundTask = function (name, params) {
    //     var _url = new JSUrl(APP.config.URL_TASK);
    //     _url.query.name = name;
    //     if (params) {
    //         if (_.isString(params))
    //             _url.query.name = params;
    //         if (_.isArray(params))
    //             _url.query.params = params.join(',');
    //     }
    //     if (APP.backgroundTaskIds[name] === null) {
    //         APP.backgroundTaskId[name] = setInterval(function () {
    //             var bgtask = Cache.getCookie('bgtask');
    //             if (!!!bgtask) {
    //                 Sandbox.eventNotify('backgroundtask:complete', name);
    //             }
    //             clearInterval(APP.backgroundTaskId[name]);
    //             APP.backgroundTaskId[name] = null;
    //         }, 5000);
    //     }
    //     return $.post(_url.toString());
    // }
})

require(APP.getModulesToDownload(), function (Sandbox, $, _, Backbone, Cache, Auth, CssInjection /* plugins goes here */ ) {
    // simple extend function
    // from: http://andrewdupont.net/2009/08/28/deep-extending-objects-in-javascript/
    Object.deepExtend = function (destination, source) {
        for (var property in source) {
            if (source[property] && source[property].constructor && source[property].constructor === Object) {
                destination[property] = destination[property] || {};
                Object.deepExtend(destination[property], source[property]);
            } else {
                destination[property] = source[property];
            }
        }
        return destination;
    };
    APP.commonElements = $('div[name^="Common"]:not(:has(*))');

    Backbone.Model.prototype.isEmpty = function () {
        return _.isEmpty(this.attributes);
    }

    var contentInjection = function (cnt, options) {

        if (!options || !options.name)
            return;

        if (!cnt || !cnt.length)
            return;

        // debugger;
        if (options.keepExisted) {
            // get element id that we want to replace
            var _elID = options.el.attr('id');
            // find existed elements
            var _items = cnt.find('#' + _elID);
            // do nothing when element exists
            if (_items.length > 0)
                return;
        }

        // debugger;
        if (options.replace) {
            // get element id that we want to replace
            var _elID = options.el.attr('id');
            if (_elID) {
                // find existed elements
                var _items = cnt.find('#' + _elID);
                // if there are some we do replace
                if (_items.length > 0)
                    _items.replaceWith(options.el);
                else // or just append as new one into container
                    cnt.append(options.el);
            }
        } else if (options.append)
            cnt.append(options.el);
        else if (options.prepend)
            cnt.prepend(options.el);
        else
            cnt.html(options.el);
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
            contentInjection($el, options);
        // $el.each(function(){
        // });
    }

    // var xhrPool = [];
    // $(document).ajaxSend(function (e, jqXHR) {
    //     xhrPool.push(jqXHR);
    // });
    $(document).ajaxComplete(function (event, jqXHR) {
        // xhrPool = $.grep(xhrPool, function (x) {
        //     return x != jqXHR
        // });
        // if (!jqXHR.responseText)
        //     return;
        var response = jqXHR.responseText;
        try {
            // debugger;
            response = JSON.parse(jqXHR.responseText);
        } catch (exception) {
            // debugger;
            console.log(exception);
        }
        // if (response && response.error && response.error === "InvalidTokenKey") {
        //     window.location.reload();
        // }
        Sandbox.eventNotify("global:ajax:responce", response);
    });
    // APP.xhrAbortAll = function () {
    //     $.each(xhrPool, function (idx, jqXHR) {
    //         jqXHR.abort();
    //     });
    // };

    APP.Cache = Cache;

    // Sandbox message handler
    $('body').on('click', '[data-action]', function (event) {
        // debugger;
        var _data = $(this).data() || {};
        _data.event = event;
        Sandbox.eventNotify($(this).data('action'), _data);
    });

    Sandbox.eventSubscribe('global:page:setTitle', function (title) {
        if (_.isArray(title))
            $('head title').text(title.join(' - '));
        else if (_.isString(title))
            $('head title').text(title);
    });

    Sandbox.eventSubscribe('global:page:setKeywords', function (keywords) {
        $('head meta[name="keywords"]').attr('content', keywords);
    });

    Sandbox.eventSubscribe('global:page:setDescription', function (description) {
        $('head meta[name="description"]').attr('content', description);
    });
    // find links and set them active accordint to current route
    // Sandbox.eventSubscribe('global:menu:set-active', function (selector) {
    //     // debugger;
    //     // _setActiveMenuItemsFn();
    //     // debugger;
    //     if (selector) {
    //         $('[rel="menu"]').removeClass('active');
    //         $(selector + '[rel="menu"]').addClass('active');
    //     } else {
    //         $('a.auto-active').removeClass('active');
    //         $('a.auto-active').parents('.panel-collapse').removeClass('in');
    //         $('a.auto-active').parents('li').removeClass('active');
    //         // debugger;
    //         var hash = !!window.location.hash ? window.location.hash : '#';
    //         $('a.auto-active[href="' + hash + '"]').addClass('active');
    //         $('a.auto-active[href="' + hash + '"]').parents('.panel-collapse').addClass('in');
    //         $('a.auto-active[href="' + hash + '"]').parents('li').addClass('active');
    //     }

    // });

    // debugger;
    Sandbox.eventSubscribe('global:content:render', function (options) {
        // debugger;
        if (_.isArray(options))
            _(options).each(function (option) {
                renderFn(option);
            });
        else
            renderFn(options);
        // refresh links (make them active)
        // Sandbox.eventNotify('global:menu:set-active');
    });

    $(window).on('hashchange', function () {
        // set page name
        var fragment = Backbone.history.getFragment();
        var _hashTags = fragment.replace(/#!\/|#|!\//g, '').split('/');
        $('body').attr('class', "MPWSPage");
        if (_hashTags && _hashTags[0])
            $('body').addClass("Page" + _hashTags[0].ucWords());

        // set active links
        // Sandbox.eventNotify('global:menu:set-active');

        // notify all subscribers that we have changed our route
        Sandbox.eventNotify('global:route', fragment);

        // save location
        if (fragment !== "!/signout" && fragment !== "!/signin")
            Cache.saveInLocalStorage("location", fragment);
    });

    var routes = {
        "": "start",
        "!/": "index",
        "!/signin": "signin",
        "!/signout": "signout"
    };

    var Router = Backbone.Router.extend({
        routes: routes,

        urls: _(routes).invert(),

        start: function () {
            if (location.pathname !== '/') {
                window.location.href = '/';
            }
            Sandbox.eventNotify('global:page:index', '');
        },
        index: function () {
            Sandbox.eventNotify('global:page:index', '');
        },
        signin: function () {
            // debugger;
            Sandbox.eventNotify('global:page:signin', 'signin');
        },
        signout: function () {
            // debugger;
            Auth.signout(function () {
                Sandbox.eventNotify('global:page:signout', 'signout');
            });
        }
    });

    APP.instances.root = new Router();
    var $dfd = $.Deferred();
    var pluginList = APP.getPluginRoutersToDownload();
    var pluginNames = _(pluginList).map(function (pluginListItem) {
        return pluginListItem.match(/^plugin\/(\w+)\//)[1];
    });
    // initialize plugins
    $dfd.done(function () {
        // start HTML5 History push
        // console.log('LOADER COMPLETE');
        // debugger;
        Backbone.history.start();
        // notify all that loader completed its tasks
        Sandbox.eventNotify('global:loader:complete');
        Backbone.trigger('global:loader:complete');
        // return Site;
        $(window).trigger('hashchange');
        // get auth status
        // Auth.getStatus();
        // set completion state
        APP.isCompleted = true;
    });

    var addPliginInstanceFn = function (pluginClass, key, preInitFn, totalPluginCount) {
        var initFn = function () {
            // console.log('calling: initFn for key ' + key + ' ||| instances count: ' + Object.getOwnPropertyNames(APP.instances).length);
            if (_.isFunction(pluginClass)) {
                APP.instances[key] = new pluginClass();
            } else {
                APP.instances[key] = pluginClass;
            }
            Backbone.trigger('appinstance:added', key, APP.instances[key], APP.instances);
            var _loadedPlugins = _.omit(APP.instances, 'CustomerRouter');
            // console.log('totalPluginCount: ' + totalPluginCount);
            if (Object.getOwnPropertyNames(_loadedPlugins).length === totalPluginCount) {
                // console.log('!!!!!!!!!!!! ALL PLUGINS READY !!!!!!!');
                $dfd.resolve();
            }
        };
        // console.log('preInitFn:');
        // console.log(preInitFn);
        if (_.isFunction(preInitFn)) {
            // console.log('calling: preInitFn for key ' + key);
            preInitFn(initFn);
        } else {
            // console.log('regular plugin init for key ' + key);
            initFn();
        }
    }

    var releasePluginsFn = function () {
        // console.log(pluginList);
        require(pluginList, function () {
            var _pluginsObjects = [].slice.call(arguments);
            _(_pluginsObjects).each(function (pluginClass, key) {
                // console.log('calling: addPliginInstanceFn for key [[[[[' + pluginNames[key]);
                // console.log(pluginClass);
                // console.log('=====================');
                addPliginInstanceFn(pluginClass, pluginNames[key], pluginClass && pluginClass.preload, _pluginsObjects.length);
            });
        });
    }

    require([APP.config.ROUTER], function (CustomerRouter) {
        if (_.isFunction(CustomerRouter)) {
            var customerRouter = new CustomerRouter();
            APP.instances['CustomerRouter'] = customerRouter;
        }
        if (CustomerRouter && _.isFunction(CustomerRouter.releasePlugins)) {
            CustomerRouter.releasePlugins(releasePluginsFn);
        } else {
            releasePluginsFn();
        }
    });

    APP.getCustomer = function () {
        return APP.instances['CustomerRouter'];
    };

    APP.getRequireJS = function (options) {
        // deep copy
        var defaultConfig = JSON.parse(JSON.stringify(APP.getRJConfig()));
        return require.config(Object.deepExtend(defaultConfig, options));
    };
});
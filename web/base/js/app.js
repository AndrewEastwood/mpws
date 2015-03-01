(function () {

    var config = JSON.parse(JSON.stringify(MPWS));

    var APP = {
        config: config,
        instances: {},
        hasPlugin: function (pluginName) {
            return _(config.PLUGINS).indexOf(pluginName) >= 0;
        },
        getAuthLink: function (options) {
            throw "Implment function getAuthLink";
        },
        getApiLink: function (options) {
            throw "Implment function getApiLink";
        },
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

    var startupModules = [
        'sandbox',
        'jquery',
        'underscore',
        'backbone',
        'auth',
        'jsurl',
        'cachejs',
        'handlebars-helpers',
        // localizations
        'vendors/moment/locale/uk',
        'vendors/select2/select2_locale_uk'
    ];

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

    require(startupModules, function (Sandbox, $, _, Backbone, Auth, JSUrl, Cache) {

        // APP.commonElements = $('div[name^="Common"]:not(:has(*))');
        Backbone.Model.prototype.isEmpty = function () {
            return _.isEmpty(this.attributes);
        }
        _.extend(APP, Backbone.Events);
        APP.Sandbox = Sandbox;

        APP.getApiLink = function (extraOptions /* or args */) {
            var _url = new JSUrl('/api/');
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
            var _url = new JSUrl('/api/system/auth/');
            return _url.toString();
        }
        APP.getUploadUrl = function (extraOptions) {
            var _url = new JSUrl('/upload/');
            // _url.query.token = APP.config.TOKEN;
            if (!_.isEmpty(extraOptions))
                _(extraOptions).each(function (v, k) {
                    _url.query[k] = !!v ? v : "";
                });
            return _url.toString();
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
        }

        var updatePageBodyClassNameFn = function () {
            // set page name
            // debugger
            var fragment = Backbone.history.getFragment();
            var _hashTags = fragment && fragment.replace(/#!\/|#|!\//g, '').split('/');
            $('body').attr('class', "MPWSPage");
            if (_hashTags && _hashTags[0]) {
                $('body').addClass("Page" + _hashTags[0].ucWords());
            }
        }

        $(document).ajaxComplete(function (event, jqXHR, ajaxOptions) {
            // debugger
            var response = jqXHR.responseText;
            try {
                response = JSON.parse(jqXHR.responseText);
            } catch (exception) {
                console.log(exception);
            }
            Sandbox.eventNotify("global:ajax:response", {
                response: response,
                isAuthRequest: new RegExp('^' + APP.getAuthLink()).test(ajaxOptions.url)
            });
        });

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

        // debugger;
        Sandbox.eventSubscribe('global:content:render', function (options) {
            // debugger;
            if (_.isArray(options)) {
                _(options).each(function (option) {
                    renderFn(option);
                });
            } else {
                renderFn(options);
            }
        });

        $(window).on('hashchange', function () {
            updatePageBodyClassNameFn();
            // notify all subscribers that we have changed our route
            var fragment = Backbone.history.getFragment();
            Sandbox.eventNotify('global:route', fragment);
            // save location
            if (fragment !== "!/signout" && fragment !== "!/signin") {
                Cache.saveInLocalStorage("location", fragment);
            }
        });

        var routes = {
            "": "index",
            "!/": "index",
            "!/signin": "signin",
            "!/signout": "signout"
        };

        var Router = Backbone.Router.extend({
            routes: routes,

            urls: _(routes).invert(),

            index: function () {
                if (location.pathname !== '/') {
                    window.location.href = '/';
                }
                Sandbox.eventNotify('global:page:index', '');
            },
            signin: function () {
                Sandbox.eventNotify('global:page:signin', 'signin');
            },
            signout: function () {
                // debugger;
                Auth.signout(function () {
                    Sandbox.eventNotify('global:page:signout', 'signout');
                });
            }
        });

        var root = new Router();
        var $dfd = $.Deferred();

        // initialize plugins
        $dfd.done(function () {
            // start HTML5 History push
            APP.instances.root = root;
            Backbone.history.start({hashChange: true});
            // notify all that loader completed its tasks
            Sandbox.eventNotify('global:loader:complete');
            Backbone.trigger('global:loader:complete');
            updatePageBodyClassNameFn();
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
                // debugger
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

        // load plugins
        var releasePluginsFn = function () {
            var pluginList = [];
            // include site file
            for (var key in config.PLUGINS) {
                pluginList.push('plugins/' + config.PLUGINS[key] + '/' + (config.ISTOOLBOX ? 'toolbox' : 'site') + '/js/router');
            }
            // return modules;
            // console.log(pluginList);
            var pluginNames = _(pluginList).map(function (pluginListItem) {
                return pluginListItem.match(/^plugins\/(\w+)\//)[1];
            });

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

        // load customer
        require(['customers/' + config.CUSTOMER + '/js/router'], function (CustomerRouter) {
            if (_.isFunction(CustomerRouter)) {
                var customerRouter = new CustomerRouter();
                APP.instances.CustomerRouter = customerRouter;
            }
            if (CustomerRouter && _.isFunction(CustomerRouter.releasePlugins)) {
                CustomerRouter.releasePlugins(releasePluginsFn);
            } else {
                releasePluginsFn();
            }
        });

        APP.getCustomer = function () {
            return APP.instances.CustomerRouter;
        };

    });
    
    this.APP = APP;
})();
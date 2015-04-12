(function (module) {

    var config = JSON.parse(JSON.stringify(MPWS));

    config.app = {
        staticUrl: '/static_/',
        baseStaticUrl: '/static_/base/',
        customerStaticUrl: '/static_/customers/' + config.CUSTOMER
    };

    var pluginsConfig = {};

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
        'asyncjs',
        'handlebars-helpers',
        'handlebars-partials',
        // localizations
        'vendors/moment/locale/uk',
        'vendors/select2/select2_locale_uk',
        'bootstrap'
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

    // debugger
    this.APP = APP;

    require(startupModules, function (Sandbox, $, _, Backbone, Auth, JSUrl, Cache, Async) {

        // APP.commonElements = $('div[name^="Common"]:not(:has(*))');
        Backbone.Model.prototype.isEmpty = function () {
            return _.isEmpty(this.attributes);
        }
        _.extend(APP, Backbone.Events);
        APP.Sandbox = Sandbox;

        APP.getApiLink = function (extraOptions /* or args */) {
            var _url = new JSUrl('/api/'),
                args = [].slice.call(arguments),
                pathItems = [];

            _(args).each(function (item) {
                if (_.isString(item) || _.isNumber(item)) {
                    pathItems.push(item);
                    return;
                }
                if (_.isArray(item)) {
                    return;
                }
                if (_.isObject(item)) {
                    _(item).each(function (v, k) {
                        _url.query[k] = !!v ? v : "";
                    });
                }
            });


            // if (typeof extraOptions === "object") {
            //     // backward compatibility
            //     if (extraOptions.source && extraOptions.fn) {
            //         _url.path += extraOptions.source + '/' + extraOptions.fn + '/';
            //     }
            //     var queryItems = _.omit(extraOptions, 'source', 'fn');
            //     _(queryItems).each(function (v, k) {
            //         _url.query[k] = !!v ? v : "";
            //     });
            // } else {
                _url.path += pathItems.join('/') + '/';
            // }
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
        // var contentInjection = function (cnt, options) {

        //     if (!options || !options.name)
        //         return;

        //     if (!cnt || !cnt.length)
        //         return;

        //     // debugger;
        //     if (options.keepExisted) {
        //         // get element id that we want to replace
        //         var _elID = options.el.attr('id');
        //         // find existed elements
        //         var _items = cnt.find('#' + _elID);
        //         // do nothing when element exists
        //         if (_items.length > 0)
        //             return;
        //     }

        //     // debugger;
        //     if (options.replace) {
        //         // get element id that we want to replace
        //         var _elID = options.el.attr('id');
        //         if (_elID) {
        //             // find existed elements
        //             var _items = cnt.find('#' + _elID);
        //             // if there are some we do replace
        //             if (_items.length > 0)
        //                 _items.replaceWith(options.el);
        //             else // or just append as new one into container
        //                 cnt.append(options.el);
        //         }
        //     } else if (options.append)
        //         cnt.append(options.el);
        //     else if (options.prepend)
        //         cnt.prepend(options.el);
        //     else
        //         cnt.html(options.el);
        // }

        // var renderFn = function (targetName, el) {
        //     // debugger;
        //     var options = {};
        //     if (_.isString(targetName) && el) {
        //         options.name = targetName;
        //         options.el = el;
        //     } else {
        //         if (targetName && targetName.name && targetName.el) {
        //             options = targetName;
        //         }
        //     }
        //     // var tags = $(options.name);
        //     // if (tags.length) {
        //     //     $(targetName).each(function () {
        //     //         $(this).wrap('<div/>').parent().replaceWith(el);
        //     //     });
        //     //     return;
        //     // }
        //     if (!options || !options.name) {
        //         throw "Wrong arguments for renderFn";
        //         return;
        //     }
        //     // debugger;
        //     var $el = $('[name="' + options.name + '"]');
        //     if ($el.length === 0) {
        //         if (APP.config.ISDEV) {
        //             throw "Render Error: Unable to resolve element by name: " + options.name;
        //         }
        //     } else
        //         contentInjection($el, options);
        // }

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
            Backbone.trigger("global:ajax:response", {
                response: response,
                isAuthRequest: new RegExp('^' + APP.getAuthLink()).test(ajaxOptions.url)
            });
        });

        APP.Cache = Cache;

        // Sandbox message handler
        // $('body').on('click', '[data-action]', function (event) {
        //     // debugger;
        //     var _data = $(this).data() || {};
        //     _data.event = event;
        //     APP.Sandbox.eventNotify($(this).data('action'), _data);
        // });

        APP.setPageAttributes = function (attr) {
            if (!attr) return;
            // set title
            if (attr.title) {
                if (_.isArray(attr.title)) {
                    $('head title').text(attr.title.join(' - '));
                    $('head meta[property="og:title"]').attr('content', attr.title.join(' - '));
                }
                if (_.isString(attr.title)) {
                    $('head title').text(attr.title);
                    $('head meta[property="og:title"]').attr('content', attr.title);
                }
            }
            // set keywords
            if (attr.keywords) {
                $('head meta[name="keywords"]').attr('content', attr.keywords);
            }
            // set description
            if (attr.description) {
                $('head meta[name="description"]').attr('content', attr.description);
                $('head meta[property="og:description"]').attr('content', attr.description);
            }
            // set image
            if (attr.image) {
                $('head meta[name="image"]').attr('content', APP.config.URL_PUBLIC_HOMEPAGE + attr.image);
                $('head meta[property="og:image"]').attr('content', APP.config.URL_PUBLIC_HOMEPAGE + attr.image);
            }
            // set url
            if (attr.url) {
                $('head meta[name="url"]').attr('content', APP.config.URL_PUBLIC_HOMEPAGE + attr.url);
                $('head meta[property="og:url"]').attr('content', APP.config.URL_PUBLIC_HOMEPAGE + attr.url);
            }
            // set type
            if (attr.type) {
                $('head meta[name="type"]').attr('content', attr.type);
                $('head meta[property="og:type"]').attr('content', attr.type);
            }
        }

        // debugger;
        // APP.Sandbox.eventSubscribe('global:content:render', function (options) {
        //     // debugger;
        //     if (_.isArray(options)) {
        //         _(options).each(function (option) {
        //             renderFn(option);
        //         });
        //     } else {
        //         renderFn(options);
        //     }
        // });

        $(window).on('hashchange', function () {
            updatePageBodyClassNameFn();
            // notify all subscribers that we have changed our route
            var fragment = Backbone.history.getFragment();
            APP.Sandbox.eventNotify('global:route', fragment);
            // save location
            if (fragment !== "!/signout" && fragment !== "!/signin") {
                Cache.saveInLocalStorage("location", fragment);
            }
        });

        // var routes = {
        //     "": "index",
        //     "!/": "index",
        //     "!/signin": "signin",
        //     "!/signout": "signout"
        // };

        // var Router = Backbone.Router.extend({
        //     routes: routes,

        //     urls: _(routes).invert(),

        //     index: function () {
        //         if (location.pathname !== '/') {
        //             window.location.href = '/';
        //         }
        //         APP.Sandbox.eventNotify('global:page:index', '');
        //     },
        //     signin: function () {
        //         APP.Sandbox.eventNotify('global:page:signin', 'signin');
        //     },
        //     signout: function () {
        //         // debugger;
        //         Auth.signout(function () {
        //             APP.Sandbox.eventNotify('global:page:signout', 'signout');
        //         });
        //     }
        // });

        // var root = new Router();
        // var $dfd = $.Deferred();

        // initialize plugins
        // $dfd.done(function () {
        //     // start HTML5 History push
        //     APP.instances.root = root;
        //     // notify all that loader completed its tasks
        //     APP.Sandbox.eventNotify('global:loader:complete');
        //     Backbone.trigger('global:loader:complete');
        //     if (APP.instances.customer && APP.instances.customer instanceof Backbone.Router) {
        //         APP.instances.customer.trigger('app:ready');
        //     }
        //     Backbone.history.start({hashChange: true});
        //     updatePageBodyClassNameFn();
        // });

        // var addPliginInstanceFn = function (pluginClass, key, preInitFn, totalPluginCount) {
        //     var initFn = function () {
        //         // console.log('calling: initFn for key ' + key + ' ||| instances count: ' + Object.getOwnPropertyNames(APP.instances).length);
        //         // if (_.isFunction(pluginClass)) {
        //         //     APP.instances[key] = new pluginClass();
        //         // } else {
        //             APP.instances[key] = pluginClass;
        //         // }
        //         if (APP.instances[key].trigger) {
        //             APP.instances[key].trigger('created');
        //         }
        //         // Backbone.trigger('appinstance:added', key, APP.instances[key], APP.instances);
        //         var _loadedPlugins = _.omit(APP.instances, 'customer');
        //         // console.log('totalPluginCount: ' + totalPluginCount);
        //         // debugger
        //         if (Object.getOwnPropertyNames(_loadedPlugins).length === totalPluginCount) {
        //             // console.log('!!!!!!!!!!!! ALL PLUGINS ARE READY !!!!!!!');
        //             $dfd.resolve();
        //         }
        //     };
        //     // console.log('preInitFn:');
        //     // console.log(preInitFn);
        //     if (_.isFunction(preInitFn)) {
        //         // console.log('calling: preInitFn for key ' + key);
        //         preInitFn(initFn);
        //     } else {
        //         // console.log('regular plugin init for key ' + key);
        //         initFn();
        //     }
        // }

        // load plugins
        var releasePluginsFn = function () {
            var pluginList = [],
                pluginNames = [],
                pluginStack = [];
            // include site file
            for (var key in config.PLUGINS) {
                pluginList.push('plugins/' + config.PLUGINS[key] + '/' + (config.ISTOOLBOX ? 'toolbox' : 'site') + '/js/router');
            }
            // return modules;
            // console.log(pluginList);
            pluginNames = _(pluginList).map(function (pluginListItem) {
                return pluginListItem.match(/^plugins\/(\w+)\//)[1];
            });
            // debugger

            require(pluginList, function () {
                var _pluginsObjects = [].slice.call(arguments);
                _(_pluginsObjects).each(function (pluginClass, key) {
                    pluginStack.push(function (callback) {
                        // debugger
                        var name = pluginNames[key],
                            plugin = null;
                        if (_.isFunction(pluginClass)) {
                            plugin = new pluginClass(pluginsConfig[name] || {});
                            plugin.name = name;
                            APP.instances.customer.plugins[name] = plugin;
                            APP.instances[name] = plugin;
                            if (plugin.dfdInitialize) {
                                plugin.dfdInitialize(callback, pluginsConfig[name] || {});
                            } else {
                                // console.log('finished loading ' + name);
                                callback();
                            }
                        } else {
                            callback();
                            // console.log('finished loading ' + name);
                        }
                    });
                });

                Async.parallel(pluginStack, function (err) {
                    console.log('all pluginas are loaded');
                    if (err) throw err;
                    // start HTML5 History push
                    // APP.instances.root = root;
                    // notify all that loader completed its tasks
                    APP.Sandbox.eventNotify('global:loader:complete');
                    Backbone.trigger('global:loader:complete');
                    if (APP.instances.customer && APP.instances.customer instanceof Backbone.Router) {
                        APP.instances.customer.trigger('app:ready');
                    }
                    Backbone.history.start({hashChange: true});
                    updatePageBodyClassNameFn();

                    // init some handy actions
                    $('body').on('click', '.mpws-js-dropdown-toggle-inner', function () {
                        $(this).toggleClass('open');
                    });
                    $('body').on('click', '.mpws-js-insertable', function () {
                        var $forEl = $($(this).data('for'));
                        if ($forEl.length) {
                            $forEl.val($forEl.val() + ' ' + $(this).text());
                        }
                    });
                    _(['click', 'mouseover']).each(function (evType) {
                        $('body').on(evType, '.btn-group.mpws-js-logvalueintoel', function (event) {
                            // console.log(evType);
                            var $btn = evType === 'click' || evType === 'mouseout' ? $(this).find('input:checked') : $(event.target).parents('.btn').find('input'),
                                $forEl = $($(this).data('for'));
                            if ($btn.length && $forEl.length) {
                                $forEl.text($btn.data('dispval') || $btn.val());
                            }
                        });
                    })
                });

            });
        }

        // load customer
        require(['customers/' + config.CUSTOMER + '/js/router'], function (RouterCustomer) {
            if (_.isFunction(RouterCustomer)) {
                var customer = new RouterCustomer();
                APP.instances.customer = customer;
                customer.plugins = {};
            }
            if (customer && _.isFunction(customer.releasePlugins)) {
                customer.releasePlugins(releasePluginsFn);
            } else {
                releasePluginsFn();
            }
        });

        APP.getCustomer = function () {
            return APP.instances.customer;
        };

        APP.identity = function (v) {
            return v;
        };

        APP.getPlugin = function (key, options) {
            return APP.instances[key];
        };

        APP.configurePlugins = function (config) {
            pluginsConfig = config || {};
        };
        APP.getPluginOptions = function (name) {
            var plg = APP.getPlugin(name);
            return plg && plg.options || {};
        };
        APP.getPluginSettings = function (name) {
            var plg = APP.getPlugin(name);
            return plg && plg.settings || {};
        };

        // APP.injectHtml = function (targetName, el) {
        //     var proceed = false,
        //         customerRenderProxy = APP.instances.customer && APP.instances.customer.renderProxy || APP.identity;
        //     if (_.isArray(targetName)) {
        //         _(targetName).each(function (option) {
        //             if (!!customerRenderProxy(option)) {
        //                 renderFn(option);
        //             }
        //         });
        //     } else {
        //         if (!!customerRenderProxy(targetName, el)) {
        //             renderFn(targetName, el);
        //         }
        //     }
        // };

    });
    
})();